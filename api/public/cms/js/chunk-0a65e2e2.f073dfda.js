(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0a65e2e2"],{"10d3":function(t,e,a){"use strict";a("5b38")},"16e4":function(t,e,a){},"2a91":function(t,e,a){},"5b38":function(t,e,a){},6651:function(t,e,a){"use strict";a("16e4")},"6e47":function(t,e,a){"use strict";a.r(e);var l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("el-container",[a("el-aside",{attrs:{width:"200px"}},[a("NavTo")],1),a("el-container",[a("el-header",{staticStyle:{"border-bottom":"1px solid #d0d0d0"}},[a("Header")],1),a("el-main",{staticStyle:{"background-color":"#F3F3F3"}},[a("el-row",[a("transition",{attrs:{appear:"","appear-active-class":"animated fadeInLeft"}},[a("div",{staticClass:"set"},[a("el-tabs",{on:{"tab-click":t.get_tab_name},model:{value:t.activeName,callback:function(e){t.activeName=e},expression:"activeName"}},[a("el-tab-pane",{attrs:{label:"基础设置",name:"1"}},[a("set-a",{attrs:{datas:t.abc}})],1),a("el-tab-pane",{attrs:{label:"微信",name:"2"}},[a("set-d",{attrs:{list:t.list,type:t.type},on:{submit:t.onSubmit}})],1),a("el-tab-pane",{attrs:{label:"支付宝",name:"3"}},[a("set-d",{attrs:{list:t.list,type:t.type},on:{submit:t.onSubmit}})],1),a("el-tab-pane",{attrs:{label:"短信",name:"4"}},[a("set-d",{attrs:{list:t.list,type:t.type},on:{submit:t.onSubmit}})],1),a("el-tab-pane",{attrs:{label:"物流",name:"5"}},[a("set-d",{attrs:{list:t.list,type:t.type},on:{submit:t.onSubmit}})],1),a("el-tab-pane",{attrs:{label:"上传配置",name:"6"}},[a("set-d",{attrs:{list:t.list,type:t.type},on:{submit:t.onSubmit}})],1),a("el-tab-pane",{attrs:{label:"积分",name:"7"}},[a("set-d",{attrs:{list:t.list,type:t.type},on:{submit:t.onSubmit}})],1),a("el-tab-pane",{attrs:{label:"水印",name:"9"}},[a("set-d",{attrs:{list:t.list,type:t.type},on:{submit:t.onSubmit}})],1)],1)],1)])],1)],1)],1)],1)],1)},s=[],o=(a("7f7f"),function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"set"},[a("el-form",{ref:"form",attrs:{"label-width":"200px"}},[a("el-row",[t._l(t.list,(function(e,l){return[1==e.switch?a("el-col",{attrs:{span:6}},[a("el-form-item",{attrs:{label:e.desc}},[[a("el-switch",{attrs:{"active-value":"1","inactive-value":"0","active-color":"#13ce66","inactive-color":"#ff4949"},model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}})]],2)],1):t._e()]}))],2),t._l(t.list,(function(e,l){return[2==e.switch?a("el-row",{staticClass:"inp"},[a("el-form-item",{attrs:{label:e.desc}},["fx_status"==e.key?[a("el-radio-group",{model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}},[a("el-radio",{attrs:{label:"0"}},[t._v("关闭分销")]),a("el-tooltip",{attrs:{placement:"top"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v("需指定代理人才能"),a("br"),t._v("进行分销")]),a("el-radio",{attrs:{label:"1"}},[t._v("代理商分销")])],1),a("el-tooltip",{attrs:{placement:"right"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v("每个人都可以"),a("br"),t._v("进行分销")]),a("el-radio",{attrs:{label:"2"}},[t._v("人人分销")])],1)],1)]:t._e(),"merge_mode"==e.key?[a("el-radio-group",{model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}},[a("el-tooltip",{attrs:{placement:"bottom"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v("可通过微信号绑定公"),a("br"),t._v("众号、小程序和APP")]),a("el-radio",{attrs:{label:"1"}},[t._v("微信开放平台关联")])],1),a("el-tooltip",{attrs:{placement:"bottom"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v("可通过手机号绑定公"),a("br"),t._v("众号、小程序和APP")]),a("el-radio",{attrs:{label:"2"}},[t._v("手机关联")])],1),a("el-tooltip",{attrs:{placement:"right"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v("适用于不通过微信来绑定"),a("br"),t._v("公众号、小程序和APP")]),a("el-radio",{attrs:{label:"3"}},[t._v("手机登录")])],1)],1)]:t._e(),"fx_royalty"==e.key?[a("el-radio-group",{model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}},[a("el-tooltip",{attrs:{placement:"bottom"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v("提现需手动进行申请")]),a("el-radio",{attrs:{label:"0"}},[t._v("手动提现")])],1),a("el-tooltip",{attrs:{placement:"right"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v("企业自动打款"),a("br"),t._v("(需开启微信公众平台权限)")]),a("el-radio",{attrs:{label:"1"}},[t._v("自动打款")])],1)],1)]:t._e(),"product_classification"==e.key?[a("el-radio-group",{model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}},[a("el-tooltip",{attrs:{placement:"bottom"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v("直接从分类页就能看见产品")]),a("el-radio",{attrs:{label:"0"}},[t._v("分类页-产品")])],1),a("el-tooltip",{attrs:{placement:"right"}},[a("div",{attrs:{slot:"content"},slot:"content"},[t._v("需点击二级类才能看见产品"),a("br")]),a("el-radio",{attrs:{label:"1"}},[t._v("二级类")])],1)],1)]:t._e(),"drive_type"==e.key?[a("el-radio-group",{model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}},[a("el-radio",{attrs:{label:"0"}},[t._v("快递")]),a("el-radio",{attrs:{label:"1"}},[t._v("快递+自提")]),a("el-radio",{attrs:{label:"2"}},[t._v("快递+同城")]),a("el-radio",{attrs:{label:"3"}},[t._v("所有")])],1)]:t._e(),"login_mode"==e.key?[a("el-radio-group",{model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}},[a("el-radio",{attrs:{label:"0"}},[t._v("开放")]),a("el-radio",{attrs:{label:"1"}},[t._v("审核")]),a("el-radio",{attrs:{label:"2"}},[t._v("邀请")])],1)]:t._e()],2)],1):t._e()]})),t._l(t.list,(function(e,l){return[0==e.switch?a("el-row",{staticClass:"inp"},[a("el-form-item",{attrs:{label:e.desc}},[a("el-input",{model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}})],1)],1):t._e()]})),t._l(t.list,(function(e,l){return[3==e.switch?a("el-row",{staticClass:"inp"},[a("el-form-item",{attrs:{label:e.desc}},[a("el-input",{attrs:{type:"textarea"},model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}})],1)],1):t._e()]})),a("el-form-item",{staticStyle:{width:"80%"}},[a("el-button",{attrs:{type:"primary"},on:{click:t.onSubmit}},[t._v("提交修改")])],1)],2)],1)}),i=[],n={name:"Set-a",data:function(){return{fx_radio:"",tx_radio:"",list:[],value1:!0,value2:!0,value:""}},methods:{onSubmit:function(){var t=this;this.http.post_show("cms/edit_config",t.list).then((function(e){t.$message({showClose:!0,message:"修改成功",type:"success"})}))},post_config:function(){var t=this;this.http.post("cms/get_config",{type:1}).then((function(e){t.list=e.data}))}},mounted:function(){this.post_config()}},r=n,c=(a("84b6"),a("2877")),u=Object(c["a"])(r,o,i,!1,null,null,null),m=u.exports,v=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{},[a("el-form",{ref:"form",attrs:{"label-width":"200px"}},[t._l(t.list,(function(e,l){return a("el-form-item",{staticStyle:{width:"70%"},attrs:{label:e.desc}},[1==e.switch?[a("el-switch",{attrs:{"active-color":"#13ce66","inactive-color":"#ff4949","active-value":"1","inactive-value":"0"},model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}})]:[a("el-input",{model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"item.value"}})]],2)})),a("el-form-item",{staticStyle:{width:"80%"}},[a("el-button",{attrs:{type:"primary"},on:{click:t.onSubmit}},[t._v("提交修改")])],1)],2)],1)},p=[],d={name:"Set-d",data:function(){return{}},props:["list"],methods:{onSubmit:function(){this.$emit("submit",this.list)},mounted:function(){this.post_config()}}},b=d,f=(a("10d3"),Object(c["a"])(b,v,p,!1,null,null,null)),_=f.exports,h=a("15fc"),y=a("71c2"),g={name:"Set",data:function(){return{activeName:"1",abc:[],auth:!1,list:[],type:""}},components:{SetA:m,SetD:_,NavTo:h["a"],Header:y["a"]},methods:{get_tab_name:function(t){console.log(t),this.type=t.name,"1"!=t.name&&this.post_config(t.name)},check_auth:function(t){var e=localStorage.getItem("oauth");e.indexOf(t)<0?this.$message({message:"无权限",type:"none"}):this.auth=!0},onSubmit:function(t){var e=this;this.http.post_show("cms/edit_config",t).then((function(t){e.$message({showClose:!0,message:"添加成功",type:"success"})}))},post_config:function(t){var e=this;this.http.post("cms/get_config",{type:1*t}).then((function(t){e.list=t.data}))}},mounted:function(){}},w=g,k=(a("6651"),Object(c["a"])(w,l,s,!1,null,"376f28dc",null));e["default"]=k.exports},"7f7f":function(t,e,a){var l=a("86cc").f,s=Function.prototype,o=/^\s*function ([^ (]*)/,i="name";i in s||a("9e1e")&&l(s,i,{configurable:!0,get:function(){try{return(""+this).match(o)[1]}catch(t){return""}}})},"84b6":function(t,e,a){"use strict";a("2a91")}}]);