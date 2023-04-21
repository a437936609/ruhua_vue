<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/29 0029
 * Time: 15:30
 */

namespace app\controller\cms;

use app\model\PointsRecord as PointsRecordModel;
use app\model\PointsConfig as PointsConfigModel;
use ruhua\bases\BaseController;

class PointsManage extends BaseController
{
    /**
     * 查看积分详情
     * @return mixed
     */
    public function getPointsRecord(){
        $post=input('get.');
        $page=-1;
        $num=10;
        if(isset($post['page'])){
            $page=$post['page'];
            $res=PointsRecordModel::with('user')->order('id','desc')->limit($page*$num,$num)->select();
        }
        else{
             $res=PointsRecordModel::with('user')->order('id','desc')->select();
        }
        return app('json')->success($res);
    }
}