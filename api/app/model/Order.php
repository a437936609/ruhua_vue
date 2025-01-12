<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 9:06
 */

namespace app\model;


use app\model\UserCoupon as UserCouponModel;
use app\services\GzhDeliveryMessage;
use app\services\TokenService;
use app\services\TuiService;
use app\services\XcxMessage;
use ruhua\bases\BaseModel;
use app\model\User as UserModel;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\OrderException;
use think\facade\Db;
use think\facade\Log;
use think\model\concern\SoftDelete;
use think\Request;
use app\model\OrderLog as OrderLogModel;
use app\model\FxGoods as FxGoodsModel;
use app\model\OrderGoods as GoodsModel;

class Order extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden = ['delete_time'];


    public function getCreateTimeAttr($v)
    {
        return date("Y-m-d H:i:s",$v);
    }

    public function getPayTimeAttr($v)
    {
        return date("Y-m-d H:i:s",$v);
    }

    /**
     * 用户删除订单
     * @param $uid
     * @param $id
     * @return int
     */
    public static function deleteOrder($id, $uid)
    {
        $order = self::where(['order_id' => $id])->find();
        if ($order['user_id'] == $uid && $order['payment_state'] == 0) {
            $coupon_id=$order['coupon_id'];
            UserCouponModel::update(['status'=>0],['id'=>$coupon_id,'status'=>1]);
            $data['id']=$id;
            event('reduceStockAdd', $data);//扣除库存
            $order->delete(config('setting.soft_del'));   //这里是软删除
        } else {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 用户提交评价
     * @param $uid
     * @param $post
     * @return mixed
     * @throws
     */
    public static function setPj($uid, $post)
    {
        $where['user_id'] = $uid;
        $where['order_id'] = $post['id'];
        $order = self::where($where)->where(['state' => 1])->find();
        if (!$order) {
            return app('json')->fail('评价出现错误');
        }
        $orderGoods = new OrderGoods();
        $order_id = $order['order_id'];
        $goods_ids = $orderGoods->where('order_id', $order_id)->column('goods_id');
        $user=UserModel::find($uid);
        if (in_array($post['goods_id'], $goods_ids)) {
            $data['user_id'] = $where['user_id'];
            $data['rate'] = $post['rate'];
            $data['content'] = $post['content'];
            $data['order_id'] = $order_id;
            $data['video']=$post['video'];
            $data['goods_id'] = $post['goods_id'];
            $data['headpic'] = $user['headpic'];
            $data['nickname'] = base64_encode($user['nickname']);
            $data['imgs'] = implode(',',$post['imgs']);
            $data['create_time'] = time();
        }
        Db::startTrans();
        try {
            OrderGoods::where(['order_id' => $post['id'], 'user_id' => $uid, 'goods_id' => $post['goods_id']])->update(['state' => 2]);
            $status=OrderGoods::Where(['order_id' => $post['id'],'state'=>1])->find();
            if(!$status){
                $order->save(['state' => 2]);
            }
            Rate::create($data);;
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 确认收货
     * @param $id
     * @param $uid
     * @return mixed
     * @throws
     */
    public static function receive($id, $uid)
    {
        $where['shipment_state'] = 1;
        $where['payment_state'] = 1;
        $where['state'] = 0;
        $where['order_id'] = $id;
        $where['user_id'] = $uid;
        $order = self::where($where)->find();
        if (!$order) {
            return app('json')->fail('订单状态有误');
        }
        Db::startTrans();
        try {
            Log::error("确认收货");

            $res = $order->save(['state' => 1, 'shipment_state' => 2]);
            OrderGoods::where(['order_id' => $id, 'user_id' => $uid])->update(['state' => 1]);
            event('EndOrder', $order);//订单完成监听
            event("AddGoodsFxRecord", [$id, $uid]);//分销订阅
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new OrderException(['msg' => $e->getMessage()]);
        }
        if ($res) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }


    public static function hexiao($number,$uid)
    {
        $web_auth=UserModel::where('id',$uid)->value('web_auth_id');
        if($web_auth!=1){
            return app('json')->fail('无权限');
        }
        $order = self::where('yz_code', $number)->find();
        if (!$order) {
            return app('json')->fail('验证码错误');
        }
        if ($order['payment_state'] != 1) {
            return app('json')->fail('未支付的订单无法发货');
        }
        if ($order['shipment_state'] == 1 || $order['state']>0) {
            return app('json')->fail('已经验证过了');
        }
        Db::startTrans();
        try {
            $data['shipment_state'] = 1;
            $order->save($data);
            $save['order_id'] = $order['order_id'];
            $save['yz_code'] = $number;
            $save['type_name'] = '核销成功';
            $save['content'] =  '核销人ID：' . $uid;
            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => '核销失败'.$e->getMessage()]);
        }
    }


    /**
     * 申请退款
     * @param $uid
     * @param $post
     * @return mixed
     * @throws
     */
    public function tuikuan_approve($uid, $post)
    {
        $money=$this->getTuiMoney($uid,$post['order_id'],$post['goods_id']); 
        //if($money<=0){
        //    throw new OrderException(['msg' => '请勿重复提交']);
        //}
        if(!is_numeric($money)){
            return app('json')->fail($money);
        }
        $user = User::find($uid);
        $order = self::where('order_id',$post['order_id'])->find();
        $data['order_id'] = $order['order_id'];
        $data['nickname'] = base64_encode($user['nickname']);
        $data['order_num'] = $order['order_num'];
        $data['money'] =$money;
        $data['because'] = $post['radio'] ?: "";
        $data['message'] = $post['content'] ?: "";
        $data['goods_id'] = $post['goods_id'] ?$post['goods_id']:0;
        $data['ip'] = (new Request())->ip(); //买家IP
        if( $data['goods_id'] == 0){
            $goodsid= OrderGoods::where('order_id',$post['order_id'])->field('goods_id')->find();
            $data['goods_id']=$goodsid['goods_id'];
        }
        Db::startTrans();

        try {
            if($data['goods_id']==0){
                $order->save(['state' => -1]);
            }else{
                $order->save(['state' => -1]);
                $goodsWhere['goods_id']= $data['goods_id'];
            }

            $goodsWhere['goods_id']= $data['goods_id'];
            $goodsWhere['order_id']=$post['order_id'];
            $goodsWhere['user_id']=$uid;
            OrderGoods::where($goodsWhere)->update(['state' => -1]);
            $tui = Tui::create($data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new OrderException(['msg' => '更新失败']);
        }
        if ($tui) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 获取退款金额
     * @param $uid
     * @param $order_id
     * @param $goods_id
     * @return int|mixed
     */
    public function getTuiMoney($uid,$order_id,$goods_id){
        $tui_state = Tui::where(['order_id' => $order_id,'goods_id'=>$goods_id, 'status' => ['>', 0]])->find();
        if ($tui_state) {
            return '已存在正在处理的退款申请';
        }
        $where['user_id'] = $uid;
        $where['order_id'] = $order_id;
        $where['payment_state'] = 1;
        //$where['shipment_state'] = 0;
        $where['state'] = 0;
        $order = self::with('ordergoods')->where($where)->find();
        if (!$order) {
            return '订单状态有误';
        }
        if($order['item_id']){
            $pt=PtItem::where('id',$order['item_id'])->find();
            if($pt['state']!=2||$pt['state']!=-1){
                return '拼团中，请等待拼团结束';
            }
        }
        $total_money=0;
        if($goods_id==0){
            $apply_money=Tui::where('order_id',$order_id)->sum('money');
            $total_money=$order['order_money']-$apply_money;
        }else{
            foreach ($order['order_goods'] as $k => $v) {
                if ($goods_id == $v['goods_id']) {
                    $total_money = $v['total_price'];
                }
            }
            if ($order['coupon_id']) {
                $coupon = UserCoupon::where('id', $order['coupon_id'])->find();
                if ($coupon && ($order['order_money'] - $total_money) < $coupon['full']) {
                    $total_money = $total_money - $coupon['reduce'];
                }
            }
        }
        return $total_money;
    }

    /**
     * 修改发货状态
     * @param $param
     * @return bool
     * @throws
     */
    public static function editCourier($param)
    {
        $pay = self::where('order_id', $param['order_id'])->value('payment_state');
        $user_id=self::where('order_id', $param['order_id'])->value('user_id');
        $payment_type=self::where('order_id',$param['order_id'])->value('payment_type');
        $order=self::with('ordergoods')->where('order_id', $param['order_id'])->find();
        if ($pay != 1) {
            return app('json')->fail('未支付的订单无法发货');
        }
        Db::startTrans();
        try {
            $courier        =       [];
            $courier['courier'] = $param['courier'];
            $courier['courier_num'] = $param['courier_num'];
            $courier['shipment_state'] = 1;
            self::where('order_id', $param['order_id'])->update($courier);
            $save           =       [];
            $save['order_id'] = $param['order_id'];
            $save['type_name'] = '录入快递单号';
            $save['content'] = $param['courier'] . '，' . $param['courier_num'];
            OrderLogModel::create($save);
            
            if($payment_type=='xcx'){
                // (new XcxMessage())->send_order_massage($param['order_id']);
            }
            else{
                // (new GzhDeliveryMessage())->sendDeliveryMessage($order,6,$user_id);
            }
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => '快递信息录入失败']);
        }
    }

    /**
     * 修改发货状态
     * @param $param
     * @return bool
     * @throws
     */
    public static function editCourierByPrepayId($param)
    {
        $order_id = self::where('prepay_id', $param['prepay_id'])->value('order_id');
        if(!$order_id){
            return false;
        }
        $param['order_id']          =           $order_id;
        $pay                        =           self::where('order_id', $param['order_id'])->value('payment_state');
        if ($pay != 1) {
            return false;
        }
        Db::startTrans();
        try {
            $courier                        =           [];
            $courier['courier']             =           $param['courier'];
            $courier['courier_num']         =           $param['courier_num'];
            $courier['courier_time']        =           strtotime($param['courier_time']);
            $courier['shipment_state']      =           1;
            $courier['drive_type']          =           "快递";
            self::where('order_id', $param['order_id'])->update($courier);

            $save                           =           [];
            $save['order_id']               =           $param['order_id'];
            $save['type_name']              =           '录入快递单号';
            $save['content']                =           $param['courier'] . '，' . $param['courier_num'];
            $save['create_time']            =           strtotime($param['courier_time']);
            OrderLogModel::create($save);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Db::rollback();// 回滚事务
            return false;
        }
    }

    /**
     * 添加订单备注信息
     * @param $param
     * @return mixed
     * @throws
     */
    public static function up_remark_model($param)
    {
        Db::startTrans();
        try {
            self::where('order_id', $param['order_id'])->update(['remark_one' => $param['remark']]);
            $save['order_id'] = $param['order_id'];
            $save['type_name'] = '添加备注';
            $save['content'] = $param['remark'];
            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
//            return true;
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => '备注信息录入失败']);
        }
    }

    /**
     * 修改订单价格
     * @param $param
     * @return mixed
     * @throws
     */
    public static function edit_price_model($param)
    {
        Db::startTrans();
        try {
            $order = self::where('order_id', $param['order_id'])->find();
            $order['edit_money'] = $param['price'];
            $order['order_money'] = $order['order_money'] + $order['edit_money'];
            $order['order_num'] = $order['order_num'].'B';
            if ($order['order_money'] <= 0) {
                return app('json')->fail('价格错误');
            }
            $order->save();
            $save['order_id'] = $param['order_id'];
            $save['type_name'] = '修改订单金额';
            $save['content'] = $param['price'];
            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }

    /**
     * 微信退款
     * @param $id
     * @return mixed
     */
    public static function TuiMoney($id)
    {
        $res = Tui::where('id',$id)->where('status',0)->find();
        if (!$res) {
            return app('json')->fail('退款订单异常');
        }
        $tui = new TuiService($id);
        return $tui->pay();
    }

    /**
     * 退款驳回
     * @param $id
     * @param $msg
     * @return mixed
     * @throws
     */
    public static function TuiBoHui($id, $msg)
    {
        Log::error('驳回');
        Log::error($id);
        Log::error($msg);
        //这里的id是tui的id
        $tui = Tui::find($id);
        if (!$tui) {
            return app('json')->fail('id错误');
        }
        $order_id = $tui['order_id'];
        $aid = TokenService::getCurrentAid();
        $data['aid'] = $aid;
        $data['remark'] = $msg;
        $data['status'] = -1;
        Db::startTrans();
        try {
            $tui->save($data);
            $res = self::where(['order_id' => $order_id, 'state' => -1])->update(['state' => 0]);
            Db::commit();
            if (!$res) {
                return app('json')->fail();
            }
            return app('json')->success();
//            return $res ? 1 : 0;
        } catch (Exception $e) {
            Db::rollback();
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }

    /**
     * 关闭订单
     */
    public static function closeOrder()
    {
        $where['state'] = 0;
        $where['payment_state'] = 0;
        $time = time() - 15 * 60; //1关闭15分钟未支付的订单
        $res =  self::where($where)->whereTime('create_time', '<', $time)->select();
        Log::error('自动关闭订单-时间');
        Log::error($res->toArray());
        foreach ($res->toArray() as $k=>$v){
            if($v['coupon_id']!=0){
                $coupon=UserCouponModel::where('coupon_id',$v['coupon_id'])->where('user_id',$v['user_id'])->update(['status'=>0]); //优惠券退回
            }
            Log::error('自动关闭订单-库存退货');
            event('reduceStockAdd', $v);//扣除库存
        }
        self::where($where)->whereTime('create_time', '<', $time)->update(['state' => -3]);
    }

    /**
     * 获取订单指定字段
     * @param $id
     * @param $field
     * @return mixed
     */
    public static function getOrderAttr($id, $field)
    {
        $value = self::where('order_id', $id)->value($field);
        if (!$value) {
            Log::channel('msgLog')->write("order_id为$id 的订单获取字段失败");
            throw new BaseException(['获取字段失败']);
        }
        return $value;
    }


    //关联规格模型
    public function sku()
    {
        return $this->hasMany('GoodsSku', 'goods_id', 'goods_id');
    }

    public function users()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }

    //关联订单商品模型
    public function ordergoods()
    {
        return $this->hasMany('OrderGoods', 'order_id', 'order_id');
    }

    //关联模型
    public function rate()
    {
        return $this->hasMany('Rate', 'order_id', 'order_id');
    }

    //关联图片
    public function imgs()
    {
        return $this->belongsTo('Image', 'goods_picture', 'id')->field('id,url');
    }

    public function setImgsAttr($v)
    {
        return $v['url'];
    }

    //计算分销佣金总和
    public static function countmoney($data)
    {
        $money=0;
        for($i=0;$i<count($data);$i++){
            $money+=FxGoodsModel::where('goods_id',$data[$i]['goods_id'])->value('price');
        }
        return $money;

    }

    public function invitecode()
    {
        return $this->belongsTo('User','invite_code','invite_code');
    }

    //订单检查
    public static function orderInspect($order_num){
        $order = self::where('order_num',$order_num)->find();
        $order_goods= OrderGoods::selectId($order['order_id']);

        if(!isset($order_goods['goods_id']))
            throw new BaseException(['msg'=>'订单错误']);
        //拼团判断
        if($order['item_id']>0){
            if($order['yz_code']>0){
                Log::error('拼团订单使用了优惠券');
            }
            Log::error('拼团订单');
            $pt = PtGoods::getPtGoods($order_goods['goods_id']);
            if(!$pt['price'])
                throw new BaseException(['msg'=>'该拼团不存在']);
            if($pt['price']==$order['goods_money']){
                Log::error('拼团订单验证正确');
                return 1;
            }
           // return app('json')->go($pt);
        }

        //vip订单 需要服务器上面测
        $goods = Goods::selectId($order_goods['goods_id']);
        if($goods['vip_price']>0){
            Log::error('vip订单');
            if($pt['price']==$goods['vip_price']){
                Log::error('vip订单验证正确');
                return 1;
            }
        }
        //折扣订单
        $dis_goods = DiscountGoods::getDiscountGoods($order_goods['goods_id']);
        if(isset($dis_goods['goods_id'])){
            Log::error('折扣订单');
            if($pt['price']==$dis_goods['discount_price']){
                Log::error('折扣订单验证正确');
                return 1;
            }
        }
        return app('json')->go($dis_goods);
        //优惠券判断
        if($order['yz_code']>0){
            Log::error('使用了优惠券');
        }
        //折扣订单


        return app('json')->go($order);
    }

    //关联订单日志
    public function orderlog()
    {
        return $this->hasMany('OrderLog', 'order_id', 'order_id');
    }

    /**
     * @param $payment_state    支付状态 0未付款  1已付款
     * @param $shipment_state   运输（验证）状态  0待发货 1已发货 2已收货
     * @param $state            0未完成 1已完成 2已评价 -1退款中 -2已退款-3关闭订单
     */
    public function export_excel_status($payment_state, $shipment_state, $state)
    {
        //未付款》关闭订单
        if($payment_state == 0)
        {
            $stateName = '未付款';
            if($state == -3){
                $stateName = '订单关闭';
            }
            return $stateName;
        }
        //已付款
        if($payment_state == 1)
        {
            $stateName = '已付款';
            if($state == -2){
                $stateName = '已退款';
                return $stateName;
            }
            if($shipment_state == 1){
                $stateName = '已发货';
                return $stateName;
            }
            return $stateName;
        }
        switch ($state){}
    }

    /**
     * 修改订单地址
     * @param $param
     * @return mixed
     * @throws
     */
    public static function edit_address_model($param)
    {
        Db::startTrans();
        try {
            $order = self::where('order_id', $param['order_id'])->find();

            $order['receiver_name']     = $param['fullname'];
            $order['receiver_mobile']   = $param['mobile'];
            $order['receiver_city']     = $param['city'];
            $order['receiver_address']  = $param['address'];
            $order->save();

            $save['order_id'] = $param['order_id'];
            $save['type_name'] = '修改订单地址';
            $save['content'] = $param['fullname'].$param['mobile'].$param['city'].$param['address'];
            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }
}