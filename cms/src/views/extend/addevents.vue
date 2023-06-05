<template>
	<div class="adddiscount">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<transition appear appear-active-class="animated fadeInLeft">
					<el-main>
						<div class="add">
							<div class="add_btn">
								<el-button type="primary" @click="jumpback">返回</el-button>
							</div>
							<div class="xiao">专题活动</div>
							<el-form ref="form" :model="form" label-width="120px" class="demo-dynamic">
								<el-form-item label="专题名称">
									<el-input v-model="form.name" hide-required-asterisk style="width:200px"></el-input>
								</el-form-item>
								<el-form-item label="专题描述">
									<el-input v-model="form.label" hide-required-asterisk style="width:400px"></el-input>
								</el-form-item>
								
							</el-form>
							<div class="xiao">选择专题商品</div>
							<el-tabs type="border-card" style="margin-bottom: 20px" v-model="active_name">
								<el-tab-pane label="专题楼层" name="one">
									<div class="choose">
										<div class="search">
											<el-button type="primary">添加楼层</el-button>
										</div>
									</div>
								</el-tab-pane>

							</el-tabs>
							
							<span slot="footer" class="dialog-footer ">
								<el-button @click="jumpback">取 消</el-button>
								<el-button type="primary" @click="onSubmit">提交</el-button>
							</span>
						</div>
					</el-main>
				</transition>
			</el-container>

		</el-container>

	</div>
</template>

<script>
	import {
		Loading
	} from 'element-ui';
	import {
		Api_url
	} from "@/common/config";

	import NavTo from '@/components/navTo.vue'
	import Header from '@/components/header.vue'
	export default {
		data() {
			return {
				x: 0,
				tableRowClassName: '',
				choosed: [],
				active_name: 'one',
				is_two: 0,
				discount_list: [],
				getimg: this.$getimg,
				choose: true,
				tableData: [],
				dialogVisibleadd: false,
				oid: 0,
				form: {
					buy_num: '',
					name: '',
					buy_rule: '',
					goods_json: [],
					label: '',
					start_time: '',
					end_time: '',
					day_json: [],
					time_rule: 0
				},

				list: [],
				all: '',
				size: 8,
				total:0,
				value: '',
			}
		},
		watch: {
			active_name() {
				if (this.active_name == 'one') {
					this.is_two = 0
				}
				if (this.active_name == 'two') {
					this.is_two = 1
				}
			}
		},
		components: {
			Header,
			NavTo
		},
		mounted() {
			this._load()
		},
		methods: {
			_load() {
				this.http.get('product/admin/get_normal_goods').then(res => {

					for (let k in res.data) {
						let v = res.data[k]
						v.is_discount = 1
					}
					this.all = res.data
					this.total = res.data.length
					this.tableData = res.data.slice(0,this.size)
					console.log(this.tableData)
				})
			},
			jump_page(e) {
				console.log(e)
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.tableData = this.all.slice(start, end);
			},
			//添加为折扣商品
			add_discout(id, index, item) {
				let obj = {
					goods_id: id,
					discount_type: 1,
					reduce_price: 0,
					discount_price: 0
				}
				this.tableData[index].is_discount = 2
				this.form.goods_json.push(obj)
				this.discount_list.push(index)
				// this.choosed.push(index)
				console.log(this.form)
				console.log(this.discount_list)
				this.x++
			},
			//取消折扣
			del(index) {
				for (var i = 0; i < this.discount_list.length; i++) {
					if (this.discount_list[i] == index) {
						this.discount_list.splice(i, 1)
					}
				}
				this.tableData[index].is_discount = 1
				this.form.goods_json.splice(index, 1)
				// this.discount_list.splice(index, 1)
				console.log(this.form)
				console.log(this.discount_list)
			},
			//删除已选中折扣商品列表中商品
			del_disgoods(item, index) {
				this.discount_list.splice(index, 1)
				this.tableData[item].is_discount = 1
			},
			get_goods(e) {
				console.log(e)
			},
			next() {
				this.active_name = 'two'
				this.is_two = 1
			},
			back() {
				this.is_two = 0
				this.active_name = 'one'
			},
			onSubmit() {
				console.log(this.form)
				this.http.post_show('discount/admin/add_discount', this.form).then((res) => {
					this.$message({
						type: 'success',
						message: '添加成功!'
					}); 
					this.form = {
							time_rule: 0,
							name: '',
							buy_rule: '',
							goods_json: [],
							day_json: [],
							label: '',
							start_time: '',
							end_time: '',
							buy_num: '',
						},
						this.dialogVisibleadd = false
					this.jumpback()
				})

			},
			res_banner_imgs(res) {

				console.log('res:', res)
				this.form_pro.img_id.push(res);
				// if (this.form_pro.img_id.length < 1) {
				// 	this.form_pro.img_id = res;
				// } else {
				// 	this.form_pro.img_id = this.form_pro.img_id + "," + res;
				// }
				console.log('xx:', this.form_pro.img_id)

			},
			add_user() {
				this.dialogVisibleadd = true
			},

			edit(id, goods_name, content, img_id, stock, points) {
				this.form.id = id
				this.form.goods_name = goods_name
				this.form.content = content
				this.form.img_id = img_id
				this.form.stock = stock
				this.form.points = points
				console.log(this.form)
			},
			
			jumpback() {
				this.$router.push({
					path: './events'
				})
			},
			close_fun(done) {
				this.clear_data()
				done(); //官方实例用法
			},

			clear_data() {
				this.dialogFormVisible = false
			},
		},

	}
