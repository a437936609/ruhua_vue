<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/30 0030
 * Time: 11:03
 */

namespace app\controller\cms;

use app\model\Events;
USE app\model\EventsGoods as EventsGoodsModel;
use app\model\Goods;
use app\validate\DiscountValidate;
use app\model\Events as EventsModel;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use think\facade\Log;

class EventsManage extends BaseController
{

    /**
     * 获取所有专题
     * @return mixed
     */
    public function getEvents()
    {
        $res = EventsModel::with('eventsGoods.goods')->select();
        $res=(new Goods())->get_es_img($res);
        foreach ($res as $k=>$v){
            foreach ($v['events_goods'] as $kk=>$vv){
                if(!$vv['goods']){
                    unset($res[$k]['events_goods'][$kk]);
                }
            }
        }
        return app('json')->success($res);
    }

    /**
     * 添加专题
     * @return mixed
     */
    public function addEvents()
    {
        $rule = [
            'name' => 'require|max:60',
        ];
        $post = input('post.');
        $this->validate($post, $rule,[],true);

        return EventsModel::addEvents($post);
    }

    /**
     * 修改优惠活动
     */
    public function editDiscount()
    {
        $post = input('post.');
        return DiscountModel::editDiscount($post);
    }

    /**
     * 删除优惠活动
     * @param $id
     * @return mixed
     */
    public function deleteDiscount($id)
    {
        (new IDPostiveInt)->goCheck();
        return DiscountModel::deleteDiscount($id);
    }


    /**
     * 获取限时优惠商品
     * @return mixed
     */
    public function getDiscountGoods()
    {
        $res = DiscountGoodsModeL::getAllDiscountGoods();
        Log::error('限时优惠');
        Log::error($res);
        if ($res) {
            return app('json')->success($res);
        }
        return app('json')->fail();
    }

}