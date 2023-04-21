<template>
	<view>
		<web-view :src="url" v-if="url.length"></web-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				url:'',
				data:''
			}
		},
		onLoad(option) {
		
			this.url=this.$getimg+"order/alipay?order_num="+option.order_num
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
