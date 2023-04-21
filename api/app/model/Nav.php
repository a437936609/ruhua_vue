<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/14 0014
 * Time: 15:22
 */

namespace app\model;


use ruhua\bases\BaseModel;
use app\model\Game as GameModel;
use ruhua\exceptions\BaseException;
use think\facade\Request;


class Nav extends BaseModel
{
    /**
     * 添加分类
     * @param $post
     * @return int
     */
    public static function addNav($post)
    {
        $post['sort'] = 0;
        $post['is_visible'] = 1;
        $res=self::where('nav_name',$post['nav_name'])->find();
        if($res){
            throw new BaseException(['msg'=>'重复添加']);
        }
        $res = self::create($post);
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 修改分组
     * @param $post
     * @return int
     */
    public static function editNav($post,$id)
    {
        $res = self::where(['id' => $id])->update($post);
        if($res){
            return app('json')->success();
        }
        return app('json')->fail();
    }

    /**
     * 删除分组
     * @param $id
     * @return int
     */
    public static function deleteNav($id)
    {
        $result = self::where(['id' => $id])->delete();
        if (!$result) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 排序
     * @param $arr
     * @return int
     */
    public static function setSort($arr){
        if(!is_array($arr)){
            return app('json')->fail();
        }
        foreach ($arr as $k=>$v){
            self::update(['sort'=>$v],['id'=>$k]);
        }
        return app('json')->success();
    }

    public function game()
    {
        return $this->belongsTo('Game','id','nav_id')->field('id,nav_id,game_type,content');
    }
    public function getImgIdAttr($value)
    {
        $x= substr( $value, 0, 1 );
        if($x!="h"){
            $url=Request::domain();
        }
        else
            $url="";
        return $url.$value;

    }

}