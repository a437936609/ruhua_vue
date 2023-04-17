<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/29 0029
 * Time: 14:20
 */

namespace app\model;


use ruhua\bases\BaseModel;
use think\facade\Request;

class SysBackup extends BaseModel
{
    public static function addBackup($name,$size)
    {
        $data['name']=$name;
        $data['size']=$size;
        $data['url']='/backup/'.$name;
        self::create($data);
    }
    public function getUrlAttr($value)
    {
        $x= substr( $value, 0, 1 );
        if($x!="h"){
            $url=Request::domain();
        }
        else
            $url="";
        return $url.$value;

    }
}