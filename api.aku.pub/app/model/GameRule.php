<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28 0028
 * Time: 16:35
 */

namespace app\model;



use ruhua\bases\BaseModel;
use think\facade\Request;

class GameRule extends BaseModel
{
    public function nav()
    {
        return $this->belongsTo('Nav','nav_id','id');
    }
    public function getImgIdAttr($value)
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