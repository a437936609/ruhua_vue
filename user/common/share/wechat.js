// #ifdef H5
import wx from './jssdk.js'
// #endif
import http from '../axios.js'
export default {
	getconfig(infoTitle,infoDesc,infoLink,infoImg) {
		// #ifdef H5
		var domine = window.location.href.split("#")[0]; // 微信会自动识别#    并且清除#后面的内容
		domine = domine.split("?code")[0]; 
		http.get('auth/js_sdk',{
			url:domine
		}).then(res=>{
			console.log(res)
			wx.config({
				debug: false, //测试时候用true 能看见wx.config的状态是否是config:ok
				appId: res.appid, // 必填，公众号的唯一标识（公众号的APPid）
				timestamp: res.timestamp, // 必填，生成签名的时间戳
				nonceStr: res.noncestr, // 必填，生成签名的随机串
				signature: res.sign, // 必填，签名
				jsApiList: [
					
					'updateAppMessageShareData', // 分享给好友1.4
					// 'updateTimelineShareData' // 分享到朋友圈1.4
				], // 必填，需要使用的JS接口列表
				openTagList: ['wx-open-launch-app'] // 可选，需要使用的开放标签列表，例如['wx-open-launch-app']
			});
			wx.ready(function() {
				var shareData = {
					title: infoTitle, // 分享标题
					desc: infoDesc, // 分享描述
					link: infoLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
					imgUrl: infoImg, // 分享图标
					success: function(res) {}
				};
				//自定义“分享到朋友圈”及“分享到QQ空间”按钮的分享内容（1.4.0）
				// wx.updateTimelineShareData(shareData);
				//自定义“分享给朋友”及“分享到QQ”按钮的分享内容（1.4.0）
				wx.updateAppMessageShareData(shareData);
			})
		})
		// #endif
		
	}
}
