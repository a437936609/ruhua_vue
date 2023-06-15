<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 10:37
 */

namespace app\controller\cms;

use app\model\Brands as BrandsModel;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseCommon;
use ruhua\bases\BaseController;
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
            'brand_name'=> 'require|min:2',
        ];
        $this->validate($form,$rule);
        return BrandsModel::addBrands($form);
    }

}