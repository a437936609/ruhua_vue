(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-address-addressManage"],{"0204":function(t,e,n){"use strict";var i=n("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i(n("ade3")),o=i(n("fbe0")),s={data:function(){var t;return{type:"",del_id:"",form:(t={name:"",mobile:"",city:"",detail:"",province:""},(0,a.default)(t,"city",""),(0,a.default)(t,"county",""),(0,a.default)(t,"detail",""),(0,a.default)(t,"areacode",""),t),tabIndex:0,mode:"range",tabList:[{mode:"region",name:""}],resultInfo:{result:"请选择地区"}}},onLoad:function(t){if(console.log(t),"edit"==t.type){var e=uni.getStorageSync("edit_data");this.form=e,this.type="edit",this.del_id=e.id,this.resultInfo.result=e.province+e.city+e.county}else"add"==t.type&&(this.type="add");console.log(this.form)},components:{wPicker:o.default},onShow:function(){},methods:{del:function(){var t=this;this.$api.http.put("address/del_address",{id:this.del_id}).then((function(e){t.$api.msg("删除成功"),setTimeout((function(){uni.navigateBack()}),1e3)}))},toggleTab:function(){this.$refs["region"].show()},onConfirm:function(t){console.log(t),this.resultInfo=t,this.form.province=t.checkArr[0],this.form.city=t.checkArr[1],this.form.county=t.checkArr[2],console.log(this.form)},sub_edit:function(t){var e=this,n=this.form;this.$api.http.post("address/edit_address",n).then((function(t){e.$api.msg("修改成功"),setTimeout((function(){uni.navigateBack()}),1e3)}))},sub_add:function(){var t=this,e=this.form;11==e.mobile.length?this.$api.http.post("address/add_address",e).then((function(e){200==e.status?(t.$api.msg("添加成功!"),setTimeout((function(){uni.navigateBack()}),1e3)):t.$api.msg(e.msg)})):this.$api.msg("请输入正确的的手机号")}}};e.default=s},"090c":function(t,e,n){"use strict";n.r(e);var i=n("0204"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},"69f1":function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */\r\n/* 页面左右间距 */\r\n/* 文字尺寸 */\r\n/*文字颜色*/\r\n/* 边框颜色 */\r\n/* 图片加载中颜色 */\r\n/* 行为相关颜色 */uni-page-body[data-v-bfd039ec]{background:#f8f8f8;padding-top:%?16?%}.row[data-v-bfd039ec]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;position:relative;padding:0 %?30?%;height:%?110?%;background:#fff}.row .tit[data-v-bfd039ec]{-webkit-flex-shrink:0;flex-shrink:0;width:%?120?%;font-size:%?30?%;color:#303133}.row .input[data-v-bfd039ec]{-webkit-box-flex:1;-webkit-flex:1;flex:1;font-size:%?30?%;color:#303133}.row .icon-shouhuodizhi[data-v-bfd039ec]{font-size:%?36?%;color:#909399}.default-row[data-v-bfd039ec]{margin-top:%?16?%}.default-row .tit[data-v-bfd039ec]{-webkit-box-flex:1;-webkit-flex:1;flex:1}.default-row uni-switch[data-v-bfd039ec]{-webkit-transform:translateX(%?16?%) scale(.9);transform:translateX(%?16?%) scale(.9)}.btn[data-v-bfd039ec]{display:-webkit-box;display:-webkit-flex;display:flex}.btn .add-btn[data-v-bfd039ec]{width:47%;height:%?80?%;margin:%?60?% auto;font-size:%?32?%;color:#fff;background-color:#fa436a;border-radius:%?10?%;box-shadow:1px 2px 5px rgba(219,63,96,.4)}.tab[data-v-bfd039ec]{color:red;padding:%?20?% 0;font-size:%?32?%}.tab.active[data-v-bfd039ec]{color:#999}.result[data-v-bfd039ec]{color:#999;margin-right:5%;width:100%;font-size:%?32?%}body.?%PAGE?%[data-v-bfd039ec]{background:#f8f8f8}',""]),t.exports=e},"97d0":function(t,e,n){"use strict";n.r(e);var i=n("9899"),a=n("090c");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("b40c");var s,r=n("f0c5"),d=Object(r["a"])(a["default"],i["b"],i["c"],!1,null,"bfd039ec",null,!1,i["a"],s);e["default"]=d.exports},9899:function(t,e,n){"use strict";n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var i={wPicker:n("fbe0").default},a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"content"},[n("v-uni-view",{staticClass:"row b-b"},[n("v-uni-text",{staticClass:"tit"},[t._v("联系人")]),n("v-uni-input",{staticClass:"input",attrs:{type:"text",placeholder:"收货人姓名","placeholder-class":"placeholder"},model:{value:t.form.name,callback:function(e){t.$set(t.form,"name",e)},expression:"form.name"}})],1),n("v-uni-view",{staticClass:"row b-b"},[n("v-uni-text",{staticClass:"tit"},[t._v("手机号")]),n("v-uni-input",{staticClass:"input",attrs:{type:"number",placeholder:"收货人手机号码","placeholder-class":"placeholder"},model:{value:t.form.mobile,callback:function(e){t.$set(t.form,"mobile",e)},expression:"form.mobile"}})],1),n("v-uni-view",{staticClass:"row b-b"},[n("v-uni-text",{staticClass:"tit"},[t._v("地区")]),n("v-uni-view",{staticClass:"result",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.toggleTab.apply(void 0,arguments)}}},[t._v(t._s(t.resultInfo.result))]),n("w-picker",{ref:"region",attrs:{mode:"region",defaultVal:["北京市","市辖区","东城区"]},on:{confirm:function(e){arguments[0]=e=t.$handleEvent(e),t.onConfirm.apply(void 0,arguments)}}})],1),n("v-uni-view",{staticClass:"row b-b"},[n("v-uni-text",{staticClass:"tit"},[t._v("详细")]),n("v-uni-input",{staticClass:"input",attrs:{type:"text",placeholder:"楼号、门牌","placeholder-class":"placeholder"},model:{value:t.form.detail,callback:function(e){t.$set(t.form,"detail",e)},expression:"form.detail"}})],1),n("v-uni-view",{staticClass:"btn"},["edit"==t.type?n("v-uni-button",{staticClass:"add-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.del.apply(void 0,arguments)}}},[t._v("删除")]):t._e(),"add"==t.type?n("v-uni-button",{staticClass:"add-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.sub_add.apply(void 0,arguments)}}},[t._v("提交")]):t._e(),"edit"==t.type?n("v-uni-button",{staticClass:"add-btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.sub_edit.apply(void 0,arguments)}}},[t._v("提交")]):t._e()],1)],1)},o=[]},b40c:function(t,e,n){"use strict";var i=n("e575"),a=n.n(i);a.a},e575:function(t,e,n){var i=n("69f1");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("d86288e6",i,!0,{sourceMap:!1,shadowMode:!1})}}]);