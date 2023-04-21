# 如花商城更新方式：
# 1、覆盖API端，除public、config目录
# 2、自行sql命令更新数据库




*************************************************************************************************************

# V1.26
新增：导出商品订单信息，可以选择导出的字段。
修复：商品规格的bug。
*************************************************************************************************************

# V1.248
新增：设置-邀请注册时 没有邀请到的不能看商品；管理员界面在线更新。
修复：部分显示bug
*************************************************************************************************************

# V1.247
新增：优惠券发放至个人，优惠券可与拼团、折扣一起使用。
修复：部分显示bug
数据库：ALTER TABLE `rh_coupon` 
ADD COLUMN `is_appoint` int(11) NOT NULL DEFAULT 0 COMMENT '优惠券指定个人发放：1指定，0不指定' ;
	
*************************************************************************************************************

# V1.246
新增：
修复：库存修改添加bug、评价头像问题、昵称、图片、评价昵称有表情无法插入问题、分销二维码问题、商品只有一级类时、点击分类没效果、创建订单-提交订单界面应该显示商品价格、会员价相差为0时不显示，会员开关关闭也不显示、拼团价与vip重复计算、games/get_dzp（规则说明）大转盘

*************************************************************************************************************

# V1.245
新增：
修复：导航nav路径问题、优惠券路径问题；规格商品-非规格 创建订单减库存，取消订单、自动取消订单，返回库存；cms添加规格商品时总库存错误。以及一些显示bug

*************************************************************************************************************

# V1.244
新增：
修复：拼团显示错误--应用；小程序 ；图片路径问题；提示分销价格问题；分销确认收货异常；部分其他bug
数据表：


*************************************************************************************************************

# V1.243
新增：
修复：分销佣金手动提现、本地与OSS保存视频问题、修复地址不存在问题、修复手机验证码登录问题
数据表：
fx_manual表：
ALTER TABLE `ruhua`.`rh_fx_manual` 
ADD COLUMN `msg` text NULL COMMENT '备注' AFTER `bk_uname`;
bank_card表：
DROP TABLE IF EXISTS `rh_bank_card`;
CREATE TABLE `rh_bank_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `bk_uname` varchar(10) DEFAULT NULL COMMENT '户主名',
  `bk_name` varchar(30) DEFAULT NULL COMMENT '银行名称',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '类型0用户卡1商家卡',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `card` varchar(30) DEFAULT NULL COMMENT '卡号',
  `card_type` int(11) NOT NULL DEFAULT '0' COMMENT '0其他1人民银行2农业银行3工商银行3建设银行',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='银行卡表';
msg_log表：
CREATE TABLE `rh_msg_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `create_time` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '手机验证码' ROW_FORMAT = Dynamic;

sys_config表：插入两个数据
INSERT INTO `rh_sys_config` (`id`, `key`, `value`, `desc`, `type`, `switch`, `other`, `update_time`, `create_time`) VALUES (NULL, 'login_mode', '0', '注册模式', '1', '2', '1开放2审核3邀请', '1606713771', '1606713771');
INSERT INTO `rh_sys_config` (`id`, `key`, `value`, `desc`, `type`, `switch`, `other`, `update_time`, `create_time`) VALUES (NULL, 'product_classification', '0', '商品分页页面', '1', '2', '0-分类页-产品 1-二级类', '1606713771', '1606713771');
rh_user表：新增两个个字段
ALTER TABLE `rh_user` ADD `invite_mobile` VARCHAR(255) NOT NULL COMMENT '邀请码' AFTER `tree_experience`;
ALTER TABLE `rh_user` ADD `state` INT(11) NOT NULL COMMENT '状态' AFTER `invite_mobile`;

*************************************************************************************************************

# V1.242
新增：统计中的（分销商统计、分销商、佣金记录）移动到应用里的分销中、分类页-产品或二级类、从选规格的地方可以加入购物车
修复：cms中：分销商，佣金记录，提现、种树应该有默认值、关闭热搜后很丑、H5的大转盘，九宫格；检测一下前端和种树、分页页面放两插件；一种是一二级分类、一种是一级分类与商品（未做组件）
数据表：






