export default {
	data() {
		return {
			goodsDown: false,
			iswechat: false,
			itemid: 0,
			goods: {},
			bannerList: [],
			show: false,
			scrollTop: 0,
			path: '',
			userShareLink: '',
			recommendList: [],
			detail: [],
			goodsList: [],
			fav: false,
			showModal: false,
			startCanvas: false,
			hasLinkCache: false,
			pinTuanInfo: 0,
			linkInfo: {},
			val: ''
		}
	},
	onLoad(option) {
		// uni.setNavigationBarTitle({
		// 	title: '商品详情'
		// });
		// this.iswechat = this.$config.isTencent();
		// if (option.scene) { //如果有scene判断商品ID和推广者ID，主要系海报推广
		// 	var scene = decodeURIComponent(option.scene)
		// 	var parmas = scene.split("&");
		// 	var arr = [];
		// 	var testData = {};
		// 	for (var i in parmas) {
		// 		arr = parmas[i].split("=");
		// 		if (i == 0) {
		// 			this.itemid = arr[1];
		// 		} else {
		// 			uni.setStorageSync('parent', parseInt(arr[1]));
		// 		}
		// 	}

		// 	this.getItem(true);
		// } else if (option.id) { //如果传入商品ID，则从ID获取商品信息
		// 	this.itemid = option.id;
		// 	this.getItem(true);
		// } else { //不传商品ID则为系统内部点击，storage读取商品ID
		// 	uni.getStorage({
		// 		key: "ITEM",
		// 		success: (res) => {
		// 			this.goods = JSON.parse(res.data);
		// 			this.bannerList = this.goods.adv;
		// 			this.show = true;
		// 			this.getItem();
		// 		},
		// 		fail: () => {
		// 			this.getItem(true);
		// 		}
		// 	});
		// }
	},
	onPageScroll(e) {
		this.scrollTop = e.scrollTop;
	},
	onUnload() {
		uni.removeStorageSync("ITEM");
	},
	onShareAppMessage(res) {
		let shareTitle = this.goods.title;
		if (this.goods.coupon_quan > 0) {
			shareTitle = "快来领取" + this.goods.coupon_quan + "元收惠券,券后只要￥" + this.goods.coupon_price + "元";
		}
		return {
			title: shareTitle,
			imageUrl: this.goods.image,
			path: this.path,
		};
	},
	methods: {
		showLogin(e) {
			if (e.showLogin) {
				this.$refs.showLogin.open();
			}
		},
		getPath() {
			//let itemId = this.goods.itemid;
			//let parentId = this.$config.getParentId();
			// if (this.hasLogin) {
			// 	parentId = this.userInfo.id;
			// }
			//console.log(this.getRoute())
			let route = this.getRoute();
			// this.path = '/' + route + '?id=' + itemId + '&uid=' + parentId;
			// this.query = "id=" + itemId + "&uid=" + parentId + "&friend=1";
			
			// #ifdef H5
			var domine = window.location.href.split("#"); // 微信会自动识别#    并且清除#后面的内容
			console.log(domine)
			if (domine[0]) {
				this.val = domine[0] + '#' + domine[1]
			} else {
				this.val = '/../'
			}
			this.userShareLink =this.val
			// #endif
			
			// #ifndef H5
			this.userShareLink = this.val
			// #endif
			
			console.log(this.userShareLink,999)
		},
		getRoute(){
			let pages = getCurrentPages();
			let curPage = pages[pages.length-1];
			return curPage.route;
		},
		getItem(formId) {
			uni.getStorage({
				key: "recommendList",
				success: (res) => {
					this.recommendList = JSON.parse(res.data);
				}
			});
			let sendData = {};
			sendData.id = formId ? this.itemid : this.goods.itemid;
			sendData.formid = formId ? 1 : 0;
			let cateId = '';
			switch (this.api) {
				case 'ali':
					cateId = 1;
					if (!formId) {
						sendData.shopnick = this.goods.shop_name;
					}
					break;
				case 'jd':
					cateId = 2;
					if (!formId) {
						sendData.cid3 = this.goods.cid3;
					}
					break;
				case 'pdd':
					cateId = 3;
					break;
			}
			sendData.cate_id = cateId; //区分商品所属平台

			this.$http.get('api/default/item', {
				params: sendData
			}).then((res) => {
				if (cateId == 1) {
					this.setDataByTb(res, formId);
				} else if (cateId == 2) {
					this.setDataByJd(res, formId);
				} else if (cateId == 3) {
					this.setDataByPdd(res, formId);
				}
				this.recommendList = res.recommend;
				if (res.recommend) {
					uni.setStorage({
						key: "recommendList",
						data: JSON.stringify(res.recommend)
					});
				}
				this.detail = res.desc ? res.desc : [];
				this.goodsList = res.list ? res.list.list : [];
				this.fav = res.fav;
				this.show = true;
				this.getPath(); //获取商品转发页面
				let isExists = this.$config.isExist("GOODSITEM", this.goods.itemid);
				if (isExists === false) {
					this.$config.insert({
						key: "GOODSITEM",
						value: this.goods
					});
				}
			}).catch(function(error) {});
		},
		setDataByTb(res, formId) {
			if (formId) {
				this.goods = res.item;
				this.bannerList = this.goods.adv;
			} else {
				this.goods.city = res.item.city;
				this.goods.shop_info = res.item.shop_info;
			}
			if (res.item.length == 0) {
				this.goodsDown = true;
			}
		},

		setDataByJd(res, formId) {
			if (formId) {
				this.goods = res.item;
				this.bannerList = this.goods.adv;
			} else {
				this.goods.video = res.item.video || "";
			}
		},

		setDataByPdd(res, formId) {
			if (formId) {
				this.goods = res.item;
			}
			this.bannerList = res.item.adv;
		},
		toShare() {
			this.$refs.footerShare.open();
		},
		webCopy() {
			let info = this.linkInfo;
			uni.setClipboardData({
				data: info.taoword,
				success: (res) => {
					this.$refs.codeBox.close();
					this.$refs.copytip.open();
				},
				fail: () => {
					this.$msg('领取失败，请重试');
				}
			});
		},
		//复制链接
		copyLink() {
			this.getPath();
			//console.log(this.userShareLink)
			this.showModal = false;
			uni.setClipboardData({
				data: this.userShareLink,
				success: (res) => {
					//#ifdef H5
					this.$msg('已复制到剪切板');
					//#endif
				},
				fail: (err) => {
					//#ifdef H5
					this.$msg('未知错误,复制失败');
					//#endif
				}
			});
		},
		//打开生成海报
		createImage() {
			this.getPath();
			this.startCanvas = true;
		},
		//关闭海报窗口
		closeGoodsCanvas() {
			this.startCanvas = false;
		},
		toFav() {
			if (!this.hasLogin) {
				this.$refs.showLogin.open();
				return;
			}
			let addItem = {
				itemid: this.goods.itemid,
				name: this.goods.title,
				image: this.goods.image,
				shop_type: this.goods.shop_type || 0,
				api: this.api
			};
			let remoreItem = {
				itemid: this.goods.itemid,
			};
			let url = this.fav ? 'api/user/fav-remove' : 'api/user/fav-add';
			let sendData = this.fav ? remoreItem : addItem;
			this.$http.post(url, sendData).then((res) => {
				this.fav = this.fav ? false : true;
				this.$msg(res);
			}).catch((error) => {
				this.$msg(error.message)
			});
		},
		buyNow(pintuan) {
			if (!this.hasLogin && this.globalConfig.setting && this.globalConfig.setting.is_rebate == 1) {
				this.$refs.showLogin.open();
				return;
			}
			if (this.hasLinkCache) {
				if (this.api === 'ali') {
					this.linkByAli();
					return;
				} else if (this.api === 'jd') {
					this.linkByJingDong();
					return;
				} else if (this.api === 'pdd') {
					if (this.pinTuanInfo == pintuan) {
						this.linkByPinDuoDuo();
						return;
					}
				}
			}
			let post = {
				id: this.goods.itemid,
				search_id: this.goods.search_id || '',
				coupon_id: this.goods.coupon_id || '',
				coupon_url: this.goods.coupon_link || '',
				api: this.api,
				pintuan: pintuan ? 1 : 0
			};
			uni.showLoading({
				title: "正在获取链接"
			});

			this.$http.post('api/default/buynow', post).then((res) => {
				uni.hideLoading();
				this.linkInfo = res;
				this.hasLinkCache = true;
				this.pinTuanInfo = pintuan;
				if (this.api === 'ali') {
					this.linkByAli();
				} else if (this.api === 'jd') {
					this.linkByJingDong();
				} else if (this.api === 'pdd') {
					this.linkByPinDuoDuo();
				}
			}).catch((error) => {
				uni.hideLoading();
				this.$msg(error.message)
			});
		},
		linkByAli() {
			let info = this.linkInfo;
			// #ifdef MP-WEIXIN || MP-QQ
			uni.setClipboardData({
				data: info.taoword,
				success: (res) => {
					this.$refs.copytip.open()
				},
				fail: () => {
					this.$msg('领取失败，请重试');
				}
			});
			// #endif
			// #ifdef H5
			if (this.iswechat) {
				this.$refs.codeBox.open()
			} else {
				let url = info.coupon_click_url ? info.coupon_click_url : info.item_url
				location.href = decodeURIComponent(url);
			}
			// #endif
		},
		linkByJingDong() {
			let info = this.linkInfo;
			// #ifdef H5
			location.href = decodeURIComponent(info.url);
			// #endif
			// #ifdef MP-WEIXIN
			uni.navigateToMiniProgram({
				appId: info.app_id,
				path: info.page_path,
				envVersion: 'release'
			});
			// #endif
		},
		linkByPinDuoDuo() {
			let info = this.linkInfo;
			// #ifdef H5
			location.href = decodeURIComponent(info.we_app_web_view_short_url);
			// #endif
			// #ifdef MP-WEIXIN
			uni.navigateToMiniProgram({
				appId: info.we_app_info.app_id,
				path: info.we_app_info.page_path,
				envVersion: 'release'
			});
			// #endif
			// #ifdef MP-QQ
			uni.navigateToMiniProgram({
				appId: info.qq_app_info.app_id,
				path: info.qq_app_info.page_path,
				envVersion: 'release'
			});
			// #endif
		},
		select(e) {
			this.$refs.footerShare.close();
			let item = e.item;
			switch (item.name) {
				case 'wx':
					break;
				case 'link':
					this.copyLink();
					break;
				case 'canvas':
					this.createImage();
					break;
			}
		}
	}
}
