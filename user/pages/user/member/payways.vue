<template>
	<view>
		<view><button class="bt wx" @click="wx_pay()">微信支付</button></view>
		<view><button class="bt zfb" @click="zfp_pay()">支付宝支付</button></view>
	</view>
</template>

<script>
	var _self
	export default {
		data() {
			return {
				order_id:'',
				msg:'11'
			}
		},
		onLoad(option) {
			_self=this
			console.log(option)
			_self.order_id=option.id
		},
		methods: {
			async wx_pay(){
				 _self.$api.http.post('order/pay/vippre_app', {
					id: _self.order_id
				}).then(res => {
						_self.app_pay(res)

				})
			
			
			},
			zfp_pay(){
				const data=_self.$api.http.post('order/appvippay', {
					order_num: _self.order_id
				}).then(res => {
					   console.log(res)
						_self.zfb_pay(res)
				
				})
				
				
							
			},
			app_pay(data) {
				const order_id = _self.order_id
				console.log('app-pay:', data)
				uni.requestPayment({
					provider: "wxpay",
				//	provider: "alipay",
					orderInfo: JSON.stringify(data),
					success: function(res) {
						console.log('success:', res);
						/* uni.redirectTo({
							url: '/pages/user/user' 
						}) */
					},
					fail: function(err) {
						
						console.log('fail:' + JSON.stringify(err));
						_self.msg=JSON.stringify(err)
						console.log("error")
						console.log(err);
						console.log("error")
						/* uni.redirectTo({
							url: '/pages/user/user'
						}) */
					}
				});
			},
			zfb_pay(data){
				console.log('app-pay:', data)
				uni.requestPayment({
					provider: "alipay",
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
			}
		}
	}
</script>

<style>
.bt{
	width: 700upx;
	height: 50px;
	color: #FFFFFF;
	margin-left: 25upx;
	margin-top: 50px;
}
.wx{
	background-color: #09BB07;
}
.zfb{
	background-color: #0A98D5;
}
</style>
