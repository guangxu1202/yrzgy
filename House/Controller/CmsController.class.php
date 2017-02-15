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
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
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
//                show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["allow_copy"] = I("post.allow_copy");
            $log["article_from"] = I("post.article_from");
            $log["author"] = I("post.author");
            $log["content"] = I("post.content");
            $log["custom_sort"] = I("post.custom_sort");
            $log["is_enable_comment"] = I("post.is_enable_comment");
            $log["keywords"] = I("post.keywords");
            $log["is_show"] = I("post.is_show");
            $log["summary"] = I("post.summary");
            $log["title"] = I("post.title");
            $log["is_title_bold"] = I("post.is_title_bold");
            $log["title_color"] = I("post.title_color");
            $log["category_id"] = I("post.category_id");
            $log["like_count"] = 0;
            $log["browse_count"] = 0;
            $log["unlike_count"] = 0;
            $result = $model->data($log)->add();

            if ($model){
                $id = $result; // 获取数据库写入数据的主键
                //插入关联表数据
                $cars = explode(',',I("post.smodule"));
                $arrlength=count($cars);
                for($x=0;$x<$arrlength;$x++) {
                    $article_model = M("article_model"); // 实例化article_model对象
                    $data['article_id'] = $id;
                    $data['model_id'] = $cars[$x];
                    $article_model->add($data);
                }

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cms/articleList");
            }else{
                $this->error("数据录入失败！");
                exit();
            }

        }else{
            $m = M("model");
            $n = M("category");
            $info = $m-> order("pk asc") ->where("is_show = 1") -> select();
            $info2 = $n ->order("pk asc") ->where("is_show = 1") -> select();
            $this -> assign("info",$info);
            $this -> assign("info2",$info2);
            $this -> display();
        }
    }

    //修改
    function articleEdit(){
        if (!empty($_POST)){
            $model = new \Model\ArticleModel();
            //验证数据 ArticleModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据修改
            $data = array(
                'allow_copy'=>checkBit(I('post.allow_copy')),
                'article_from'=>I('post.article_from'),
                'author'=>I('post.author'),
                'content'=>I('post.content'),
                'custom_sort'=>I('post.custom_sort'),
                'is_enable_comment'=>checkBit(I('post.is_enable_comment')),
                'keywords'=>I('post.keywords'),
                'is_show'=>checkBit(I('post.is_show')),
                'summary'=>I('post.summary'),
                'category_id'=>I('post.category_id'),
                'is_title_bold'=>checkBit(I('post.is_title_bold')),
                'title'=>I('post.title'),
                'title_color'=>I('post.title_color'),
                'regenerator'=>session("a_username"),
                'update_time'=>date("Y-m-d H:i:s")
            );
            $model->  where("pk=".I('post.pk'))  ->setField($data);

            //删除原有文章模型关联
            $article_model = M("article_model"); // 实例化article_model对象
            $article_model->where('article_id = '.I('post.pk'))->delete();

            //创建新文章模型关联
            $cars = explode(',',I("post.smodule"));
            $arrlength=count($cars);
            for($x=0;$x<$arrlength;$x++) {
                $data1['article_id'] = I('post.pk');
                $data1['model_id'] = $cars[$x];
                $article_model->add($data1);
            }

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cms/articleList");

        }else{
            $a = M("model");
            $arrid = $a -> join("left join article_model ON model.pk = article_model.model_id") -> field("model.pk") -> where("article_model.article_id =".I('get.pa'))->select();
            $info1 = $a -> where("is_show = 1")->field("pk,name") -> select();

            //关联数组数据整理
            $arr = array();
            for ($c=0;$c<count($arrid);$c++){
                array_push($arr,$arrid[$c]['pk']);
            }

            //数组转换为字符串
            $nb = implode(",", $arr);

            //追加关联数组数据
            for($x=0;$x<count($info1);$x++) {
                if (in_array($info1[$x]['pk'],$arr)){
                    $info1[$x]['check'] = "1";
                }else{
                    $info1[$x]['check'] = "0";
                }
            }

            $m = M("category");
            $info2 = $m-> order("pk asc") ->where("is_show = 1") -> field("pk,name") -> select();
            $model = M("article");
            $info = $model-> where("pk=".I('get.pa')) -> select();
            $cate = $model ->where("pk=".I('get.pa')) -> field("category_id") ->select();

            //追加类别id显示
            for($r=0;$r<count($info2);$r++) {
                if ($info2[$r]['pk'] == $cate[0]['category_id']){
                    $info2[$r]['check'] = "1";
                }else{
                    $info2[$r]['check'] = "0";
                }
            }

            $this -> assign("info",$info);
            $this -> assign("info1",$info1);
            $this -> assign("info2",$info2);
            $this->assign('arr',$nb);
            $this -> display();
        }
    }
}