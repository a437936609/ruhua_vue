<template>
	<view class="content">
		<canvas style="width: 280px; height:437px;" canvas-id="npGoodsCanvas" @click="saveCans"></canvas>
		<np-qrcode ref="qrcode" :val="link" :size="350" :show="false" :loadMake="false" :usingComponents="false" :showLoading="true"
		 loadingText="海报生成中" @result="userQr"></np-qrcode>
		<view class="canvas-footer" v-if="showFooter">
			<!-- #ifdef H5 -->
			<view class="canvas-tip">
				<text>点击图片，然后长按图片可保存</text>
			</view>
			<!-- #endif -->
			<view class="btn-box">
				<!-- #ifndef H5 -->
				<view class="save-btn" @click="saveCans">保存图片</view>
				<!-- #endif -->
				<view class="close" @click="closeModel">关闭窗口</view>
			</view>
		</view>
	</view>
</template>

<script>
	import productModel from "@/model/product.js"
	// import {
	// 	mapState,
	// 	mapMutations
	// } from 'vuex';
	export default {
		// computed: {
		// 	...mapState(['hasLogin', 'userInfo']),
		// },
		data() {
			return {
				imageList: [],
				showFooter: false,
				avatar: "/static/images/missing-face.png"
			};
		},
		props: {
			api: {
				type: String,
				default: ""
			},
			link: {
				type: String,
				default: ""
			},
			goodsInfo: {}, //商品信息
		},
		provide() {
			return {
				npGoodsCanvas: this
			}
		},
		created() {
			this.startCanvas();
		},
		methods: {
			startCanvas() {
				// #ifdef H5
				setTimeout(() => {
					this.$refs.qrcode._makeCode()
				}, 100);
				// #endif
				// #ifdef MP-WEIXIN
				this.qrcode();
				// #endif
			},
			closeModel() {
				this.$emit('close');
			},
			userQr(e) {
				this.goodsImage(e)
			},
			qrcode() {
				let data = {};
				data.api = this.api;
				data.itemid = this.goodsInfo.itemid;
				uni.showLoading({
					title: "正在生成"
				});
				productModel.postWxCode().then(res => {
					console.log(res.data)
					uni.hideLoading();
					var url = this.$getimg + res.data
					uni.downloadFile({
						url: url,
						success: (result) => {
							//console.log(result)
							this.goodsImage(result.tempFilePath)
						},
						fail: (err) => {
							//console.log(err)
							this.$msg("生成小程序码出错");
							this.closeModel();
						}
					});
				})
			},
			goodsImage(qrcode) {
				var that = this
				let url;
				// #ifdef H5
				url = that.goodsInfo.banner_imgs_url[0];
				// #endif
				// #ifdef MP-WEIXIN
				url = that.goodsInfo.banner_imgs_url[0];
				//url = url + '&platform=wechat&appid=' + uni.getAccountInfoSync().miniProgram.appId;
				// #endif
				console.log(url)
				uni.downloadFile({
					url: url,
					success: (result) => {
						that.drow(qrcode, result.tempFilePath);
					},
					fail: (err) => {
						that.$msg("绘制商品图片失败");
						that.closeModel();
					}
				})
			},
			drow(qrcode, image) {
				// let userText = "Hi！你好！";
				// if (that.hasLogin) {
				// 	userText = "我是" + that.userInfo.nickname;
				// }
				let title = this.goodsInfo.goods_name;
				let titleCount = title.length;
				let titleOne = title;
				let titleTwo = '';
				if (titleCount > 18) {
					titleOne = title.slice(0, 18);
					if (titleCount > 34) {
						titleTwo = title.slice(18, 34);
					} else {
						titleTwo = title.slice(18, titleCount);
					}
				}
				console.log(titleCount)
				uni.showLoading({
					title: '生成海报中'
				});

				const context = uni.createCanvasContext('npGoodsCanvas', this)

				context.drawImage('/static/images/canvas_bg.png', 0, 0, 280, 437)
				context.drawImage(image, 15, 40, 250, 250)
				context.drawImage(qrcode, 205, 360, 60, 60)

				context.setFillStyle('#000000')
				context.setFontSize(12)
				context.fillText('推荐一个好物给你,请查收！', 15, 25)
				context.fill()

				context.setFillStyle('#000000')
				context.setFontSize(14)
				context.fillText(titleOne, 15, 310)
				context.fill()

				if (titleTwo) {
					context.setFillStyle('#333333')
					context.setFontSize(14)
					context.fillText(titleTwo, 15, 330)
					context.fill()
				}
				let priceIcon = "￥";
				let priceLeft = 25;


				// if (this.goodsInfo.coupon_quan > 0) {
				// 	let priceWidth = context.measureText(this.goodsInfo.coupon_price);
				// 	let quanWidth = 90 + priceWidth.width;

				// 	context.setFillStyle('#1cbbb4')
				// 	context.setFontSize(12)
				// 	context.fillText('现金券' + this.goodsInfo.coupon_quan + '元', quanWidth, 360)
				// 	context.fill()
				// }
				context.setFillStyle('#e54d42')
				context.setFontSize(12)
				context.fillText(priceIcon, 15, 360)
				context.fill()

				context.setFillStyle('#e54d42')
				context.setFontSize(20)
				context.fillText(this.goodsInfo.price, priceLeft, 360)
				context.fill()

				context.save()

				context.draw()

				uni.hideLoading();
				this.showFooter = true;
			},
			saveCans(e) {
				// #ifdef H5
				if (this.imageList.length != 0) {
					uni.previewImage({
						current: 0,
						urls: this.imageList
					});
					return;
				}
				// #endif
				uni.showLoading({
					title: '保存海报中'
				});
				uni.canvasToTempFilePath({
					x: 0,
					y: 0,
					width: 280,
					height: 437,
					destWidth: 680,
					destHeight: 1060,
					canvasId: 'npGoodsCanvas',
					success: (res) => {
						uni.hideLoading()
						// #ifdef H5
						this.imageList.push(res.tempFilePath);
						uni.previewImage({
							current: 0,
							urls: this.imageList
						});
						// #endif
						// #ifndef H5
						uni.saveImageToPhotosAlbum({
							filePath: res.tempFilePath,
							success: (red) => {
								uni.showToast({
									title: '保存相册成功',
									duration: 2000
								});

							},
							fail(res) {
								if (res.errMsg == "saveImageToPhotosAlbum:fail auth deny") {
									uni.showModal({
										title: '您需要授权相册权限',
										success(res) {
											if (res.confirm) {
												uni.openSetting({
													success(res) {

													},
													fail(res) {
														console.log(res)
													}
												})
											}
										}
									})
								}

							}
						});
						// #endif
					},
					fail(res) {
						uni.hideLoading()
					}
				}, this)
			},
		}
	}
</script>

<style>
	.content {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(0, 0, 0, .4);
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}

	.btn-box {
		margin-top: 10rpx;
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
	}

	.save-btn {
		margin-right: 20rpx;
		color: #FFFFFF;
		background: linear-gradient(to right, #FE726B, #FE956B);
		padding: 15rpx 40rpx;
		border-radius: 50rpx;
	}

	.close {
		color: #FFFFFF;
		background: linear-gradient(to right, #FE726B, #FE956B);
		padding: 15rpx 40rpx;
		border-radius: 50rpx;
	}

	.canvas-footer {
		min-height: 100rpx;
	}

	.canvas-tip {
		margin-top: 10rpx;
		text-align: center;
		font-size: 28rpx;
		color: #fff;
	}

	.canvas-btn {
		height: 64rpx;
		margin-right: 10rpx;
	}
</style>
