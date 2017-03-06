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
            if (!empty($_POST)) {

                $verify = new \Think\Verify();
                if (!$verify->check(I("post.verify"))){
                    $this->error("验证码错误！");
                }
                //实例化
                $model = new \Model\CommentModel();
                //验证数据 CommentModel
                $z = $model -> create();
                if (!$z){
                    //show_bug($model -> getError());
                    $this->error("您录入的数据格式错误！");
                    exit();
                }

                //数据录入
                $log["audit"] = 0;
                $log["browse_count"] = 0;
                $log["is_admin"] = false;
                $log["is_leaf"] = 1;
                $log["content"] = I("post.content");
                $log["ip"] = get_client_ip();
                $log["publish_time"] = date("Y-m-d H:i:s");
                $log["article_id"] = I("post.saveid");
                $log["member_id"] = session("c_id");

                $model->data($log)->add();

                //录入成功
                $this->success("恭喜您，留言成功，请等待审核！");

            }
            else{
                //获取新闻内容
                $info = $article-> field("title,keywords,summary,author,article_from,update_time,content,browse_count,like_count,unlike_count,pk") ->where("pk=".I("get.u")) ->select();
                $this->assign("info", $info);
                $model_id = M("article_model")->where("article_id=".I("get.u"))->field("model_id") -> find();
                //show_bug($model_id["model_id"]);

                //上一篇
                $prev = $article->join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") -> field("a.pk,a.title")-> order("a.custom_sort desc,a.pk desc")->where("a.is_show = 1 and model.pk = '".$model_id["model_id"]."' and a.pk<".I("get.u"))->find();
                $this->assign("prev", $prev);

                //下一篇
                $next = $article->join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") -> field("a.pk,a.title")-> order("a.custom_sort desc,a.pk desc")->where("a.is_show = 1 and model.pk = ".$model_id["model_id"]." and a.pk>".I("get.u"))->find();
                $this->assign("next", $next);

                //已有评论循环
                $comment = M("comment")->join("as a left join member as b on a.member_id = b.pk") ->field("a.publish_time,a.content,b.nickname") ->where("a.audit = 1 and a.article_id =".I("get.u"))->order("a.publish_time desc") -> select();
                $this->assign("comment", $comment);
                $count = M("comment")->where("audit = 1 and article_id =".I("get.u")) ->count();
                $this->assign("count", $count);

                //文章点击数累加
                $article->where('pk='.I("get.u"))->setInc('browse_count',1);

                //会员状态
                if(!session('?c_id')){
                    //未登录
                }else{
                    $member = M("member")->field("nickname,email")->where("pk=".session('c_id')) -> find();
                    $this -> assign("member",$member);
                }
                $this->display();
            }

        }
    }

    //喜欢
    function like(){
        $article = M("article"); // 实例化User对象
        if ($article->find(I("post.id")) == null){
            $this->ajaxReturn(false);
        }else {
            //判断重复投票
            if (session("?like.".I("post.id"))){
                $this->ajaxReturn(false);
            }else{
                //喜欢数累加
                $article->where('pk='.I("post.id"))->setInc('like_count',1);
                //session限制累加频率
                session("like.".I("post.id"),I("post.id"));
            }
            $this->ajaxReturn(true);
        }
    }

    //不喜欢
    function unlike(){
        $article = M("article"); // 实例化User对象
        if ($article->find(I("post.id")) == null){
            $this->ajaxReturn(false);
        }else {
            //判断重复投票
            if (session("?unlike.".I("post.id"))){
                $this->ajaxReturn(false);
            }else{
                //喜欢数累加
                $article->where('pk='.I("post.id"))->setInc('unlike_count',1);
                //session限制累加频率
                session("unlike.".I("post.id"),I("post.id"));
            }
            $this->ajaxReturn(true);
        }
    }

    //留言咨询列表
    function message(){
        $model =M("comment");

        $count      = $model->where("pid is null and article_id is null and audit = 1") ->order("publish_time desc")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $model->where("pid is null and article_id is null and audit = 1") ->order("publish_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this->display();

    }

    //留言咨询详情
    function msg(){
        $model = M("comment");
        if ($model->find(I("get.u")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else {

            if (!empty($_POST)) {

                $verify = new \Think\Verify();
                if (!$verify->check(I("post.verify"))){
                    $this->error("验证码错误！");
                }
                //实例化
                $model = new \Model\CommentModel();
                //验证数据 CommentModel
                $z = $model -> create();
                if (!$z){
                    //show_bug($model -> getError());
                    $this->error("您录入的数据格式错误！");
                    exit();
                }

                //数据录入
                $log["audit"] = 0;
                $log["browse_count"] = 0;
                $log["is_admin"] = false;
                $log["is_leaf"] = 1;
                $log["pid"] = I("post.saveid");
                $log["content"] = I("post.content");
                $log["ip"] = get_client_ip();
                $log["publish_time"] = date("Y-m-d H:i:s");
                $log["member_id"] = session("c_id");

                $model->data($log)->add();

                //录入成功
                $this->success("恭喜您，留言成功，请等待审核！");

            }
            else{

                //获取评论详情
                $info = $model-> where("pk=".I("get.u")) ->select();
                $this->assign("info", $info);


                //已有评论循环
                $comment = $model->join("as a left join member as b on a.member_id = b.pk") ->field("a.publish_time,a.content,b.nickname,a.is_admin") ->where("a.audit = 1 and a.pid =".I("get.u"))->order("a.publish_time desc") -> select();
                $this->assign("comment", $comment);
                $count =$model->where("audit = 1 and pid =".I("get.u")) ->count();
                $this->assign("count", $count);

                //文章点击数累加
                $model->where('pk='.I("get.u"))->setInc('browse_count',1);

                //会员状态
                if(!session('?c_id')){
                    //未登录
                }else{
                    $member = M("member")->field("nickname")->where("pk=".session('c_id')) -> find();
                    $this -> assign("member",$member);
                }
                $this->display();
            }
        }
    }

    //留言
    function advice_msg(){

        if (!empty($_POST)) {

            $verify = new \Think\Verify();
            if (!$verify->check(I("post.verify"))){
                $this->error("验证码错误！");
            }
            //实例化
            $model = new \Model\CommentModel();
            //验证数据 CommentModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据录入
            $log["audit"] = 0;
            $log["browse_count"] = 0;
            $log["is_admin"] = false;
            $log["is_leaf"] = false;
            $log["content"] = I("post.content");
            $log["title"] = I("post.title");
            $log["ip"] = get_client_ip();
            $log["publish_time"] = date("Y-m-d H:i:s");
            $log["member_id"] = session("c_id");

            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，留言成功，请等待审核！",__MODULE__."/Article/message");

        }
        else{
            $this->display();
        }
    }

}