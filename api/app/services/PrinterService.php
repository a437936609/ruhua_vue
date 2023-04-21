<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/18 0018
 * Time: 14:25
 */

namespace app\services;
use app\model\Order as OrderModel;
use app\model\SysConfig;
use ruhua\exceptions\BaseException;
use app\model\OrderGoods as OrderGoodModel;
use think\facade\Log;

class PrinterService
{
    public function check_order($order_num)
    {
        $order=OrderModel::where(['order_num'=>$order_num,'payment_state'=>0])->find();
        if(!$order){

        	 return null;
           // throw new BaseException(['msg'=>'暂无订单数据']);
        }
        $order_goods=OrderGoodModel::where('order_id',$order['order_id'])->select();
        if(count($order_goods)==0){
        		 Log::error('暂无订单商品');
        		 return null;
            //throw new BaseException(['msg'=>'暂无订单商品']);
        }
        $printer_name=SysConfig::where('key','feie_name')->value('value');
        if($printer_name==''){
            $printer_name="如花商城";
        }
        $content="<CB>$printer_name 订单</CB><BR>";

        for($i=0;$i<count($order_goods);$i++)
        {
        	
            $name=$order_goods[$i]['goods_name'];
            $total_price=$order_goods[$i]['total_price'];
            if($total_price==0||$total_price==''){
            	 continue;
            }
            $num=$order_goods[$i]['num'];
            $price=$order_goods[$i]['price'];
            $content .= '--------------------------------<BR>';
            $content .= "商品：$name<BR>";
            if($order_goods[$i]['sku_id']>0||$order_goods[$i]['sku_name']!=null){
                $sku_name=$order_goods[$i]['sku_name'];
                $content .= "类型：$sku_name<BR>";
            }
            $content .= "数量：$num<BR>";
            $content .= "单价：$price<BR>";
            $content .= "总价：$total_price<BR>";
        }
        $content .= '--------------------------------<BR>';
        $order_total=$order['order_money'];
        $order_num=$order['order_num'];

        $receiver_name=$order['receiver_name'];
        $receiver_mobile=$order['receiver_mobile'];
        $receiver_city=$order['receiver_city'];
        $receiver_address=$order['receiver_address'];
        $content .= "订单总价：$order_total<BR>";
        $content .= "订单编号：$order_num<BR>";

        $content .= "收货人  ：$receiver_name<BR>";
        $content .= "联系电话：$receiver_mobile<BR>";
        $content .= "收货城市：$receiver_city<BR>";
        $content .= "详细地址：$receiver_address<BR>";
        return $content;


    }

}