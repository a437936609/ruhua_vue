<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22 0022
 * Time: 10:00
 */

namespace app\controller\common;

use app\model\Brands as BrandsModel;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use ruhua\services\QyFactory;

class Brands extends BaseController
{

    /**
     * 获取所有品牌信息
     * @return mixed
     */
    public function getAllBrands()
    {
        $article=(new QyFactory())->instance('UserService');
        $data=$article->get_brands_list();
        return app('json')->success($data);
    }


}