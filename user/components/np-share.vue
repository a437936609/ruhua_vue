<template>
	<view class="uni-popup-share">
		<view class="uni-share-title"><text class="uni-share-title-text">— · 分享 · —</text></view>
		<view class="">
			<block v-if="list.fx>0">
				<view class="st_tit" >分享后预计可赚取佣金¥{{list.fx}}</view>
			<view class="st_des" >朋友通过你分享的页面成功买够后，你可获得对应的佣金。
			佣金可在“我的页面-分销中心”里查看</view>
			</block>
		</view>
		<view class="uni-share-content">
			<view class="uni-share-content-box">
				<!-- #ifdef MP-WEIXIN -->
				<button class="uni-share-content-item" open-type="share">
					<image class="uni-share-image" src="/static/img/share1.png"></image>
					<text class="uni-share-text">朋友</text>
				</button>
				<!-- #endif -->
				<view class="uni-share-content-item" v-for="(item,index) in bottomData" :key="index" @click.stop="select(item,index)">
					<image class="uni-share-image" :src="item.icon" mode="aspectFill"></image>
					<text class="uni-share-text">{{item.text}}</text>
				</view>
			</view>
		</view>
		<view class="uni-share-button-box">
			<button class="uni-share-button" @click="close">取消</button>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'UniPopupShare',
		props: {
			title: {
				type: String,
				default: '分享到'
			},
			goods: {
				type: Object,
				default: () => {
					return {}
				}
			},
			path: {
				type: String,
				default: ""
			},
			list:{
				type: Object,
				default: () => {
					return {}
				}
			}
		},
		inject: ['popup'],
		data() {
			return {
				bottomData: [{
						text: '生成海报',
						icon: '/static/img/share2.png',
						name: 'canvas'
					},
					// #ifndef MP-WEIXIN
					{
						text: '复制链接',
						icon: '/static/images/share_link.png',
						name: 'link'
					},
					// #endif
				]
			}
		},
		methods: {
			/**
			 * 选择内容
			 */
			select(item, index) {
				this.$emit('select', {
					item,
					index
				}, () => {
					this.popup.close()
				})
			},
			/**
			 * 关闭窗口
			 */
			close() {
				console.log(this.list)
				// this.popup.close()
			}
		}
	}
</script>
<style lang="scss" scoped>
	.uni-popup-share {
		padding-top: 15px;
		background-color: #fff;
		text-align: center;
	}
	
	.st_tit {
		font-size: 16px;
	}
		
	.st_des {
		padding: 10px 20px 0;
		color: #ADAEB0;
		font-size: 13px;
	}
	.uni-share-title {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: row;
		align-items: center;
		justify-content: center;
		height: 40px;
	}

	.uni-share-title-text {
		font-size: 14px;
		color: #666;
	}

	.uni-share-content {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: row;
		justify-content: center;
		padding-top: 10px;
	}

	.uni-share-content-box {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: row;
		flex-wrap: wrap;
		width: 360px;
	}

	.uni-share-content-item {
		width: 90px;
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: column;
		justify-content: center;
		padding: 10px 0;
		align-items: center;
		height: 200rpx;
	}

	.uni-share-content-item:active {
		background-color: #f5f5f5;
	}

	.uni-share-image {
		width: 30px;
		height: 30px;
	}

	.uni-share-text {
		margin-top: 10px;
		font-size: 14px;
		color: #3B4144;
	}

	.uni-share-button-box {
		/* #ifndef APP-NVUE */
		display: flex;
		/* #endif */
		flex-direction: row;
		padding: 10px 15px;
	}

	.uni-share-button {
		flex: 1;
		border-radius: 50px;
		color: #666;
		font-size: 16px;
	}

	.uni-share-button::after {
		border-radius: 50px;
	}

	.uni-share-content-box button{
		border-radius: 0;
		background-color: #fff;
		padding: 0;
		margin: 0;
		line-height:inherit;
	}

	.uni-share-content-box button::after {
		border: none;
	}
</style>
