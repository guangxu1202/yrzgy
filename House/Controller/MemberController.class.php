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

//**********文章评论管理************
    //文章评论列表
    function articleCommentList(){
        $user = M("comment");
        $info = $user->field("b.nickname,a.content,c.title,a.publish_time,a.ip,a.audit,a.pk,c.pk as id")->where("a.article_id<>''")->join("as a left join member as b on a.member_id=b.pk left join article as c on a.article_id = c.pk") ->order("a.audit asc,a.publish_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }

    //文章评论详情
    function articleCommentShow(){
        $user = M("comment");
        if ($user->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else {
            $info = $user->field("b.nickname,a.content,c.title,a.publish_time,a.ip,a.audit,a.pk,c.pk as id")->where("a.article_id<>'' and a.pk=".I("get.pa"))->join("as a left join member as b on a.member_id=b.pk left join article as c on a.article_id = c.pk")->order("a.audit asc,a.publish_time desc")->select();
            $this->assign("info", $info);
            $this->display();
        }
    }

    //评论状态修改
    function commentLocked(){
        if (I('get.sa')=="pass"){
            $str = "恭喜您，通过审核成功！";
            $sa = 1;
        }else if (I('get.sa')=="lock"){
            $str = "恭喜您，评论屏蔽成功！";
            $sa = 2;
        }else{
            $str = "恭喜您，解除屏蔽成功！";
            $sa = 1;
        }
        $model = M("comment");
        $data = array('audit'=> $sa);

        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Member/articleCommentList");
    }

//**********留言咨询管理************

    //列表
    function messageList(){
        $user = M("comment");
        $info = $user->field("b.nickname,a.content,a.publish_time,a.ip,a.audit,a.pk")->where("a.article_id is null and a.pid is null")->join("as a left join member as b on a.member_id=b.pk") ->order("a.audit asc,a.publish_time desc") -> select();

        foreach ($info as $key =>$u){
            $n = $user->where("audit = 0 and pid =".$u["pk"]) -> count();
            $num1[$key] = $n;
            $num2[$key] = $u['audit'];
            $info[$key]["num"] = $n;
        }

        array_multisort($num1,SORT_DESC,$num2,SORT_ASC,$info);

        $this -> assign("info",$info);
        $this -> display();
    }

    //详情
    function messageShow(){
        $user = M("comment");
        if ($user->find(I("get.pa")) == null){
            //错误ID
            $this->error("页面无法访问！");
        }else {
            if (!empty($_POST)){

                //实例化
                $model = new \Model\CommentModel();
                //验证数据 CommentModel
                $z = $model -> create();
                if (!$z){
                    //show_bug($model -> getError());
                    $this->error("您录入的数据格式错误！");
                    exit();
                }


                //数据录入
                $log["audit"] = 1;
                $log["browse_count"] = 0;
                $log["is_admin"] = true;
                $log["is_leaf"] = true;
                $log["pid"] = I("post.pa");
                $log["content"] = I("post.content");
                $log["ip"] = get_client_ip();
                $log["publish_time"] = date("Y-m-d H:i:s");
                $log["member_id"] = 1;

                $model->data($log)->add();

                //录入成功
                $this->success("恭喜您，留言回复成功!");
            }else{
                //读取内容
                $info = $user->field("b.nickname,a.content,a.publish_time,a.ip,a.audit,a.pk,a.browse_count,a.title")->where("a.article_id is null and a.pk=".I("get.pa"))->join("as a left join member as b on a.member_id=b.pk")->order("a.audit asc,a.publish_time desc")->select();
                $this->assign("info", $info);

                //已有评论循环
                $comment = $user->join("as a left join member as b on a.member_id = b.pk") ->field("a.publish_time,a.content,b.nickname,a.pk,a.audit,a.is_admin") ->where("a.pid =".I("get.pa"))->order("a.publish_time desc") -> select();
                $this->assign("comment", $comment);

                $this->display();
            }

        }
    }
    //留言状态修改
    function messageLocked(){
        if (I('get.sa')=="pass"){
            $str = "恭喜您，通过审核成功！";
            $sa = 1;
        }else if (I('get.sa')=="lock"){
            $str = "恭喜您，评论屏蔽成功！";
            $sa = 2;
        }else{
            $str = "恭喜您，解除屏蔽成功！";
            $sa = 1;
        }
        $model = M("comment");
        $data = array('audit'=> $sa);

        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Member/messageList");
    }

    //留言内页状态修改
    function msgLocked(){
        if (I('get.sa')=="pass"){
            $str = "恭喜您，通过审核成功！";
            $sa = 1;
        }else if (I('get.sa')=="lock"){
            $str = "恭喜您，评论屏蔽成功！";
            $sa = 2;
        }else{
            $str = "恭喜您，解除屏蔽成功！";
            $sa = 1;
        }
        $model = M("comment");
        $data = array('audit'=> $sa);

        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Member/messageShow/pa/".I('get.u'));
    }
}