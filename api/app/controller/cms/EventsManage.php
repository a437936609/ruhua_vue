<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/30 0030
 * Time: 11:03
 */

namespace app\controller\cms;


use app\model\DiscountGoods as DiscountGoodsModel;
use app\model\Goods;
use app\validate\DiscountValidate;
use app\model\Discount as DiscountModel;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use think\facade\Log;

class EventsManage extends BaseController
{

    /**
     * 添加专题
     * @return mixed
     */
    public function addEvents()
    {
        (new DiscountValidate())->goCheck();
        $post = input('post.');
        return DiscountModel::addDiscount($post);
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
     * 获取所有优惠活动
     * @return mixed
     */
    public function getDiscount()
    {
        $res = DiscountModel::with('discountGoods.goods')->select();
        $res=(new Goods())->get_ds_img($res);
        foreach ($res as $k=>$v){
            foreach ($v['discount_goods'] as $kk=>$vv){
                if(!$vv['goods']){
                    unset($res[$k]['discount_goods'][$kk]);
                }
            }
        }
        return app('json')->success($res);
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