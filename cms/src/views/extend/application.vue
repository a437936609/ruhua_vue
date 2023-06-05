<template>
	<div>
		<el-container class="container">
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>

				<transition appear appear-active-class="animated fadeInLeft">
					<el-main>
						<div class="zhang">
							<div class="ying">
								<Zhishi :type="type" :list="list"></Zhishi>
								<div class="biao">应用</div>
								<div class="gong">
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color4">
												<img src="../../img/fenxiao1.png" />
											</div>
											<div class="gong-01-r">
												分销
												<br />
												<span>分销</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_toreseller">进入</el-button>
												<el-button size="mini" @click="jump_explain(1)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color1">
												<img src="../../img/biao.png" />
											</div>
											<div class="gong-01-r">
												自定义表单
												<br />
												<span>自定义表单</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_zdy">进入</el-button>
												<el-button size="mini" @click="jump_explain(2)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color2">
												<img src="../../img/pintuan.png" />
											</div>
											<div class="gong-01-r">
												拼团活动
												<br />
												<span>拼团</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_pt">进入</el-button>
												<el-button size="mini" @click="jump_explain(3)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color3">
												<img src="../../img/huiyuan.png" />
											</div>
											<div class="gong-01-r">
												会员套餐
												<br />
												<span>会员套餐</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_tovip_tc">进入</el-button>
												<el-button size="mini" @click="jump_explain(4)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color5">
												<img src="../../img/xianshi.png" />
											</div>
											<div class="gong-01-r">
												限时折扣
												<br />
												<span>限时折扣</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_todiscount">进入</el-button>
												<el-button size="mini" @click="jump_explain(5)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l">
												<img src="../../img/yhq.png" />
											</div>
											<div class="gong-01-r">
												优惠券
												<br />
												<span>优惠券</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_tocoupon">进入</el-button>
												<el-button size="mini" @click="jump_explain(6)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color7">
												<img src="../../img/yunfei.png" />
											</div>
											<div class="gong-01-r">
												运费模板
												<br />
												<span>运费模板</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_template">进入</el-button>
												<el-button size="mini" @click="jump_explain(7)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color7">
												<img src="../../img/qd.png" />
											</div>
											<div class="gong-01-r">
												签到
												<br />
												<span>签到</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_grade">进入</el-button>
												<el-button size="mini" @click="jump_explain(12)">说明</el-button>
											</div>
										</div>
									</div>
									
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color5">
												<img src="../../img/ceshi.png" />
											</div>
											<div class="gong-01-r">
												活动专题
												<br />
												<span>活动专题</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_events">进入</el-button>
												<el-button size="mini" @click="jump_explain(13)">说明</el-button>
											</div>
										</div>
									</div>
									
									
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color7">
												<img src="../../img/zhibo.png" />
											</div>
											<div class="gong-01-r">
												直播
												<br />
												<span>直播</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_livetv">进入</el-button>
												<el-button size="mini" @click="jump_explain(12)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="kong"></div>
									<div class="kong"></div>
								</div>
								<div class="line"></div>
								<div class="biao">saas 应用</div>
								<div class="gong">
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color2">
												<img src="../../img/ceshi.png" />
											</div>
											<div class="gong-01-r">
												测试数据
												<br />
												<span>新站点适用</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_toapply">进入</el-button>
												<el-button size="mini" @click="jump_explain(8)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color7">
												<img src="../../img/pingjia.png" />
											</div>
											<div class="gong-01-r">
												虚拟评价
												<br />
												<span>虚拟评价</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_toestimate">进入</el-button>
												<el-button size="mini" @click="jump_explain(9)">说明</el-button>
											</div>
										</div>
									</div>
									<!-- <div class="gong-01">
                    <div class="gong-01-bor">
                      <div class="gong-01-l color2">
                        <img src="../../img/haibao.png" />
                      </div>
                      <div class="gong-01-r">
                        海报生成
                        <br />
                        <span>生成海报</span>
                      </div>
                      <div class="gong-01-btn">
                        <el-button size="mini" @click="jump_tobill">进入</el-button>
                        <el-button size="mini" @click="jump_explain(10)">说明</el-button>
                      </div>
                    </div>
                  </div> -->
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color8">
												<img src="../../img/fapiao.png" />
											</div>
											<div class="gong-01-r">
												飞鹅小票机
												<br />
												<span>飞鹅小票机</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_feie">进入</el-button>
												<el-button size="mini" @click="jump_explain(11)">说明</el-button>
											</div>
										</div>
									</div>

									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color9">
												<img src="../../img/jgg.png" />
											</div>
											<div class="gong-01-r">
												九宫格
												<br />
												<span>九宫格</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_jgg">进入</el-button>
												<el-button size="mini" @click="jump_explain(11)">说明</el-button>
											</div>
										</div>
									</div>

									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color10">
												<img src="../../img/dzp.png" />
											</div>
											<div class="gong-01-r">
												大转盘
												<br />
												<span>大转盘</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_dzp">进入</el-button>
												<el-button size="mini" @click="jump_explain(11)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="gong-01">
										<div class="gong-01-bor">
											<div class="gong-01-l color11">
												<img src="../../img/tree.png" />
											</div>
											<div class="gong-01-r">
												种树
												<br />
												<span>种树</span>
											</div>
											<div class="gong-01-btn">
												<el-button size="mini" @click="jump_tree">进入</el-button>
												<el-button size="mini" @click="jump_explain(11)">说明</el-button>
											</div>
										</div>
									</div>
									<div class="kong"></div>
									<div class="kong"></div>
								</div>
							</div>
						</div>
					</el-main>
				</transition>
			</el-container>
		</el-container>
	</div>
