<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/11 0011
 * Time: 14:19
 */

namespace app\model;


use ruhua\bases\BaseCommon;
use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\OrderException;
use think\facade\Db;
use think\facade\Log;
use think\facade\Request;
use app\model\VipUser as VipUserModel;

class Events extends BaseModel
{
    static protected $str='VVM9YzlwSWZtRUE3dFZScThoZGN6aU5KaG09RnpUZU95VE85LWpUQ25DZlhsVEtJeW9ySWNYUkRzVXJJTQ%3D%3D';

    protected $hidden = ['create_time', 'update_time'];

    public function eventsGoods()
    {
        return $this->hasMany('EventsGoods', 'events_id', 'id');
    }


}