<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21 0021
 * Time: 9:13
 */

namespace app\model;


use ruhua\bases\BaseModel;
use think\model\concern\SoftDelete;

class OrderGoods extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden = ['delete_time'];

    //关联订单商品模型
//    public function orders()
//    {
//        return $this->belongsTo('Order','order_id','order_id');
//    }

//    //关联规格模型
//    public function sku()
//    {
//        return $this->hasMany('GoodsSku','goods_id','goods_id');
//    }
//
    //关联图片
    public function imgs()
    {
        return $this->belongsTo('Image','pic_id','id')->field('id,url');
    }

//
//    //关联团单
//    public function item()
//    {
//        return $this->belongsTo('item','item_id','id');
//    }
//
//    public function setImgsAttr($v)
//    {
//        return $v['url'];
//    }
    //商品销售统计
      public static function order_stats(){
        $post=input('post.');
        if(isset($post['goods_id']) && !empty($post['goods_id'])) {
            $where[]=['goods_id','in',$post['goods_id']];
        }
        if(isset($post['stat_time'])&&!empty($post['stat_time'])&&isset($post['end_time'])&&!empty($post['end_time'])){
            $where[]=[['create_time','>=',$post['stat_time']],['create_time','<=',$post['end_time']]];
        }
        else{
            $post['stat_time']=strtotime(date("Y-m-d H:i:s", strtotime("-1 month")));
            $post['end_time']=time();
            $where[]=[['create_time','>=',$post['stat_time']],['create_time','<=',$post['end_time']]];
          }
        $data = self::where('state','=',1)->where($where)->field('goods_id,goods_name,price,sum(num)')->group('goods_id')->select();
        return $data;
      }

      //通过id返回数据
    public static function selectId($orderid){
        $res = self::where('order_id',$orderid)->find();
        return $res;
    }


    //关联内控编码
    public function iccode()
    {
        return $this->hasOne('Goods','goods_id','goods_id')->bind(['ic_code']);
    }
    
}