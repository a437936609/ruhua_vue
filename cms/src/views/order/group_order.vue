<template>
	<div class="reseller_order">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F3F3F3;">
					<transition appear appear-active-class="animated fadeInLeft">
						<el-table :data="tableData" border style="width: 100%">
                            <!-- <block slot-scope="scope" v-if="scope.row.state>0"> -->
                                <el-table-column type="index" label="序号">
                                </el-table-column>
                                <el-table-column prop="order_num" label="订单号">
                                </el-table-column>
                                <el-table-column label="商品名称">
                                    <template slot-scope="scope" v-if="scope.row.ordergoods[0]">
                                        {{scope.row.ordergoods[0].goods_name}}
                                    </template>
                                </el-table-column>
                                <!-- <el-table-column prop="region" label="地区">
                                    <template slot-scope="scope" v-if="scope.row.region">
                                        {{region_list[scope.row.region]['region_name']}}
                                    </template>
                                </el-table-column> -->
                                <el-table-column label="发起人">
                                    <template slot-scope="scope" v-if="scope.row.users">									
                                        {{scope.row.users.nickname}}
                                    </template>
                                </el-table-column>
                                <el-table-column label="发起时间">
                                    <template slot-scope="scope">									
                                        {{scope.row.update_time}}
                                    </template>
                                </el-table-column> 
                                <el-table-column label="商品价格">
                                    <template slot-scope="scope">									
                                        {{scope.row.order_money}}
                                    </template>
                                </el-table-column>
                                <el-table-column label="状态" 
                                                 :filters="[{ text: '拼团中', value:1}, { text: '已完成', value:2}]"
                                                 :filter-method="filterTag">
                                    <template slot-scope="scope" >									
                                        <el-tag type="danger" v-if="scope.row.state==1">拼团中</el-tag>
                                        <el-tag type="info" v-else>已完成</el-tag>
                                    </template>
                                </el-table-column>
                            <!-- </block> -->
						</el-table>

					</transition>
					<el-pagination  style="margin-top: 50px;" background layout="prev, pager, next" :total="total"
					 :page-size="size" @current-change="jump_page">
					</el-pagination>
				</el-main>
			</el-container>
		</el-container>
	</div>
</template>
<script>
	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	export default {
		data() {
			return {
				tableDatas:[
                    {
                        ordergoods:[],
                    },
				
                ],
				tableData:'',
				size: 10,
				total: 0,
			}
		},

		components: {
			NavTo,
			Header
		},
		mounted() {
			this._load()
		},
		methods: {
			_load(){
				this.http.post('order/admin/get_order').then(res=>{
                    let n=[];
                    for(let i=0;i<res.data.length;i++){
                        if(res.data[i].item_id>0){
                            n.push(res.data[i]);
                        }
                    }
                    this.tableDatas=n;
					this.total = this.tableDatas.length;
					this.tableData = this.tableDatas.slice(0, 10);
				})
			},
            filterTag(value, row) {
                return row.state === value;
            },
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.tableData = this.tableDatas.slice(start, end);
			},
		}
	}
</script>



<style lang="less">
	/* <style>   */
    .reseller_order {}
</style>
