<template>
	<div class="coupon">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>

				<transition appear appear-active-class="animated fadeInLeft">
					<el-main>
						<div class="article">
							<el-button  @click="jumpback">返回</el-button> &emsp;
							<el-button type="primary" @click="add_user">添加优惠券</el-button>
							<el-button type="primary" @click="fa_user">发放优惠券</el-button>
							<div style="height:20px;">&nbsp;</div>
							<template>
								<!-- 弹窗--发放优惠券 -->
								<el-dialog title="" :visible.sync="editbox" width="350px" center :close-on-click-modal='false' :show-close="false">
									<el-form :model="form_ff">
										<h1 align="center">优惠券发放</h1>
										<br>
										<el-form-item label="选择优惠券:" :label-width="formLabelWidth">
											<el-select v-model="form_ff.yhq" placeholder="请选择优惠券">
												<el-option v-for="item in list" :value="item.id" :label="item.name" v-if="item.is_appoint==1"></el-option>
											</el-select>
										</el-form-item>
										<el-input placeholder="用户昵称" style='width: 250px' size='small' v-model="value_ff" @keyup.enter.native="serach(value_ff)" > </el-input>
										<el-button size='small' @click='serach(value_ff)' style="padding-left:10px;"><i class="el-icon-search"></i></el-button>
										<el-form-item label="选择用户:">
											<!--<el-select v-model="form_ff.userid" placeholder="请选择用户">
												<el-option v-for="item in list_user" :value="item.id" :label="item.nickname"></el-option>
											</el-select> -->
											
										</el-form-item>
										<span v-for="item in list_user" v-if="is_user==true">
											<el-radio v-model="form_ff.uid" :label="item.id">{{item.nickname}}</el-radio>
										</span>
									</el-form>
									
									<div slot="footer" class="dialog-footer" style='text-align: center'>
										<el-button @click="editbox = false,is_user =false">返 回</el-button>
										<el-button type="primary" @click="onSubmit(form_ff)" v-if="is_user==false" disabled>确 定</el-button>
										<el-button type="primary" @click="onSubmit(form_ff)" v-if="is_user==true" >确 定</el-button>
									</div>
								</el-dialog>
								
							<el-tabs type="border-card">
								  <el-tab-pane label="优惠券列表">
								<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="name" label="优惠券名称"></el-table-column>
									<!-- <el-table-column prop="img.url" label="图片">
										<template slot-scope="scope">
											<template v-if="scope.row.img">
												<img :src="scope.row.img.url" />
											</template>
											<template v-if="!scope.row.img && scope.row.img_url">
												<img :src="scope.row.img_url[0]" />
											</template>
										</template>
									</el-table-column> -->
									<el-table-column prop="status" label="使用次数">
										<template slot-scope="scope">
											{{scope.row.status == 1?'一次':'不限'}}
										</template>
									</el-table-column>
									<el-table-column prop="type" label="类型">
										<template slot-scope="scope">
											{{scope.row.type == 1?'店铺优惠券':'其他'}}
										</template>
									</el-table-column>
									<el-table-column prop="is_appoint" label="发放方式">
										<template slot-scope="scope">
											{{scope.row.is_appoint == 1?'指定发放':'平台领取'}}
										</template>
									</el-table-column>
									<el-table-column prop="goods_ids" label="可使用商品">
										<template slot-scope="scope">
											{{scope.row.goods_ids == 0?'全部商品':'部分商品'}}
										</template>
									</el-table-column>
									<el-table-column prop="full" label="满多少">
										<template slot-scope="scope">
											<template v-if="scope.row.full == 0">无门槛</template>
											<template v-else>{{scope.row.full}}</template>
										</template>
									</el-table-column>
									<el-table-column prop="reduce" label="减多少"></el-table-column>
									<el-table-column prop="start_time" label="起始时间"></el-table-column>
									<el-table-column prop="end_time" label="结束时间"></el-table-column>
									<el-table-column prop="stock" label="库存">
										<template slot-scope="scope">
											<template v-if="!scope.row.stock">无限张</template>
											<template v-else>{{scope.row.stock}}</template>
										</template>
									</el-table-column>
									<el-table-column prop="operation" label="操作" width="300px">
										<template slot-scope="scope">
											<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">删除</el-button>
										</template>
									</el-table-column><strong></strong>
								</el-table>
								</el-tab-pane>
								  <el-tab-pane label="指定发放" @click="get_give_coupon(4)">
									   <el-button size="mini" type="primary" @click="get_give_coupon(4)">全部</el-button>
									   <el-button size="mini" type="primary" @click="get_give_coupon(0)">未使用</el-button>
									   <el-button size="mini" type="primary" @click="get_give_coupon(1)">已使用</el-button>
									   <el-button size="mini" type="primary" @click="get_give_coupon(2)">已完成</el-button>
									   <el-button size="mini" type="primary" @click="get_give_coupon(3)">已过期</el-button>
									   
									   <el-table :data="give_coupon_alit" border style="width: 100%">
									   	<el-table-column type="index" label="序号" width="50px"></el-table-column>
										<el-table-column prop="users.nickname" label="用户名"></el-table-column>
									   	<el-table-column prop="coupons.name" label="优惠券名称"></el-table-column>
									   	
									   	<el-table-column prop="type" label="类型">
									   		<template slot-scope="scope">
									   			{{scope.row.type == 1?'店铺优惠券':'其他'}}
									   		</template>
									   	</el-table-column>
									  
									   	<el-table-column prop="goods_ids" label="可使用商品">
									   		<template slot-scope="scope">
									   			{{scope.row.goods_ids == 0?'全部商品':'部分商品'}}
									   		</template>
									   	</el-table-column>
									   	<el-table-column prop="full" label="满多少">
									   		<template slot-scope="scope">
									   			<template v-if="scope.row.full == 0">无门槛</template>
									   			<template v-else>{{scope.row.full}}</template>
									   		</template>
									   	</el-table-column>
									   	<el-table-column prop="reduce" label="减多少"></el-table-column>
									   	<el-table-column prop="create_time" label="起始时间"></el-table-column>
									   	<el-table-column prop="end_time" label="结束时间"></el-table-column>
										<el-table-column prop="goods_ids" label="状态">
											<template slot-scope="scope">
												<a v-if="scope.row.status == 0"> 未使用</a>
												<a v-if="scope.row.status == 1"> 已使用</a>
												<a v-if="scope.row.status == 2"> 已完成</a>
												<a v-if="scope.row.status == 3"> 已过期</a>
											</template>
										</el-table-column>
									   	<el-table-column prop="operation" label="操作" width="300px">
									   		<template slot-scope="scope">
									   			<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del_user_coupon(scope.row.id)">删除</el-button>
									   		</template>
									   	</el-table-column><strong></strong>
									   </el-table>
									   <el-pagination style='padding-top: 60px;text-align: center;' background layout="prev, pager, next" :total="total"
									    :page-size="size" @current-change="jump_page">
									   </el-pagination>
								  </el-tab-pane>
								  </el-tabs>
							</template>
						</div>
					</el-main>
				</transition>
			</el-container>
		</el-container>
	</div>
