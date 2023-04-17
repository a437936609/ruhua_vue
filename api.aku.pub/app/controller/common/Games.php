<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/23 0023
 * Time: 8:48
 */

namespace app\controller\common;


use app\model\GameRecord;
use app\services\TokenService;
use ruhua\bases\BaseController;
use jgg\GameJgg;
use dzp\GameDzp;
use Game\Game;
use app\model\Game as GameModel;
use app\model\GameRecord as GameRecordModel;

class Games extends BaseController
{
    /**添加九宫格
     * @return mixed
     */
    public function add_jgg()
    {

        $data=input('post.');
        $rule=[
            'type'=>'require',
            'state'=>'require',
            'reword'=>'require'
        ];
        $this->validate($data,$rule);
        if($data['type']==0){
            $rule1=[
                'integral'=>'require'
            ];
            $data['tice']=0;
        }
        elseif ($data['type']==1){
            $rule1=[
                'tice'=>'require'
            ];
            $data['integral']=0;
        }else{
            $rule1=[
                'integral'=>'require',
                'tice'=>'require'
            ];
        }
        $this->validate($data,$rule1);

        return GameJgg::add_game($data);

    }

    /**更新九宫格
     * @return mixed
     */
    public function update_jgg()
    {
        $data=input('post.');
        return GameJgg::update_game($data);
    }

    public function get_jgg()
    {
        return GameJgg::get_game();
    }

    public function del_jgg($id)
    {
        return GameJgg::del_game($id);
    }

    public function add_jgg_rule()
    {
        $data=input('post.');
        $rule=[
            'gid'=>'require',
            'value'=>'require',
            'stock'=>'require',
        ];
        $this->validate($data,$rule);
        return GameJgg::add_game_rule($data);
    }

    public function del_game_rule($id)
    {
        return GameJgg::del_game_rule($id);
    }

    public function update_jgg_rule()
    {
        $data=input('post.');
        return GameJgg::update_game_rule($data);
    }

    public function get_jgg_rule($id)
    {
        return GameJgg::get_game_rule($id);
    }



    /**添加大转盘
     * @return mixed
     */
    public function add_dzp()
    {

        $data=input('post.');
        $rule=[
            'type'=>'require',
            'state'=>'require',
            'reword'=>'require'
        ];
        $this->validate($data,$rule);
        if($data['type']==0){
            $rule1=[
                'integral'=>'require'
            ];
            $data['tice']=0;
        }
        elseif ($data['type']==1){
            $rule1=[
                'tice'=>'require'
            ];
            $data['integral']=0;
        }else{
            $rule1=[
                'integral'=>'require',
                'tice'=>'require'
            ];
        }
        $this->validate($data,$rule1);

        return GameDzp::add_game($data);

    }

    /**更新大转盘
     * @return mixed
     */
    public function update_dzp()
    {
        $data=input('post.');
        return GameDzp::update_game($data);
    }

    public function get_dzp()
    {
        return GameDzp::get_game();
    }

    public function del_dzp($id)
    {
        return GameDzp::del_game($id);
    }

    public function add_dzp_rule()
    {
        $data=input('post.');
        $rule=[
            'gid'=>'require',
            'value'=>'require',
            'stock'=>'require',
        ];
        $this->validate($data,$rule);
        return GameDzp::add_game_rule($data);
    }

    public function del_dzp_rule($id)
    {
        return GameDzp::del_game_rule($id);
    }

    public function update_dzp_rule()
    {
        $data=input('post.');
        return GameDzp::update_game_rule($data);
    }

    public function get_dzp_rule($id)
    {
        return GameDzp::get_game_rule($id);
    }

    public function upadate_state($id,$state)
    {
        return Game::update_state($id,$state);
    }


    public function start($id)
    {
        $res=GameModel::start($id);
        return app('json')->go($res);
    }

    public function get_record()
    {
        $res=GameRecordModel::with(['user'])->order('state asc')->select();
        return app('json')->go($res);
    }

    public function update_state($id)
    {
        $res=GameRecordModel::update(['state'=>1],['id'=>$id]);
        return app('json')->go($res);
    }

    public function get_user_record($gid)
    {
        $uid=TokenService::getCurrentUid();
        $res=GameRecordModel::where(['uid'=>$uid,'gid'=>$gid])->select();
        return app('json')->go($res);
    }

    public function get_record_ten($gid)
    {
        $res=GameRecordModel::with(['user'])->where('gid',$gid)->limit(10)->order('create_time desc')->select();
        return app('json')->go($res);

    }




}