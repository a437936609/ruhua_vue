<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/30 0030
 * Time: 13:33
 */

namespace app\controller\cms;


use app\common\BaseCom;
use app\model\SysConfig;
use app\services\CommonServices;
use app\services\TokenService;
use ruhua\bases\BaseCommon;
use ruhua\bases\BaseController;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Log;
use think\facade\Request;
use Uedit\controller as Contro;

class Common extends BaseController
{
    /**
     * 更新不同模型的布尔字段
     * @param $id
     * @param $db
     * @param $field
     * @return int
     */
    public function upValue($id, $db, $field)
    {
        return CommonServices::upValue($id, $db, $field);
    }

    /**
     *
     * @return mixed
     */
    public function ue_uploads()
    {
        $ue = new Contro;
        $res = $ue->show();
        return $res;
    }

    /**
     * 上传图片
     * @param string $use
     * @param string $back
     * @param  $cid
     * @return mixed
     */
    public function uploadImg($use = 'other', $back = 'url', $cid = '')
    {
        return CommonServices::uploadImg($use, $back, 0, $cid);
    }

    /**
     * 上传视频
     * @return mixed
     */
    public function uploadVideo()
    {
        return CommonServices::uploadVideo();
    }


    /**
     * 上传视频返回地址
     * @return mixed
     */
    public function uploadVideoUrl()
    {
        return CommonServices::uploadVideoUrl();
    }

    /**
     * 下载图片
     * @param  $url
     * @return mixed
     */
    public function downImg($url)
    {
        return CommonServices::downImg($url);
    }

    /**
     * 上传图片返还ID
     * @param string $use
     * @param string $back
     * @param  $cid
     * @return mixed
     */
    public function uploadImgID($use = 'other', $back = 'id', $cid = '')
    {
        $data = CommonServices::uploadImg($use, $back, 1, $cid);
        return json($data);
    }

    /**
     * 导出数据
     * @return mixed
     */
    public function export_excl()
    {
        $data = (new CommonServices)->export_excl();
        if ($data) {
            return app('json')->success($data);
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 获取导出的数据
     * @return mixed
     */
    public function get_excel()
    {
        $data = (new CommonServices)->getExcel();
        if ($data) {
            return app('json')->success($data);
        } else {
            return app('json')->fail();
        }
    }
    /**
     * 删除导出的数据
     * @return mixed
     */
    public function del_excel()
    {
        $data = (new CommonServices)->delExcel();
        if ($data) {
            return app('json')->success($data);
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 下载导出的数据
     * @return mixed
     */
    public function download_excel()
    {
//        $name = input('param.name');
//        $url=Request::domain();
//        $download_path = $url.'/storage/excel/'.$name;
//        return $download_path;
    }
    /**
     * 检查是否登录
     * @return mixed
     */
    public function checkLogin()
    {
        $aid = TokenService::getCurrentAid();
        if ($aid) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }

    /*
     * 获取直播列表
     * */
    public function liveTvList($start,$limit){
        Log::error('获取直播列表');
        $tk = Cache::get('access_token');
        //从服务器获取token
        if(!$tk || $start==-1) {
            $data = SysConfig::get_xcx_infor();
            $appid=$data['appid'];
            $appsecret=$data['secret'];
            $r = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret");
            $tokenappid =  json_decode($r,true);
            cache('access_token', $tokenappid['access_token'], 3600);
            $start=0;
        }
        $post_data = array(
            'start' => $start,
            'limit' => $limit,
        );
        $url = "https://api.weixin.qq.com/wxa/business/getliveinfo?access_token=".$tk;
        $list = $this->curls($url, json_encode($post_data));
        return app('json')->go(json_decode($list,true));
    }

    function curls($url, $data_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'X-AjaxPro-Method:ShowList',
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data_string))
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}