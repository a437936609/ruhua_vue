<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/27 0027
 * Time: 16:07
 */

namespace Game;
use app\model\Nav as NavModel;
use app\services\TokenService;
use think\facade\Log;
use app\model\GameRule as GameRuleModel;
use app\model\User as UserModel;

class Game
{

    public function start($data,$type)
    {
        if($type==1)
            $res=$this->probability_stock($data);
        else
            $res=$this->probability($data);
        $rule=GameRuleModel::where('id',$res['id'])->find();
        if($rule['type']==0){
            $uid=TokenService::getCurrentUid();
            $user=UserModel::find($uid);
            $user->save(['points'=>($user['points']+$rule['points'])]);
        }

        return $res;
    }

    /**
     * 概率抽奖(无库存，只有id和概率)
     */
    public function probability($shops)
    {
        $proArr=array();
        foreach ($shops as $k=>$v)
        {
            $proArr[$k]=(int)$v['value']*100;
        }
        $res=$this->get_rand($proArr);
        return $shops[$res];
    }

    /**概率抽奖，有库存
     * @param $shops
     */
    public function probability_stock($shops)
    {
        $proArr=array();
        foreach ($shops as $k=>$v)
        {
            if($v['stock']==0){
                $proArr[$k]=0;
                continue;
            }
            $proArr[$k]=(int)$v['value']*100;
        }
        $res=$this->get_rand($proArr);
        return $shops[$res];
    }

    public function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }


    public static function update_state($id,$state){
        $res=NavModel::update(['is_visible'=>$state],['id'=>$id]);
        Log::error($res);
        return app('json')->go($res);
    }

}