<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/4 0004
 * Time: 8:41
 */

namespace app\controller\cms;


use app\model\User as UserModel;
use ruhua\bases\BaseController;
use think\facade\Log;

class UserManage extends BaseController
{
    /**
     * 获取所有用户信息
     * @return mixed
     */
    public function getAllUser(){
       return UserModel::getAllUser();
    }

    public function trial_user($uid,$start)
    {
        $res=UserModel::update(['start'=>$start],['id'=>$uid]);
        return app('json')->go($res);
    }
    /*
     * 按昵称获取用户
     * */
    public function user_name($name){
        $name=base64_encode($name);
        $res = UserModel::where('nickname','like','%'.$name.'%')->field('id,nickname')->select();
        return app('json')->go($res);
    }
}