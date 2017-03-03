<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends CommonController {

    //文章列表
    function article(){
        $model =M("model");
        if ($model->find(I("get.cate")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else {
            $model_name = $model->field("name")->where("pk=".I("get.cate"))->select();
            $this -> assign("model_name",$model_name[0]);
            //获取article
            //文章列表获取
            $article = M("article");
            $count      = $article->join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk")->field("a.title,a.pk,a.update_time") ->where("a.is_show = 1 and model.pk =".I("get.cate")) ->order("a.update_time desc")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list = $article->join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk")->field("a.title,a.pk,a.update_time") ->where("a.is_show = 1 and model.pk =".I("get.cate"))->order('a.update_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('list', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出

            $this->display();
        }
    }

    //文章详情
    function show(){
        $article = M("article");
        if ($article->find(I("get.u")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else {
            //获取所属模块
            $info = $article-> field("title,keywords,summary,author,article_from,update_time,content") ->where("pk=".I("get.u")) ->select();
            $this->assign("info", $info);
            $this->display();
        }
    }


}