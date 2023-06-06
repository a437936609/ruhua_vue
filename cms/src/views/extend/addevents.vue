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
				form: {
					name: ''
				}
			}
		},
		components: {
			Header,
			NavTo
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
