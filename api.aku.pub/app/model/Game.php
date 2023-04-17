<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28 0028
 * Time: 16:14
 */

namespace app\model;



use app\services\TokenService;
use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use app\model\GameRule as GamemRuleModel;
use Game\Game as GameExtend;
use think\facade\Log;
use app\model\PointsRecord as PointsRecordModel;
use app\model\Nav as NavModel;

class Game extends BaseModel
{
    public function nav()
    {
        return $this->belongsTo('Nav','nav_id','id');
    }

    public static function start($id)
    {
        $game=self::find($id);
        $switch=NavModel::where('id',$game['nav_id'])->value('is_visible');
        if($switch==0)
            throw new BaseException(['msg'=>'活动已关闭']);


        if(!$game){
            throw new BaseException(['msg'=>'活动不存在']);
        }
        $rule=GameRule::where('gid',$id)->field('id,value,stock')->select();

        if($game['reword']==1){
            $end=GamemRuleModel::where(['gid'=>$id])->where('stock','<>',0)->find();
            if(!$end){
                self::update(['state'=>0],['id'=>$id]);
                throw new BaseException(['msg'=>'活动已关闭']);
            }
        }

        if($game['type']==0)
        {

            $res=self::point($rule,$game);
        }
        elseif ($game['type']==1)
        {

            $res=self::tice($rule,$game);
        }
        else{

            $res=self::tice_points($rule,$game);
        }
        return $res;
    }

    /**积分抽奖
     * @param $game
     */
    public static function point($rule,$game)
    {

        $uid=TokenService::getCurrentUid();
        $user=User::where('id',$uid)->find();

        if($user['points']<$game['integral']){
            throw new BaseException(['msg'=>'积分不够']);
        }

        User::update(['points'=>($user['points']-$game['integral'])],['id'=>$uid]);
        $res=(new GameExtend())->start($rule,$game['reword']);

        self::game_record($res);
        PointsRecordModel::create(['uid'=>$uid,'credittype'=>'game','num'=>$game['integral'],'module'=>'qy2019_shop','remark'=>"抽奖消耗积分,减少{$game['integral']}分"]);
        if($game['reword']==1){

            self::reduce_stock($res);
        }
        return $res;
    }

    public static function tice($rule,$game)
    {

        $uid=TokenService::getCurrentUid();
        $tice=GameRecord::where(['uid'=>$uid,'gid'=>$game['id']])->whereTime('create_time','today')->count();

        if($tice>=$game['tice'])
            throw new BaseException(['msg'=>'今日抽奖次数已达到限制']);
        $res=(new GameExtend())->start($rule,$game['reword']);
        self::game_record($res);

        if($game['reword']==1){
            self::reduce_stock($res);
        }

        return $res;
    }

    public static function tice_points($rule,$game)
    {
        $uid=TokenService::getCurrentUid();
        $tice=GameRecord::where(['uid'=>$uid,'gid'=>$game['id']])->whereTime('create_time','today')->count();
        if($tice>=$game['tice'])
            throw new BaseException(['msg'=>'今日抽奖次数已达到限制']);
        $user=User::where('id',$uid)->find();
        if($user['points']<$game['integral']){
            throw new BaseException(['msg'=>'积分不够']);
        }
        User::update(['points'=>($user['points']-$game['integral'])],['id'=>$uid]);
        $res=(new GameExtend())->start($rule,$game['reword']);
        self::game_record($res);
        PointsRecordModel::create(['uid'=>$uid,'credittype'=>'game','num'=>$game['integral'],'module'=>'qy2019_shop','remark'=>"抽奖消耗积分,减少{$game['integral']}分"]);
        if($game['reword']==1){
            self::reduce_stock($res);
        }
        return $res;

    }


    /**抽奖记录
     * @param $rule
     */
    public static function game_record($res)
    {
        $uid=TokenService::getCurrentUid();
        $rule=GamemRuleModel::where('id',$res['id'])->find();
        if($rule['type']==2)
            return;
        $data=[
            'uid'=>$uid,
            'gid'=>$rule['gid'],
            'content'=>$rule['content']
        ];
        GameRecord::create($data);
    }

    /**库存减少
     * @param $res
     */
    public static function reduce_stock($res)
    {

        $rule=GamemRuleModel::where('id',$res['id'])->find();
        GamemRuleModel::update(['stock'=>$rule['stock']-1],['id'=>$rule['id']]);
        $end=GamemRuleModel::where(['gid'=>$rule['gid']])->where('stock','<>',0)->find();
        if(!$end){
            self::update(['state'=>0],['id'=>$rule['gid']]);
        }

    }
}