<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/10 0010
 * Time: 14:12
 */

namespace app\model;


use ruhua\bases\BaseModel;

class FxManual extends BaseModel
{
    public static function getFxAll()
    {
        return self::with('user')->order('status asc')->select();
    }
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,headpic,mobile');
    }
    /*
     * 获取已提现金额
     * */
    public static function getMoneyOk($uid){
        $res = self::where('user_id',$uid)->where('status',1)->sum('money');
        return $res;
    }
    /*
     * 获取提现中的金额
     * */
    public static function getMoneyNow($uid){
        $res = self::where('user_id',$uid)->where('status',0)->sum('money');
        return $res;
    }

    /*
    * 手动退款操作
    * */
    public static function tuiMoneyHand($id,$msg){
        $res = self::update(['msg'=>$msg,'status'=>1],['id'=>$id]);
        return $res;
    }
    /*
   * 备注修改
   * */
    public static function MoneyMsg($id,$msg){
        $res = self::update(['msg'=>$msg],['id'=>$id]);
        return $res;
    }
}