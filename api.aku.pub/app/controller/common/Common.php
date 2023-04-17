<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/6 0006
 * Time: 15:31
 */

namespace app\controller\common;


use app\cache\AdminVerifyService;
use app\model\SysConfig as SysConfigModel;
use app\services\CommonServices;
use ruhua\bases\BaseController;
use app\model\Order as OrderModel;
use ruhua\subscribes\OrderSubscribes;
use app\model\UserCoupon as UserCouponModel;

class Common extends BaseController
{

    //app版本必须更新
    public function up_version()
    {
        $appid = $_GET["appid"];
        $version = $_GET["version"]; //客户端版本号
        $rsp = array("status" => 0); //默认返回值，不需要升级
        if (isset($appid) && isset($version)) {
            if ($appid === "__UNI__434B608") { //校验appid
                if (!in_array($version, ["1.1.0", "1.1.1", "1.0.7"])) { //这里是示例代码，真实业务上，最新版本号及relase notes可以存储在数据库或文件中
                    $rsp["status"] = 1;
                    $rsp["note"] = "新增部分功能，请升级;"; //release notes
                    $rsp["url"] = "https://test.phps.shop/aa.apk"; //应用升级包下载地址
                }
            }
        }
        return json_encode($rsp);
    }

    //app版本手动更新
    public function up_sd_version()
    {
        $appid = $_GET["appid"];
        $version = $_GET["version"]; //客户端版本号
        $rsp = array("status" => 0); //默认返回值，不需要升级
        if (isset($appid) && isset($version)) {
            if ($appid === "__UNI__434B608") { //校验appid
                if ($version !== "1.0.1") { //这里是示例代码，真实业务上，最新版本号及relase notes可以存储在数据库或文件中
                    $rsp["status"] = 1;
                    $rsp["note"] = "新增部分功能，请升级;"; //release notes
                    $rsp["url"] = "https://test.phps.shop/aa.apk"; //应用升级包下载地址
                }
            }
        }
        return json_encode($rsp);
    }


    /**
     * 返回二维码
     * @return string
     */
    public function gitCodeImg()
    {
        $post = input('post.');
        $this->validate($post, ['path' => 'require']);
        return CommonServices::getCodeImg($post);
    }


    /**
     * 获取文件
     * @param $type
     * @return array|false|int
     */
    public function getFile($type)
    {
        $file = [];
        if ($type == 1) {
            $file = file_get_contents("./files/fw.txt", "r");
        }
        if ($type == 2) {
            $file = file_get_contents("./files/ys.txt", "r");
        }
        if ($type == 3) {
            $file = file_get_contents("./files/sq.txt", "r");
        }
        return $file;
    }
    /**
     * 前端获取部分配置信息
     */
    public function getSysConfig()
    {
        // 购物车16、客服18、分销25、会员36、登录方式37、发票38
        $res = SysConfigModel::where(['key' => ['exchang_points','is_cart', 'is_serve', 'fx_status', 'is_vip', 'merge_mode','is_invoice','zt_address','drive_type','is_form','min_money','hot_swicth','gzh_share_title','gzh_share_desc','tree_experience',
            'tree_middle','tree_big','tree_small_point','tree_middle_point','tree_big_point','login_mode','product_classification']])->field('key,desc,value')->select();
        return app('json')->success($res);
    }
    public function get_member_equity()
    {
        $list=SysConfigModel::where('key','menber_equity')->find();
        return app('json')->go($list);

    }

    public function send_gzh_massege($id,$type,$ids)
    {
        $data=OrderModel::with('ordergoods')->where('order_id',$id)->find();
        $sd=new OrderSubscribes();
        $res=$sd->onSendGzhDeliveryMessageApi($data,$type,$ids);
        return app('json')->success();
    }

    public function getcode()
    {
       // $data=(new AdminVerifyService())->check();
        $data=(new AdminVerifyService())->create();

        return app('json')->go($data);
    }

    public static function check_code($verify_id,$code)
    {
        $data=(new AdminVerifyService())->check($verify_id,$code);
        return $data;
    }

    ////返回优惠券使用数量;共2000；实际数量*2;120起步；实际发放940个
    public function getCouponIdAll($cid){
        $data['cid']=$cid;
        $rule=[
            'cid' => 'require|number|>:0',
        ];
        $this->validate($data,$rule);
        return UserCouponModel::couponNumCustomized($data['cid']);
    }
}