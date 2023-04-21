<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/9 0009
 * Time: 13:40
 */

namespace app\model;


use ruhua\bases\BaseCommon;
use ruhua\bases\BaseModel;
use think\facade\Log;
use app\model\VipTc as VipTcModel;

class VipOrder extends BaseModel
{
    /**
     * 购买Vip
     * @param $uid
     * @param $id
     * @return mixed
     */
    public static function addVip($uid, $id)
    {
        $state = SysConfig::where(['key' => 'is_vip'])->value('value');
        if ($state != 1) {
            return app('json')->fail('未开启会员');
        }
       // $tc = VipTc::where('id', $id)->find();
        $tc=VipTcModel::where('id',$id)->find();


        if (!$tc) {
            return app('json')->fail('套餐不存在');
        }
        $orderSn ="V" . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        $data['order_num'] = $orderSn;
        $data['user_id'] = $uid;
        $data['pay_money'] = $tc['price'];
        $data['order_statue'] = 1;
        $data['day_num'] = $tc['day_num'];
        $res = self::create($data);
        return app('json')->success($res['id']);
    }

    /**
     * 购买成功支付回调
     * @param $id
     * @return mixed
     */
    public static function notifyVip($id)
    {
        try {
            $order = self::where('id', $id)->find();
            $order['order_status'] = 2;
            $order['pay_time'] = time();
            $order->save();
            $userVip = VipUser::where('user_id', $order['user_id'])->find();
            if ($userVip && $userVip['end_time'] > time()) {
                $time = $userVip['end_time'] + $order['day_num'] * 24 * 60 * 60;;
            } else {
                $time = time() + $order['day_num'] * 24 * 60 * 60;;
            }
            if ($userVip) {
                $userVip->save(['status' => 1, 'end_time' => $time]);
            } else {
                VipUser::create(['user_id' => $order['user_id'], 'status' => 1, 'end_time' => $time]);
            }
            return app('json')->success();
        } catch (\Exception $e) {
            Log::error('购买会员错误:' . $e->getMessage());
            return app('json')->fail();
        }
    }

    /*
     * 管理员设置会员
     * */
    public static function setVipCms($id){
        $time = time() + 30 * 24 * 60 * 60;
        Log::error('777777777777');
        Log::error($id);
        if(VipUser::where('user_id',$id)->count())
            $res = VipUser::update(['status' => 1, 'end_time' => $time],['user_id'=>$id]);
        else
            $res = VipUser::create(['user_id' => $id, 'status' => 1, 'end_time' => $time]);
        return app('json')->go($res);
    }

    /*
     * 管理员设置取消会员
     * */
    public static function setVipCmsNo($id){
        $time = time();
        $res = VipUser::update(['status' => 0, 'end_time' => 0],['user_id'=>$id]);

        return app('json')->go($res);
    }

    /**
     * 获取订单指定字段
     * @param $id
     * @param $field
     * @return mixed
     */
    public static function getOrderAttr($id, $field)
    {
        $value = self::where('id', $id)->value($field);
        if (!$value) {
            throw new BaseException(['获取字段失败']);
        }
        return $value;
    }

    //关联模型
    public function userName()
    {
        return $this->belongsTo('User','user_id','id')->field('id,nickname,mobile');
    }
}