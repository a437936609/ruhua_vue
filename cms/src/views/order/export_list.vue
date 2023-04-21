<template>
	<div>
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<el-row style="text-align: left;margin-bottom: 15px;">
						<el-button type="primary" size="mini" @click="back">返回</el-button>
					</el-row>
					
					<transition appear appear-active-class="animated fadeInLeft">
						<el-table :data="tableData" border style="width: 100%">
							<el-table-column type="index" label="序号" width="80"></el-table-column>
							<el-table-column prop="name" label="文件名"></el-table-column>
							<el-table-column label="操作">
      							<template slot-scope="scope">
        							<el-button
          							size="mini"
          							@click="download(scope.$index, scope.row)">下载</el-button>

									<el-button
									type="danger"
          							size="mini"
          							@click="del_excel(scope.$index, scope.row)">删除</el-button>
      							</template>
    						</el-table-column>
						</el-table>
					</transition>
				</el-main>
			</el-container>
		</el-container>
	</div>
</template>

<script>
	import {
		Loading
	} from 'element-ui';
	import {
		Api_url
	} from '@/common/config'
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	export default {
		name: 'Ad',
		data() {
			return {
				tableData:[],
				
			}
		},
		components: {
			NavTo,
			Header,
		},
		computed: {

		},
		watch: {

		},
		mounted() {
			this.get_excel();
		},
		methods: {
			download(index,item){
				console.log(item.name)
				let url = Api_url+'/storage/excel/'+item.name;
				 const aLink = document.createElement('a');
				 let token = localStorage.getItem('token');
				 aLink.href = Api_url + '/storage/excel/'+item.name+"?token=" + token
				 aLink.target = '_blank'
				 aLink.download = 'ly_2019.csv';
				 aLink.click();
				 aLink.remove();
			},
			del_excel(index,item){
				console.log("xx")
				this.http.post('index/del_excel?name='+item.name).then(res=>{
					if(res.status==200){
						this.tableData.splice(index,1);
						this.$message({
								showClose: true,
								message: "删除成功",
								type: "success"
							});
					}
				})
			},
			get_excel(){
				this.http.get("index/get_excel").then(res=>{
					let middata = res.data.slice(0,res.data.length-2);
					console.log("middata",middata)
					for( let a in middata){
						console.log("middatasss",middata[a])
						let s = {name:middata[a]}
						this.tableData.push(s);
					}
					console.log(this.tableData);
				})
			},
			back(){
				this.$router.go(-1)
			}
		}



	}
</script>

<style lang="less">

</style>
