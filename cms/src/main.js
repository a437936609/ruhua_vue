import Vue from 'vue'
import App from './App.vue'
import router from './router' 
import VCharts from 'v-charts'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css'; 
import http from './common/axios.js' //找到该文件,修改API网址 
import {Api_url} from './common/config'  

import Print from 'vue-print-nb'
import * as filters from './filters' 
Object.keys(filters).forEach(key =>{
    Vue.filter(key,filters[key])
})

Vue.use(Print);

Vue.prototype.http = http	//全局调用axios  

Vue.config.productionTip = false; 
Vue.use(ElementUI);
Vue.use(VCharts)
Vue.prototype.$getimg = ''//Api_url;
Vue.prototype.shop_name = '商城后台';
Vue.prototype.$ue = "/cms_adm/";  //百度编辑器路径，与vue打包路径一致


  
new Vue({
  router, 
  render: h => h(App)
}).$mount('#app')

 