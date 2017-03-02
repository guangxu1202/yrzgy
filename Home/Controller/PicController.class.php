<?php
namespace Home\Controller;
use Think\Controller;
class PicController extends CommonController {

    //图片故事列表
    function lists(){

        //获取图片故事
        $picture_story = M("picture_story");

        //分页输出
        $count      = $picture_story-> where("is_show = 1") ->order("custom_sort desc")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,15); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $picture_story->where("is_show = 1") ->order("custom_sort desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }
    function show(){

        //获取图片故事
        $picture_story = M("picture_story");
        if ($picture_story->where("is_show = 1") ->find(I("get.u")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else {
            $info = $picture_story->field("title,story_descirbe,keywords,update_time")->where("pk=".I("get.u"))->select();

            //获取图片展示列表
            $picture_story_item = M("picture_story_item");
            $list = $picture_story_item -> where("picture_story_id =".I("get.u")) ->select();
            $this->assign("list", $list);

            //图片总数
            $sum = $picture_story_item ->where("picture_story_id =".I("get.u"))->count();
            $this->assign("sum", $sum);

            //上一个图集
            $prev = $picture_story -> order("pk desc")->where("is_show = 1 and pk<".I("get.u"))->find();
            $this->assign("prev", $prev);

            //下一个图集
            $next =  $picture_story -> order("pk asc")->where("is_show = 1 and pk>".I("get.u"))->find();
            $this->assign("next", $next);


            $this->assign("info", $info);
            $this->display();
        }
    }
}