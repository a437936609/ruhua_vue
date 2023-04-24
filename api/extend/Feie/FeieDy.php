<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/18 0018
 * Time: 11:33
 */

namespace Feie;
use app\model\SysConfig;
use think\facade\Log;

class FeieDy
{
    private $user='';//*必填*：飞鹅云后台注册账号
    private $ukey=''; //*必填*: 飞鹅云后台注册账号后生成的UKEY 【备注：这不是填打印机的KEY】
    private $key=''; //*必填*: 飞鹅云后台注册账号后生成的UKEY 【备注：这不是填打印机的KEY】
    private $sn=''; //*必填*：打印机编号，必须要在管理后台里添加打印机或调用API接口添加之后，才能调用API
    private $name='如花商城'; //选填，名称
    private $ip='api.feieyun.cn';  //接口IP或域名
    private $port=80; //接口IP端口
    private $path='/Api/Open/';  //接口路径


    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct()
    {
        $this->user=SysConfig::where('key','feie_user')->value('value');
        $this->ukey=SysConfig::where('key','feie_ukey')->value('value');
        $this->key=SysConfig::where('key','feie_ukey')->value('value');
        $this->sn=SysConfig::where('key','feie_sn')->value('value');
        $this->name=SysConfig::where('key','feie_name')->value('value');

    }

    protected function initialize()
    {
    }

    /***
    * [批量添加打印机接口 Open_printerAddlist]
    * @param  [string] $printerContent [打印机的sn#key]
    * @return [string]                 [接口返回值]
    */
    function printerAddlist(){
     /*   $printerContent=[
            'sn'=>$this->sn,
            'key'=>$this->ukey
        ];*/
     $printerContent='';
     $printerContent.=$this->sn."#".$this->key."#".$this->name;

     /*   $printerContent=[
            'sn'=>'yuwqr4fq',
            'key'=>'921559032'
        ];*/

        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->user,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_printerAddlist',
            'printerContent'=>$printerContent
        );


         $client = new HttpClient($this->ip,$this->port);

