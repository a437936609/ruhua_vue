
<!--测试数据-->
<template>
  <div class="apply">
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
              <el-button @click="jumpback">返回</el-button>&emsp;
              <el-button type="primary" v-if="show==false" @click="increase">安装</el-button>
              <div style="height:20px;">&nbsp;</div>
              <!-- 进度条 -->
              <div class="bar" v-show="show==false">
                <!-- elementui组件 -->
                <template>
                  <el-progress
                    :percentage="percentage"
                    :stroke-width="10"
                    :color="customColorMethod"
                  ></el-progress>
                </template>

                <template v-if="progsuc==1">
                  <div style="width:100%;text-align:center;margin-top:60px;">
                    <!-- <el-button style="width:10%" type="primary" @click="jumpback">已完成</el-button> -->
                  </div>
                </template>
                <template v-else-if="progsuc==0">
                  <div style="width:100%;text-align:center;margin-top:60px;">
                    <el-button style="width:10%" type="success">安装中</el-button>
                  </div>
                </template>
                <template v-else>
                  <div style="width:100%;text-align:center;margin-top:60px;">
                    <el-button style="width:10%" type="info">未安装</el-button>
                  </div>
                </template>
              </div>
              <!--已完成-->
              <div class="fix" v-show="show==true">
                <img src="@/img/success2.png" alt />
                <div style="width:100%;text-align:center;margin-top:5px;">
                  <span
                    style="display:inline-block;fontSize:14px;color:rgb(5,222,208)"
                    @click="jumpback"
                  >已完成</span>
                </div>
              </div>
            </div>
          </el-main>
        </transition>
      </el-container>
    </el-container>
  </div>
</template>

<script>
import { Loading } from "element-ui";
import { Api_url } from "@/common/config";

import NavTo from "@/components/navTo.vue";
import Header from "@/components/header.vue";
export default {
  data() {
    return {
      percentage: 0,
      customColor: "#409eff",
      customColors: [
        { color: "#f56c6c", percentage: 20 },
        { color: "#e6a23c", percentage: 40 },
        { color: "#5cb87a", percentage: 60 },
        { color: "#1989fa", percentage: 80 },
        { color: "#6f7ad3", percentage: 100 },
      ],
      progclass: false,
      progsuc: -1,
      show: false,
      cone: false,
      stoken:'',
    };
  },
  components: {
    Header,
    NavTo,
  },
  mounted() {
    this._load();
  },
  methods: {
    // 检查是否安装
    _load() {
      this.stoken = localStorage.getItem('stoken')
      this.http.post("plugIn/is_Install",{
        lock: 'import_plug'
      }).then((res) => {
        if (!res.data) {
          this.$message({
            message: "未安装",
            type: "warning",
          });
          this.progsuc = 1;
          this.show = false;
        } else {
          this.show = true;
        }
      });
    },

    //返回
    jumpback() {
      this.$router.push({
        path: "/extend/application",
      });
    },
    customColorMethod(percentage) {
      if (percentage < 30) {
        return "#909399";
      } else if (percentage < 70) {
        return "#e6a23c";
      } else {
        return "#67c23a";
      }
    },
    // 安装
     increase() {
		
      this.progsuc = 0;
      let prog = setInterval((_) => {
		  console.log("安装")
		  this.http.post("plugIn/is_Install",{
		    lock: 'import_plug'
		  }).then(res=>{
			  console.log(res.data)
		  			 if(res.data){
		  				 this.cone=true
		  			 }
		  })
		  
		   
        this.percentage += 10;
        if (this.percentage > 100) {
          this.percentage = 100;
          clearInterval(prog);
          this.progsuc = 1;
          this.show = true;
          this.disabled = true;
        }
        if (this.cone != true) {
          if (this.percentage > 90) {
            this.percentage = 90;
            this.progsuc = 0;
          }
        }
      }, 1000);
	  



     this.http.get_show("/index/admin/import?stoken="+this.stoken);
    },
  },
};
</script>

<style lang="less">
.apply {
  background-color: #f3f3f3;

  .el-main {
    height: auto !important;
    background-color: #f3f3f3;
    padding: 15px;
  }

  .article {
    line-height: 30px;
    background-color: #fff;
    padding: 15px;
    text-align: left;
    // 进度条
    .bar {
      padding: 10px 15px;
      border: 1px solid #f0f0f0;
      padding-top: 60px;
    }

    .fix {
      padding: 10px 15px;
      border: 1px solid #f0f0f0;
      padding-top: 60px;
      text-align: center;
    }
  }
  [v-cloak] {
    display: none;
  }
}
</style>