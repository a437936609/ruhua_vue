<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/18 0018
 * Time: 13:14
 */

namespace app\controller\cms;


use app\services\PrinterService;
use ruhua\bases\BaseController;
use Feie\FeieDy;
use think\facade\Log;
use app\model\SysConfig as SysConfigModel;
use app\model\Order as OrderModel;

class Printer extends BaseController
{

    /**打印机推送云平台
     * @return mixed
     */
    public function add_printer()
    {
        $printer=new FeieDy();
        $res=$printer->printerAddlist();
        $res=json_decode($res);
        return app('json')->success($res);
    }

    /**订单打印
     * @param $order_id
     * @return string
     */
    public static function order_printer($order_id)
    {
        $isprinter=SysConfigModel::where('key','is_printer')->value('value');
        $os=OrderModel::where(['order_num'=>$order_id,'payment_state'=>0])->find();
        if(!$os){
        	return null;
        }
        $content=(new PrinterService())->check_order($order_id);

        if($isprinter>0&&$content!=null){
        	Log::error("printer");
            $printer=new FeieDy();
            return $printer->printMsg($content,1);
        }else{
            return null;
        }

    }

    /**
     * 获取打印机接口状态
     */
    public function queryPrinterStatus()
    {
        $printer=new FeieDy();
        $res=$printer->queryPrinterStatus();
        return app('json')->go($res);

    }

    /**查询指定打印机某天的订单统计数接口
     * @param $data日期
     */
    public function queryOrderInfoByDate($date)
    {
        $printer=new FeieDy();
        $res=$printer->queryOrderInfoByDate($date);
        return app('json')->go($res);

    }

    public function queryOrderState($orderId)
    {
        $printer=new FeieDy();
        $res=$printer->queryOrderState($orderId);
        return $res;

    }





}