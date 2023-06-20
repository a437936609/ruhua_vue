<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 10:37
 */

namespace app\controller\cms;

use app\model\Brands as BrandsModel;
use app\validate\BrandsValidate;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseCommon;
use ruhua\bases\BaseController;
use ruhua\bases\BaseValidate;
use ruhua\services\QyFactory;

class BrandsManage extends BaseController
{
    /**
     * cms 新增分类
     * @param $form
     * @return int
     */
    public function addBrands($form)
    {
        $rule=[
            'brand_name|品牌名称'=> 'require|min:2',
            'brand_pic|品牌图片'=> 'require',
        ];
        $this->validate($form,$rule);
        return BrandsModel::addBrands($form);
    }

    /**
     * cms更新品牌
     * @param $form
     * @return int|\think\response\Json
     */
    public function editBrands($form)
    {
        return BrandsModel::editBrands($form);
    }

    /**
     * cms 删除品牌
     * @param $id
     * @return \think\Collection|void
     */
    public function deleteBrands($id)
    {
        (new IDPostiveInt())->goCheck();
        return BrandsModel::deleteBrands($id);
    }

    /**
     * cms 获取所有品牌并排好序，包括隐藏
     * @return \think\response\Json
     */
    public function getBrandsSort()
    {
        $article=(new QyFactory())->instance('CmsService');
        $data=$article->get_brands_list();
        return app('json')->success($data);
    }


    /**
     * 更新品牌排序
     * @return int
     */
    public function setSort()
    {
        $arr=input('post.');
        return BrandsModel::setSort($arr);
    }
}