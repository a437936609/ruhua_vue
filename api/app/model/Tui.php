<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21 0021
 * Time: 14:10
 */

namespace app\model;


use ruhua\bases\BaseModel;

class Tui extends BaseModel
{
    //获取器 提前修改
    public function getNicknameAttr($v)
    {
        $data = base64_decode($v);
        if($data=='')
            return $v;
        return $data;
    }
}