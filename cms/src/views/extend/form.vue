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
          <transition appear appear-active-class="animated fadeInLeft">
            <div class="article">
              <el-row>
                <el-col :span="2">
                  <el-button @click="jumpback" size="mini">返回</el-button>&emsp;
                </el-col>
                <el-col :span="4">
                  万能表单开启状态：
                  <el-switch
                    style
                    v-model="form_list[0].value"
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
              <el-tabs v-model="activeName" v-if="form_list[0].value == 1">
                <el-tab-pane label="表单" name="1">
                  <div class="fx-con">
                    <el-table :data="list" border style="width: 100%">
                      <el-table-column type="index" label="序号" width="60">
                        <el-input v-model="index"></el-input>
                      </el-table-column>
                      <el-table-column prop="name" label="名称" width="380"></el-table-column>

                      <el-table-column label="样式">
                        <template slot-scope="scope">
                          <p v-if="scope.row.type == 'radio'">单选</p>
                          <p v-if="scope.row.type == 'check'">多选</p>
                          <p v-if="scope.row.type == 'input'">输入框</p>
                          <p v-if="scope.row.type == 'select'">下拉</p>
                          <p v-if="scope.row.type == 'date'">日期</p>
                          <p v-if="scope.row.type == 'time'">时间</p>
                          <p v-if="scope.row.type == 'city'">城市</p>
                        </template>
                      </el-table-column>
                      <el-table-column prop="create_time" label="创建时间"></el-table-column>
                      <el-table-column prop="is_hidden" label="隐藏" width="100%">
                        <template slot-scope="scope">
                          <el-switch
                            @change="onswitch(scope.row.id)"
                            v-model="scope.row.is_hidden"
                            :active-value="1"
                            active-color="#409EFF"
                            inactive-color="#DCDFE6"
                          ></el-switch>
                        </template>
                      </el-table-column>

                      <el-table-column prop="operation" label="操作">
                        <template slot-scope="scope">
                          <el-button @click="edit_get(scope.row.id)" type="success" size="small">修改</el-button>
                          <el-button
                            style="margin-left: 10px"
                            type="danger"
                            size="small"
                            slot="reference"
                            @click="del(scope.row.id,scope.$index)"
                          >删除</el-button>
                        </template>
                      </el-table-column>
                      <strong></strong>
                    </el-table>
                    <el-button
                      style="margin-top: 10px"
                      type="success"
                      size="small"
                      slot="reference"
                    >提交排序</el-button>
                  </div>
                </el-tab-pane>

                <el-tab-pane label="添加表单" name="2">
                  <div class="fx-con">
                    <Form :edit="is_form_edit" @changezhi="changezhi"></Form>
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
import Pic from "../PicList.vue";
import { Loading } from "element-ui";
import VueUeditorWrap from "vue-ueditor-wrap";
import { Api_url } from "@/common/config";
import NavTo from "@/components/navTo.vue";
import Header from "@/components/header.vue";
import Form from "@/components/form.vue";

export default {
  name: "Article",
  data() {
    return {
      form_list: [],
      activeName: "1",
      is_form_edit: 0,
      img_list: [],
      drawer: false,
      length: 1,
      is_cg: 0,
      isedit: false,
      tab_nav: 1,
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
      options: [
        {
          value: 1,
          label: "独立文章",
        },
        {
          value: 2,
          label: "小程序跳转",
        },
        {
          value: 3,
          label: "公告",
        },
        {
          value: 4,
          label: "活动",
        },
        {
          value: 5,
          label: "个人中心",
        },
      ],
      oid: 0,
      form: {
        title: "",
        content: "",
        type: "",
        appid: "",
        path: "",
        image: [],
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
  },
  methods: {
    edit() {
      let data = {
        id: this.form_list[0].id,
        data: this.form_list[0].value,
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
      this.http.post_show("cms/get_config?type=11").then((res) => {
        this.form_list = res.data;
		console.log("form_list************************")
		console.log(this.form_list)
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
          that.getArticles();
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
        that.getArticles();
      });
    },
    get_wntable() {
      var that = this;
      this.http.get("wntable/getAll").then((res) => {
        that.list = res.data;
        console.log(that.list);
      });
    },
    //获取所有文章
    getArticles() {
      var that = this;
      let loadingInstance = Loading.service({
        fullscreen: true,
      }); //显示加载
      this.http.get("article/admin/get_all_article").then((res) => {
        console.log(res.data);
        loadingInstance.close(); //关闭加载
        var res_code = res.status.toString();
        if (res_code.charAt(0) == 2) {
          that.list = res.data;
        }
      });
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
    //删除商品
    /* del(id, index) {
				var that = this;
				this.$confirm("是否删除?", "提示", {
					confirmButtonText: "确定",
					cancelButtonText: "取消",
					type: "warning"
				}).then(() => {
					this.http
						.put_show("article/admin/del_article", {
							id: id
						})
						.then(res => {
							that.$message({
								showClose: true,
								message: "删除成功",
								type: "success"
							});
							that.list.splice(index, 1);
						});
				});
			}, */
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
    edit_get(id) {
      var that = this;
      console.log("id0");
      console.log(id);
      console.log("id0");
      // -------------------------------------------文章类型为表单时的修改
      // this.tab_nav = 3
      // this.is_form  = true
      // this.form_btn = '返回'
      // this.is_form_edit = 1
      // -----------------------------------------------end

      // 此时没有接口,需要从接口中判断文章列表中的文章类型为表单还是文章,根据值跳转不同界面

      // -------------------------------------------------------------类型为文章时取消注释，勿删
      this.http.get("article/get_one_article?id=" + id).then((res) => {
        that.btn_title = "文章列表";
        that.form.title = res.data.title;
        that.form.content = res.data.content;
        that.form.type = res.data.type;
        if (res.data.type == 2) {
          that.form.appid = res.data.summary;
          that.form.path = res.data.content;
        }
        this.img_list.push(res.data.img);
        that.isedit = true;
        that.oid = res.data.id;
        that.tab_nav = 2;
      });
      // ------------------------------------------------------------------end
    },
    close_fun(done) {
      this.clear_data();
      done(); //官方实例用法
    },
    clear_data() {
      this.img_list = [];
      this.dialogFormVisible = false;
      this.form.type = "";
      this.form.title = "";
      this.form.content = "";
      this.oid = 0;
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
    //this.getArticles();
    this.get_wntable();
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
