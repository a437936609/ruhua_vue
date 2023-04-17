<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/29 0029
 * Time: 8:43
 */

namespace app\controller\cms;

use app\model\Image;
use app\model\SysConfig as SysConfigModel;
use app\services\TokenService;
use ruhua\bases\BaseController;
use OSS\OssClient;
use think\Db;
use think\facade\Log;
use app\model\Video as VideoModel;


class Oss extends BaseController
{
    private $oss;
    private $bucket;

    /**构造函数
     * Oss constructor.
     */
    public function __construct()
    {
        $accessKeyId=SysConfigModel::where('key','accessKeyId')->value('value');
        $accessKeySecret=SysConfigModel::where('key','accessKeySecret')->value('value');
        $endpoint=SysConfigModel::where('key','endpoint')->value('value');
        $this->bucket=SysConfigModel::where('key','bucket')->value('value');
        $this->oss=(new OssClient($accessKeyId,$accessKeySecret,$endpoint));

    }

    /**创建Bucket
     * @param $name
     */
    public function createBucket($name)
    {
        $res=$this->oss->createBucket($name);
      //  $this->oss->createObjectDir($name,'shop');
        $this->oss->putBucketAcl($name,'public-read-write',['default', 'private', 'public-read', 'public-read-write']);
        if($res)
            return true;
        else
            return false;
    }

    /**删除Bucket
     * @param $name
     */
    public function deleteBucket($name)
    {
        $res=$this->oss->deleteBucket($name);
        return app('json')->go($res);
    }

    /**
     * 上传本地图片
     */
    public function uploadFileImg($file)
    {
            $name = uniqid() . '.png';
       $res=$this->oss->uploadFile($this->bucket,"images/".$name,$file);
       if($res){
           $data=$res['info']['url'];
       }else{
          $data=null;
       }
       Image::where('url',$file)->delete();
        if(file_exists($file))
            unlink($file);//删除文件
        return $data;

    }

    /**
     * 上传本地视频
     */
    public function uploadFileVideo($file)
    {
        $data=explode('.',$file);
        $back_name=$data[count($data)-1];
       // $name = uniqid() . '.png';
        $name=uniqid().".".$back_name;
        $res=$this->oss->uploadFile($this->bucket,"video/".$name,$file);
        if($res){
            $data=$res['info']['url'];
        }else{
            $data=null;
        }
        VideoModel::where('url',$file)->delete();
        if(file_exists($file))
            unlink($file);//删除文件
        return $data;

    }

    /**
     * 上传本地文件多个
     */
    public function uploadFiles($files)
    {
        $img_name = uniqid() . '.png';
        $sid=TokenService::getShopId();
        $url='';
        $flg=0;


        foreach ($files as $k=>$v){
            $res=$this->oss->uploadFile($this->bucket,$sid."/".$img_name,ROOT.$v);
            if($res){
                if($flg==1)
                    $url.=",";
                $url.=$res['info']['url'];
                $flg=1;
            }
        }


    }

    /**图片删除
     * @param $name
     * @param $file
     * @return mixed
     */

    public function deletefile($name,$file)
    {
        $res=$this->oss->doesObjectExist($name,$file);
        if($res){
            $data=$this->oss->deleteObject($name,$file);
        }else
            $data=null;
        return app('json')->go($data);
    }

    /**
     * 创建虚拟目录
     */
    public function createObjectDir($shop_id)
    {
        $res=$this->oss->createObjectDir($this->bucket,$shop_id);
        return $res;

    }





}