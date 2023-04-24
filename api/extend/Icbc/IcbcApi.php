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
use Icbc\lib\UiIcbcClient;
use Icbc\lib\IcbcConstants;

class IcbcApi
{
    public static function getUserInfo(){
        $request = array(
            "serviceUrl" => 'https://apipcs.dccnet.com.cn' . '/api/mall/b2C/order/custmer/info/V1',
            "method" => 'POST',
            "isNeedEncrypt" => false,
            "biz_content" => array(
                "appId"=>"11000000000000003001",
                "userInfoKey"=>"4BB6F8162A744D19B655CFB73BEE1E66"
            )
        );

        $client = new UiIcbcClient('11000000000000003001',
            'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCevygoVrJ+83PlPIrtdIUvX+x7stSrDu0iZaRc9T4qsSqWGXwHhP7tnK41YfE22H6ACxXh3qF8UMNK5YrwClOEEprgZ/GVoJAY9mwIKJ32124olqDlKq4c35+NkLetY9CBDCL6uDGN/aHF8/mQ/SvIbNkvsBrTz64kl80tWoxLNb6xaTwHev5qw9VvLy/8oUTQMbVOt5v8SF2xpe/gGOg00XawKYdqwHFFzN8u9UkdpNZOxOQ3mCS51sfaCZNE6kvcunCpK9X6oqD6Ksy5GXWuiBWCpw4Q1I2E7Vt2Deu3trXB22k2OJY0OlWyQ5KE9JViNkeyo3AS8d4XvttgA727AgMBAAECggEAIPbe9ulx7WzOjzawPrjogzrvF4y+HrtdwLvMksUWYLUje8HVAJa8fUkA3/LzrQjt09b5d0rMy8zVkPLzk/8A8rcOVDUhdInAAn8BVfxhOQXpSSJmTNA9EZ/aOQJpMec9P97uUJP5LlwRwNJ164a3FcqMmxt9pqhEqDKhl55q7Z7qiFTxxcndQpNJzmKhLaRmSKzG5Dhz+jzRC6VFhnpf8ofYi6zy1/WmW0GFuyRUTZmSdFCkddSlnbkaRJvcKa5OY0IZzn59WKi0yZKOewUKpwx4NCZn2itzPMRmMsnGiqNqBRQSXqat3XUpHj7d1mu3ZIjaom2uEZhd6glaBmgu4QKBgQDYYdTXRxbGINYgRBHrdDvkO1vnx8bEnbs11lKMbLE5T7LPcPW14xLDixqFLzCOhX3hCuzvk9cQG6ODVGRRpF1x8JVN4T7Mvh7mt8aw5UO48GHuDvTk/q6LKBySNMAmWLEo8Ymf+xyyHBlEm3Zbig2DJP0tPb5o/wRjPOIgh8cvqwKBgQC7z9y9L/xTSxDSH/N/GvrZ/JiP5u1gVrE2Ebiu1N36YrmnueXWNzbTA74NY0v44RD3hkOAt4XMZTBtZ2npZJv5XSvxurI3gkPbBeNdWrz4IK3fDiRrPMmZ/p3T3GujA60IEtCe6ULR+gMb0pL5Xk8LKVXJfQivIYBFoXvB8OTaMQKBgDL0btO3OOS1TNdSUEn7GDN5yv287NDWOATIkK3i1qUhYIE7H5GShJOpyTf90dhuFSOOmBce4HE5oPrHP8x+AUdYmUA6v7glOU+pjU+Q3a7KZLVTrlXSp7W7X2cKpwkgr69tmbQjxTEoLscaOPf7fY0Zg8lIlfNRwm3AI5v3fW97AoGAcijbKDQu0AJk6xR40d34dOBRa8cWquSHOzJya/MAKt4vs4AP8LIHJSS1NTYSS+migpKeHXNwZNltIlMl0bRSCrVTrM+q3IrV7CAcH/azvq8+gML76CM+99gI32qwEpp9Ztbo8G8hKrkpgpPwuptUuJgbiSnzSWC3s5uAulZPBUECgYB8Zt1GSkolRqkuidSvlYgHzbSgr51yQSrsIac3QIbBmwf+ZB/cyGyHrfn58yEfdsIJDRj7H00j100VHfI/9OWeYDvjS9BW1PPo6lY9xWMB/uRi4eMBeqbLKrqH8c82xBgd44vjsRuEC+IiRHG+Sxrysv5dyVzzMZM9oYY1vYR2xQ==',
            IcbcConstants::$SIGN_TYPE_RSA2,
            IcbcConstants::$CHARSET_UTF8,
            IcbcConstants::$FORMAT_JSON,
            'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCwFgHD4kzEVPdOj03ctKM7KV+16bWZ5BMNgvEeuEQwfQYkRVwI9HFOGkwNTMn5hiJXHnlXYCX+zp5r6R52MY0O7BsTCLT7aHaxsANsvI9ABGx3OaTVlPB59M6GPbJh0uXvio0m1r/lTW3Z60RU6Q3oid/rNhP3CiNgg0W6O3AGqwIDAQAB',
            '',
            '',
            '',
            '');
        try{
            $resp = $client->buildPostForm($request, time() . '', ''); //执行调用
            echo $resp;
        }catch(Exception $e){//捕获异常
            var_dump($e);
            echo 'Exception:'.$e->getMessage()."\n";
        }
    }
}