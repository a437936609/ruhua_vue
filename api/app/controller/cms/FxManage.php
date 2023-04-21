<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31 0031
 * Time: 16:55
 */

namespace app\controller\cms;


use app\model\FxBind;
use app\model\FxGoods as FxGoodsModel;
use app\model\FxRecord as FxRecordModel;
use app\services\StatisticService;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use app\model\FxAgent as FxAgentModel;
use app\model\User as UserModel;
use ruhua\exceptions\BaseException;
use think\facade\Log;

class FxManage extends BaseController{

    /**
     * 设置分销商品
     * @return mixed
     */
    public function addFxGoods(){
        $post=input('post.');
        return FxGoodsModel::addGoods($post);
    }

    /**
     * cms获取分销商品
     * @param $type
     * @return array|\think\Collection
     */
    public function getFxGoods($type){
        return FxGoodsModel::getGoods($type);
    }

    /**
     * 修改分销提成价格
     * @return mixed
     */
    public function editFxGoods(){
        $rule=[
            'id'=>'require',
            'price'=>'require'
        ];
        $post=input('post.');
        $this->validate($post,$rule);
        return FxGoodsModel::editPrice($post['id'],$post['price']);
    }

    /**
     * 删除分销商品
     * @param string $id
     * @return mixed
     */
    public function delFxGoods($id=''){
        (new IDPostiveInt())->goCheck();
        return FxGoodsModel::deleteFxGoods($id);
    }

    /**
     * cms获取分销记录
     * @return mixed
     */
    public function getFxRecord(){

        if(app('system')->getValue('fx_status')==0){
            return app('json')->fail('未开启分销活动');
        }
        return FxRecordModel::getRecord();
    }

    /**
     * 获取分销统计排行
     * @return mixed
     */
    public function countFx(){
        return StatisticService::countFx();
    }

    /**
     * 手动打款成功修改状态
     * @param $id
     * @return mixed
     */
    public function editTxApply($id,$card){
        (new IDPostiveInt)->goCheck();
        return FxRecordModel::agreeTxApply($id,$card);
    }

    public function refuseTxApply($id)
    {
        (new IDPostiveInt)->goCheck();
        return FxRecordModel::refuseTxApply($id);
    }


    /**
     * 用户获取分销商品
     * @return array|\think\Collection
     */
    public function selectFxGoods(){
        return FxGoodsModel::selectFxGoods();
    }

    /**
     * 获取所有分销商
     */
    public function get_fx_agent()
    {
        $list =FxAgentModel::with('user')->select()->toArray();
        $data = StatisticService::countFxone();
        //赋值
        for($i=0;$i<count($list);$i++){
            $list[$i][ 'all_money']=0;//总佣金
            $list[$i][ 'money']=0;//未提现佣金
            $list[$i][ 'num']=0;//订单数
            for($j=0;$j<count($data);$j++){
                if($list[$i]['agent_id']==$data[$j]['agent_id']){
                    $list[$i]['all_money']=$data[$j]['all_money'];//总佣金
                    $list[$i]['money']=$data[$j]['money'];//未提现佣金
                    $list[$i]['num']=$data[$j]['num'];//订单数
                }
            }
            $list[$i]['lower']=FxBind::getBindUserCount($list[$i]['agent_id']);
            $list[$i][ 'moneyok']=$list[$i][ 'all_money']-$list[$i][ 'money'];//已提现佣金

        }
        return app('json')->go($list);
    }


    /**
     * 获取非分销商用户
     */

    public function get_no_fx_agent()
    {
        $fx=FxAgentModel::field('user_id')->select();
        if(!count($fx)){
            //return app('json')->go(null);
            $ids[0]=0;
        }
        for($i=0;$i<count($fx);$i++){
            $ids[$i]=$fx[$i]['user_id'];
        }
        $user=UserModel::where('id','not in',$ids)->field("id,nickname,headpic,mobile,invite_code")->select();
        return app('json')->go($user);


    }

    /**
     * 设置用户为分销商
     * @param $uid
     *
     */

    public function add_fx_agent()
    {
        $data=input('post.ids');
        FxAgentModel::add_fx_agent($data);
        return app('json')->go(['msg'=>'添加成功']);
    }

    /**取消分销商
     * @param $uid
     */

    public function cancel_fx_agent($uid)
    {
        $fx=FxAgentModel::where('user_id',$uid)->find();
        if(!$fx){
            throw new BaseException(['msg'=>'用户不是分销商，取消失败']);
        }
        $res=FxAgentModel::where('user_id',$uid)->delete();
        return app('json')->go($res);

    }

}