<template>
	<view class="j">
		
		<view class="head">
			<img src="../../../imgs/heika.jpg"></img>
		</view>
		<view class="taoc">
			<view :class="xz==index?'zhong':'tc_01'"  v-for="(item,index) of tc_list" @click="xuan(index)" :key="index">
				<view class="tc_01_1">{{item.title}}</view>
				<view class="tc_01_2">¥ <span>{{item.price}}</span></view>
				<view class="zhe" v-if="index == 2">限时8折</view>
			</view>
			<!-- <view :class="xz==2?'zhong':'tc_01'" @click="xuan(2)">
				<view class="tc_01_1">半年卡</view>
				<view class="tc_01_2">¥ <span>169</span></view>
			</view>
			<view :class="xz==3?'zhong':'tc_01'" @click="xuan(3)">
				<view class="tc_01_1">尊享年卡</view>
				<view class="tc_01_2">¥ <span>279</span></view>
				<view class="zhe">限时8折</view>
			</view> -->
		</view>
		<view class="btn" @click="buy">立即购买</view>
		<view class="quan">
			<view class="q_tit">会员权益</view>
			<textarea v-model="member"></textarea>
			<!-- <view class="q_qy" v-for="(item,index) of list" :key="index">
				<uni-icon type="spinner" size="20" color="#E09A39"></uni-icon>
				<span>1元充20话费</span>
			</view> -->
		</view>
		
	</view>
</template>

