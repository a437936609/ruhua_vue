<template>
	<div id="brands">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container style="">
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<transition appear appear-active-class="animated fadeInLeft">
					<el-main style="background-color: #F3F3F3;">

						<div class="brands">
							<!-- 弹窗--新增品牌 -->
							<AddBrands @up_parent="up_list" :list="brands_top"></AddBrands>

							<!-- 弹窗--修改品牌 -->
							<el-dialog title="" :visible.sync="editbox" width="35%" center>
								<el-form :model="editform">
									<el-form-item label="品牌名称" :label-width="formLabelWidth" style='width: 80%'>
										<el-input v-model="editform.brand_name" auto-complete="off"></el-input>
									</el-form-item>
									<el-form-item label="品牌简称" :label-width="formLabelWidth" style='width: 80%'>
										<el-input v-model="editform.short_name" auto-complete="off"></el-input>
									</el-form-item>
									<el-form-item label="选择图片" :label-width="formLabelWidth">
										<div style="display: flex; width:530px ; flex-wrap: wrap;" v-if="editform.imgs">
											<i class="el-icon-circle-close" @click="delimg"></i>
											<div class="picA" @click="to_choose_img">
												<img class="img" :src="getimg + editform.imgs">
											</div>
										</div>
										<div v-else class="picA" style="margin-left: 19px;" @click="to_choose_img">
											<i class="el-icon-plus" style="margin-top: 45%; height: 28px; width: 28px;"></i>
										</div>
									</el-form-item>
								</el-form>
								<div slot="footer" class="dialog-footer" style='text-align: center'>
									<el-button @click="editbox = false">取 消</el-button>
									<el-button type="primary" @click="onSubmit(editindex)">确 定</el-button>
								</div>
							</el-dialog>
							<template>
								<el-table :data="brands" border style="width: 100%" :default-sort="{prop: 'sort', order: 'descending'}">
									<el-table-column type="index" label="序号" width="80">
									</el-table-column>
									<el-table-column prop="brand_name" label="品牌名字">
										<template slot-scope="scope">
											{{scope.row.brand_name}}
										</template>
									</el-table-column>
									<el-table-column prop="short_name" label="品牌简称">
									</el-table-column>
									<el-table-column prop="imgs" label="缩略图">
										<template slot-scope="scope">
											<div v-if="scope.row.imgs != null">
												<img :src="$getimg+scope.row.imgs" width="40" height="40" />
											</div>
											<div v-else>
												无
											</div>
										</template>
									</el-table-column>
									<el-table-column prop="brand_id" label="品牌id">
									</el-table-column>
									<el-table-column prop="is_visible" label="是否显示">
										<template slot-scope="scope">
											<el-switch @change="onswitch(scope.row.brand_id)" v-model="scope.row.is_visible" active-color="#409EFF"
											 inactive-color="#DCDFE6">
											</el-switch>
										</template>
									</el-table-column>
									<el-table-column prop="level" label="操作">
										<template slot-scope="scope">
											<el-button type="success" size="small" @click="onedit(scope.row.brand_id)">修改</el-button>
											<el-button type="danger" size="small" @click="del(scope.row.brand_id,scope.$index)">删除</el-button>
										</template>
									</el-table-column>
								</el-table>
							</template>
							<!-- <div class="sort_btn">
								<el-button type="success" size="small" @click="sort_sub">提交排序</el-button>
							</div> -->
						</div>

					</el-main>

				</transition>
			</el-container>
		</el-container>
		<!-- <Pic :drawer="drawer" @drawer="is_show" @get_img="get_img" :length="length"></Pic> -->
		<Pic ref="child" :drawer="drawer" :father_fun="get_img" :length="length" :iscg="is_cg"></Pic>
	</div>
</template>

