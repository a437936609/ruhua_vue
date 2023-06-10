<template>
	<view class="container">
    <view class="banner">
      <image src="./assets/banner.jpg" mode="widthFix"></image>
    </view>
    <view class="product-list">
      <view class="product-item" v-for="(item, index) in jsonData" :key="index" @click="navToPro(item.id)">
        <view class="picture">
          <image :src="item.image" mode="widthFix"></image>
        </view>
        <view class="brand">
          <view class="tag">{{item.brand}}</view>
        </view>
        <view class="name">{{item.title}}</view>
        <view class="price-count">
          <view class="price">￥{{item.price || item.Price}}</view>
          <view class="count">已售<text>{{item.sales}}+</text>件</view>
        </view>
      </view>
    </view>
	</view>
</template>

<script>
	import {  
	  mapMutations  
	} from 'vuex';
	export default {
		data(){
			return {
        jsonData: [{
          "id":"32",
          "ic_code": "WD011",
          "brand": "维达",
          "title": "维达纸巾抽纸整箱家用实惠装大包120抽24包餐巾纸卫生纸面巾纸抽（新老包装随机发货）",
          "image": "https://img.aku.pub/uploads/other/6440df8b345ae.jpg",
          "price": "65.9",
          "sales": "604"
        },{
          "id":"24",
          "ic_code": "WD009",
          "brand": "维达",
          "title": "维达蓝色经典卷纸卫生纸实惠装整箱有芯大卷纸厕纸家用厕所纸巾卫生间手纸",
          "image": "https://img.aku.pub/uploads/other/64534fb5803a7.jpg",
          "Price": "29.9",
          "sales": "691"
        },{
          "id":"8",
          "ic_code": "WD004",
          "brand": "维达",
          "title": "维达超韧抽纸130抽6包一提装（新老包装随机发货）",
          "image": "https://img.aku.pub/uploads/other/643eb985375d2.jpg",
          "Price": "19.9",
          "sales": "332"
        },{
          "id":"41",
          "ic_code": "WD015",
          "brand": "维达",
          "title": "【特惠商品】维达纸巾抽纸20包家用实惠装纸抽餐巾纸卫生纸面巾纸擦手纸（新老包装随机发货）",
          "image": "https://img.aku.pub/uploads/other/644b6b0fe0cea.jpg",
          "Price": "49.9",
          "sales": ""
        },{
          "id":"67",
          "ic_code": "WD025",
          "brand": "维达",
          "title": "【199-50】维达超韧抽纸100抽40包+维达卷纸140克10卷*2提（新老包装随机发货）",
          "image": "https://img.aku.pub/uploads/other/645cc24856163.jpg",
          "Price": "149",
          "sales": "200"
        },{
          "id":"5",
          "ic_code": "WD001",
          "brand": "维达",
          "title": "维达超韧抽纸24包（新老包装随机发货）",
          "image": "https://img.aku.pub/uploads/other/6454aa7a6bb90.jpg",
          "Price": "65.9",
          "sales": "200"
        },{
          "id":"70",
          "ic_code": "WD026",
          "brand": "维达",
          "title": "维达棉韧立体美110克有芯卷纸多规格",
          "image": "https://img.aku.pub/uploads/other/646347e6420f8.jpg",
          "Price": "27.9",
          "sales": "160"
        },{
          "id":"39",
          "ic_code": "WD013",
          "brand": "维达",
          "title": "【限时折扣】维达棉韧纸巾抽纸M码24包整箱大包家用实惠装大号压花餐巾纸卫生纸抽（新老包装随机发货）",
          "image": "https://img.aku.pub/uploads/other/644b59b3574f5.jpg",
          "Price": "65.9",
          "sales": "600"
        },{
          "id":"43",
          "ic_code": "WD017",
          "brand": "维达",
          "title": "【特惠商品】维达纸巾抽纸棉韧奢柔M码12包家用实惠装压花大号纸抽餐巾纸（新老包装随机发货）",
          "image": "https://img.aku.pub/uploads/other/644b69efb8bb0.jpg",
          "Price": "34",
          "sales": "244"
        },{
          "id":"56",
          "ic_code": "WD019",
          "brand": "维达",
          "title": "维达抽纸婴儿专用婴幼儿宝宝柔纸巾超柔家用实惠装整箱纸抽餐巾纸",
          "image": "https://img.aku.pub/uploads/other/6457177bb3219.png",
          "Price": "79.9",
          "sales": "199"
        },{
          "id":"77",
          "ic_code": "WD038",
          "brand": "维达",
          "title": "维达手帕纸小包纸巾随身装便携式",
          "image": "https://img.aku.pub/uploads/other/646db99bb48a0.jpg",
          "Price": "15.9",
          "sales": "2165"
        },{
          "id":"76",
          "ic_code": "WD037",
          "brand": "维达",
          "title": "维达湿巾纸小包装便携式杀菌消毒家用湿纸巾随身装单片装独片包装",
          "image": "https://img.aku.pub/uploads/other/646db52a6c371.jpg",
          "Price": "15.9",
          "sales": "1540"
        }]

			}
		},
		onLoad(options) {
		},
		methods:{
      navToPro (id) {
        uni.navigateTo({
					url:'../extend-view/productDetail/productDetail?id='+id
				})
      }
		}
	}
</script>

<style lang='scss' scoped>
page
{
  background: #8663B5;
}
.banner
{
  image
  {
    width: 100%;
  }
}
.product-list
{
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  flex-wrap: wrap;
  margin: 20upx;
  .product-item
  {
    flex: 0 0 345upx;
    padding: 16upx;
    margin-bottom: 20upx;
    min-height: 386upx;
    background-color: #fff;
    border-radius: 5px;
    .picture
    {
      image
      {
        width: 100%;
        vertical-align: top;
      }
    }
    .brand
    {
      display: flex;
      flex-direction: row;
      justify-content: flex-start;
      margin: 12upx 0px;
      .tag
      {
        padding: 0px 6upx;
        height: 38upx;
        line-height: 38upx;
        background-color: #F13939;
        color: #fff;
        font-size: 24upx;
      }
    }
    .name
    {
      margin: 16upx 0px;
      overflow: hidden;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 2;
      text-overflow: ellipsis;
      white-space: normal;
      color: #666666;
      font-size: 30upx;
    }
    .price-count
    {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
      margin: 12upx 0px;
      .price
      {
        font-size: 32upx;
        font-weight: bold;
        color: #F13939;
      }
      .count
      {
        color: #999999;
        font-size: 24upx;
        text
        {
          margin: 0px 3upx;
          color: #444;
          font-weight: bold;
        }
      }
    }
  }
}
</style>
