<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/31 0031
 * Time: 11:14
 */

namespace app\model;


use ruhua\bases\BaseModel;
use think\facade\Request;

class Video extends BaseModel
{

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