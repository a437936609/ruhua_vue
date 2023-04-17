<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

require __DIR__ . '/../vendor/autoload.php';


// 定义程序当前版本号
define('VAE_VERSION','1.26');

// 定义vaeThink项目目录
define('VAE_ROOT', __DIR__ . '/../');
define('ROOT', __DIR__);
define('WEB_URL', "http://serve.thinkphpcode.com/");//服务端地址
define('VERSION_URL',"http://authorization.ruhuashop.com/");
//define('VERSION_URL',"http://192.168.1.102:8002/");




// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
