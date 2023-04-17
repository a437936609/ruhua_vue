<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/30 0030
 * Time: 13:34
 */

namespace app\services;


use app\controller\cms\Watermark;
use app\model\Goods as GoodsModel;
use app\model\Image as ImageModel;
use app\model\Order as OrderModel;
use app\model\SysConfig as SysConfigModel;
use app\model\SysConfig;
use app\model\Video;
use ruhua\bases\BaseCommon;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\ProductException;
use ruhua\exceptions\TokenException;
use kuaidi\Kd;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Filesystem;
use think\facade\Log;
use think\facade\Request;
use think\Image;
use app\controller\cms\Oss;

class CommonServices
{

    /**
     * 导出数据
     * @return int
     */
    public function export_excl()
    {
        $token = input('get.token');
        $csv_titless = input('param.title');
        if (!$token) {
            throw new TokenException();
        }
        $vars = Cache::get($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (!array_key_exists('admin_id', $vars)) {
                throw new TokenException(['msg' => '尝试获取的变量并不存在']);
            }
        }
        $list = OrderModel::with(['ordergoods', 'users'])->order('create_time desc')->select()->toArray();
        if (count($list) < 1) {
            return 0;
        }
        $arr = [];
        $title = ['序号'];
        foreach ($list as $k => $v) {
            $arr[$k]['xu'] = $k + 1;
            if(in_array('number',$csv_titless)){
                $arr[$k]['number'] = $v['order_num'];
                if($k==0)
                array_push($title,'订单号');
            }
            if(in_array('status',$csv_titless)){
                $arr[$k]['status'] = $v['shipment_state'] ? '已发货' : '待发货';
                if($k==0)
                array_push($title,'订单状态');
            }
            if(in_array('pay_time',$csv_titless)){
                $arr[$k]['pay_time'] = $v['pay_time'] ? date('Y-m-s h:i:s',$v['pay_time']) : '未付款';
                if($k==0)
                    array_push($title,'付款时间');
            }
            if(in_array('ordergoods',$csv_titless)){
                $pro = '';
                foreach ($v['ordergoods'] as $kk => $vv) {
                    $sku = $vv['sku_name']?$vv['sku_name']:'无';
                    $pro .= '【商品名称】：'.$vv['goods_name'].';【商品规格】：'.$sku.';'.'[x' . $vv['num'] . ']@@';
                }
                if($k==0){
                    array_push($title,'商品名');
                    array_push($title,'商品价格');
                }
                $arr[$k]['goods'] = $pro;
                $arr[$k]['goods_money'] = $v['goods_money'];
            }
            if(in_array('order_money',$csv_titless)){
                $arr[$k]['order_money'] = $v['order_money'];
                if($k==0)
                array_push($title,'订单价格');
            }
            if(in_array('yunfei',$csv_titless)){
                $arr[$k]['shipping_money'] = $v['shipping_money'];
                if($k==0)
                    array_push($title,'运费');
            }
            if(in_array('nickname',$csv_titless)){
                $arr[$k]['nickname'] = $v['users']['nickname'];
                if($k==0)
                array_push($title,'买家');
            }
            if(in_array('username',$csv_titless)){
                $arr[$k]['username'] = $v['receiver_name'];
                if($k==0)
                array_push($title,'收货人');
            }
//            if(isset($csv_titless['username'])){
//                $arr[$k]['mobile'] = $v['receiver_mobile'];
//            }
            if(in_array('address',$csv_titless)){
                $arr[$k]['address'] = $v['receiver_city'].$v['receiver_address'];
                if($k==0)
                array_push($title,'收获地址');
            }
            if(in_array('wuliunum',$csv_titless)){
                $arr[$k]['wuliunum'] = $v['create_time'];
                if($k==0)
                array_push($title,'物流单号');
            }
            if(in_array('message',$csv_titless)){
                $arr[$k]['message'] = $v['message'];
                if($k==0)
                array_push($title,'买家留言');
            }
        }
     return   $this->put_csv($arr, $title);
    }

