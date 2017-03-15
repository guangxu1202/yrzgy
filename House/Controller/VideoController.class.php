<?php
namespace House\Controller;
use Think\Controller;
class VideoController extends CommonController{

//**********视频管理************
    //列表
    function videoList(){
        $user = M("video");
        $info = $user -> join("as a left join video_category as b on a.video_category_id = b.pk") ->field("a.pk,a.create_time,a.update_time,a.title,a.is_show,b.name,a.author,a.custom_sort") ->order("a.is_show desc,a.custom_sort desc,a.update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //过时与恢复
    function videoLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，记录过时成功！";
            $sa = false;
        }else{
            $str = "恭喜您，记录恢复成功！";
            $sa = true;
        }
        $model = M("video");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Video/videoList");
    }


    //新增
    function videoAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\VideoModel();
            //验证数据 VideoModel
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
            $log["author"] = I("post.author");
            $log["browse_count"] = 0;
            $log["content"] = I("post.content");
            $log["custom_sort"] = I("post.custom_sort");
            $log["is_enable_comment"] = true;
            $log["is_free"] = checkBit(I('post.is_free'));
            $log["video_path"] = I("post.video_path");
            $log["keywords"] = I("post.keywords");
            $log["like_count"] = 0;
            $log["unlike_count"] = 0;
            $log["is_show"] = checkBit(I('post.is_show'));
            $log["summary"] = I("post.summary");
            $log["title"] = I("post.title");
            $log["is_title_bold"] = checkBit(I('post.is_title_bold'));
            $log["title_color"] = I("post.title_color");
            $log["video_category_id"] = I("post.video_category_id");
            $model->data($log)->add();

            $this->success("恭喜您，操作成功！",__MODULE__."/Video/videoList");

        }else{

            $n = M("video_category");
            $info2 = $n ->order("pk asc") ->where("is_show = 1") -> select();
            $this -> assign("info2",$info2);
            $this -> display();
        }
    }


    //修改
    function videoEdit(){
        if (!empty($_POST)){
            $model = new \Model\VideoModel();
            //验证数据 VideoModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据修改
            $data = array(
                'author'=>I('post.author'),
                'content'=>I('post.content'),
                'custom_sort'=>I('post.custom_sort'),
                'is_free'=>checkBit(I('post.is_free')),
                'keywords'=>I('post.keywords'),
                'is_show'=>checkBit(I('post.is_show')),
                'summary'=>I('post.summary'),
                'title'=>I('post.title'),
                'is_title_bold'=>checkBit(I('post.is_title_bold')),
                'title_color'=>I('post.title_color'),
                'video_category_id'=>I('post.video_category_id'),
                'video_path'=>I('post.video_path'),
                'regenerator'=>session("a_username"),
                'update_time'=>date("Y-m-d H:i:s")
            );
            $model->  where("pk=".I('post.pk'))  ->setField($data);


            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Video/videoList");

        }else{

            $info2 = M("video_category") ->order("pk asc") ->where("is_show = 1") -> select();
            $this -> assign("info2",$info2);

            $info = M("video") -> where("pk=".I('get.pa')) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }


    //查看
    function videoShow(){
        $user = M("video");
        $info = $user-> where('pk='.I('get.pa'))->select();

        $m = M("video_category");
        $info1 = $m ->where("pk = ".$info[0]["video_category_id"]) -> field("name") -> select();


        $this->assign("info", $info);
        $this->assign("category_name",$info1[0]["name"]);
        $this->display();
    }


}