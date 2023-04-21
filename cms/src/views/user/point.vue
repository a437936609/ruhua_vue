<template>
	<div class="root">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<div>
						<transition appear appear-active-class="animated fadeInLeft">
							<el-main>
								<div class="article">
									<div style="text-align: left;">
					
										
										<div style="height:20px;">&nbsp;</div>
									</div>
									<template>
										<el-table :data="tableData" border style="width: 100%">
											<el-table-column type="index" label="序号" width="50px"></el-table-column>
											<el-table-column prop="img_id" label="头像" width="65">
												<template slot-scope="scope">
													<img v-if="scope.row.user.headpic" class="navimg" :src="scope.row.user.headpic" style="width: 40px;height: 40px;"/>
													<img v-else class="navimg" src="../../img/user.png" style="width: 40px;height: 40px;"/>
												</template>
											</el-table-column>
											<el-table-column prop="user.nickname" label="用户">
												<template slot-scope="scope">
													{{scope.row.user.nickname?scope.row.user.nickname:'未授权'}}
												</template>
											</el-table-column>
											<el-table-column prop="remark" label="内容"></el-table-column>
											<el-table-column prop="create_time" label="创建时间"></el-table-column>
											
											<strong></strong>
										</el-table>
									</template>
								</div>
								<el-pagination  style="margin-top: 50px;" background layout="prev, pager, next" :total="total"
								 :page-size="size" @current-change="jump_page">
								</el-pagination>
							</el-main>
						</transition>
					</div>
				</el-main>
			</el-container>
		</el-container>
	</div>
</template>

<script>
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	export default {
		data(){
			return{
				tableDatas:[],
				tableData:[],
				size: 10,
				total: 0,
			}
		},
		components: {
			NavTo,
			Header
		},
		mounted() {
			this.get_data()
		},
		methods:{
			get_data() {
					this.http.get('points/admin/get_record').then(res => {
					this.tableDatas = res.data
					this.total = this.tableDatas.length;
					this.tableData = this.tableDatas.slice(0, 10);
					})
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.tableData = this.tableDatas.slice(start, end);
			},
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
