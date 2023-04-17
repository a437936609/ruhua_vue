<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/8/13 0013
 * Time: 16:18
 */

namespace app\controller\cms;


use ruhua\bases\BaseController;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\OrderException;
use think\facade\Cache;
use think\facade\Log;

class MsgLog extends BaseController
{
    public function get_all()
    {
        $data=$this->getDirFileName();
        if($data==null)
            return app('json')->go(['data'=>null]);
        foreach ($data as $k => $v){
            if($v!='.'&&$v!="..")
            $arr[$k]=$this->readLog(app()->getRootPath()."runtime/msgLog/".$v);
        }
        $res=array();
        $i=0;
        foreach ($arr as $k=> $v)
           foreach ($v as $k1=>$v1)
               $res[$i++]=$v1;
        $j=0;
        for ($n=$i-1;$n>=0;$n--){
            if(mb_strlen($res[$n]['msg'])>50){
                $res[$n]['msg']=mb_substr($res[$n]['msg'],0,50,'utf-8')."..";
            }
            $reg[$j++]=$res[$n];
        }



        return app('json')->go($reg);
    }




    public function testtry()
    {
        try{
            throw new BaseException(['msg'=>'xxxx']);
        }catch (\Exception $e){
            throw new BaseException(['msg'=>"aa:".$e->getMessage()]);
            Log::error("异常抛弃出来".$e->getMessage());
        }
    }

    /**清除msgLog日志
     * @return mixed
     */
    public function clearlog()
    {
        $url=$this->getDirFileName();
        $path=app()->getRootPath()."runtime/msgLog/";
        foreach ($url as $k=>$v){
            if($v!="."&&$v!=".."){
                if(!empty($path.$v))
                    unlink($path.$v);
            }

        }
        $res=Log::clear();
        return app('json')->go($res);
    }

    public function clearErrorLog()
    {
        $url=$this->getDirFileName($url='runtime/log');
        $path=app()->getRootPath()."runtime/log/";
        foreach ($url as $k=>$v){
            if($v!="."&&$v!=".."){
                if(!empty($path.$v))
                    unlink($path.$v);
            }

        }
        $res=Log::clear();
        return app('json')->go($res);
    }

    public function clearCache()
    {
       $res=Cache::clear();
        return app('json')->go($res);
    }

    public function getDirFileName($url='runtime/msgLog'){

        $dir =  app()->getRootPath().$url;
        if(!is_dir($dir))
            return null;
        $data =  scandir($dir);
        return $data;
    }

    /**
     * @param $filename 文件路径
     * @param $table 表名
     * @param string $action
     * @return array
     */
    public function readLog($filename){

        $content=file_get_contents($filename);
        $array=explode("\r\n",$content);
        $arr=array();
        foreach ($array as $k=>$v){
            $arr[$k]=json_decode($v,true);
        }

      //  $array=json_decode($array);
        return $arr;
       /* $handle  = fopen ($filename, "r");

        while (!feof ($handle))
        {

            $buffer  = fgets($handle, 4096);

            $data = trim($buffer);
            if ($data !='---------------------------------------------------------------'){
                if (strpos($data,$table)!=false ){
                    if (strpos($data,$action)!=false){
                        $str = str_replace('[ sql ] [ SQL ]','',$data);
                        $str = substr($str,0,strrpos($str,"["));
                        $arr[] = $str;
                    }
                }

            }
        }

        fclose ($handle);*/
       // return $arr;
    }

}