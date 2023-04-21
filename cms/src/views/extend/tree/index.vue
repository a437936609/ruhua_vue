<template>
  <div class="root">
    <el-container>
      <el-aside width="200px">
        <NavTo></NavTo>
      </el-aside>
      <el-container>
        <el-header style="border-bottom: 1px solid #d0d0d0;">
          <Header></Header>
        </el-header>
        <el-main style="background-color: #F3F3F3;">
			<Zhishi :type="type" :list="sm"></Zhishi>
          <transition appear appear-active-class="animated fadeInLeft">
            <div class="article">
              <el-row>
                <el-col :span="2">
                  <el-button @click="jumpback" size="mini">返回</el-button>&emsp;
                </el-col>
                <el-col :span="4">
                 种树开关：
                  <el-switch
                    style
                    v-model="form_list.value"
                    active-color="#13ce66"
                    inactive-color="#ff4949"
                    active-text="开"
                    inactive-text="关"
                    active-value="1"
                    inactive-value="0"
					@change="edit"
                  ></el-switch>
                </el-col>
              </el-row>

              <div style="height:20px;">&nbsp;</div>
              <el-tabs v-model="activeName" v-if="form_list.value == 1">
                <el-tab-pane label="列表" name="1">
                  <div class="fx-con">
                    <el-table :data="list" border style="width: 100%">
                      
                      <el-table-column prop="desc" label="名称" width="380"></el-table-column>
					<el-table-column prop="value" label="值" width="380"></el-table-column>
                   
                      </el-table-column>

                      <el-table-column prop="operation" label="操作">
                        <template slot-scope="scope">
                          <el-button @click="edit_get(scope.row.id,scope.row.value)" type="success" size="small">修改</el-button>
                          
                        </template>
                      </el-table-column>
                      <strong></strong>
                    </el-table>
					
					<el-dialog title="修改" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
						<el-form ref="form" :model="form" label-width="80px">
							<el-form-item label="对应值">
								<el-input v-model="form.value"></el-input>
							</el-form-item>
						</el-form>
						<span slot="footer" class="dialog-footer">
							<el-button @click="cancel">取 消</el-button>
							<el-button type="primary" @click="sub_add">确 定</el-button>
						</span>
					</el-dialog>
                  </div>
                </el-tab-pane>
              </el-tabs>
            </div>
          </transition>
        </el-main>
      </el-container>
    </el-container>
    <Pic ref="child" :drawer="drawer" :father_fun="get_img" :length="length" :iscg="is_cg"></Pic>
  </div>
</template>

<script>
import Pic from "../../PicList.vue";
import { Loading } from "element-ui";
import VueUeditorWrap from "vue-ueditor-wrap";
import { Api_url } from "@/common/config";
import NavTo from "@/components/navTo.vue";
import Header from "@/components/header.vue";
import Form from "@/components/form.vue";
import Zhishi from "@/components/zhishi.vue";

