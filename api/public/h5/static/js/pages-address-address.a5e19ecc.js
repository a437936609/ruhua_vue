(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-address-address"],{"246d":function(e,t,n){var i=n("24fb");t=i(!1),t.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */\r\n/* 页面左右间距 */\r\n/* 文字尺寸 */\r\n/*文字颜色*/\r\n/* 边框颜色 */\r\n/* 图片加载中颜色 */\r\n/* 行为相关颜色 */uni-page-body[data-v-17044c2b]{padding-bottom:%?120?%}.content[data-v-17044c2b]{position:relative}.list[data-v-17044c2b]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding:%?20?% %?30?%;background:#fff;position:relative}.wrapper[data-v-17044c2b]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-flex:1;-webkit-flex:1;flex:1}.address-box[data-v-17044c2b]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.address-box .tag[data-v-17044c2b]{font-size:%?24?%;width:%?100?%;color:#fa436a;margin-right:%?10?%;background:#fffafb;border:1px solid #ffb4c7;border-radius:%?4?%;padding:%?4?% %?10?%;line-height:1}.address-box .address[data-v-17044c2b]{font-size:%?30?%;color:#303133}.u-box[data-v-17044c2b]{font-size:%?28?%;color:#909399;margin-top:%?16?%}.u-box .name[data-v-17044c2b]{margin-right:%?30?%}.icon-bianji[data-v-17044c2b]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;height:%?80?%;font-size:%?40?%;color:#909399;padding-left:%?30?%}.add-btn[data-v-17044c2b]{position:fixed;left:%?30?%;right:%?30?%;bottom:%?16?%;z-index:95;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;width:%?690?%;height:%?80?%;font-size:%?32?%;color:#fff;border-radius:%?10?%}',""]),e.exports=t},3124:function(e,t,n){"use strict";var i=n("8768"),a=n.n(i);a.a},"3b25":function(e,t,n){var i=n("24fb");t=i(!1),t.push([e.i,".none[data-v-4f6f7336]{padding:150px 0;text-align:center;color:#adadad;line-height:50px}.none img[data-v-4f6f7336]{width:60px;height:60px}.none .guang[data-v-4f6f7336]{text-align:center;color:#282828;font-size:14px}.none .guang span[data-v-4f6f7336]{height:30px;line-height:30px;border:1px solid #282828;display:inline-block;padding:0 25px;border-radius:2px}",""]),e.exports=t},4144:function(e,t,n){"use strict";n.r(t);var i=n("823c"),a=n.n(i);for(var r in i)"default"!==r&&function(e){n.d(t,e,(function(){return i[e]}))}(r);t["default"]=a.a},"47cd":function(e,t,n){"use strict";n.r(t);var i=n("d420"),a=n.n(i);for(var r in i)"default"!==r&&function(e){n.d(t,e,(function(){return i[e]}))}(r);t["default"]=a.a},5133:function(e,t,n){"use strict";var i=n("4ea4");n("c975"),n("ac1f"),n("466d"),n("5319"),n("841c"),n("1276"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,n("96cf");var a=i(n("1da1")),r=n("f9f7"),o=i(n("e143")),s=0,c={GetUrlParame:function(e){var t=window.location.search;if(t.indexOf(e)>-1){var n="";return n=t.substring(t.indexOf(e),t.length),n.indexOf("&")>-1?(n=n.substring(0,n.indexOf("&")),n=n.replace(e+"=",""),n):""}},check_login_xcx:function(){var e=uni.getStorageSync("token");return uni.request({url:r.Api_url+"auth/token_verify",method:"POST",data:{token:e},success:function(e){var t=e.data.isValid;return console.log(e),t&&401!=e.data.statusCode?s=1:(console.log("token不存在"),s=0),console.log(s),s}})},check_login_h5:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:"";var t=this.GetUrlParame("code"),n=window.location.href.split("#")[0];n=n.split("?code")[0];if(!t){console.log("获取code");var i=uni.getStorageSync("token");return i?s=1:(console.log("token不存在"),s=0)}},check_login_APP:function(){var e=uni.getStorageSync("token");return e?1:(console.log("token不存在"),0)},judge_gl:function(){return(0,a.default)(regeneratorRuntime.mark((function e(){var t,n;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,o.default.prototype.promise_switch.then((function(e){return e}));case 2:t=e.sent,console.log("xxa:",t),t.merge_mode,n=window.navigator.userAgent.toLowerCase(),"micromessenger"==n.match(/MicroMessenger/i)?uni.redirectTo({url:"/pages/login/login"}):uni.redirectTo({url:"/pages/login/loginB/loginB"});case 7:case"end":return e.stop()}}),e)})))()},a:function(){var e=this;return(0,a.default)(regeneratorRuntime.mark((function t(){var n,i,a;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(n=e,i="",a=window.navigator.userAgent.toLowerCase(),"micromessenger"!=a.match(/MicroMessenger/i)){t.next=9;break}return t.next=6,n.check_login_xcx();case 6:i=t.sent,t.next=12;break;case 9:return t.next=11,n.check_login_h5();case 11:i=t.sent;case 12:if(0!=i){t.next=15;break}return n.judge_gl(),t.abrupt("return",!1);case 15:if(1!=i){t.next=17;break}return t.abrupt("return",!0);case 17:case"end":return t.stop()}}),t)})))()}};t.default=c},5991:function(e,t,n){"use strict";n.r(t);var i=n("8e7e"),a=n("4144");for(var r in a)"default"!==r&&function(e){n.d(t,e,(function(){return a[e]}))}(r);n("f8e5");var o,s=n("f0c5"),c=Object(s["a"])(a["default"],i["b"],i["c"],!1,null,"4f6f7336",null,!1,i["a"],o);t["default"]=c.exports},"7b4e":function(e,t){e.exports="data:image/png;base64,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"},"7ea2":function(e,t,n){var i=n("3b25");"string"===typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);var a=n("4f06").default;a("f27ca22c",i,!0,{sourceMap:!1,shadowMode:!1})},"823c":function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i={name:"none",props:["guang"],data:function(){return{}},mounted:function(){},methods:{emits:function(){uni.redirectTo({url:"../../pages/extend-view/productList/productList"})}}};t.default=i},8768:function(e,t,n){var i=n("246d");"string"===typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);var a=n("4f06").default;a("1c2d85ee",i,!0,{sourceMap:!1,shadowMode:!1})},"8bf6":function(e,t,n){"use strict";n.r(t);var i=n("d386"),a=n("47cd");for(var r in a)"default"!==r&&function(e){n.d(t,e,(function(){return a[e]}))}(r);n("3124");var o,s=n("f0c5"),c=Object(s["a"])(a["default"],i["b"],i["c"],!1,null,"17044c2b",null,!1,i["a"],o);t["default"]=c.exports},"8e7e":function(e,t,n){"use strict";var i;n.d(t,"b",(function(){return a})),n.d(t,"c",(function(){return r})),n.d(t,"a",(function(){return i}));var a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("v-uni-view",{staticClass:"no"},[i("v-uni-view",{staticClass:"none"},[i("img",{attrs:{src:n("7b4e")}}),i("v-uni-view",[e._v("暂无数据")]),e.guang?i("v-uni-view",{staticClass:"guang",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.emits.apply(void 0,arguments)}}},[i("span",[e._v(e._s(e.guang))])]):e._e()],1)],1)},r=[]},d386:function(e,t,n){"use strict";var i;n.d(t,"b",(function(){return a})),n.d(t,"c",(function(){return r})),n.d(t,"a",(function(){return i}));var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-uni-view",{staticClass:"content b-t"},[e.list_empty?n("None"):e._l(e.addressList,(function(t,i){return n("v-uni-view",{key:i,staticClass:"list b-b"},[n("v-uni-view",{staticClass:"wrapper"},[n("v-uni-view",{staticClass:"address-box"},[t.is_default?n("v-uni-text",{staticClass:"tag"},[e._v("默认")]):e._e(),t.is_default?e._e():n("v-uni-text",{staticClass:"tag",on:{click:function(n){arguments[0]=n=e.$handleEvent(n),e.set_default(t.id)}}},[e._v("设默认")]),n("v-uni-text",{staticClass:"address",on:{click:function(n){arguments[0]=n=e.$handleEvent(n),e.checkAddress(t.id)}}},[e._v(e._s(t.province)+" "+e._s(t.city)+e._s(t.county))])],1),n("v-uni-view",{staticClass:"u-box",on:{click:function(n){arguments[0]=n=e.$handleEvent(n),e.checkAddress(t.id)}}},[n("v-uni-text",{staticClass:"name"},[e._v(e._s(t.name))]),n("v-uni-text",{staticClass:"mobile"},[e._v(e._s(t.mobile))])],1)],1),n("v-uni-text",{staticClass:"yticon icon-bianji",on:{click:function(n){n.stopPropagation(),arguments[0]=n=e.$handleEvent(n),e.addAddress("edit",t)}}})],1)})),n("v-uni-view",{staticClass:"add-btn"},[n("v-uni-button",{staticStyle:{width:"45%"},attrs:{type:"warn"},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.addAddressA("add")}}},[e._v("新增地址")])],1)],2)},r=[]},d420:function(e,t,n){"use strict";var i=n("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a=i(n("ade3")),r=i(n("5991")),o=n("f7ec"),s={data:function(){var e;return{list_empty:!1,form:(e={name:"",mobile:"",city:"",detail:"",province:"",county:""},(0,a.default)(e,"detail",""),(0,a.default)(e,"areacode",""),e),wx_address:"",source:0,addressList:[],type_add:"add"}},mixins:[o.common],components:{None:r.default},onLoad:function(e){this.source=e.source,this.check_login()},onShow:function(){this._load()},methods:{get_address:function(){var e=this,t="";uni.chooseAddress({success:function(n){t=n,e.sub_address(n)}}),console.log(t)},sub_address:function(e){var t=this;this.form.name=e.userName,this.form.mobile=e.telNumber,this.form.city=e.cityName,this.form.county=e.countyName,this.form.detail=e.detailInfo,this.form.province=e.provinceName,this.form.areacode=e.postalCode,this.$api.http.post("address/add_address",this.form).then((function(e){200==e.status?(t.$api.msg("添加成功!"),setTimeout((function(){uni.navigateBack()}),1e3)):t.$api.msg(e.msg)}))},_load:function(){var e=this;this.$api.http.get("address/get_all_address").then((function(t){""==t.data?e.list_empty=!0:e.addressList=t.data}))},set_default:function(e){var t=this;this.$api.http.post("address/set_default_address",{id:e}).then((function(e){t.$api.msg("成功更改默认地址"),t._load()}))},checkAddress:function(e){var t=this;this.$api.http.post("address/set_default_address",{id:e}).then((function(e){t._load(),uni.navigateBack()}))},addAddress:function(e,t){uni.setStorageSync("edit_data",t),uni.navigateTo({url:"/pages/address/addressManage?type="+e})},addAddressA:function(){uni.navigateTo({url:"/pages/address/addressManage?type="+this.type_add})},refreshList:function(e,t){this.addressList.unshift(e),console.log(e,t)}},onPullDownRefresh:function(){this._load(),setTimeout((function(){uni.stopPullDownRefresh()}),2e3)}};t.default=s},f7ec:function(e,t,n){"use strict";var i=n("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.common=void 0;var a=i(n("5133")),r={onLoad:function(){this.check_login()},onShow:function(){},methods:{check_login:function(){console.log("检查登录"),a.default.a()&&this._load()}}};t.common=r},f8e5:function(e,t,n){"use strict";var i=n("7ea2"),a=n.n(i);a.a}}]);