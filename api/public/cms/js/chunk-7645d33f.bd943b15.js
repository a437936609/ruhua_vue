(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-7645d33f"],{"3b3f":function(t,e,o){},"4d49":function(t,e,o){"use strict";o.r(e);var n=function(){var t=this,e=t.$createElement,o=t._self._c||e;return o("div",{staticClass:"discount"},[o("el-container",[o("el-aside",{attrs:{width:"200px"}},[o("NavTo")],1),o("el-container",[o("el-header",{staticStyle:{"border-bottom":"1px solid #d0d0d0","background-color":"#FFFFFF"}},[o("Header")],1),o("transition",{attrs:{appear:"","appear-active-class":"animated fadeInLeft"}},[o("el-main",[o("div",{staticClass:"article"},[o("el-button",{on:{click:t.jumpback}},[t._v("返回")]),t._v("  "),o("el-button",{attrs:{type:"primary"},on:{click:t.add_user}},[t._v("添加分销商品")]),o("el-button",{attrs:{type:"primary"},on:{click:t.fx_user_manage}},[t._v("分销商管理")]),o("div",{staticStyle:{height:"20px"}},[t._v(" ")]),o("el-table",{attrs:{data:t.list,border:""}},[o("el-table-column",{attrs:{type:"index",label:"序号",width:"50px"}}),o("el-table-column",{attrs:{prop:"goodsInfo.goods_name",label:"商品名称",width:"400"}}),o("el-table-column",{attrs:{prop:"goodsInfo",label:"图片",width:"110"},scopedSlots:t._u([{key:"default",fn:function(e){return[e.row.goodsInfo?[o("img",{attrs:{src:t.$getimg+e.row.goodsInfo.imgs}})]:t._e()]}}])}),o("el-table-column",{attrs:{prop:"goodsInfo.price",label:"商品价格",width:"100"}}),o("el-table-column",{attrs:{prop:"price",label:"分销佣金",width:"150"},scopedSlots:t._u([{key:"default",fn:function(e){return[o("el-input",{model:{value:e.row.price,callback:function(o){t.$set(e.row,"price",o)},expression:"scope.row.price"}})]}}])}),o("el-table-column",{attrs:{prop:"operation",label:"操作"},scopedSlots:t._u([{key:"default",fn:function(e){return[o("el-button",{attrs:{type:"success",size:"small"},on:{click:function(o){return t.edit(e.row.id,e.row.price,e.row.goodsInfo.price)}}},[t._v("修改")]),o("el-button",{staticStyle:{"margin-left":"10px"},attrs:{slot:"reference",type:"danger",size:"small"},on:{click:function(o){return t.del(e.row.id)}},slot:"reference"},[t._v("删除")])]}}])}),o("strong")],1)],1)])],1)],1)],1)],1)},s=[],i=(o("5c96"),o("a49b"),o("15fc")),a=o("71c2"),r={data:function(){return{getimg:this.$getimg,dialogImageUrl:"",dialogVisiblea:!1,tab_nav:!1,price:"",dialogVisibleadd:!1,oid:0,form:{id:"",goods_name:"",content:"",img_id:[],stock:"",points:""},form_pro:{goods_name:"",content:"",img_id:[],stock:"",points:""},list:[],all:"",size:10}},components:{Header:a["a"],NavTo:i["a"]},methods:{fx_user_manage:function(){this.$router.push({path:"/extend/fx_user_manage"})},jumpback:function(){this.$router.push({path:"/extend/application"})},onSubmit:function(){var t=this;this.http.post_show("admin/add_goods",this.form_pro).then((function(){t.$message({type:"success",message:"添加成功!"}),t.form={},t.upfile_banner_list=[],t.form_pro={goods_name:"",content:"",img_id:[],stock:"",points:""},t.dialogVisibleadd=!1,t.get_reseller()}))},add_user:function(){this.$router.push({path:"/extend/addreseller"})},edit:function(t,e,o){var n=this;1*e<1*o?this.http.post_show("fx/admin/edit_goods",{id:t,price:e}).then((function(t){n.$message({showClose:!0,message:"修改成功",type:"success"})})):this.$message({message:"分销佣金必须小于商品价格",type:"warning"})},get_reseller:function(){var t=this;this.http.get("fx/admin/get_goods?type=1").then((function(e){t.list=e.data}))},del:function(t){var e=this,o=this;this.$confirm("是否删除?","提示",{confirmButtonText:"确定",cancelButtonText:"取消",type:"warning"}).then((function(){e.http.put_show("fx/admin/del_goods",{id:t}).then((function(){o.$message({showClose:!0,message:"删除成功",type:"success"}),e.get_reseller()}))}))}},mounted:function(){this.get_reseller()}},l=r,c=(o("8af4"),o("2877")),d=Object(c["a"])(l,n,s,!1,null,null,null);e["default"]=d.exports},"8af4":function(t,e,o){"use strict";o("3b3f")}}]);