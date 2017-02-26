<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
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
        

    }
}