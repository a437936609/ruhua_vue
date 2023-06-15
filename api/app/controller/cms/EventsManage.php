<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/30 0030
 * Time: 11:03
 */

namespace app\controller\cms;

use app\model\Events as EventsModel;
USE app\model\EventsGoods as EventsGoodsModel;
use app\model\Goods;
use think\facade\Request;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use think\facade\Log;

class EventsManage extends BaseController
{

    /**
     * 前端获取所有专题
     * @return mixed
     */
    public function getAllEvents()
    {
        $where[] = ['state','=',1];//显示上架

        $res = EventsModel::where($where)->select();
        return app('json')->success($res);
    }



    /**
     * 获取所有专题
     * @return mixed
     */
    public function getEvents()
    {
        $res = EventsModel::with('eventsGoods.goods')->select();
        $res=(new Goods())->get_es_img($res);
        foreach ($res as $k=>$v){
            foreach ($v['events_goods'] as $kk=>$vv){
                if(!$vv['goods']){
                    unset($res[$k]['events_goods'][$kk]);
                }
            }
        }
        return app('json')->success($res);
    }

    /**
     * 添加专题
     * @return mixed
     */
    public function addEvents()
    {
        $rule = [
            'name' => 'require|max:60',
        ];
        $post = input('post.');
        $this->validate($post, $rule,[],true);

        return EventsModel::addEvents($post);
    }

    /**
     * 添加专题内楼层商品
     * @return mixed
     */
    public function addEventsGoods()
    {
        $rule = [
            'id' => 'require',
        ];
        $get = input('get.');

        $this->validate($get, $rule,[],true);
        return EventsModel::addEventsGoods($get);
    }




    /**
     * 修改优惠活动
     * @return mixed
     */
    public function editEvents()
    {
        $rule = [
            'name' => 'require|max:60',
        ];
        $post = input('post.');

        return EventsModel::editEvents($post);
    }

    /**
     * 修改专题内楼层商品
     * @return mixed
     */
    public function editEventsGoods()
    {
        $rule = [
            'name' => 'require|max:60',
        ];
        $post = input('post.');
        $this->validate($post, $rule,[],true);

        return EventsModel::editEventsGoods($post);
    }



    /**
     * 删除专题
     * @param $id
     * @return mixed
     */
    public function deleteEvents($id)
    {
        (new IDPostiveInt)->goCheck();
        return DiscountModel::deleteDiscount($id);
    }



    /**
     * 获取专题商品
     * @return mixed
     */
    public function getEventsGoods()
    {
        $rule = [
            'id' => 'require',
        ];
        $param = Request::param();
        $this->validate($param, $rule);

        $res = EventsGoodsModeL::getEventsGoods($param);

        Log::error('专题活动');
        Log::error($res);
        if ($res) {
            return app('json')->success($res);
        }
        return app('json')->fail();
    }
}