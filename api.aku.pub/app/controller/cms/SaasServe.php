<?php


namespace app\controller\cms;


use app\controller\auth\Token;
use app\model\Rate as RateModel;
use extend\poster\Poster;
use ruhua\bases\BaseCommon;
use app\model\Goods;
use ruhua\bases\BaseController;
use app\model\Image;
use ruhua\exceptions\BaseException;
use think\facade\Db;
use think\facade\Config;
use app\services\QrcodeServer;
use think\facade\Log;
use think\facade\Request;

class SaasServe extends BaseController
{

    /**
     * *导入测试数据
     */
    public function import_data($stoken){

        if(file_exists(VAE_ROOT . 'data/import_plug.lock')){
            throw new BaseException(['msg'=>'已安装']);
        }




        $data=(new Token())->get_sin_token($stoken);
        if($data['code']!=200){
            throw new BaseException(['msg'=>$data['msg']]);
        }

        Log::error("test start");
        $get=(new BaseCommon())->curl_sa_post($stoken,WEB_URL.'export');
        $get1=json_decode($get,true);

        $post=$get1['data'];
        $w = Config::get('database.connections.mysql.prefix');
        $mysql = file_get_contents($post['sqlurl']);
        $sql_array = preg_split("/;[\r\n]+/", $mysql);
        Db::Query('TRUNCATE TABLE rh_category');
        Db::startTrans();
        try {
            foreach ($sql_array as $k => $v) {
                if (!empty($v)) {
                    $q = substr($v,strpos($v,'_')+1);
                    $l = "INSERT INTO `".$w.$q;
                    Log::write($l);
                    Db::Query($l);
                }
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new BaseException(['msg'=>'导入数据失败']);
        }
        $path1="uploads/other/";
        $data[]=(new BaseCommon())->dw($post['imgs'],$post['imgsurl'],$path1);
        file_put_contents(VAE_ROOT . "data/import_plug.lock", 'ruhua安装鉴定文件，勿删！！！！！此次安装时间：' . date('Y-m-d H:i:s', time()));

    }
    /**
     * 一键评价
     */
    public function keyRate($stoken){

        $data=(new Token())->get_sin_token($stoken);

        if($data['code']!=200){
            throw new BaseException(['msg'=>$data['msg']]);
        }
        $post=input('post.');
        $get=(new BaseCommon())->curl_sa_post($stoken,WEB_URL.'evaluate',$post);
        $data=json_decode($get,true);
        $imgs=[];
        $list=[];
        $rate=new RateModel();
        Log::error($data);
        foreach ($data['data'] as $v) {
            if(is_array($v)) {
                $list[]=$v;
                array_push($imgs, $v['headpic']);
            }
        }
        Db::startTrans();
        try {
            $res=$rate->saveAll($list);
            Db::commit();
            $res['img']=(new BaseCommon())->dw($imgs,$data['data']['imgurl'],"/uploads/other/");
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail(['msg'=>$e->getMessage()]);
        }
        return app('json')->success($res);
    }
    /**
     * 安装插件
     */
    public function installPlug($pulg,$stoken)
    {
        if(file_exists(VAE_ROOT . "data/$pulg.lock")){
            return app('json')->fail("已安装");
        }

        $post=(new Token())->get_sin_token($stoken);
        if($post['code']!=200){
            throw new BaseException(['msg'=>$post['msg']]);
        }
        $path1="../extend/".$pulg;
        $a['pulg']=$pulg;
        $res=json_decode((new BaseCommon())->curl_sa_post($stoken,WEB_URL.'tf_file',$a),true);
        if (! file_exists($path1)) {
            mkdir ( "$path1", 0777, true );
        }

        $data[]=(new BaseCommon())->download($res['data']['files'],$res['data']['fileurl'],$path1."/");
        file_put_contents(VAE_ROOT . "data/$pulg.lock", 'ruhua安装鉴定文件，勿删！！！！！此次安装时间：' . date('Y-m-d H:i:s', time()));
       return app('json')->success($data);
    }

    /**
     * 安装插件
     */
    public function installGamePlug($pulg,$stoken)
    {
        if(file_exists(VAE_ROOT . "data/$pulg.lock")){
            return app('json')->fail("已安装");
        }

        $post=(new Token())->get_sin_token($stoken);
        if($post['code']!=200){
            throw new BaseException(['msg'=>$post['msg']]);
        }

        $this->installGame('Game',$stoken);

        $path1="../extend/".$pulg;
        $a['pulg']=$pulg;
        $res=json_decode((new BaseCommon())->curl_sa_post($stoken,WEB_URL.'tf_file',$a),true);
        if (! file_exists($path1)) {
            mkdir ( "$path1", 0777, true );
        }

        $data[]=(new BaseCommon())->download($res['data']['files'],$res['data']['fileurl'],$path1."/");
        file_put_contents(VAE_ROOT . "data/$pulg.lock", 'ruhua安装鉴定文件，勿删！！！！！此次安装时间：' . date('Y-m-d H:i:s', time()));


        return app('json')->success($data);
    }


    /**安装抽奖主插件
     * @param $pulg
     * @param $stoken
     * @return mixed
     * @throws BaseException
     */
    public function installGame($pulg,$stoken)
    {

        if(file_exists(VAE_ROOT . "data/$pulg.lock")){
            return app('json')->fail("已安装");
        }

        $post=(new Token())->get_sin_token($stoken);
        if($post['code']!=200){
            throw new BaseException(['msg'=>$post['msg']]);
        }
        $path1="../extend/".$pulg;
        $a['pulg']=$pulg;
        $res=json_decode((new BaseCommon())->curl_sa_post($stoken,WEB_URL.'tf_file',$a),true);
        if (! file_exists($path1)) {
            mkdir ( "$path1", 0777, true );
        }

        $data[]=(new BaseCommon())->download($res['data']['files'],$res['data']['fileurl'],$path1."/");
        file_put_contents(VAE_ROOT . "data/$pulg.lock", 'ruhua安装鉴定文件，勿删！！！！！此次安装时间：' . date('Y-m-d H:i:s', time()));

    }

    /**
     * @param $lock
     * @return mixed
     * 判断插件是否安装
     */
    //
    public function is_Install()
    {
        $lock=input('post.lock');
        static $vaeIsInstalled;
        if (empty($vaeIsInstalled)) {
            $vaeIsInstalled = file_exists(VAE_ROOT . 'data/'.$lock.'.lock');
        }
        return app('json')->success($vaeIsInstalled);//true or false   是否安装
    }

    public function is_game_Install()
    {
        $lock=input('post.lock');
        Log::error("Game");
        $game_lock="Game";
        $gameInstalled = file_exists(VAE_ROOT . 'data/'.$game_lock.'.lock');
        if(!$gameInstalled){
            return app('json')->success($gameInstalled);//true or false   是否安装
        }

        static $vaeIsInstalled;
        if (empty($vaeIsInstalled)) {
            $vaeIsInstalled = file_exists(VAE_ROOT . 'data/'.$lock.'.lock');
        }
        return app('json')->success($vaeIsInstalled);//true or false   是否安装
    }

    /**
     * 生成海报
     */
    public function createPoster($id){
        $filename=uniqid() . '.png';
        $goods=Goods::getProductByID($id);
        $code=(new QrcodeServer())->getCodeUrlPng("http://baidu.com");
        $image=new Image();
        $goods['img']= $image->field('url')->find($goods['img_id']);
        (new Poster())->createPoster($goods,$code,"../public/Images/".$filename);
        $poster['url']="./Images/".$filename;
        return app('json')->success("操作成功",$poster);
    }

    public function get_auth()
    {
        $data=(new Token())->get_sin_token();
        if($data['code']!=200){
            throw new BaseException(['msg'=>$data['msg']]);
        }
        if($data['token']==null){
            throw new BaseException(['msg'=>'没有权限']);
        }
        return app('json')->go($data);
    }

    public function save_otoken()
    {
        $res=(new Token())->getCloudToken();
        return app('json')->go($res);
    }

}