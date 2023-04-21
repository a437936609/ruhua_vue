<template>
	<div class="list">
		<el-container>
			<el-aside width="200px">
				<NavTo></NavTo>
			</el-aside>

			<el-container style="" class="pro-list">
				<el-header style="border-bottom: 1px solid #d0d0d0;background-color: #FFFFFF;">
					<Header></Header>
				</el-header>
				<transition appear appear-active-class="animated fadeInLeft">
					<el-main style="background-color: #F3F3F3;">
						<el-tabs type="border-card">
							<el-tab-pane label="检测版本">
								<el-button @click="up" type="primary" size="medium" style="margin-bottom: 20px;">获取新版本
								</el-button>
								<el-table :data="list" stripe style="width: 100%">
									<el-table-column type="index" label="序号" width="90"></el-table-column>
									<el-table-column prop="now_version" label="当前系统版本" width="200">
									</el-table-column>
									<el-table-column prop="version" label="最新系统版本" width="200">
									</el-table-column>
									<el-table-column prop="update_time" label="发布时间" width="200">
									</el-table-column>
									<el-table-column prop="content" label="版本描述" width="600">
										<template slot-scope="scope">
											<div v-html="scope.row.content"></div>
										</template>
									</el-table-column>
									<el-table-column prop="url" label="操作">
										<template slot-scope="scope">
											<a @click="download(scope.row)">下载</a>
										</template>
									</el-table-column>
								</el-table>
							</el-tab-pane>
							<el-tab-pane label="备份">
								<el-button type="primary" size="medium" style="margin-bottom: 20px;" @click="add_backup"
									v-loading.fullscreen.lock="fullscreenLoading">备份数据库</el-button>
								<el-table :data="tableData" stripe style="width: 100%">
									<el-table-column type="index" label="序号">
									</el-table-column>
									<el-table-column prop="name" label="文件名称">
									</el-table-column>
									<el-table-column prop="size" label="文件大小">
									</el-table-column>
									<el-table-column prop="url" label="文件地址">
									</el-table-column>
									<el-table-column prop="create_time" label="备份时间">
									</el-table-column>
									<el-table-column label="操作">
										<template slot-scope="scope">
											<el-button type="danger" size="medium" @click="del_backup(scope.row.id)">删除
											</el-button>
											<el-button type="primary" size="medium">
												<a style="text-decoration: none;color: #FFFFFF;"
													:href="surl + scope.row.url" target="_blank">下载</a>
											</el-button>


										</template>
									</el-table-column>
								</el-table>
							</el-tab-pane>
							<el-tab-pane label="模板消息">
								<el-button size="medium" style="margin-bottom: 20px;" type="primary"
									@click="add_moulds">添加模板</el-button>
								<el-table :data="modle_list" stripe style="width: 100%">
									<el-table-column type="index" label="编号" width="80">
									</el-table-column>
									<el-table-column prop="temp_id" label="模板编号">
										<template slot-scope="scope">
											{{scope.row.temp_id}}
										</template>
									</el-table-column>
									<el-table-column prop="temp_key" label="模板ID">
										<template slot-scope="scope">
											{{scope.row.temp_key}}
										</template>
									</el-table-column>
									<el-table-column prop="temp_name" label="模板名称">
										<template slot-scope="scope">
											{{scope.row.temp_name}}
										</template>
									</el-table-column>
									<el-table-column prop="content" label="回复内容">
										<template slot-scope="scope">
											{{scope.row.content}}
										</template>
									</el-table-column>
									<el-table-column prop="temp_date" label="添加时间">
										<template slot-scope="scope">
											{{scope.row.temp_date}}
										</template>
									</el-table-column>
									<el-table-column label="操作">
										<template slot-scope="scope">
											<el-button type="success" @click="edit_moulds(scope.$index,scope.row.id)">编辑
											</el-button>
											<el-button type="danger" @click='del_moulds(scope.row.id)'>删除</el-button>
										</template>
									</el-table-column>
								</el-table>
							</el-tab-pane>
							<el-tab-pane label="隐私政策">
								<el-button @click="get_Ys" type="primary" plain :class="{'btn-blue': isYs==1}">隐私政策修改
								</el-button>
								<el-button @click="get_Fw" type="primary" plain :class="{'btn-blue': isYs==0}"> 商城服务协议修改
								</el-button>
								</br>
								<el-form ref="forms" :model="forms" label-width="0px" style="margin-top: 30px;">
									<el-form-item label="">
										<vue-ueditor-wrap v-model="forms.content" :config="myConfig"></vue-ueditor-wrap>
									</el-form-item>
									<el-form-item>
										<el-button type="success" @click="up_Ys" v-if="isYs == true">提交修改</el-button>
										<el-button type="success" @click="up_Fw" v-if="isYs == false">提交修改</el-button>
										<el-button @click="rest">取消</el-button>
									</el-form-item>
								</el-form>
							</el-tab-pane>
						</el-tabs>
					</el-main>
				</transition>
			</el-container>
		</el-container>
		<el-dialog :title="titMap[titleDialog]" :visible.sync="add_mould_dialog" width="30%"
			:before-close="handleCloseAE">
			<el-form ref="form" :model="add_mould" label-width="90px">
				<el-form-item label="模板ID">
					<el-input v-model="add_mould.temp_key"></el-input>
				</el-form-item>
				<el-form-item label="模板编号">
					<el-input v-model="add_mould.temp_id"></el-input>
				</el-form-item>
				<el-form-item label="模板名称">
					<el-input v-model="add_mould.temp_name"></el-input>
				</el-form-item>
				<el-form-item label="回复内容">
					<el-input type="textarea" style="resize:none;" :rows='8' v-model="add_mould.content"></el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel_add_mould">取 消</el-button>
				<template v-if="add_edit==true">
					<el-button type="primary" @click="sub_add_mould">确 定</el-button>
				</template>
				<template v-else>
					<el-button type="primary" @click="sub_edit_mould">修 改</el-button>
				</template>
			</span>
		</el-dialog>
	</div>
