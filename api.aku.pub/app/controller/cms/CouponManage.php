<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18 0018
 * Time: 11:39
 */

namespace app\controller\cms;


use app\model\Coupon as CouponModel;
use app\model\UserCoupon;
use ruhua\bases\BaseController;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\OrderException;
use ruhua\services\QyFactory;
use think\facade\Log;

class CouponManage extends BaseController
{
    /**
     * 商家创建店铺优惠券
     * @return mixed
     */
    public function addCoupon()
    {
        $rule = [
            'full' => 'require|float',
            'reduce' => 'require|float',
            'name' => 'require|max:60',
            'status' => 'require',
        ];
        $post = input('post.');

        if(isset($post['full'])&&isset($post['reduce'])){

            if($post['full']<$post['reduce']&&$post['full']!=0)
                throw new OrderException(['msg'=>'减价不能超过满价']);
        }

        $this->validate($post, $rule,[],true);
        return CouponModel::addCoupon($post);
    }

    /**
     * cms查看优惠券
     * @return \think\response\Json
     */
    public function getCoupon()
    {
        $coupon=(new QyFactory())->instance('CmsService');
        $data=$coupon->get_coupon_list();
        return app('json')->success($data);
    }
    /*
     * 管理员发放优惠券
     * */

    public function giveCoupon(){
        $get = input('get.');
        Log::error($get);
        if(!isset($get['cid'])||$get['cid']=='undefined')
            throw new BaseException(['msg'=>'请填写优惠券']);
        if(!isset($get['uid'])||$get['uid']=='undefined')
            throw new BaseException(['msg'=>'请填写用户']);
        return UserCoupon::addUserCouponCms($get['cid'],$get['uid']);
    }
    /*
     * 管理员获取指定发放的优惠券
     * */
    public function getGiveCoupon($state){
       return UserCoupon::getAllState($state);
    }
    /*
     * 管理员删除发放的指定优惠券
     * */
    public function delGiveCoupon($id){
        return UserCoupon::del_id($id);
    }
    /**
     * 删除优惠券
     * @param $id
     * @return int
     */
    public function deleteCoupon($id)
    {
        $res = CouponModel::where('id', $id)->delete();
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
//        return $res?1:0;
    }

}