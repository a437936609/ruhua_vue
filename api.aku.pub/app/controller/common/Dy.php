<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/28 0028
 * Time: 14:56
 */

namespace app\controller\common;

//抖音小程序接口
use app\controller\auth\Token;
use app\services\TokenService;
use ruhua\bases\BaseController;
use ruhua\bases\BaseCommon;
use app\model\SysConfig as SysconfigModel;
use app\model\User as UserModel;
use think\facade\Log;

class Dy extends BaseController
{
    private $basecommon;

    public function __construct()
    {

        $this->basecommon=new BaseCommon();
    }

    public function getAccessToken()
    {
        $data=SysConfigModel::get_dy_infor();
        $data['grant_type']="client_credential";
        $url="https://developer.toutiao.com/api/apps/token";
        $url=$this->cache_url($url,$data);
        $res=$this->basecommon->curl_get($url);
        return $res;

    }

    public function get_code2Session()
    {
        $code=input('post.');
        $data=SysConfigModel::get_dy_infor();
        foreach ($code as $k=>$v){
            $data[$k]=$v;
        }
        $url="https://developer.toutiao.com/api/apps/jscode2session";
        $url=$this->cache_url($url,$data);
        $res=$this->basecommon->curl_get($url);
        $res=json_decode($res);
        $res=(array)$res;
        $cache=[
            'openid'=>$res['openid']
        ];
        $user=UserModel::where('dy_openid',$cache['openid'])->find();
        if(!$user){
            UserModel::create(['dy_openid'=>$cache['openid']]);
        }
        $user=UserModel::where('dy_openid',$cache['openid'])->find();
        $cache=[
            'openid'=>$res['openid'],
            'uid'=>$user['id'],
            'scope'=>'9'
        ];
        $token=(new Token())->saveCache($cache);

        return app('json')->go(['token'=>$token]);

    }

    public function cache_url($url,$data)
    {
        $fig=0;
        foreach ($data as $k=>$v){
            if($fig==0){
                $url.="?$k=$v";
            }else{
                $url.="&$k=$v";
            }
            $fig=1;
        }
        return $url;
    }

}