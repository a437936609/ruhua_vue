<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18 0018
 * Time: 13:23
 */

namespace app\model;


use app\services\TokenService;
use app\model\Coupon as CouponModel;
use app\model\UserCoupon as UserCouponModel;
use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use think\facade\Db;

class UserCoupon extends BaseModel
{

    protected $type = [
        'end_time'  =>  'timestamp:Y-m-d',
    ];

    /**
     * 用户领取优惠券
     * @param $id
     * @return mixed
     * @throws BaseException
     */
    public static function addUserCoupon($id)
    {
        $uid = TokenService::getCurrentUid();
        $coupon = CouponModel::where('id', $id)->where('is_appoint',0)->find();
        $userCoupon = UserCouponModel::where('user_id', $uid)->where('coupon_id', $id)->order('id', 'desc')->find();
        if (!$coupon) {
            return app('json')->fail('优惠券错误');
        }
        if ($coupon['stock_type'] == 0 && $coupon['stock'] == 0) {
            return app('json')->fail('库存不够');
        } else if ($coupon['stock_type'] == 0) {
            $coupon['stock'] -= 1;
        }
        if ($coupon['status'] == 1 && $userCoupon) {
           return app('json')->fail('该优惠券只能领取一次');
        } else if ($coupon['status'] == 2 && $userCoupon) {
            if ($userCoupon['status'] != 2 && $userCoupon['status'] != 3) {
                return  app('json')->fail('已领取');
            }
        }
        $data['user_id'] = $uid;
        $data['shop_id'] = $coupon['shop_id'];
        $data['coupon_id'] = $id;
        $data['goods_ids'] = $coupon['goods_ids'];
        $data['full'] = $coupon['full'];
        $data['reduce'] = $coupon['reduce'];
        if ($coupon['end_time']) {
            $data['end_time'] = strtotime($coupon['end_time']);
        } else {
            $data['end_time'] = strtotime('+' . $coupon['day'] . ' day', time());
        }
        Db::startTrans();
        try {
            $res = UserCouponModel::create($data);
            $coupon->save();
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new BaseException(['msg' => $e->getMessage()]);
        }
        return app('json')->success($res['id']);
//        return $res['id'];
    }

    /**
     * 用户查看订单可用优惠券
     * @param $uid
     * @param $ids
     * @return mixed
     */
    public static function  couponStatus($uid, $data)
    {
        $coupon = UserCouponModel::where('user_id', $uid)->where('status', 0)
            ->whereTime('end_time', '>', time())
            ->select();
        foreach ($coupon as $k => $v) {
            $total_money=0;
            foreach ($data as $key => $value) {
                if ($v['goods_ids'] != 0) {
                    $coupon_ids = explode(',', $v['goods_ids']);
                    if (in_array($value['goods_id'], $coupon_ids)) {
                        $money=$value['price']*$value['num'];
                        $total_money+=$money;
                    }

                }else{
                    $money=$value['price']*$value['num'];
                    $total_money+=$money;

                }
            }
            if($total_money<$v['full']){
                unset($coupon[$k]);
            }
        }
        return app('json')->success($coupon);
//        return $coupon;
    }

    /**
     * 删除用户过期优惠券
     */
    public static function delUserCoupon(){
        $time=time()-10*24*60*60;
        self::where('status',3)->whereTime('end_time','<',$time)->delete();
    }
    /*
     * 管理员发放优惠券
     * */
    public static function addUserCouponCms($id,$uid)
    {

        $coupon = CouponModel::where('id', $id)->where('is_appoint',1)->find();
        $userCoupon = UserCouponModel::where('user_id', $uid)->where('coupon_id', $id)->order('id', 'desc')->find();
        if (!$coupon) {
            return app('json')->fail('优惠券错误');
        }
        if ($coupon['stock_type'] == 0 && $coupon['stock'] == 0) {
            return app('json')->fail('库存不够');
        } else if ($coupon['stock_type'] == 0) {
            $coupon['stock'] -= 1;
        }
        if ($coupon['status'] == 1 && $userCoupon) {
            return app('json')->fail('该优惠券只能发放一次');
        } else if ($coupon['status'] == 2 && $userCoupon) {
            if ($userCoupon['status'] != 2 && $userCoupon['status'] != 3) {
                return  app('json')->fail('已发放过');
            }
        }
        $data['user_id'] = $uid;
        $data['shop_id'] = $coupon['shop_id'];
        $data['coupon_id'] = $id;
        $data['goods_ids'] = $coupon['goods_ids'];
        $data['full'] = $coupon['full'];
        $data['reduce'] = $coupon['reduce'];
        if ($coupon['end_time']) {
            $data['end_time'] = strtotime($coupon['end_time']);
        } else {
            $data['end_time'] = strtotime('+' . $coupon['day'] . ' day', time());
        }
        Db::startTrans();
        try {
            $res = UserCouponModel::create($data);
            $coupon->save();
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new BaseException(['msg' => $e->getMessage()]);
        }
        return app('json')->success($res['id']);
//        return $res['id'];
    }

    //管理员获取指定发放优惠券
    public static function getAllState($state){
        //使用状态(0未使用1已使用2已完成3已过期
        $res = null;
        switch ($state){
            case 0:
                $res = UserCouponModel::with(['users','coupons'])->where('status', $state)->order('id', 'desc')->select();
                break;
            case 1:
                $res = UserCouponModel::with(['users','coupons'])->where('status', $state)->order('id', 'desc')->select();
                break;
            case 2:
                $res = UserCouponModel::with(['users','coupons'])->where('status', $state)->order('id', 'desc')->select();
                break;
            case 3:
                $res = UserCouponModel::with(['users','coupons'])->where('status', $state)->order('id', 'desc')->select();
                break;
            default:
                $res = UserCouponModel::with(['users','coupons'])->order('id', 'desc')->select();

        }
        if(count($res)<1){
            return app('json')->go("");
        }
        $arr = null;
        $i=0;
        //过滤不是指定的优惠券
        foreach ($res as $k => $v) {
            if($v['coupons']!=null){
                $arr[$i]=$v;
                $i++;
            }
        }
        if($i ==0) {
            throw new BaseException(['msg' => '没有数据']);
        }
        return app('json')->go($arr);

    }
    public static function del_id($id){
        $res = self::where('id',$id)->delete();
        return app('json')->go($res);
    }

    //返回优惠券使用数量;共2000；实际数量*2;120起步；实际发放940个
    public static function couponNumCustomized($cid){
        $num = self::where('coupon_id',$cid)->count();
        $data['all'] = 2000;
        if(120+$num*2>2000){
            $data['issuedOk']=2000;
            $data['issuedNo']=0;
        }
        else{
            $data['issuedOk']=120+$num*2;
            $data['issuedNo']=2000-$data['issuedOk'];
        }
        return app('json')->go($data);
    }

    //关联用户
    public function users()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname');
    }
    //关联优惠券
    public function coupons()
    {
        return $this->belongsTo('Coupon', 'coupon_id', 'id')->where('is_appoint',1)->field('id,name,is_appoint');
    }

}