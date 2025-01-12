<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 10:06
 */

namespace app\controller\cms;

use app\model\Order as OrderModel;
use app\services\TokenService;
use app\model\OrderGoods as OrderGoodsModel;
use app\validate\IDPostiveInt;
//use ruhua\bases\BaseController;
use app\BaseController;
use ruhua\services\QyFactory;
use think\facade\Log;
use think\facade\Request;

class OrderManage extends BaseController
{
    /**
     * 代发货订单提醒
     * @return mixed
     */
    public function checkDrive()
    {
        $res = OrderModel::where(['state' => 0, 'payment_state' => 1, 'shipment_state' => 0])->count();
        return app('json')->success($res);
    }


    /**
     * cms删除订单
     * @param string $id
     * @return int
     */
    public function deleteOrder($id = '')
    {
        return app('json')->success();
        (new IDPostiveInt)->goCheck();
        $result = OrderModel::where('order_id', $id)->find(); //这里是软删除
        if (!$result) {
            return app('json')->fail();
        }
        if (!$result->delete(config('setting.soft_del'))) {
            return app('json')->fail();
        }
        return app('json')->success();
//        return $result?1:0;
    }

    /**
     * CMS获取所有订单简要
     * @return string
     */
    public function getOrderAll()
    {
        $key = $this->request->param('keywords') ?: '';
        $order = (new QyFactory())->instance('CmsService');
        $order->set_param($key);
        $data = $order->get_order_list();
        return app('json')->success($data);
    }
    /**
     * 商品销量统计
     * */
    public function order_stats(){
        $data = OrderGoodsModel::order_stats();
        return app('json')->success($data);
    }
    /**
     * mscs获取订单
     * @return string
     */
    public function mCmsGetOrder()
    {
        $data = OrderModel::with(['ordergoods.imgs', 'users' => function ($query) {
            $query->field('id,nickname,headpic');
        }])->where(['payment_state'=>1,'shipment_state'=>0])->where('state','in',[0,-1])->order('create_time desc')->field('order_id,order_num,user_id,state,payment_state,shipment_state,delete_time,update_time,pay_time,shipping_money,order_money,user_ip,message,create_time', true)
            ->limit(100)->select();
        return app('json')->success($data);
    }

    /**
     * 获取指定订单详细--CMS
     * @param string $id
     * @return mixed
     */
    public function GetOrderOne($id = '')
    {
        (new IDPostiveInt())->goCheck();
        $order = (new QyFactory())->instance('CmsService');
        $data = $order->get_order_detail($id);
        return app('json')->success($data);
    }


    /**
     * 发货--更新订单配送信息
     * @return bool
     */
    public function editCourier()
    {
        $rule = [
            'courier' => 'require',
            'courier_num' => 'require|min:5',
            'order_id' => 'require|number',
        ];
        $param = Request::param();
        $this->validate($param, $rule);
        return OrderModel::editCourier($param);
    }

    /**
     * 添加订单备注信息
     * @return mixed
     */
    public function editRemark()
    {
        $rule = [
            'remark' => 'require',
            'order_id' => 'require|number',
        ];
        $param = Request::param();
        $this->validate($param, $rule);
        return OrderModel::up_remark_model($param);
    }

    /**
     * 修改订单价格
     * @return mixed
     */
    public function edit_price()
    {
        $rule = [
            'price' => 'require',
            'order_id' => 'require|number',
        ];
        $param = Request::param();
        $this->validate($param, $rule);
        return OrderModel::edit_price_model($param);
    }

    /**
     * 手机端核销订单
     * @param $number
     * @return bool
     */
    public function hexiao($number)
    {
        $uid=TokenService::getCurrentUid();
        return OrderModel::hexiao($number,$uid);
    }

    /**
     * 获取单条订单详情
     * @return mixed
     * @throws \ruhua\exceptions\OrderException
     */
    public function info_order(){
        $rule = [
            'order_id'  => 'require|number',
        ];
        $param = Request::param();
        $this->validate($param, $rule);

        $order = (new QyFactory())->instance('CmsService');
        $data = $order->get_order_detail($param['order_id']);
        return app('json')->success($data);

    }

    /**
     * 后面修改订单地址
     * @return mixed
     * @throws \ruhua\exceptions\OrderException
     */
    public function edit_address(){
        $rule = [
            'fullname'  => 'require',
            'order_id'  => 'require|number',
            'mobile'    => 'require',
            'city'      => 'require',
            'address'   => 'require',
        ];
        $param = Request::param();
        $this->validate($param, $rule);
        return OrderModel::edit_address_model($param);
    }
}