</template>

<script>
	import NavTo from "@/components/navTo.vue";
	import Header from "@/components/header.vue";
	import Zhishi from "@/components/zhishi.vue";
	import axios from "axios";
	export default {
		name: "",
		data() {
			return {
				list: [
					"免费版无营销应用",
					"部分营销应用为saas版",
					"更多营销应用开发中，敬请期待",
				],
				type: "default",
				fenxiao: false,
				biaodan: true,
				pintuan: true,
				jifen: false,
				daili: false,
				quyu: false,
				qunfa: false,
			};
		},
		components: {
			NavTo,
			Header,
			Zhishi,
		},
		mounted() {},
		methods: {
			an_fenxiao() {
				this.$confirm("是否确定?", "提示", {
					confirmButtonText: "确定",
					cancelButtonText: "取消",
					type: "warning",
				}).then(() => {});
			},
			// 跳说明
			jump_explain(e) {
				if (e == 1) {
					localStorage.setItem(
						"explain",
						"http://read.ruhuashop.com/#/distribution"
					);
				}
				if (e == 2) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/form");
				}
				if (e == 3) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/pintuan");
				}
				if (e == 4) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/member");
				}
				if (e == 5) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/discount");
				}
				if (e == 6) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/coupon");
				}
				if (e == 7) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/yunfei");
				}
				if (e == 8) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/test");
				}
				if (e == 9) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/evaluate");
				}
				if (e == 10) {
					localStorage.setItem("explain", "");
				}
				if (e == 11) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/fapiao");
				}
				if (e == 12) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/jifen");
				}
				if (e == 13) {
					localStorage.setItem("explain", "http://read.ruhuashop.com/#/discount");
				}
				this.$router.push({
					path: "/extend/explain",
				});
			},
			jump_toreseller() {
				this.$router.push({
					path: "/extend/reseller",
				});
			},
			jump_tocoupon() {
				this.$router.push({
					path: "/coupon",
				});
			},
			jump_tovip_tc() {
				this.$router.push({
					path: "/vip_tc/vip_tc",
				});
			},
			jump_pt() {
				this.$router.push({
					path: "/extend/pt",
				});
			},
			jump_todiscount() {
				this.$router.push({
					path: "/extend/discount",
				});
			},
			jump_events() {
				this.$router.push({
					path: "/extend/events",
				});
			},
			jump_template() {
				this.$router.push({
					path: "/product/template",
				});
			},
			jump_zdy() {
				this.$router.push({
					path: "/extend/form",
				});
			},
			//加载loading
			async _loading() {
				const that = this;
				const loading = this.$loading({
					lock: true,
					text: "Loading",
					spinner: "el-icon-loading",
					background: "rgba(0, 0, 0, 0.7)",
				});
				let url = window.location.href
				let api_url = 'http://cloud.token.ruhuashop.com/get_token'
				url = url.slice(0, 5)
				console.log("url:", url)
				if (url != 'http:') {
					api_url = 'https://cloud.token.ruhuashop.com/get_token'
				}
				setTimeout(() => {
					loading.close();
				}, 3000);
				// res  0未授权  -1 授权未安装  1授权已安装
				const res = await axios.get(api_url).then((res) => {
					console.log(res)
					if (!res.data.token || res.data.token == null) {
						console.log(3333)
						this.$message.error(res.data.msg);
						loading.close();
						return 0
					}
					console.log('已授权，检测是否安装')
					localStorage.setItem('stoken', res.data.token)
					return this.http.post("plugIn/is_Install", {
							lock: "import_plug",
							stoken: res.data.token,
						})
						.then((res) => {
							loading.close();
							if (res.data) {
								return 1
							}
							return -1
						});
				});
				console.log(res)
				return res;
				// const res=await axios.get('http://cloud.token.ruhuashop.com/get_token').then(res=>{
				// 	loading.close();
				// 	if(res.status==400){
				// 		that.$message({
				// 			message: res.msg,
				// 			type: 'warning'
				// 		});
				// 		return false;
				// 	}
				// 	return true;
				// }).catch(err=>{
				// 	loading.close();
				// 	return false;
				// })
			},
			//跳转测试数据
			async jump_toapply() {
				const res = await this._loading();
				if(res == 1 || res == -1){
					this.$router.push({
						path: "/extend/apply",
					});
				}
				
			},
			//跳转虚拟评价
			async jump_toestimate() {
				const res = await this._loading();
				if(res == 1 || res == -1){
					this.$router.push({
						path: "/extend/estimate",
					});
				}
				
			},
			//跳转海报
			async jump_tobill() {
				const res = await this._loading();
				if (res) {
					this.$router.push({
						path: "/extend/bill",
					});
				}
			},
			//跳转飞鹅小票机
			async jump_feie() {
				const res = await this._loading();
				if(res == 1 || res == -1){
					this.$router.push({
						path: "/extend/bill",
					});
				}
				
			},

			//跳转九宫格
			async jump_jgg() {
				const res = await this._loading();
				if(res == 1 || res == -1){
					this.$router.push({
						path: "/extend/jgglist",
					});
				}
				
			},

			//跳转大转盘
			async jump_dzp() {
				const res = await this._loading();
				if(res == 1 || res == -1){
					this.$router.push({
						path: "/extend/dzplist",
					});
				}
				
			},
			jump_tree() {
				if(res == 1 || res == -1){
					this.$router.push({
						path: "/extend/tree/list",
					});
				}
				
			},

			//跳转签到
			async jump_grade() {
				const res = await this._loading();

				this.$router.push({
					path: "/extend/grade",
				});
			},
			//跳转直播
			jump_livetv() {
				this.$router.push({
					path: "/extend/live/livetv",
				});
			},
		},
	};
