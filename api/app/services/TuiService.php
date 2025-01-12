<?php

namespace app\services;


use app\model\Coupon;
use app\model\Order as OrderModel;
use app\model\OrderGoods as OrderGoodsModel;
use app\model\OrderLog;
use app\model\PtItem;
use app\model\Tui as TuiModel;
use think\facade\Log;
use WxPay\WxPayApi;
use WxPay\WxPayData;
use WxPay\WxPayRefund;
use app\model\UserCoupon;
use Icbc\IcbcApi;
use app\model\SysConfig;
use app\model\User as UserModel;
use think\Exception;
use ruhua\exceptions\BaseException;

class TuiService
{

    /* 申请退款，WxPayRefund中out_trade_no、transaction_id至少填一个且
    * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
    */
    public function pay($id)
    {
        $aid = TokenService::getCurrentAid();
        $tui=TuiModel::where(['status' => 0,'id'=>$id])->find();
        $order = OrderModel::with('ordergoods')->where(['order_num' => $tui['order_num']])->find();

        $order_sn = $order['order_num'];
        $refund_no = 'tui' . rand(10000, 99999); //退款单号
        $total_fee = $order['order_money'] * 100;
        $refund_fee = $this->getTuiMoney($tui,$order) *100;
        if($refund_fee!=$tui['money']*100){
            return app('json')->fail('金额有误'.$refund_fee."-".$tui['money']*100);
        }
        $merchid = $aid;
        new WxPayData();
        $input = new WxPayRefund();
        $input->SetOut_trade_no($order_sn);            //自己的订单号
        $input->SetOut_refund_no($refund_no);            //退款单号
        $input->SetTotal_fee($total_fee);            //订单标价金额，单位为分
        $input->SetRefund_fee($refund_fee);            //退款总金额，订单总金额，单位为分，只能为整数
        $input->SetOp_user_id($merchid);    //操作员ID

        $res = WxPayApi::refund($input);    //退款操作
        if (isset($res['err_code'])||$res['return_code']!='SUCCESS') {
            return app('json')->fail($res['err_code_des']);
//            throw new BaseException([ 'msg'=>$res['err_code_des'] ]);
        }
        if (isset($res['refund_id'])) {
            $res = $tui->save(['status' => 1, 'wx_id' => $res['refund_id']]);
            if($tui['goods_id']==0){
                $order->save(['state' => -2]);
                OrderGoodsModel::where(['order_id' => $order['order_id']])->update(['state' => -2]);
                if ($order['coupon_id']) {//整个订单退款退回优惠券
                    Coupon::where(['id' => $order['coupon_id'],'status'=>1])->update(['status' => 0]);
                }
            }else{
                OrderGoodsModel::where(['order_id' => $order['order_id'] ,'goods_id' => $tui['goods_id']])->update(['state' => -2]);
                $state=0;

                $order = OrderModel::with('ordergoods')->where(['order_num' => $tui['order_num']])->find();
                foreach ($order['order_goods'] as $k => $v) {
                    if ($v['state']!=-2) {
                        $state=1;
                    }
                }
                if($state==0){
                    $order->save(['state'=>-2]);
                }
            }
            if ($res) {
                return app('json')->success('退款成功');
            }
            return app('json')->fail('退款异常');
        } else {
            return app('json')->fail('退款异常');
        }
    }

    /**
     * 验证金额
     * @param $tui
     * @param $order
     * @return int|mixed
     */
    public function getTuiMoney($tui,$order){
        $total_money=0;
        if($tui['goods_id']==0){

            $apply_money=TuiModel::where('order_id',$order['order_num'])->sum('money');

            $total_money=$order['order_money']-$apply_money;
        }else{

            foreach ($order['order_goods'] as $k => $v) {
                //if ($tui['goods_id'] == $v['goods_id']) {
                $total_money += $v['total_price'];
                //}
            }
            if ($order['coupon_id']) {
                $coupon = UserCoupon::where('id', $order['coupon_id'])->where('status',1)->find();
                if ($coupon && ($order['order_money'] - $total_money) < $coupon['full']) {
                    $total_money = $total_money - $coupon['reduce'];
                    $coupon->save(['status' => 0]);
                }
            }
        }
        return $total_money;
    }

