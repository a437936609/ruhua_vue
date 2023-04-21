<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/10 0010
 * Time: 14:38
 */

namespace app\controller\cms;


use app\model\FxRecord;
use ruhua\bases\BaseController;
use app\model\FxManual as FxManualModel;
use app\model\FxRecord as FxRecordModel;
use ruhua\exceptions\OrderException;
class FxManual extends BaseController
{
    public function getall()
    {
        $list=FxManualModel::getFxAll();
        return app('json')->go($list);

    }

    public function getallStatus($status='')
    {
        $list=FxManualModel::where('status',$status)->select();
        return app('json')->go($list);

    }

    public function agreeFxApply($id,$card){
        $list=FxManualModel::where('id',$id)->find();
        if($list['status']>0){
            throw new OrderException(['msg'=>'该记录已被处理']);
        }
        $rid=explode(",",$list['rid']);
        foreach($rid as $k=>$v){
            FxRecordModel::agreeTxApply($v,$card);
        }
        $res=FxManualModel::update(['status'=>1,'card'=>$card],['id'=>$id]);
        return app('json')->go($res);
    }

    public function refuseFxApply($id){
        $list=FxManualModel::where('id',$id)->find();
        if($list['status']>0){
            throw new OrderException(['msg'=>'该记录已被处理']);
        }
        $rid=explode(",",$list['rid']);
        foreach($rid as $k=>$v){
            FxRecordModel::refuseTxApply($v);
        }
        $res=FxManualModel::update(['status'=>2],['id'=>$id]);
        return app('json')->go($res);
    }




}