<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 10:44
 */

namespace app\model;


use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;

class Brands extends BaseModel
{
    protected $updateTime = false;

    /**
     * 关联图片
     * @return \think\model\relation\BelongsTo
     */
    public function imgs()
    {
        return $this->belongsTo('Image', 'brand_pic', 'id');
    }

    /**
     * 关联商品
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo('Goods', 'brand_id', 'brand_id');
    }

    /**
     * 添加品牌
     * @param $form
     * @return int
     */
    public static function addBrands($form)
    {
        $form['sort'] = 0;
        $res = self::create($form);
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 修改品牌
     * @param $form
     * @return int
     */
    public static function editBrands($form)
    {
        //$form_data['brand_id'] = $form['brand_id'];
        $form_data['brand_name'] = $form['brand_name'];
        $form_data['short_name'] = $form['short_name'];
        $form_data['brand_pic'] = $form['brand_pic'];
        $res = self::where(['brand_id' => $form['brand_id']])->update($form_data);
        if ($res) {
            $data = self::where(['brand_id' => $form['brand_id']])->find()->toArray();
            $img = Image::where('id',$data['brand_pic'])->find();
            if($img)
            {
                $data['imgs'] = $img['url'];
            }

            $data['is_visible'] = $data['is_visible'] == 1 ? true : false;
            return app('json')->success($data);
        }
        return app('json')->fail();
    }

    /**
     * 删除品牌
     * @param $id
     * @return int
     */
    public static function deleteBrands($id)
    {
        $pid_goods = Goods::where('brand_id', $id)->where('state',1)->count();
        if ($pid_goods > 0) {
            return app('json')->fail('无法删除，该品牌下有商品');
        }
        $result = self::where(['brand_id' => $id])->delete();
        if (!$result) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 获取所有品牌信息
     * @param bool $vs
     * @return \think\Collection
     */
    public static function getBrandsAll($vs=false)
    {
        if($vs){
            $data=self::with('imgs')->order('sort asc')->select();
        }else{
            $data=self::with('imgs')->where('is_visible',1)->order('sort asc')->select();
        }
        if(!$data || count($data)<1){
            app('json')->fail('请至少添加一个品牌');
        }
        return app('json')->success('操作成功',$data);
//        return $data;
    }

    /**
     * 获取分类下所有品牌与广告图
     * @param $id
     * @return \think\response\Json
     */
    public static function getBrandsImg($id)
    {
        $data['brands'] = self::with('imgs')->where('pid',$id)->select();
        $banner = Banner::with(['items','items.imgs'])->find($id+1);
        $data['banner'] = $banner['items'];
        return app('json')->success($data);
//        return  json($data);
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
            self::update(['sort'=>$v],['brand_id'=>$k]);
        }
        return app('json')->success();
    }

    public function setImgsAttr($v)
    {
        return $v['url'];
    }

    public function getIsVisibleAttr($value)
    {
        $status = ['1'=>true,'0'=>false];
        return $status[$value];
    }
}