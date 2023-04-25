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
			this.url="https://api.aku.pub/order/second_pay?id=" + option.order_id
		},
		methods: {
			
		}
	}
</script>

<style>

</style>
