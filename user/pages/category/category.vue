<template> 
	<view class="content">
		<scroll-view scroll-y class="left-aside">
			<view v-for="item in flist" :key="item.category_id" class="f-item b-b" :class="{active: item.category_id === currentId}"
			 @click="tabtap(item)">
				{{item.category_name}}
			</view>
		</scroll-view>

		<scroll-view scroll-with-animation scroll-y class="right-aside" @scroll="asideScroll" :scroll-top="tabScrollTop">
			<view class="s-list" v-if="cate_type == 1">
				<view class="nav-title" @click="navToList(0)">——— {{currentName}} ———</view>
				<view class="t-list">
					<view v-for="titem in slist" :key="titem.category_id" v-if="titem.pid == currentId" @click="navToList(titem.category_id)"
					 class="t-item">
						<image :src="getimg+titem.imgs"></image>
						<text>{{titem.category_name}}</text>
					</view>
				</view>
			</view>
			<view class="s-list-0" v-else>
				<view class="nav-title-0" @click="navToList(0)">——— {{currentName}} ———</view>
				<view class="t-list-0">
					<view v-for="titem in cate_pro_list" :key="titem.goods_id" @click="navToPro(titem.goods_id)"
					 class="t-item-0">
						<image :src="getimg+titem.imgs"></image>
						<view class="pro_name">
							{{titem.goods_name}}
						</view>
						<view class="pro_price">
							<price-to-integral :price="titem.price"></price-to-integral>
						</view>
						
					</view>
				</view>
			</view>
		</scroll-view>
		<!-- #ifdef MP-WEIXIN -->
		<button class="btn1" open-type="contact" v-if="sys_switch && sys_switch.is_serve == 1">
			<image class="btnImg" src="../../static/images/kefu.png"></image>
			<!-- <view>客服</view> -->
		</button>
		<!-- #endif -->
	</view>
</template>

<script>
	import PriceToIntegral from "@/components/price-to-integral/price-to-integral"
	import categoryModel from '@/model/category.js'
	
	export default {
		components: { PriceToIntegral },
		data() {
			return {
				cate_pro_list:[],
				page_num:0,
				cate_id:'',
				cate_type:0,
				sys_switch:'',
				getimg: this.$getimg,
				sizeCalcState: false,
				tabScrollTop: 0,
				currentId: 1,
				currentName: '',
				flist: [],
				slist: [],
				is_page_data:true
			}
		},
		onLoad() {
			let type  = uni.getStorageSync('switch')
			this.cate_type = type.product_classification*1
			this.loadPrmSwitch()
			this.loadData()
		},
		onReachBottom() {
			this.page_num++
			if(this.is_page_data){
				this.get_cate_pro(this.cate_id)
			}else{
				uni.showToast({
					title:'没有更多商品了',
					icon:'none'
				})
			}
			
		},
		methods: {
			navToPro(id){
				uni.navigateTo({
					url:'../extend-view/productDetail/productDetail?id='+id
				})
			},
			async loadPrmSwitch(){
				this.sys_switch=await this.promise_switch.then(res=>{
					return res;
				})
				console.log(">>>>")
				console.log(this.sys_switch);
			},
			loadData() {
				uni.showLoading({
					title: '加载中'
				});
				this.flist = []
				this.slist = []
				// this.tabbar = this.$api.http.get('category/all_category').then(res => {
					
				this.tabbar= categoryModel.getCategoryAll().then( res=>{
					console.log(res)
					let list = res.data
					list.forEach(item => {
						if (!item.pid) {
							this.flist.push(item); //pid为父级id, 没有pid或者pid=0是一级分类
						} else {
							this.slist.push(item); //2级分类
						}
					})
					this.cate_id = this.flist[0].category_id
					this.get_cate_pro(this.flist[0].category_id)
					if (list[0]) {
						this.currentName = list[0].category_name;
						this.currentId = list[0].category_id;
					}
					uni.hideLoading();
				})
			},
			//一级分类点击
			tabtap(item) {
				this.page_num = 0
				if (!this.sizeCalcState) {
					this.calcSize();
				}
				this.cate_pro_list = []
				this.is_page_data = true
				this.currentName = item.category_name;
				this.currentId = item.category_id;
				let index = this.slist.findIndex(sitem => sitem.pid === item.category_id);
				if(this.slist[index]){
					this.tabScrollTop = this.slist[index].top;
					}
				this.cate_id = item.category_id
				this.get_cate_pro(item.category_id)
			},
			get_cate_pro(id){
				this.$api.http.get('product/get_cate_pros?id='+id+'&page='+this.page_num).then(res=>{
					if(res.data.length>0){
						for (let k in res.data) {
							let v = res.data[k]
							this.cate_pro_list.push(v)
						}
					}else{
						this.is_page_data = false
					}
					// this.cate_pro_list = res.data
				})
				
			},
			//右侧栏滚动
			asideScroll(e) {
				if (!this.sizeCalcState) {
					this.calcSize();
				}
				let scrollTop = e.detail.scrollTop;
				console.log('scrollTop');
				console.log(scrollTop);
				let tabs = this.slist.filter(item => item.top <= scrollTop).reverse();
				if (tabs.length > 0) {
					this.currentId = tabs[0].pid;
				}
			},
			//计算右侧栏每个tab的高度等信息
			calcSize() {
				let h = 0;
				this.slist.forEach(item => {
					let view = uni.createSelectorQuery().select("#main-" + item.category_id);
					view.fields({
						size: true
					}, data => {
						item.top = h;
						
						h += data.height;
						item.bottom = h;
					}).exec();
				})
				this.sizeCalcState = true;
			},
			navToList(sid) {
				const cid = this.currentId
				uni.navigateTo({
					url: `/pages/extend-view/productList/productList?cid=${cid}&sid=${sid}`
				})
			}
		},
		onPullDownRefresh() {
			this.loadData()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 1500);
		},
		//小程序右上角原生菜单分享按钮，也可是页面中放置的分享按钮
		onShareAppMessage(res) {
			let my = uni.getStorageSync('my')
			let path = "/pages/category/category"
			if (my && my.data && my.data.sfm) {
				path = path + '?sfm=' + my.data.sfm
			}
			console.log('path:', path)
			return {
				title: this.shop_name,
				path: path
			}
		},
	}
