<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/4 0004
 * Time: 16:42
 */

namespace Icbc;

use think\Exception;
use think\facade\Log;
use ruhua\exceptions\BaseException;
use Icbc\lib\DefaultIcbcClient;
use Icbc\lib\UiIcbcClient;
use Icbc\lib\IcbcConstants;

class IcbcApi
{
    public static function getUserInfo($icbc_config, $userInfoKey){
        $request = array(
            "serviceUrl" => $icbc_config['icbc_server_url'] . '/api/mall/b2C/order/custmer/info/V1',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "appId"=> $icbc_config['icbc_appid'],
                "userInfoKey"=> $userInfoKey
            )
        );
        $client = new DefaultIcbcClient($icbc_config['icbc_appid'],
            $icbc_config['icbc_mer_private_key'],
            IcbcConstants::$SIGN_TYPE_RSA2,
            IcbcConstants::$CHARSET_UTF8,
            IcbcConstants::$FORMAT_JSON,
            $icbc_config['icbc_platform_public_key'],
            '',
            '',
            '',
            '');
        try{
            $resp = $client->execute($request, time() . '', ''); //执行调用
            if($resp['return_code'] != 0){
                throw new BaseException(['msg' => isset($resp['return_msg']) ? $resp['return_msg'] : '查询用户信息失败']);
            }
            return $resp;
        }catch(Exception $e){
            var_dump($e);
            //捕获异常
            throw new BaseException(['msg' => $e->getMessage()]);
        }
    }

    public static function createOrder($icbc_config, $order_info){
        $thirdPartySubmitRequestOrders          =       [];
        $prods                                  =       [];
        $prods[]                                =       array(
            "prodId"                    =>      $order_info['prodId'],
            "prodName"                  =>      $order_info['prodName'],
            "skuId"                     =>      $order_info['skuId'],
        );
        $thirdPartySubmitRequestOrders[]        =       array(
            "orderProdType"             =>      '19',
            "appId"                     =>      $icbc_config['icbc_appid'],
            "platformId"                =>      '019',
            "remark1"                   =>      null,
            "remark2"                   =>      null,
            "remark3"                   =>      null,
            "orderMerchantMemo"         =>      $order_info['orderMerchantMemo'],
            "outUserId"                 =>      $order_info['outUserId'],
            "thirdPartyOrderId"         =>      $order_info['thirdPartyOrderId'],
            "orderPrice"                =>      $order_info['orderPrice'],
            "orderPayAmout"             =>      $order_info['orderPayAmout'],
            "orderInvalidTime"          =>      $order_info['orderInvalidTime'],
            "payUse"                    =>      '0',
            "noticeUrl"                 =>      $order_info['noticeUrl'],
            "payBackUrl"                =>      $order_info['payBackUrl'],
            "payFailUrl"                =>      $order_info['payFailUrl'],
            "storeVO"                   =>      array(
                "mercId"                =>      $order_info['mercId'],
                "storeId"               =>      $order_info['storeId'],
                "storeName"             =>      $order_info['storeName'],
                "prods"                 =>      $prods
            ),
            "orderProdName"             =>      '测试商品'
        );
        $request = array(
            "serviceUrl" => $icbc_config['icbc_server_url'] . '/ui/mall/b2C/page/order/create/V1',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "thirdPartySubmitRequestOrders"     =>  $thirdPartySubmitRequestOrders
            )
        );
        $client = new UiIcbcClient($icbc_config['icbc_appid'],
            $icbc_config['icbc_mer_private_key'],
            IcbcConstants::$SIGN_TYPE_RSA2,
            IcbcConstants::$CHARSET_UTF8,
            IcbcConstants::$FORMAT_JSON,
            $icbc_config['icbc_platform_public_key'],
            '',
            '',
            '',
            '');
        try{
            $resp = $client->buildPostForm($request, time() . '', ''); //执行调用
            return $resp;
        }catch(Exception $e){
            var_dump($e);
            //捕获异常
            throw new BaseException(['msg' => $e->getMessage()]);
        }
    }
}