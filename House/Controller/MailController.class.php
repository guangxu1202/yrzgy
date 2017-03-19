<?php
namespace House\Controller;
use Think\Controller;
class MailController extends CommonController{


//**********SMTP设置************
    //列表
    function smtpList(){
        $user = M("smtp");
        $info = $user ->order("is_used desc,custom_sort desc,update_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //过时与恢复
    function smtpLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，记录过时成功！";
            $sa = false;
        }else{
            $str = "恭喜您，记录恢复成功！";
            $sa = true;
        }
        $model = M("smtp");
        $data = array('is_used'=> $sa,'regenerator'=>session("a_username"),'update_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Mail/smtpList");
    }

    //新增
    function smtpAdd(){
        if (!empty($_POST)){
            //实例化
            $model = new \Model\SmtpModel();
            //验证数据 SmtpModel
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
            $log["is_used"] = checkBit(I('post.is_used'));
            $log["email"] = I("post.email");
            $log["host_name"] = I("post.host_name");
            $log["password"] = I("post.password");
            $log["sender"] = I("post.sender");
            $log["custom_sort"] = I("post.custom_sort");

            $model->data($log)->add();

            //录入成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Mail/smtpList");

        }else{
            $this -> display();
        }
    }

    //修改
    function smtpEdit(){
        if (!empty($_POST)){
            $model = new \Model\SmtpModel();
            //验证数据 SmtpModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            if ($model->find(I("post.pk")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{

                //数据修改

                $data["update_time"] = date("Y-m-d H:i:s");
                $data["regenerator"] = session("a_username");
                $data["is_used"] = checkBit(I('post.is_used'));
                $data["email"] = I("post.email");
                $data["host_name"] = I("post.host_name");
                $data["password"] = I("post.password");
                $data["sender"] = I("post.sender");
                $data["custom_sort"] = I("post.custom_sort");


                $model->  where("pk=".I('post.pk'))  ->setField($data);
                //录入成功
                $this->success("恭喜您，操作成功！",__MODULE__."/Mail/smtpList");
            }


        }else{
            $a = M("smtp");
            if ($a->find(I("get.pa")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                //修改界面展示
                $info = $a -> where("pk=".I("get.pa")) -> select();
                $this -> assign("info",$info);
                $this -> display();
            }
        }
    }

//**********已发邮件列表************
    //列表
    function historyList(){
        $user = M("sent_mail");
        $info = $user ->field("m.nickname,m.email,p.email as send_mail,sent_mail.title,p.sender,u.real_name,sent_mail.sent_time,sent_mail.pk") ->join("LEFT JOIN member AS m ON sent_mail.member_id = m.pk LEFT JOIN smtp as p ON sent_mail.smtp_id = p.pk LEFT JOIN `user` AS u ON sent_mail.user_id = u.pk") ->order("sent_mail.sent_time desc")->limit(500) -> select();
        //show_bug($info);
        $this -> assign("info",$info);
        $this -> display();
    }

    //列表
    function historyShow(){
        $user = M("sent_mail");

        if ($user->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else{
            //界面展示
            $info = $user ->field("m.nickname,m.username,m.email,p.email as send_mail,sent_mail.title,sent_mail.content,p.sender,u.real_name,sent_mail.sent_time,sent_mail.pk") ->join("LEFT JOIN member AS m ON sent_mail.member_id = m.pk LEFT JOIN smtp as p ON sent_mail.smtp_id = p.pk LEFT JOIN `user` AS u ON sent_mail.user_id = u.pk") ->order("sent_mail.sent_time desc") -> where("sent_mail.pk=".I("get.pa")) -> select();
            //show_bug($info);
            $this -> assign("info",$info);
            $this -> display();
        }

    }

}