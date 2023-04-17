<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/9 0009
 * Time: 13:40
 */

namespace app\model;


use ruhua\bases\BaseModel;

class VipUser extends BaseModel
{

    //查询用户是否vip
    public static function getis_vip( $uid)
    {
        $list=self::where('user_id',$uid)->where('status',1)->find();
        $num=0;
        if($list){
            $num=1;
        }
        return $num;
    }


}