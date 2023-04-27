<template> 
	<view class="content content-category">
		<cp-goods-select v-if="goodsSelectHeight" :height="goodsSelectHeight">
		</cp-goods-select>
	</view>
</template>

<script>
	import PriceToIntegral from "@/components/price-to-integral/price-to-integral"
	import categoryModel from '@/model/category.js'
	
	export default {
		components: { PriceToIntegral },
		data() {
			return {
				goodsSelectHeight: '',
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
		mounted () {
			const query = uni.createSelectorQuery().in(this);
			query.select('.content-category').boundingClientRect(data => {
				console.log(data)
				this.goodsSelectHeight = `${data.height}px`
			}).exec()
		},
		onLoad() {
			let type = uni.getStorageSync('switch')
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
.content
{
	position: relative;
	height: calc(100vh - 44px - env(safe-area-inset-top) - 50px - env(safe-area-inset-bottom));
}
</style>
