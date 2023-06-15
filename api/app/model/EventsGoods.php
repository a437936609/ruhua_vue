<?php
namespace app\model;


use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use think\db\Where;
use think\facade\Log;
use think\Model;

class EventsGoods extends BaseModel
{
    public function events()
    {
        return $this->belongsTo('Events', 'events_id', 'id');
    }

    public function label()
    {
        return $this->belongsTo('EventsLabel', 'label_id','id');
    }

    public function goods()
    {
        return $this->belongsTo('Goods', 'goods_id', 'goods_id');
    }

    /**
     * 获取指定专题信息
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getEventsGoods($param)
    {
        $data=[];
        $rows=[];
        $rs = Events::where('id', $param['id'])->find()->toArray();
        if($rs){
            $rows = EventsLabel::where('events_id', $param['id'])->select()->order('sort desc')->toArray();
            foreach($rows as $key=>$val)
            {
                $res = EventsGoods::with(['goods.imgs'])->where('label_id',$val['id'])->select()->toArray();
                foreach ($res as $k => $v) {
                    if (!$v['goods']['state']) {
                        unset($res[$k]);
                    }
                    if(!$v['goods']||!$v['goods']['state']){
                        unset($res[$k]);
                    }
                    if(!empty($res[$k])){
                        $data['goods_id']       = $v['goods']['goods_id'];
                        $data['goods_name']     = $v['goods']['goods_name'];
                        $data['goods_code']     = $v['goods']['goods_code'];
                        $data['ic_code']        = $v['goods']['ic_code'];
                        $data['price']          = $v['goods']['price'];
                        $data['stock']          = $v['goods']['stock'];
                        $data['sales']          = $v['goods']['sales'];
                        $data['sort']           = $v['goods']['sort'];
                        $rows[$key]['goods'][] = $data;
                    }
                }
            }
        }
        return $rows;
    }

}