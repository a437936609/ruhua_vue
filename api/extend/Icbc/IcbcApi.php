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
}