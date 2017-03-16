<?php
namespace Home\Controller;
use Think\Controller;
class VideopubController extends Controller {
    public function _initialize(){
        // 自动运行方法
        //获取导航
        $model = M("model");
        $nav = $model->field("name,pk") -> where("is_show = 1 and custom_sort >0") ->order("custom_sort desc") -> select();
        $this -> assign("nav",$nav);

        //获取底部友情链接
        $friendship_links = M("friendship_links");
        $friend = $friendship_links->where("position = 0")->field("name,url") ->order("custom_sort desc") ->select();
        $this -> assign("friend",$friend);

        //登录状态判断
        if(!session('?c_id')){
            //未登录
        }else{
            $id = session('c_id');
            $this -> assign("id",$id);
        }

        //客服状态判定
        $customer_service = M("customer_service");
        if ($customer_service -> find() == null){
        }else{
            $slist = $customer_service ->select();
            $this -> assign("service",$slist);
        }

        //最新课程
        $video_category = M("video_category")->field("pk,update_time,name")->order("custom_sort desc,update_time desc")->where("is_show=1")->limit(5)->select();
        $this -> assign("video_category",$video_category);


        //详细课程
        $videos = M("video")->field("pk,update_time,title,is_title_bold,title_color")->order("custom_sort desc,update_time desc")->where("is_show=1")->limit(5)->select();
        $this -> assign("videoLimit",$videos);


    }
}