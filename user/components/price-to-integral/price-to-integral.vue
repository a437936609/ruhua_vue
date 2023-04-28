<template>
	<span>{{ integral }} 积分</span>
</template>

<script>
	export default {
		name: "price-to-integral",
		props: {
			price: {
				type: [Number, String],
				default: null
			}
		},
		computed: {
			integral() {
				return this.$priceToIntegral(this.price)
				// if (!this.price || this.price < 0) {
				// 	return 0
				// }
				// return Math.ceil( this.price / 0.0015)
			},
		}
	}
</script>

<style>
</style>
