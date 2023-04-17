<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 10:35
 */

namespace app\controller\common;


use app\model\DiscountGoods as DiscountGoodsModel;
use app\model\FxGoods as FxGoodsModel;
use app\model\PtGoods as PtGoodsModel;
use app\model\Goods as GoodsModel;
use app\model\Rate as RateModel;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use app\model\Category as CategoryModel;
use think\facade\Log;

class Product extends BaseController
{
    /**
     * 获取某商品详情
     * @param string $id
     * @return \think\response\Json
     */
    public function getProduct($id = '')
    {
        (new IDPostiveInt())->goCheck();
        //return GoodsModel::getProduct($id);
        return app('json')->go(GoodsModel::getProduct($id));
    }

    /**
     * 获取最新商品
     * @param string $type
     * @return \think\Collection|void
     */
    public function getRecent($type = '')
    {
        $data = GoodsModel::getRecentAll($type);
        return app('json')->success($data);
    }

    /**
     * 获取优惠券能使用商品
     * @param $id
     * @return \think\Collection|void
     */
    public function getCouponProduct($id)
    {
        (new IDPostiveInt())->goCheck();
        $data = GoodsModel::getCouponProduct($id);
        return app('json')->success($data);
    }

    /**
     * 获取某商家所有商品
     * @param $id
     * @return \think\Collection
     */
    public function getShopProduct($id)
    {
        (new IDPostiveInt)->goCheck();
        $data = GoodsModel::getShopID($id);
        return app('json')->success($data);
    }

    /**
     * 获取某分类下所有商品
     * @param string $id
     * @return \think\response\Json
     */
    public function getCatePros($id='')
    {
        $post=input('get.');
        $page=0;
        $num=10;
        if(isset($post['page'])){
            $page=$post['page'];

        }
        (new IDPostiveInt())->goCheck();
        $ids=CategoryModel::where('pid',$id)->column('category_id');
        if($ids){
            array_push($ids,$id);
        }else{
            $ids=$id;
        }
        $data = GoodsModel::with('imgs')->where(['state'=>1,'category_id'=>$ids])->limit($page*$num,$num)->select();
        if (config('setting.is_business') == 1) {
            foreach ($data as $k => $v) {
                $data[$k]['discount'] = DiscountGoodsModel::getDiscountGoods($v['goods_id']);
            }
        }
        if (config('setting.is_business')) {
            foreach ($data as $k => $v) {
                $data[$k]['pt'] = PtGoodsModel::getPtGoods($v['goods_id']);
            }
        }
        return app('json')->success($data);
    }

    /**
     * 获取某个商品的所有评价
     * @param $id
     * @return \think\response\Json
     */
    public function getEvaluate($id)
    {

        (new IDPostiveInt)->goCheck();
        $list=RateModel::where('goods_id',$id)->order('create_time desc')->limit(20)->select();
        return app('json')->success($list);
    }

}