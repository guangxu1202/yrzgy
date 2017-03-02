<?php
namespace Home\Controller;
use Think\Controller;
class SystemController extends CommonController {

    //条款显示
    function show(){
        $miscellaneous = M("miscellaneous");
        $info = $miscellaneous->where("code='".I("get.u")."'") ->find();

        if ($info == null){
            //错误ID
            $this->error("页面无法访问！");
        }else {
            //获取所属模块
            $info1[0] = $info;
            $this->assign("info1", $info1);

            $this->display();
        }
    }
    //知名媒体专题报道
    function  report(){
        //列表获取
        $friendship_links = M("friendship_links");
        $count      = $friendship_links->field("name,url") ->where("position = 1") ->order("custom_sort desc")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $friendship_links->field("name,url") ->where("position = 1")->order('custom_sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();
    }
}