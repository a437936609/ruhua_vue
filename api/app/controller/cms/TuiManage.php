<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/26 0026
 * Time: 11:36
 */

namespace app\controller\cms;

use app\model\Order as OrderModel;
use app\model\Tui as TuiModel;
use app\services\TuiService;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use ruhua\exceptions\OrderException;
use think\facade\Log;

class TuiManage extends BaseController
{
    /**
     * cms 获取所有退款信息
     * @return mixed
     */
    public function getTuiAll()
    {
        $res = TuiModel::with('order')->order('create_time desc')->select();
       // mb_convert_encoding($res, 'UTF-8', 'UTF-8');
        foreach ($res as $k => $v) {
            $res[$k]['money'] = $v['money'];
        }
        return app('json')->success($res);
    }

    /**
     * 微信退款
     * @param string $id
     * @return mixed
     */
    public function TuiMoney($id='')
    {
        $data = input('post.');
        (new IDPostiveInt())->goCheck();
        return (new TuiService())->icbc_pay($id);
    }

    /**
     * 手动退款
     * @param string $id
     * @return mixed
     */
    public function TuiMoneyHand()
    {
        $data = input('get.');
        $id = $data['id'];
        $msg = $data['msg'];
        return app('json')->go(\app\model\FxManual::tuiMoneyHand($id,$msg));
    }
    /*
     * 添加备注
     * */
    public function MoneyMsg()
    {
        $data = input('get.');
        $id = $data['id'];
        $msg = $data['msg'];
        return app('json')->go(\app\model\FxManual::MoneyMsg($id,$msg));
    }
    /**
     * 退款申请驳回
     * @param $id
     * @param $msg
     * @return mixed
     */
    public function TuiBoHui($id, $msg)
    {

        return OrderModel::TuiBoHui($id,$msg);
    }
}