<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/12 0012
 * Time: 13:11
 */

namespace app\controller\cms;


use app\model\Image;
use app\model\ImageCategory as ImageCategoryModel;
use app\services\CommonServices;
use app\validate\IDPostiveInt;
use ruhua\bases\BaseController;
use app\model\Image as ImageModel;
use ruhua\exceptions\BaseException;
use think\facade\Log;

class ImageManage extends BaseController
{
    /**
     * 添加分类
     * @return mixed
     */
    public function addImageCategory()
    {
        $rule=['category_name'=>'require'];
        $post=input('post.');
        $this->validate($post,$rule);
        return ImageCategoryModel::addCategory($post);
    }

    /**
     * 删除分类
     * @param $id
     * @return mixed
     */
    public function deleteImageCategory($id='')
    {
        (new IDPostiveInt)->goCheck();
        $img=ImageModel::where('category_id',$id)->find();
        if($img){
            throw new BaseException(['msg'=>'该分类下有图片，无法删除']);
        }
        $res=ImageCategoryModel::destroy($id);
        return app('json')->go($res);

        //return ImageCategoryModel::deleteCategory($id);
    }

    /**
     * 获取分类
     * @return mixed
     */
    public function getImageCategory()
    {
        $res=ImageCategoryModel::select();
        return app('json')->success($res);
    }

    /**
     * 获取所有图片
     * @return mixed
     */
    public function getAllImage(){
        $res=Image::where('is_visible',1)->order('id','desc')->select();
        return app('json')->success($res);
    }

    /**
     * 删除图片
     * @param $ids
     * @return mixed
     */
    public function editImage($ids){
        $res=Image::where(['id'=>$ids])->update(['is_visible'=>0]);
        if($res){
            return app('json')->success();
        }else{
            return app('json')->fail();
        }
    }

}