<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 9:03
 */

namespace app\controller\user;


use Aliyun\api_demo\SmsDemo;
use app\model\SysConfig;
use app\model\User as UserModel;
use app\services\TokenService;
use app\model\VipUser as VipUserModel;
use ruhua\bases\BaseController;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\TokenException;
use think\facade\Cache;
use think\facade\Log;
use app\model\TreeRecord as TreeRecordModel;
use app\model\MsgLog as MsgLogModel;

class User extends BaseController
{
    /**
     * 模拟用户登录获取TOKEN
     * @return mixed
     */
    public function userLogin()
    {
        return (new TokenService())->saveCache(['uid' => 15,'openid' => 'oq_jb4mLWx97WOEn7x38yM0YkFhs']);
    }
    
    /**
     * 获取用户基础信息
     */
    public function getInfo()
    {
        $uid = TokenService::getCurrentUid();
        Log::error($uid);
        if($uid==-1){
            return app('json')->go(['msg'=>'账号未提交审核','code'=>1]);
        }
        if($uid==-2){
            return app('json')->go(['msg'=>'账号审核中','code'=>2]);
        }
        $res=UserModel::with('vip')->field('id,nickname,headpic,mobile,web_auth_id as web_auth,invite_code as sfm,points,tree_experience,start,invite_mobile',true)->find($uid);

        if($res&&$res['vip']!=null){
            $uid=TokenService::getCurrentUid();
            $list=VipUserModel::where('user_id',$uid)->find();
            $currentTime=time();
            $cnt=$list['end_time']-$currentTime;
            $days = floor($cnt/(3600*24));
            if ($days+1<=0){
                VipUserModel::update(['status'=>0],['id'=>$list['id']]);
            }

        }
        return app('json')->success($res);
    }


    /**
     * 获取发票信息
     * @return mixed
     */
    public function getCpy(){
        $uid = TokenService::getCurrentUid();
        $data= UserModel::getCpyInfo($uid);
        return app('json')->success($data);
    }

    /**
     * 修改
     * @return mixed
     */
    public function editCpy(){
        $uid = TokenService::getCurrentUid();
        Log::error("");
        $post=input('post.');
        $this->validate($post,['cpy_name'=>'require','cpy_num'=>'require','email'=>'require','user_name'=>'require']);
        return UserModel::editCpy($post,$uid);
    }

    /**
     * 获取小程序码
     * @return mixed
     */
    public function getXcxCode(){
        $uid=TokenService::getCurrentUid();
        $path=input('post.path');
        $scene=input('post.scene');
        return (new UserModel)->getXcxInviteUrl($uid,$path,$scene);
    }

    /**
     * 获取二维码
     * @return mixed
     */
    public function getWebCode(){
        $uid=TokenService::getCurrentUid();
        $path=input('post.path');
        $scene=input('post.scene');
        return (new UserModel)->getWebInviteUrl($uid,$path,$scene);
    }

    public function gzh_bind_code($mobile)
    {
        $uid=TokenService::getCurrentUid();
        $user=UserModel::where('id',$uid)->find();
        Cache::tag('tag')->set('mobile_'.$uid,$mobile);
        if ($user['mobile']){
            throw new TokenException(['msg' => '用户电话已绑定']);
        }
        $code=rand(100000,999999);
        $res=UserModel::update(['gzg_code'=>$code],['id'=>$uid]);
        SmsDemo::sendSms($mobile,$code);
        return app('json')->success();
    }

    public function bind_mobile($code)
    {
        $uid=TokenService::getCurrentUid();
        $user=UserModel::where('id',$uid)->find();
        $mobile=Cache::tag('tag')->get('mobile_'.$uid);
        if(!$mobile){
            throw new TokenException(['msg' => '非法操作']);
        }
        if($user&&$user['gzg_code']==$code){

            $res=UserModel::update(['mobile'=>$mobile,'gzg_code'=>''],['id'=>$uid]);
            Cache::tag('tag')->clear();
            return app('json')->go($res);

        }else{
            throw new TokenException(['msg' => '验证码错误']);
        }
    }

    public function tree()
    {
        $res=TreeRecordModel::add();
        return app('json')->go($res);
    }

    //用户注册  审核模式时使用
    public function mobileAdduser(){
        $data=input('post.');
        $uid=TokenService::getCurrentUid();
        $res = UserModel::update(['mobile'=>$data['mobile'],'start'=>1],['id'=>$uid]);
        return app('json')->go($res);
    }

    public function register()
    {
        $data=input('post.');
        $login_mode=SysConfig::get('login_mode');
        if($login_mode==1){

            throw new BaseException(['msg'=>'非法操作']);
        }
    //    $uid=TokenService::getCurrentUid();
        $user=UserModel::where('mobile',$data['mobile'])->find();
        if($user){
            throw new BaseException(['msg'=>'已注册']);
        }
        $code=MsgLogModel::where(['mobile'=>$data['mobile'],'code'=>$data['code']])->find();
        if(!$code){
            throw new BaseException(['msg'=>'验证码过期或错误']);
        }
        $code->save(['code'=>'']);
        if($login_mode==2){
            $data['start']=0;
            $res=UserModel::create($data);
         //   $res=$user->save(['mobile'=>$data['mobile']]);
        }
        if($login_mode==3){
            if(!isset($data['invite_mobile'])){
                throw new BaseException(['msg'=>'请输入邀请人']);
            }
            $invite=UserModel::where('mobile',$data['invite_mobile'])->find();
            if(!$invite)
                throw new BaseException(['msg'=>'邀请号码不存在']);
            $res=UserModel::create($data);
          ///  $res=$user->save(['mobile'=>$data['mobile'],'invite_mobile'=>$data['invite_mobile']]);
        }
        $reg=UserModel::find($res['id']);
        $cache['scope'] = 9;
        $cache=[
            'uid'=>$reg['id'],
            'scpoe'=>9,
            'mobile'=>$reg['mobile'],
            'state'=>$reg['state'],
            'login_mode'=>$login_mode
        ];
        $token=(new TokenService())->saveCache($cache);
        return app('json')->go(['token'=>$token]);
    }

    public function get_is_state($mobile)
    {
        $login_mode=SysConfig::get('login_mode');
        if($login_mode==0){
            return app('json')->go(['data'=>1]);
        }
        $state=UserModel::where('mobile',$mobile)->value('state');
        return app('json')->go(['data'=>$state]);
    }


}