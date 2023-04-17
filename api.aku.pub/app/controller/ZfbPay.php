<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/18
 * Time: 10:52
 */

namespace app\controller;

require __DIR__ . '/../../vendor/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
require __DIR__ . '/../../vendor/alipay/wappay/service/AlipayTradeService.php';
require __DIR__ . '/../../vendor/alipay/aop/AopClient.php';

use Aop\AopClient;
use app\model\VipOrder as VipOrderModel;
use app\model\Order as OrderModel;
use app\Request;
use ruhua\bases\BaseController;
use ruhua\exceptions\BaseException;
use think\facade\Log;
use app\model\SysConfig;
use ruhua\exceptions\OrderException;



class ZfbPay extends BaseController
{

    public function vippay($order_num)
    {
        try{
        $order=VipOrderModel::where('id',$order_num)->find();
        if(!$order){
            throw new BaseException(['msg' => '订单不存在']);
        }
        if($order['order_status']!=1){
            throw new BaseException(['msg' => '订单已支付']);
        }
        $zfb_data=SysConfig::where('key','in',['zfb_appid','zfb_private_key','zfb_public_key','zfb_back'])->select()->toArray();
        if(!$zfb_data){
            throw new OrderException(['msg'=>'验证码秘钥错误']);
        }
        $arr=[];
        foreach ($zfb_data as $k=>$v){
            $arr[$v['key']]=$v['value'];
        }




        $out_trade_no = $order['order_num'];
        $total_amount = $order['pay_money'];
        $aop=new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $arr['zfb_appid'];
        $aop->rsaPrivateKey=$arr['zfb_private_key'];
        $aop->alipayrsaPublicKey=$arr['zfb_public_key'];
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $aop->signType='RSA2';
        $request=new \AlipayTradeWapPayRequest();
        $request->setBizContent("{" .
            "    \"body\":\"\"," .
            "    \"subject\":\"会员支付\"," .
            "    \"out_trade_no\":\"$out_trade_no\"," .
            "    \"timeout_express\":\"90m\"," .
            "    \"total_amount\":$total_amount," .
            "    \"product_code\":\"QUICK_WAP_WAY\"" .
            "  }");
        $request->setNotifyUrl($arr['zfb_back']."order/alinotify");
        $request->setReturnUrl($arr['zfb_back']."h5");

        $result = $aop->pageExecute($request);
        }catch (\Exception $e){
            Log::channel('msgLog')->write("订单$order_num 支付失败,原因:".$e->getMessage());
        }
        return $result;
    }

    //会员支付宝支付
    public function appvippay($order_num)
    {
        try{
        $order=VipOrderModel::where('id',$order_num)->find();
        if(!$order){
            throw new BaseException(['msg' => '订单不存在']);
        }
        if($order['order_status']!=1){
            throw new BaseException(['msg' => '订单已支付']);
        }

        $zfb_data=SysConfig::where('key','in',['zfb_appid','zfb_private_key','zfb_public_key','zfb_back'])->select()->toArray();
        if(!$zfb_data){
            throw new OrderException(['msg'=>'验证码秘钥错误']);
        }
        $arr=[];
        foreach ($zfb_data as $k=>$v){
            $arr[$v['key']]=$v['value'];
        }
        Log::error($arr);
        $out_trade_no = $order['order_num'];
        $total_amount = $order['pay_money'];

        $aop=new \AopClient();
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId =$arr['zfb_appid'];
        $aop->rsaPrivateKey=$arr['zfb_private'];
        $aop->format = "json";
        $aop->apiVersion="1.0";
        $aop->postCharset = "utf-8";
        $aop->signType = "RSA2";

        $aop->alipayrsaPublicKey = $arr['zfb_public_key'];
//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request=new \AlipayTradeAppPayRequest();

        $request->setNotifyUrl($arr['zfb_back']."order/alinotify");

        $request->setBizContent("{" .
            "    \"body\":\"pay\"," .
            "    \"subject\":\"pay\"," .
            "    \"out_trade_no\":\"$out_trade_no\"," .
            "    \"timeout_express\":\"30m\"," .
            "    \"total_amount\":$total_amount," .
            "    \"product_code\":\"QUICK_MSECURITY_PAY\"" .
            "  }");
        Log::error($out_trade_no);
        $response = $aop->sdkExecute($request);
        }catch (\Exception $e){
            Log::channel('msgLog')->write("订单$order_num 支付失败,原因:".$e->getMessage());
        }
        echo $response;
       // return htmlspecialchars($response);
    }