</script>

<style lang="less">
	.adddiscount {
		background-color: #F3F3F3;

		.el-table__row {
			line-height: 40px !important;

			img {
				width: 80px !important;
				height: 80px !important;
			}
		}

		.search {
			text-align: left;
			border-bottom: 1px solid #E6E6E6;
			padding-bottom: 15px
		}

		.xiao {
			padding: 10px 10px 15px 15px;
			text-align: left;
			font-size: 15px;
			font-weight: 600;
		}

		.quan {
			display: flex;
			justify-content: space-between;
			padding: 15px 10px;

			.quan_r {
				font-size: 13px;
			}
		}

		.el-main {
			height: auto !important;
			background-color: #F3F3F3;
			padding: 15px;

			.el-table {
				height: auto !important;
			}

			.el-table__body-wrapper,
			.is-scrolling-none,
			.el-table__body {
				height: auto !important;
			}

			.el-form-item__content {
				padding-left: 15px;
			}
		}

		.pro {
			display: flex;

			.pro_01 {
				padding-right: 10px;
				display: flex;
				flex-direction: column;
				justify-content: center;
			}

			.pro_02 img {
				height: 60px !important;
				;
				width: 60px !important;
				padding-right: 10px
			}

			.pro_03 {
				display: flex;
				flex-direction: column;
				justify-content: space-between;

				.pro_03_1 {
					color: #2768D7
				}

				.pro_03_2 {
					color: #FF6600
				}
			}
		}

		.tableRowClassName {
			background-color: #f0f0f0;
		}

		.add {
			background-color: #fff;
			padding: 15px;

			.add_btn {
				padding: 0 0 20px 20px;
				text-align: left;
			}

			.dan {
				padding-left: 10px
			}

			.ts {
				text-align: left;
				font-size: 12px;
				color: #A6A7A8;
				padding-left: 130px;
				margin-top: -18px;
				padding-bottom: 18px;
			}

			.jian {
				padding-right: 10px
			}

			.line {
				padding: 0 15px;
			}

			.xzsp {
				color: #155BD4;
				text-align: left;
				padding: 0 0 15px 130px;
				margin-top: -20px;
				font-size: 14px;
			}

			.spxz {
				padding: 0 0 25px 130px;
				width: 40%;

				.shiyong {
					display: flex;

					.sy_01 img {
						height: 40px !important;
						;
						width: 40px !important;
						padding-right: 10px
					}

					.sy_02 {
						display: flex;
						flex-direction: column;
						justify-content: space-between;

						.sy_01_1 {
							color: #2768D7
						}

						.sy_01_2 {
							color: #FF6600
						}
					}
				}
			}
		}

		.article {
			line-height: 30px;
			text-align: left;
		}

		.list-head {
			padding-bottom: 10px
		}

		.el-form-item__content {
			display: flex;
			justify-content: flex-start;
		}
	}
</style>
