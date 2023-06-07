<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/11 0011
 * Time: 14:19
 */

namespace app\model;


use ruhua\bases\BaseCommon;
use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\OrderException;
use think\facade\Db;
use think\facade\Log;
use think\facade\Request;
use app\model\VipUser as VipUserModel;

class Events extends BaseModel
{
    static protected $str='VVM9YzlwSWZtRUE3dFZScThoZGN6aU5KaG09RnpUZU95VE85LWpUQ25DZlhsVEtJeW9ySWNYUkRzVXJJTQ%3D%3D';

    protected $hidden = ['create_time', 'update_time'];

    public function eventsGoods()
    {
        return $this->hasMany('EventsGoods', 'events_id', 'id');
    }

    /**
     * 添加专题活动
     * @param $post
     * @return mixed
     */
    public static function addEvents($post)
    {
        $hd_res = self::where('name', $post['name'])->find();
        if ($hd_res) {
            return app('json')->fail('专题名称已存在');
        }
        Db::startTrans();
        try {
            $res = self::create($post);
            Db::commit();
            return app('json')->success($res['id']);
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }
    }

    /**
     * 添加专题楼层商品
     */
    public static function addEventsGoods($post)
    {

    }


    /**
     * 修改专题
     * @param $post
     * @return mixed
     */
    public static function editEvents($post)
    {
        $res = self::where('id', $post['id'])->find();
        Db::startTrans();
        try {
            $res->save($post);
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail();
        }
    }

    /**
     * 修改专题楼层商品
     */
    public static function editEventsGoods($post){

    }
    
}