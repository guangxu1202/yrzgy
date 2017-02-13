<?php
namespace House\Controller;
use Think\Controller;
class CmsController extends CommonController
{

//**********网站简介管理************
    //查看
    function introShow(){
        $user = M("introduction");
        $info = $user->order("pk desc")->select();
        $this->assign("info", $info);
        $this->display();
    }

    //修改
    function introEdit(){
        if (!empty($_POST)){
            $model = new \Model\IntroductionModel();
            //验证数据 IntroductionModel
            $z = $model -> create();
            if (!$z){
                show_bug($model -> getError());
                //$this->error("您录入的数据格式错误！");
                exit();
            }

            //数据修改
            $data = array(
                'title'=> I('post.title'),
                'summary_title'=>I('post.summary_title'),
                'summary'=>I('post.summary'),
                'content'=>$_POST["content"],
                'regenerator'=>session("a_username"),
                'update_time'=>date("Y-m-d H:i:s")
            );
            $model->  where("pk=".I('post.pk'))  ->setField($data);

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cms/introShow");

        }else{
            $model = M("introduction");
            $info = $model-> order("pk desc") -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }
//**********文章管理************
    //列表
    function articleList(){
        $user = M("article");
        $info = $user -> join("left join category on category.pk = article.category_id") ->field("article.update_time,article.title,category.name,article.author,article.custom_sort,article.is_enable_comment,article.allow_copy,article.creator,article.create_time,article.regenerator,article.is_show,article.pk") ->order("article.is_show desc,custom_sort desc,article.update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //新增
    function articleAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\ArticleModel();
            //验证数据 ArticleModel
            $z = $model -> create();
            if (!$z){
                show_bug($model -> getError());
//                $this->error("您录入的数据格式错误！");
                //exit();
            }
            echo $_POST["title"];
            echo "<hr>";
            echo $_POST["models"];
            echo "<hr>";
            echo $_POST["select_module"];
            echo "<hr>";
            echo $_POST["test"];
            echo "<hr>";
            //数据录入
//            $log["create_time"] = date("Y-m-d H:i:s");
//            $log["creator"] = session("a_username");
//            $log["name"] = $_POST["name"];
//            $log["update_time"] = date("Y-m-d H:i:s");
//            $log["regenerator"] = session("a_username");
//            $log["is_show"] = 1;
//            $model->data($log)->add();
//
//            //录入成功
//            $this->success("恭喜您，操作成功！",__MODULE__."/Cate/articleList");
        }else{
            $m = M("model");
            $info = $m-> order("pk asc") ->where("is_show = 1") -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }
}