<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/23 0023
 * Time: 8:43
 */

namespace  ruhua\events\listens;

use app\model\Goods as GoodsModel;
use think\facade\Log;


class ReduceStock
{
    public function handle($event)
    {
        $post = $event;
        $this->reduceStock($post);
    }

    public function reduceStock($data){
        if(isset($data['order_id']))
            $tes=GoodsModel::ReduceStock($data['order_id']);
        else
            $tes = GoodsModel::ReduceStock($data['id']);
    }

}