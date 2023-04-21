<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/28 0028
 * Time: 10:29
 */

namespace app\model;


use app\services\TokenService;
use ruhua\bases\BaseModel;
use app\model\User as UserModel;
use ruhua\exceptions\BaseException;
use app\model\SysConfig as SysConfigModel;
use app\model\PointsRecord as PointsRecordModel;
use think\facade\Log;


class TreeRecord extends BaseModel
{
    /**
     * 种树签到
     */
    public static function add()
    {
        $is_tree=SysConfigModel::get('tree');
        if(!$is_tree){
            throw new BaseException(['msg'=>'活动已关闭']);
        }
        $uid=TokenService::getCurrentUid();
        $user=UserModel::find($uid);
        $tree_experience=SysConfigModel::get('tree_experience');
        $data=self::where('uid',$uid)->find();
        $state=self::where('uid',$uid)->whereTime('update_time','today')->find();
        if($state){
           throw new BaseException(['msg'=>'今日已浇水过']);
        }
        if($data){
            $data->save(['state'=>($data['state']+1)]);
        }else{
            self::create(['uid'=>$uid]);
        }
        $experience=$user['tree_experience']+$tree_experience;
        $sys=SysConfigModel::where('key','in',['tree_middle','tree_big','tree_small_point','tree_middle_point','tree_big_point'])->select();
        $arr=array();
        foreach ($sys as $k=>$v){
            $arr[$v['key']]=$v['value'];
        }
        Log::error($arr);
        if($experience>=0&&$experience<$arr['tree_middle']){
            $point=$arr['tree_small_point'];
        }elseif ($experience>=$arr['tree_middle']&&$experience<$arr['tree_big']){
            $point=$arr['tree_middle_point'];
        }else{
            $point=$arr['tree_big_point'];
        }
        $res=$user->save(['tree_experience'=>$experience,'points'=>($user['points']+$point)]);
        if($res){
            PointsRecordModel::create(['uid'=>$uid,'credittype'=>'tree','num'=>$point,'module'=>'qy2019_shop','remark'=>"浇水获得积分，增加$point 积分"]);
        }
        return $res;
    }
}