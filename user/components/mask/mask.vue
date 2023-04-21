<template>
	<view class="mask2"> 
		<view class="cpt-mask">
			<view class="header" >
				<view :class="index < 2?'title':'title_01'" v-for="(item,index) in title_list" @click="change_tab(index)">
					{{item}}
				</view>
			</view>
			<view class="line"></view>
			<view class="content" v-if="tab_index == 0">
				<view class="content_item" style="margin-bottom: 20px;">
					<text >{{content}}</text>
				</view>
				
				<view class="line"></view>
			</view>
			<view class="content" v-if="tab_index == 1">
				<view class="content_item">
					<view class="content_item_title">
						用户名
					</view>
					<view class="content_item_title">
						奖品名称
					</view>
					<view class="content_item_title">
						中奖时间
					</view>
				</view>
				<view class="content_item" v-for="(item,index) in zj_list">
					<view class="content_item_title">
						{{item.user.nickname}}
					</view>
					<view class="content_item_title">
						{{item.content}}
					</view>
					<view class="content_item_title">
						{{item.create_time}}
					</view>
				</view>
				<view class="line"></view>
			</view>
			<view class="content" v-if="tab_index == 2">
				<view class="content_item">
					<view class="content_item_title">
						奖品名称
					</view>
					<view class="content_item_title">
						中奖时间
					</view>
				</view>
				<view class="content_item" v-for="(item,index) in gift_list">
					<view class="content_item_title">
						{{item.content}}
					</view>
					<view class="content_item_title">
						{{item.create_time}}
					</view>
				</view>
				<view class="line"></view>
			</view>
			<view class="" style="height: 60px;">
				
			</view>
			<view class="btn" style="position: fixed;bottom: 10px;width: 90%;text-align: center;">
				<icon type="cancel" size="30" :color="'#FFFFFF'" @click="close"/>				
			</view>
		</view>
 
	</view>
</template>

<script>
	export default {
		data() {
			return { 
				tab_index:0,
				phb_list:[1,2,3,4,5],
				title_list:['活动说明','最近中奖','我的奖品']
			}
		},
		props:["gift_list","zj_list","content"],
		 
		methods:{
			change_tab(index){ 
				this.tab_index = index
			},
			close(){
				this.$emit('close')
			}
		}
	}
</script>

<style lang="scss"> 
	.cpt-mask {
		overflow: scroll;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		// background-color: #000000;
		opacity: 1;
		z-index: 99999;
		padding: 20px;
		
		.content{
			color: #FFFFFF;
			overflow: scroll;
			.content_item{
				display: flex;
				justify-content: space-between;
				padding-top: 10px;
				padding-bottom: 10px;
			}
		}
		.header {
			width: 100%;
			color: #FFFFFF;
			display: flex;
			justify-content: space-around;
			opacity: 1 !important;
			margin-bottom: 20px;
			.title{
				width: 33%;
				text-align: center;
				border-right: 1px solid #FFFFFF;
			}
			.title_01{
				width: 33%;
				text-align: center;
				// border-right: 1px solid #FFFFFF;
			}
		}

		.line {
			
			color: #FFFFFF;
			height: 2px;
			border-bottom: 1px solid #FFFFFF;
			opacity: 1;
			margin-bottom: 20px;
		}
	}
</style>
