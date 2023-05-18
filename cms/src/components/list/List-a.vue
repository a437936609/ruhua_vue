<template>
	<div class="list-a">


		<div class="tab-btn" v-if="!addShow">
			<el-button size="medium" @click="all">在售商品</el-button>
			<el-button size="medium" @click="getDownPro">下架商品</el-button>
		</div>
		<div>
			<div v-if="addShow" class="add-category ">
				<!-- <div class="order-back">
					<el-button type="primary" size="small" @click="back">返回</el-button>
					<el-button type="primary" size="small" @click="caiji">采集商品</el-button>
				</div> -->
				<Good :eid='eid' @emit_tg="emit_tg_list" @back="back"></Good>
			</div>
			<el-row v-if="!addShow" style="margin-bottom: 35px;">
				<el-button type="primary" size='small' @click="on_add">发布商品</el-button>
				<el-button type="warning" size='small' @click='sort_sub'>更新排序</el-button>
				<el-button type="success" size='small' @click='getProductList'>刷新</el-button>
				<el-input placeholder="商品名称" style="width: 10%;margin:10px" size='small' v-model="search_form.name"
					@keyup.enter.native="serach()"></el-input>
				<el-select v-model="search_form.cate" placeholder="请选择分类" size="small" style="margin-right: 10px;">
					<el-option v-for="item in cate_list" :key="item.value" :label="item.level == 1?item.category_name:'--'+item.category_name" :value="item.category_id">
					</el-option>
				</el-select>
				<el-button type="info" size='small' @click="serach()">搜索</el-button>
			</el-row>
			<el-row v-if="!addShow">
				<template>
					<el-table :data="product" border style="width: 100%" :default-sort="{prop: 'sort', order: 'ascending'}">
						
						<el-table-column prop="sort" label="排序" width="60" sortable>
							<template slot-scope="scope">
								<el-input v-model="scope.row.sort"></el-input>
							</template>
						</el-table-column>

						<el-table-column label="商品名称" width="550">
							<template slot-scope="scope">
								<div style="display: flex;">
									<div class="product-img" v-if="scope.row.imgs"><img :src="$getimg+scope.row.imgs">
									</div>
									<div class="product-mes">
										<p>{{scope.row.goods_name}}</p>
										<p>{{scope.row.description}}</p>
									</div>
								</div>
							</template>
						</el-table-column>

						<el-table-column label="商品编码" width="150">
							<template slot-scope="scope">
								<div>{{scope.row.goods_code}}</div>
							</template>
						</el-table-column>
						
						<el-table-column label="内控编码" width="110">
							<template slot-scope="scope">
								<div>{{scope.row.ic_code}}</div>
							</template>
						</el-table-column>
						
						<!-- <el-table-column label="类型"  width="120">
								<template slot-scope="scope">
								<p>{{scope.row.style?'普通商品':'拼团商品'}}</p>
							</template>
						</el-table-column> -->
						<el-table-column prop="price" label="价格" sortable></el-table-column>
						<el-table-column prop="stock" label="总库存" sortable></el-table-column>
						<el-table-column prop="sales" label="销量" sortable></el-table-column>

						<el-table-column label="是否热销" width="100" prop="is_hot"
							:filters="[{text: '是', value: true},{text: '否', value: false}]"
							:filter-method="filterHandler">
							<template slot-scope="scope">
								<el-switch @change="set_hot(scope.row.goods_id)" v-model="scope.row.is_hot"
									active-color="#409EFF" inactive-color="#DCDFE6">
								</el-switch>
							</template>
						</el-table-column>
						<el-table-column label="是否新品" prop="is_new" width="100"
							:filters="[{text: '是', value: true},{text: '否', value: false}]"
							:filter-method="filterHandler">
							<template slot-scope="scope">
								<el-switch @change="set_new(scope.row.goods_id)" v-model="scope.row.is_new"
									active-color="#409EFF" inactive-color="#DCDFE6">
								</el-switch>
							</template>
						</el-table-column>
						<el-table-column label="是否下架" width="100">
							<template slot-scope="scope">
								<el-switch @change="set_down(scope.row.goods_id,scope.$index)" v-model="scope.row.state"
									active-color="#DCDFE6" inactive-color="#F56C6C">
								</el-switch>
							</template>
						</el-table-column>
						<el-table-column label="操作" width="220">
							<template slot-scope="scope">
								<el-button type="default" size="small" @click="on_preview(scope.row.goods_id)">预览
								</el-button>
								<el-button type="success" size="small" @click="on_edit(scope.row.goods_id)">编辑
								</el-button>
								<el-button style="margin-left: 10px" type="danger" size="small" slot="reference"
									@click="del(scope.row.goods_id,scope.$index)">删除</el-button>

							</template>
						</el-table-column>
					</el-table>
				</template>

				<el-pagination style='padding-top: 60px;text-align: center;' background layout="prev, pager, next"
					:total="total" :page-size="size" @current-change="jump_page">
				</el-pagination>

			</el-row>


		</div>
	</div>
</template>

