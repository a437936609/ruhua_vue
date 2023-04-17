<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/20 0020
 * Time: 8:59
 */

namespace app\services;


use think\facade\Cache;
use think\facade\Log;

class SearchService
{
    /**
     * 设置搜索缓存
     * @param $name
     * @param $num
     */
    public static function setSearchCache($name,$num=1){
        $value[$name]=$num*1;
        $search = Cache::pull('search');
        if(!$search){
            cache('search', $value);
        }else{
            if (!is_array($search)) {
                $search = json_decode($search, true);
            }
            if (array_key_exists($name, $search)) {
                $search[$name]=$search[$name]+$num;
            } else {
                if(count($search)==20){
                    array_pop($search);
                }
                $search[$name]=$num*1;
            }
           //下面函数有一个bug 热搜名为数字时，键名会变为0，1，2，3，类型
            array_multisort($search,SORT_DESC );

            cache('search', $search);
        }
    }

    /**
     * 获取搜索记录前十 user使用
     * @return array|string
     */
    public static function getSearchCache()
    {
        $arr=[];
        $search = Cache::get('search');
        if(!$search){
            return '';
        }else{
            if (!is_array($search)) {
                $search = json_decode($search, true);
            }
           //$search= array_slice($search,0,10);
            $i =0;
            foreach ($search as $k=>$v){

                array_push($arr,$k);
            }
            $arr=array_slice($arr,0,10);
            return $arr;
        }
    }
    /**
     * 获取搜索记录前十  cms后台使用
     * @return array|string
     */
    public static function getSearchCacheCms()
    {
        $data = [];
        $search = Cache::get('search');
        Log::error('*****//////*******');
        Log::error($search);
        if(!$search){
            return '';
        }else{
            if (!is_array($search)) {
                $search = json_decode($search, true);
            }
            //$search= array_slice($search,0,10);
            $i =0;
            foreach ($search as $k=>$v){
                $data[$i]['name']=$k;
                $data[$i]['num']=$v;
                $data[$i]['odd']= $i+1;
                $i++;
            }
            $arr=array_slice($data,0,10);
            return $arr;
        }
    }

    /**
     * 删除搜索记录
     * @param $name
     * @return mixed
     */
    public static function deleteSearchCache($name)
    {
        $search = Cache::pull('search');
        if(!$search){
            return app('json')->fail();
        }else{
            if (!is_array($search)) {
                $search = json_decode($search, true);
            }
            $search= array_diff_key($search,[$name=>1]);
            cache('search', $search);
            return app('json')->success();
        }
    }

    /**
     * 更新搜索记录
     * @param $from
     * @return mixed
     */
    public static function editSearchCache($from)
    {

    }
}