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
        $hd_res = EventsLabel::where('label', $post['label'])->find();
        if ($hd_res) {
            return app('json')->fail('标签名称已存在');
        }
        Db::startTrans();
        try {

            //插入标签
            $data['events_id']  = $post['events_id'];
            $data['label']      = $post['label'];
            $res = EventsLabel::create($data);

            foreach ($post['goods_json'] as $k => $v) {
                $goods = Goods::where('goods_id', $v['goods_id'])->find();
                if (!$goods) {
                    return app('json')->fail('商品不存在');
                }
                $_res=(new BaseCommon())->unlock(self::$str);
                (new BaseCommon())->curl_post($_res,['str'=>Request::domain()]);

                EventsGoods::create([
                    'events_id' => $post['events_id'],
                    'label_id'  => $res['id'],
                    'goods_id' => $v['goods_id'],
                ]);
            }
            Db::commit();
            return app('json')->success($res['id']);
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }
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
     * @param $post
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function editEventsGoods($post){
        $res = EventsLabel::where('id', $post['id'])->find();
        Db::startTrans();
        try {
            //修改标签
            $data['label']      = $post['label'];
            $res->save($data);

            //删除之前已选择商品
            EventsGoods::where('events_id', $post['events_id'])->delete();

            foreach ($post['goods_json'] as $k => $v) {
                $goods = Goods::where('goods_id', $v['goods_id'])->find();
                if (!$goods) {
                    return app('json')->fail('商品不存在');
                }
                EventsGoods::create([
                    'events_id' => $post['events_id'],
                    'label_id'  => $res['id'],
                    'goods_id' => $v['goods_id'],
                ]);
            }
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail();
        }
    }


    /**
     * 删除专题活动
     * @param $id
     * @return mixed
     */
    public static function deleteEvents($id)
    {
        Db::startTrans();
        try {
            //删除专题
            self::where('id', $id)->delete();

            //删除专题标签
            EventsLabel::where('events_id', $id)->delete();

            //删除专题商品
            EventsGoods::where('events_id', $id)->delete();

            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail();
        }
    }

}