</template>

<script>
	import {
		Api_url
	} from "@/common/config";

	import NavTo from '@/components/navTo.vue'
	import Header from "@/components/header.vue";
	import VueUeditorWrap from "vue-ueditor-wrap";
	import {
		Loading
	} from "element-ui";

	export default {
		data() {
			return {
				list: [],
				new_version: '',
				backup: Api_url + 'backup/get_backup',
				surl: Api_url,
				tableData: '',
				fullscreenLoading: false,
				tableData: [],
				modle_list: [],
				add_mould_dialog: false,

				add_mould: {
					temp_key: '',
					temp_id: '',
					temp_name: '',
					content: '',
					temp_date: '2020-07-20',
				},
				id: '',
				titMap: {
					add_mould_title: '新增模板',
					edit_mould_title: '编辑模板',
				},
				titleDialog: '',
				add_edit: true,
				forms: {
					content: '<p>彩色</p>',
				},
				isYs: 1,
				contentA: {
					str: '',
				},
				myConfig: {
					autoHeightEnabled: false,
					initialFrameHeight: 500,
					initialFrameWidth: "60%",
					serverUrl: '/index.php/index/admin/ue_uploads',
					UEDITOR_HOME_URL: this.$ue + '/static/UEditor/',
					toolbars: [
						[
							"justifyleft",
							"justifycenter",
							"justifyright",
							"justifyjustify",
							"bold",
							"forecolor",
							"fontsize",
							"source",
							"insertimage"
						]
					]
				},
			}
		},
		components: {
			NavTo,
			Header,
			VueUeditorWrap,
		},
		mounted() {
			this.get_backup()
			this._load();
			this.get_Ys();
		},
		methods: {
			get_Ys() {
				this.http.get('gy/ys').then(res => {
					this.forms['content'] = res.data['msg']
				})
				this.isYs = true;
			},
			up_Ys() {
				this.contentA['str'] = this.forms['content'];
				this.http.post('gy/update_ys', this.contentA).then(res => {
					this.$message({
						showClose: true,
						message: "隐私政策修改成功",
						type: "success"
					});
				})
			},
			get_Fw() {
				this.http.get('gy/fw').then(res => {
					this.forms['content'] = res.data['msg']

				})
				this.isYs = false;
			},
			up_Fw() {
				this.contentA['str'] = this.forms['content'];
				this.http.post('gy/update_fw', this.contentA).then(res => {
					this.$message({
						showClose: true,
						message: "商城服务协议修改成功",
						type: "success"
					});
				})
			},
			emit_one() {
				console.log(123)
			},

			rest() {
				if (this.isYs)
					this.get_Ys();
				else
					this.get_Fw();
			},
			_load() {
				this.http.get('cms//template/get_template').then(res => {
					this.modle_list = res.data;
				})
			},


			// 添加模板
			add_moulds() {
				this.add_mould_dialog = true;
				this.titleDialog = 'add_mould_title';
				if (this.add_edit != true) {
					this.add_edit = true;
				}
			},
			// 提交添加
			sub_add_mould() {
				if (this.add_mould.temp_key < 6) {
					this.$message({
						message: '模板ID不能少于6位',
						type: 'warning'
					});
					this.add_mould.temp_key = '';
					return
				}
				if (this.add_mould.temp_id < 6) {
					this.$message({
						message: '模板编号不能少于6位',
						type: 'warning'
					});
					this.add_mould.temp_id = '';
					return
				}
				if (this.add_mould.temp_name == '') {
					this.$message({
						message: '模板名称不能为空',
						type: 'warning'
					});
					this.add_mould.temp_name = '';
					return
				}
				if (this.add_mould.content == '') {
					this.$message({
						message: '回复内容不能为空',
						type: 'warning'
					});
					this.add_mould.content = '';
					return
				}
				this.http.post_show('cms/template/add_template', this.add_mould).then(res => {
					this.add_mould.temp_date = new Date();
					this.$message({
						message: '添加成功',
						type: 'success'
					});
					this.add_mould = {
						temp_key: '',
						temp_id: '',
						temp_name: '',
						content: '',
						temp_date: '',
					}
					this.add_mould_dialog = false;
					this._load();
				})
			},
			// 取消添加
			cancel_add_mould() {
				this.add_mould = {
					temp_key: '',
					temp_id: '',
					temp_name: '',
					content: '',
					temp_date: '',
				}
				this.add_mould_dialog = false;
			},

			// 修改模板
			edit_moulds(index, id) {
				this.add_mould_dialog = true;
				this.titleDialog = 'edit_mould_title';
				this.add_mould.temp_key = this.modle_list[index].temp_key;
				this.add_mould.temp_id = this.modle_list[index].temp_id;
				this.add_mould.temp_name = this.modle_list[index].temp_name;
				this.add_mould.content = this.modle_list[index].content;

				if (this.add_edit == true) {
					this.add_edit = false;
				}
				this.id = id;
				console.log(id);
			},
			// 提交修改
			sub_edit_mould() {
				console.log(this.add_mould)
				let edit_arr = {
					temp_key: this.add_mould.temp_key,
					temp_id: this.add_mould.temp_id,
					temp_name: this.add_mould.temp_name,
					content: this.add_mould.content,
					id: this.id,
				}
				console.log(edit_arr);
				this.http.post_show('cms/template/update_template', edit_arr).then(res => {
					this.$message({
						type: 'success',
						message: '修改成功'
					});
					this.add_mould_dialog = false;
					this._load();
				})
			},
			// 取消修改
			cancel_edit_mould() {
				this.edit_mould = {
					temp_key: '',
					temp_id: '',
					temp_name: '',
					content: '',
					temp_date: '',
				}
				this.id = "";
				this.add_mould_dialog = false;
			},
			// 删除模板
			del_moulds(id) {
				this.$confirm('确定删除该模板？', '提示', {
					confirmButtonText: '删除',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.del('cms/template/del_template', {
						id: id
					}).then(res => {
						this.$message({
							type: 'success',
							message: '删除成功'
						});
						this._load();
					});
				})
			},

			handleCloseAE() {
				this.add_mould = {
					temp_key: '',
					temp_id: '',
					temp_name: '',
					content: '',
					temp_date: '',
				};
				this.add_mould_dialog = false;
			},
			get_backup() {
				this.http.get('backup/get_backup').then(res => {
					this.tableData = res.data
				})
			},
			add_backup() {
				this.fullscreenLoading = true; //正式版解开注释
				const that = this
				setTimeout(_ => {
					that.fullscreenLoading = false;
				}, 1000)
				this.http.get_show('backup/add_backup').then(res => {
					this.fullscreenLoading = false;
					this.get_backup()
				})
			},
			del_backup(id) {
				this.$confirm('确定删除该备份?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('backup/del_backup?id=' + id).then(res => {
						this.$message({
							type: 'success',
							message: '删除成功!'
						});
						this.get_backup()
					})
				})
			},
			up() {
				this.http.get('update/get_versionMore').then(res => {
					console.log('xx:', res)
					if (res.status == 200) {
						this.$message({
							message: '获取成功',
							type: 'success'
						});
						this.list = res.data
						this.new_version = res.data.version
					} else {
						this.$message({
							message: '非授权站点，请购买商业授权，避免法律纠纷',
							type: 'error',
							duration: 7000
						});
						this.list = []
					}
					console.log(this.list)
				})
			},
			download(item) {
				this.http.get_show('update/get_url_more?id=' + item.id).then(res => {
					if (res.status != 200) {
						this.$message({
							message: res.msg,
							type: 'error',
							duration: 7000
						});
						return;
					}
					const aLink = document.createElement('a');
					aLink.href = res.data.data
					aLink.target = '_blank'
					aLink.download = item.version + '.zip';
					aLink.click();
					aLink.remove();
				})
			}
		},

	}
</script>

<style lang="less" scoped="">
	.pro-list {
		line-height: 30px;
		text-align: left;
	}
</style>
