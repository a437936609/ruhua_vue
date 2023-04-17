<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14 0014
 * Time: 13:50
 */

namespace app\controller\cms;


use app\services\TokenService;
use ruhua\bases\BaseController;
use app\services\AdminService;
use ruhua\exceptions\BaseException;
use think\captcha\facade\Captcha;
use app\controller\common\Common;

class Admin extends BaseController
{
    /**
     *管理员登录
     **/
    public function login()
    {
        $post = input('post.');
        $rule = [
            'username' => 'require',
            'psw' => 'require',
            'code'=>'require'
        ];
        $verify_id=$post['verify_id'];
        $code=$post['code'];
        $data=Common::check_code($verify_id,$code);
        if(!$data){
            throw new BaseException(['msg'=>'验证码不对']);
        }

        $this->validate($post, $rule);
        return AdminService::getInstance()->loginService($post['username'], $post['psw']);
    }

    /**
     *管理员修改密码
     **/
    public function editPSW()
    {
        $post = input('post.');
        $rule = [
            'old_psw' => 'require',
            'new_psw' => 'require',
        ];
        $this->validate($post, $rule);
        return Adminservice::getInstance()->editAdminPwd($post);
    }

    /**
     * 获取验证码
     * @return \think\Response
     */
    public function getCode(){
        return Captcha::create();
    }

}