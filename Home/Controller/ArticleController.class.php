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
            $count      = $article->join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk")->field("a.title,a.pk,a.update_time") ->where("model.pk =".I("get.cate")) ->order("a.update_time desc")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list = $article->join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk")->field("a.title,a.pk,a.update_time") ->where("model.pk =".I("get.cate"))->order('a.update_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('list', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出

            //文章类左侧公共显示获取
            //获取图片故事
            $picture_story = M("picture_story");
            $story = $picture_story->field("thumbnail,title,pk")-> where("is_show = 1") ->order("custom_sort desc")-> limit("1")->select() ;
            $this -> assign("story",$story);

            //获取研修生园地
            $researcher  = $article ->join("as a LEFT JOIN article_model AS b ON a.pk = b.article_id LEFT JOIN model AS c ON b.model_id = c.pk ")->field("a.pk,a.title") ->order("a.custom_sort desc,a.update_time desc") ->where("c.pk = 12") -> limit("5") -> select();
            $this -> assign("researcher",$researcher);

            //获取专业委员会
            $Committee  = $article ->join("as a LEFT JOIN article_model AS b ON a.pk = b.article_id LEFT JOIN model AS c ON b.model_id = c.pk ")->field("a.pk,a.title") ->order("a.custom_sort desc,a.update_time desc") ->where("c.pk = 14") -> limit("5") -> select();
            $this -> assign("Committee",$Committee);

            //获取投票
            $vote = M("vote");
            $vote_arr = $vote ->field("title,pk")->order("custom_sort desc,update_time desc")->where("is_show = 1")->limit("5")->select();
            $this -> assign("vote_arr",$vote_arr);

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

            //文章类左侧公共显示获取
            //获取图片故事
            $picture_story = M("picture_story");
            $story = $picture_story->field("thumbnail,title,pk")-> where("is_show = 1") ->order("custom_sort desc")-> limit("1")->select() ;
            $this -> assign("story",$story);

            //获取研修生园地
            $researcher  = $article ->join("as a LEFT JOIN article_model AS b ON a.pk = b.article_id LEFT JOIN model AS c ON b.model_id = c.pk ")->field("a.pk,a.title") ->order("a.custom_sort desc,a.update_time desc") ->where("c.pk = 12") -> limit("5") -> select();
            $this -> assign("researcher",$researcher);

            //获取专业委员会
            $Committee  = $article ->join("as a LEFT JOIN article_model AS b ON a.pk = b.article_id LEFT JOIN model AS c ON b.model_id = c.pk ")->field("a.pk,a.title") ->order("a.custom_sort desc,a.update_time desc") ->where("c.pk = 14") -> limit("5") -> select();
            $this -> assign("Committee",$Committee);

            //获取投票
            $vote = M("vote");
            $vote_arr = $vote ->field("title,pk")->order("custom_sort desc,update_time desc")->where("is_show = 1")->limit("5")->select();
            $this -> assign("vote_arr",$vote_arr);


            $this->display();
        }
    }


}