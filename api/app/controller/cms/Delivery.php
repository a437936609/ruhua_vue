<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/17 0017
 * Time: 13:39
 */

namespace app\controller\cms;


use app\model\DeliveryRule as DeliveryRuleModel;
use app\model\Region;
use app\model\UserAddress;
use app\services\TokenService;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use app\model\Delivery as DeliveryModel;
use think\facade\Log;

class Delivery extends BaseController
{
    /**
     * 添加快递模板
     * @return bool|int
     */
    public function addDelivery(){
        $rule=[
            'name'=>'require',
            'method'=>'require',
            'rule'=>'require',
        ];
        $post=input('post.');
        $this->validate($post,$rule);
        return DeliveryModel::addDelivery($post);
    }

    /**
     * 修改快递模板
     * @return bool|int
     */
    public function editDelivery(){
        (new IDPostiveInt)->goCheck();
        $post=input('post.');
        return DeliveryModel::editDelivery($post);
    }

    /**
     * 查看快递模板
     * @return mixed
     */
    public function selectDelivery(){
        $data=DeliveryModel::selectDelivery();
        return app('json')->success($data);
    }

    /**
     * 删除快递模板
     * @param string $id
     * @return mixed
     */
    public function deleteDelivery($id=''){
        (new IDPostiveInt())->goCheck();
        return DeliveryModel::deleteDelivery($id);
    }

    public function getRegion(){
        $data=Region::getRegion(2);
       return  json($data);
    }

    //计算运费
    public function getShipmentPrice(){
        $post= input('post.');
        $uid=TokenService::getCurrentUid();
        $user_data = UserAddress::where('user_id', $uid)->where('is_default', 1)->find();
        if(!isset($user_data))
            return app('json')->success(0);
        $price= DeliveryModel::computeShipping($user_data['region_id'],$post);  //某城市运费
        return app('json')->success($price);
    }
}