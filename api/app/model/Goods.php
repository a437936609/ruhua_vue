<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 13:00
 */

namespace app\model;


use ruhua\bases\BaseModel;
use ruhua\exceptions\BaseException;
use ruhua\exceptions\OrderException;
use ruhua\exceptions\ProductException;
use think\Exception;
use think\facade\Db;
use think\facade\Log;
use think\facade\Request;
use think\model\concern\SoftDelete;
use app\model\Image as ImageModel;


class Goods extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden = ['is_stock_visible', 'is_pre_sale', 'shipping_fee', 'is_bill'];

    //关联图片
    public function imgs()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }

    //关联视频
    public function video()
    {
        return $this->belongsTo('Video', 'video_id', 'id');
    }

    //关联评价
    public function rate()
    {
        return $this->belongsTo('Rate', 'rate_id', 'id');
    }

    //关联规格
    public function sku()
    {
        return $this->hasMany('GoodsSku', 'goods_id', 'goods_id');
    }

    public function goodsSku()
    {
        return $this->hasOne('GoodsSku', 'goods_id', 'goods_id');
    }

    //物流模板
    public function delivery()
    {
        return $this->belongsTo('Delivery', 'delivery_id', 'id')->field('id,name,method');
    }

    //限时优惠
    public function discountGoods()
    {
        return $this->hasOne('DiscountGoods', 'goods_id', 'goods_id');
    }

    //拼团
    public function ptGoods()
    {
        return $this->hasOne('PtGoods', 'goods_id', 'goods_id');
    }

    //拼团
    public function fxGoods()
    {
        return $this->hasOne('FxGoods', 'goods_id', 'goods_id');
    }

    /**
     * 添加商品
     * @param $post
     * @return int
     * @throws BaseException
     */
    public static function addProduct($post)
    {
        Db::startTrans();// 启动事务
        try {
            $post['img_id'] = $post['banner_imgs'][0];
            $post['banner_imgs'] = implode(',', $post['banner_imgs']);

            //https://api.aku.pub 转换成 img.aku.pub
            $post['content'] = str_replace(config('site.api_domain'), config('site.img_domain'), $post['content']);

            if (input('?post.sku')) {

                //如果填写规格后单独的商品编号被清空
                $post['goods_code'] = '';

                $num =0;
                $sku= input('post.sku');
                foreach ($sku as $k => $v) {
                    $num = $num+$v['stock_num'];
                }
                if($num>0) {
                    $post['stock'] = $num;
                    $arr = input('post.sku');
                    $sku_img_ids = input('post.sku_img_ids');

                    $res = self::create($post);
                    (new GoodsSku())->addSku($res['id'], $arr, $sku_img_ids);//添加sku
                }else{
                    $res = self::create($post);
                }
            }else{
                $res = self::create($post);
            }
            Db::commit();
            if ($res) {
                return app('json')->success();
            }
            return app('json')->fail();
        } catch (Exception $e) {
            Db::rollback(); // 回滚事务
            throw new ProductException(['msg' => '商品添加失败']);
        }
    }

    /**
     * 修改商品
     * @param $post
     * @return int
     * @throws BaseException
     */
    public static function editProduct($post)
    {
        $arrs = input('post.sku');
        //Log::error('修改商品222');
        //Log::error($post);
        //Log::error('xxx');
        //Log::error($arrs);

        //https://api.aku.pub 转换成 img.aku.pub
        //$post['content'] = str_replace(config('site.api_domain'), config('site.img_domain'), $post['content']);

        Db::startTrans();// 启动事务
        try {
            $post['img_id'] = $post['banner_imgs'][0];
            $post['banner_imgs'] = implode(',', $post['banner_imgs']);
            //删除未填写价格的规格参数行
            //如果用request()->param 数据对sku的操作会有问题

            //https://api.aku.pub 转换成 img.aku.pub
            $post['content'] = str_replace(config('site.api_domain'), config('site.img_domain'), $post['content']);

            /*
             * 修改思路
             * 1.从$post中获取$arr的值，若为空，则证明商品没有规格。
             * 2.添加判断，尽量不修改原代码，只增加程序分支。
             * 3.商品规格为空有两种可能，1.以前为空，2.以前有规格修改为空
             **/
            if(!empty($arrs)){
                if (input('?post.sku')){

                    //如果填写规格后单独的商品编号被清空
                    $post['goods_code'] = '';

                    $arr = input('post.sku');
                    $num =0;
                    foreach ($arr as $k => $v) {
                        $num = $num+$v['stock_num'];
                    }

                    if($num>0) {
                        $sku_img_ids = input('post.sku_img_ids');

                        (new GoodsSku())->editSku($post['goods_id'], $arr, $sku_img_ids); //修改sku
                        $post['stock'] = $num;
                        $res = self::update($post, ['goods_id' => $post['goods_id']]);
                    }else{
                        $res = self::update($post, ['goods_id' => $post['goods_id']]);
                    }
                }
                else{
                    $res = self::update($post, ['goods_id' => $post['goods_id']]);
                }
            }else{
                $gsku =  (new GoodsSku())->where('goods_id',$post['goods_id'])->find();
                Log::error('$gsku');
                Log::error($gsku);
                if($gsku){
                    $gsku->delete();
                }
                $res = self::update($post, ['goods_id' => $post['goods_id']]);
            }
            Db::commit();
            if (!$res) {
                return app('json')->fail();
            }
            return app('json')->success();
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new ProductException(['msg' => '商品修改失败' . $e->getMessage()]);
        }
    }

    /**获取拼团商品图片
     * @param $res
     * @return mixed
     * @throws ProductException
     */
    public function get_img($res)
    {
        Db::startTrans();// 启动事务
        try {
            foreach ($res as $k => $v) {
                foreach ($v['ptGoods'] as $kk => $vv) {
                    $img_id=self::where('goods_id',$vv['goods_id'])->value('img_id');
                    $imgs=ImageModel::where('id',$img_id)->value('url');
                    $x= substr( $imgs, 0, 1 );
                    $url='';
                    if($x!="h"){
                        $url=Request::domain();
                    }
                    $fx = FxGoods::selectIsFx($res[$k]['ptGoods'][$kk]['goods_id']);
                    $res[$k]['ptGoods'][$kk]['fx']=$fx;
                    $res[$k]['ptGoods'][$kk]['imgs']=$url.$imgs;
                }
            }

            Db::commit();
            return $res;
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new ProductException(['msg' => '获取失败' . $e->getMessage()]);
        }
    }

    /**获取限时折扣商品图片
     * @param $res
     * @return mixed
     * @throws ProductException
     */
    public function get_ds_img($res)
    {
        Db::startTrans();// 启动事务
        try {
            foreach ($res as $k => $v) {
                foreach ($v['discount_goods'] as $kk => $vv) {
                    $img_id=self::where('goods_id',$vv['goods_id'])->value('img_id');
                    $imgs=ImageModel::where('id',$img_id)->value('url');
                    $x= substr( $imgs, 0, 1 );
                    $url='';
                    if($x!="h"){
                        $url=Request::domain();
                    }
                    $fx = FxGoods::selectIsFx($res[$k]['discount_goods'][$kk]['goods_id']);
                    $res[$k]['discount_goods'][$kk]['fx']=$fx;
                    $res[$k]['discount_goods'][$kk]['imgs']=$url.$imgs;
                }
            }
            Db::commit();
            return $res;
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new ProductException(['msg' => '获取失败' . $e->getMessage()]);
        }
    }

    /**获取限时专题产品图片
     * @param $res
     * @return mixed
     * @throws ProductException
     */
    public function get_es_img($res)
    {
        Db::startTrans();// 启动事务
        try {
            foreach ($res as $k => $v) {
                foreach ($v['events_goods'] as $kk => $vv) {

                    $img_id=self::where('goods_id',$vv['goods_id'])->value('img_id');
                    $imgs=ImageModel::where('id',$img_id)->value('url');
                    $x= substr( $imgs, 0, 1 );
                    $url='';
                    if($x!="h"){
                        $url=Request::domain();
                    }
                    $fx = FxGoods::selectIsFx($res[$k]['events_goods'][$kk]['goods_id']);
                    $res[$k]['events_goods'][$kk]['fx']=$fx;
                    $res[$k]['events_goods'][$kk]['imgs']=$url.$imgs;
                }
            }
            Db::commit();
            return $res;
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new ProductException(['msg' => '获取失败' . $e->getMessage()]);
        }
    }

    /**
     * 手机修改商品
     * @param $post
     * @return int
     * @throws BaseException
     */
    public static function mobileEditProduct($post)
    {
        $data['goods_name'] = $post['goods_name'];
        $data['stock'] = $post['stock'];
        $data['sales'] = $post['sales'];
        $data['market_price'] = $post['market_price'];
        $data['price'] = $post['price'];
        Db::startTrans();// 启动事务
        try {
            $res = self::save($data, ['goods_id' => $post['goods_id']]);
            /*$arr = input('post.sku');
            if($arr) {
                $sku_img_ids = input('post.sku_img_ids');
                (new GoodsSku())->editSku($post['goods_id'], $arr, $sku_img_ids); //修改sku
            }*/
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new ProductException(['msg' => '商品修改失败' . $e->getMessage()]);
        }
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 排序
     * @param $arr
     * @return int
     */
    public static function setSort($arr)
    {
        if (!is_array($arr)) {
            return app('json')->fail();
        }
        foreach ($arr as $k => $v) {
            self::update(['sort' => $v], ['goods_id' => $k]);
        }
        return app('json')->success();
    }

    /**
     * 获取未参加活动的商品
     * @return int
     */
    public static function getNormalGoods()
    {
        $arr = [];
        $discount = [];
        $pt = [];
        if (config('setting.is_business') == 1) {
            $discount = DiscountGoods::column('goods_id');
        }
        if (config('setting.is_business') == 1) {
            $pt = PtGoods::column('goods_id');
        }
        $res = Goods::with(['imgs','video'])->field('goods_id,img_id,price,goods_name')->select();
        foreach ($res->toArray() as $k => $v) {
            $fx = FxGoods::selectIsFx($res[$k]['goods_id']);
            $v['fx']=$fx;
            if (!in_array($v['goods_id'], $discount) && !in_array($v['goods_id'], $pt)) {
                array_push($arr, $v);
            }
        }
        return $arr;
    }

    /**商品图片转base64
     * @param $img_file
     * @return string
     */
    public static function imgToBase64($img_file) {

        $img_file=str_replace("\\",'/',$img_file);
        $img_base64 = '';
        if (file_exists($img_file)) {

            $app_img_file = $img_file; // 图片路径
            $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

            //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
            $fp = fopen($app_img_file, "r"); // 图片是否可读权限

            if ($fp) {
                $filesize = filesize($app_img_file);
                $content = fread($fp, $filesize);
                $file_content = chunk_split(base64_encode($content)); // base64编码
                switch ($img_info[2]) {           //判读图片类型
                    case 1: $img_type = "gif";
                        break;
                    case 2: $img_type = "jpg";
                        break;
                    case 3: $img_type = "png";
                        break;
                }
                $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码
            }
            fclose($fp);
        }
        return $img_base64; //返回图片的base64
    }

    /*
     * 获取商品部分信息
     * */
    public static function selectId($id){
        $res = self::where('goods_id',$id)->find();
        return $res;
    }
    /**
     * 获取某商品详情
     * @param $id
     * @return \think\response\Json
     */
    public static function   getProduct($id)
    {
        $post=input('get.');
        $page=-1;
        $num=0;
        if(isset($post['page'])&&isset($post['num'])){
            $page=$post['page'];
            $num=$post['num'];
        }
        if($page<0)
            $res = self::with(['imgs','video','sku', 'delivery'])->where('goods_id', $id)->find();
        else
            $res = self::with(['imgs','video','sku', 'delivery'])->where('goods_id', $id)->limit($page*$num,$num)->find();
        $url = [];
        $list = [];
        $flg=0;
        if (!empty($res['banner_imgs'])) {


            Db::startTrans();// 启动事务
            try {
                $ids=explode(',',$res['banner_imgs']);
                $imgModel=new ImageModel();
                foreach ($ids as $k=>$v){
                    $img=$imgModel->where('id',$v)->find();
                    $url[$k] = $img['url'];
                    if($flg==0){
                        $img['base_url']=self::imgToBase64(ROOT.$img['url']);
                        $flg=1;
                    }

                    $list[$k]=$img;
                }

                Db::commit();
            } catch (Exception $e) {
                // 回滚事务
                Db::rollback();
                throw new ProductException(['msg' => '获取失败' . $e->getMessage()]);
            }
        }
        //评论
        $res['rate'] = Rate::with('user')->where('goods_id', $id)->order('id desc')->limit(1)->select();
        $num = Rate::where('goods_id', $id)->count();
        $res['rate_fen'] = 0;
        $res['rate_num'] = $num;
        if ($num > 0) {
            $fen = Rate::where('goods_id', $id)->sum('rate');
            $res['rate_fen'] = round($fen * 20 / $num, 2);
        }
        //banner
        $res['banner_imgs'] = explode(',', $res['banner_imgs']);
        $res['banner_imgs_url'] = $url;
        $res['banner_imgs_list'] = $list;
        if (!empty($res['sku']) && count($res['sku']) > 0) {
            $sku_arr = $res['sku'][0]['json'];
            if ($sku_arr['tree']) {
                foreach ($sku_arr['tree'][0]['v'] as $k => $v) {
                    if (isset($v['img_id'])) {
                        $img_url = Image::where('id', $v['img_id'])->find();
                        $sku_arr['tree'][0]['v'][$k]['imgUrl'] = $img_url['url'];
                    }
                }
            }
            $res['sku_arr'] = $sku_arr;
        } else {
            $res['sku_arr'] = [];
        }
        $business=config('setting.is_business');
        //限时折扣
        if ($business == 1) {
            $res['discount'] = DiscountGoods::getDiscountGoods($id);
        }
        //拼团
        if ($business == 1) {
            $res['pt'] = PtGoods::getPtGoods($id);
        }
        //分销
        if ($business == 1) {
            $res['fx'] = FxGoods::selectIsFx($id);
        }
        return $res;
    }

    /**
     * 获取所有、最新、最热、推荐商品
     * @param $type
     * @return \think\Collection|void
     */
    public static function getRecentAll($type)
    {
        $post=input('get.');
        $page=0;
        $num=10;
        if(isset($post['page'])){
            $page=$post['page'];
        }
        $where['state'] = 1;
        if ($type == 'new') {
            $data = self::with(['imgs','video'])->where('is_new', 1)->where($where)->limit($page*$num,$num)->order('sort desc')->select();
        } else if ($type == 'hot') {
            $data = self::with(['imgs','video'])->where('is_hot', 1)->where($where)->limit($page*$num,$num)->order('sort desc')->select();
        } else if ($type == 'recommend') {
            $data = self::with(['imgs','video'])->where('is_recommend', 1)->where($where)->limit($page*$num,$num)->order('sort desc')->select();
        } else {
            $data = self::with(['imgs','video'])->where($where)->limit($page*$num,$num)->order('sort desc')->select();
        }
        if (!$data || count($data) < 1) {
            return;//throw new BaseException(['msg'=>'获取最新商品失败或无数据']);
        }
        if (config('setting.is_business') == 1) {
            foreach ($data as $k => $v) {
                $data[$k]['discount'] = DiscountGoods::getDiscountGoods($v['goods_id']);
            }
        }
        if (config('setting.is_business') == 1) {
            foreach ($data as $k => $v) {
                $data[$k]['pt'] = PtGoods::getPtGoods($v['goods_id']);
            }
        }
        return $data;
    }

    /**
     * 获取优惠券能使用商品
     * @param $id
     * @return \think\Collection|void
     */
    public static function getCouponProduct($id)
    {
        $coupon = Coupon::where('id', $id)->find();
        if ($coupon['goods_ids'] == 0) {
            $data = self::with('imgs')->where(['state' => 1])->select();
        } else {
            $ids = explode(',', $coupon['goods_ids']);
            $data = self::with('imgs')->where(['state' => 1, 'goods_id' => $ids])->select();
        }
        return $data;
    }

    /**
     * 获取某商家所有商品
     * @param $id
     * @return \think\Collection
     * @throws BaseException
     */
    public static function getShopID($id)
    {
        $data = self::with(['imgs','video'])->where('shop_id', $id)->where('state', 1)->select();
        if (!$data || count($data) < 1) {
            throw new BaseException(['msg' => '获取店铺商品失败或无数据']);
        }
        return $data;
    }


    /**
     * 获取所有上架商品，包含分页
     * @param int $page
     * @param int $size
     * @param string $key
     * @return \think\response\Json
     */
    public static function getProductByPage($key = '')
    {
        $post=input('get.');
        $page=-1;
        $num=0;
        if(isset($post['page'])&&isset($post['num'])){
            $page=$post['page'];
            $num=$post['num'];

        }
        if (!empty($key)) {
            $data = self::with(['imgs','video'])->where(['state' => 1])->where('goods_name', 'like', '%' . $key . '%')
                ->order('create_time desc')->select();
        } else {
            if($page<0)
                $data = self::with(['imgs','video'])->where(['state' => 1])->order('create_time desc')->select();
            else
                $data = self::with(['imgs','video'])->where(['state' => 1])->order('create_time desc')->limit($page*$num,$page)->select();
        }
        return app('json')->success($data);
    }

    /**
     * 获取所有下架商品，包含分页
     * @param int $page
     * @param int $size
     * @return \think\response\Json
     */
    public static function getProductDownByPage()
    {
        $data = self::with(['imgs','video'])->where('state', 0)->order('create_time desc')->select();
        return app('json')->success($data);
    }

    /**
     * ID获取某商品及关联详情
     * @param $id
     * @return array|null|\think\Model
     * @throws BaseException
     */
    public static function getProductByID($id)
    {
        $data = self::with(['sku'])->where('goods_id', $id)->find();
        if (!$data) {
            throw new BaseException(['商品不存在或数据错误']);
        }
        return $data;
    }


    /**
     * name获取某商品详情
     * @param $name
     * @return \think\Collection
     */
    public static function getProductByName($name)
    {
        $data = self::with(['imgs','video'])->where('state', 1)->where('goods_name', 'like', '%' . $name . '%')
            ->order('sales desc')->select();
        if (config('setting.is_business') == 1) {
            foreach ($data as $k => $v) {
                $data[$k]['discount'] = DiscountGoods::getDiscountGoods($v['goods_id']);
            }
        }
        if (config('setting.is_business') == 1) {
            foreach ($data as $k => $v) {
                $data[$k]['pt'] = PtGoods::getPtGoods($v['goods_id']);
            }
        }
        return $data;
    }

    /**
     * 检查库存少于10的商品
     * @return int
     */
    public static function getGoodsStock()
    {
        $goods = self::with('goodsSku')->where('state', 1)->select();
        $goods_stock = 0;
        foreach ($goods as $k => $v) {
            if ($v['goods_sku']) {
                foreach ($v['goods_sku']['json']['list'] as $key => $value) {
                    if ($value['stock_num'] < 10) {
                        $goods_stock += 1;
                        break;
                    }
                }
            } else {
                if ($v['stock'] < 10) {
                    $goods_stock += 1;
                }
            }
        }
        return $goods_stock;
    }

    /**
     * 检测库存
     * @param $data
     * @return int
     * @throws OrderException
     */
    public static function checkStock($data)
    {
        foreach ($data as $k => $v) {
            $goods = self::with('goodsSku')->where('goods_id', $v['goods_id'])->find();
            if ($v['sku_id']) {
                foreach ($goods['goods_sku']['json']['list'] as $key => $value) {
                    if ($v['sku_id'] == $value['id']) {
                        if ($value['stock_num'] < $v['num']) {
                            throw new OrderException(['msg' => '库存不足']);
                        }
                    }
                }
            } else if ($v['num'] > $goods['stock']) {
                throw new OrderException(['msg' => '库存不足']);
            }
        }
        return 1;
    }

    /**
     * 修改库存
     * @param $data
     * @return int
     * @throws OrderException
     */
    public static function editStock($data)
    {
        foreach ($data as $k => $v) {
            $goods = self::with('goodsSku')->where('goods_id', $v['goods_id'])->find();
            if ($v['sku_id']) {
                foreach ($goods['goods_sku']['json']['list'] as $key => $value) {
                    if ($v['sku_id'] == $value['id']) {
                        $goods['goods_sku']['json']['list'][$key]['stock_num'] = $value['stock_num'] - $v['num'];
                        if ($goods['goods_sku']['json']['list'][$key]['stock_num'] >= 0) {
                            $goods->save();
                        }
                    }
                }
            } else {
                $goods['stock'] = $goods['stock'] - $v['num'];
                // $goods['sales']= $goods['sales']+$v['num'];
                if ($goods['stock'] >= 0) {
                    $goods->save();
                }
            }
        }
        return 1;
    }
    /**
     * cms 删除商品关联删除该商品存在的所有活动
     * @param $id
     */
    public static function deleteGoods($id = '')
    {
        if (app('system')->getValue('fx_status')) {
            FxGoods::where('goods_id', $id)->delete();
        }
        if (config('setting.is_business')) {
            DiscountGoods::where('goods_id', $id)->delete();
        }
        if (config('setting.is_business')) {
            PtGoods::where('goods_id', $id)->delete();
        }
    }
    //事件调用函数  减库存
    public static function ReduceStock($order_id)
    {
        $ordergoods=OrderGoods::where('order_id',$order_id)->select();

        log::error('库存减少操作函数');
        Db::startTrans();

        try {

            for($i=0;$i<count($ordergoods);$i++){
                if($ordergoods[$i]['sku_id']!=0){
                    $sku = GoodsSku::getSkuGoodsId($ordergoods[$i]['goods_id']);

                    $sname=explode(" ",$ordergoods[$i]['sku_name']);
                    /*
                     * 从Goods_sku 中获取库存，因$ordergoods[$i]['sku_name']里的数据是“xxx xxx xxx xxx”这样的，从表中取出的json数据又是这样的's1_name'、's2_name'、's3_name'
                     * 所以进行如下对比
                     * */
                    foreach ($sku['json']['list'] as $k=>$v) {
                        $short1 = 1;
                        $shrot2 = 1;
                        foreach ($sname as $kk=>$vv){
                            if($vv!=''){
                                if(isset($v['s'.$short1.'_name'])){
                                    if($v['s'.$short1.'_name']!=$vv){
                                        $shrot2 = 2;
                                        break;
                                    }
                                }
                                else{
                                    $shrot2 = 2;
                                    break;
                                }
                                $short1++;
                            }}
                        if ($shrot2==1) {
                            if($v['stock_num'] - $ordergoods[$i]['num']<0)
                                throw new OrderException(['msg' => '库存不足']);
                            $v['stock_num'] = $v['stock_num'] - $ordergoods[$i]['num'];
                        }
                        $arrlist[$k] = $v;
                    }

                    $ty['tree'] = $sku['json']['tree'];
                    $ty['list'] = $arrlist;
                    $json = json_encode($ty, JSON_UNESCAPED_UNICODE);
                    GoodsSku::where('goods_id',$ordergoods[$i]['goods_id'])->update(['json'=>$json]);
                }
                $num=self::where('goods_id',$ordergoods[$i]['goods_id'])->value('stock');
                if($num-$ordergoods[$i]['num']<0)
                    throw new OrderException(['msg' => '库存不足']);
                $res=self::where('goods_id',$ordergoods[$i]['goods_id'])->update(['stock'=>($num-$ordergoods[$i]['num']),'sales'=>($num+$ordergoods[$i]['num'])]);
            }
            Db::commit();
            return true;
        }catch (\Exception $e) {
            Db::rollback();
            throw new BaseException(['msg'=>$e->getMessage()]);
        }
    }

    public static function ReduceStockAdd($order_id)
    {
        $ordergoods=OrderGoods::where('order_id',$order_id)->select();
        log::error('库存回收操作函数');
        Db::startTrans();
        try {
            for($i=0;$i<count($ordergoods);$i++){
                //规格商品减库存
                if($ordergoods[$i]['sku_id']!=0){
                    $sku = GoodsSku::getSkuGoodsId($ordergoods[$i]['goods_id']);
                    /*
                     * 从Goods_sku 中获取库存，因$ordergoods[$i]['sku_name']里的数据是“xxx xxx xxx xxx”这样的，从表中取出的json数据又是这样的's1_name'、's2_name'、's3_name'
                     * 所以进行如下对比
                     * */
                    $sname=explode(" ",$ordergoods[$i]['sku_name']);
                    foreach ($sku['json']['list'] as $k=>$v) {
                        $short1 = 1;
                        $shrot2 = 1;
                        foreach ($sname as $kk=>$vv){
                            if($vv!=''){
                                if(isset($v['s'.$short1.'_name'])){
                                    if($v['s'.$short1.'_name']!=$vv){
                                        $shrot2 = 2;
                                        break;
                                    }
                                }
                                else{
                                    $shrot2 = 2;
                                    break;
                                }
                                $short1++;
                            }}
                        if ($shrot2==1) {
                            $v['stock_num'] = $v['stock_num'] + $ordergoods[$i]['num'];
                        }
                        $arrlist[$k] = $v;
                    }
                    $ty['tree'] = $sku['json']['tree'];
                    $ty['list'] = $arrlist;
                    $json = json_encode($ty, JSON_UNESCAPED_UNICODE);
                    GoodsSku::where('goods_id',$ordergoods[$i]['goods_id'])->update(['json'=>$json]);
                }
                $num=self::where('goods_id',$ordergoods[$i]['goods_id'])->value('stock');
                $res=self::where('goods_id',$ordergoods[$i]['goods_id'])->update(['stock'=>($num+$ordergoods[$i]['num']),'sales'=>($num+$ordergoods[$i]['num'])]);
            }
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }
    public function setImgsAttr($v)
    {
        return $v['url'];
    }

    public function getIsHotAttr($v)
    {
        return $v == 1 ? true : false;
    }

    public function getIsRecommendAttr($v)
    {
        return $v == 1 ? true : false;
    }

    public function getIsNewAttr($v)
    {
        return $v == 1 ? true : false;
    }

    public function getStateAttr($v)
    {
        return $v == 1 ? true : false;
    }

}