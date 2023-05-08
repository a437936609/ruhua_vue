import Vue from 'vue'
import App from './App'
import http from './common/axios.js'
import {
	Api_url
} from './common/config'
import Switch from 'common/switch.js'
import * as filters from 'common/filters/filters.js'
import json from './json.js' //测试用数据
App.mpType = 'app'


const msg = (title, duration = 1500, mask = false, icon = 'none') => {
	//统一提示方便全局修改
	if (Boolean(title) === false) {
		return;
	}
	uni.showToast({
		title,
		duration,
		mask,
		icon
	});
}
const prePage = () => {
	let pages = getCurrentPages();
	let prePage = pages[pages.length - 2];
	// #ifdef H5
	return prePage;
	// #endif
	return prePage.$vm;
}
Vue.prototype.$api = {
	msg,
	http,
	prePage,
	json
};
Vue.prototype.$api_url = Api_url
Vue.prototype.$getimg = ''
Vue.prototype.shop_name = "积分商城"
Vue.prototype.$priceToIntegral = (price) => {
	if (!price || price < 0) {
		return 0
	}
	return Math.ceil( price / 0.0015)
}

Vue.prototype.version = "shops2" //首页,个人中心

//过滤器集合
Object.keys(filters).forEach(key => {
	Vue.filter(key, filters[key])
})

Vue.prototype.promise_switch = Switch.set_storage()


App.mpType = 'app'

// import eruda from 'eruda/eruda.js'
// eruda.init()

const app = new Vue({
	...App
})
app.$mount()