</template>

<script>
	import NavTo from '@/components/navTo.vue'
	import Header from '@/components/header.vue'
	export default {
		data() {
			return {
				status: '1',
				goods_ids: '0',
				dialogImageUrl: '',
				dialogVisible: false,
				oid: 0,
				form: {},
				list: [],
				value: '',
				fa_yhq:0,
				editbox:false,
				is_user:false,
				yhq:[],
				form_ff: [
				],
				list_user: [],
				value_ff:'',
				give_coupon:[],
				give_coupon_alit:[],
				coupon_stuta:4,
				total:0,
				size:10,
			}
		},
		components: {
			Header,
			NavTo
		},
		methods: {
			//返回
			jumpback() {
				this.$router.push({
					path: '/extend/application'
				})
			},
			handleRemove(file, fileList) {
				console.log(file, fileList);
			},
			add_user() {
				this.$router.push({
					path: './addcoupon'
				})
			},
			fa_user() {
				this.editbox=true;
			},
			//确认发放优惠券
			onSubmit(e){
					console.log('确认发放优惠券');
					console.log(e);
				this.http.get('coupon/admin/give_coupon?uid='+e.uid+'&cid='+e.yhq).then(res => {	
						if(res.data==null)
						{
							this.$message({
								showClose: false,
								message: res.msg,
								type: 'error'
							});
						}
						if(res.data>0){ 
							this.get_give_coupon(0)
							this.$message({
								showClose: true,
								message: res.msg,
								type: 'success'
							});
							
						}
				})
				
			},
			//返回用户
			serach(e){
				this.http.get('user/admin/get_user_name?name='+e).then(res => {
					this.list_user = res.data;
					console.log('返回用户');
					this.is_user =true;
					if(res.data.length<1){
						this.$message({
							showClose: false,
							message: '没有搜到用户',
							type: 'error'
						});
						this.is_user =false;
					}
					
				})
				
			},
			//返回优惠券
			get_give_coupon(e){
				this.coupon_stuta =e
				this.http.get('coupon/admin/get_give_coupon?state='+e).then(res => {
					//this.give_coupon = res.data;
					this.give_coupon = res.data;
					this.total = res.data.length;			
					this.give_coupon_alit = this.give_coupon.slice(0, this.size);		
					console.log('返回优惠券',this.total);
					if(res.data.length<1){
						this.$message({
							showClose: false,
							message: '没有数据',
							type: 'error'
						});
					}
				})
			},
			edit(item) {
				this.form = item
				this.dialogVisible = true
			},
			//获取优惠券列表
			get_coupon() {
				this.http.get('coupon/admin/get_coupon').then(res => {
					this.list = res.data
				})
			},
			//删除优惠券
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('coupon/admin/del_coupon', {
						id: id
					}).then(() => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						this.get_coupon()
						// that.list.splice(index, 1);
					});
				})
			},
			//删除
			del_user_coupon(id){
				console.log('删除发放的指定优惠券');
				var that = this;
					this.http.put_show('coupon/admin/del_give_coupon', {
						id: id
					}).then(() => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						this.get_give_coupon(this.coupon_stuta)
						// that.list.splice(index, 1);
					});
				
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.give_coupon_alit = this.give_coupon.slice(start, end);
			},
		},
		mounted() {
			this.get_coupon();
			this.get_give_coupon(4);
		}
	}
</script>

<style lang="less">
	.coupon {
		background-color: #F3F3F3;

		.el-table__row {
			line-height: 40px !important;

			img {
				width: 80px !important;
				height: 80px !important;
			}
		}

		.el-main {
			height: auto !important;
			background-color: #F3F3F3;
			padding: 15px;

			.el-table {
				height: auto !important;
			}

			.el-table__body-wrapper,
			.is-scrolling-none,
			.el-table__body {
				height: auto !important;
			}
		}

		.article {
			line-height: 30px;
			background-color: #fff;
			padding: 15px;
			text-align: left;
		}

		.list-head {
			padding-bottom: 10px
		}

		.el-form-item__content {
			display: flex;
			justify-content: flex-start;
		}
	}
	
</style>
