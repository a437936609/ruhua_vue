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


}