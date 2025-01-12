<?php

/**
 * 如花商城系统
 * =========================================================
 * 官方网址：http://www.ruhuashop.com
 * QQ 交流群：728615087
 * Version：1.2.0
 */

namespace app\http\middleware;

//中间件，验证token，检测权限

use app\services\GroupService;
use app\services\TokenService;
use ruhua\exceptions\BaseException;
use think\facade\Log;
use think\facade\Request;
use app\model\SysConfig;

class CheckCms
{
    public function handle($request, \Closure $next)
    {
        $res = TokenService::GTadmimScope();//判断是否具有管理员权限
        $remote=SysConfig::where('key','remote_login')->value('value');
        if($remote>0){
            $header=Request::header();
            if(!isset($header['origin'])){
                throw new BaseException(['msg'=>'错误']);
            }
        }
        $aid = TokenService::getCurrentAid();
        $data = (new GroupService())->checkRule($aid);
        if ($res == true && $data == true) {
            return $next($request);
        }
        return app('json')->fail('没有权限');
    }
}