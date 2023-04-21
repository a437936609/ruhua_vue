<template>
	<div class="apply">
		<el-container>
				<el-aside width="200px">
				<NavTo></NavTo>
				</el-aside>
		 <el-container>
			 <el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
			   <Header></Header>
			 </el-header>
		 
		 <div>
			 <transition appear appear-active-class="animated fadeInLeft">
			 	<el-main>
			 		<div class="article">
						<div style="text-align: left;">
					
			 			<el-button  @click="jumpback">返回</el-button> &emsp;
			 			<el-button type="primary" @click="add_game">添加规则</el-button>
			 			<div style="height:20px;">&nbsp;</div>
						</div>
			 			<template>
			 				<el-table :data="tableData" border style="width: 100%">
			 					<el-table-column type="index" label="序号" width="50px"></el-table-column>
			 					<el-table-column prop="nav.url_name" label="名称"></el-table-column>
								<el-table-column prop="img_id" label="导航图标" width="100">
									<template slot-scope="scope">
										<img class="navimg" :src="getimg+scope.row.img_id" />
									</template>
								</el-table-column>
			 					<el-table-column prop="content" label="奖励"></el-table-column>
								<el-table-column prop="value" label="概率"></el-table-column>
								<el-table-column prop="scope" label="库存">
									<template slot-scope="scope">
										<p v-if="scope.row.stock<0">无限</p>
										<p v-else>{{scope.row.stock}}</p>
									</template>
								</el-table-column>
								
								<el-table-column prop="type" label="奖励">
									<template slot-scope="scope">
										<p v-if="scope.row.type==0">积分</p>
										<p v-if="scope.row.type==2">谢谢参与</p>
										<p v-if="scope.row.type==1">其它</p>
									</template>
								</el-table-column>
								<el-table-column prop="points" label="奖励积分"></el-table-column>
			 					<el-table-column prop="operation" label="操作" width="300px">
			 						<template slot-scope="scope">
										<el-button style="margin-left: 10px" type="infor" size="small" slot="reference" @click="update(scope.row.id,scope.$index)">修改</el-button>
			 							<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">删除</el-button>
			 						</template>
			 					</el-table-column><strong></strong>
			 				</el-table>
			 			</template>
			 		</div>
			 	</el-main>
			 </transition>
		 </div>
		 <el-dialog :title="edit_title" :visible.sync="is_disLogshow" width="30%" :before-close="handleClose">
		 	<el-form ref="form" :model="form" label-width="80px">
				<el-form-item label="类型">
					<el-select v-model="form.type" placeholder="请选择类型" style="width: 100%;">
						<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
						</el-option>
					</el-select>
				</el-form-item>
				
				<el-form-item label="奖励内容">
					<el-input v-model="form.content"></el-input>
				</el-form-item>
				
				<el-form-item label="奖励积分" v-if="form.type==0">
					<el-input v-model="form.points"></el-input>
				</el-form-item>
				
				<el-form-item label="概率">
					<el-input v-model="form.value"></el-input>
				</el-form-item>
				
				
				<el-form-item label="库存" style="text-align: left;">
					<el-radio v-model="stock" :label="0" border size="medium">不限</el-radio>
					<el-radio v-model="stock" :label="1" border size="medium">有限</el-radio>
				</el-form-item>
				
				<el-form-item label="库存量" v-if="stock==1">
					<el-input v-model="form.stock"></el-input>
				</el-form-item>
				
				<el-form-item label="图标" :label-width="formLabelWidth">
					<template v-if="img_list.length > 0">
						<div style="display: flex; width:530px ; flex-wrap: wrap;">
							<template v-for="(item,index) of img_list">
								<i class="el-icon-circle-close" @click="delimg(index)"></i>
								<div class="picA" v-if="img_list.length > 0">
									<img class="img" :src="getimg + form.img_id">
								</div>
							</template>
						</div>
					</template>
					<div class="picA" style="margin-left: 19px;" @click="choose_pic" v-if="img_list.length < 1">
						<i class="el-icon-plus" style="margin-top: 45%; height: 28px; width: 28px;"></i>
					</div>
				</el-form-item>
		 	</el-form>
		 	<span slot="footer" class="dialog-footer">
		 		<el-button @click="cancel">取 消</el-button>
		 		<el-button type="primary" @click="sub_add">确 定</el-button>
		 	</span>
		 </el-dialog>
		 	 </el-container>
			 
		 
	  </el-container>
<Pic :drawer="drawer" :father_fun="get_img" :length="length"></Pic>
	  
	  
	</div>
</template>

<script>
import { Loading } from "element-ui";
import NavTo from "@/components/navTo.vue";
import Header from "@/components/header.vue";
import Pic from '../../PicList.vue'
	import {
		Api_url
	} from '@/common/config.js'

