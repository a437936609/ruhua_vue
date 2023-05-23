<template>
	<view>
		<view class="navbar">
			<view v-for="(item, index) in packList" :key="index" class="nav-item" :class="{current: tabCurrentIndex === index}" @click="tabClick(index)">包裹{{index + 1}}</view>
		</view>
		<view  v-for="(item, index) in packList" :key="index" class="pack-item" :class="{ current: tabCurrentIndex === index}">
			<view class="example-title">承运公司：{{item.name}}</view>
			<view class="example-title" v-if="item.number">快递单号：{{item.number}}</view> 
			<view class="example-body" v-if="item.list">
				<uni-steps :options="item.list" :active="0" direction="column" />
			</view>
		</view>
	</view>
</template>

<script>
	import uniSteps from '@/components/uni/uni-steps/uni-steps.vue'
	export default {
		components: {
			uniSteps
		},
		data() {
			return { 
				list: [],
				id: '',
				kd_cpy:'',
				kd_num:'',
				name_list: [{
						name: '顺丰速运',
						value: 'SF',
					},
					{
						name: '百世快递',
						value: 'HTKY',
					},
					{
						name: '中通快递',
						value: 'ZTO',
					},
					{
						name: '申通快递',
						value: 'STO',
					},
					{
						name: '圆通速递',
						value: 'YTO',
					},
					{
						name: '韵达速递',
						value: 'YD',
					},
					{
						name: '邮政快递包裹',
						value: 'YZPY',
					},
					{
						name: 'EMS',
						value: 'EMS',
					},
					{
						name: '天天快递',
						value: 'HHTT',
					},
					{
						name: '京东快递',
						value: 'JD',
					},
					{
						name: '优速快递',
						value: 'UC',
					},
					{
						name: '德邦快递',
						value: 'DBL',
					},
					{
						name: '宅急送',
						value: 'ZJS',
					},
					{
						name: 'UPS',
						value: 'UPS',
					},
					{
						name: '其他',
						value: '0',
					}

				],
				tabCurrentIndex: 0,
				packList: []
			}
		},
		onLoad(options) {
			this.id = options.id
			this.get_courier()
		},
		methods: {
			change() {
				if (this.active < this.list1.length - 1) {
					this.active += 1
				} else {
					this.active = 0
				}
			},
			get_courier() {
				const that = this
				this.$api.http.post('order/get_kd', {
					id: this.id
				}).then(res => { 
					console.log(res);
					if(res.status > 200){
						this.$api.msg(res.msg)
					}
					const temp = []
					;(res || []).forEach((item) => {
						const list = []
						for (let k in item.result.list) {
							const v= item.result.list[k]
							list[k]={}
							list[k]['title']=v.status
							list[k]['desc']=v.time
						} 
						temp.push({
							name: item.result.expName,
							number: item.result.number,
							list
						})
					})
					this.packList = temp
					console.log(this.packList)
					// this.kd_cpy = res.result.expName
					// this.kd_num = res.result.number
					// let list=[]
					// for (let k in res.result.list) {
					// 	const v=res.result.list[k]
					// 	list[k]={}
					// 	list[k]['title']=v.status
					// 	list[k]['desc']=v.time
					// } 
					// this.list = list 
				})

			},
			tabClick (index) {
				this.tabCurrentIndex = index
			}
		}
	}
</script>

<style lang ="scss">
	page {
		display: flex;
		flex-direction: column;
		box-sizing: border-box;
		background-color: #efeff4
	}

	view {
		font-size: 28upx;
		line-height: inherit
	}

	.example {
		padding: 0 30upx 30upx
	}

	.example-title {
		display: flex;
		justify-content: space-between;
		align-items: center;
		font-size: 26upx;
		color: #464e52;
		padding: 15upx 30upx 15upx 50upx;
		margin-top: 20upx;
		position: relative;
		background-color: #fdfdfd;
		border-bottom: 1px #f5f5f5 solid
	}
	.example-title + .example-title
	{
		margin-top: 0px;
	}

	.example-title__after {
		position: relative;
		color: #031e3c
	}

	.example-title:after {
		content: '';
		position: absolute;
		left: 30upx;
		margin: auto;
		top: 0;
		bottom: 0;
		width: 6upx;
		height: 32upx;
		background-color: #ccc
	}

	.example .example-title {
		margin: 40upx 0
	}

	.example-body {
		padding: 0px;
		background: #fff
	}

	.example-info {
		padding: 30upx;
		color: #3b4144;
		background: #fff
	}

	button {
		margin: 30upx;
	}
	.navbar {
		display: flex;
		height: 40px;
		padding: 0 5px;
		background: #fff;
		box-shadow: 0 1px 5px rgba(0, 0, 0, .06);
		position: relative;
		z-index: 10;

		.nav-item {
			padding: 0px 15px;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
			font-size: 15px;
			color: $font-color-dark;
			position: relative;

			&.current {
				color: $base-color;

				&:after {
					content: '';
					position: absolute;
					left: 50%;
					bottom: 0;
					transform: translateX(-50%);
					width: 44px;
					height: 0;
					border-bottom: 2px solid $base-color;
				}
			}
		}
	}
	.pack-item
	{
		display: none;
		&.current
		{
			display: block;
		}
	}
</style>
