<template>
	<view class="tixian">
		<view class="one">
			<view class="t_tit">提现到</view>
			<view class="head_btn" @click="jump_card(-1,'add')">添加银行卡</view>
		</view>
		<view class='swip-box'>
			<swiper @change="changeindex" class="swiper" :indicator-dots="indicatorDots" :autoplay="autoplay" :interval="interval"
			 :duration="duration">
				<swiper-item v-for="(item,index) of card" :key="index" style="height: 150px;">
					<view class="card" :class="bank_bg" @click="jump_card(index,'edit')">
						<view class="card_l"><img :src="require('@/static/img/bank'+item.card_type+'.png' )" /></view>
						<view class="card_r">
							<!-- <view class="card_r_1">{{item.bk_name}}</view> -->
							<view class="card_r_2">{{item.bk_uname}}</view>
							<view class="card_r_3">{{item.card}}</view>
						</view>
					</view>
				</swiper-item>
			</swiper>
		</view>
		<view class="BH"></view>
		<view class="je">提现金额</view>
		<view class="hsuru">
			<view class="hu_l">
				<input v-model="num" placeholder="0" placeholder-style="color:#000;font-size: 24px;font-weight: bold;height:35px;" />
			</view>
		</view>
		<view class="yue">
			<view class="yue_l">可提现余额<span>{{yu}}</span>元</view>
			<view class="yue_r" @click="whole"><span>全部提现</span></view>
		</view>
		<view class="yue">这是一段自定义关于提现的描述 </view>
		<view class="yue"></view>
		<view class="qdtx" @click="qdtx">确定提现</view>
	</view>
</template>

<script>
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				id: 0,
				num: 0,
				yu: 0,
				card: [1, 2, 3],
				index: 0,
				cindex:0
			};
		},
		onShow(option) {
			this._load()
			// console.log('xxx',option)
		},
		onLoad(options) {
			// this._load()
			console.log('xxx',options)
			if (options) {
				this.yu = options.money
			}
		},
		components: {
			uniIcon
		},
		computed: {
			bank_bg: function() {
				let type = this.index
				if (type == 1) {
					return 'renmin';
				}
				if (type == 2) {
					return 'nongye';
				}
				if (type == 3) {
					return 'gongshang';
				}
				if (type == 4) {
					return 'jianshe';
				}
				return '';
			}
		},
		methods: {
			_load() {
				// this.card = this.$api.json.get_card
				this.$api.http.get('user/select_bank').then(res => {
					this.card = res.data
					this.index = res.data[0].card_type
				})
				// const yue = this.$api.json.get_sale_money
				// if (yue.ktx_all <= 0) {
				// 	yue.ktx_all = 0
				// }
				// this.yu = yue.ktx_all

			},
			changeindex(e) {
				console.log(e.detail.current)
				this.index = this.card[e.detail.current].card_type
				this.cindex = e.detail.current
			},
			jump_card(index,type) {
				
				if(index == -1) {
					uni.removeStorageSync('bank')
					uni.navigateTo({
						url: '../addcard/addcard?type=add'
					})
				} else {
					const card = this.card[this.cindex]
					uni.setStorageSync('bank', card)
					uni.navigateTo({
						url: '../addcard/addcard?type=edit'
					})
				}

			},
			qdtx() {
				console.log(this.yu,this.num)
				if(this.num == 0){
					this.$api.msg('请输入提现金额！')
					return
				}
				if (this.yu*1 == 0 || this.num > this.yu*1) {
					this.$api.msg('余额不足！')
					return
				}
				let card_info = this.card[this.cindex]
				this.$api.http.post('fx/user/apply_api',{
					money:this.num,
					bk_name: this.bgc(card_info.card_type),
					card:card_info.card,
					bk_uname:card_info.bk_uname,
					card_type:card_info.card_type
				}).then(res=>{
					if(res.status != 200){
						this.$api.msg(res.msg)
					}else{
						this.$api.msg('申请提现成功！')
						uni.navigateTo({
							url: '../txcg/txcg?id='+res.data.id
						})
					}
					
				})
				
			},
			bgc(type) {
				if (type == 0) {
					return '其他';
				}
				if (type == 1) {
					return '人民银行';
				}
				if (type == 2) {
					return '农业银行';
				}
				if (type == 3) {
					return '工商银行';
				}
				if (type == 4) {
					return '建设银行';
				}

			},
			whole() {
				this.num = this.yu
			}
		},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
	.tixian {
		font-size: 22rpx;

		.BH {
			height: 10px;
			background-color: #F8F8F8;
			margin: 0px 0 10px;
		}

		.je {
			padding: 10px;
			font-size: 16px;
			font-weight: bold;
		}

		.hsuru {
			display: flex;
			border-bottom: 1px solid #F0F0F0;
			margin: 10px 10px 12px;
			padding: 10px 10px 10px 0;
			justify-content: space-between;
		}

		.hu_l input {
			font-size: 24px;
			font-weight: bold;
		}

		.yue {
			margin: 0px 10px 20px;
			display: flex;
			justify-content: space-between;
		}

		.yue span {
			color: #F86744;
		}

		.qdtx {
			margin: 0 20% 20px;
			border-radius: 20px;
			color: #fff;
			height: 36px;
			line-height: 36px;
			text-align: center;
			font-size: 16px;
			background: linear-gradient(to top, #FA7458, #EB511B);
			box-shadow: 2px 2px 4px #ED5624;
			border: 1px solid #F76644;
		}

		.card {
			background-color: #45AAF0;
			margin: 0px 20px 10px;
			border-radius: 10px;
			padding: 20px 10px;
			color: #fff;
			display: flex;
			box-shadow: 0px 5px 10px #A5CBEA;
		}

		.card_l {
			width: 20%;
			text-align: center;
		}

		.card_l img {
			width: 45px;
			height: 45px;
		}

		.card_r {
			width: 80%;

			.card_r_1 {
				font-size: 16px;
				padding-bottom: 10px;
			}

			.card_r_2 {
				font-size: 14px;
				padding-bottom: 20px;
			}

			.card_r_3 {
				font-size: 14px;
			}
		}

		.one {
			display: flex;
			justify-content: space-between;

			.t_tit {
				font-size: 20px;
				padding: 20px;
				font-weight: bold;
			}

			.head_btn {
				margin: 15px 20px 0px;
				border: 1px solid #F2F2F2;
				padding: 0px 15px;
				line-height: 25px;
				height: 25px;
			}
		}

		.gongshang {
			background-color: #C42B25;
		}

		.nongye {
			background-color: #008566;
		}

		.renmin {
			background-color: #E50012;
		}

		.jianshe {
			background-color: #003B8F;
		}
	}
</style>
