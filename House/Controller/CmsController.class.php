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
}