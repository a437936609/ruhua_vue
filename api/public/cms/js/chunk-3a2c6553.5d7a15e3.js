(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-3a2c6553"],{1961:function(t,e,n){},b843:function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"user"},[n("el-container",[n("el-aside",{attrs:{width:"200px"}},[n("NavTo")],1),n("el-container",{staticStyle:{"background-color":"#F3F3F3"}},[n("el-header",{staticStyle:{"border-bottom":"1px solid #d0d0d0","background-color":"#FFFFFF"}},[n("Header")],1),n("transition",{attrs:{appear:"","appear-active-class":"animated fadeInLeft"}},[n("el-main",[n("el-collapse",{staticStyle:{padding:"0 10px","background-color":"#fff","margin-bottom":"15px"},on:{change:t.handleChange},model:{value:t.activeNames,callback:function(e){t.activeNames=e},expression:"activeNames"}},[n("el-collapse-item",{attrs:{title:"用户",name:"1"}},[n("div",{staticClass:"user_sear"},[n("div",{staticClass:"sear_01"},[n("div",{staticClass:"sear_01_01"},[n("div",{staticClass:"sear_01_01_1"},[t._v("昵称：")]),n("el-input",{attrs:{placeholder:"请输入昵称"},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.search(t.nickname)}},model:{value:t.nickname,callback:function(e){t.nickname=e},expression:"nickname"}}),n("div",{staticStyle:{"margin-left":"20px"}},[n("el-button",{attrs:{type:"primary"},on:{click:function(e){return t.search(t.nickname)}}},[n("i",{staticClass:"el-icon-search"}),t._v("搜索")])],1),n("div",{staticStyle:{"margin-left":"20px"}},[n("el-button",{attrs:{type:"primary"},on:{click:t.reset}},[t._v("重置")])],1)],1)]),n("div",{staticClass:"sear_01"})])])],1),n("div",{staticClass:"article"},[n("div",{staticStyle:{height:"20px"}},[t._v(" ")]),[n("el-table",{staticStyle:{width:"100%"},attrs:{data:t.list,border:""}},[n("el-table-column",{attrs:{type:"index",label:"序号",width:"50px"}}),n("el-table-column",{attrs:{label:"昵称"},scopedSlots:t._u([{key:"default",fn:function(e){return[t._v("\n\t\t\t\t\t\t\t\t\t\t"+t._s(e.row.nickname?e.row.nickname:"未授权")+"\n\t\t\t\t\t\t\t\t\t")]}}])}),n("el-table-column",{attrs:{prop:"create_time",label:"创建时间"}}),n("el-table-column",{attrs:{prop:"points",label:"积分"}}),n("el-table-column",{attrs:{prop:"vip",label:"是否会员"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("a",{staticStyle:{visibility:"hidden"}},[t._v(t._s(1==e.row.vip?e.row.vip=!0:e.row.vip=!1))]),n("el-switch",{attrs:{"active-color":"#409EFF","inactive-color":"#DCDFE6"},on:{change:function(n){return t.set_hot(e.row)}},model:{value:e.row.vip,callback:function(n){t.$set(e.row,"vip",n)},expression:"scope.row.vip"}})]}}])}),n("el-table-column",{attrs:{prop:"web_auth_id",label:"前端管理权限"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.web_auth_id?[t._v("管理员")]:t._e(),0==e.row.web_auth_id?[t._v("普通用户")]:t._e(),2==e.row.web_auth_id?[t._v("员工")]:t._e()]}}])}),n("el-table-column",{attrs:{prop:"start",label:"审核"},scopedSlots:t._u([{key:"default",fn:function(e){return[n("el-switch",{attrs:{"active-color":"#13ce66","inactive-color":"#ff4949","active-value":2,"inactive-value":1},on:{change:function(n){return t.charge_user(e.row.id,e.row.start)}},model:{value:e.row.start,callback:function(n){t.$set(e.row,"start",n)},expression:"scope.row.start"}})]}}])}),n("el-table-column",{attrs:{prop:"operation",label:"操作",width:"300px"},scopedSlots:t._u([{key:"default",fn:function(e){return[1==e.row.web_auth_id?[n("el-button",{attrs:{type:"info",size:"small"},on:{click:function(n){return t.open(e.row.id,0)}}},[t._v("设为普通用户")]),n("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(n){return t.open(e.row.id,2)}}},[t._v("设为员工")])]:t._e(),0==e.row.web_auth_id?[n("el-button",{attrs:{type:"success",size:"small"},on:{click:function(n){return t.open(e.row.id,1)}}},[t._v("设为管理员")]),n("el-button",{attrs:{type:"primary",size:"small"},on:{click:function(n){return t.open(e.row.id,2)}}},[t._v("设为员工")])]:t._e(),2==e.row.web_auth_id?[n("el-button",{attrs:{type:"success",size:"small"},on:{click:function(n){return t.open(e.row.id,1)}}},[t._v("设为管理员")]),n("el-button",{attrs:{type:"info",size:"small"},on:{click:function(n){return t.open(e.row.id,0)}}},[t._v("设为普通用户")])]:t._e()]}}])}),n("strong")],1)]],2),n("el-pagination",{staticStyle:{"margin-top":"50px"},attrs:{background:"",layout:"prev, pager, next",total:t.total,"page-size":t.size},on:{"current-change":t.jump_page}})],1)],1)],1)],1)],1)},i=[],o=(n("5c96"),n("a49b"),n("15fc")),l=n("71c2"),s={data:function(){return{activeNames:["1"],nickname:"",dialogImageUrl:"",oid:0,list:[],all:"",size:10,total:""}},components:{Header:l["a"],NavTo:o["a"]},mounted:function(){this._load()},methods:{charge_user:function(t,e){var n=this;this.http.put_show("/user/admin/trial_user?uid="+t+"&start="+e).then((function(t){n._load()}))},_load:function(){var t=this;this.http.get("user/admin/get_all_user").then((function(e){t.list=e.data,t.all=e.data,t.total=e.data.length,t.list=e.data.slice(0,t.size)}))},open:function(t,e){var n=this,a=[{msg:"取消权限，恢复普通用户？",auth:0},{msg:"将该用户设置为管理员？",auth:1},{msg:"设置为员工，仅有验证权限？",auth:2}],i=a[e].msg,o=a[e].auth;this.$confirm(i,"提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){n.http.post_show("cms/admin/set_web_auth",{id:t,auth_id:o}).then((function(t){n.$message({type:"success",message:"操作成功!"}),n._load()}))}))},reset:function(){this._load()},search:function(t){var e=this;for(var n in e.list=[],e.all){var a=e.all[n];null==a.nickname&&(a.nickname="null"),a.nickname.indexOf(t)>=0&&e.list.push(a)}e.total=this.list.length,e.list=e.list.slice(0,e.size),this.nickname=[]},handleChange:function(){},handleRemove:function(t,e){console.log(t,e)},handlePictureCardPreview:function(t){this.dialogImageUrl=t.url,this.dialogVisible=!0},jump_page:function(t){var e=this,n=(t-1)*e.size,a=t*e.size;console.log(n,a),this.list=this.all.slice(n,a)},sub:function(){},del:function(t){this.$confirm("是否删除?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){}))},set_hot:function(t){t["vip"]?(this.http.get("user/admin/set_vip_cms?id="+t["id"]).then((function(t){})),console.log("11111111111")):(this.http.get("user/admin/set_vip_cms_no?id="+t["id"]).then((function(t){})),console.log("000000000000000"))}}},r=s,c=(n("bf55"),n("2877")),u=Object(c["a"])(r,a,i,!1,null,null,null);e["default"]=u.exports},bf55:function(t,e,n){"use strict";n("1961")}}]);