<?php


namespace app\model;

/*
 * 银行卡号相关
 * */

use app\services\TokenService;
use ruhua\bases\BaseModel;
use think\facade\Log;

class BankCard extends BaseModel
{
    public static function addBankCard($data){
        $uid=TokenService::getCurrentUid();
        $data['uid'] = $uid;
        $data['bk_name'] = '';
        $data['type'] = '0';
        $res = self::create($data);
        return $res;
    }
    public static function selectBankCard(){
        $uid=TokenService::getCurrentUid();
        $res = self::where('uid',$uid)->select();
        return $res;
    }

    public static function updateBankCard($data){
        $uid=TokenService::getCurrentUid();
        $data['uid'] = $uid;
        $res = self::update(['bk_uname'=>$data['bk_uname'],'card'=>$data['card'],'card_type'=>$data['card_type']],['id'=>$data['id'],'uid'=>$data['uid']]);
        return $res;
    }
    public static function delBankCard($id){
        $uid=TokenService::getCurrentUid();
        $res = self::where('id',$id)->where('uid',$uid)->delete();
        return $res;
    }
}