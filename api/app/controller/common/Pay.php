<?php

namespace app\controller\common;


use app\model\Goods;
use app\model\GoodsSku;
use app\services\AppPayService;
use app\services\GzhNotifyService;
use app\services\GzhVipNoticefyService;
use app\model\SysConfig;
use app\services\NotifyService;
use app\services\PayService;
use app\services\WxNotifyService;
use app\services\TokenService;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use app\model\Order as OrderModel;
use app\model\VipOrder as VipOrderModel;
use GzhPay\JsApi;
use GzhPay\WxPayConfig;
use think\facade\Log;

class Pay extends BaseController
{
	 private $orderID;
    //公众号-我的订单页面中进行支付
    public function gzhPaySecond($id)
    {
       (new IDPostiveInt())->goCheck();

        $order=OrderModel::where('order_id',$id)->find();

        $order_data['order_num']=$order['order_num'];
        $order_data['order_money']=$order['order_money'];
        $openid=TokenService::getCurrentTokenVar('openid');
        $gzh['web_name']=SysConfig::where(['key'=>'web_name'])->value('value');
        $gzh['api_url']=SysConfig::where(['key'=>'api_url'])->value('value');
        $res=(new JsApi())->gzh_pay($openid,$order_data,$gzh);
        return $res;
    }
    //公众号回调
    public function gzh_back()
    {
        Log::error("公众号支付回调");
        $config = new WxPayConfig();
        $notify = new GzhNotifyService();
        $notify->Handle($config, false);
    }

    //公众号购买vip创建订单 在userVip控制器中createVipOrder(),与小程序是用函数
    //公众号购买vip拉起支付
    public function gzhVipPaySecond($id)
    {
       (new IDPostiveInt())->goCheck();
        $order=VipOrderModel::find(['order_id'=>$id]);
        $order_data['order_num']=$order['order_num'];
        $order_data['order_money']=$order['pay_money'];
        $openid=TokenService::getCurrentTokenVar('openid');
        $gzh['web_name']=SysConfig::where(['key'=>'web_name'])->value('value');
        $gzh['api_url']=SysConfig::where(['key'=>'api_url'])->value('value');
        $this->orderID=$id;
        $res=(new JsApi())->gzh_vippay($openid,$order_data,$gzh);
        return $res;
    }
    //公众号购买vip的支付回调
    public function gzh_vipback()
    {
    	Log::error("公众号vip支付回调");
    	
    	$config = new WxPayConfig();
        $notify = new GzhVipNoticefyService();
        $notify->Handle($config, false);
        
    }


    //小程序创建订单
    public function getPreOrder($id = '')
    {
        (new IDPostiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }
    //小程序支付回调:订单+vip
    public function receiveNotify()
    {
        //session('notify',true);
        Log::error("小程序支付回调:");
        $notify = new WxNotifyService();
        Log::error('进入支付回调');
        $notify->Handle();
    }

    //小程序购买vip创建订单 在userVip控制器中
    //小程序开通会员拉起支付
    public function getPreVipOrder($id='')
    {
        (new IDPostiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->payvip();

    }



    //app支付
    public function getAppPayData($id = ''){
        (new IDPostiveInt())->goCheck();
        $pay = new AppPayService($id);
        return $pay->pay();
    }
    //app支付回调
    public function appNotify()
    {
        $notify = new WxNotifyService();
        Log::error('进入app支付回调');
        $notify->Handle();
        exit;
        /*$order_num = input();
        $notify = new NotifyService();
        Log::error("app_pay_back".json_encode($order_num,JSON_UNESCAPED_UNICODE));
        exit();
        $res = $notify->NotifyEditOrder($order_num);
        return app('json')->success($res);*/
    }

    /**
     *  app微信会员支付
     */
    public function getAppVipPay($id='')
    {
        (new IDPostiveInt())->goCheck();
        $pay = new AppPayService($id);
        return $pay->payvip();

    }

    //app支付回调
    public function appvipNotify()
    {
        $notify = new WxNotifyService();
        Log::error('进入app支付回调');
        $notify->Handle();
        exit;

    }

    /**支付回调测试，删除
     * @param $order_num
     */
    public function pay_test($order_num)
    {
        $Notyci=new NotifyService();
        return $Notyci->NotifyEditOrder($order_num);
    }

    public function wx_h5_pay($id)
    {
        $payService=new PayService($id);
        return $payService->wx_h5_pay($id);

    }
    //检测订单是否符合要求
    public function orderPayTest($order_num){

        $order_num = 0.1000001-0.07;
        Log::error($order_num);
        $order_num =  round($order_num,2);
        return $order_num;
    }

}