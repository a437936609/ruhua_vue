<template>
    <view class="xianshi"> 
      <view class="xs_l">{{title}}</view>
	    <view class="xs_r">
      <price-to-integral :price="price"></price-to-integral></view>
    </view>
</template>

<script>
import priceToIntegral from '../price-to-integral/price-to-integral'
export default {
  name: 'none', 
  props: ['title','price'],
  components: { priceToIntegral },
  data() {
    return {
      
    } 
  },
   mounted(){      
  }
}
</script>

<style lang="less">
   .xianshi{display: flex;font-size: 12px;border: 1px solid #FE201F;border-radius: 3px;margin-left: 5px;flex-shrink: 0;
   height: 20px;
	   .xs_l{background-color: #FE201F;color: #fff;padding: 0 2px;}
	   .xs_r{color: #FE201F;padding: 0 2px;}
   }
</style>