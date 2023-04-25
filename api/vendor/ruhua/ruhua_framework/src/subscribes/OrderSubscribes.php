<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/8 0008
 * Time: 10:09
 */

namespace  ruhua\subscribes;

use app\model\Goods as GoodsModel;
use app\model\Order as OrderModel;
use app\model\OrderLog as OrderLogModel;
use app\model\PtItem;
use app\model\UserCoupon as UserCouponModel;
use app\services\DeliveryMessage;
use app\services\GzhDeliveryMessage;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\OrderException;
use think\facade\Log;

/**
 * 订单事件
 * Class OrderSubscribes
 * @package subscribes
 */
class OrderSubscribes
{
    public function handle()
    {

    }

    /**
     * 检测库存
     * @param $event
     * @return int
     * @throws OrderException
     */
    public function onCheckStock($event)
    {
        $data = OrderModel::with('ordergoods')->where('order_id', $event)->find();
        GoodsModel::checkStock($data['order_goods']);
    }

    public function onInvoiceLog($event)
    {
        if(app('system')->getValue('is_invoice') == 1) {
            OrderLogModel::addInvoiceLog($event);
        }
    }

    /**
     * 扣除库存
     * @param $event
     */
    public function onReduceStock($event)
    {
        $data=$event['order_goods'];
        GoodsModel::editStock($data);
    }

    /**
     * 检测是否开启拼团，能否参加拼团
     * @param $event
     * @return int
     * @throws OrderException
     */
    public function onCheckItem($event)
    {
        if(config('setting.is_business') == 1) {
            PtItem::checkItemUser($event);
        }

    }

    /**
     * 公众号发送模板消息通知管理员
     * @param $event
     */
    public function onSendGzhDeliveryMessage($event)
    {

        list($data,$type,$ids)=$event;
        $message = new GzhDeliveryMessage();
         $message->sendDeliveryMessage($data,$type,$ids);  //公众号发送模板消息通知管理员
    }

    /**
     * 小程序发送模板消息通知用户
     * @param $event
     */
    public function onSendDeliveryMessage($event)
    {
        $message = new DeliveryMessage();
        $message->sendDeliveryMessage($event, '');//小程序发送模板消息通知用户
    }
    /**
     * 优惠券回收
     * @param $data
     * @throws OrderException
     */
    public function oneditCouponNo($data){
        if($data['coupon_id']){
            $coupon=UserCouponModel::where('id',$data['coupon_id'])->update(['status'=>0]);
            if(!$coupon){
                throw new OrderException();
            }
        }
    }
    /*
     * 库存添加
     * */
    public function onreduceStockAdd($data){
        if(isset($data['order_id']))
            $tes=GoodsModel::ReduceStockAdd($data['order_id']);
        else
            $tes = GoodsModel::ReduceStockAdd($data['id']);
    }
    /*
     * 库存减少s
     * */
    public function onreduceStocks($data){
        if(isset($data['order_id']))
            $tes=GoodsModel::ReduceStock($data['order_id']);
        else
            $tes = GoodsModel::ReduceStock($data['id']);
    }
}