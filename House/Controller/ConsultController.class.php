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



//*****************预约视频咨询管理********************
    //视频咨询申请列表
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


//*****************视频咨询分配管理********************
    //视频咨询申请列表
    function allotList(){
        $user = M("consult");
        $item = M("consult_item");
        $info = $user->where("examine = 1 and is_pay = 1")-> order("release_statu asc,is_pay asc,examine asc,create_time desc") -> select();
        foreach ($info as $k =>$c){
            //show_bug($c);
            $tables[$k]['order_number'] = $c["order_number"];
            $tables[$k]['lx_name'] = $c["lx_name"];
            $tables[$k]['lx_phone'] = $c["lx_phone"];
            $tables[$k]['title'] = $c["title"];
            $tables[$k]['create_time'] = $c["create_time"];
            $tables[$k]['release_statu'] = $c["release_statu"];
            $tables[$k]['pk'] = $c["pk"];

            $count_times = $item->field("count_times")->where("order_id=".$c["pk"]) ->find();
            if ($count_times['count_times'] !=null){
                $tables[$k]['times'] = $item->where("status=1 and order_id=".$c["pk"]) ->count() .'/'.$count_times['count_times'];
            }else{
                $tables[$k]['times'] = null;
            }

        }

        $this -> assign("info",$tables);
        $this -> display();
    }

    //分配
    function allotEdit(){
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

            if ($user->find(I("post.pk")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                $i=0;
                foreach (I("post.datetime") as $k  => $datetime){
                    if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $datetime, $parts))
                    {
                        //检测是否为日期,checkdate为月日年
                        if(checkdate($parts[2],$parts[3],$parts[1])){
                            $temp[$i]["start_time"] = $datetime.' '.I("start_time")[$k];
                            $temp[$i]["end_time"] = $datetime.' '.I("end_time")[$k];
                            $i++;
                        }
                    }
                }
                $log["release_statu"] = true;
                $log["release_name"] = session("a_username");
                $user->  where("pk=".I('post.pk'))  ->setField($log);

                //咨询子记录
                $table_model = M("consult_item"); // 实例化picture_story_item对象
                foreach ($temp as $k => $w){
                    $data["order_id"] = I("post.pk");
                    $data["teacher_id"] = I("post.teacher_id");
                    $data["member_id"] = I("post.member_id");
                    $data["time_number"] = I("post.time_number");
                    $data["start_time"] = $w["start_time"];
                    $data["end_time"] = $w["end_time"];
                    $data["times"] = $k+1;
                    $data["count_times"] =$i;
                    $data["status"] =false;
                    $table_model->add($data);
                }
            }
            //录入成功
            $this->success("恭喜您，分配成功！",__MODULE__."/Consult/allotList");

        }else{
            $user = M("consult");
            $info = $user-> where("pk=".I("get.pa")) -> select();
            $this -> assign("info",$info);

            $info1 = M("teacher")->field("real_name,pk")->where("is_show = 1")->select();
            $this -> assign("info1",$info1);
            $this -> display();
        }
    }

    //修改分配
    function allotModi(){
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

            if ($user->find(I("post.pk")) == null){
                //错误ID
                $this->error("页面无法访问！");
            }else{
                $i=0;
                foreach (I("post.datetime") as $k  => $datetime){
                    if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $datetime, $parts))
                    {
                        //检测是否为日期,checkdate为月日年
                        if(checkdate($parts[2],$parts[3],$parts[1])){
                            $temp[$i]["start_time"] = $datetime.' '.I("start_time")[$k];
                            $temp[$i]["end_time"] = $datetime.' '.I("end_time")[$k];
                            $temp[$i]["teacher_id"] =I("teacher_id")[$k];
                            $i++;
                        }
                    }
                }

                $log["release_name"] = session("a_username");
                $user->  where("pk=".I('post.pk'))  ->setField($log);


                //删除原有关联ID记录，已完成咨询的(status=1)除外
                $table_model = M("consult_item"); // 实例化picture_story_item对象
                $table_model->where('status=0 and order_id = '.I('post.pk'))->delete();

                //已完成咨询数据获取及修改
                $compate = $table_model->field("times") ->where("status=1 and order_id=".I('post.pk')) ->select();
                $i +=count($compate);
                foreach ($compate as $s => $w){
                    $c[$s] = $w['times'];
                }
                $count["count_times"] = $i;
                $table_model->where('status=1 and order_id = '.I('post.pk'))->setField($count);


                //子记录
                $u = 1;
                foreach ($temp as $k => $w){
                    if (in_array((string)($k+$u),$c, TRUE)){
                        $u++;
                    }
                    $data["order_id"] = I("post.pk");
                    $data["teacher_id"] = $w["teacher_id"];
                    $data["member_id"] = I("post.member_id");
                    $data["time_number"] = I("post.time_number");
                    $data["start_time"] = $w["start_time"];
                    $data["end_time"] = $w["end_time"];
                    $data["times"] = $k+$u;
                    $data["count_times"] =$i;
                    $data["status"] =false;
                    $table_model->add($data);
                }

            }
            //录入成功
            $this->success("恭喜您，分配修改成功！",__MODULE__."/Consult/allotList");

        }else{
            $user = M("consult");
            $info = $user-> where("pk=".I("get.pa")) -> select();
            $this -> assign("info",$info);

            $info1 = M("teacher")->field("real_name,pk")->where("is_show = 1")->select();
            $this -> assign("info1",$info1);

            $info2 = M("consult_item")->order("times asc")->where("order_id=".I("get.pa"))->select();
            $this -> assign("info2",$info2);
            $this -> display();
        }
    }

    //查看
    function allotShow(){

        $user = M("consult");
        $info = $user-> where("pk=".I("get.pa")) -> select();
        $this -> assign("info",$info);

        $info1 = M("teacher")->field("real_name,pk")->where("is_show = 1")->select();
        $this -> assign("info1",$info1);

        $info2 = M("consult_item")->order("times asc")->where("order_id=".I("get.pa"))->select();
        $this -> assign("info2",$info2);
        $this -> display();

    }

//*****************视频咨询评价管理********************
    //评价列表
    function evalList(){

        $item = M("consult_item");
        $info = $item->field("a.pk,a.time_number,b.lx_name,b.title,a.start_time,a.end_time,a.teacher_eval,a.member_eval,a.times,a.count_times")->join("as a left join consult as b on a.order_id = b.pk") -> where("a.status = 1") ->order("start_time desc") -> select();

        $this -> assign("info",$info);
        $this -> display();
    }

    //查看评价详情
    function evalShow(){

        $user = M("consult_item");
        $info = $user-> where("pk=".I("get.pa")) -> select();
        $this -> assign("info",$info);
        $this -> display();

    }


}