<template>
	<view class="loginB">
	</view>
</template>

<script>
	export default {
		data() {
			return {
			};
		},
		components: {},
		onLoad(options) {
			let that = this;
			console.log('登录参数:', options);
			if (options.is_login != '1') {
				window.location.href =
					`https://mmall.dccnet.com.cn/mobile/member/checkAuthorizationNew.jhtml?targetUrl=${encodeURIComponent('https://mall.aku.pub/#/pages/login/loginB/loginB?is_login=1')}&outerName=021424`
				return;
			}
			that.loginByIcbc(options.userInfoKey);
		},
		methods: {
			loginByIcbc(userInfoKey) {
				this.$api.http.post('auth/login_by_icbc', {
					userInfoKey: userInfoKey
				}).then(res => {
					if (res.status == 400) {
						this.$api.msg(res.msg)
						return;
					}
					let token = res.data.token;
					uni.setStorageSync('token', token)
					this.$api.msg('登录成功')
					setTimeout(() => {
						uni.switchTab({
							url: '/pages/index/index'
						});
					}, 2000);
				})
			},
		},
		mounted() {

		}
	}
</script>

<style lang="less">
	.loginB {
		padding-top: 100px;

		.xieyi {
			background-color: #FFFFFF;
			padding-left: 0;
			padding-right: 0;
			border-radius: 0;
		}

		.xieyi::after {

			border: none;

		}

		.name {
			padding: 20px 0px 10px;
			margin: 15px 20px 0px;
			border-bottom: 1px solid #F4F4F4;
			display: flex;
		}

		.yzm {
			padding: 6px 0px;
			margin: 0px 20px;
			display: flex;
			justify-content: space-between;
		}

		.name_r {
			width: 100%;
			border-bottom: 1px solid #F4F4F4;
			padding-top: 15px;
		}

		.yzm_r {
			background-color: #E21534;
			color: #fff;
			line-height: 50px;
			padding: 0 15px;
			font-size: 14px;
			margin-left: 15px;
			border-radius: 3px;
			flex-shrink: 0;
		}

		.btn {
			margin: 30px 20px 10px;
			background-color: #E21534;
			color: #fff;
			height: 45px;
			text-align: center;
			line-height: 45px;
			border-radius: 3px;
		}

		.wj {
			padding: 0 20px;
			color: #AAAAAA;
			font-size: 12px;

			span {
				color: #78B0E5;
			}
		}
	}
</style>