export default {
  name: "Article",
  data() {
    return {
      form_list:{},
      activeName: "1",
      is_form_edit: 0,
      img_list: [],
      drawer: false,
      length: 1,
      is_cg: 0,
	  edit_value:0,
      isedit: false,
	  dialogVisible:false,
      tab_nav: 1,
	  type: "default",
	  list: [
	  ],
	  sm:[
		   "声明：目前本应用所提供的图片、图标及其元素来源于网络，仅供展示效果无商用权限，请自行更换。我们设计师正在制作相关素材，完成后将免费提供给大家"
	  ],
      dialogFormVisible: false,
      myConfig: {
        autoHeightEnabled: false,
        initialFrameHeight: 420,
        initialFrameWidth: "100%",
        serverUrl: Api_url + "index/admin/ue_uploads",
        UEDITOR_HOME_URL: this.$ue + "/static/UEditor/",
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
            "insertimage",
          ],
        ],
      },
      oid: 0,
      form: {
        id:'',
		value:0
      },
      formLabelWidth: "120px",
      list: [],
      is_form: false,
    };
  },
  components: {
    VueUeditorWrap,
    NavTo,
    Header,
    Pic,
    Form,
	Zhishi
  },
  methods: {
    edit() {
      let data = {
        id: this.form_list.id,
        data: this.form_list.value,
      };
      this.http.post_show("cms/set_sys", data).then((res) => {
        this.$message({
          message: "操作成功",
          type: "success",
		});
		this.get_config()
      });
    },
    get_config() {
      this.http.post_show("cms/get_config?type=13").then((res) => {
      //  this.form_list = res.data;
	  console.log(res.data)
		for(let i=0;i<res.data.length;i++){
			if(res.data[i].key=="tree"){
				this.form_list=res.data[i]
				res.data.splice(i,1)
				break;
			}
		}
		console.log("form_list************************")
		console.log(this.form_list)
		this.list=res.data
		console.log("form_list************************")
      });
    },
    //返回
    jumpback() {
      this.$router.push({
        path: "/extend/application",
      });
    },
    changezhi() {
      this.activeName = "1";
    },
    sub(form) {
      var that = this;
      let data = that.form;
      if (data["type"] == 2) {
        data["summary"] = data.appid;
        data["content"] = data.path;
      }
      delete data.appid;
      delete data.path;
      this.http
        .post_show("article/admin/add_article", that.form)
        .then((res) => {
          that.$message({
            showClose: true,
            message: "添加成功",
            type: "success",
          });
          that.tab_nav = 1;
          this.btn_title = "添加文章";
          that.clear_data();

        });
    },
    sub_edit(form) {
      var that = this;
      const data = that.form;
      data["oid"] = that.oid;
      if (data["type"] == 2) {
        data["summary"] = data.appid;
        data["content"] = data.path;
      }
      delete data.appid;
      delete data.path;
      this.http.post_show("article/admin/edit_article", data).then((res) => {
        that.$message({
          showClose: true,
          message: "修改成功",
          type: "success",
        });
        this.img_list = [];
        this.btn_title = "添加文章";
        this.tab_nav = false;
        that.clear_data();
      });
    },
	sub_add(){
		let data = {
		  id: this.form.id,
		  data: this.form.value,
		};
		this.http.post_show("cms/set_sys", data).then((res) => {
		  this.$message({
		    message: "操作成功",
		    type: "success",
				});
				this.clear_data()
				this.dialogVisible=false
				this.get_config()
		});
		/* this.http.post('cms/play_award/update',this.form).then(res=>{
			console.log(res)
			if(res){
				this.dialogVisible=false
				this.clear_data()
			}
		}) */
	},

    del(id, index) {
      var that = this;
      this.$confirm("是否删除?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning",
      }).then(() => {
        that.http
          .put_show("wntable/del", {
            id: id,
          })
          .then((res) => {
            that.$message({
              showClose: true,
              message: "删除成功",
              type: "success",
            });
            that.list.splice(index, 1);
          });
      });
    },
	cancel() {
		console.log("点击取消")
		this.dialogVisible = false
		this.dialogVisible_edit = false
		this.clear_data()
		
	},
	handleClose() {
		this.dialogVisible = false
		this.dialogVisible_edit = false
	},
  
    //是否隐藏
    onswitch(id) {
      var that = this;
      this.http
        .put_show("/cms/update", {
          id: id,
          db: "article",
          field: "is_hidden",
        })
        .then((res) => {
          console.log(res);
        });
    },
    edit_get(id,value) {
		this.dialogVisible=true
		this.form.id=id
		this.form.value=value
    },
    close_fun(done) {
      this.clear_data();
      done(); //官方实例用法
    },
    clear_data() {
      this.dialogFormVisible = false;
      this.form.id = "";
      this.form.award = "";
    },
    get_img(e) {
      this.drawer = false;
      for (let k in e) {
        const v = e[k];
        this.img_list.push(v);
        this.form.image = v.id;
      }
      this.length = 1 - this.img_list.length;
      console.log("get_img_end:", e, this.img_list);
    },
    //打开图库
    choose_pic() {
      this.length = 1 - this.img_list.length;
      this.drawer = true;
    },
    del_img(index) {
      this.img_list.splice(index, 1);
      this.form.image.splice(index, 1);
    },
  },
  mounted() {
    this.get_config();
  },
};
</script>

<style lang="less" scoped="">
.article {
  line-height: 30px;
  background-color: #fff;
  padding: 15px;
  text-align: left;
}

.list-head {
  padding-bottom: 10px;
}

.picA {
  width: 148px;
  height: 148px;
  position: relative;
  background-color: #fbfdff;
  border: 1px dashed #c0ccda;
  border-radius: 6px;
  box-sizing: border-box;
  vertical-align: top;
  text-align: center;
  margin: 6px 6px 6px 10px;

  img {
    width: 144px;
    height: 144px;
    border: 1px solid #bfbfbf;
    border-radius: 3px;
  }

  .el-icon-circle-close {
    position: absolute;
    top: -13px;
    right: -10px;
    color: #7c7c7c;
    font-size: 25px;
    cursor: pointer;
  }
}
</style>