        if(!$client->post($this->path,$msgInfo)){

            return 'error';
        }else{

            $result = $client->getContent();

            return $result;
        }
    }



    /**
     * [打印订单接口 Open_printMsg]
     * @param  [string] $sn      [打印机编号sn]
     * @param  [string] $content [打印内容]
     * @param  [string] $times   [打印联数]
     * @return [string]          [接口返回值]
     */
    function printMsg($content,$times){

        $time = time();//请求时间
        $sn=$this->sn;
        $msgInfo = array(
            'user'=>$this->user,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_printMsg',
            'sn'=>$sn,
            'content'=>$content,
            'times'=>$times//打印次数
        );

        $client = new HttpClient($this->ip,$this->port);
        if(!$client->post($this->path,$msgInfo)){
            Log::error("error");
            return 'error';
        }else{
            //服务器返回的JSON字符串，建议要当做日志记录起来
            $result = $client->getContent();
            return $result;
        }
    }



    /**
     * [获取某台打印机状态接口 Open_queryPrinterStatus]
     * @param  [string] $sn [打印机编号]
     * @return [string]     [接口返回值]
     */
     public function queryPrinterStatus(){
        $sn=$this->sn;
        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->user,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_queryPrinterStatus',
            'sn'=>$sn
        );
        $client = new HttpClient($this->ip,$this->port);
        if(!$client->post($this->path,$msgInfo)){
            return 'error';
        }else{
            $result = $client->getContent();
            return $result;
        }
    }


    /**
     * [查询指定打印机某天的订单统计数接口 Open_queryOrderInfoByDate]
     * @param  [string] $sn   [打印机的编号]
     * @param  [string] $date [查询日期，格式YY-MM-DD，如：2019-09-20]
     * @return [string]       [接口返回值]
     */
    function queryOrderInfoByDate($date){
        $time = time();         //请求时间
        $sn=$this->sn;
        $msgInfo = array(
            'user'=>$this->user,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_queryOrderInfoByDate',
            'sn'=>$sn,
            'date'=>$date
        );
        $client = new HttpClient($this->ip,$this->port);
        if(!$client->post($this->path,$msgInfo)){
            return 'error';
        }else{
            $result = $client->getContent();
            return $result;
        }
    }


    /**
     * [查询订单是否打印成功接口 Open_queryOrderState]
     * @param  [string] $orderid [调用打印机接口成功后,服务器返回的JSON中的编号 例如：123456789_20190919163739_95385649]
     * @return [string]          [接口返回值]
     */
    public function queryOrderState($orderid){
        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->user,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_queryOrderState',
            'orderid'=>$orderid
        );
        $client = new HttpClient($this->ip,$this->port);
        if(!$client->post($this->path,$msgInfo)){
            return 'error';
        }else{
            $result = $client->getContent();
            return $result;
        }
    }


    /**
     * [清空待打印订单接口 Open_delPrinterSqs]
     * @param  [string] $sn [打印机编号]
     * @return [string]     [接口返回值]
     */
    function delPrinterSqs(){

        $sn=$this->sn;
        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->user,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_delPrinterSqs',
            'sn'=>$sn
        );
        $client = new HttpClient($this->ip,$this->port);
        if(!$client->post($this->path,$msgInfo)){
            echo 'error';
        }else{
            $result = $client->getContent();
            echo $result;
        }
    }

    /**
     * [修改打印机信息接口 Open_printerEdit]
     * @param  [string] $sn       [打印机编号]
     * @param  [string] $name     [打印机备注名称]
     * @param  [string] $phonenum [打印机流量卡号码,可以不传参,但是不能为空字符串]
     * @return [string]           [接口返回值]
     */
    function printerEdit($name,$phonenum){
        $sn=$this->sn;
        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->user,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_printerEdit',
            'sn'=>$sn,
            'name'=>$name,
            'phonenum'=>$phonenum
        );
        $client = new HttpClient($this->ip,$this->port);
        if(!$client->post($this->path,$msgInfo)){
            return 'error';
        }else{
            $result = $client->getContent();
            return $result;
        }
    }


    /**
     * [批量删除打印机 Open_printerDelList]
     * @param  [string] $snlist [打印机编号，多台打印机请用减号“-”连接起来]
     * @return [string]         [接口返回值]
     */
    function printerDelList($snlist){
        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->user,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_printerDelList',
            'snlist'=>$snlist
        );
        $client = new HttpClient($this->ip,$this->port);
        if(!$client->post($this->path,$msgInfo)){
            return 'error';
        }else{
            $result = $client->getContent();
            return $result;
        }
    }


    /**
     * [标签机打印订单接口 Open_printLabelMsg]
     * @param  [string] $sn      [打印机编号sn]
     * @param  [string] $content [打印内容]
     * @param  [string] $times   [打印联数]
     * @return [string]          [接口返回值]
     */
    function printLabelMsg($sn,$content,$times){
        $time = time();         //请求时间
        $msgInfo = array(
            'user'=>$this->user,
            'stime'=>$time,
            'sig'=>$this->signature($time),
            'apiname'=>'Open_printLabelMsg',
            'sn'=>$sn,
            'content'=>$content,
            'times'=>$times//打印次数
        );
        $client = new HttpClient($this->ip,$this->port);
        if(!$client->post($this->path,$msgInfo)){
            echo 'error';
        }else{
            //服务器返回的JSON字符串，建议要当做日志记录起来
            $result = $client->getContent();
            echo $result;
        }
    }


    /**
     * [signature 生成签名]
     * @param  [string] $time [当前UNIX时间戳，10位，精确到秒]
     * @return [string]       [接口返回值]
     */
    private function signature($time){
        return sha1($this->user.$this->ukey.$time);//公共参数，请求公钥
    }




}