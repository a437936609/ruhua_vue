<template>
	<div class="coupon">
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
						<div class="article">
							<div class="liat-head-02" style="padding-left:10px;">
								<el-input placeholder="直播间名称" style='width: 250px' size='small' v-model="value" @keyup.enter.native="serach(value)" > </el-input>
							
							<el-button size='small' @click='serach(value)' style="padding-left:10px;"><i class="el-icon-search"></i></el-button>
							<el-button type="info" size='small' @click='refresh()' style="padding-left:10px;">刷新</el-button>
							<el-button type="primary" size='small' @click='get_tc_data()' style="padding-left:10px;"><i class="el-icon-refresh"></i>同步直播间</el-button>
							</div>
							<div style="height:20px;">&nbsp;</div>
							<template>
								<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="roomid" label="直播间id"></el-table-column>
									<el-table-column prop="name" label="直播间名称"></el-table-column>
									<el-table-column prop="anchor_name" label="主播昵称"></el-table-column>
									<el-table-column label="直播时间" width="400px">
										<template slot-scope="scope">
											{{scope.row.start_time|formatDate}}———{{scope.row.end_time|formatDate}}
											<!-- <p v-else style="color:#E6A23C;">未收货</p> -->
										</template>
									</el-table-column>
									<el-table-column  label="状态">
										<template slot-scope="scope">
											<p v-if="scope.row.live_status == 101">直播中</p>
											<p v-if="scope.row.live_status == 102">未开始</p>
											<p v-if="scope.row.live_status == 103">已结束</p>
											<p v-if="scope.row.live_status == 104">禁播</p>
											<p v-if="scope.row.live_status == 105">暂停</p>
											<p v-if="scope.row.live_status == 106">异常</p>
											<p v-if="scope.row.live_status == 107">已过期</p>
											<!-- <p v-else style="color:#E6A23C;">未收货</p> -->
										</template>
									</el-table-column>
								</el-table>
							</template>
							<el-pagination style='padding-top: 60px;text-align: center;' background layout="prev, pager, next" :total="total"
							 :page-size="size" @current-change="jump_page">
							</el-pagination>
						</div>
					</el-main>
				</transition>
			</el-container>
		</el-container>

	</div>
</template>

<script>
	import NavTo from '@/components/navTo.vue'
	import Header from '@/components/header.vue'
	export default {
		data() {
			return {
				list: [],
				listAll:[],
				start:0,
				limit:100,
				size: 10,
				total: 0,
				value:'',
			};
		},
		filters:{
				formatDate: function(value) {//10位时间戳转换
					let date = new Date(parseInt(value) * 1000);
					let y = date.getFullYear();
					let m = date.getMonth() + 1;
					m = m < 10 ? "0" + m : m;
					let d = date.getDate();
					d = d < 10 ? "0" + d : d;
					let h = date.getHours();
					h = h < 10 ? "0" + h : h;
					let minute = date.getMinutes();
					let second = date.getSeconds();
					minute = minute < 10 ? "0" + minute : minute;
					second = second < 10 ? "0" + second : second;
					return y + "年" + m + "月" + d + "日 " + h + ":" + minute + ":"+second;
			}},
		components: {
			Header,
			NavTo
		},
		mounted() {
			this.get_tc_data()
		},
		methods: {
			
			//搜索
			serach(key) {
				console.log(key)
				// this.getProductList(1, this.value);
				let arr = []
				for (let s of this.listAll) {
					let a = s.name.indexOf(key)
					if (a >= 0) {
						arr.push(s)
					}
				}
				this.list = arr
			},
			//刷新
			refresh(){
				this.list =  this.listAll.slice(0, this.size);
			},
			//获取直播信息
			get_tc_data() {
				this.http.get('/zb/liveTvList?start='+this.start+'&limit='+this.limit).then(res => {
					this.listAll = res.data.room_info
					this.total = res.data.total
					this.list =  this.listAll.slice(0, this.size);
					if(res.data.errcode==40001){
						
						this.http.get('/zb/liveTvList?start=-1&limit='+this.limit).then(res1 => {
							this.listAll = res1.data.room_info
							this.total = res1.data.total
							this.list =  this.listAll.slice(0, this.size);
						})
					}
				})
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.list = this.listAll.slice(start, end);
			},
			
			
		}

	}
</script>

<style lang="less">
	.coupon {
		background-color: #F3F3F3;

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
	.liat-head-02 {
		font-size: 14px;
		padding-left: 10px
	}
</style>
