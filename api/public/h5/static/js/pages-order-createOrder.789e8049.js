(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-order-createOrder"],{"292b":function(t,e,i){"use strict";var n=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n(i("3f9b")),o={postOrderAll:function(){var t="order/user/all_order";return a.default.post(t).then((function(t){return t}))},postOrderWxPay:function(t){var e="order/pay/pre_order";return a.default.post(e,{id:t}).then((function(t){return t}))},postOrderAppPay:function(t){var e="order/pay/pre_app";return a.default.post(e,{id:t}).then((function(t){return t}))},postOrderH5Pay:function(t){var e="order/second_pay";return a.default.post(e,{id:t}).then((function(t){return t}))},putOrderdel:function(t){var e="order/user/del_order?id=";return a.default.put(e,{id:t}).then((function(t){return t}))},getPtOneItem:function(t){var e="pt/get_one_item";return a.default.post(e,{id:t}).then((function(t){return t}))},postPtCreateItem:function(t){var e="pt/create_pt_item";return a.default.post(e,t).then((function(t){return t}))},postPtCreateItems:function(t){var e="pt/create_pt";return a.default.post(e,t).then((function(t){return t}))}};e.default=o},"36f7":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[t.address_show?i("v-uni-navigator",{staticClass:"address-section",attrs:{url:"/pages/address/address?source=1"}},[i("v-uni-view",{staticClass:"order-content"},[i("v-uni-text",{staticClass:"yticon icon-shouhuodizhi"}),t.address?i("v-uni-view",{staticClass:"cen"},[i("v-uni-view",{staticClass:"top"},[i("v-uni-text",{staticClass:"name"},[t._v(t._s(t.address.name))]),i("v-uni-text",{staticClass:"mobile"},[t._v(t._s(t.address.mobile))])],1),i("v-uni-text",{staticClass:"address"},[t._v(t._s(t.address.province)+" "+t._s(t.address.city)+t._s(t.address.county))])],1):t._e(),i("v-uni-text",{staticClass:"yticon icon-you"})],1)],1):t._e(),1!=t.is_kai&&1==t.is_pt?i("v-uni-view",{staticClass:"H10"}):t._e(),1!=t.is_kai&&1==t.is_pt?i("v-uni-view",{staticClass:"tuanzhang"},[i("v-uni-view",{staticClass:"tz-l"},[i("v-uni-checkbox-group",[i("v-uni-label",[i("v-uni-checkbox",{staticStyle:{transform:"scale(0.7)"},attrs:{value:"cb"}})],1)],1)],1),i("v-uni-view",{staticClass:"tz-m"},[t._v("该团由团长代收包裹（免运费）")]),i("v-uni-view",{staticClass:"tz-r"},[t._v("团长：123")])],1):t._e(),i("v-uni-view",{staticClass:"H10"}),i("v-uni-view",{staticClass:"uni-list"},[i("v-uni-radio-group",{on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.radioChange.apply(void 0,arguments)}}},[1==t.switch_list.drive_type||2==t.switch_list.drive_type||3==t.switch_list.drive_type?i("v-uni-label",{staticClass:"uni-list-cell uni-list-cell-pd"},[i("v-uni-view",{staticClass:"tuanzhang"},[i("v-uni-view",{staticClass:"tz-l"},[i("v-uni-radio",{staticStyle:{transform:"scale(0.7)"},attrs:{value:"1"}})],1),i("v-uni-view",{staticClass:"tz-m"},[t._v("快递配送")]),i("v-uni-view",{staticClass:"tz-r"})],1)],1):t._e(),2==t.switch_list.drive_type||3==t.switch_list.drive_type?i("v-uni-label",{staticClass:"uni-list-cell uni-list-cell-pd"},[i("v-uni-view",{staticClass:"tuanzhang"},[i("v-uni-view",{staticClass:"tz-l"},[i("v-uni-radio",{staticStyle:{transform:"scale(0.7)"},attrs:{value:"2"}})],1),i("v-uni-view",{staticClass:"tz-m"},[t._v("同城配送")]),i("v-uni-view",{staticClass:"tz-r"})],1)],1):t._e(),1==t.switch_list.drive_type||3==t.switch_list.drive_type?i("v-uni-label",{staticClass:"uni-list-cell uni-list-cell-pd"},[i("v-uni-view",{staticClass:"tuanzhang"},[i("v-uni-view",{staticClass:"tz-l"},[i("v-uni-radio",{staticStyle:{transform:"scale(0.7)"},attrs:{value:"3"}})],1),i("v-uni-view",{staticClass:"tz-m"},[t._v("到店自提")]),i("v-uni-view",{staticClass:"tz-r"})],1)],1):t._e()],1)],1),3==t.kuaidi?i("v-uni-view",{staticClass:"ztdz"},[t._v(t._s(t.switch_list.zt_address))]):t._e(),i("v-uni-view",{staticClass:"goods-section"},t._l(t.buy_data,(function(e,n){return i("v-uni-view",{key:n,staticClass:"g-item"},[i("v-uni-image",{attrs:{src:t.getimg+e.imgs}}),i("v-uni-view",{staticClass:"right"},[i("v-uni-text",{staticClass:"title clamp"},[t._v(t._s(e.goods_name))]),i("v-uni-text",{staticClass:"spec"},[t._v(t._s(e.sku_name?e.sku_name:""))]),i("v-uni-text",{staticClass:"spec"},[t._v("￥"+t._s(e.price))]),i("v-uni-view",{staticClass:"price-box"})],1)],1)})),1),1==t.is_pt?i("v-uni-view",{staticClass:"yt-list"},[i("v-uni-view",{staticClass:"jr"},[i("v-uni-view",{staticClass:"jr_tit"},[t._v("为您加入仅差"+t._s(t.pt_data.num)+"人的团，支付后即可拼购成功")]),i("v-uni-view",{staticClass:"jr_img"},[i("v-uni-view",{staticClass:"img_01 img_01_border"},[i("img",{attrs:{src:t.pt_data.c_pic}}),i("v-uni-view",{staticClass:"zhicheng"},[t._v("团长")])],1),t._l(t.pt_data.item_pic,(function(t){return i("v-uni-view",{staticClass:"img_01"},[i("img",{attrs:{src:t}})])})),i("v-uni-view",{staticClass:"img_01"},[i("img",{attrs:{src:t.head.avatarUrl}}),i("v-uni-view",{staticClass:"zhicheng"},[t._v("待支付")])],1)],2)],1)],1):t._e(),1==t.is_kai?i("v-uni-view",{staticClass:"yt-list"},[i("v-uni-view",{staticClass:"jr"},[i("v-uni-view",{staticClass:"jr_tit"},[t._v("立即支付，即可开团成功")]),i("v-uni-view",{staticClass:"jr_img"},[i("v-uni-view",{staticClass:"img_01 img_01_border"},[i("img",{attrs:{src:t.head.avatarUrl}}),i("v-uni-view",{staticClass:"zhicheng"},[t._v("待支付")])],1),t._l(t.buy_data[0].pt.pt.user_num-1,(function(e,n){return i("v-uni-view",{key:n,staticClass:"img_01 img_01_borderx"},[t._v("?")])}))],2)],1)],1):t._e(),t.buy_data[0]["discount"]||t.buy_data[0]["pt"]?t._e():i("v-uni-view",{staticClass:"yt-list"},[i("v-uni-view",{staticClass:"yt-list-cell b-b",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.toggleMask("show")}}},[i("v-uni-view",{staticClass:"cell-icon"},[t._v("券")]),i("v-uni-text",{staticClass:"cell-tit clamp"},[t._v("优惠券")]),i("v-uni-text",{staticClass:"cell-tip active"},[t._v(t._s(t.coupon_text))]),i("v-uni-text",{staticClass:"cell-more wanjia wanjia-gengduo-d"})],1)],1),i("v-uni-view",{staticClass:"yt-list"},[i("v-uni-view",{staticClass:"yt-list-cell b-b"},[i("v-uni-text",{staticClass:"cell-tit clamp"},[t._v("商品金额")]),i("v-uni-text",{staticClass:"cell-tip"},[t._v("￥"+t._s(t._f("count_price")(t.goods_money,t.goods_money)))])],1),t.coupon_money>0?i("v-uni-view",{staticClass:"yt-list-cell b-b"},[i("v-uni-text",{staticClass:"cell-tit clamp"},[t._v("优惠金额")]),i("v-uni-text",{staticClass:"cell-tip red"},[t._v("-￥"+t._s(t.coupon_money))])],1):t._e(),i("v-uni-view",{staticClass:"yt-list-cell b-b"},[i("v-uni-text",{staticClass:"cell-tit clamp"},[t._v("运费")]),t.yunfei_money?i("v-uni-text",{staticClass:"cell-tip"},[t._v("￥ "+t._s(t.yunfei_money))]):i("v-uni-text",{staticClass:"cell-tip"},[t._v("免运费")])],1),t.form_switch?i("v-uni-view",{staticStyle:{"margin-top":"20px"}},[i("Formt",{ref:"child",attrs:{data_form:t.data_form,kg:t.kg},on:{get_data:function(e){arguments[0]=e=t.$handleEvent(e),t.get_data.apply(void 0,arguments)}}})],1):t._e(),i("v-uni-view",{staticClass:"yt-list-cell desc-cell"},[i("v-uni-text",{staticClass:"cell-tit clamp"},[t._v("备注")]),i("v-uni-input",{staticClass:"desc",attrs:{type:"text",placeholder:"请填写备注信息","placeholder-class":"placeholder"},model:{value:t.obj.msg,callback:function(e){t.$set(t.obj,"msg",e)},expression:"obj.msg"}})],1)],1),i("v-uni-view",{staticClass:"footer"},[i("v-uni-view",{staticClass:"price-content"},[i("v-uni-text",[t._v("实付款")]),i("v-uni-text",{staticClass:"price-tip"},[t._v("￥")]),i("v-uni-text",{staticClass:"price"},[t._v(t._s(t._f("count_price")(t.pay_money)))])],1),i("v-uni-text",{staticClass:"submit",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.submit.apply(void 0,arguments)}}},[t._v("提交订单")])],1),i("v-uni-view",{staticClass:"mask",class:0===t.maskState?"none":1===t.maskState?"show":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.toggleMask.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"mask-content"},[i("v-uni-scroll-view",{staticClass:"scroll",attrs:{"scroll-y":!0}},t._l(t.couponList,(function(e,n){return i("v-uni-view",{key:n,staticClass:"coupon-item"},[0==e.status?[i("v-uni-view",{staticClass:"con"},[i("v-uni-view",{staticClass:"left"},[0==e.full?i("v-uni-text",{staticClass:"title"},[t._v("减"+t._s(e.reduce))]):i("v-uni-text",{staticClass:"title"},[t._v("满"+t._s(e.full)+" 减"+t._s(e.reduce))])],1),i("v-uni-view",{staticClass:"right"},[i("v-uni-text",{staticClass:"price"},[t._v(t._s(e.reduce))])],1),i("v-uni-view",{staticClass:"circle l"}),i("v-uni-view",{staticClass:"circle r"})],1),i("v-uni-view",{staticClass:"use",staticStyle:{display:"flex","justify-content":"space-between"}},[i("v-uni-text",{staticClass:"tips"},[t._v("有效期至"+t._s(e.end_time))]),1*e.full<=1*t.goods_money?i("v-uni-view",{staticClass:"tips2",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.to_use(n)}}},[t._v("去使用")]):i("v-uni-view",{staticClass:"tips3"},[t._v("不可用")])],1)]:t._e()],2)})),1),i("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.cancel_use.apply(void 0,arguments)}}},[t._v("不使用优惠券")])],1)],1),t.xieyi?i("v-uni-view",{staticClass:"tan"},[i("div",{staticClass:"black"},[i("div",{staticClass:"container"})]),i("v-uni-view",{staticClass:"t_con"},[i("v-uni-view",{staticClass:"t_c_con"},[i("v-uni-rich-text",{attrs:{nodes:t.content}})],1),i("v-uni-view",{staticClass:"t_c_btn"},[i("v-uni-view",{staticClass:"t_c_btn_01 bg_gery",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.xy_approve(!1)}}},[t._v("不同意")]),i("v-uni-view",{staticClass:"t_c_btn_01 bg_red",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.xy_approve(!0)}}},[t._v("同意")])],1)],1)],1):t._e()],1)},o=[]},"4fba":function(t,e,i){"use strict";var n=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n(i("3f9b"));function o(t){var e="product/get_recent";return a.default.get(e,{page:t}).then((function(t){return t}))}function s(t,e){var i="product/get_cate_pros?id="+t+"&page="+e;return a.default.get(i).then((function(t){return t}))}function r(t){var e="product/search?name=";return a.default.get(e+t).then((function(t){return t}))}function c(){var t="/fx/get_goods";return a.default.get(t).then((function(t){return t}))}function d(t){var e="product/mcms/edit_product";return a.default.post(e,t).then((function(t){return t}))}function l(t){var e="product/get_product?id=";return a.default.get(e,{id:t}).then((function(t){return t}))}function u(t){var e="pt/get_item";return a.default.get(e,{id:t}).then((function(t){return t}))}function f(t){var e="product/get_recent";return a.default.get(e,{type:"hot",page:t}).then((function(t){return t}))}function p(){var t="product/get_recent";return a.default.get(t,{type:"new"}).then((function(t){return t}))}function v(){var t="product/mcms/all_goods_info";return a.default.post(t).then((function(t){return t}))}function g(t){var e="/mcms/update";return a.default.put(e,{id:t,db:"goods",field:"state"}).then((function(t){return t}))}function _(t){var e="product/mcms/del_product";return a.default.put(e,{id:t}).then((function(t){return t}))}function h(t){var e="product/get_shipment_price";return a.default.psot(e,t).then((function(t){return t}))}function b(t){console.log("获取二维码");var e="user/get_xcx_code";return a.default.post(e,{path:"pages/extend-view/productDetail/productDetail",scene:t}).then((function(t){return console.log(t),t}))}function m(t){var e="user/get_web_code";return a.default.post(e,{path:"pages/extend-view/productDetail/productDetail?id="+t}).then((function(t){return t}))}function w(t){var e="product/get_evaluate?id=";return a.default.get(e,{id:t}).then((function(t){return t}))}function y(){var t="coupon/get_coupon";return a.default.get(t).then((function(t){return t}))}function x(t){var e="favorite/get_one_fav";return a.default.post(e,{id:t}).then((function(t){return t}))}function k(t){var e="coupon/add_coupon";return a.default.get(e,{id:t}).then((function(t){return t}))}function C(t){var e="favorite/del_fav";return a.default.put(e,{id:t}).then((function(t){return t}))}function z(t){var e="favorite/add_fav";return a.default.post(e,t).then((function(t){return t}))}var S={getProduct:l,getPtItem:u,postWxCode:b,postH5Code:m,getEvalutes:w,getCoupons:y,postIsLike:x,getAddCoupons:k,putDelFavorite:C,postAddFavorite:z,getProList:o,getProductCate:s,getProductSearch:r,getProductFx:c,getProductHotRecent:f,getProductNewRecent:p,postProductEdit:d,postProductAllInfo:v,putProductUpdate:g,putProductDel:_,postProductSimPrice:h};e.default=S},"50f7":function(t,e,i){"use strict";var n=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.CUser=void 0,i("96cf");var a=n(i("1da1")),o=n(i("d4ec")),s=n(i("bee2")),r=n(i("3f9b")),c=Date.parse(new Date)/1e3,d=function(){function t(){(0,o.default)(this,t)}return(0,s.default)(t,[{key:"info",value:function(){var t=uni.getStorageSync("token");if(t){var e=uni.getStorageSync("my");return e&&e.save_time+7200>c?e.data:r.default.get("/user/info").then((function(t){var e={};return e["data"]=t.data,e["save_time"]=c,uni.setStorageSync("my",e),t.data}))}}},{key:"info_wait",value:function(){var t=(0,a.default)(regeneratorRuntime.mark((function t(){var e;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(e=uni.getStorageSync("my"),!(e.data&&e.data.headpic&&e.save_time+7200>c)){t.next=3;break}return t.abrupt("return",e.data);case 3:return t.abrupt("return",r.default.get("user/info").then((function(t){console.log("获取用户信息：",t.data);var e={};return e["data"]=t.data,e["save_time"]=c,uni.setStorageSync("my",e),t.data})));case 4:case"end":return t.stop()}}),t)})));function e(){return t.apply(this,arguments)}return e}()},{key:"reset_storage",value:function(){var t=(0,a.default)(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.abrupt("return",r.default.get("/user/info").then((function(t){var e={};return e["data"]=t.data,e["save_time"]=c,uni.setStorageSync("my",e),console.log("重置缓存"),console.log(e,t.data),t.data})));case 1:case"end":return t.stop()}}),t)})));function e(){return t.apply(this,arguments)}return e}()}]),t}();e.CUser=d},6915:function(t,e,i){var n=i("ecd3");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("981a2134",n,!0,{sourceMap:!1,shadowMode:!1})},"801b":function(t,e,i){"use strict";var n=i("4ea4");i("13d5"),i("d3b7"),i("ac1f"),i("466d"),i("ddb0"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,i("96cf");var a=n(i("1da1")),o=n(i("fb38")),s=i("50f7"),r=(n(i("4fba")),n(i("292b"))),c=new s.CUser,d={data:function(){return{address_show:!1,form_switch:"",kuaidi:0,kg:0,data_form:[],goods_money:0,content:"演示商品，请勿购买。如需测试支付功能，购买后不发货不退款",xieyi:!1,sku_id:"",head:"",is_kai:0,pid:"",is_pt:0,pt_data:"",coupon_id:0,save_cache:{},order_id:0,getimg:this.$getimg,maskState:0,desc:"",payType:1,yunfei_money:0,couponList:[],addressData:{},state:"",buy_data:"",address:"",paying:"",coupon_text:"选择优惠券",coupon_money:0,switch_list:"",obj:{msg:"",order_from:"xcx",payment_type:"wx",total_price:"",shoping_price:"",coupon_price:"",coupon_id:0,discount:"",switch_list:"",invoice_switch:!1,drive_type:"",other:""},wnlist:"",sys_switch:""}},components:{Formt:o.default},onLoad:function(t){var e=this;return(0,a.default)(regeneratorRuntime.mark((function i(){var n,a,o,s,r,d,l,u,f,p,v,g,_;return regeneratorRuntime.wrap((function(i){while(1)switch(i.prev=i.next){case 0:return e.sys_switch=uni.getStorageSync("switch"),e.form_switch=1==e.sys_switch.is_form,e.state=t.state,i.next=5,e.prmSwitch();case 5:if(e.check_switch(),"buy"!=t.state){i.next=27;break}n=uni.getStorageSync("buy"),console.log("购买的商品：",n),e.form_switch&&e.get_wn_data(),e.buy_data=n,i.t0=regeneratorRuntime.keys(n);case 12:if((i.t1=i.t0()).done){i.next=26;break}if(a=i.t1.value,o=n[a],0!=o.style){i.next=21;break}return e.address_show=!0,console.log("实物商品",e.address_show),i.abrupt("break",26);case 21:return e.address_show=!1,console.log("虚拟商品",e.address_show),i.abrupt("break",26);case 24:i.next=12;break;case 26:console.log("虚拟商品",e.address_show);case 27:if("car"!=t.state){i.next=46;break}for(u in s=uni.getStorageSync("cart"),r=[],d={},l=0,s)f=s[u],console.log(u,f),f.radio?(r[l]=f,l++):d[u]=f;e.buy_data=r,e.save_cache=d,i.t2=regeneratorRuntime.keys(s);case 36:if((i.t3=i.t2()).done){i.next=45;break}if(p=i.t3.value,v=s[p],0!=v.style){i.next=43;break}return e.address_show=!0,console.log("实物商品",e.address_show),i.abrupt("break",45);case 43:i.next=36;break;case 45:console.log("虚拟商品",e.address_show);case 46:g=uni.getStorageSync("pid"),g&&(e.pid=g,console.log(g),e.is_pt=1,e.get_pid(g)),_=c.info(),e.head={avatarUrl:_.headpic},e.is_kai=uni.getStorageSync("is_kai"),uni.removeStorageSync("is_kai"),console.log("onLoad",e.buy_data),e._load(),e.js_goods_money(),e.$api.http.get("address/get_default_address").then((function(t){e.address=t.data}));case 56:case"end":return i.stop()}}),i)})))()},onShow:function(){var t=this;this.$api.http.get("address/get_default_address").then((function(e){t.address=e.data,console.log("请求地址",e.data)})),this.get_yunfei()},computed:{pay_money:function(){console.log("应付金额",this.goods_money,this.yunfei_money,this.coupon_money);var t=this.goods_money+this.yunfei_money-this.coupon_money;return t<=0?0:t}},methods:{prmSwitch:function(){var t=this;return(0,a.default)(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,t.promise_switch.then((function(t){return t}));case 2:return t.switch_list=e.sent,e.abrupt("return",t.switch_list);case 4:case"end":return e.stop()}}),e)})))()},get_user_vip_status:function(){var t=uni.getStorageSync("my");return console.log(t),!(!t.data||!t.data.vip||1!=t.data.vip.status)},radioChange:function(t){this.kuaidi=t.detail.value,1==this.kuaidi&&(this.obj.drive_type="快递"),2==this.kuaidi&&(this.obj.drive_type="同城"),3==this.kuaidi&&(this.obj.drive_type="自提："+this.switch_list.zt_address)},get_data:function(t){console.log("eeee"),console.log(JSON.stringify(t)),this.obj.other=JSON.stringify(t)},js_goods_money:function(){var t=this.buy_data;console.log(this.get_user_vip_status());var e=0;for(var i in t){var n=t[i];console.log(n),n.discount?e+=n.price*n.num:this.get_user_vip_status()&&n.vip_price&&!n.pt?e+=(n.price-n.vip_price)*n.num:e+=n.price*n.num}console.log("总价",e),this.goods_money=e},check_switch:function(){var t=this;return(0,a.default)(regeneratorRuntime.mark((function e(){var i;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return i=t,e.next=3,t.prmSwitch();case 3:i.invoice_switch=1==t.switch_list.is_invoice;case 4:case"end":return e.stop()}}),e)})))()},_load:function(){var t=this;console.log("load"),this.$api.http.get("coupon/user/get_coupon").then((function(e){t.couponList=e.data})),this.get_yunfei()},jump_invoices:function(){uni.navigateTo({url:"/pages/invoices/invoices"})},get_pid:function(t){var e=this;r.default.getPtItem(t).then((function(t){e.pt_data=t.data,uni.removeStorageSync("pid")}))},check_fx_pro:function(){var t=this.buy_data,e=!1;for(var i in t){var n=t[i];n.is_fx&&(e=!0)}return console.log("是否为分销订单：",e),e},get_yunfei:function(){var t=this;console.log("get_yunfei",this.buy_data);var e=this.buy_data,i=[];for(var n in e){var a=e[n];i[n]={},i[n]["goods_id"]=a.goods_id,i[n]["num"]=a.num,console.log("v:",i)}console.log("get_obj",i),this.$api.http.post("product/get_shipment_price",i).then((function(e){t.yunfei_money=1*e.data}))},toggleMask:function(t){var e=this,i="show"===t?10:300,n="show"===t?1:0;this.maskState=2,setTimeout((function(){e.maskState=n}),i)},to_use:function(t){this.maskState=0,this.coupon_id=this.couponList[t].id?this.couponList[t].id:0;var e=this.couponList[t].reduce;this.coupon_money=e,this.coupon_text="已优惠"+e+"元"},cancel_use:function(){this.maskState=0,this.coupon_id=0,this.coupon_money=0,this.coupon_text="选择优惠券"},numberChange:function(t){this.number=t.number},changePayType:function(t){this.payType=t},set_order_data:function(){var t=this,e=this.obj;for(var i in e.discount=0,t.buy_data){var n=t.buy_data[i];n.discount&&"[]"!=n.discount&&(e.discount=1)}e.total_price=this.pay_money,e.shoping_price=this.yunfei_money,e.coupon_price=this.coupon_money,e.coupon_id=this.coupon_id,e.goods_id=this.buy_data[0].goods_id,e.price=this.buy_data[0].price*this.buy_data[0].num,e.num=this.buy_data[0].num,e.order_from=0,e.payment_type="wx",this.get_user_vip_status()&&(e.price=(this.buy_data[0].price-this.buy_data[0].vip_price)*e.num);var a=uni.getStorageSync("level_one");this.check_fx_pro()&&a&&(e.sfm=a),this.buy_data[0].pt&&(e.is_captain_sign=1),this.is_item=uni.getStorageSync("is_item"),this.is_item&&(e.item_id=this.pt_data.id,uni.removeStorageSync("is_item"));var o=this.buy_data,s={};for(var r in o){var c=o[r],d=c.goods_id;c.sku&&(d=c.goods_id+"-"+c.sku.id,this.sku_id=c.sku.id),s[d]=c,s[d].sku_id=0,c.sku&&(s[d].sku_id=c.sku.id),delete s[d].radio,delete s[d].sku,delete s[d].sku_name,delete s[d].goods_name}return e.json=s,console.log("obj",e),e},check_sub_data:function(){if(!this.address_show||this.address){if(!this.paying){var t="",e=window.navigator.userAgent.toLowerCase();return t="micromessenger"==e.match(/MicroMessenger/i)?"order/create_cart":"order/create",console.log("url:",t),t}}else this.$api.msg("未填写地址")},xy_approve:function(t){this.xieyi=!1,t||uni.navigateBack({})},submit:function(){var t=this;return(0,a.default)(regeneratorRuntime.mark((function e(){var i,n,a,o,s,c;return regeneratorRuntime.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if(0!=t.pay_money){e.next=3;break}return t.$api.msg("价格错误"),e.abrupt("return");case 3:if(1==t.sys_switch.is_form&&t.$refs.child.get_form(),i=uni.getStorageSync("is_item"),uni.removeStorageSync("is_item"),n=t.check_sub_data(),n){e.next=9;break}return e.abrupt("return");case 9:if(a=t.set_order_data(),!(t.switch_list.drive_type>0)||a.drive_type&&""!=a.drive_type){e.next=13;break}return t.$api.msg("请选择配送方式"),e.abrupt("return");case 13:if(console.log("submit:",a),""==t.sku_id?a.sku_id=0:a.sku_id=t.sku_id,o="",1!=t.is_kai){e.next=23;break}return a.is_captain_sign=1,e.next=20,r.default.postPtCreateItem(a).then((function(t){return t}));case 20:o=e.sent,e.next=34;break;case 23:if(1!=i){e.next=31;break}return a.item_id=t.pid,a.is_captain_sign=1,e.next=28,r.default.postPtCreateItems(a).then((function(t){return t}));case 28:o=e.sent,e.next=34;break;case 31:return e.next=33,t.$api.http.post(n,a).then((function(t){return t}));case 33:o=e.sent;case 34:if(t.paying=!0,console.log("创建订单：",o),o.data){e.next=40;break}return t.$api.msg(o.msg),setTimeout((function(){uni.navigateBack({})}),1e3),e.abrupt("return");case 40:t.order_id=o.data,"buy"==t.state?uni.removeStorageSync("buy"):(uni.removeStorageSync("cart"),uni.setStorageSync("cart",t.save_cache)),s=o.data,t.order_id=s,c=window.navigator.userAgent.toLowerCase(),"micromessenger"==c.match(/MicroMessenger/i)?(console.log("进入公众号id"),t.wxPay(t.order_id)):uni.reLaunch({url:"pay?order_num="+t.order_id});case 46:case"end":return e.stop()}}),e)})))()},wxPay:function(t){"undefined"==typeof WeixinJSBridge?document.addEventListener?document.addEventListener("WeixinJSBridgeReady",jsApiCall,!1):document.attachEvent&&(document.attachEvent("WeixinJSBridgeReady",jsApiCall),document.attachEvent("onWeixinJSBridgeReady",jsApiCall)):this.jsApiCall(t)},jsApiCall:function(t){var e=this;console.log("公众号支付"),WeixinJSBridge.invoke("getBrandWCPayRequest",t,(function(t){WeixinJSBridge.log("a:",t.err_msg),"get_brand_wcpay_request:ok"==t.err_msg?e.$api.msg("支付成功!"):"get_brand_wcpay_request:cancel"==t.err_msg?e.$api.msg("取消支付"):e.$api.msg("支付失败"),1!=this.is_kai?setTimeout((function(){uni.redirectTo({url:"/pages/order/order"})}),1e3):uni.navigateTo({url:"../invite/invite?id="+e.pid})}))},pay:function(t){var e=this.order_id;uni.requestPayment({provider:"wxpay",timeStamp:t.timeStamp,nonceStr:t.nonceStr,package:t.package,signType:t.signType,paySign:t.paySign,success:function(t){console.log("success:",t),uni.redirectTo({url:"/pages/user/myorder/myorder?id="+e})},fail:function(t){console.log("fail:"+JSON.stringify(t)),uni.redirectTo({url:"/pages/user/myorder/myorder?id="+e})}})},app_pay:function(t){console.log("app支付"),console.log(this.order_id),console.log("app支付");var e=this.order_id;console.log(t),uni.requestPayment({provider:"wxpay",orderInfo:JSON.stringify(t),success:function(t){console.log("success:",t),uni.redirectTo({url:"/pages/user/myorder/myorder?id="+e})},fail:function(t){console.log("fail:"+JSON.stringify(t)),uni.redirectTo({url:"/pages/user/myorder/myorder?id="+e})}})},get_wn_data:function(){var t=this,e=2;this.$api.http.get("wntable/getbytype",e).then((function(e){t.data_form=e.data,console.log("dataform"),console.log(t.data_form),console.log("dataform")}))},diy_form:function(){this.get_wn_data()}},onPullDownRefresh:function(){setTimeout((function(){uni.stopPullDownRefresh()}),2e3)}};e.default=d},"97d7":function(t,e,i){"use strict";var n=i("6915"),a=i.n(n);a.a},d5ac:function(t,e,i){"use strict";i.r(e);var n=i("801b"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},d779:function(t,e,i){"use strict";i.r(e);var n=i("36f7"),a=i("d5ac");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("97d7");var s,r=i("f0c5"),c=Object(r["a"])(a["default"],n["b"],n["c"],!1,null,"49d30f52",null,!1,n["a"],s);e["default"]=c.exports},ecd3:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */\r\n/* 页面左右间距 */\r\n/* 文字尺寸 */\r\n/*文字颜色*/\r\n/* 边框颜色 */\r\n/* 图片加载中颜色 */\r\n/* 行为相关颜色 */uni-page-body[data-v-49d30f52]{background:#f8f8f8;padding-bottom:%?100?%}.black .container[data-v-49d30f52]{background-color:#000;position:fixed;top:0;opacity:.6;width:100%;height:100%;z-index:999}.t_con[data-v-49d30f52]{background-color:#fff;position:fixed;top:120px;left:10%;width:80%;padding:20px;border-radius:5px;z-index:1999;overflow:hidden}.t_con .t_c_tit[data-v-49d30f52]{font-size:16px;text-align:center;font-weight:600;padding-bottom:10px}.t_con .t_c_con[data-v-49d30f52]{font-size:14px;max-height:280px;min-height:120px;overflow-y:scroll;width:100%;overflow-x:hidden}.t_con .t_c_more[data-v-49d30f52]{text-align:center;color:#868686;padding:15px 0 10px;font-size:14px}.t_con .t_c_more span[data-v-49d30f52]{color:#4b73ce}.t_con .t_c_btn[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between}.t_con .t_c_btn .t_c_btn_01[data-v-49d30f52]{height:35px;line-height:35px;color:#fff;border-radius:20px;text-align:center;width:47%;font-size:14px;margin-top:10px}.t_con .t_c_btn .bg_red[data-v-49d30f52]{background-color:#ff7900}.t_con .t_c_btn .bg_gery[data-v-49d30f52]{background-color:#ccc}.H10[data-v-49d30f52]{height:10px}.ztdz[data-v-49d30f52]{padding:10px 0 10px 20px;color:#777;background-color:#fff;font-size:14px}.tuanzhang[data-v-49d30f52]{background:#fff;padding:3px 10px;display:-webkit-box;display:-webkit-flex;display:flex;font-size:12px}.tuanzhang .tz-l[data-v-49d30f52]{width:30px}.tuanzhang .tz-l uni-input[data-v-49d30f52]{border:1px solid #000}.tuanzhang .tz-m[data-v-49d30f52]{-webkit-box-flex:1;-webkit-flex-grow:1;flex-grow:1;color:#ff8d42;padding-top:2px}.tuanzhang .tz-r[data-v-49d30f52]{padding-top:3px}.jr[data-v-49d30f52]{padding:10px;font-size:14px}.jr .jr_tit[data-v-49d30f52]{font-weight:600;text-align:center;padding-bottom:10px}.jr .jr_img[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.jr .jr_img .img_01[data-v-49d30f52]{position:relative;border-radius:50%;margin:0 7px;width:44px;height:44px}.jr .jr_img .img_01 img[data-v-49d30f52]{width:40px;height:40px;border-radius:50%}.jr .jr_img .img_01 .zhicheng[data-v-49d30f52]{position:absolute;bottom:-5px;left:0;background-color:#e93b3d;color:#fff;font-size:12px;border-radius:10px;width:40px;text-align:center}.jr .jr_img .img_01_border[data-v-49d30f52]{border:2px solid #e93b3d}.jr .jr_img .img_01_borderx[data-v-49d30f52]{border:2px dashed #e6e6e6;text-align:center;line-height:44px;color:#e6e6e6}.address-section[data-v-49d30f52]{padding:%?30?% 0;background:#fff;position:relative}.address-section .order-content[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.address-section .icon-shouhuodizhi[data-v-49d30f52]{-webkit-flex-shrink:0;flex-shrink:0;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;width:%?90?%;color:#888;font-size:%?44?%}.address-section .cen[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-flex:1;-webkit-flex:1;flex:1;font-size:%?28?%;color:#303133}.address-section .name[data-v-49d30f52]{font-size:%?34?%;margin-right:%?24?%}.address-section .address[data-v-49d30f52]{margin-top:%?16?%;margin-right:%?20?%;color:#909399}.address-section .icon-you[data-v-49d30f52]{font-size:%?32?%;color:#909399;margin-right:%?30?%}.address-section .a-bg[data-v-49d30f52]{position:absolute;left:0;bottom:0;display:block;width:100%;height:%?5?%}.goods-section[data-v-49d30f52]{margin-top:%?16?%;background:#fff;padding-bottom:1px;padding-top:10px}.goods-section .g-header[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;height:%?84?%;padding:0 %?30?%;position:relative}.goods-section .logo[data-v-49d30f52]{display:block;width:%?50?%;height:%?50?%;border-radius:100px}.goods-section .name[data-v-49d30f52]{font-size:%?30?%;color:#606266;margin-left:%?24?%}.goods-section .g-item[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;margin:%?20?% %?30?%}.goods-section .g-item uni-image[data-v-49d30f52]{-webkit-flex-shrink:0;flex-shrink:0;display:block;width:%?140?%;height:%?140?%;border-radius:%?4?%}.goods-section .g-item .right[data-v-49d30f52]{-webkit-box-flex:1;-webkit-flex:1;flex:1;padding-left:%?24?%;overflow:hidden}.goods-section .g-item .title[data-v-49d30f52]{font-size:%?30?%;color:#303133}.goods-section .g-item .spec[data-v-49d30f52]{font-size:%?26?%;color:#909399}.goods-section .g-item .price-box[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;font-size:%?32?%;color:#303133;padding-top:%?10?%}.goods-section .g-item .price-box .price[data-v-49d30f52]{margin-bottom:%?4?%}.goods-section .g-item .price-box .number[data-v-49d30f52]{font-size:%?26?%;color:#606266;margin-left:%?20?%}.goods-section .g-item .step-box[data-v-49d30f52]{position:relative}.yt-list[data-v-49d30f52]{margin-top:%?16?%;background:#fff}.yt-list-cell[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding:%?10?% %?30?% %?10?% %?40?%;line-height:%?70?%;position:relative}.yt-list-cell.cell-hover[data-v-49d30f52]{background:#fafafa}.yt-list-cell.b-b[data-v-49d30f52]:after{left:%?30?%}.yt-list-cell .cell-icon[data-v-49d30f52]{height:%?32?%;width:%?32?%;font-size:%?22?%;color:#fff;text-align:center;line-height:%?32?%;background:#f85e52;border-radius:%?4?%;margin-right:%?12?%}.yt-list-cell .cell-icon.hb[data-v-49d30f52]{background:#ffaa0e}.yt-list-cell .cell-icon.lpk[data-v-49d30f52]{background:#3ab54a}.yt-list-cell .cell-more[data-v-49d30f52]{-webkit-align-self:center;align-self:center;font-size:%?24?%;color:#909399;margin-left:%?8?%;margin-right:%?-10?%}.yt-list-cell .cell-tit[data-v-49d30f52]{-webkit-box-flex:1;-webkit-flex:1;flex:1;font-size:%?26?%;color:#909399;margin-right:%?10?%}.yt-list-cell .cell-tip[data-v-49d30f52]{font-size:%?26?%;color:#303133}.yt-list-cell .cell-tip.disabled[data-v-49d30f52]{color:#909399}.yt-list-cell .cell-tip.active[data-v-49d30f52]{color:#fa436a}.yt-list-cell .cell-tip.red[data-v-49d30f52]{color:#fa436a}.yt-list-cell.desc-cell .cell-tit[data-v-49d30f52]{max-width:%?90?%}.yt-list-cell .desc[data-v-49d30f52]{-webkit-box-flex:1;-webkit-flex:1;flex:1;font-size:%?28?%;color:#303133}\r\n/* 支付列表 */.pay-list[data-v-49d30f52]{padding-left:%?40?%;margin-top:%?16?%;background:#fff}.pay-list .pay-item[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding-right:%?20?%;line-height:1;height:%?110?%;position:relative}.pay-list .icon-weixinzhifu[data-v-49d30f52]{width:%?80?%;font-size:%?40?%;color:#6bcc03}.pay-list .icon-alipay[data-v-49d30f52]{width:%?80?%;font-size:%?40?%;color:#06b4fd}.pay-list .icon-xuanzhong2[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;width:%?60?%;height:%?60?%;font-size:%?40?%;color:#fa436a}.pay-list .tit[data-v-49d30f52]{font-size:%?32?%;color:#303133;-webkit-box-flex:1;-webkit-flex:1;flex:1}.footer[data-v-49d30f52]{position:fixed;left:0;bottom:0;z-index:995;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;width:100%;height:%?90?%;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;font-size:%?30?%;background-color:#fff;z-index:998;color:#606266;box-shadow:0 -1px 5px rgba(0,0,0,.1)}.footer .price-content[data-v-49d30f52]{padding-left:%?30?%}.footer .price-tip[data-v-49d30f52]{color:#fa436a;margin-left:%?8?%}.footer .price[data-v-49d30f52]{font-size:%?36?%;color:#fa436a}.footer .submit[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;width:%?280?%;height:100%;color:#fff;font-size:%?32?%;background-color:#fa436a}\r\n/* 优惠券面板 */.mask[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:end;-webkit-align-items:flex-end;align-items:flex-end;position:fixed;left:0;top:var(--window-top);bottom:0;width:100%;background:transparent;z-index:9995;-webkit-transition:.3s;transition:.3s}.mask .mask-content[data-v-49d30f52]{width:100%;min-height:30vh;max-height:70vh;padding:20px 0 60px;background:#f3f3f3;-webkit-transform:translateY(100%);transform:translateY(100%);-webkit-transition:.3s;transition:.3s;overflow-y:scroll}.mask .btn[data-v-49d30f52]{position:fixed;bottom:0;width:95%;text-align:center;border:1px solid #fa436a;background-color:#fa436a;color:#fff;border-radius:20px;margin:10px;padding:5px}.mask.none[data-v-49d30f52]{display:none}.mask.show[data-v-49d30f52]{background:rgba(0,0,0,.4)}.mask.show .mask-content[data-v-49d30f52]{-webkit-transform:translateY(0);transform:translateY(0)}.scroll[data-v-49d30f52]{max-height:55vh}\r\n/* 优惠券列表 */.coupon-item[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;margin:%?3?% %?24?%;background:#fff}.coupon-item .con[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;position:relative;height:%?120?%;padding:0 %?30?%\r\n  /* &:after {\r\n\t\t\tposition: absolute;\r\n\t\t\tleft: 0;\r\n\t\t\tbottom: 10;\r\n\t\t\tcontent: \'\';\r\n\t\t\twidth: 100%;\r\n\t\t\theight: 0;\r\n\t\t\tborder-bottom: 1px dashed #f3f3f3;\r\n\t\t\ttransform: scaleY(50%);\r\n\t\t} */}.coupon-item .left[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-flex:1;-webkit-flex:1;flex:1;overflow:hidden;height:%?100?%}.coupon-item .title[data-v-49d30f52]{font-size:%?32?%;color:#303133;margin-bottom:%?10?%}.coupon-item .time[data-v-49d30f52]{font-size:%?24?%;color:#909399}.coupon-item .right[data-v-49d30f52]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center;font-size:%?26?%;color:#606266;height:%?100?%}.coupon-item .price[data-v-49d30f52]{font-size:%?44?%;color:#fa436a}.coupon-item .price[data-v-49d30f52]:before{content:"￥";font-size:%?34?%}.coupon-item .tips[data-v-49d30f52]{font-size:%?24?%;color:#909399;line-height:%?60?%;white-space:nowrap;padding-left:%?30?%}.coupon-item .tips2[data-v-49d30f52]{font-size:%?24?%;color:#909399;line-height:%?50?%;height:%?50?%;text-align:center;margin:%?5?% 10px 0 0;width:80px;border:1px solid #fa436a;background-color:#fa436a;color:#fff;border-radius:25px}.coupon-item .tips3[data-v-49d30f52]{font-size:%?24?%;color:#909399;line-height:%?50?%;height:%?50?%;text-align:center;margin:%?5?% 10px 0 0;width:80px;border:1px solid silver;background-color:silver;color:#fff;border-radius:25px}.coupon-item .circle[data-v-49d30f52]{position:absolute;left:%?-6?%;bottom:%?-10?%;z-index:10;width:%?20?%;height:%?20?%;background:#f3f3f3;border-radius:100px}.coupon-item .circle.r[data-v-49d30f52]{left:auto;right:%?-6?%}body.?%PAGE?%[data-v-49d30f52]{background:#f8f8f8}',""]),t.exports=e}}]);