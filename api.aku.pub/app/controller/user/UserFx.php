<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18 0018
 * Time: 14:15
 */

namespace app\controller\user;


use app\model\FxAgent as FxAgentModel;
use app\model\FxBind as FxBindModel;
use app\model\FxManual;
use app\model\FxRecord as FxRecordModel;
use app\services\TokenService;
use ruhua\bases\BaseController;
use ruhua\exceptions\BaseException;
use think\facade\Log;

class UserFx extends BaseController
{
    public function check_fxagent($uid)
    {
        $agent=FxAgentModel::where('user_id',$uid)->find();
        if(!$agent){
            return app('json')->fail('不是代理商');
        }
    }
    /**
     * 用户查看分销收入统计
     * @return mixed
     */
    public function getFxData(){
        $uid=TokenService::getCurrentUid();
        //$this->check_fxagent($uid);
        return FxRecordModel::getFxMoneyData($uid);
    }
/*
 * 测试
 *获取全部金额
 */
    public function test($id){
        //$uid=TokenService::getCurrentUid();
        //$this->check_fxagent($uid);
        Log::error('获取全部金额');
        Log::error(FxRecordModel::getFxMoneyAll($id));
        Log::error('获取已提现金额');
        Log::error(FxManual::getMoneyOk($id));
        Log::error('获取提现中金额');
        Log::error(FxManual::getMoneyNow($id));
        return FxRecordModel::getFxMoneyAll($id);
    }


    /**
     * 用户查看申请情况
     * @return mixed
     */
    public function getFxRecord(){
        $uid=TokenService::getCurrentUid();
        //$this->check_fxagent($uid);
        return FxRecordModel::userGetRecord($uid);
    }
    /*
     * 通过id返回申请请假
     * */
    public function getFxRecordId($id){
        return FxRecordModel::userGetRecordId($id);
    }

    /**
     * 用户查看分销收入明细
     * @return mixed
     */
    public function userRecord(){
        $uid=TokenService::getCurrentUid();

        //$this->check_fxagent($uid);
        return FxRecordModel::userRecord($uid);
    }

    /**
     * 查看排名
     * @return mixed
     */
    public function getFxRank(){
        $uid=TokenService::getCurrentUid();
        //$this->check_fxagent($uid);
        return FxRecordModel::getFxRank($uid);
    }

    public function getBindUser(){
        $uid=TokenService::getCurrentUid();
        //$this->check_fxagent($uid);
        return FxBindModel::getBindUser($uid);
    }

    /**
     * 提交提现申请
     * @return mixed
     */
    public function applyTx(){
        $uid=TokenService::getCurrentUid();
        $ids=input('post.ids');
        $data = [];
        $data['bk_uname']=input('post.bk_uname');
        $data['bk_name']=input('post.bk_name');
        $data['card']=input('post.card');
        if(!$ids) return app('json')->fail('ids必填');
        return FxRecordModel::userApplyTx($uid,$ids,$data);
    }

    /**
     * 手动提现接口
     */

    public function applyApi()
    {
        $data=input('post.');
        $uid=TokenService::getCurrentUid();
        $money_all=FxRecordModel::getFxMoneyAll($uid);//获取全部金额
        $money_ok=FxManual::getMoneyOk($uid);//获取已提现金额
        $money_now=FxManual::getMoneyNow($uid);//获取提现中金额
        if($data['money']>($money_all-$money_ok-$money_now)||$data['money']<10)
            throw new BaseException(['msg'=>'提现金额不足10元或提现数字有误']);
        $time=time();
        $list=FxRecordModel::where(['agent_id'=>$uid,'status'=>0])->whereTime('update_time','<',$time)->field('id')->select();
        if(!$list) return app('json')->fail('没有记录');
        $ids=array();
        $i=0;
        foreach ($list as $k=>$v){
            $ids[$i]=$v['id'];
            $i++;
        }
        return FxRecordModel::userApplyTx($uid,$ids,$data);
    }

    /**
     * 获取用户待提现信息
     */
    public function getFxAll()
    {
        $list=FxRecordModel::with(['user'])->where('status',1)->select();
        return $list;

    }

}