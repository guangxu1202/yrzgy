<?php
namespace House\Controller;
use Think\Controller;
class MemberController extends CommonController{

//**********会员基本信息************
    //会员列表
    function baseList(){
        $user = M("member");
        $info = $user ->order("last_login_time desc,pk desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }
    //会员锁定与解锁
    function memberLock(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，用户锁定成功！";
            $sa = 2;
        }else{
            $str = "恭喜您，用户解锁成功！";
            $sa = 3;
        }
        $model = M("member");
        $data = array('state'=> $sa);

        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Member/baseList");
    }
    //查看会员
    function baseShow(){
        $model = M("member");
        $info = $model-> where("pk=".I('get.pa')) -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

//**********会员登录信息************
    //登录列表
    function logInfo(){
        $user = M("member_login_info");
        $info = $user ->join("member ON member_login_info.member_id = member.pk") ->limit("500") -> field("member_login_info.IP as pp,member_login_info.login_time,member.username,member.real_name") -> order("login_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

}