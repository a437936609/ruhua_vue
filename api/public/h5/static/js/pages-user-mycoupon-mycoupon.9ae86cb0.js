(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-user-mycoupon-mycoupon"],{"3b25":function(e,n,t){var o=t("24fb");n=o(!1),n.push([e.i,".none[data-v-4f6f7336]{padding:150px 0;text-align:center;color:#adadad;line-height:50px}.none img[data-v-4f6f7336]{width:60px;height:60px}.none .guang[data-v-4f6f7336]{text-align:center;color:#282828;font-size:14px}.none .guang span[data-v-4f6f7336]{height:30px;line-height:30px;border:1px solid #282828;display:inline-block;padding:0 25px;border-radius:2px}",""]),e.exports=n},4144:function(e,n,t){"use strict";t.r(n);var o=t("823c"),i=t.n(o);for(var a in o)"default"!==a&&function(e){t.d(n,e,(function(){return o[e]}))}(a);n["default"]=i.a},"43f4":function(e,n,t){e.exports=t.p+"static/img/late.631b0ea8.png"},5133:function(e,n,t){"use strict";var o=t("4ea4");t("c975"),t("ac1f"),t("466d"),t("5319"),t("841c"),t("1276"),Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0,t("96cf");var i=o(t("1da1")),a=t("f9f7"),r=o(t("e143")),c=0,s={GetUrlParame:function(e){var n=window.location.search;if(n.indexOf(e)>-1){var t="";return t=n.substring(n.indexOf(e),n.length),t.indexOf("&")>-1?(t=t.substring(0,t.indexOf("&")),t=t.replace(e+"=",""),t):""}},check_login_xcx:function(){var e=uni.getStorageSync("token");return uni.request({url:a.Api_url+"auth/token_verify",method:"POST",data:{token:e},success:function(e){var n=e.data.isValid;return console.log(e),n&&401!=e.data.statusCode?c=1:(console.log("token不存在"),c=0),console.log(c),c}})},check_login_h5:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";var n=this.GetUrlParame("code"),t=window.location.href.split("#")[0];t=t.split("?code")[0];if(!n){console.log("获取code");var o=uni.getStorageSync("token");return o?c=1:(console.log("token不存在"),c=0)}},check_login_APP:function(){var e=uni.getStorageSync("token");return e?1:(console.log("token不存在"),0)},judge_gl:function(){return(0,i.default)(regeneratorRuntime.mark((function e(){var n,t;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,r.default.prototype.promise_switch.then((function(e){return e}));case 2:n=e.sent,console.log("xxa:",n),n.merge_mode,t=window.navigator.userAgent.toLowerCase(),"micromessenger"==t.match(/MicroMessenger/i)?uni.redirectTo({url:"/pages/login/login"}):uni.redirectTo({url:"/pages/login/loginB/loginB"});case 7:case"end":return e.stop()}}),e)})))()},a:function(){var e=this;return(0,i.default)(regeneratorRuntime.mark((function n(){var t,o,i;return regeneratorRuntime.wrap((function(n){while(1)switch(n.prev=n.next){case 0:if(t=e,o="",i=window.navigator.userAgent.toLowerCase(),"micromessenger"!=i.match(/MicroMessenger/i)){n.next=9;break}return n.next=6,t.check_login_xcx();case 6:o=n.sent,n.next=12;break;case 9:return n.next=11,t.check_login_h5();case 11:o=n.sent;case 12:if(0!=o){n.next=15;break}return t.judge_gl(),n.abrupt("return",!1);case 15:if(1!=o){n.next=17;break}return n.abrupt("return",!0);case 17:case"end":return n.stop()}}),n)})))()}};n.default=s},5991:function(e,n,t){"use strict";t.r(n);var o=t("8e7e"),i=t("4144");for(var a in i)"default"!==a&&function(e){t.d(n,e,(function(){return i[e]}))}(a);t("f8e5");var r,c=t("f0c5"),s=Object(c["a"])(i["default"],o["b"],o["c"],!1,null,"4f6f7336",null,!1,o["a"],r);n["default"]=s.exports},"5c09":function(e,n,t){e.exports=t.p+"static/img/yi.ec529d7b.png"},"6c38":function(e,n,t){"use strict";t.r(n);var o=t("f196"),i=t.n(o);for(var a in o)"default"!==a&&function(e){t.d(n,e,(function(){return o[e]}))}(a);n["default"]=i.a},"7b4e":function(e,n){e.exports="data:image/png;base64,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"},"7ea2":function(e,n,t){var o=t("3b25");"string"===typeof o&&(o=[[e.i,o,""]]),o.locals&&(e.exports=o.locals);var i=t("4f06").default;i("f27ca22c",o,!0,{sourceMap:!1,shadowMode:!1})},"823c":function(e,n,t){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var o={name:"none",props:["guang"],data:function(){return{}},mounted:function(){},methods:{emits:function(){uni.redirectTo({url:"../../pages/extend-view/productList/productList"})}}};n.default=o},"8e7e":function(e,n,t){"use strict";var o;t.d(n,"b",(function(){return i})),t.d(n,"c",(function(){return a})),t.d(n,"a",(function(){return o}));var i=function(){var e=this,n=e.$createElement,o=e._self._c||n;return o("v-uni-view",{staticClass:"no"},[o("v-uni-view",{staticClass:"none"},[o("img",{attrs:{src:t("7b4e")}}),o("v-uni-view",[e._v("暂无数据")]),e.guang?o("v-uni-view",{staticClass:"guang",on:{click:function(n){arguments[0]=n=e.$handleEvent(n),e.emits.apply(void 0,arguments)}}},[o("span",[e._v(e._s(e.guang))])]):e._e()],1)],1)},a=[]},b155:function(e,n,t){"use strict";var o=t("c2a8"),i=t.n(o);i.a},bd2c:function(e,n,t){"use strict";t.r(n);var o=t("c634"),i=t("6c38");for(var a in i)"default"!==a&&function(e){t.d(n,e,(function(){return i[e]}))}(a);t("b155");var r,c=t("f0c5"),s=Object(c["a"])(i["default"],o["b"],o["c"],!1,null,"0b43adfe",null,!1,o["a"],r);n["default"]=s.exports},c2a8:function(e,n,t){var o=t("c979");"string"===typeof o&&(o=[[e.i,o,""]]),o.locals&&(e.exports=o.locals);var i=t("4f06").default;i("4e4ef2cc",o,!0,{sourceMap:!1,shadowMode:!1})},c634:function(e,n,t){"use strict";var o;t.d(n,"b",(function(){return i})),t.d(n,"c",(function(){return a})),t.d(n,"a",(function(){return o}));var i=function(){var e=this,n=e.$createElement,o=e._self._c||n;return o("v-uni-view",{staticClass:"coupon"},[o("v-uni-view",{staticClass:"po"},[o("v-uni-view",{staticClass:"tab"},[o("v-uni-view",{class:0==e.c_index?"xz":"bb",on:{click:function(n){arguments[0]=n=e.$handleEvent(n),e.num(0)}}},[e._v("未使用")]),o("v-uni-view",{class:1==e.c_index?"xz":"bb",on:{click:function(n){arguments[0]=n=e.$handleEvent(n),e.num(1)}}},[e._v("使用记录")]),o("v-uni-view",{class:3==e.c_index?"xz":"bb",on:{click:function(n){arguments[0]=n=e.$handleEvent(n),e.num(3)}}},[e._v("已过期")])],1),e.list_empty?o("None"):e._l(e.list,(function(n,i){return o("v-uni-view",{key:i},[n.status==e.state?o("v-uni-view",{staticClass:"coupon"},[o("v-uni-view",{staticClass:"cou_t"},[o("v-uni-view",{staticClass:"cou_t_l"},[o("v-uni-view",{class:e.class_name},[o("span",[e._v("¥")]),e._v(e._s(Math.floor(n.reduce)))])],1),o("v-uni-view",{staticClass:"cou_t_r"},[0==n.full?o("v-uni-view",{staticClass:"cou_t_r_01"},[e._v("减"+e._s(Math.floor(n.reduce)))]):o("v-uni-view",{staticClass:"cou_t_r_01"},[e._v("满"+e._s(Math.floor(n.full))+" 减"+e._s(Math.floor(n.reduce)))]),o("v-uni-view",{staticClass:"cou_t_r_02"},[e._v("有效期："+e._s(n.end_time))])],1),0==e.state?o("v-uni-view",{class:e.btn_name},[o("v-uni-view",{on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.jump_to(n.goods_ids)}}},[e._v("去使用")])],1):e._e()],1),0==n.goods_ids?[o("v-uni-view",{staticClass:"cou_d"},[e._v("所有商品可用")])]:[o("v-uni-view",{staticClass:"cou_d"},[e._v("指定商品可用")])],1==e.state?o("v-uni-view",{staticClass:"ysy"},[o("img",{attrs:{src:t("5c09")}})]):e._e(),3==e.state?o("v-uni-view",{staticClass:"ysy"},[o("img",{attrs:{src:t("43f4")}})]):e._e()],2):e._e()],1)}))],2)],1)},a=[]},c979:function(e,n,t){var o=t("24fb");n=o(!1),n.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */\r\n/* 页面左右间距 */\r\n/* 文字尺寸 */\r\n/*文字颜色*/\r\n/* 边框颜色 */\r\n/* 图片加载中颜色 */\r\n/* 行为相关颜色 */.coupon[data-v-0b43adfe]{background-color:#f8f8f8;min-height:100vh;padding-bottom:1px}.coupon .po[data-v-0b43adfe]{z-index:99;width:100%}.coupon .tab[data-v-0b43adfe]{padding:10px 10px 0;display:-webkit-box;display:-webkit-flex;display:flex;width:100%;font-size:14px;background-color:#fff}.coupon .bb[data-v-0b43adfe]{padding-bottom:5px;text-align:center;width:50%}.coupon .xz[data-v-0b43adfe]{border-bottom:2px solid red;padding-bottom:5px;width:50%;text-align:center}.coupon .coupon[data-v-0b43adfe]{margin:15px;background-color:#fff;border:1px solid #eee;border-radius:5px;box-shadow:2px 2px 2px #eee;color:#9fa0a2;min-height:10px;position:relative}.coupon .coupon .ysy[data-v-0b43adfe]{position:absolute;right:10px;top:0}.coupon .coupon .ysy img[data-v-0b43adfe]{width:80px;height:80px;z-index:99}.coupon .cou_t[data-v-0b43adfe]{display:-webkit-box;display:-webkit-flex;display:flex;padding:20px 10px 10px;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;font-size:12px;width:100%;box-sizing:border-box}.coupon .cou_t_l[data-v-0b43adfe]{width:20%;-webkit-flex-shrink:0;flex-shrink:0}.coupon .cou_t_r[data-v-0b43adfe]{width:55%;box-sizing:border-box;padding-left:10px;-webkit-box-flex:1;-webkit-flex-grow:1;flex-grow:1}.coupon .cou_t_rr[data-v-0b43adfe]{width:70px;background-color:#eb113e;color:#fff;height:25px;line-height:25px;font-size:12px;text-align:center;margin:15px 0 0 10px;border-radius:20px}.coupon .btn_grey[data-v-0b43adfe]{width:70px;background-color:#9fa0a2;color:#fff;height:25px;line-height:25px;font-size:12px;text-align:center;margin:15px 0 0 10px;border-radius:20px}.coupon .cou_t_l_01[data-v-0b43adfe]{color:#f44;font-size:26px;padding-top:8px}.coupon .cou_t_l_01 span[data-v-0b43adfe]{font-size:12px}.coupon .cou_sss[data-v-0b43adfe]{font-size:26px;padding-top:8px}.coupon .cou_sss span[data-v-0b43adfe]{font-size:12px}.coupon .cou_t_r_01[data-v-0b43adfe]{font-size:18px;color:#323233;padding:3px 0 5px}.coupon .cou_d[data-v-0b43adfe]{background-color:#fafafa;padding:6px 15px;border-top:1px dashed #ebedf0;font-size:12px}.coupon .H50[data-v-0b43adfe]{height:50px}.coupon .btn[data-v-0b43adfe]{position:fixed;bottom:0;width:100%;height:45px;line-height:45px;text-align:center;border-top:1px solid #eee;z-index:999;font-size:14px;background-color:#fff}',""]),e.exports=n},f196:function(e,n,t){"use strict";var o=t("4ea4");Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var i=o(t("5991")),a=t("f7ec"),r={data:function(){return{btn_name:"cou_t_rr",c_index:0,list:"",class_name:"cou_t_l_01",state:0,list_empty:!1}},mixins:[a.common],components:{None:i.default},onLoad:function(){},onShow:function(){this._load()},methods:{jump_to:function(e){var n="";n=0==e?"all":"coupon",uni.navigateTo({url:"../../extend-view/productList/productList?cid=5&sid=0&type="+n})},_load:function(){var e=this;this.$api.http.get("coupon/user/get_coupon").then((function(n){""==n.data?e.list_empty=!0:e.list=n.data,uni.stopPullDownRefresh()}))},num:function(e){0==e&&(this.c_index=e,this.class_name="cou_t_l_01",this.btn_name="cou_t_rr",this.state=0),1==e&&(this.c_index=e,this.class_name="cou_sss",this.btn_name="btn_grey",this.state=1),3==e&&(this.c_index=e,this.class_name="cou_sss",this.btn_name="btn_grey",this.state=3)}},onPullDownRefresh:function(){console.log("refresh"),this._load(),setTimeout((function(){uni.stopPullDownRefresh()}),2e3)}};n.default=r},f7ec:function(e,n,t){"use strict";var o=t("4ea4");Object.defineProperty(n,"__esModule",{value:!0}),n.common=void 0;var i=o(t("5133")),a={onLoad:function(){this.check_login()},onShow:function(){},methods:{check_login:function(){console.log("检查登录"),i.default.a()&&this._load()}}};n.common=a},f8e5:function(e,n,t){"use strict";var o=t("7ea2"),i=t.n(o);i.a}}]);