    public function notify(Request $request)
    {
        Log::error("会员回调成功");
        $zfb_data=SysConfig::where('key','in',['zfb_appid','zfb_private_key','zfb_public_key','zfb_back'])->select()->toArray();
        if(!$zfb_data){
            throw new OrderException(['msg'=>'验证码秘钥错误']);
        }
        $arr=[];
        foreach ($zfb_data as $k=>$v){
            $arr[$v['key']]=$v['value'];
        }
        $config = array (
            //应用ID,您的APPID。
            'app_id' => $arr['zfb_appid'],

            //商户私钥，您的原始格式RSA私钥
            'merchant_private_key'=>$arr['zfb_private_key'],
            //异步通知地址
            'notify_url' => "",

            //同步跳转
            'return_url' => "",

            //编码格式
            'charset' => "UTF-8",

            //签名方式
            'sign_type'=>"RSA2",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => $arr['zfb_public_key'],

        );
        $arr=$request->param();
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            $out_trade_no = $arr['out_trade_no'];
            //支付宝交易号
            $trade_no = $arr['trade_no'];
            //交易状态
            $trade_status = $arr['trade_status'];


            //交易金额
            $total_amount = $arr['total_amount'];
            if($arr['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            if($arr['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
                //此处应该更新一下订单状态，商户自行增删操作
                // Order::where('id',1)->update(['type'=>1]);
                $order=VipOrderModel::where('order_num',$out_trade_no)->find();
                VipOrderModel::notifyVip($order['id']);

                echo 'success';
            }
        }else {
            //验证失败
            echo "fail";
        }
    }


    public function aliorder($order_id)
    {
        try{
        $order=OrderModel::where("order_id",$order_id)->find();
        log::error($order);
        if(!$order){
            throw new BaseException(['msg' => '订单不存在']);
        }
        if($order['payment_state']==1){
            throw new BaseException(['msg' => '订单已支付']);
        }

        $zfb_data=SysConfig::where('key','in',['zfb_appid','zfb_private_key','zfb_public_key','zfb_back'])->select()->toArray();
        if(!$zfb_data){
            throw new OrderException(['msg'=>'验证码秘钥错误']);
        }
        $arr=[];
        foreach ($zfb_data as $k=>$v){
            $arr[$v['key']]=$v['value'];
        }

        $out_trade_no = $order['order_num'];
        $total_amount = $order['order_money'];
        $aop=new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $arr['zfb_appid'];
        $aop->rsaPrivateKey=$arr['zfb_private_key'];
        $aop->alipayrsaPublicKey=$arr['zfb_public_key'];
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $aop->signType='RSA2';
        $request=new \AlipayTradeWapPayRequest();
        $request->setBizContent("{" .
            "    \"body\":\"\"," .
            "    \"subject\":\"订单支付\"," .
            "    \"out_trade_no\":\"$out_trade_no\"," .
            "    \"timeout_express\":\"90m\"," .
            "    \"total_amount\":$total_amount," .
            "    \"product_code\":\"QUICK_WAP_WAY\"" .
            "  }");
        $request->setNotifyUrl($arr['zfb_back']."order/alinotify_order");
        $request->setReturnUrl($arr['zfb_back']."h5");
        $result = $aop->pageExecute($request);
        }catch (\Exception $e){
            Log::channel('msgLog')->write("订单支付失败,原因:".$e->getMessage());
        }
        return $result;
    }

    public function ordernotify(Request $request)
    {
        $zfb_data=SysConfig::where('key','in',['zfb_appid','zfb_private_key','zfb_public_key'])->select()->toArray();
        if(!$zfb_data){
            throw new OrderException(['msg'=>'验证码秘钥错误']);
        }
        $arr=[];
        foreach ($zfb_data as $k=>$v){
            $arr[$v['key']]=$v['value'];
        }

        $config = array (
            //应用ID,您的APPID。
            'app_id' => $arr['zfb_appid'],

            //商户私钥，您的原始格式RSA私钥
            'merchant_private_key'=>$arr['zfb_public_key'],
            //异步通知地址
            'notify_url' => "",
            //同步跳转
            'return_url' => "",
            //编码格式
            'charset' => "UTF-8",
            //签名方式
            'sign_type'=>"RSA2",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => $arr['zfb_public_key'],

        );
        $arr=$request->param();
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            $out_trade_no = $arr['out_trade_no'];
            //支付宝交易号
            $trade_no = $arr['trade_no'];
            //交易状态
            $trade_status = $arr['trade_status'];
            //交易金额
            $total_amount = $arr['total_amount'];
            if($arr['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            if($arr['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
                //此处应该更新一下订单状态，商户自行增删操作
                // Order::where('id',1)->update(['type'=>1]);
                $order=OrderModel::update(['payment_state'=>1],['order_num'=> $trade_no]);

                echo 'success';
            }
        }else {
            //验证失败
            echo "fail";
        }
    }
}