export default{
	data(){
		return{
			is_add:true,
			is_disLogshow:false,
			edit_title:'添加',
			progsuc:-1,
			percentage:0,
			tableData:[],
			formLabelWidth: '120px',
			getimg: this.$getimg,
			stock:0,
			form:{
				id:'',
				gid:'',
				content:'',
				value:'',
				stock:'',
				type:'',
				points:'',
				img_id:''
			},
			options:[{label:"积分",value:0},{label:"其它",value:1},{label:"谢谢参与",value:2}],
			length: 1,
			drawer: false,
			img_list: [],
		}
	},
	components: {
	  Header,
	  NavTo,
	  Pic
	},
	mounted() {
		let id  = this.$route.query.id
		this.form.gid=id
	  this._load(id)
	},
	methods:{
		//返回
		jumpback() {
		  this.$router.push({
		    path: "/extend/jgglist",
		  });
		},
		get_data(id){
			
			this.http.get('games/get_jgg_rule?id='+id).then(res=>{
				console.log(res)
				this.tableData=res.data
			})
		},
		_load(id){
			this.get_data(id)
		},
		del(id){
			this.http.del('games/del_game_rule?id='+id).then(res=>{
				this.get_data(this.form.gid)
			})
		},
		get_img(e) {
			this.drawer = false
			for (let k in e) {
				const v = e[k]
				this.img_list.push(v)
				this.form.img_id = v.url
			}
			this.length = 6 - this.img_list.length
			console.log('get_img_end:', e, this.img_list)
		},
		to_choose_img() {
			this.drawer = !this.drawer
		},
		is_show() {
			this.drawer = !this.drawer
		},
		//打开图库
		choose_pic() {
			this.length = 6 - this.img_list.length
			this.drawer = true
		},
		clear_data(){
			this.form.id=''
			this.form.content=''
			this.form.value=0
			this.form.stock=0
			this.form.type=0
			this.form.points=0
			this.form.img_id=''
			
			this.img_list=[]
			
		},
		customColorMethod(percentage) {
		  if (percentage < 30) {
		    return "#909399";
		  } else if (percentage < 70) {
		    return "#e6a23c";
		  } else {
		    return "#67c23a";
		  }
		},
		
			add_game(){
				this.clear_data()
				this.is_disLogshow=true
			},
			cancel(){
				this.is_disLogshow=false
			},
			delimg(index) {
				this.img_list.splice(index, 1)
			},
			sub_add()
			{
				if(this.is_add){
					this.http.post('games/add_jgg_rule',this.form).then(res=>{
						if(res.data.status==200){
							this.$message({
								type: 'success',
								message: '添加成功!'
							});
						
						}
							this.clear_data()
							this.get_data(this.form.gid)
							this.is_disLogshow=false
							
					})
				}else{
					this.http.post('games/update_jgg_rule',this.form).then(res=>{
						if(res.data.status==200){
							this.$message({
								type: 'success',
								message: '添加成功!'
							});
						
						}
							this.clear_data()
							this.is_disLogshow=false
							this.get_data(this.form.gid)
					})
				}
	
			},
			update(id,index){
				this.img_list=[]
				console.log(index)
				this.form.id=this.tableData[index].id
				this.form.content=this.tableData[index].content
				this.form.value=this.tableData[index].value
				this.form.stock=this.tableData[index].stock
				this.form.type=this.tableData[index].type
				this.form.points=this.tableData[index].points
				this.form.img_id=this.tableData[index].img_id
				this.img_list.push(this.form.img_id)
				this.is_disLogshow=true
				this.is_add=false
				this.edit_title="编辑";
			}
			
		
	}
}

</script>

<style lang="less" scoped="">
	.apply {
	  background-color: #f3f3f3;
	
	  .el-main {
	    height: auto !important;
	    background-color: #f3f3f3;
	    padding: 15px;
	  }
	
	  .article {
	    line-height: 30px;
	    background-color: #fff;
	    padding: 15px;
	    text-align: left;
	    // 进度条
	    .bar {
	      padding: 10px 15px;
	      border: 1px solid #f0f0f0;
	      padding-top: 60px;
	    }
	
	    .fix {
	      padding: 10px 15px;
	      border: 1px solid #f0f0f0;
	      padding-top: 60px;
	      text-align: center;
	    }
	  }
	  [v-cloak] {
	    display: none;
	  }
	}
	.navimg {
		height: 60px;
		width: 60px;
	}
	.picA {
		width: 148px;
		height: 148px;
		background-color: #FBFDFF;
		border: 1px dashed #C0CCDA;
		border-radius: 6px;
		box-sizing: border-box;
		vertical-align: top;
		text-align: center;
		margin: 6px;
	
	
		img {
			width: 144px;
			height: 144px;
			border: 1px solid #BFBFBF;
			border-radius: 3px;
		}
	}
</style>
