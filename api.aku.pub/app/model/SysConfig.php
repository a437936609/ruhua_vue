<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 8:52
 */

namespace app\model;


use ruhua\bases\BaseModel;

class SysConfig extends BaseModel
{

    protected function setCreateTimeAttr($value)
    {
        return time();
    }

    protected function setUpdateTimeAttr($value)
    {
        return time();
    }

    public static function get($value)
    {
        return self::where('key',$value)->value('value');
    }

    /**
     * \获取抖音appid和key
     */
    public static function get_dy_infor()
    {
        $data=[
            'appid'=>self::where('key','dy_appid')->value('value'),
            'secret'=>self::where('key','dy_AppSecret')->value('value')
        ];
        return $data;
    }

    /**
     * \获取小程序appid和key
     */
    public static function get_xcx_infor()
    {
        $data=[
            'appid'=>self::where('key','wx_app_id')->value('value'),
            'secret'=>self::where('key','wx_app_secret')->value('value')
        ];
        return $data;
    }

}