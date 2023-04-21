 
import http from './axios.js'	
export default {
	async set_storage(){ 
		
		return http.get("index/user/sys_config").then(res=>{ 
			console.log('获取系统配置')
			let arr = {}
			for (let k in res.data) { 
				let v = res.data[k]
				arr[v.key] = v.value
			}
			console.log("arr:",arr)
			uni.setStorageSync('switch',arr)
			
			
			return arr
		})
		
	}
	//获取开关列表
	// get_switch_list(){
	// 	uni.request({
	// 		url:"",
	// 		method::"",
	// 		success(res) {
	// 			console.log(res.data)
	// 			uni.setStorageSync(res)
	// 		}
	// 	})
	// }
}
