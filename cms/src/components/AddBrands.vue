<template>
	<div class="add-cate">
		<el-button type="primary" @click="addbox = true">添加品牌</el-button>
		<el-button type="primary" @click="img_manage">图库管理</el-button>
		<div style="height:10px;"></div>
		<el-dialog title="" :visible.sync="addbox" width="35%" center>
			<el-form :model="addform">
				<el-form-item label="品牌名称" :label-width="formLabelWidth" style='width: 80%'>
					<el-input v-model="addform.brand_name" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="品牌简称" :label-width="formLabelWidth" style='width: 80%'>
					<el-input v-model="addform.short_name" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="品牌图片" :label-width="formLabelWidth">

					<template v-if="img_list.length > 0">
						<div style="display: flex; width:530px ; flex-wrap: wrap;">
							<template v-for="(item,index) of img_list">
								<i class="el-icon-circle-close" @click="delimg(index)"></i>
								<div class="picA" v-if="img_list.length > 0">
									<img class="img" :src="getimg + item.url">
								</div>
							</template>
						</div>
					</template>
					<div class="picA" style="margin-left: 19px;" @click="to_choose_img" v-if="img_list.length < 1">
						<i class="el-icon-plus" style="margin-top: 45%; height: 28px; width: 28px;"></i>
					</div>
				</el-form-item>
				<!-- <el-upload class="upload-demo" :action="upfile_url" :data="upfile_data" :on-success="up_ok" :limit=1 :file-list="upfile_list"
				 :headers="upfile_head">
					
				</el-upload> -->
			</el-form>
			<div slot="footer" class="dialog-footer" style='text-align: center'>
				<el-button @click="addbox = false">取 消</el-button>
				<el-button type="primary" @click="onSubmit">确 定</el-button>
			</div>
		</el-dialog>
		<Pic :drawer="drawer"  :father_fun="get_img" :length="length"></Pic>
	</div>
</template>

<script>
	import {
		Api_url
	} from '@/common/config.js'
	import Pic from '../views/brandsPicList.vue'
	export default {
		name: 'Brands',
		props: ['list'],
		data() {
			return {
				length:1,
				drawer: false,
				getimg: this.$getimg,
				img_list: [],
				addbox: false,
				addform: {
					brand_name: '',
					short_name: '',
					pid: '',
					brand_pic: ''
				},
				formLabelWidth: '120px',
				upfile_url: Api_url + '/admin/upload/img',
				upfile_data: {
					use: 'brands'
				},
				upfile_head: {
					token: localStorage.getItem("token")
				},
				upfile_list: [], //上传文件列表
			};
		},
		components: {
			Pic
		},
		methods: {
			//图库管理
			img_manage(){
				this.drawer = !this.drawer
				this.length = 50
			},
			//新增品牌
			onSubmit() {
				var that = this;
				this.http.post_show('brands/admin/add_brands', {
					form: that.addform
				}).then((res) => {
					that.$message({
						showClose: true,
						message: '新增成功',
						type: 'success'
					});
					that.addbox = false;
					for (var x in that.addform) {
						that.addform[x] = ""
					};
					that.upfile_list = [];
					that.$emit("up_parent");
					that.img_list = []
				});
			},


			to_choose_img() {
				this.length = 1
				this.drawer = !this.drawer
			},
			is_show() {
				this.drawer = !this.drawer
			},
			get_img(e) {
				this.drawer = false
				for (let k in e) {
					const v=e[k]
					this.img_list.push(v)
					this.addform.brand_pic = v.id
				}
				this.length=6-this.img_list.length
				console.log('get_img_end:',e,this.img_list)
			},
			delimg(index) {
				this.img_list.splice(index, 1)
			},



			up_ok(res) {
				if (res.code == 201) {
					this.addform.brand_pic = res.id;
				}
			},
		},
	}
</script>

<style lang="less" scoped="">
	.add-cate {
		.category {
			line-height: 30px;
			text-align: left;
		}

		.btn {
			margin-bottom: 6px;
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
	}
</style>
