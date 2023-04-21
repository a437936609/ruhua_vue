<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/1 0001
 * Time: 9:49
 */

namespace app\model;


use app\model\FxManual as FxManualModel;
use app\services\CpyPayUserService;
use ruhua\bases\BaseCommon;
use ruhua\bases\BaseModel;
use function PHPSTORM_META\elementType;
use think\facade\Log;


class FxRecord extends BaseModel
{
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,headpic,mobile');
    }

    public function agent()
    {
        return $this->belongsTo('User', 'agent_id', 'id')
            ->field('id,openid,openid_gzh,openid_app,nickname,user_name,unionid,headpic,mobile');
    }

    /**
     * 用户查看分销收入统计
     * @param $agent_id
     * @return mixed
     */
    public static function getFxMoneyData($agent_id)
    {
       /* $data['money'] = self::where('agent_id', $agent_id)->where('status','<', 2)->sum('money');
        $data['yesterday_money'] = self::where('agent_id', $agent_id)
            ->whereDay('create_time', 'yesterday')
            ->sum('money');
        $data['already_money']=self::where('status',2)->where('agent_id',$agent_id)->sum('money');*/
        $money_all=self::getFxMoneyAll($agent_id);//获取全部金额
        $money_ok=self::getFxMoneyOk($agent_id);//获取已提现金额
        $money_now=self::getFxMoneyNow($agent_id);//获取提现中金额
        $data['money'] = round($money_all-$money_ok-$money_now,2);//可提现的金额
        $data['yesterday_money'] = round(self::where('agent_id', $agent_id)    //昨天的金额
            ->whereDay('create_time', 'yesterday')
            ->sum('money'),2);

        $data['already_money']= round($money_ok,2);//已提现的金额
        $data['now_money'] = round($money_now,2);//提现中的金额
        $data['all_money'] = round($money_all,2);//总金额
        return app('json')->success($data);
    }
    /**
     * 获取总金额
     */
    public static function getFxMoneyAll($agent_id)
    {
        $data = self::where('agent_id', $agent_id)->sum('money');
        return $data;
    }

    /**
     * 获取已提现金额
     */
    public static function getFxMoneyOk($agent_id)
    {
        $data = self::where('agent_id', $agent_id)->where('status',2)->sum('money');
        return $data;
    }
    /**
     * 获取提现中的金额
     */
    public static function getFxMoneyNow($agent_id)
    {
        $data = self::where('agent_id', $agent_id)->where('status',1)->sum('money');
        return $data;
    }
    /**
     * 用户获取申请记录
     * @param $agent_id
     * @return mixed
     */
    public static function userGetRecord($agent_id)
    {
        //$data = self::with(['user'])->where('agent_id', $agent_id)->select();
        $data=FxManualModel::where('user_id',$agent_id)->order('update_time desc')->select();
        return app('json')->success($data);
    }

    /**
     * 用户获取申请记录
     * @param $agent_id
     * @return mixed
     */
    public static function userGetRecordId($id)
    {
        //$data = self::with(['user'])->where('agent_id', $agent_id)->select();
        $data=FxManualModel::where('id',$id)->find();
        return app('json')->success($data);
    }


    /**
     * 用户获取分销记录
     * @param $agent_id
     * @return mixed
     */
    public static function userRecord($agent_id)
    {
        $data = self::with(['user'])->where('agent_id', $agent_id)->select();

        return app('json')->success($data);
    }


    /**
     * 查看排名
     * @param $agent_id
     * @return mixed
     */
    public static function getFxRank($agent_id)
    {
        $data['rank'] = self::with(['agent'])
            ->field('id,agent_id,sum(money) as total_money')
            ->group('agent_id')
            ->order('total_money', 'desc')
            ->select()
            ->toArray();
        foreach ($data['rank'] as $k => $v) {
            $data['self'] = ['rank' => 0, 'total_money' => 0];
            if ($agent_id == $v['agent_id']) {
                $data['self'] = ['rank' => $k + 1, 'total_money' => $v['total_money']];
            }
        }
        return app('json')->success($data);
    }

    /**
     * cms获取分销记录
     * @return mixed
     */
    public static function getRecord()
    {
        $post=input('get.');
        $page=-1;
        $num=0;
        if(isset($post['page'])&&isset($post['num'])){
            $page=$post['page'];
            $num=$post['num'];

        }
        if($page<0)
            $data = self::with(['agent', 'user'])->order('id','desc')->select();
        else
            $data = self::with(['agent', 'user'])->order('id','desc')->limit($page*$num,$num)->select();
        return app('json')->success($data);
    }

    /**
     * 创建商品分销提现记录
     * @param $oid
     * @param $uid
     * @return mixed
     */
    public static function addRecord($oid, $uid)
    {
        $state = app('system')->getValue('fx_status');//是否开启分销
        $goods_status = app('system')->getValue('goods_fx_status');
        if ($state == 0 || $goods_status != 1) {
            return app('json')->fail();
        }
        Log::error("判断分销模式");
        $order = Order::with('ordergoods')->where('order_id', $oid)->find();
        //1推荐人分销2人人分销
        if ($state == 1) {
            $pid = FxAgent::where('invite_code', $order['invite_code'])->value('user_id');
        } else if ($state == 2) {
            $pid = User::where('invite_code', $order['invite_code'])->value('id');
        } else {
            return app('json')->fail();
        }
        if (!$pid) {
            return app('json')->fail();
        }
        //官方测试分销自动返佣的功能，如果忘记删了，自行删除一下注释
        //不允许给自己返佣
       /* if ($uid == $pid) {
            return app('json')->fail();
        }*/
        Log::error("计算佣金");
        $fxgoods=new FxGoods;
        foreach ($order['ordergoods'] as $k => $v) {
            $goods = $fxgoods->where('goods_id', $v['goods_id'])->find();
            if ($goods) {
                $data = self::setData($pid, $oid, $uid, $goods['price'], 2);
                $record=self::create($data);
                Log::error("判断是否自动提现");
                $royalty = app('system')->getValue('fx_royalty');//是否开启自动提现功能
                if($royalty==1){
                    self::autoTx($record['id'],'分销佣金');
                }
            }
        }
    }

    /**
     * 创建VIP提现记录
     * @param $oid
     * @param $uid
     * @return mixed
     */
    public static function addVipRecord($oid, $uid)
    {
        $state = app('system')->getValue('fx_status');//是否开启分销
        if ($state == 0) {
            return app('json')->fail();
        }
        $goods_status = app('system')->getValue('vip_fx_status');
        if ($goods_status != 1) {
            return app('json')->fail();
        }
        $res = FxBind::where('user_id', $uid)->where('is_bind', 1)->find();//有没有上线
        if (!$res) {
            return app('json')->fail();
        }

        $price = app('system')->getValue('agent_tc');
        $data = self::setData($res['pid'], $oid, $uid, $price, 1);
        $record=self::create($data);
        $royalty = app('system')->getValue('fx_royalty');//是否开启自动提现功能
        if($royalty==1){
            self::autoTx($record['id'],'代理商分销提成');
        }
    }

    private static function setData($agent_id, $oid, $uid, $price, $type)
    {
        $data['order_num'] = (new BaseCommon())->makeOrderNum();
        $data['order_id'] = $oid;
        $data['user_id'] = $uid;
        $data['agent_id'] = $agent_id;
        $data['money'] = $price;
        $data['status'] = 0;
        $data['type'] = $type;
        return $data;
    }

    /**
     * 用户手动申请提现
     * @param $uid
     * @param $ids
     * @return mixed
     */
    public static function userApplyTx($uid,$ids,$data){
        $royalty = app('system')->getValue('fx_royalty');//是否开启自动提现功能
        if($royalty!=0){
            return app('json')->fail('未开启手动提现');
        }
        /*$money=0;
        $rid='';
        foreach ($ids as $k=>$v){
            $list=self::where('id',$v)->where('status',0)->where('agent_id',$uid)->find();
            if($list){
                $rid.=$v.",";
                $money+=$list['money'];
            }

            self::where('id',$v)->where('status',0)->where('agent_id',$uid)->update(['status'=>1]);
        }*/
        $data['user_id']=$uid;
        $res=FxManualModel::create($data);
       // if($money!=0){
       //     $data['user_id']=$uid;
           // $data['money']=$money;
            //$data['rid']=$rid;
          // $res=FxManualModel::create($data);
          //  FxManualModel::create(['user_id'=>$uid,'money'=>$money,'rid'=>$rid]);
       // }
        return app('json')->go($res);
    }

    /**
     * cms打款成功
     * @param $id
     * @return mixed
     */
    public static function agreeTxApply($id,$card)
    {
        self::where(['id'=>$id,'status'=>1])->update(['status'=>2,'card'=>$card]);
        return app('json')->success();
    }

    public static function refuseTxApply($id)
    {
        self::where(['id'=>$id,'status'=>1])->update(['status'=>0]);
        return app('json')->success();

    }

    /**
     * 自动提现
     * @param $id
     * @param $msg
     * @return string|\think\response\Json
     */
    public static function autoTx($id,$msg){
        Log::error("进入自动提现");
        $record=new self();
        $res=$record->with('agent')->where('id',$id)->find();
        $pay_num=$record->where('agent_id',$id)->whereTime('pay_time','today')->count();
        if($pay_num>8){
            $res->save(['cpy_pay_status'=>3]);
            return '';
        }
        $data['order_sn']=$res['order_num'];
        $data['openid']=$res['agent']['openid'];
        $data['truename']=$res['agent']['nickname']?$res['agent']['nickname']:'test';
        $data['money']=$res['money'];	//最小3角
        $data['desc']=$msg;


        $pay=new CpyPayUserService();
        $cpyPay=$pay->transfer($data);
        Log::error("准备自动提现".json_encode($cpyPay,JSON_UNESCAPED_UNICODE));
        if($cpyPay['code']){
            $res->save(['status'=>2,'cpy_pay_status'=>1,'wx_pay'=>$cpyPay['data']['payment_no'],'pay_time'=>$cpyPay['data']['payment_time']]);
        }else{
            $res->save(['cpy_pay_status'=>2]);
            Log::record($cpyPay,'error');
        }
        Log::error("自动提现完成");
        return app('json')->success();
    }

    /**
     * 统计未打款的，统一打款
     * @return mixed
     */
    public function taskTx(){
        $royalty = app('system')->getValue('fx_royalty');//是否开启自动提现功能
        if($royalty!=1){
            return app('json')->fail('未开启自动提现');
        }
        $record=new self();
        $res=$record->with('agent')->where('cpy_pay_status',3)->select();
        $arr=[];
        foreach ($res as $k=>$v){
            if(!$arr){
                $data['ids']=[];
                array_push($data['ids'],$v['id']);
                $data['order_sn']=$v['order_num'];
                $data['agent_id']=$v['agent_id'];
                $data['openid']=$v['agent']['openid'];
                $data['truename']=$v['agent']['nickname'];
                $data['money']=$v['money'];	//最小3角
                $data['desc']='代理商分销提成';
                array_push($arr,$data);
            }else{
                foreach ($arr as $kk=>$vv){
                    if($v['agent_id']==$vv['agent_id']){
                        array_push($vv['ids'],$v['id']);
                        $vv['money']+=$v['money'];	//最小3角
                    }else{
                        $data['ids']=[];
                        array_push($data['ids'],$v['id']);
                        $data['order_sn']=$v['order_num'];
                        $data['agent_id']=$v['agent_id'];
                        $data['openid']=$v['agent']['openid'];
                        $data['truename']=$v['agent']['nickname'];
                        $data['money']=$v['money'];	//最小3角
                        $data['desc']='代理商分销提成';
                        array_push($arr,$data);
                    }
                }
            }
        }
        $pay=new CpyPayUserService();
        foreach ($arr as $k=>$v){
            $cpyPay=$pay->transfer($v);
            if($cpyPay['code']){
                $record->where(['id'=>$v['ids']])->update(['status'=>2,'cpy_pay_status'=>1,'wx_pay'=>$cpyPay['data']['payment_no'],'pay_time'=>strtotime($cpyPay['data']['payment_time'])]);
            }else{
                $record->where(['id'=>$v['ids']])->update(['cpy_pay_status'=>2]);
                Log::record($cpyPay,'error');
            }
        }
    }

}