    /**
     * 更新不同模型的布尔字段
     * @param $id
     * @param $db
     * @param $field
     * @return int
     */
    public static function upValue($id, $db, $field)
    {
        switch ($db) {
            case 'order':
                $where['order_id'] = $id;
                break;
            case 'goods':
                $where['goods_id'] = $id;
                break;
            case 'category':
                $where['category_id'] = $id;
                break;
            case 'user':
                $where['id'] = $id;
                break;
            case 'article':
                $where['id'] = $id;
                break;
            case 'admins':
                $where['id'] = $id;
                break;
            default:
                return app('json')->fail('找不到模型');
        }
        $vs = Db::name($db)->where($where)->value($field);
        if ($vs == 0) {
            $res = Db::name($db)->where($where)->update([$field => 1]);
        } else {
            $res = Db::name($db)->where($where)->update([$field => 0]);
        }
        if ($res) {
            if ($db == 'goods' && $field == 'state') {
                GoodsModel::deleteGoods($id);
            }
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 上传图片
     * @param string $use
     * @param string $back
     * @return mixed
     */
    public static function uploadImg($use, $back, $type = '', $cid = '')
    {
        /*if(Request::ip() != '123'){
            return app('json')->fail('非法操作');
        }*/
        $up_file=request()->file('img');
        Log::error('上传图片');
        Log::error($up_file);
        $file = Image::open($up_file);
        $filename=$up_file->getOriginalExtension();

        if($filename!="jpg"&&$filename!="jpeg"&&$filename!="png"&&$filename!="gif"){
            throw new ProductException(['msg'=>'非图片上传']);
        }
        if(!is_dir(ROOT."/uploads/$use")){
            mkdir(ROOT."/uploads/$use");
        }

        $name = uniqid() .".". $filename;
        $file->thumb(500, 500, 1)->save('./uploads/' . $use . '/' . $name);
        $res = self::img_save($use, $name, $cid);   //保存图片
        if ($res['id']) {
            if ($back == 'id' && $type == 1) {
                return $res['id'];
            } else if ($back == 'id') {
                return app('json')->success($res['id']);
            } else if ($back == 'idurl') {
                $web_url = config('setting.web_url');
                $pic = $web_url . '/uploads/' . $use . '/' . $name;
                $r['id'] = $res->id;
                $r['url'] = $pic;
                return app('json')->success($r);
            } else {
                $web_url = config('setting.web_url');
                $pic = $web_url . '/uploads/' . $use . '/' . $name;
                return app('json')->success([$pic]);
            }
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 上传视频上传视频
     * @return mixed
     */
    public static function uploadVideo()
    {
        $file = request()->file('file');
        $filename=$file->getOriginalExtension();
        if (!$file) {
            return app('json')->fail('请上传文件video');
        }
        if($filename!="mp4"&&$filename!="flv"){
            throw new BaseException(['msg'=>'视频格式只支持mp4和flv']);
        }
        validate(['file' => 'fileSize:10240000'])
            ->check(['file' => $file]);
        $fileName = Filesystem::disk('public')->putFile('video', $file, 'uniqid');
        $file1=getcwd()."/storage/".$fileName; //旧目录
        if (file_exists($file1)) {
            $dir = './uploads/video/';
            if(is_dir($dir)){
                Log::error('上传视频：该目录已存在');
            }else{
                if(mkdir($dir,0777,true))Log::error('上传视频：目录创建完毕'); ;
            }
            $newFile=getcwd()."/uploads/".$fileName; //新目录
            copy($file1,$newFile); //拷贝到bai新目录
            unlink($file1); //删除旧目录下的文件
            }
        $res = self::video_save('video', $fileName);   //保存视频
        if ($res['id']) {
            return app('json')->success($res['id']);
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 上传视频返回地址
     * @return mixed
     */
    public static function uploadVideoUrl()
    {
        $file = request()->file('file');
        $filename=$file->getOriginalExtension();
        if (!$file) {
            return app('json')->fail('请上传文件video');
        }
        if($filename!="mp4"&&$filename!="flv"){
            throw new BaseException(['msg'=>'视频格式只支持mp4和flv']);
        }
        validate(['file' => 'fileSize:10240000'])
            ->check(['file' => $file]);
        $fileName = Filesystem::putFile('video', $file, 'uniqid');

        $res = self::video_save('video', $fileName);   //保存视频
        if ($res['id']) {
            $arr[0]=$res['url'];
            return app('json')->go($arr);

        } else {
            return app('json')->fail();
        }
    }

    /**
     * 上传的图片信息，录入数据库
     * @param $name
     * @param $use
     * @param $cid
     * @return \think\Model|static
     */
    private static function img_save($use, $name, $cid = '')
    {
        $data['use_name'] = $use;
        $data['url'] = '/uploads/' . $use . '/' . $name;
        $data['category_id'] = $cid;
        $is_oss=SysConfigModel::where('key','upload_oss')->value('value');
        if($is_oss==1){
            $oss=new Oss();
            $data['url']=$oss->uploadFileImg(ROOT.$data['url']);
            $path=ROOT.$data['url'];
            if(file_exists($path))
                unlink($path);//删除文件
        }
        $watermark=SysConfig::where('key','watermark')->value('value');
        if($watermark==1&&$is_oss==0)
            $res=(new Watermark())->text($data);
        else
            $res = ImageModel::create($data);
        return $res;
    }
    /**
     * 上传的图片信息，录入数据库
     * @param $name
     * @param $use
     * @param $cid
     * @return \think\Model|static
     */
    private static function
    video_save($use, $name)
    {
        $data['use_name'] = $use;
        $data['url'] = '/uploads/' . $name;
        $data['description'] = input('post.description');
        $is_oss=SysConfigModel::where('key','upload_oss')->value('value');
        if($is_oss==1){
        $oss=new Oss();
        $data['url']=$oss->uploadFileVideo(ROOT.$data['url']);
        $path=ROOT.$data['url'];

        if(file_exists($path))
           unlink($path);//删除文件
    }
        $res = Video::create($data);
        return $res;
    }

    public static function downImg($url)
    {
        $res = file_get_contents($url);
        $name = uniqid() . '.png';
        $out = fopen('./uploads/product/' . $name, "a");
        fwrite($out, $res);
        fclose($out);
        $img = self::img_save('product', $name);   //保存图片
        return $img['id'];
    }

    /**
     * 快递查询
     * @param $id
     * @return mixed
     */
    public static function getCourier($id)
    {
        $order = OrderModel::where(['order_id' => $id])->field('order_id,courier,courier_num,receiver_mobile')->find();
        if (!$order['courier'] || !$order['courier_num']) {
            return app('json')->fail('未找到单号');
        }
        $code = SysConfigModel::where(['key' => 'appcode'])->value('value');
        $mobile=substr($order['receiver_mobile'],-4,4);
        $kd = new Kd($code, '', $order['courier_num'],$mobile);
        $data=json_decode($kd->get(),true);
        Log::error($data);
        if($data==null){
            Log::channel('msgLog')->write($order['courier_num']." 快递单号获取失败,原因：快递code不对");
        }else{
            if($data['status']!=0){
                Log::channel('msgLog')->write($order['courier_num']." 快递单号获取失败,原因：".$data['msg']);
            }
        }
        return json($data);
    }

    /**
     * 导出csv文件
     * @param $list
     * @param $title
     */
    public function put_csv($list, $title)
    {
        $file_name = date("Y年m月d日h时i分", time()) . ".csv";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $file_name);
        header('Cache-Control: max-age=0');
        $file = fopen(ROOT.'/storage/excel/'.$file_name, "a");
        $limit = 1000;
        $calc = 0;
        foreach ($title as $v) {
            $tit[] = iconv('UTF-8', 'GB2312//IGNORE', $v);
        }
        fputcsv($file, $tit);
        foreach ($list as $v) {
            $calc++;
            if ($limit == $calc) {
                ob_flush();
                flush();
                $calc = 0;
            }
            foreach ($v as $t) {
                $tarr[] = iconv('UTF-8', 'GB2312//IGNORE', $t);
            }
            $res = fputcsv($file, $tarr);
            unset($tarr);
        }
        unset($list);
        fclose($file);
        if($res){
            return true;
        }
    }

    /**
     * 获取导出的所有文件
     */
    public static function getExcel(){
        $dir = ROOT."/storage/excel";
        return scandir($dir,1);
    }

    /**
     * 删除导出的文件
     */
    public static function delExcel(){
        $dir = ROOT."/storage/excel/";
        $name = input('param.name');
        return unlink($dir.$name);
    }
    /**
     * 获取二维码
     * @param $path
     * @return string
     */
    public static function getCodeImg($path)
    {
        $qcode = app('json')->getValue('code_url');
        $url = $qcode . $path;
        return $data['qrcode'] = (new QrcodeServer())->get_qrcode($url);
    }

    //生成小程序码
    public function getXcxCodeImg($path, $scene, $sf_code = '')
    {
        //$scene为参数
        $a = (new AccessToken)->getXcx();
        $access_token = $a['access_token'];
        //文档：https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
        //必须是已经发布的小程序，未发布的会报错

        $qcode = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=$access_token";

        if($scene){
            $scene = $scene . '&' . $sf_code;
        }else{
            $scene= $sf_code;
        }
        $param = ["page" => $path, 'scene' => $scene, "width" => 140];
        //POST参数
        $result = (new BaseCommon())->curl_post($qcode, $param);
        Log::error($result);
        $date = date('Ymd', time());
        $filename = $date . uniqid() . '.jpg'; //定义图片名字及格式
        if($path){
            //返回图片base数据给前端小程序，直接放前端img src即可显示;
           /* $base64_image = "data:image/jpeg;base64," . base64_encode($result);
            return $base64_image;*/
            $filename = "pro".$date . uniqid() . '.jpg'; //定义图片名字及格式
        }
        file_put_contents('./uploads/code/' . $filename, $result);    //保存小程序码到服务器
        $url = '/uploads/code/';
        return $url . $filename;
    }

    private function getCodeUrl()
    {

        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return 'https://mp.weixin.qq.com/a/~kMNh0OK3gimX7K6A4T7yRw~~';
        }
        //判断是不是支付宝
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
            return "您正在使用 支付宝 扫码";
        }
        //哪个都不是
        return "请使用支付宝、QQ、微信扫码";
    }
}