</script>

<style lang="less">
	.bg-main {
		background-color: #f5f7f9;
		margin: 0px;
		padding: 20px;
	}

	.ying {
		background-color: #fff;
		font-size: 14px;
		padding: 10px 10px 120px;

		.biao {
			padding: 10px;
			text-align: left;
		}
	}

	.gong {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		padding: 0 10px;

		.gong-01 {
			width: 23%;
			border: 1px solid #f0f0f0;
			border-radius: 5px;
			padding: 10px 10px 20px;
			margin-bottom: 15px;
			box-sizing: border-box;

			.gong-01-bor {
				border-bottom: 1px solid #e8e8e8;
				padding: 10px 0 5px;
				display: flex;

				.gong-01-l {
					background-color: #25b6d3;
					height: 80px;
					width: 80px;
					text-align: center;
					border-radius: 5px;

					img {
						width: 50px;
						height: 50px;
						margin-top: 15px;
					}
				}

				.gong-01-r {
					padding-left: 10px;
					font-weight: 600;
					line-height: 25px;
					flex-grow: 1;
					text-align: left;

					span {
						color: #807c80;
						font-size: 13px;
						font-weight: 300;
					}
				}

				.gong-01-btn {
					display: flex;
					align-self: flex-end;
				}
			}
		}

		.kong {
			width: 23%;
			height: 0;
		}

		.color1 {
			background-color: #f15d5b !important;
		}

		.color2 {
			background-color: #96ebff !important;
		}

		.color3 {
			background-color: #ffbf00 !important;
		}

		.color4 {
			background-color: #bcf2d8 !important;
		}

		.color5 {
			background-color: #add3fa !important;
		}

		.color6 {
			background-color: #2fbda5 !important;
		}

		.color7 {
			background-color: #bff5f0 !important;
		}

		.color8 {
			background-color: #fabfc9 !important;
		}

		.color9 {
			background-color: #fabfc9 !important;
		}

		.color10 {
			background-color: #fabfc9 !important;
		}

		.color11 {
			background-color: #96ebff !important;
		}
	}

	.line {
		// 分隔线
		height: 2px;
		background-color: rgb(243, 244, 244);
		margin-top: 10px;
	}
</style>