</script>

<style lang='scss'>
	.content{min-height: 100vh;}
	/* #ifdef MP-WEIXIN */
	.btn1{
	  width: 60rpx;
	  height: 60rpx; 
	  font-size: 30rpx; 
	  position: fixed;
	  padding: 0px;
	  margin: 0px;
	  top:50%;
	  right:10rpx;
	  z-index: 999;
	  background: none !important; 
	  
	}
	
	.btnImg {
	  width: 60rpx;
	  height: 60rpx;
	  opacity: 0.8;
	}

	.btn1::after {
		border: 0;
	}

	/* #endif */

	.content {
		height: 100%;
		background-color: #f8f8f8;
	}

	

	.content {
		display: flex;
	}

	.left-aside {
		flex-shrink: 0;
		width: 200upx;
		height: 100%;
		background-color: #fff;
	}

	.f-item {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 100%;
		height: 100upx;
		font-size: 28upx;
		color: $font-color-base;
		position: relative;

		&.active {
			color: $base-color;
			background: #f8f8f8;

			&:before {
				content: '';
				position: absolute;
				left: 0;
				top: 50%;
				transform: translateY(-50%);
				height: 36upx;
				width: 8upx;
				background-color: $base-color;
				border-radius: 0 4px 4px 0;
				opacity: .8;
			}
		}
	}

	.right-aside {
		flex: 1;
		overflow: hidden;
		padding-left: 20upx;
	}
	.nav-title {
		text-align: center;
		padding: 20px 0;
	}
	.s-item {
		display: flex;
		align-items: center;
		height: 70upx;
		padding-top: 8upx;
		font-size: 28upx;
		color: $font-color-dark;
	}

	.s-list {
		width: 100%;
		background: #fff;
	}

	.t-list {
		display: flex;
		flex-wrap: wrap;
		width: 100%;
		background: #fff;
		padding-top: 12upx;

		&:after {
			content: '';
			flex: 99;
			height: 0;
		}
	}

	.t-item {
		flex-shrink: 0;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		width: 176upx;
		font-size: 26upx;
		color: #666;
		padding-bottom: 20upx;

		image {
			width: 140upx;
			height: 140upx;
		}
	}
	
	
	
	.nav-title-0 {
		text-align: center;
		padding: 20px 0;
	}
	.s-item-0 {
		display: flex;
		align-items: center;
		height: 70upx;
		padding-top: 8upx;
		font-size: 28upx;
		color: $font-color-dark;
	}
	
	.s-list-0 {
		width: 100%;
		background: #fff;
	}
	
	.t-list-0 {
		display: flex;
		flex-wrap: wrap;
		width: 100%;
		background: #fff;
		padding-top: 12upx;
	
		&:after {
			content: '';
			flex: 99;
			height: 0;
		}
	}
	
	.t-item-0 {
		flex-shrink: 0;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
		width: 49%;
		font-size: 26upx;
		color: #666;
		padding: 20upx;
	
		image {
			width: 200upx;
			height: 200upx;
		}
		.pro_name{
			width: 100%;
			padding-top: 8upx;
			padding-bottom: 8upx;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		.pro_price{
			width: 100%;
			color: #FB586A;
		}
	}
</style>
