<?php
namespace app\model;


use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use think\facade\Log;
use think\Model;

class EventsGoods extends BaseModel
{
    public function events()
    {
        return $this->belongsTo('Events', 'events_id', 'id');
    }

    public function goods()
    {
        return $this->belongsTo('Goods', 'goods_id', 'goods_id');
    }

}