<?php


namespace ruhua\events\listens;


use app\model\Goods as GoodsModel;

class ReduceStockAdd
{
    public function handle($event)
    {
        $post = $event;
        $this->reduceStockAdd($post);
    }

    public function reduceStockAdd($data){
        GoodsModel::ReduceStockAdd($data['order_id']);
    }
}