    public function ptTuiPay($id)
    {
        $order = OrderModel::where(['order_id' => $id])->find();
        $order_sn = $order['order_num'];
        $refund_no = 'tui' . rand(10000, 99999); //退款单号
        $total_fee = $order['order_money'] * 100;
        $refund_fee = $order['order_money'] * 100;
        new WxPayData();
        $input = new WxPayRefund();
        $input->SetOut_trade_no($order_sn);            //自己的订单号
        $input->SetOut_refund_no($refund_no);            //退款单号
        $input->SetTotal_fee($total_fee);            //订单标价金额，单位为分
        $input->SetRefund_fee($refund_fee);            //退款总金额，订单总金额，单位为分，只能为整数
        $input->SetOp_user_id(1);    //操作员ID

        $res = WxPayApi::refund($input);    //退款操作
        if (isset($res['err_code'])) {
            return app('json')->fail($res['err_code_des']);
        }
        if (isset($res['refund_id'])) {
            OrderLog::create(['order_id' => $id, 'type_name' => '拼团退款', 'content' => '拼团失败退款', 'wx_refund' => $res['refund_id']]);
            $res = $order->save(['state' => -2, 'pt_state' => -1]);
            return $res;
        }
    }

    /* 申请退款，WxPayRefund中out_trade_no、transaction_id至少填一个且
    * out_refund_no、total_fee、refund_fee、op_user_id为必填参数
    */
    public function icbc_pay($id)
    {
        $aid = TokenService::getCurrentAid();
        $tui=TuiModel::where(['status' => 0,'id'=>$id])->find();
        $order = OrderModel::with('ordergoods')->where(['order_num' => $tui['order_num']])->find();

        $order_sn = $order['order_num'];
        $refund_no = 'tui' . rand(10000, 99999); //退款单号
        $total_fee = $order['order_money'] * 100;
        $refund_fee = $this->getTuiMoney($tui,$order) *100;
        if($refund_fee!=$tui['money']*100){
            return app('json')->fail('金额有误'.$refund_fee."-".$tui['money']*100);
        }
        //回调地址
        $notify_url                                 =   SysConfig::get('icbc_notify_url');
        $user = UserModel::where('id', $order['user_id'])->find();
        if(!$user){
            return app('json')->fail('工行用户不存在');
        }

        $icbc_config                                =   [];
        $icbc_config['icbc_server_url']             =   SysConfig::get('icbc_server_url');
        $icbc_config['icbc_appid']                  =   SysConfig::get('icbc_appid');
        $icbc_config['icbc_mer_private_key']        =   SysConfig::get('icbc_mer_private_key');
        $icbc_config['icbc_platform_public_key']    =   SysConfig::get('icbc_platform_public_key');

        $refund_info                                =   [];
        $refund_info['orderId']                     =   $order['prepay_id'];
        $refund_info['refundUrl']                   =   $notify_url;
        $refund_info['thirdOrderId']                =   $order_sn;
        $refund_info['thirdRefundId']               =   $refund_no;
        $refund_info['userId']                      =   $user['icbc_user_id'];
        $refund_info['refundAmt']                   =   $tui['money'] . '';
        $refund_info['remark']                      =   $aid . '';

        $resp = IcbcApi::refundPay($icbc_config, $refund_info);

        if (isset($resp['refund_id'])) {
            $res = $tui->save(['status' => 1, 'wx_id' => $resp['refund_id']]);

            if($tui['goods_id']==0){
                $order->save(['state' => -2]);
                OrderGoodsModel::where(['order_id' => $order['order_id']])->update(['state' => -2]);
                if ($order['coupon_id']) {//整个订单退款退回优惠券
                    Coupon::where(['id' => $order['coupon_id'],'status'=>1])->update(['status' => 0]);
                }

            }else{

                //这个修改状态有问题只能修改一个产品，如果出现一个订单多个产品是没有办法一次性修改状态
                //OrderGoodsModel::where(['order_id' => $order['order_id'] ,'goods_id' => $tui['goods_id']])->update(['state' => -2]);

                OrderGoodsModel::where(['order_id' => $order['order_id']])->update(['state' => -2]);
                $state=0;

                $order = OrderModel::with('ordergoods')->where(['order_num' => $tui['order_num']])->find();
                foreach ($order['ordergoods'] as $k => $v) {
                    if ($v['state']!=-2) {
                        $state=1;
                    }
                }
                if($state==0){
                    $order->save(['state'=>-2]);
                }
            }
            if ($res) {
                return app('json')->success('退款成功');
            }
            return app('json')->fail('退款异常');
        } else {
            return app('json')->fail('退款异常');
        }
    }
}