<script>
	import {
		Loading
	} from 'element-ui';
	import AddBrands from '../../components/AddBrands.vue'
	import {
		Api_url
	} from '@/common/config.js'
	import NavTo from '@/components/navTo.vue'
	import Header from '@/components/header.vue'
	import Pic from '../brandsPicList.vue'
	export default {
		name: 'Brands',
		components: {
			AddBrands,
			NavTo,
			Header,
			Pic
		},
		data() {
			return {
				is_cg: 0,
				length: 1,
				drawer: false,
				getimg: this.$getimg,
				img_list: [],
				input: '',
				brands: [],
				brands_top: [],
				dialogTableVisible: false,
				editbox: false, //修改的box
				editform: {
					brand_name: '',
					short_name: '',
					pid: '',
					brand_pic: [],
					imgs: []
				},
				formLabelWidth: '120px',
				editindex: 0,
				upfile_url: Api_url + '/admin/upload/img',
				upfile_data: {
					use: 'brands'
				},
				upfile_head: {
					token: localStorage.getItem("token")
				}
			};
		},
		methods: {
			getBrands() {
				var that = this;
				// 				let loadingInstance = Loading.service({
				// 					fullscreen: true
				// 				}); //显示加载
				var arr = [];
				//获取所有分类	
				this.http.get('brands/admin/all_brands')
					.then((res) => {
						// loadingInstance.close(); //关闭加载 
						console.log(that.brands);
						for (var i = 0; i < res.data.length; i++) {
							if (res.data[i].level == 1) {
								arr.push(res.data[i]);
							}
							if (res.data[i].level == 2) {
								res.data[i].line = true;
							} else {
								res.data[i].line = false;
							}
						}
						that.brands = res.data;
						that.brands_top = arr;
					});
			},


			to_choose_img() {
				this.drawer = !this.drawer
			},
			is_show() {
				this.drawer = !this.drawer
			},
			get_img(e) {
				// this.img_list = e
				// for (let v in e) {
				// 	this.editform.brand_pic = e[v].id
				// 	this.editform.imgs = e[v].url
				// }
				// console.log(this.form.img_id)



				this.drawer = false
				for (let k in e) {
					const v = e[k]
					this.img_list.push(v)
					this.editform.brand_pic = v.id
					this.editform.imgs = v.url
				}
				this.length = 6 - this.img_list.length
				console.log('get_img_end:', e, this.img_list)



			},
			delimg(index) {
				// this.img_list.splice(index, 1)
				this.editform.imgs = ''
			},


			//删除分类
			del(id, index) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('brands/admin/del_brands', {
						id: id
					}).then((res) => {
						if (res.data == 1) {
							that.$message({
								showClose: true,
								message: res.msg,
								type: 'success'
							});
						}else{
							that.$message({
								showClose: true,
								message: res.msg,
								type: 'warning'
							});
						}
						this.getBrands()
					})
				})
			},
			//修改品牌
			onedit(id) {
				console.log(id)

				// this.editform = this.category[index];
				const that = this
				for (let k in that.brands) {
					let v = that.brands[k]
					if (v.brand_id == id) {
						that.editform = v
					}
				}
				console.log(this.editform)
				// this.editindex = index;
				this.editbox = true;
			},
			//新增品牌成功后
			up_list() {
				this.getBrands();
			},
			//提交排序
			sort_sub() {
				let obj = {}
				const that = this
				for (let value of that.brands) {
					obj[value.brand_id] = value.sort
				}
				this.http.post_show('brands/admin/set_sort', obj)
					.then((res) => {
						this.$message({
							message: '操作成功',
							type: 'success'
						})
					})
			},
			//更新品牌
			onSubmit(index) {
				const that = this;
				this.http.post_show('brands/admin/edit_brands', {
					form: that.editform
				}).then((res) => {
					this.$message({
						message: '修改成功',
						type: 'success'
					})
					// console.log(res.data);
					that.editbox = false;
					// this.category.splice(index, 1, res.data);
					this.getBrands()
				});
			},
			//是否隐藏
			onswitch(id) {
				var that = this;
				this.http.put_show('/cms/update', {
						id: id,
						db: 'brands',
						field: 'is_visible'
					})
					.then((res) => {
						console.log(res);
					});
			},
			up_ok(res) {
				if (res.code == 201) {
					this.editform.brand_pic = res.id;
				}
			},

		},
		mounted() {
			this.getBrands(); //获取品牌  
		}
	}
</script>

<style lang="less" scoped="">
	#brands {
		.brands {
			line-height: 30px;
			text-align: left;
			background-color: #fff;
			padding: 15px;
		}

		.sort_btn {
			margin-top: 20px;
			;
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
