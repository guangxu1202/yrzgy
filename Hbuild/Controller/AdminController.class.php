<?php
namespace Hbuild\Controller;
use Think\Controller;
class AdminController extends CommonController {

    //修改密码
    function myself(){

        if (!empty($_POST)){
            //实例化
            $user = new \Model\TeacherModel();
            //验证数据 UserModel
            $z = $user -> create();
            if (!$z){
                //show_bug($user -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //判断初始密码是否正确
            $myUser = M("teacher"); // 实例化User对象
            $cc = $myUser -> find(I("post.id"));
            if ($cc == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //继续修改流程

                if(sha1(I("post.opassword"))==$cc["password"]){
                    //数据修改
                    $data = array('password'=>sha1(I("post.password")));
                    $myUser-> where('pk='.I("post.id"))->setField($data);

                    //录入成功
                    $this->success("恭喜您，操作成功！",__MODULE__."/Admin/myself");
                }else{
                    //初始密码匹配失败
                    $this->error("您输入的原始密码错误!");
                    exit();
                }
            }

        }else{
            $user = M("teacher");
            $info = $user-> where("pk=".session("b_id")) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }

    }
}