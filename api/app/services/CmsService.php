<?php

namespace app\services;

use app\model\Coupon as CouponModel;
use app\model\Article as ArticleModel;
use app\model\BannerItem as BannerItemModel;
use app\model\Category as CategoryModel;
use app\model\Order as OrderModel;
use app\model\OrderGoods;
use app\model\OrderLog;
use app\model\User;
use ruhua\exceptions\OrderException;
use ruhua\interfaces\RoleInterface;
use think\facade\Log;

class CmsService implements RoleInterface
{
    private $param;

    public function set_param($param){
        $this->param=$param;
    }
    //文章列表
    public function get_article_list()
    {
        // TODO: Implement get_article_list() method.
        $data = ArticleModel::select();
        return $data;
    }

    //广告列表
    public function get_banner_list()
    {
        $data = BannerItemModel::with(['imgs', 'banner'])->select();
        return $data;
    }

    //分类列表
    public function get_category_list()
    {
        $data=CategoryModel::with('imgs')->order('sort asc')->select();
        return $data;
    }

    //优惠券列表
    public function get_coupon_list()
    {
        $coupon = CouponModel::select();
        return $coupon;
    }

    //订单列表
    public function get_order_list()
    {
        $post=input('post.');
        $where=[];
        $os='';
        $page=-1;
        $num=0;
        if(isset($post['page'])&&isset($post['num'])){
            $page=$post['page'];
            $num=$post['num'];
        }
        if(isset($post['num']) && !empty($post['num'])) {
            $where[] = ['prepay_id', 'like', '%' . trim($post['num']) . '%'];
        }
        if(isset($post['user_name']) && !empty($post['user_name'])) {
            $name=base64_encode(trim($post['user_name']));
            $uid=User::where('nickname','like', $name)->value('id');
            $where[] = ['user_id','=',$uid];
        }
        //收货人手机
        if(isset($post['mobile']) && !empty($post['mobile'])) {
            $mobile=trim($post['mobile']);
            $where[] = ['receiver_mobile','=',$mobile];
        }

        if(isset($post['filter_status'])){
            //订单状态 值里有空和负数
            if(strlen($post['filter_status']) > 0)
            {
                $filter_status=$post['filter_status'];
                $where[] = ['state','=',$filter_status];
            }else{
                $where[] = ['state','<>',-3];
            }
        }

        if(isset($post['filter_pay'])){
            //支付状态 值里有空和负数
            if(strlen($post['filter_pay']) > 0)
            {
                $filter_pay=$post['filter_pay'];
                $where[] = ['payment_state','=',$filter_pay];
            }
        }

        if(isset($post['filter_send'])){
            //发货状态 值里有空和负数
            if(strlen($post['filter_send']) > 0)
            {
                $filter_send=$post['filter_send'];
                $where[] = ['shipment_state','=',$filter_send];
            }
        }

        if(isset($post['pro_name']) && !empty($post['pro_name'])) {
            $order_list=OrderGoods::where('goods_name','like', '%' . trim($post['pro_name']) . '%')->column('order_id');
            $where[] = ['order_id', 'in', $order_list];
        }

        if(isset($post['pt'])&&!empty($post['pt'])){
            $where[]=['item_id','>','0'];
            $os="is_captain,item_id,";
        }
        //去除时间戳小数点后面的值
        if(isset($post['stat_time'])&&!empty($post['stat_time'])&&isset($post['end_time'])&&!empty($post['end_time'])){
            $where[]=[['create_time','>=',intval($post['stat_time'])],['create_time','<=',intval($post['end_time'])]];
        }else{
            //不选日期的话，默认一个月
            //$post['stat_time']=strtotime(date("Y-m-d H:i:s", strtotime("-1 month")));
            $post['stat_time']  =   strtotime("-1 month");
            $post['end_time']   =   time();
            $where[]=[['create_time','>=',$post['stat_time']],['create_time','<=',$post['end_time']]];
        }
        if($page<0) {

            //原始代码 不用获取用户信息
            $data = OrderModel::with('ordergoods.imgs')->where($where)
                ->order('create_time desc')->field("$os order_id,order_num,prepay_id,user_id,item_id,pt_state,state,payment_state,shipment_state,
            delete_time,update_time,pay_time,shipping_money,order_money,receiver_name,receiver_mobile,user_ip,message,create_time", true)
                ->limit(1000)->select();


            //原始代码 获取用户信息
//            $data = OrderModel::with(['ordergoods.imgs', 'users' => function ($query) {
//                $query->field('id,nickname,headpic');
//            }])->where($where)->where('state', '<>', -3)
//                ->order('create_time desc')->field("$os order_id,order_num,prepay_id,user_id,item_id,pt_state,state,payment_state,shipment_state,
//            delete_time,update_time,pay_time,shipping_money,order_money,user_ip,message,create_time", true)
//                ->limit(1000)->select();
        }else {

            //原始代码 不用获取用户信息
            $data = OrderModel::with('ordergoods.imgs')->where($where)
                ->order('create_time desc')->field("$os order_id,order_num,prepay_id,user_id,item_id,pt_state,state,payment_state,shipment_state,
                delete_time,update_time,pay_time,shipping_money,order_money,receiver_name,receiver_mobile,user_ip,message,create_time", true)
                ->limit($page * $num, $num)->select();


            //原始代码 获取用户信息
//            $data = OrderModel::with(['ordergoods.imgs', 'users' => function ($query) {
//                $query->field('id,nickname,headpic');
//            }])->where($where)
//                ->order('create_time desc')->field("$os order_id,order_num,prepay_id,user_id,item_id,pt_state,state,payment_state,shipment_state,
//                delete_time,update_time,pay_time,shipping_money,order_money,user_ip,message,create_time", true)
//                ->limit($page * $num, $num)->select();
        }
        return $data;
    }
    //订单详情
    public function get_order_detail($id)
    {
        Log::error('123456');
        //'ordergoods.imgs','imgs',
        $tes = OrderModel::where(['order_id' => $id])->find();
        if(isset($tes['goods_picture'])){
            $data['order'] = OrderModel::with(['ordergoods.imgs','imgs','users' => function ($query) {
                    $query->field('id,nickname,headpic');
                }])->where(['order_id' => $id])->find();
        }
        else{
            $data['order'] = OrderModel::with(['ordergoods.imgs','users' => function ($query) {
                    $query->field('id,nickname,headpic');
                }])->where(['order_id' => $id])->find();
        }

        Log::error('654321');
        $data['log'] = OrderLog::where(['order_id' => $id])->order('create_time desc')->select();
        return $data;
    }

}