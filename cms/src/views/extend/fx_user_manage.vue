<template>
	<div class="user">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container style="background-color: #F3F3F3;">
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>

				<transition appear appear-active-class="animated fadeInLeft">
					<el-main>
						<div class="article">
							<el-button type="primary"  @click="jumpback">返回</el-button> &emsp;
							<div style="height:20px;">&nbsp;</div>
							<el-tabs v-model="activeName" @tab-click="get_tab_name">
								<el-tab-pane label="分销商" name="1">
									<div class="fx-con">
										<template>
											<el-table :data="list" border style="width: 100%">
												<el-table-column type="index" label="序号" width="50px"></el-table-column>
												<el-table-column prop="user.nickname" label="昵称"></el-table-column>
												<el-table-column prop="all_money" label="总佣金"></el-table-column>
												<el-table-column prop="num" label="订单数"></el-table-column>
												<el-table-column prop="lower" label="下线数"></el-table-column>
												<el-table-column prop="moneyok" label="已提现"></el-table-column>
												<el-table-column prop="money" label="未提现"></el-table-column>
												<el-table-column prop="invite_code" label="邀请码"></el-table-column>
												<el-table-column prop="operation" label="操作" width="300px">
													<template slot-scope="scope">
														<el-button type="info" size="small" @click="del_fx_user(scope.row.user_id)">设为普通用户</el-button>
													</template>
												</el-table-column><strong></strong>
											</el-table>
										</template>
									</div>
									<div class="t_c">
										<el-pagination style="margin-top: 50px;" background layout="prev, pager, next" :total="total" :page-size="size" @current-change="jump_page">
										</el-pagination>
									</div>
								</el-tab-pane>

								<el-tab-pane label="添加分销商" name="2">
									<div class="fx-con">
										<div class="user_sear">
											<div class="sear_01">
												<div class="sear_01_01">
													<div class="sear_01_01_1">昵称：</div>
													<el-input v-model="nickname" placeholder="请输入昵称"></el-input>
													<div style="margin-left: 20px;">
														<el-button type="primary" @click="search(nickname)"><i class="el-icon-search"></i>搜索</el-button>
													</div>
													<div style="margin-left: 20px;">
														<el-button type="primary" @click="reset">重置</el-button>
													</div>

												</div>
											</div>
										</div>
										<el-table :data="no_fx_list" border style="width: 100%">
											<el-table-column type="index" label="序号" width="50px"></el-table-column>
											<el-table-column prop="nickname" label="昵称"></el-table-column>
											<el-table-column prop="invite_code" label="邀请码"></el-table-column>
											<el-table-column prop="operation" label="操作" width="300px">
												<template slot-scope="scope">
													<el-button type="primary" v-if="scope.row.is_reseller == 0" size="small" @click="join(scope.row.id,scope.$index)">设为分销商</el-button>
													<el-button type="danger" v-if="scope.row.is_reseller == 1" size="small"  @click="cancel(scope.$index,scope.row.id)">取消</el-button>
												</template>
											</el-table-column><strong></strong>
										</el-table>
									</div>
									<div class="t_c">
										<el-pagination style="margin-top: 50px;" background layout="prev, pager, next" :total="all2.length"
										 :page-size="size" @current-change="jump_page2">
										</el-pagination>
									</div>
								</el-tab-pane>
								<el-tab-pane label="分销商统计" name="3">
									<el-row style="background-color: #FFFFFF;" :gutter="20">
										<el-col :span="12" style="height: 800px;">
											<el-row style="display: flex;justify-content: flex-start; padding: 10px; height: 70px;line-height: 50px;">
												<span>按月查询：</span>
												<el-date-picker v-model="value2" type="month" :placeholder="now" format="yyyy年MM月" value-format="timestamp"
												 @change="get_month(value2)">
												</el-date-picker>
											</el-row>
											<ve-line :data="chartData" :settings="chartSettings" height="800px" width = "600px"></ve-line>
										</el-col>
										<!-- <el-col :span="0.6" style="background-color: #F3F3F3;height: auto"></el-col> -->
										<el-col :span="12" style="height: 600px;">
											<el-row>
												<el-col :span="12" style="display: flex;justify-content: flex-start; padding: 10px; height: 70px;line-height: 50px;">
													<span>按月查询：</span>
													<el-date-picker v-model="value3" type="month" :placeholder="now" format="yyyy年MM月" value-format="timestamp"
													 @change="get_monthB(value3)">
													</el-date-picker>
												</el-col>
												<el-col :span="12" style="display: flex;justify-content: flex-start; padding: 10px; height: 70px;line-height: 50px;">
													总数：
												</el-col>
											</el-row>
											<el-row style="height: 50px;"></el-row>
											<el-table :data="tableData" style="width: 100%;padding: 0px;" stripe>
												<el-table-column type="index" label="排名" width="100">
												</el-table-column>
												<el-table-column prop="nickname" label="昵称">
												</el-table-column>
												<el-table-column label="头像">
													<template slot-scope="scope">
														<img style="width: 60px; height: 60px;" :src="scope.row.headpic" />
													</template>
												</el-table-column>
												<el-table-column prop="num" label="总订单">
												</el-table-column>
												<el-table-column prop="" label="总佣金">
													<template slot-scope="scope">
													{{parseFloat(scope.row.all_money).toFixed(2)}}
													</template>
												</el-table-column>
												<el-table-column prop="" label="未提现佣金">
													<template slot-scope="scope" >
													{{parseFloat(scope.row.money).toFixed(2)}}
													</template>
												</el-table-column>
											
											</el-table>
										</el-col>
									
									</el-row>
								</el-tab-pane>
								<el-tab-pane label="佣金记录" name="4">
									<el-table :data="tableDataYj" stripe style="width: 100%">
										<el-table-column type="index" label="序号" width="80">
										</el-table-column>
										<el-table-column prop="order_num" label="订单号" width="180"></el-table-column>
										<el-table-column prop="user_id" label="用户ID"></el-table-column>
										<el-table-column prop="agent_id" label="分销商ID"></el-table-column>
										<el-table-column prop="money" label="佣金"></el-table-column>
										<el-table-column prop="create_time" label="日期"></el-table-column>
										<el-table-column prop="status" label="申请提现">
											<template slot-scope="scope">
												<span v-if="scope.row.status == 0">未申请</span>
												<span v-if="scope.row.status == 1">申请中</span>
												<span v-if="scope.row.status == 2">已完成</span>
											</template>
										</el-table-column>
										<el-table-column label="提现状态">
											<template slot-scope="scope">
												<span v-if="scope.row.cpy_pay_status == 0">未打款</span>
												<span v-if="scope.row.cpy_pay_status == 1">打款成功</span>
												<span v-if="scope.row.cpy_pay_status == 2">打款失败</span>
												<span v-if="scope.row.cpy_pay_status == 3">当天未打款</span>
											</template>
										</el-table-column>
									</el-table>
								</el-tab-pane>
							</el-tabs>
							<div style="height:20px;">&nbsp;</div>
							<div style="height:20px;">&nbsp;</div>
							<div class="t_c" v-if="activeName==2">
								<span slot="footer" class="dialog-footer ">
									<el-button @click="jumpback">取 消</el-button>
									<el-button type="primary" @click="sub_add" >确 定</el-button>
								</span>
							</div>
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
			this.chartSettings = {
				xAxisType: 'time',
				labelMap: {
					'day': '日期',
					'order_num': '用户量',
					'user_num': '订单量'
				},
				legendName: {
					'访问用户': '访问用户 total: 10000'
				}
			}
			
			return {
				is_reseller:1,
				activeName: '1',
				no_fx_list: '',
				no_fx_list:{},
				fx_users: [],
				dialogVisible: false,
				all2:{},
				nickname: '',
				dialogImageUrl: '',
				oid: 0,
				list: [],
				all: '',
				size: 10,
				total: 0,
				total2: 0,
				tableDataB:[],
				//分销商统计
				now: '',
				value2: '',
				value3: '',
				userList: '',
				points_rank: '',
				chartData: {
					columns: ['day', 'order_num', 'user_num'],
					rows: []
				},
				tableData: [],
				//佣金
				tableDataYj: [ ],
			}
		},
		components: {
			Header,
			NavTo
		},
		watch: {
			dialogVisible(n, o) {
				if (!n) {
					this.fx_users = []
				}
			}
		},
		mounted() {
			this._load();
			this.get_all_no_fx_user();
			this.get_fx_data();
			this.get_data();
			var date = new Date();
			this.now = date.toLocaleDateString();
		},
		methods: {
			jumpback() {
				this.$router.push({
					path: '/extend/reseller'
				})
			},
			get_tab_name(e) {
				console.log(e)
			},
			//删除分销用户
			del_fx_user(id) {
				this.$confirm('确定取消该用户分销商资格?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.del('fx/admin/cancel_fx_agent?uid=' + id).then(res => {
						this.$message({
							type: 'success',
							message: '操作成功!'
						});
						this._load()
					})
				})
			},
			//提交添加分销商
			sub_add(){
				console.log(this.tableDataB)
				this.http.post('fx/admin/add_fx_agent',{
					ids:this.tableDataB
				}).then(res=>{
					this.$message({
						type: 'success',
						message: '操作成功!'
					});
					this._load();
					this.get_all_no_fx_user()
				})
			},
			//获取所有分销商
			_load() {
				this.http.get('fx/admin/get_fx_agent').then(res => {
					this.list = res.data
					this.all = res.data
					this.total = res.data.length
					this.list = res.data.slice(0, this.size)
				}) 
				this.http.get('fx/admin/get_record').then(res => {
					this.tableDataYj = res.data
				})
			},
			//获取非分销商用户
			get_all_no_fx_user() {
				this.http.get('fx/admin/get_no_fx_agent').then(res => {
					for(let k in res.data){
						let v = res.data[k]
						v.is_reseller = 0
					} 
					this.all2 =  res.data
					this.no_fx_list =  res.data.slice(0, this.size)
				})
			},
			//选择加入分销商
			join(item,index) {
				this.no_fx_list[index].is_reseller = 1
				const that = this
				let list = that.tableDataB
				if (list.length == 0) {
					that.tableDataB.push(item) 
				} else {
					if (JSON.stringify(list).indexOf(JSON.stringify(item)) == -1) {
						//使用string方法判断数组中对象是否重复需要保证对象中值的顺序一致
						list.push(item); 
					} else {
						this.$message({
							message: '请勿重复添加',
							type: 'warning'
						});
					}
				}
			},
			//取消选择
			cancel(index, id) {
				this.no_fx_list[index].is_reseller = 0
				for (let s of this.tableDataB) {
					if (id == s) {
						this.tableDataB.splice(index, 1)
						console.log(111)
					}
				}
				console.log(this.tableDataB)
			},
			
			open(id, index) {
				let obj = [{
					msg: '取消权限，恢复普通用户？',
					auth: 0
				}, {
					msg: '将该用户设置为管理员？',
					auth: 1
				}, {
					msg: '设置为员工，仅有验证权限？',
					auth: 2
				}]
				let msg = obj[index].msg
				let auth = obj[index].auth
				this.$confirm(msg, '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post_show("cms/admin/set_web_auth", {
						id: id,
						auth_id: auth
					}).then(res => {
						this.$message({
							type: 'success',
							message: '操作成功!'
						});
						this._load()
					});
				})
			},


			reset() {
				this.no_fx_list = []
				this.total2=0
			},
			search(key) {
				const that = this
				that.no_fx_list = []
				for (let k in that.all2) {
					let v = that.all2[k]
					if (v.nickname.indexOf(key) >= 0) {
						that.no_fx_list.push(v)
					}
				}
				that.total2 = this.no_fx_list.length;
				that.no_fx_list = that.no_fx_list.slice(0, that.size);
				this.nickname = []
			},
			handleChange() {

			},
			handleRemove(file, fileList) {
				console.log(file, fileList);
			},
			handlePictureCardPreview(file) {
				this.dialogImageUrl = file.url;
				this.dialogVisible = true;
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.list = this.all.slice(start, end);
			},
			jump_page2(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.no_fx_list = this.all2.slice(start, end);
			},
			sub() {},
			//删除权限
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {

				})
			},
			
			//分销商统计函数
			get_fx_data(month) {
				if (!month) {
					this.http.post('fx/admin/count_fx').then(res => {
						this.tableData = res.data
					})
				}else{
					this.http.post('fx/admin/count_fx',{month:month}).then(res => {
						this.tableData = res.data
					})
				}
			
			},
			get_data() {
				this.http.post('statistic/admin/get_table').then(res => {
					this.chartData.rows = res.data
				})
			},
			get_month(month) {
				this.http.post('statistic/admin/get_table', {
					month: month / 1000
				}).then(res => {
					this.chartData.rows = res.data
				})
				console.log(month)
			},
			get_monthB(month){
				this.get_fx_data(month/1000)
			},
			handleOpen(key, keyPath) {
				console.log(key, keyPath);
			},
			handleClose(key, keyPath) {
				console.log(key, keyPath);
			},

		},

	}
</script>

<style lang="less">
	.user {
		background-color: #F3F3F3;
		.t_c{text-align: center;}
		.el-table__row {
			line-height: 40px !important;

			img {
				width: 80px !important;
				height: 80px !important;
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
		}

		.user_sear {
			line-height: 40px;
			text-align: left;
			color: #6B6B6B;
			font-size: 14px;
			padding: 15px 15px 25px;

			.sear_01 {
				display: flex;
				margin-bottom: 0px;

				.sear_01_01 {
					display: flex;
					margin-right: 30px;

					.sear_01_01_1 {
						flex-shrink: 0;
					}

					.el-input__inner {
						width: 200px;
					}
				}
			}

			.sea_02_02_r_2_1 {
				background-color: #008DE1;
				color: #fff;
				padding: 0 10px;
				margin-right: 10px;
				border-radius: 3px;
				width: 45px;
			}
		}

		.article {
			background-color: #fff;
			padding: 15px;
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
		.fx-con{margin-top: 20px;}
		
		//分销商统计css
		.reseller {
			.ve-line {
				height: 600px;
			}
		}
	}
</style>
