<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/7 0007
 * Time: 15:38
 */

namespace app\services;


use app\model\FxOrder as FxOrderModel;
use app\model\FxRecord as FxRecordModel;
use app\model\Order as OrderModel;
use app\model\PtItem as PtItemModel;
use app\model\UserCoupon as UserCouponModel;
use think\facade\Cache;
use think\facade\Log;

class TaskService
{

    /**
     * 自动提现任务
     * @return mixed
     */
    public function TxTask()
    {
        $this->clearCache();
        try {
            (new FxRecordModel)->taskTx(); //定时打款
        } catch (\Exception $e) {
            return app('json')->fail($e);
        }
        return app('json')->success();
    }

    //清除缓存
    public function clearCache()
    {
        //echo CACHE_PATH;  //缓存地址 --CACHE_PATH
        if(file_exists(CACHE)){
            $this->delDir(CACHE);
        }
    }


    //删除缓存文件
    public function delDir($dirName) {
        $dh = opendir($dirName);
        //循环读取文件
        while ($file = readdir($dh)) {
            if($file != '.' && $file != '..') {
                $fullpath = $dirName . '/' . $file;
                //判断是否为目录
                if(!is_dir($fullpath)) {
                    //如果不是,删除该文件
                    if(!unlink($fullpath)) {
                        echo $fullpath . '无法删除,可能是没有权限!<br>';
                    }
                } else {
                    //如果是目录,递归本身删除下级目录
                    $this->delDir($fullpath);
                }
            }
        }
        //关闭目录
        closedir($dh);
        //删除目录
        //if(!rmdir($dirName)) {
        //     R('Public/errjson',array($dirName.'__目录删除失败'));
        //}
    }


    /**
     * 每日任务
     * @return mixed
     */
    public function DayTask()
    {
        try {
            FxOrderModel::deleteOrder(); //删除会员订单
            UserCouponModel::delUserCoupon();//删除用户过期优惠券
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage());
        }
        return app('json')->success();
    }

    /**
     * 关闭订单任务
     * @return mixed
     */
    public function MinTask()
    {
        Log::error("关闭订单任务");
        try {
            OrderModel::closeOrder(); //关闭订单
            if (config('setting.is_business')) {
                PtItemModel::closeItem();//关闭拼团订单并退款
            }
        } catch (\Exception $e) {
            return app('json')->fail($e->getMessage());
        }
        return app('json')->success();
    }

    /**
     * 检看系统自动关闭订单运行状态
     */
    public function checkLoopTask()
    {
        $where['state'] = 0;
        $where['payment_state'] = 0;
        $time = time() - 60 * 60;
        $res = OrderModel::where($where)->whereTime('create_time', '<', $time)->find();
        if ($res) {
            return app('json')->success(0);
        }
        return app('json')->success(1);
    }

    /**
     * 循环任务
     * @param $code
     * @return string
     */
    public function loopTask($code)
    {
        static $vaeIsInstalled;
        if (empty($vaeIsInstalled)) {
            $vaeIsInstalled = file_exists(VAE_ROOT . 'data/task.lock');
        }
        if ($vaeIsInstalled) {
            return app('json')->fail();
        } else {
            file_put_contents(VAE_ROOT . "data/task.lock", 'ruhua定时文件，勿删！！！！！时间：' . date('Y-m-d H:i:s', time()));
        }
        if ($code == '174369') {
            ignore_user_abort(); //即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
            set_time_limit(0); // 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
            $interval = 15*60; // 每隔15分钟运行
            do {
                OrderModel::closeOrder(); //关闭订单
                PtItemModel::closeItem();//关闭拼团订单并退款
                sleep($interval); // 按设置的时间等待15分钟循环执行
                $str = file_get_contents(VAE_ROOT . "data/taskstate.lock");
                Log::error('循环任务状态' . $str);
            } while ($str=='1');
        } else {
            return app('json')->fail();
        }
        return app('json')->success();
    }
}