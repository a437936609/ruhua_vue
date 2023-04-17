<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 14:28
 */

namespace app\model;


use ruhua\bases\BaseModel;
use think\facade\Request;

class Image extends BaseModel
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