<script>
	import Good from '../Good.vue'
	import {
		Loading
	} from 'element-ui';
	export default {
		name: 'List-b',
		props: ['down'],
		data() {
			return {
				search_form:{
					name:null,
					cate:null
				},
				cate_list:[],
				search_cate: '',
				options: [],
				getimg: this.$getimg,
				allB: '',
				value: '',
				inpue: '',
				visible2: false,
				product: [],
				addShow: false,
				eid: 0,
				size: 8,
				total: 0,
				is_hot: true,

			}
		},
		//
		components: {
			Good,
		},
		//vue生命函数
		mounted() {
			this.getProductList(); //获取商品 
			this.all()
			this.get_all_cate()
		},
		methods: {
			serach() {
				let arr = []
				if(this.search_form.name){
					for (let s of this.allB) {
						let a = s.goods_name.indexOf(this.search_form.name)
						if (a >= 0) {
							arr.push(s)
						}
					}
				}
				if(this.search_form.cate){
					for (let k in this.allB) {
						let v = this.allB[k]
						if(v.category_id == this.search_form.cate){
							arr.push(v)
						}
					}
				}
				
				this.product = arr
			},
			filterHandler(value, row, column) {
				const property = column['property'];
				return row[property] === value;
			},
			//提交排序
			sort_sub() {
				let obj = {}
				const that = this
				console.log('pro:', that.product)
				for (let value of that.product) {
					obj[value.goods_id] = value.sort
				}
				this.http.post_show('product/admin/set_sort', obj)
					.then((res) => {
						this.$message({
							message: '操作成功',
							type: 'success'
						})
						this.all()
					})
			},
			//获取在售商品
			all() {
				this.value = '';
				this.http.post('product/admin/get_product').then(res => {
					this.total = res.data.length
					this.allB = res.data
					this.product = res.data.slice(0, this.size)
				})
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.product = this.allB.slice(start, end);
			},
			//添加商品信息
			on_add() {
				this.addShow = true;
				this.eid = 0;
			},
			//修改商品信息
			on_edit(id) {
				this.addShow = true;
				this.eid = id;

			},
			back() {
				this.order_id = 0;
				this.addShow = false;
				this.getProductList();
			},
			//获取所有商品
			getProductList() {
				const that = this;
				this.http.post('product/admin/all_goods_info').then((res) => {
					that.allB = res.data
					// that.product = res.data
					that.product = res.data.slice(0, that.size);
					that.total = res.data.length
				});
			},
			//获取所有分类
			get_all_cate(){
				this.http.get('category/admin/all_category').then(res=>{
					this.cate_list = res.data
				})
			},
			//获取下架商品
			getDownPro() {
				var that = this;
				this.http.post('product/admin/get_products_down').then((res) => {
					that.allB = res.data
					that.product = res.data
					that.total = res.data.length
				});
			},
			//删除商品
			del(id, index) {
				var that = this;
				const auth = localStorage.getItem("oauth");
				// if (auth.indexOf('product_del') < 0) {
				// 	this.$message({
				// 		message: '无权限',
				// 		type: 'none'
				// 	});
				// 	return;
				// }
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('product/admin/del_product', {
							id: id
						})
						.then((res) => {
							that.$message({
								showClose: true,
								message: '删除成功',
								type: 'success'
							});
							that.product.splice(index, 1);
							this.getProductList()
						});
				})
			},
			//是否热销
			set_hot(id) {
				var that = this;
				this.http.put_show('cms/update', {
						id: id,
						db: 'goods',
						field: 'is_hot'
					})
					.then((res) => {
						console.log(res)
					})
			},
			//是否新品
			set_new(id) {
				var that = this;
				this.http.put_show('cms/update', {
						id: id,
						db: 'goods',
						field: 'is_new'
					})
					.then((res) => {
						console.log(res);
					});
			},
			//是否上架
			set_down(id, index) {
				var that = this;
				this.http.put_show('cms/update', {
						id: id,
						db: 'goods',
						field: 'state'
					})
					.then((res) => {
						that.$message({
							message: '成功',
							type: 'success'
						})
						that.product.splice(index, 1)
					});
			},
			emit_tg_list() {
				this.addShow = false
				this.eid = 0
				this.getProductList() //获取商品 
			},
			on_preview (id) {
				let url = `${process.env.VUE_APP_H5_PRODUCT_PREVIEW}${id}`
				this.$emit('preview', url)
				console.log(url)
			}

		},

	}
</script>

<style lang="less" scoped="">
	.list-a {
		line-height: 30px;
		background-color: #fff;
		padding: 15px;
		text-align: left;
	}

	.tab-btn {
		margin: 0 0 20px;
	}

	.list {
		line-height: 30px;
		text-align: left;
	}

	.list-head {
		padding-bottom: 10px;
		display: flex;
	}

	.liat-head-02 {
		font-size: 14px;
		padding-left: 10px
	}

	.product-img {
		display: inline-block;
		width: 60px;
	}

	.product-img img {
		height: 60px;
		width: 60px;
		margin: 10px;
		margin-bottom: 20px;
	}

	.product-mes {
		display: inline-block;
		// width: 220px;
		margin-left: 30px;
	}

	.product-mes p {
		line-height: 30px;
		margin-top: 2px;
		overflow: hidden;
		height: 30px
	}

	.product-mes-name {
		line-height: 30px;
		overflow: hidden;
		height: 30px
	}

	.order-back {
		line-height: 30px;
		padding-bottom: 10px;
		text-align: left;
		padding-left: 50px
	}
</style>
