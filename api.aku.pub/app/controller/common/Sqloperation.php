<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/12/7 0007
 * Time: 8:40
 */

namespace app\controller\common;


use app\model\SysConfig;
use ruhua\bases\BaseController;
use PDO;
use ruhua\exceptions\BaseException;
use think\db\exception\PDOException;
use mysqli;
use think\facade\Db;
use think\facade\Log;
use app\model\SysConfig as SysCongfigModel;

class Sqloperation extends BaseController
{
    private $dataBases="";
    private $old_dht;
    private $new_dht;
    private $username="";
    private $password="";
    private $servername="localhost";

    /**
     * 创建临时数据库
     */
    private function create_data_bases()
    {
        $this->dataBases="rh".time();
        try {
            $conn = new PDO("mysql:host={$this->servername}", $this->username, $this->password);
            // 设置 PDO 错误模式为异常
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE {$this->dataBases}";
            // 使用 exec() ，因为没有结果返回
            $conn->exec($sql);
        }
        catch(PDOException $e)
        {
            throw new BaseException(['msg'=>$e->getMessage()]);
        }
    }

    /**
     * 导入sql到临时数据库
     */
    private function import_sql_data()
    {
        try {
            $link = @new mysqli("{$this->servername}:3306", $this->username, $this->password);
            $error = $link->connect_error;
            if (!is_null($error)) {
                // 转义防止和alert中的引号冲突
                throw new BaseException(['msg' => '数据库链接失败']);

            }
            $link->query("SET NAMES 'utf8'");
            if ($link->server_info < 5.0) {
                throw new BaseException(['msg' => '请将您的mysql升级到5.0以上']);
            }
            $link->select_db($this->dataBases);
            $vaethink_sql = file_get_contents(VAE_ROOT . '/data/ruhua.sql');
            $sql_array = preg_split("/;[\r\n]+/", str_replace("ims_qy2019_shop_", "rh_", $vaethink_sql));
            foreach ($sql_array as $k => $v) {
                if (!empty($v)) {
                    $link->query($v);
                }
            }

        }catch(PDOException $e)
        {
            throw new BaseException(['msg'=>$e->getMessage()]);
        }
    }

    private  function add_field()
    {
        $databases=config('database.connections.mysql.database');
        $username=config('database.connections.mysql.username');
        $prefix=config('database.connections.mysql.prefix');

        $password=config('database.connections.mysql.password');
        $this->old_dht=new PDO("mysql:host={$this->servername};dbname={$databases}",$username,$password);

        $this->new_dht=new PDO("mysql:host={$this->servername};dbname={$this->dataBases}",$this->username,$this->password);
        $this->old_dht->query("set names utf8");
        $this->new_dht->query("set names utf8");
        $sql=" show tables";
        $old_tables=$this->old_dht->query($sql)->fetchAll();
        $new_tables=$this->new_dht->query($sql)->fetchAll();

        Db::startTrans();// 启动事务
        try {
            foreach ($new_tables as $k=>$v){
                $flg=0;
                foreach ($old_tables as $key=>$val){
                    if($val[0]==$v[0]){
                        $flg=1;
                        break;
                    }
                }
                if($flg==0){
                    $sql="show create table {$v[0]}";

                    $table_sql=$this->new_dht->query($sql)->fetch();
                    $this->old_dht->exec($table_sql['Create Table']);
                }
            }



            $sql="SELECT TABLE_NAME,COLUMN_TYPE,COLUMN_COMMENT,column_name,NUMERIC_SCALE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA ='{$databases}'";
            $old_field=$this->old_dht->query($sql)->fetchAll();

            $sql="SELECT TABLE_NAME,COLUMN_TYPE,COLUMN_COMMENT,column_name,NUMERIC_SCALE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '{$this->dataBases}'";
            $new_field=$this->new_dht->query($sql)->fetchAll();
            $arr=array();
            $j=0;

            foreach ($new_field as $k=>$v){
                $flg=1;
                foreach ($old_field as $key=>$val){
                    $flg=1;
                    if($v['TABLE_NAME']==$val['TABLE_NAME']&&$v['column_name']==$val['column_name']){
                        $flg=0;
                       break;
                    }

                }
                if($flg==1){
                    $arr[$j++]=$v['column_name'];
                    $sql="ALTER TABLE {$v['TABLE_NAME']} ADD {$v['column_name']} {$v['COLUMN_TYPE']} DEFAULT  '{$v['NUMERIC_SCALE']}'";


                    $res=$this->old_dht->exec($sql);
                }

            }

            $old_sys=$this->old_dht->query("select *from {$prefix}sys_config")->fetchAll();
            $new_sys=$this->new_dht->query("select *from rh_sys_config")->fetchAll();
            foreach ($new_sys as $k=>$v){
                $flg=1;
                foreach ($old_sys as $key=>$val){
                    if($v['key']==$val['key']){
                        $flg=0;
                        break;
                    }
                }
                if($flg==1){

                   $sql="insert into {$prefix}sys_config(`key`,`value`,`desc`,`type`,switch,other) values('{$v['key']}','{$v['value']}','{$v['desc']}','{$v['type']}','{$v['switch']}','{$v['other']}')";
                   $this->old_dht->exec($sql);
                    $arr[$j++]=$v;
                }
            }


            $this->new_dht->exec("drop database {$this->dataBases}");

            Db::commit();

        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new ProductException(['msg' => '插入失败' . $e->getMessage()]);
        }



        return $arr;


    }


    public function update_sql_field()
    {
        $data=input('post.');
        $rule=[
            'username'=>'require',
            'password'=>'require'
        ];
        $this->validate($data,$rule);
        $this->username=$data['username'];
        $this->password=$data['password'];

        $this->create_data_bases();

        $this->import_sql_data();

        $res=$this->add_field();
        return app('json')->go($res);

    }

}