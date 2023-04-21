<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/9 0009
 * Time: 13:22
 */

namespace app\controller\cms;


use app\model\VipOrder as VipOrderModel;
use app\model\VipOrder;
use app\model\VipTc as VipTcModel;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;

class VipManage extends BaseController
{
    //所有VIP订单
    public function vip_order_all()
    {
        $post=input('get.');
        $page=-1;
        $num=0;
        if(isset($post['page'])&&isset($post['num'])){
            $page=$post['page'];
            $num=$post['num'];

        }
        if($page<0)
            $res=VipOrderModel::with('userName')->where('order_status',2)->select();
        else
            $res=VipOrderModel::with('userName')->limit($page*$num,$num)->where('order_status',2)->select();
        return app('json')->go($res);
    }

    /**
     * 添加vip套餐
     * return mixed
     */
    public function addVipTc(){
        $post=input('post.');
        $this->validate($post,['price'=>'require','title'=>'require','day_num'=>'require|number']);
        return VipTcModel::addVipTc($post);
    }

    public function update_vip()
    {
        $post=input('post.');
        $res=VipTcModel::update($post,['id'=>$post['id']]);
        return app('json')->go($res);
    }

    /**
     * 删除vip套餐
     * @param $id
     * return mixed
     */
    public function deleteVipTc($id){
        (new IDPostiveInt())->goCheck();
        $res=VipTcModel::where('id',$id)->delete();
        return $res?app('json')->success():app('json')->fail();
    }

    /**
     * 获取vip套餐
     * return mixed
     */
    public function getVipTc(){
        $res=VipTcModel::select();
        return app('json')->success($res);
    }

    /*
     * cms后台给用户设置vip 为期1个月
     * */
    public function setVipCms($id){
        return VipOrder::setVipCms($id);
    }

    /*
     * cms后台取消用户vip
     * */
    public function setVipCmsNo($id){
        return VipOrder::setVipCmsNo($id);
    }



}