<?php
namespace House\Controller;
use Think\Controller;
class CateController extends CommonController {

//**********网站分类部分************
    //网站分类管理
    function modelList(){
        $user = D("model");
        $info = $user ->order("is_show desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }
    //添加网站分类
    function modelAdd(){

        if (!empty($_POST)){
            //实例化
            $model = new \Model\ModelModel();
            //验证数据 CateModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //判定文章属性
            if (I("post.article")=="1"){
                $article = true;
            }else{
                $article = false;
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["name"] = I("post.name");
            $log["article"] = $article;
            $log["is_show"] = 1;
            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cate/modelList");
        }else{
            $this -> display();
        }
    }

    //修改网站分类
    function modelEdit(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\ModelModel();
            //验证数据 CateModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //判断ID是否存在
            $myModel = M("model"); // 实例化User对象
            $pk =  $myModel->where('pk='.I('get.pa'))->getField('pk');

            if($pk > 0){

                //判定文章属性
                if (I("post.article")=="1"){
                    $article = true;
                }else{
                    $article = false;
                }

                //数据修改
                $data = array('name'=>I("post.name"),'article'=>$article,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
                $model-> where('pk='.I('get.pa'))->setField($data);

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cate/modelList");
            }else{
                //ID不存在
                $this->error("您的数据有错误!");
                exit();
            }
        }else{
            $model = M("model");
            $info = $model-> where("pk=".I('get.pa')) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }
    //查找model name是否重名
    function checkModel(){
        $model = M("model"); // 实例化User对象
        $info = $model->where('name="'.I("post.name").'"')->find();
        if (!empty($info)){
            if ($info["pk"] !== I("get.pa")){
                $data['valid'] = false;
            }else{
                $data['valid'] = true;
            }
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }
    //分类模块作废与启用
    function locked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，模块作废成功！";
            $sa = false;
        }else{
            $str = "恭喜您，模块启用成功！";
            $sa = true;
        }
        $model = M("model");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Cate/modelList");
    }

//**********视频分类部分************
    //视频分类管理
    function videoList(){
        $user = D("video_category");
        $info = $user ->order("is_show desc,custom_sort desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }
    //视频分类作废与启用
    function videoLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，分类作废成功！";
            $sa = false;
        }else{
            $str = "恭喜您，分类启用成功！";
            $sa = true;
        }
        $model = M("video_category");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Cate/videoList");
    }
    //video name是否重名
    function checkVideo(){

        $model = M("video_category"); // 实例化User对象
        $info = $model->where('name="'.I("post.name").'"')->find();
        if (!empty($info)){
            if ($info["pk"] !== I("get.pa")){
                $data['valid'] = false;
            }else{
                $data['valid'] = true;
            }
        }else{
            $data['valid'] = true;
        }

        $this->ajaxReturn($data);
    }
    //添加视频分类
    function videoAdd(){

        if (!empty($_POST)){
            //实例化
            $model = new \Model\Video_categoryModel();
            //验证数据 Video_categoryModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["name"] = I("post.name");
            $log["custom_sort"] = I("post.custom_sort");
            $log["description"] = I("post.description");
            $log["price"] = I("post.price");

            $log["is_show"] = 1;
            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cate/videoList");
        }else{
            $this -> display();
        }
    }

    //修改视频分类
    function videoEdit(){
        if (!empty($_POST)){
            $model = new \Model\Video_categoryModel();
            //验证数据 Video_categoryModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //判断ID是否存在
            $myModel = M("video_category"); // 实例化对象
            $pk =  $myModel->where('pk='.I('get.pa'))->getField('pk');

            if($pk > 0){
                //数据修改
                $data = array('name'=>I("post.name"),'price'=>I("post.price"),'custom_sort'=>I("post.custom_sort"),'description'=>I("post.description"),'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
                $model-> where('pk='.I('get.pa'))->setField($data);

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cate/videoList");
            }else{
                //ID不存在
                $this->error("您的数据有错误!");
                exit();
            }
        }else{
            $model = M("video_category");
            $info = $model-> where("pk=".I('get.pa')) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }

    //查看视频分类
    function videoShow(){
        $model = M("video_category");
        $info = $model-> where("pk=".I('get.pa')) -> select();
        $this -> assign("info",$info);
        $this -> display();
    }


//**********文章分类部分************
    //文章分类管理
    function articleList(){
        $user = D("category");
        $info = $user ->order("is_show desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }
    //文章分类作废与启用
    function articleLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，分类作废成功！";
            $sa = false;
        }else{
            $str = "恭喜您，分类启用成功！";
            $sa = true;
        }
        $model = M("category");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Cate/articleList");
    }
    //article name是否重名
    function checkArticle(){
        $model = M("category"); // 实例化User对象
        $info = $model->where('name="'.I("post.name").'"')->find();
        if (!empty($info)){
            if ($info["pk"] !== I("get.pa")){
                $data['valid'] = false;
            }else{
                $data['valid'] = true;
            }
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }
    //添加文章分类
    function articleAdd(){

        if (!empty($_POST)){
            //实例化
            $model = new \Model\CategoryModel();
            //验证数据 categoryModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["name"] = I("post.name");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["is_show"] = 1;
            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cate/articleList");
        }else{
            $this -> display();
        }
    }

    //修改文章分类
    function articleEdit(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\CategoryModel();
            //验证数据 CategoryModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //判断ID是否存在
            $myModel = M("category"); // 实例化对象
            $pk =  $myModel->where('pk='.I('get.pa'))->getField('pk');

            if($pk > 0){
                //数据修改
                $data = array('name'=>I("post.name"),'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
                $model-> where('pk='.I('get.pa'))->setField($data);

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cate/articleList");
            }else{
                //ID不存在
                $this->error("您的数据有错误!");
                exit();
            }
        }else{
            $model = M("category");
            $info = $model-> where("pk=".I('get.pa')) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }

//**********研修生档案分类部分************
    //档案分类管理
    function archivesList(){
        $user = D("archives_cate");
        $info = $user ->order("is_show desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //档案分类作废与启用
    function archivesLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，分类作废成功！";
            $sa = false;
        }else{
            $str = "恭喜您，分类启用成功！";
            $sa = true;
        }
        $model = M("archives_cate");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Cate/archivesList");
    }

    //archives name是否重名
    function checkArchives(){
        $model = M("archives_cate"); // 实例化User对象
        $info = $model->where('name="'.I("post.name").'"')->find();
        if (!empty($info)){
            if ($info["pk"] !== I("get.pa")){
                $data['valid'] = false;
            }else{
                $data['valid'] = true;
            }
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }

    //添加档案分类
    function archivesAdd(){

        if (!empty($_POST)){
            //实例化
            $model = new \Model\Archives_cateModel();
            //验证数据 Archives_cateModel
            $z = $model -> create();
            if (!$z){
                show_bug($model -> getError());
//                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["name"] = I("post.name");
            $log["update_time"] = date("Y-m-d H:i:s");
            $log["regenerator"] = session("a_username");
            $log["is_show"] = true;
            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Cate/archivesList");
        }else{
            $this -> display();
        }
    }

    //修改档案分类
    function archivesEdit(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\Archives_cateModel();
            //验证数据 Archives_cateModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //判断ID是否存在
            $myModel = M("archives_cate"); // 实例化对象
            $pk =  $myModel->where('pk='.I('get.pa'))->getField('pk');

            if($pk > 0){
                //数据修改
                $data = array('name'=>I("post.name"),'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
                $model-> where('pk='.I('get.pa'))->setField($data);

                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Cate/archivesList");
            }else{
                //ID不存在
                $this->error("您的数据有错误!");
                exit();
            }
        }else{
            $model = M("archives_cate");
            $info = $model-> where("pk=".I('get.pa')) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }
}