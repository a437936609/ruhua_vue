<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/22 0022
 * Time: 16:39
 */

namespace app\controller\cms;


use ruhua\bases\BaseController;
use app\model\PlayAward as PlayAwardModel;
use think\facade\Log;

class PlayAward extends BaseController
{
    public function get()
    {
        $res=PlayAwardModel::where('type_id',1)->field('id,award,name')->select();
        return app('json')->go($res);
    }

    public function update()
    {
        $data=input('post.');
        Log::error($data);
        $res=PlayAwardModel::update($data,['id'=>$data['id']]);
        return app('json')->go($res);
    }
}