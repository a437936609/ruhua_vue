(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["means-cms-user-mingxi-mingxi"],{"30df":function(i,t,e){"use strict";e.r(t);var n=e("9dd6"),a=e("495b");for(var o in a)"default"!==o&&function(i){e.d(t,i,(function(){return a[i]}))}(o);e("abe8");var s,c=e("f0c5"),d=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"664ec8f4",null,!1,n["a"],s);t["default"]=d.exports},"495b":function(i,t,e){"use strict";e.r(t);var n=e("9148"),a=e.n(n);for(var o in n)"default"!==o&&function(i){e.d(t,i,(function(){return n[i]}))}(o);t["default"]=a.a},"806f":function(i,t,e){var n=e("dcda");"string"===typeof n&&(n=[[i.i,n,""]]),n.locals&&(i.exports=n.locals);var a=e("4f06").default;a("12f5283b",n,!0,{sourceMap:!1,shadowMode:!1})},9148:function(i,t,e){"use strict";var n=e("4ea4");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a=n(e("d23c")),o={data:function(){return{list:[],num:0}},onLoad:function(){this._load()},components:{uniIcon:a.default},methods:{_load:function(){this.list=this.$api.json_cms.get_tx_log},jump_cash:function(){uni.navigateTo({url:"/means/cms/user/fenxiao/tixian/tixian"})},jump:function(i){uni.navigateTo({url:"/means/cms/user/fenxiao/success/success?id="+i})},change:function(i){this.num=i,console.log(i)}},onPullDownRefresh:function(){this._load(),setTimeout((function(){uni.stopPullDownRefresh()}),2e3)}};t.default=o},"9dd6":function(i,t,e){"use strict";var n;e.d(t,"b",(function(){return a})),e.d(t,"c",(function(){return o})),e.d(t,"a",(function(){return n}));var a=function(){var i=this,t=i.$createElement,e=i._self._c||t;return e("v-uni-view",{staticClass:"mingxi"},[e("v-uni-view",{staticStyle:{display:"flex","justify-content":"flex-end","margin-bottom":"10px"}},[e("v-uni-view",{staticClass:"head_btn",on:{click:function(t){arguments[0]=t=i.$handleEvent(t),i.jump_cash.apply(void 0,arguments)}}},[i._v("去提现")])],1),e("v-uni-view",{staticClass:"ticheng"},[i._l(i.list,(function(t,n){return[e("li",{key:n+"_0",staticClass:"tc",on:{click:function(e){arguments[0]=e=i.$handleEvent(e),i.jump(t.id)}}},[e("v-uni-view",{staticClass:"tc_l"},[e("span",[i._v("代理提成-"+i._s(t.card.bank_num.substr(t.card.bank_num.length-4)))]),e("br"),i._v(i._s(t.create_time))]),e("v-uni-view",{staticClass:"tc_2"},[i._v("+"+i._s(t.money)),e("br"),0==t.status?e("span",[i._v("提现中")]):i._e(),1==t.status?e("span",[i._v("已完成")]):i._e()])],1)]}))],2)],1)},o=[]},abe8:function(i,t,e){"use strict";var n=e("806f"),a=e.n(n);a.a},dcda:function(i,t,e){var n=e("24fb");t=n(!1),t.push([i.i,".mingxi .head[data-v-664ec8f4]{display:-webkit-box;display:-webkit-flex;display:flex;margin:10px 0}.mingxi .head_l[data-v-664ec8f4]{display:-webkit-box;display:-webkit-flex;display:flex;width:85%;-webkit-justify-content:space-around;justify-content:space-around}.mingxi .head_r[data-v-664ec8f4]{width:15%;text-align:center}.mingxi .head_l_1[data-v-664ec8f4]{border:1px solid #f2f2f2;padding:0 15px;line-height:25px}.mingxi .head_btn[data-v-664ec8f4]{margin:10px 20px 0;border:1px solid #f2f2f2;padding:0 15px;line-height:25px}.mingxi .ling[data-v-664ec8f4]{color:#e1461d;border:1px solid #e1461d;padding:0 15px;line-height:25px}.mingxi .shouyi[data-v-664ec8f4]{border-top:1px solid #ebebeb;border-bottom:1px solid #ebebeb;background-color:#fafafa;display:-webkit-box;display:-webkit-flex;display:flex;height:30px;line-height:30px;padding:3px 10px;margin-top:15px}.mingxi .sy_l[data-v-664ec8f4]{width:50%}.mingxi .sy_l span[data-v-664ec8f4]{font-weight:700}.mingxi .ticheng li[data-v-664ec8f4]:nth-of-type(odd){background-color:#eee}.mingxi .ticheng li[data-v-664ec8f4]:nth-of-type(even){background-color:#fff}.mingxi .tc[data-v-664ec8f4]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;padding:10px;line-height:25px;font-size:14px}.mingxi .tc_l[data-v-664ec8f4]{color:#9a9a9a}.mingxi .tc_l span[data-v-664ec8f4]{font-size:14px;font-weight:700;color:#000}.mingxi .tc_2[data-v-664ec8f4]{color:#e1461d}.mingxi .tc_2 span[data-v-664ec8f4]{color:#9a9a9a}",""]),i.exports=t}}]);