*************************************************************************************************************

# V1.241
新增：九宫格、大转盘、种树
修复：签到功能、商城导航页面滑动、商品分类页面价格升降序，销量排行

数据表：
sys_config表：
INSERT INTO `rh_sys_config` VALUES(73,'is_qd','0','签到',12,1,NULL,1603869812,1596008818),(74,'tree','0','种树开关',13,1,NULL,1603950812,1603516012),(75,'tree_experience','0','浇水固定经验',13,0,NULL,1603869760,1603516012),(77,'tree_middle','0','成为中树所需经验',13,0,NULL,1603516012,1603516012),(78,'tree_big','0','成为大树所需经验',13,0,NULL,1603516012,1603516012),(79,'tree_small_point','0','小数每天获取积分',13,0,NULL,1603516012,1603516012),(80,'tree_middle_point','0','中树每天获取积分',13,0,'',1603516012,1603516012),(81,'tree_big_point','0','大树每天获取积分',13,0,'',1603869413,1603516012);

game_record表
CREATE TABLE `rh_game_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `gid` int(11) NOT NULL DEFAULT '0',
  `content` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '记录说明',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '状态0代发货1已发货',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='抽奖记录'


game表：
CREATE TABLE `rh_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nav_id` int(11) NOT NULL DEFAULT '0' COMMENT '导航id',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0积分抽奖1每日抽奖2积分+每日抽奖',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '积分',
  `tice` int(11) NOT NULL DEFAULT '0' COMMENT '每日次数',
  `state` int(11) NOT NULL DEFAULT '0' COMMENT '状态0关闭活动1开启活动',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `game_type` int(11) NOT NULL DEFAULT '0' COMMENT '0九宫格1大转盘',
  `reword` int(11) NOT NULL DEFAULT '0' COMMENT '0奖品不限1奖品有限',
  `content` text COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='抽奖游戏表'

INSERT INTO `rh_game` VALUES (1,1,0,0,0,1,1603950295,1603950759,0,0,''),(2,2,0,0,0,0,1603950319,1603951067,1,0,'');


game_rule表
CREATE TABLE `rh_game_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nav_id` int(11) NOT NULL DEFAULT '0' COMMENT '导航id',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `content` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '奖励',
  `value` float NOT NULL DEFAULT '0' COMMENT '概率',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存-1为无限',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0积分1其它2谢谢参与',
  `points` int(11) NOT NULL DEFAULT '0' COMMENT '积分值',
  `img_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='游戏规则'


tree_record表
CREATE TABLE `rh_tree_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `state` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='种树记录'

nav表
INSERT INTO `rh_nav` VALUES (1,'九宫格','','/means/play/sherpa-jiugongge/sherpa-jiugongge','九宫格',0,0,0,NULL),(2,'大转盘','/uploads/other/5f96a56cb73f2.jpg','/means/play/activity/draw','大转盘',0,0,0,NULL);



*************************************************************************************************************

# V1.240
新增：图片水印
修复：php7.4兼容

数据表：
sys_config表：
INSERT INTO `rh_sys_config` VALUES (62,'watermark','0','水印开关',9,1,'',1603332916,1603332916),(63,'watermark_text','','水印文字',9,0,NULL,1603332916,1603332916)




*************************************************************************************************************

# V1.239
新增：saas应用

修复：部分bug



*************************************************************************************************************

# V1.238

修复：以往版本出现的bug



*************************************************************************************************************
# V1.237
新增：saas应用
修复：公众号海报，cms打印订单

数据库：
sys_config表：
INSERT INTO `rh_sys_config` VALUES (54,'hot_swicth','0','热搜开关',1,1,NULL,1594177593,1594177593),(55,'remote_login','0','远程登录',1,1,NULL,1594177593,1594177593);

*************************************************************************************************************
# V1.236

新增：分销手动提现、公众号手机绑定
数据库：
User表增加了一个字段gzg_code，varchar类型,主要用来存储公众号手机绑定时的验证码

fx_manual表：
CREATE TABLE `rh_fx_manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `money` float NOT NULL DEFAULT '0' COMMENT '金额',
  `rid` text NOT NULL COMMENT '申请记录id关联',
  `bk_name` varchar(255) DEFAULT NULL COMMENT '银行名称',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态:0待同意,1同意,-1驳回',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `card` varchar(30) DEFAULT NULL COMMENT '卡号',
  `bk_uname` varchar(255) DEFAULT NULL COMMENT '银行户名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='手动提现申请表'


