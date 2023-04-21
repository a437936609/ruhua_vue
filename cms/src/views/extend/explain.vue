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
						<div class="article ">
							<el-button  @click="jumpback">返回</el-button>
							<div style="height:20px;">&nbsp;</div>
							<div class="baidu">
							<iframe :src="src" style="background-color:transparent; position: absolute; z-index: 99; width: 100%; height: 100%; top: 0px;left:0px;" 
							frameborder="0" ></iframe>
						</div>
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
		name: "",
		data() {
			return {
				src:''
			}
		},
		components:{
			NavTo,
			Header
		},
		mounted() {
			this.src=localStorage.getItem("explain");
		},
		methods: {	
			//返回
			jumpback() {
				this.$router.push({
					path: '/extend/application'
				})
			},
		},
	};
</script>

<style lang="less" >
.bg-main{background-color: #F5F7F9;margin: 0px;padding: 20px;}
.baidu{position: relative;min-height: 730px;}
	.article{text-align: left;}
</style>
