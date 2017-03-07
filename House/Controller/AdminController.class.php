<?php
namespace House\Controller;
use Think\Controller;
class AdminController extends CommonController {
    //系统用户列表
    function userList(){
        $user = M("user");
        $info = $user -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //登录信息记录
    function userLoginlist(){
        $user = M("user_login_info");
        $info = $user -> join("left join user on user.pk = user_login_info.user_id") ->limit("500") -> order("user_login_info.pk desc")->select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //查找username是否重名
    function checkuser(){
        $user = M("user"); // 实例化User对象
        $info = $user->where('username="'.I("post.username").'"')->find();
        if (!empty($info)){
            $data['valid'] = false;
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }

    //添加系统用户
    function userAdd(){
        //实例化
        $user = new \Model\UserModel();
        if (!empty($_POST)){
            //验证数据 UserModel
            $z = $user -> create();
            if (!$z){
                //show_bug($user -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //判定锁定状态
            if (I("post.locked") == "on"){
                $locked = false;
            }else{
                $locked = true;
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["locked"] =   $locked;
            $log["password"] = sha1(I("post.password"));
            $log["real_name"] = I("post.real_name");
            $log["username"] = I("post.username");
            $user->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Admin/userList");
        }else{
            $this -> display();
        }
    }

    //用户锁定或解锁
    function locked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，帐号锁定成功！";
            $sa = true;
        }else{
            $str = "恭喜您，帐号解锁成功！";
            $sa = false;
        }
        $user = M("user");
        $data = array('locked'=> $sa);
        $user-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Admin/userList");
    }

    //修改我的信息
    function myself(){
        //实例化
        $user = new \Model\UserModel();
        if (!empty($_POST)){
            //验证数据 UserModel
            $z = $user -> create();
            if (!$z){
                //show_bug($user -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //判断初始密码是否正确
            $myUser = M("user"); // 实例化User对象
            $cc = $myUser -> find(I("post.id"));
            if ($cc == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //继续修改流程

                if(sha1(I("post.opassword"))==$cc["password"]){
                    //数据修改
                    $data = array('password'=>sha1(I("post.password")),'real_name'=>I("post.real_name"));
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
            $user = M("user");
            $info = $user-> where("pk=".session("a_id")) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }

}