fx_record表：
CREATE TABLE `rh_fx_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_num` varchar(50) NOT NULL COMMENT '订单编号',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `user_id` int(11) NOT NULL COMMENT '购买人ID',
  `agent_id` int(11) NOT NULL COMMENT '代理商ID',
  `money` decimal(10,2) NOT NULL COMMENT '金额',
  `status` int(11) NOT NULL COMMENT '是否提现完成(0为否，1申请中，2完成)',
  `type` int(11) NOT NULL COMMENT '购买的类型：1会员2商品',
  `wx_pay` varchar(50) DEFAULT NULL,
  `cpy_pay_status` int(11) DEFAULT '0' COMMENT '公司打款状态1打款成功，2打款失败，3当天未打款',
  `pay_time` varchar(255) DEFAULT NULL COMMENT '打款时间',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `delete_time` int(11) NOT NULL,
  `card` varchar(30) DEFAULT NULL COMMENT '银行卡',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='分销提现记录表'

SysConfig表:
INSERT INTO `rh_sys_config` VALUES ('min_money','','余额最低限制',1,0,NULL,1591959426,1591959426),

修复：后台优化




*************************************************************************************************************
# V1.235
新增：支付宝支付、会员权益
数据库：
SysConfig表增加了5条记录，主要是支付宝支付钥匙及会员权益等信息
SQL:
SysConfig表:
INSERT INTO `rh_sys_config` VALUES (46,'zfb_appid','','支付宝appid',1,0,NULL,1591959426,1591959426),(47,'zfb_private_key','','支付宝商户私钥',1,0,NULL,1591959426,1591959426),(48,'zfb_public_key','','支付宝公钥',1,0,NULL,1591959426,1591959426),(49,'zfb_back','','支付宝回调地址',1,0,NULL,1591959426,1591959426),(51,'menber_equity','','会员权益',1,3,NULL,1591959426,1591959426);


*************************************************************************************************************
## V1.234
新增：会员功能、万能表单

数据库：
rh_order增加一个other字段,text类型，主要用来存储万能表数据

*************************************************************************************************************
## V1.233
### 注意：小程序已分包，如果使用新版本小程序，首页对应的导航需要从后台重新设置一下。

新增：
配送方式：快递，同城，自提

修复：
小程序海报及扫码进入、部分快递单号异常、公众号评价功能、商城手机管理后台权限

SQL：
order表：
ALTER TABLE `rh_order` ADD `drive_type` VARCHAR(255) NULL DEFAULT NULL COMMENT '配送方式' AFTER `courier`;

SysConfig表：
INSERT INTO `rh_sys_config` (`id`, `key`, `value`, `desc`, `type`, `switch`, `other`, `update_time`, `create_time`) VALUES
(40, 'yzm_tmp_id', 'sm1', '阿里云模板ID', 1, 0, NULL, 1589794997, 1589794997), 
(44, 'zt_address', '某某市某某街102号', '自提地址', 1, 0, NULL, 1589794997, 1589794997),
(45, 'drive_type', '0', '配送方式', 1, 2, '0快递1快递+自提2快递+同城3所有', 1589794997, 1589794997);

*************************************************************************************************************

## V1.232
新增：小程序直播

修复：运费计算，评价