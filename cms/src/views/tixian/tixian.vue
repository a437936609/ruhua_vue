<template>
	<div class="tixian">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<transition appear appear-active-class="animated fadeInLeft">

					<el-main style="background-color: #F3F3F3;" align="left">
						<Zhishi style="background-color: #FFFFFF !important; " :type="type" :list="list"></Zhishi>
						<el-row>
							<el-button type="success" size='small' @click="_loadstate(-1)">已驳回</el-button>
							<el-button type="success" size='small' @click='_loadstate(1)'>已同意</el-button>
							<el-button type="success" size='small' @click='_loadstate(0)'>待同意</el-button>
							<el-button type="success" size='small' @click='_load'>刷新</el-button>
						</el-row>
						<br>
						<el-table :data="tableDatap" stripe style="width: 100%">
							<el-table-column type="index" label="序号" width="80">
							</el-table-column>
							<el-table-column prop="bk_uname" label="姓名" width="100">
								<template slot-scope="scope">
									{{scope.row.bk_uname}}
								</template>
							</el-table-column>
							<el-table-column prop="create_time" label="日期">
								<template slot-scope="scope">
									{{scope.row.create_time}}
								</template>
							</el-table-column>
							<el-table-column prop="money" label="提现金额">
								<template slot-scope="scope">
									{{scope.row.money}}
								</template>
							</el-table-column>
							<el-table-column prop="card" label="银行卡号">
								<template>

								</template>
							</el-table-column>
							<el-table-column prop="msg" label="备注">

							</el-table-column>
							<el-table-column prop="status" label="状态">
								<template slot-scope="scope">
									<template v-if="scope.row.status==0">待同意</template>
									<template v-if="scope.row.status==-1">已驳回</template>
									<template v-if="scope.row.status==1">已同意</template>
								</template>
							</el-table-column>
							<el-table-column label="操作">
								<template slot-scope="scope">
									<el-button size="small" type="primary" @click="agree(scope.row.id)"
										:disabled="scope.row.status!=0?true:false">同意</el-button>
									<el-button size="small" type="danger" @click="refuse(scope.row.id)" slot="reference"
										:disabled="scope.row.status!=0?true:false">驳回</el-button>
									<el-button size="small" type="primary" @click="refusezd(scope.row.id)"
										:disabled="scope.row.status!=0?true:false">备注</el-button>
								</template>
							</el-table-column>
						</el-table>
						<el-pagination style="margin-top: 50px;" background layout="prev, pager, next" :total="total"
							:page-size="size" @current-change="jump_page">
						</el-pagination>
					</el-main>
				</transition>
			</el-container>
		</el-container>
		<el-dialog title="提示" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
			<el-input v-model="msg" placeholder="请输入驳回原因"></el-input>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="sub_refuse">确 定</el-button>
			</span>
		</el-dialog>
		<el-dialog title="提示" :visible.sync="dialogVisiblezd" width="30%" :before-close="handleClosezd">
			<el-input v-model="msgzd" placeholder="请输入账单号"></el-input>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancelzd">取 消</el-button>
				<el-button type="primary" @click="sub_refusezd">确 定</el-button>
			</span>
		</el-dialog>
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
	import Zhishi from "@/components/zhishi.vue";
	export default {
		data() {
			return {
				list: [
					"分销提现可直接到微信零钱， 但需到微信商户平台开通“ 付款到零钱” 功能",
					"也可以选择提交提现申请， 人工打款;以下是提示申请列表",
					"更多营销应用开发中，敬请期待",
				],
				type: "default",
				dialogVisible: false,
				dialogVisiblezd: false,
				msg: '',
				msgzd: '',
				rid: '',
				tableData: [],
				tableDatap: [],
				size: 10,
				total: 0,
			}
		},
		components: {
			NavTo,
			Header,
			Zhishi,
		},
		mounted() {
			this._load()
		},
		methods: {
			_load() {
				this.http.get('fx/admin/get_user_record').then(res => {
					this.tableData = res.data;
					this.tableDatap = this.tableData.slice(0, 10);
					this.total = res.tableData.length
				})
			},

			_loadstate(status) {
				this.http.get('fx/admin/get_user_record_status?status=' + status).then(res => {
					this.tableData = res.data;
					this.tableDatap = this.tableData.slice(0, 10);
					this.total = res.tableData.length
				})
			},

			agree(id) {
				this.$confirm('同意提现申请？', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post_show('order/admin/tui_money', {
						id: id
					}).then(res => {
						if (res.status == 400) {
							this.$message({
								message: res.msg,
								type: 'warning'
							});
						} else {
							this.$message({
								message: '退款成功',
								type: 'success'
							});
						}
						this._load()
					})
				})
			},
			refusezd(id) {
				this.dialogVisiblezd = true
				this.rid = id
			},

			sub_refusezd() {
				let refuse_data = {
					id: this.rid,
					msg: this.msgzd
				}
				if (!refuse_data.msg) {
					this.$message({
						message: '未填写账单号',
						type: 'warning'
					});
					return
				}
				this.http.get('fx/admin/up_money_msg?id=' + this.rid + '&msg=' + this.msgzd).then(res => {
					this.$message({
						message: '备注成功',
						type: 'success'
					});
					this.msgzd = ''
					this.dialogVisiblezd = false
					this._load()
				})
			},
			cancelzd() {
				this.dialogVisiblezd = false
				this.msgzd = ''
			},
			refuse(id) {
				this.dialogVisible = true
				this.rid = id
			},
			sub_refuse() {
				let refuse_data = {
					id: this.rid,
					msg: this.msg
				}
				if (!refuse_data.msg) {
					this.$message({
						message: '未填写驳回原因',
						type: 'warning'
					});
					return
				}
				this.http.post_show('order/admin/tui_bohui', refuse_data).then(res => {
					if (res.status == 200) {
						this.$message({
							message: res.msg,
							type: 'success'
						});
						this.msg = ''
						this.dialogVisible = false
						this._load()
					} else {
						this.$message({
							message: res.msg,
							type: 'warning'
						});
						this.msg = ''
						this.dialogVisible = false
					}

				})
			},
			cancel() {
				this.dialogVisible = false
				this.msg = ''
			},
			cancelzd() {
				this.dialogVisiblezd = false
				this.msgzd = ''
			},
			handleClose() {
				this.dialogVisible = false
				this.msg = ''
			},
			handleClosezd() {
				this.dialogVisiblezd = false
				this.msgzd = ''
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.tableDatap = this.tableData.slice(start, end);
			},
		},

	}
</script>

<style lang="less">
	.tixian {
		background-color: #FFFFFF !important;

		.el-table__row {
			line-height: 40px !important;

			img {
				width: 80px !important;
				height: 80px !important;
			}
		}

		.el-main {
			height: auto !important;
			// background: #fff;

			.el-table {
				height: auto !important;
			}

			.el-table__body-wrapper,
			.is-scrolling-none,
			.el-table__body {
				height: auto !important;
			}
		}

		.article {
			line-height: 30px;
			background-color: #fff;
			padding: 15px;
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
