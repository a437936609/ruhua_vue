<?php

namespace app\services;


use app\model\UserCoupon as UserCouponModel;
use app\model\Article as ArticleModel;
use app\model\BannerItem as BannerItemModel;
use app\model\Category as CategoryModel;
use app\model\Brands as BrandsModel;
use app\model\Coupon as CouponModel;
use app\model\Order as OrderModel;
use app\model\Tui as TuiModel;
use app\model\User as UserModel;
use ruhua\interfaces\RoleInterface;
use think\facade\Log;
use think\facade\Request;

class UserService implements RoleInterface
{

    //文章列表
    public function get_article_list()
    {
        // TODO: Implement get_article_list() method.
        $data = ArticleModel::where(['is_hidden' => 1])->select();
        return $data;
    }

    //广告列表
    public function get_banner_list()
    {
        $data = BannerItemModel::with(['imgs', 'banner'])->where('is_visible', 1)->order('sort asc')->select();
        return $data;
    }

    //品牌列表
    public function get_brands_list()
    {
        $data = BrandsModel::with('imgs')->where('is_visible', 1)->order('sort asc')->select();
        if (!$data || count($data) < 1) {
            return app('json')->fail('请至少添加一个商品品牌');
        }
        return $data;
    }

    //分类列表
    public function get_category_list()
    {
        $data = CategoryModel::with('imgs')->where('is_visible', 1)->order('sort asc')->select();
        if (!$data || count($data) < 1) {
            return app('json')->fail('请至少添加一个商品分类');
        }
        return $data;
    }
    //获取分类页-产品，所有产品数据
    public function get_category_goods_list($pid = ''){
        $data = CategoryModel::with('goods.imgs')->where('pid',$pid)->select();
        return $data;
    }

    //优惠券列表
    public function get_coupon_list()
    {
        $uid = TokenService::getCurrentUid();
        $data['type'] = 1;
        $data['shop_id'] = 1;
        $data['region_id'] = 0;
        $user_coupon = UserCouponModel::where('user_id', $uid)->select();
        $res = CouponModel::where($data)->where('is_appoint',0)->select();
        foreach ($res as $k => $v) {
            $res[$k]['uesr_status'] = 1;
            if (strtotime($v['end_time']) && strtotime($v['end_time']) < time()) {
                unset($res[$k]);
                continue;
            }
            if ($v['goods_ids'] != 0) {
                $res[$k]['goods_ids'] = explode(',', $v['goods_ids']);
            }
            if ($v['status'] == 1) {
                foreach ($user_coupon as $value) {
                    if ($v['id'] == $value['coupon_id']) {
                        $res[$k]['uesr_status'] = 0;
                        break;
                    }
                }
            } else if ($v['status'] == 2) {

                foreach ($user_coupon as $value) {
                    if ($v['id'] == $value['coupon_id'] && $value['status'] < 2) {
                        $res[$k]['uesr_status'] = 0;
                        break;
                    }
                }
            }

        }
        return $res;
    }

    //订单列表
    public function get_order_list()
    {
        $uid = TokenService::getCurrentUid();
        $post=input('get.');
        $page=-1;
        $num=0;
        if(isset($post['page'])&&isset($post['num'])){
            $page=$post['page'];
            $num=$post['num'];

        }
        if($page<0)
            $data = OrderModel::with(['OrderGoods.imgs'=>function($q){
            return $q->field('id,order_id,goods_id,goods_name,sku_name,price,num,total_price as goods_money,pic_id');
        }, 'Imgs'])->where(['user_id' => $uid])
            ->field('order_id,order_num,prepay_id,user_id,type,state,payment_state,shipping_money,shipment_state,order_money,activity_type,create_time')
            ->order('order_id desc')->select()->toArray();
        else
            $data = OrderModel::with(['OrderGoods.imgs'=>function($q){
                return $q->field('id,order_id,goods_id,goods_name,sku_name,price,num,total_price as goods_money,pic_id');
            }, 'Imgs'])->where(['user_id' => $uid])
                ->field('order_id,order_num,prepay_id,user_id,type,state,payment_state,shipping_money,shipment_state,order_money,activity_type,create_time')
                ->order('order_id desc')->limit($page*$num,$num)->select()->toArray();
        foreach ($data as $key => $value)
        {
            $x= substr( $value['OrderGoods'][0]['imgs']['url'], 0, 1 );
            if($x!="h"){
                $url=Request::domain();
            }
            else
                $url="";
            $data[$key]['OrderGoods'][0]['imgs']['url']=$url. $data[$key]['OrderGoods'][0]['imgs']['url'];
        }
        return $data;
    }



    //订单详情
    public function get_order_detail($id)
    {
        $uid = TokenService::getCurrentUid();
        $auth = UserModel::where('id', $uid)->value('web_auth_id');
        $where['order_id'] = $id;
        if ($auth == 0) {
            $where['user_id'] = $uid;
        }
        $data = OrderModel::with(['rate', 'ordergoods.imgs'])->where($where)->find(); //常规查询自动不包含软删除的数据

        //===================================
        if(!empty($data['courier_num'])){
            $courier = [];
            $courierArray = [];
            //433200798387676/433200798387676/JT3031074245539
            //转化成数组
            $courier_num    = explode('/', $data['courier_num']);
            //YD/YD/JTSD
            $courier        = explode('/', $data['courier']);

            //判断快递包裹是否为多个
            foreach ($courier_num as $key => $val) {

                if(count($courier) > 1){
                    $courier_ = $courier[$key];
                }else{
                    $courier_ = $courier[0]; //默认 手动发货 快递编码字段只有一个，快递编号是多个。
                }

                $courierArray[$key] = [
                    'courier_num' => $val,
                    'courier' => $courier_,
                    'courier_time' => $data['courier_time'],
                ];
            }

            //兼容之前的类型获取快递数组第一个数据
            $data['courier_num']   = $courierArray[0]['courier_num'];
            $data['courier']       = $courierArray[0]['courier'];
            $data['courierList']   = $courierArray?$courierArray:[];
        }
        //===================================

        if (!$data) {
            return app('json')->fail('获取订单数据失败');
        }
        $tui_data = TuiModel::where('order_id', $id)->order('id desc')->select();
//        $data['rate'] = RateModel::where('order_id', $id)->find();
        if ($data['yz_code']) {
            $data['qrcode'] = (new QrcodeServer())->get_qrcode($data['yz_code']);
        }
        if ($tui_data) {
            $data['tui'] = $tui_data;
        }
        return $data;
    }
}