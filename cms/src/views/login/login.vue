<template>
	<div class="login">
		<div class="tit">商城管理系统</div>
		<div class="zhong">
			<div class="shu">
				<div class="shu-l">
					<img src="../../img/user.png" />
				</div>
				<div class="shu-m">
					<input type="text" v-model="user" placeholder="账号" />
				</div>
			</div>
		</div>
		<div class="zhong">
			<div class="shu">
				<div class="shu-l">
					<img src="../../img/Lock.png" />
				</div>
				<div class="shu-m">
					<input :type="type" v-model="password" v-on:keyup.enter="sub(password)" placeholder="密码" />
				</div>
				<div class="shu-r" @click="change(type)">
					<i v-if="type=='text'" class="el-icon-view" style="color:#889AA4;padding:5px 0 0 0"></i>
					<img v-if="type=='password'" src="../../img/eye.png" />
				</div>
			</div>
		</div>
		<div class="zhong">
			<div class="shu">
				<div class="shu-m">
					<input type="text" v-on:keyup.enter="sub(password)" v-model="code" placeholder="验证码" />
				</div>
				<div>
					<img :src="verify_src" class="shu-code" @click="get_code" />
				</div>
			</div>
		</div>
		<div class="zhong">
			<div class="btn">
				<el-button type="primary" style="width:100%;height:50px;" size="medium" @click="sub(password)">登 录</el-button>
			</div>
		</div>
		<div class="zhong">
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				user: "",
				password: "",
				show: true,
				type: "password",
				code: '',
				verify_id: '',
				verify_src: ''
			};
		},
		mounted() {
			var token = localStorage.getItem("token");
			if(token){
				this.check_login();
			}
			this.get_code();
		},
		methods: {
			check_login(){
				this.http.get("index/admin/check_login").then(res=>{
					if(res.status == 200){
						this.$router.push({
								path: "/"
							});
					}
				})
			},
			sub(psw) {
					this.get_code()
				console.log(psw);
				var that = this;
				const username = this.user;
				this.http.post("index/admin/login", {
						username: username,
						psw: psw,
						verify_id: that.verify_id,
						code: that.code
					})
					.then(res => {
						console.log(res,"index/admin/login")
						if (res.status == 200) {
							localStorage.setItem("token", res.data.token);
							localStorage.setItem("oauth", res.data.oauth);
							localStorage.setItem("admin_name", that.user);
							that.$message({
								showClose: true,
								message: "登陆成功",
								type: "success"
							});
							this.$router.push({
								path: "/"
							});
						}
						if (res.status == 400) {
							this.$message.error(res.msg);
							that.get_code()
							return;
						}
					});
			},
			get_code() {
				var that = this
				this.http.get('index/getcode').then(res => {
					console.log(res);
					that.verify_id = res.data.verify_id
					that.verify_src = res.data.verify_src
				})
			},
			login() {
				const token = localStorage.getItem("token");
				if (token) {
					this.$router.push({
						path: "/"
					});
				} else {
					return;
					this.$router.push({
						path: "/login"
					});
				}
			},
			change(e) {
				if (e == "password") {
					this.type = "text";
				}
				if (e == "text") {
					this.type = "password";
				}
			}
		}
	};
</script>

<style lang="less">
	.login {
		background-color: #2d3a4b;
		width: 100%;
		height: 100vh;
		color: #fff;
		text-align: center;

		.tit {
			font-size: 32px;
			font-weight: 600;
			padding: 200px 0 40px;
			text-align: center;
		}

		.zhong {
			display: flex;
			justify-content: center;
			width: 100%;
			margin-bottom: 20px;

			.shu {
				display: flex;
				border: 1px solid #3d4856;
				background-color: #283443;
				justify-content: space-between;
				width: 350px;
				padding: 8px 10px;
				box-sizing: border-box;

				.shu-l img {
					width: 22px;
					height: 22px;
					margin-top: 2px;
				}

				.shu-m {
					flex-grow: 1;
					padding: 0 10px 0 20px;

					input {
						background-color: #283443;
						color: #fff;
						width: 100%;
						border: none;
						height: 30px;
						line-height: 30px;
						outline: none;
						font-size: 16px;
					}
				}

				.shu-r img {
					width: 22px;
					height: 22px;
					margin-top: 2px;
				}
			}

			.shu-code {
				width: 100px;
				height: 35px;
				margin-top: 2px;
			}

			.btn {
				width: 350px;
				text-align: left;
				font-size: 16px;
			}
		}
	}
</style>
