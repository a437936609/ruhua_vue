<?php

namespace app\controller\auth;


use Aliyun\api_demo\SmsDemo;
use app\model\SysConfig as SysConfigModel;
use app\model\SysConfig;
use app\model\User as UserModel;
use ruhua\bases\BaseController;
use ruhua\exceptions\BaseException;
use think\facade\Cache;
use think\facade\Log;
use app\model\MsgLog as MsgLogModel;
use Icbc\IcbcApi;

class Mobile extends BaseController
{
    /**
     * 手机登录获取验证码
     * return mixed
     */
    public function getLoginCode()
    {

        if (app('system')->getValue('merge_mode') == 3||app('system')->getValue('login_mode')>0) {
            $post = input('post.');
            $this->validate($post, ['mobile' => 'require|length:11']);
            $mobile_user = UserModel::where('mobile', $post['mobile'])->find();
            //验证码目前是放数据库；可以改为放缓存，手机号做key，获取后删除；
            if ($mobile_user) {
                $data['code'] = rand(10000, 999999);
                $data['code_time'] = time() + (60 * 5);
                $mobile_user->save($data);
            } else {
                Log::error('888888777777');
                $data['mobile'] = $post['mobile'];
                $data['code'] = rand(10000, 999999);
                $data['code_time'] = time() + (60 * 5);
                UserModel::create($data);
            }
            //Log::channel('msgLog')->write('发送短信验证');
            $yzm_tmp_id=SysConfigModel::where('key', 'yzm_tmp_id')->value('value');
            SmsDemo::sendSms($post['mobile'], $data['code'],$yzm_tmp_id);
            $data['code'] = 1;
            return app('json')->success($data);

        } else {
            return app('json')->fail('未开启手机登录');
        }
    }

    public function mobile_login_code()
    {
        if(app('system')->getValue('login_mode')>0){

            $mobile=input('post.mobile');
            $data['code'] = rand(10000, 999999);
            $data['mobile']=$mobile;
            $msglog=MsgLogModel::where('mobile',$data['mobile'])->find();
            if($msglog){
                $msglog->save($data);
            }else{
                MsgLogModel::create($data);
            }
            $yzm_tmp_id=SysConfigModel::where('key', 'yzm_tmp_id')->value('value');
           // SmsDemo::sendSms($data['mobile'], $data['code'],$yzm_tmp_id);
            return app('json')->go($data);

        }else{
            throw new BaseException(['msg'=>'模式未开启']);
        }
    }

    /**
     * 手机登录获取token
     * @return string
     */
    public function getMobileToken()
    {
        if (app('system')->getValue('merge_mode') == 3||app('system')->getValue('login_mode')) {
            $post = input('post.');
            $this->validate($post, ['mobile' => 'require|length:11','code'=>'require']);
            //$res = UserModel::where(['mobile' =>$post['mobile'], 'code' => $post['code']])->whereTime('code_time', '>', time())->find();
           // $res = UserModel::where(['mobile' =>$post['mobile'], 'code' => $post['code']])->find(); //用于测试
            $code=MsgLogModel::where(['mobile'=>$post['mobile'],'code'=>$post['code']])->find();
            if(!$code){
                $code->save(['code'=>'']);
                throw new BaseException(['msg'=>'验证码错误']);
            }
            $code->save(['code'=>'']);
            $user=UserModel::where('mobile',$post['mobile'])->find();
            if(!$user){
                $res=UserModel::create(['mobile'=>$post['mobile']]);
                $res['state']=false;
                $res['token']='';
                return app('json')->go($res);
            }
            $cache['uid'] = $user['id'];
            $cache['scope'] = 9;  // 推荐用枚举
            $cache['mobile']=$user['mobile'];
            $cache['state']=$user['state'];
            $cache['login_mode']=SysConfig::get('login_mode');
            $token = (new Token())->saveCache($cache);
            $data=[
                'state'=>true,
                'token'=>$token
            ];
            MsgLogModel::where('mobile',$post['mobile'])->delete();
            return app('json')->go($data);
        } else {
            return app('json')->fail('未开启手机登录');
        }
    }


    public function login_by_icbc_key()
    {
        $userInfoKey        =       input('post.userInfoKey');
        if(!$userInfoKey){
            throw new BaseException(['msg'=>'未获取到userInfoKey']);
        }
        // 解密工行userInfoKey
        $icbc_config                                =   [];
        $icbc_config['icbc_server_url']             =   SysConfig::get('icbc_server_url');
        $icbc_config['icbc_appid']                  =   SysConfig::get('icbc_appid');
        $icbc_config['icbc_mer_private_key']        =   SysConfig::get('icbc_mer_private_key');
        $icbc_config['icbc_platform_public_key']    =   SysConfig::get('icbc_platform_public_key');
        Log::channel('msgLog')->write("userInfoKey:".json_encode($userInfoKey));
        $resp           =           IcbcApi::getUserInfo($icbc_config, $userInfoKey);
        Log::channel('msgLog')->write("resp".json_encode($resp));
        #$resp = [];
        #$resp['mobile'] = '18901737789';
        #$resp['userId'] = '201511300025429190';
        $user           =           UserModel::where('mobile', $resp['mobile'])->find();
        if(!$user){
            UserModel::create(['mobile'=>$resp['mobile']]);
        }
        $user           =           UserModel::where('mobile', $resp['mobile'])->find();
        if(!$user){
            $user       =           UserModel::create(['mobile'=>$resp['mobile']]);
        }
        if(!$user){
            throw new BaseException(['msg'=>'创建用户失败,请重试']);
        }
        $data['icbc_user_id']       =       $resp['userId'];
        #$data['nickname']           =       $resp['mobile'];
        $user->save($data);

        $cache['uid']               =       $user['id'];
        $cache['scope']             =       9;  // 推荐用枚举
        $cache['mobile']            =       $user['mobile'];
        $cache['state']             =       $user['state'];
        $cache['login_mode']        =       SysConfig::get('login_mode');
        $token = (new Token())->saveCache($cache);
        $data=['state' => true, 'token' => $token];
        return app('json')->go($data);
    }

}