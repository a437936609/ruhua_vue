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

    /***
     * 获取工行用户信息
     * @param $icbc_config
     * @param $userInfoKey
     * @return mixed
     * @throws BaseException
     */
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
            Log::channel('msgLog')->write("getUserInfo：req=" . json_encode($request));
            $resp = $client->execute($request, time() . '', ''); //执行调用.
            Log::channel('msgLog')->write("getUserInfo：res=" . json_encode($resp));
            #Log::channel('msgLog')->write();
            if($resp['return_code'] != 0){
                throw new BaseException(['msg' => isset($resp['return_msg']) ? $resp['return_msg'] : '查询用户信息失败']);
            }
            return $resp;
        }catch(Exception $e){
            Log::channel('msgLog')->write("err:123".$e->getMessage());
            //捕获异常
            throw new BaseException(['msg' => $e->getMessage()]);
        }
    }

    /***
     * 工行创建订单
     * @param $icbc_config
     * @param $order_info
     * @return string
     * @throws BaseException
     */
    public static function createOrder($icbc_config, $order_info){
        $request = array(
            "serviceUrl" => $icbc_config['icbc_server_url'] . '/ui/mall/b2C/page/order/create/V1',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "thirdPartySubmitRequestOrders" => array(
                    array(
                        "orderProdType"=> '19',
                        "appId"=> $icbc_config['icbc_appid'],
                        "platformId" => '19',
                        "remark1" => null,
                        "remark2" => null,
                        "remark3" => null,
                        "isSupportCoupon"=>'1',
                        "pcOrderUrl"=>$order_info['orderDetailUrl'],
                        "mobOrderUrl"=>$order_info['orderDetailUrl'],
                        "orderMerchantMemo"=> $order_info['orderMerchantMemo'],
                        "outUserId"=> $order_info['outUserId'],
                        "thirdPartyOrderId"=> $order_info['thirdPartyOrderId'],
                        "orderPrice"=> $order_info['orderPrice'],
                        "orderPayAmout"=> $order_info['orderPayAmout'],
                        "orderInvalidTime"=> $order_info['orderInvalidTime'],
                        "payUse"=> '0',
                        "noticeUrl"=> $order_info['noticeUrl'],
                        "payBackUrl"=> $order_info['payBackUrl'],
                        "payFailUrl"=> $order_info['payFailUrl'],
                        "storeVO" => array(
                            "mercId"=> $order_info['mercId'],
                            "storeId"=> $order_info['storeId'],
                            "storeName"=> $order_info['storeName'],
                            "prods" => array(array(
                                "prodId"=> $order_info['prodId'],
                                "prodName"=> $order_info['prodName'],
                                "skuId"=> $order_info['skuId'],
                            ))
                        )
                    )
                )
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
            Log::channel('msgLog')->write("createOrder：req=" . json_encode($request));
            $resp = $client->buildPostForm($request, time() . '', ''); //执行调用
            Log::channel('msgLog')->write("createOrder：res=" . json_encode($resp));
            return $resp;
        }catch(Exception $e){
            //var_dump($e);
            Log::error($e->getMessage());
            //捕获异常
            throw new BaseException(['msg' => $e->getMessage()]);
        }
    }


    public static function replyNotify($icbc_config, $order_num){
        $request = array(
            "serviceUrl" => '/order/pay/notify',
            "response_biz_content" => array(
                "msg_id"=> $order_num . time(),
                "return_code"=> 0,
                "return_msg"=> 'success'
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
            return $client->prepareResponseParams($request, time() . '', ''); //执行调用
        }catch(Exception $e){
            //捕获异常
            throw new BaseException(['msg' => $e->getMessage()]);
        }
     }

    /***
     * 支付结果查询接口
     * @param $icbc_config
     * @param $userId
     * @param $thirdOrderId
     * @return mixed
     * @throws BaseException
     */
    public static function queryOrderInfo($icbc_config, $userId, $thirdOrderId){
        $request = array(
            "serviceUrl" => $icbc_config['icbc_server_url'] . '/api/mall/b2C/order/payment/query/V1',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "appId"=> $icbc_config['icbc_appid'],
                "userId"=> $userId,
                "thirdOrderId"=> $thirdOrderId,
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
            Log::channel('msgLog')->write("queryOrderInfo：req=" . json_encode($request));
            $resp = $client->execute($request, time() . '', ''); //执行调用.
            Log::channel('msgLog')->write("queryOrderInfo：res=" . json_encode($resp));
            if($resp['return_code'] != 0){
                throw new BaseException(['msg' => isset($resp['return_msg']) ? $resp['return_msg'] : '查询订单信息失败']);
            }
            return $resp;
        }catch(Exception $e){
            Log::error($e->getMessage());
            //捕获异常
            throw new BaseException(['msg' => $e->getMessage()]);
        }
    }

    /***
     * 退款接口
     * @param $icbc_config
     * @param $refund_info
     * @return mixed
     * @throws BaseException
     */
    public static function refundPay($icbc_config, $refund_info){
        $request = array(
            "serviceUrl" => $icbc_config['icbc_server_url'] . '/api/mall/b2C/order/refund/V1',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "appId"=> $icbc_config['icbc_appid'],
                "orderId"=> $refund_info['orderId'],
                "refundUrl"=> $refund_info['refundUrl'],
                "thirdOrderId"=> $refund_info['thirdOrderId'],
                "thirdRefundId"=> $refund_info['thirdRefundId'],
                "userId"=> $refund_info['userId'],
                "refundAmt"=> $refund_info['refundAmt'],
                "remark" => $refund_info['remark'],
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
            Log::channel('msgLog')->write("refundPay：req=" . json_encode($request));
            $resp = $client->execute($request, time() . '', ''); //执行调用.
            Log::channel('msgLog')->write("refundPay：res=" . json_encode($resp));
            if($resp['return_code'] != 0){
                throw new BaseException(['msg' => '请求退款失败' . $resp['return_msg']]);
            }
            return $resp;
        }catch(Exception $e){
            Log::record($e->getMessage());
            //捕获异常
            throw new BaseException(['msg' => $e->getMessage()]);
        }
    }
}