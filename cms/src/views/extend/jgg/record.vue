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
				<div v-if="!is_install">
					<transition appear appear-active-class="animated fadeInLeft">
						<el-main>
							<div class="article">
								<div style="text-align: left;">
									<el-button @click="jumpback">返回</el-button>&emsp;
									<el-button type="primary" v-if="!is_install" @click="increase">安装</el-button>
								</div>
								<div style="height:20px;">&nbsp;</div>
								<!-- 进度条 -->
								<div class="bar" v-show="!is_install">
									<!-- elementui组件 -->
									<template>
										<el-progress :percentage="percentage" :stroke-width="10" :color="customColorMethod"></el-progress>
									</template>

									<template v-if="progsuc==1">
										<div style="width:100%;text-align:center;margin-top:60px;">
											<!-- <el-button style="width:10%" type="primary" @click="jumpback">已完成</el-button> -->
										</div>
									</template>
									<template v-else-if="progsuc==0">
										<div style="width:100%;text-align:center;margin-top:60px;">
											<el-button style="width:10%" type="success">安装中</el-button>
										</div>
									</template>
									<template v-else>
										<div style="width:100%;text-align:center;margin-top:60px;">
											<el-button style="width:10%" type="info">未安装</el-button>
										</div>
									</template>
								</div>
							</div>
						</el-main>
					</transition>
				</div>
				<div v-if="is_install">
					<transition appear appear-active-class="animated fadeInLeft">
						<el-main>
							<div class="article">
								<div style="text-align: left;">

									<el-button @click="jumpback">返回</el-button> &emsp;
									<div style="height:20px;">&nbsp;</div>
								</div>
								<template>
									<el-table :data="tableData" border style="width: 100%">
										<el-table-column type="index" label="序号" width="50px"></el-table-column>
										<el-table-column prop="img_id" label="头像" width="100">
											<template slot-scope="scope">
												<img class="navimg" :src="scope.row.user.headpic" />
											</template>
										</el-table-column>
										<el-table-column prop="user.nickname" label="用户"></el-table-column>
										<el-table-column prop="content" label="获奖"></el-table-column>
										<el-table-column prop="create_time" label="创建时间"></el-table-column>
										<el-table-column prop="operation" label="操作" width="300px">
											
											<template slot-scope="scope">
												<el-button style="margin-left: 10px" type="infor" size="small" slot="reference" @click="update(scope.row.id)" v-if="scope.row.state==0">确认发货</el-button>
												<p v-else>已发货</p>
												
											</template>
										</el-table-column>
										<strong></strong>
									</el-table>
								</template>
							</div>
						</el-main>
					</transition>
				</div>
				<el-dialog :title="edit_title" :visible.sync="is_disLogshow" width="30%" :before-close="handleClose">
					<el-form ref="form" :model="form" label-width="80px">
						<el-form-item label="名称">
							<el-input v-model="form.url_name"></el-input>
						</el-form-item>
						<el-form-item label="类型">
							<el-select v-model="form.type" placeholder="请选择类型" style="width: 100%;">
								<el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value">
								</el-option>
							</el-select>
						</el-form-item>

						<el-form-item label="所需积分" v-if="form.type==0||form.type==2">
							<el-input v-model="form.integral"></el-input>
						</el-form-item>

						<el-form-item label="每日次数" v-if="form.type==1||form.type==2">
							<el-input v-model="form.tice"></el-input>
						</el-form-item>

						<el-form-item label="奖品数量" style="text-align: left;">
							<el-radio v-model="form.reword" :label="0" border size="medium">奖品不限</el-radio>
							<el-radio v-model="form.reword" :label="1" border size="medium">奖品有限</el-radio>
						</el-form-item>
						<el-form-item label="导航图标" :label-width="formLabelWidth">
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
						<el-form-item label="活动状态" style="text-align: left;">
							<el-radio v-model="form.state" :label="0" border size="medium">关闭</el-radio>
							<el-radio v-model="form.state" :label="1" border size="medium">开启</el-radio>
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
	import {
		Loading
	} from "element-ui";
	import NavTo from "@/components/navTo.vue";
	import Header from "@/components/header.vue";
	import Pic from '../../PicList.vue'
	import {
		Api_url
	} from '@/common/config.js'

	export default {
		data() {
			return {
				is_install: true,
				is_add: true,
				is_disLogshow: false,
				edit_title: '添加',
				progsuc: -1,
				percentage: 0,
				tableData: [],
				formLabelWidth: '120px',
				getimg: this.$getimg,
				form: {
					url_name: '',
					type: 0,
					tice: 0,
					reword: 0,
					integral: 0,
					state: 0,
					img_id: '',
					id: ''
				},
				options: [{
					label: "积分抽奖",
					value: 0
				}, {
					label: "每日抽奖",
					value: 1
				}, {
					label: "积分+每日抽奖",
					value: 2
				}],
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
			this._load()
		},
		methods: {
			handleClose() {
				this.is_disLogshow = false
			},
			//返回
			jumpback() {
				this.$router.go(-1)
			},
			get_data() {
				if (this.is_install) {
					this.http.get('games/get_reword').then(res => {
						console.log(res)
						this.tableData = res.data
					})
				}

			},
			record(){
				this.$router.push('/extend/record')
			},
			_load() {



				this.http.post_show("plugIn/is_game_Install", {
					lock: "dzp"
				}).then(res => {
					if (res.msg == "没有权限") {
						this.show = false;
						this.$router.push({
							path: "/extend/application",
						});
						this.$message({
							message: "没有权限",
							type: "warning",
						});
					} else {
						if (res.status == 200) {
							if (res.data == true) {
								this.get_data()
								this.is_install = true;
							} else {
								this.is_install = false;
							}
						}
					}
				})




			},
			del(id) {
				this.http.del('games/del_dzp?id=' + id).then(res => {
					this.get_data()
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
			clear_data() {
				this.form.award = 0
				this.form.type = 0
				this.form.tice = 0
				this.form.reword = 0
				this.form.integral = 0
				this.form.state = 0
				this.form.img_id = ''
				this.form.id = ''
				this.form.url_name = ''
				this.img_list = []

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
			// 安装
			increase() {
				this.progsuc = 0;
				let prog = setInterval((_) => {
					this.percentage += 10;
					if (this.percentage > 100) {
						this.percentage = 100;
						clearInterval(prog);
						this.progsuc = 1;
						//this.show = true;
						this.is_install = true
					}
					if (this.cone != true) {
						if (this.percentage > 90) {
							this.percentage = 90;
							this.progsuc = 0;
							// clearInterval(prog);
						}
					}
				}, 400);
				let data = {
					pulg: "dzp",
					stoken: localStorage.getItem("stoken"),
				};
				this.http.post_show("plugIn/installGamePlug", data).then((res) => {
					if (res.status == 200) {
						this.cone = true;
					} else {
						this.cone = false;
					}
				});
			},
			rule(id) {
				this.$router.push({
					path: "/extend/dzprule",
					query: {
						id: id
					}
				});
			},
			add_game() {
				this.clear_data()
				this.is_disLogshow = true
			},
			cancel() {
				this.is_disLogshow = false
			},
			delimg(index) {
				this.img_list.splice(index, 1)
			},
			onswitch(id, state) {
				console.log(id)
				console.log(state)
				let st = 1
				if (state == 1) {
					st = 0
				}
				this.http.put_show('games/upadate_state', {
					id: id,
					state: st
				}).then(res => {
					this.get_data()
				})
			},
			sub_add() {
				if (this.is_add) {
					this.http.post('games/add_dzp', this.form).then(res => {
						if (res.data.status == 200) {
							this.$message({
								type: 'success',
								message: '添加成功!'
							});

						}
						this.clear_data()
						this.is_disLogshow = false
						this.get_data()
					})
				} else {
					this.http.post('games/update_dzp', this.form).then(res => {
						if (res.data.status == 200) {
							this.$message({
								type: 'success',
								message: '添加成功!'
							});

						}
						this.clear_data()
						this.is_disLogshow = false
						this.get_data()
					})
				}

			},
			update(id) {
				this.http.put_show('games/update_state?id='+id).then(res=>{
					this.get_data()
				})
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
