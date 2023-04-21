<?php


namespace app\controller\user;


use app\BaseController;
use app\model\BankCard;
use think\facade\Log;

/*
 * 银行卡相关
 * */
class UserBank extends BaseController
{
    public function addCard(){
        $data = input('post.');
        $rule=[
            'bk_uname'=> 'chsAlpha',
            'card'=> 'number|length:15,20',
            'card_type'=> 'number|length:1',
        ];
        $this->validate($data,$rule);
        return app('json')->go(BankCard::addBankCard($data));
    }

    public function selectCard(){
        return app('json')->go(BankCard::selectBankCard());
    }

    public function updateCard(){
        $data = input('post.');
        return app('json')->go(BankCard::updateBankCard($data));
    }

    public function delCard($id){
        return app('json')->go(BankCard::delBankCard($id));
    }
}