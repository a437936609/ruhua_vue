<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/9/28 0028
 * Time: 16:37
 */

namespace app\model;


use ruhua\bases\BaseModel;

class GameRecord extends BaseModel
{
    public function user()
    {
        return $this->belongsTo('User','uid','id')->field('id,nickname,headpic');
    }

    public function game()
    {
        return $this->belongsTo('Game','gid','id');
    }

}