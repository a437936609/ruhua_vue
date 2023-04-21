<!--本页面由uniapp切片工具生成，uni-app切片-可视化设计工具(一套代码编译到7个平台iOS、Android、H5、小程序)，软件下载地址：http://www.ymznkf.com/new_view_669.htm -->
<template>
	<view class="content">
		<view class="wait_1">
			<view class="wait_12" style="height: 100vh;padding-top: 35%;">
				<view class="wait_2">
				</view>
				<image v-on:click="wait_3_3_click()" src="/static/wait/images/wait_chage.png" mode="scaleToFill" border="0" class="wait_3"></image>
				<span decode="true" class="wait_4">等待管理员审核中...</span>
				<!-- <text decode="true" class="wait_7">你本次消费的◎金积分次日到账</text> -->
					<!-- <text decode="true" class="wait_10" @click="get_info">刷新状态</text> -->
			</view>
		</view>


		<view class="loading">{{loadingText}}</view>
		<view class="ymBbottom"></view>
	</view>
</template>

<script>
	export default {
		data(){
			return{
				
			}
		},
		methods:{
			get_info(){
				uni.showLoading({
					title:'刷新中'
				})
				this.$api.http.get('user/info').then(res=>{
					uni.hideLoading()
					if(res.data.start == 2){
						uni.removeStorageSync('user_mode')
						uni.showToast({
							icon:'success',
							title:'已通过审核'
						})
						setTimeout(()=>{
							uni.switchTab({
								url:'../../pages/index/index'
							})
						},2000)
						
					}
				})
			},
		},
		onPullDownRefresh() {
			this.get_info()
		}
	}
</script>

<style lang="scss" scoped>
	@import './wait.scss';
</style>
