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


//**********订单管理************
    //列表
    function orderList(){
        $user = M("video_order");
        $info = $user->field("a.pk,a.order_number,c.nickname,b.name,a.price,a.request_time,a.comment,a.confirm_time,a.status")->join("as a LEFT JOIN video_category as b on a.video_category_id = b.pk LEFT JOIN member as c ON a.member_id = c.pk") ->order("a.status asc,a.request_time desc") -> select();
        $this -> assign("info",$info);
        $this -> display();
    }


    //作废与恢复
    function orderLocked(){
        if (I('get.sa')=="lock"){
            $str = "恭喜您，订单作废成功！";
            $sa = 2;
        }else{
            $str = "恭喜您，订单恢复成功！";
            $sa = 0;
        }
        $model = M("video_order");
        $data = array('status'=> $sa,'user_id'=>session("a_id"),'confirm_time'=>date("Y-m-d H:i:s"));
        $model-> where('pk='.I('get.pa'))->setField($data);

        //操作成功
        $this->success($str,__MODULE__."/Video/orderList");
    }

    //审核
    function orderEdit(){
        $video_order = M("video_order");
        if ($video_order->find(I('get.pa'))==null){
            $this ->error("您访问的页面错误！");
            exit();
        }
        
        if (!empty($_POST)){


            //数据录入
            $model = new \Model\Video_limitModel();
            //验证数据 Video_limitModel
            $z = $model -> create();
            if (!$z){
                //show_bug($model -> getError());
                $this->error("您录入的数据格式错误！");
                exit();
            }

            $video = M("video")->field("pk")->where("is_show = 1 and video_category_id = ".I("post.video_category_id"))->select();

            //清空原有限制记录
            $model->where("member_id=".I("post.member_id")." and video_category_id = ".I("post.video_category_id"))->delete();

            //新增限制记录
            foreach ($video as $k =>$m){
                $log["video_id"] = $m['pk'];
                $log["limit_count"] = I("post.count");
                $log["member_id"] = I("post.member_id");
                $log["video_category_id"] = I("post.video_category_id");
                $log["current_count"] = 0;
                $model ->data($log)->add();
            }

            //修改订单审核状态
            $mys = array('status'=>1,'confirm_time'=>date("Y-m-d H:i:s"));
            $video_order->where('pk='.I("post.pk"))->setField($mys);


            //邮件内容配置
            $order = $video_order->field("c.email,c.nickname,b.name,a.order_number,a.request_time,a.price,a.comment,a.confirm_time")->join("as a left join video_category as b on a.video_category_id = b.pk left join member as c on a.member_id = c.pk")->where("a.member_id = ".I("member_id")." and a.pk=".I('get.pa'))->find();
            $mail_title = $order['nickname']."您的订单[".$order['order_number']."]已审核通过";
            $mail_content = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>元认知干预课程开通成功</title></head><body><style>.Order {padding: 10px; background-color: #e2ffc6; border: 1px solid #a1ff45; margin-bottom: 10px;} .Order h6 {font-size: 14px; font-weight: bold; border-bottom: 1px solid #ccc; height: 24px; line-height: 24px; padding-left: 15px; margin-bottom: 10px;} .Order table {margin-bottom: 10px; width: 100%; font-size: 12px;} .Order table th {text-align: right;} .Order table th,.Order table td {padding: 5px;} .Order table tr.Impro th,.Order table tr.Impro td {font-weight: bold; color: #cc0000;} </style>	<h2 style="font-size: 14px; font-weight: bold; margin: 10px; margin-top: 60px;">亲爱的'.$order['nickname'].', 您好: </h2>	<div class="main" style="margin: 10px;">		<div class="Order">			<h6>我们很高兴通知您如下: 您在<a href="'.SITE_URL.'" target="_blank">元认知心理干预技术网</a>订购的课程已经被管理员开通, 现在就<a href="'.SITE_URL.'/Home/Index/login" target="_blank">登录</a>网站观看.</h6>			<h6>您的订单信息如下</h6>			<table>				<colgroup width="150px"></colgroup>				<tr><th>订单编号: </th><td>'.$order['order_number'].'</td></tr>				<tr><th>订购时间: </th><td>'.$order['request_time'].'</td></tr>				<tr><th>确认时间: </th><td>'.$order['confirm_time'].'</td></tr>				<tr><th>订购课程: </th><td>'.$order['name'].'</td></tr>				<tr class="Impro"><th>订单总计: </th><td>'.$order['price'].' 元</td></tr>				<tr><th>订单备注: </th><td>'.$order['comment'].'</td></tr>			</table>			<h6>联络及咨询方式</h6>			<table>				<colgroup width="150px"></colgroup>				<tr><th>联系人: </th><td>魏晓旭</td></tr>				<tr><th>联系电话: </th><td>15998660373</td></tr>			</table>		</div>	</div></body></html>';

            //邮件发送代码
            $smtp = M("smtp")->field("email,host_name,password,sender,pk")->where("is_used = 1")->order("custom_sort desc")->select();
            foreach ($smtp as $m =>$k){
                if (sendMail($order['email'],$mail_title,$mail_content,$k['host_name'],$k['email'],$k['password'],$k['email'],$k['sender'])){
                    //记录邮件发送
                    $sent_mail = M("sent_mail");
                    $save['content'] = $mail_content;
                    $save['sent_time'] = date("Y-m-d H:i:s");
                    $save['title'] = $mail_title;
                    $save['member_id'] = I("member_id");
                    $save['smtp_id'] = $k['pk'];
                    $sent_mail->add($save);
                    break;
                }
            }



            //成功
            $this->success("恭喜您，操作成功！",__MODULE__."/Video/orderList");

        }else{

            $info = $video_order->field("a.pk,a.order_number,c.username,c.nickname,c.mobile,b.name,a.price,a.request_time,a.comment,a.member_id,a.video_category_id")->join("as a LEFT JOIN video_category as b on a.video_category_id = b.pk LEFT JOIN member as c ON a.member_id = c.pk") ->where("a.pk = ".I("get.pa")) -> find();
            $this -> assign("info",$info);
            $this -> display();
        }
    }


    //查看
    function orderShow(){
        $video_order = M("video_order");
        if ($video_order->find(I('get.pa'))==null){
            $this ->error("您访问的页面错误！");
            exit();
        }
        $info = $video_order->field("a.pk,a.order_number,c.username,c.nickname,c.mobile,b.name,a.price,a.request_time,a.comment,a.member_id,a.video_category_id,a.status")->join("as a LEFT JOIN video_category as b on a.video_category_id = b.pk LEFT JOIN member as c ON a.member_id = c.pk") ->where("a.pk = ".I("get.pa")) -> find();
        $this -> assign("info",$info);
        $this -> display();

    }


//**********视频分配信息************
    //列表
    function infoList(){
        $user = M("video_limit");
        $info = $user->field("b.username,b.nickname,c.title,a.limit_count,a.current_count,a.member_id,a.video_id")->join("as a LEFT JOIN member as b on a.member_id = b.pk LEFT JOIN video as c on a.video_id = c.pk ")->order("a.member_id desc")-> select();
        $this -> assign("info",$info);
        $this -> display();
    }
    //修改
    function infoEdit(){
        $video_limit = M("video_limit");
        if ($video_limit->where("video_id=".I("get.v")." and member_id=".I("get.m"))->find()==null){
            $this ->error("您访问的页面错误！");
            exit();
        }

        if (!empty($_POST)){

            //数据录入
            $model = new \Model\Video_limitModel();
            //验证数据 Video_limitModel
            $z = $model -> create();
            if (!$z){
                show_bug($model -> getError());
                //$this->error("您录入的数据格式错误！");
                exit();
            }
            $model->where("video_id=".I("get.v")." and member_id=".I("get.m"))->setField('limit_count',I("post.count"));

            //成功
            $this->success("恭喜您，操作成功！");

        }else{

            $info = $video_limit->field("b.username,b.nickname,b.mobile,c.name,d.title,a.current_count,a.limit_count,a.video_category_id,a.video_id,a.member_id")->join(" as a LEFT JOIN member as b on a.member_id = b.pk LEFT JOIN video_category as c on a.video_category_id = c.pk LEFT JOIN video as d ON a.video_id = d.pk") ->where("a.video_id=".I("get.v")." and a.member_id=".I("get.m")) -> find();
            $this -> assign("info",$info);
            $this -> display();
        }
    }

}