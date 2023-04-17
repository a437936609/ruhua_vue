<?php
// 事件定义文件
return [
    'bind'      => [
        'Ctest1'=>'ruhua\events\listens\Ctest1',
    ],

    'listen' => [
        'AppInit' => ['app\\behavior\\CORS'],
        'HttpRun' => [],
        'HttpEnd' => [],
        'LogLevel' => [],
        'LogWrite' => [],
        //创建订单
        'CreateOrder' => [
            'ruhua\events\listens\CheckStyle',  //检测是否有虚拟商品
            'ruhua\events\listens\CheckStock',  //库存是否满足
            'ruhua\events\listens\CheckCoupon',   //优惠券是否符合满减
            // '',  //更新优惠券为使用中
        ],
        //商品订单支付成功
        'PayOrder' => [
            'ruhua\events\listens\ReduceStock',  //扣除库存
            '',  //减库存
            '',  //模板消息通知用户
            '',  //模板消息通知管理员
        ],
        //发货或核销
        'DeliverProduct' => [
            '',  //模板消息通知用户
        ],
        //订单完成
        'EndOrder' => [
            'ruhua\events\listens\editCoupon',  //优惠券：使用中变已使用
            'ruhua\events\listens\editSales',  //商品销量+1
            'ruhua\events\listens\addPoints',  //增加积分

//            '',  //分销商抽佣
//            '',  //企业微信付款到零钱
        ],
        //订单评价
        'AppraisesOrder' => [
            '',  //更新商品评分
        ],
        //退款
        'BackOrder' => [
            'ruhua\events\listens\ReduceStockAdd',  //库存+
            'ruhua\events\listens\editCouponNo',  //更新优惠券为待使用
            '',  //模板消息通知用户
        ],
        //关闭订单
        'ShutOrder' => [
            'ruhua\events\listens\ReduceStockAdd',  //库存+
            'ruhua\events\listens\editCouponNo',  //更新优惠券为待使用
        ],
        //'UserLogin' => ['app\listener\OrderListen', ''],
//        'CheckFx'=>[]
    ],

    'subscribe' => [
        ruhua\subscribes\FxSubscribes::class,
        ruhua\subscribes\OrderSubscribes::class,
    ],
];
