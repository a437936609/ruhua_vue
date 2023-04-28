<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/26 0026
 * Time: 16:32
 */

namespace app\services;


use app\controller\cms\Printer;
use app\model\Order as OrderModel;
use app\model\PtItem as PtItemModel;
use app\model\VipOrder as VipOrderModel;
use think\facade\Log;
use app\model\FxGoods as FxGoodsModel;
use app\model\FxRecord as FxRecordModel;
use app\model\SysConfig as SysConfigModel;
use app\model\User as UserModel;
use app\model\PointsRecord as PointsRecordModel;
use Icbc\IcbcApi;
use app\model\SysConfig;

class IcbcNotifyService
{
    //异步接收微信回调，更新订单状态
    public function NotifyEditOrder($orderNo)
    {


        $str = substr($orderNo, 0, 1);

        Log::error('支付回调' . $str);
        if ($str == 'V') {
            return $this->updatevip($orderNo);
        } else {

            return $this->upOrder($orderNo);
        }

    }
    
    public function updatevip($orderNo)
    {
        Log::error('Vip订单更新');
         $order=VipOrderModel::where('order_num',$orderNo)->find();
         if($order['order_status']==1){
            VipOrderModel::notifyVip($order['id']);
         }
        return true;
    }

    //更新订单状态
    private function upOrder($orderNo)
    {
        $icbc_config                                =   [];
        $icbc_config['icbc_server_url']             =   SysConfig::get('icbc_server_url');
        $icbc_config['icbc_appid']                  =   SysConfig::get('icbc_appid');
        $icbc_config['icbc_mer_private_key']        =   SysConfig::get('icbc_mer_private_key');
        $icbc_config['icbc_platform_public_key']    =   SysConfig::get('icbc_platform_public_key');
        
        $order_inf=OrderModel::where('order_num', $orderNo)->find();
        if(!$order_inf){
            Log::error("订单不存在:". $orderNo);
            return json(IcbcApi::replyNotify($icbc_config, $orderNo));
        }
        $user = UserModel::where('id', $order_inf['user_id'])->find();
        if(!$user){
            Log::error("用户不存在:". $orderNo);
            return json(IcbcApi::replyNotify($icbc_config, $orderNo));
        }
        if(empty($user['icbc_user_id'])){
            Log::error("工行用户id不存在:". $orderNo);
            return json(IcbcApi::replyNotify($icbc_config, $orderNo));
        }
        $resp = IcbcApi::queryOrderInfo($icbc_config, $user['icbc_user_id'], $orderNo);
        if($resp['pay_status'] != '04'){
            Log::error("交易状态!=04:". json_encode($resp));
            return json(IcbcApi::replyNotify($icbc_config, $orderNo));
        }
        // 记录融易购订单号,便于退款使用
        $prepay_id      =       $resp['order_id'];   
        $isprinter=SysConfigModel::where('key','is_printer')->value('value');
        if($isprinter){
            $res=Printer::order_printer($orderNo);
        }

        try {
            //Lock方法是用于数据库的锁机制;
            $order = OrderModel::with(['ordergoods', 'users','invitecode'])->where('order_num', $orderNo)->lock(true)->find();

            if ($order&&$order['payment_state'] == 0 && $order['state'] == 0) {

                OrderModel::where('order_id', $order['order_id'])->update(['payment_state' => 1, 'pay_time' => time(), 'prepay_id' => $prepay_id]);//更新订单状态为已支付

                if($order['invite_code']){

                    $money=OrderModel::countmoney($order['ordergoods']);
                    $data=[
                        'order_num'=>$orderNo,
                        'order_id'=>$order['order_id'],
                        'user_id'=>$order['user_id'],
                        'agent_id'=>$order['invitecode']['id'],
                        'money'=>$money,
                    ];
                    FxRecordModel::create($data);
                }
                Log::error("进入统计1");
                $is_point=SysConfigModel::get('exchang_points');
                $bili=SysConfigModel::get('money_to_points');
                if($is_point){
                    $point=(int)$order['order_money']/$bili;
                    $user=UserModel::find($order['user_id']);
                    $user->save(['points'=>($user['points']+$point)]);
                    $dt=[
                        'uid'=>$order['user_id'],
                        'credittype'=>'buy',
                        'num'=>$point,
                        'module'=>'qy2019_shop',
                       'remark'=>"购物获得积分，增加{$point}分"
                    ];
                    PointsRecordModel::create($dt);



                }
                Log::error('支付调用事件');
              //  event('PayOrder', $order);//扣除库存  修改至创建订单里扣库存
                event('InvoiceLog', $order);//检查是否需要开发票
                event('SendGzhDeliveryMessage', [$order, 1, '']);//公众号发送模板消息通知管理员
            


                if ($order['payment_type'] == 'wx') {

                    event('SendGzhDeliveryMessage', [$order, 5, $order['user_id']]);//公众号发送模板消息通知用户

                } else if ($order['payment_type'] == 'xcx') {
                    //小程序发送模板消息
                }
              
            }
        } catch (\Exception  $ex) {
            Log::error('更新订单Notify:' . $ex->getMessage());
        }
        return json(IcbcApi::replyNotify($icbc_config, $orderNo));
    }

    //更新订单状态
    private function upPtOrder($orderNo)
    {
        try {
            //Lock方法是用于数据库的锁机制;
            $order = OrderModel::with(['ordergoods', 'users'])->where('order_num', $orderNo)->lock(true)->find();
            $item = PtItemModel::with('user')->where('id', $order['item_id'])->find();
            if ($order['payment_state'] == 0 && $order['state'] == 0) {
                $order->save(['payment_state' => 1, 'pay_time' => time()]);//更新订单状态为已支付
                if ($order['is_captain'] == 1 && $order['user_id'] == $item['user_id']) {
                    $data['state'] = 1;
                }
                $data['pay_user'] = $item['pay_user'] + 1;
                if ($data['pay_user'] == $item['user_num']) {
                    $data['state'] = 2;
                }
                $item->save($data);
                event('ReduceStock', $order);//扣除库存
                if ($data['state'] == 2) {
                    OrderModel::where(['item_id' => $item['id'], 'payment_state' => 1])->update(['pt_state' => 2]);
                    $ids = OrderModel::where('item_id', $item['id'])->where('payment_state', 1)->column('user_id');
                    event('SendGzhDeliveryMessage', [$item, 3, $ids]);//拼团成功发送公众号模板消息
                }
                $res['order_goods'] = $order;
                if ($order['payment_type'] == 'wx') {
                    event('SendGzhDeliveryMessage', [$order, 5, $order['user_id']]);//公众号发送模板消息通知用户
                } else if ($order['payment_type'] == 'xcx') {
                    //小程序发送模板消息
                }

            }
        } catch (\Exception  $ex) {
            Log::error('更新订单Notify:' . $ex->getMessage());
            return false;
        }
        return true;
    }
}