<script>
	var _self;
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {
		data() {
			return {
				xz:'0',
				list:[1,2,3],
				tc_list:[1],
				tc_id:'',
				order_id:'0',
				url:'',
				member:''
			};
		},
		components: {uniIcon},
		onLoad() {
			_self=this;
			_self.get_vip_tc()
		},
		methods:{
			wxPay(json) {
	
				if (typeof WeixinJSBridge == "undefined") {
					if (document.addEventListener) {
						document.addEventListener("WeixinJSBridgeReady", jsApiCall, false);
					} else if (document.attachEvent) {
						document.attachEvent("WeixinJSBridgeReady", jsApiCall);
						document.attachEvent("onWeixinJSBridgeReady", jsApiCall);
					}
				} else {
					_self.jsApiCall(json);
				}
			},
			jsApiCall(json) {
				const that = this;
				WeixinJSBridge.invoke("getBrandWCPayRequest", json, function(res) {
					WeixinJSBridge.log('a:', res.err_msg);
					console.log(res);
					if (res.err_msg == "get_brand_wcpay_request:ok") {
						that.$api.msg("支付成功!");
						 setTimeout(() => {
						 	uni.redirectTo({
						 		url: '/pages/user/user'
						 	});
						 }, 1000);
					} else if (res.err_msg == "get_brand_wcpay_request:cancel") {
						that.$api.msg("取消支付");
					} else {
						that.$api.msg("支付失败");
					}
					
					setTimeout(() => {
						uni.redirectTo({
							url: '/pages/user/user'
						});
					}, 1000);
				});
			},
			get_vip_tc(){
				_self.$api.http.get('vip/get_tc').then(res=>{
					console.log(res)
					_self.tc_list = res.data
					this.tc_id = res.data[0].id
				})
				_self.$api.http.get('banner/get_member_equity').then(res=>{
					_self.member=res.data.value
				})
			},
			xuan(e){
				console.log(e)
				_self.xz=e
				_self.tc_id = _self.tc_list[e].id
				console.log(_self.tc_id)
			},
			buy(){ 
			
				_self.$api.http.post('vip/create_vip_order',{tc_id:_self.tc_id}).then(res=>{
					_self.order_id = res.data
				
					_self.sub_buy()
				})
				
			},
			async sub_buy(){
			
				
				//#ifdef MP-WEIXIN
				const pay_data = await _self.$api.http.post('order/pay/pre_vip', {
					id: _self.order_id
				}).then(res => {
					console.log('pay:', res)
					return res
				})
				await _self.pay(pay_data)
				//#endif
				//#ifdef H5
				
				let ua = window.navigator.userAgent.toLowerCase()
				if (ua.match(/MicroMessenger/i) == 'micromessenger') {
					const pay = await _self.$api.http.post('order/vipsecond_pay', {
						id: _self.order_id
					}).then(res => {
						console.log('pay:', res)
						return res
					}) 
					
					_self.wxPay(pay)
				
			//	console.log("true")
				
				} else {
					
					//支付宝支付
				 	uni.reLaunch({
						url:'pay?order_num='+_self.order_id
					}) 
					
					//微信支付
				/* 	console.log(_self.order_id)
					const pay = _self.$api.http.post('order/pay/pre_vip', {
						id: _self.order_id
					}).then(res => {
						console.log('pay:', res)
						return res
					}) 
					
					//微信支付
					 const wx=require('@/common/h5_pay.js')
					 wx.config({
					 		debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
					 		appId:pay.appId, // 必填，公众号的唯一标识
					 		timestamp:pay.timeStamp, // 必填，生成签名的时间戳
					 		nonceStr:pay.nonceStr, // 必填，生成签名的随机串
					 		signature:pay.paySign, // 必填，签名，见附录1
					 		jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
					 	});
						wx.ready(function() {
								wx.chooseWXPay({
									timestamp:pay.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
									nonceStr:pay.nonceStr, // 支付签名随机串，不长于 32 位
									package: pay.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
									signType: 'MD5', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
									paySign:pay.paySign, // 支付签名
									success(res) {
										// 支付成功后的回调函数
										console.log("支付成功")
									},
									fail(res) {
										errorCb(res);
									}
								});
							});
						 
							wx.error(function(res) {
								// config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
							
								console.log("支付失败")
								console.log(res)
								console.log("支付失败")
							}); */


					
					
				
				console.log("false")
				
				}
				
				 
				//#endif
				
				
				
				//#ifdef APP-PLUS
				//	await _self.wx_pay()
			 	/* uni.reLaunch({
					url:'pay?order_num='+_self.order_id
				}) */
				 	 uni.navigateTo({
						url:'payways?id='+_self.order_id
					}) 
					/* const app_data = await this.$api.http.post('order/pay/pre_app', {
						id: order_id
					}).then(res => {
						console.log('app-pay:', res)
						return res
					})
					console.log('获取到app支付参数：', app_data)
					await this.app_pay(app_data) */
				
						
					

				//#endif
				
			},
			app_pay(data) {
						
				const order_id = _self.order_id
				console.log('app-pay:', data)
				uni.requestPayment({
					provider: "wxpay",
					orderInfo: JSON.stringify(data),
					success: function(res) {
						console.log('success:', res);
					},
					fail: function(err) {
						
						console.log('fail:' + JSON.stringify(err));
						_self.msg=JSON.stringify(err)
						console.log("error")
						console.log(err);
						console.log("error")
					}
				});
			},
			async wx_pay(){
				
				 _self.$api.http.post('order/pay/vippre_app', {
					id: _self.order_id
				}).then(res => {
					 console.log("进入app支付1")
						_self.app_pay(res)
			
				})
			
			
			},
			back(){
				
				this.$api.http.get('user/info').then(res=>{
					uni.setStorageSync('my',res.data)
					console.log("支付成功");
					uni.showToast({
						title:'支付成功'
					})
					let pages = getCurrentPages(); // 当前页面
					 let beforePage = pages[pages.length - 2]; // 前一个页面
					setTimeout(function(){
						uni.switchTab({
							url:'../user'
						})
					
					},1000)
				})
			},
			pay(data) {
				console.log(data)
				uni.requestPayment({
					provider: "wxpay",
					timeStamp: data.timeStamp,
					nonceStr: data.nonceStr,
					package: data.package,
					signType: data.signType,
					paySign: data.paySign,
					success: function(res) {
						console.log('success:', res);
						_self.back();
						
						
					},
					fail: function(err) {
						console.log('fail:' + JSON.stringify(err));
						
					}
				});
			},
		},
		
		
		onPullDownRefresh() {
			//_self._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="scss">
.j{font-size: 14px;
	.head{background: url(../../../imgs/00.jpg) no-repeat ;background-size: 100% 100%;height: 250px;width: 100%;color: #E8B374;
	box-sizing: border-box;padding: 55px 20px 0;
		img{width: 100%;height: 168px;}
		.head_01{display: flex;justify-content: space-between;font-size: 15px;padding: 0 4px 40px 15px;}
		.head_02{display: flex;
			.head_02_1{line-height: 40px;width: 25%;text-align: center;}
		}
		
	}
	.taoc{display: flex;justify-content: space-around;margin: 25px 10px;
		.tc_01{border: 1px solid #DCDCDC;border-radius: 5px;text-align: center;width: 28%;position: relative;
			.tc_01_1{border-bottom: 1px solid #F2F2F2;padding: 8px 0;color: #96713F;}
			.tc_01_2{padding: 15px 0;color: #7F5214;
				span{font-size: 24px;font-weight: 600;}
			}
			.zhe{position: absolute;top: -20px;left: 50px;background: linear-gradient(to right, #E22208, #FF9E6A);color: #fff;
			padding: 3px 8px;border-top-left-radius:15px;border-bottom-right-radius: 15px;white-space: nowrap;font-size: 12px;
			}
		}
		.zhong{border: 1px solid #FFC476;border-radius: 5px;text-align: center;width: 28%;position: relative;
			.tc_01_1{background-color: #FFCB85;border-bottom: 1px solid #FFCB85;padding: 8px 0;color: #96713F;}
			.tc_01_2{background-color: #FFDCAD;padding: 15px 0;color: #7F5214;
				span{font-size: 24px;font-weight: 600;}
				}
			.zhe{position: absolute;top: -20px;left: 50px;background: linear-gradient(to right, #E22208, #FF9E6A);color: #fff;
			padding: 3px 8px;border-top-left-radius:15px;border-bottom-right-radius: 15px;white-space: nowrap;font-size: 12px;
			}
		}
	}
	.btn{margin: 20px 25px;background: linear-gradient(to bottom, #E8BE86, #D88D28);color: #fff;text-align: center;height: 45px;line-height: 45px;
	border-radius: 30px;font-size: 16px;border: 1px solid #EBB163;margin-bottom: 40px;
	}
	.quan{margin: 10px;
		.q_tit{border-left: 2px solid #E09A39;padding-left: 10px;font-size: 15px;font-weight: 600;margin-bottom: 10px;}
		.q_qy{padding: 10px;
			span{padding-left: 10px;}
		}
	}
}
</style>
