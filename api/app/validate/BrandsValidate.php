<?php

namespace app\validate;

use ruhua\bases\BaseValidate;

class BrandsValidate extends BaseValidate
{

    protected $rule = [
        'brand_name' => 'require|isNotEmpty',
        'brand_pic' => 'min:0',  //图片ID
    ];

    protected $message = [
      'brand_name' => "品牌名称不能为空",
      'brand_pic' => "品牌LOGO不能为空"
    ];
}
