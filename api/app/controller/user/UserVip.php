<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/9 0009
 * Time: 13:34
 */

namespace app\controller\user;


use app\model\VipOrder as VipOrderModel;
use app\services\TokenService;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use app\model\VipUser as VipUserModel;
use think\facade\Log;

class UserVip extends BaseController
{
    /**
     * 购买vip 下单
     * @return mixed
     */
    public function createVipOrder(){
        $post=input('post.');

        $this->validate($post,['tc_id'=>'require']);
        $uid=TokenService::getCurrentUid();
        return VipOrderModel::addVip($uid,$post['tc_id']);
    }
    public function getis_vip()
    {
        $uid=TokenService::getCurrentUid();
        $list=VipUserModel::where('user_id',$uid)->where('status',1)->find();
        $num=0;
        if($list){
            $num=1;
        }
         return app('json')->success($num);

    }



}