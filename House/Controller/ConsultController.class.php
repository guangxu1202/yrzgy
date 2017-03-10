<?php
namespace House\Controller;
use Think\Controller;
class ConsultController extends CommonController {

//*****************视频咨询管理********************
    //咨询师列表
    function teacherList(){
        $user = M("teacher");
        $info = $user->order("is_show desc,custom_sort desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //查找username是否重名
    function checkuser(){
        $user = M("teacher"); // 实例化User对象
        $info = $user->where('username="'.I("post.username").'"')->find();
        if (!empty($info)){
            $data['valid'] = false;
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }

    //新增
    function teacherAdd(){
        //实例化
        $user = new \Model\TeacherModel();
        if (!empty($_POST)){
            //验证数据 TeacherModel
            $z = $user -> create();
            if (!$z){
                //show_bug($user -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据录入
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["creator"] = session("a_username");
            $log["password"] = sha1(I("post.password"));
            $log["is_show"] = checkBit(I('post.is_show'));
            $log["real_name"] = I("post.real_name");
            $log["username"] = I("post.username");
            $log["summary"] = I("post.summary");
            $log["custom_sort"] = I("post.custom_sort");
            $log["phone"] = I("post.phone");
            $log["qq"] = I("post.qq");
            $log["address"] = I("post.address");
            $user->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Consult/teacherList");
        }else{
            $this -> display();
        }
    }

    //修改
    function teacherEdit(){
        if (!empty($_POST)){
            //实例化
            $user = new \Model\TeacherModel();
            //验证数据 TeacherModel
            $z = $user -> create();
            if (!$z){
                //show_bug($user -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据修改
            $data["update_time"] = date("Y-m-d H:i:s");
            $data["regenerator"] = session("a_username");
            $data["is_show"] = checkBit(I('post.is_show'));
            $data["real_name"] = I("post.real_name");
            $data["username"] = I("post.username");
            $data["summary"] = I("post.summary");
            $data["custom_sort"] = I("post.custom_sort");
            $data["phone"] = I("post.phone");
            $data["qq"] = I("post.qq");
            $data["address"] = I("post.address");



            $user->  where("pk=".I('post.pk'))  ->setField($data);
            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Consult/teacherList");


        }else{
            $user = M("teacher");
            $info = $user-> where("pk=".I("get.pa")) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }

    //查看
    function teacherShow(){
        $user = M("teacher");
        if ($user ->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            $info = $user->where("pk=".I("get.pa")) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }

    }

    //锁定或解锁
    function locked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，帐号锁定成功！";
            $sa = false;
        }else{
            $str = "恭喜您，帐号解锁成功！";
            $sa = true;
        }

        $user = M("teacher");
        $data = array('is_show'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $user-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Consult/teacherList");
    }

    //重置密码
    function resetPwd(){
        $user = M("teacher");
        if ($user ->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            $data = array('password'=> sha1("ABC123"),'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
            $user-> where('pk='.I('get.pa'))->setField($data);

            $this->success("密码重置成功!");
        }
    }



//*****************视频咨询管理********************
    //视频咨询申请李彪
    function orderList(){
        $user = M("consult");
        $info = $user->order("release_statu asc,is_pay asc,examine asc,create_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //审核
    function orderEdit(){
        if (!empty($_POST)){
            //实例化
            $user = new \Model\ConsultModel();
            //验证数据 ConsultModel
            $z = $user -> create();
            if (!$z){
                //show_bug($user -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            //数据修改
            if (checkBit(I('post.examine'))){
                $data["examine"] = 1;
            }else{
                $data["examine"] = 2;
                $data["refuse"] = I("post.refuse");
            }
            $data["handle_time"] = date("Y-m-d H:i:s");
            $data["handle_name"] = session("a_username");

            $user->  where("pk=".I('post.pk'))  ->setField($data);
            //录入成功
            $this->success("恭喜您，审核成功！");


        }else{
            $user = M("consult");
            $info = $user-> where("pk=".I("get.pa")) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }

    //付款
    function orderPay(){
        if (!empty($_POST)){
            //实例化
            $user = new \Model\ConsultModel();
            //验证数据 ConsultModel
            $z = $user -> create();
            if (!$z){
                //show_bug($user -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }
            //数据修改
            if (checkBit(I('post.is_pay'))){
                $data["pay_money"] = I("post.pay_money");
            }else{
                $data["pay_money"] = null;
            }
            $data["is_pay"] = checkBit(I("post.is_pay"));
            $data["pay_time"] = date("Y-m-d H:i:s");
            $data["is_pay_name"] = session("a_username");

            $user->  where("pk=".I('post.pk'))  ->setField($data);
            //录入成功
            $this->success("恭喜您，付款确认成功！");

        }else{
            $user = M("consult");
            $info = $user-> where("pk=".I("get.pa")) -> select();
            $this -> assign("info",$info);
            $this -> display();
        }
    }

    //查看
    function orderShow(){
       
        $user = M("consult");
        $info = $user-> where("pk=".I("get.pa")) -> select();
        $this -> assign("info",$info);
        $this -> display();

    }

}