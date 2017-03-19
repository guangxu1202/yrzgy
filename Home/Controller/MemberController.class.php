<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends CommonController {
    //基础资料
    function main(){
        //非登录用户判断
        if (session("?c_id")){
                //获取个人信息
                $member = M("member");
                $info = $member->where("pk=".session("c_id")) -> find();
                $this -> assign("info",$info);
                $this -> display();
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }
    }

    //基础资料修改
    function baseEdit(){
        //非登录用户判断
        if (session("?c_id")){
            if (!empty($_POST)) {

                $user = new \Model\MemberModel();
                //验证数据 MemberModel
                $z = $user->create();
                if (!$z) {
                    show_bug($user->getError());
                    //$this->error("您录入的数据格式错误！");
                    exit();
                }

                //数据录入
                $log["nickname"] = I("post.nickname");
                $log["real_name"] = I("post.real_name");
                $log["gender"] = I("post.gender");
                $log["mobile"] = I("post.mobile");
                $log["telephone"] = I("post.telephone");
                $log["email"] = I("post.email");
                $user->  where("pk=".I('post.pk'))  ->setField($log);
                //修改成功
                $this->success("恭喜您，资料修改成功！",__MODULE__."/Member/main");
            }else{
                //获取个人信息
                $member = M("member");
                $info = $member->where("pk=".session("c_id")) -> find();
                $this -> assign("info",$info);
                $this -> display();
            }
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }

    }

    //详细资料
    function detail(){
        //非登录用户判断
        if (session("?c_id")){
            //获取个人信息
            $member = M("member");
            $info = $member->where("pk=".session("c_id")) -> find();
            $this -> assign("info",$info);
            $this -> display();
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }
    }

    //详细资料修改
    function detailEdit(){
        //非登录用户判断
        if (session("?c_id")){
            if (!empty($_POST)) {
                $user = new \Model\MemberModel();
                //验证数据 MemberModel
                $z = $user->create();
                if (!$z) {
                    show_bug($user->getError());
                    //$this->error("您录入的数据格式错误！");
                    exit();
                }

                //上传照片处理
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','bmp');// 设置附件上传类型
                $upload->rootPath  =      'CDN/uploaded/member/'; // 设置附件上传根目录
                $upload->autoSub  =      true;

                // 上传照片
                $info   =   $upload->uploadOne($_FILES['avatarFile']);
                if(!$info) {// 上传错误提示错误信息
                    //$this->error($upload->getError());
                }else{// 上传成功 获取上传文件信息
                    $log["avatar"] = $info['savepath'].$info['savename'];
                }


                // 上传身份证
                $info2   =   $upload->uploadOne($_FILES['identityCardFile']);
                if(!$info2) {// 上传错误提示错误信息
                    //$this->error($upload->getError());
                }else{// 上传成功 获取上传文件信息
                    $log["identity_card"] = $info2['savepath'].$info2['savename'];
                }

                // 上传资质证明
                $info3   =   $upload->uploadOne($_FILES['certificationFile']);
                if(!$info3) {// 上传错误提示错误信息
                    //$this->error($upload->getError());
                }else{// 上传成功 获取上传文件信息
                    $log["certification"] = $info3['savepath'].$info3['savename'];
                }

                //数据录入
                $log["address"] = I("post.address");
                if (I("post.birthday")!=''){
                    $log["birthday"] = I("post.birthday");
                }
                $log["msn"] = I("post.msn");
                $log["organization"] = I("post.organization");
                $log["qq"] = I("post.qq");
                $log["summary"] = I("post.summary");

                $user->  where("pk=".I('post.pk'))  ->setField($log);
                //修改成功
                $this->success("恭喜您，资料修改成功！",__MODULE__."/Member/detail");
            }else{
                //获取个人信息
                $member = M("member");
                $info = $member->where("pk=".session("c_id")) -> find();
                $this -> assign("info",$info);
                $this -> display();
            }
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }

    }

    //密码修改
    function pwdEdit(){
        //非登录用户判断
        if (session("?c_id")){
            if (!empty($_POST)) {
                $user = new \Model\MemberModel();
                //验证数据 MemberModel
                $z = $user->create();
                if (!$z) {
                    show_bug($user->getError());
                    //$this->error("您录入的数据格式错误！");
                    exit();
                }

                $cc = $user -> find(I("post.pk"));
                if ($cc == null){
                    //错误ID
                    $this->error("页面无法访问！");
                }else{
                    //继续修改流程

                    if(sha1(I("post.opassword"))==$cc["password"]){
                        //数据修改
                        $data = array('password'=>sha1(I("post.password")));
                        $user-> where('pk='.I("post.pk"))->setField($data);

                        //录入成功
                        $this->success("恭喜您，密码修改成功！");
                    }else{
                        //初始密码匹配失败
                        $this->error("您输入的原始密码错误!");
                        exit();
                    }
                }

            }else{
                //获取个人信息
                $member = M("member");
                $info = $member->where("pk=".session("c_id")) -> find();
                $this -> assign("info",$info);
                $this -> display();
            }
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }

    }

    //查找email是否重名
    function checkemail(){
        $user = M("member"); // 实例化User对象
        $info = $user->where('pk <> '.I("post.id").' and email="'.I("post.email").'"')->find();
        if (!empty($info)){
            $data['valid'] = false;
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }

    //我的留言咨询
    function myMessage(){
        if (session("?c_id")){
            $model =M("comment");
            $count      = $model->where("pid is null and article_id is null and audit <>2 and member_id =".session("c_id")) ->order("publish_time desc")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list = $model->where("pid is null and article_id is null and audit <>2 and member_id =".session("c_id")) ->order("publish_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('list', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出

            $this->display();
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }
    }

    //我的预约视频咨询
    function myConsult(){
        if (session("?c_id")){
            $model =M("consult");
            $count      = $model->where("member_id =".session("c_id")) ->order("create_time desc")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list = $model->where("member_id =".session("c_id")) ->order("create_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('list', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出

            $this->display();
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }

    }

    //我的预约咨询详情
    function consultor(){
        if (session("?c_id")){
            $model =M("consult");
            $info = $model-> where("pk=".I("get.u")) -> select();
            $this -> assign("info",$info);
            $this->display();
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }
    }


    //我的预约咨询时间表
    function consultList(){
        if (session("?c_id")){
            $model =M("consult");
            $info = $model-> where("pk=".I("get.u")) -> select();
            $this -> assign("info",$info);
            $info2 = M("consult_item")->field("a.pk,a.times,a.count_times,a.status,a.start_time,a.end_time,a.teacher_eval,a.member_eval,a.member_eval_time,b.real_name")->join("as a left join teacher as b on a.teacher_id = b.pk")->order("a.times asc")->where("a.order_id=".I("get.u"))->select();
            $this -> assign("info2",$info2);
            $this->display();
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }

    }

    //我的预约咨询时间表
    function myEval(){
        if (session("?c_id")){
            if (!empty($_POST)) {

                $verify = new \Think\Verify();
                if (!$verify->check(I("post.verify"))){
                    $this->error("验证码错误！");
                }
                //实例化
                $model = new \Model\Consult_itemModel();
                //验证数据 Consult_itemModel
                $z = $model -> create();
                if (!$z){
                    //show_bug($model -> getError());
                    $this->error("您录入的数据格式错误！");
                    exit();
                }

                $log["member_eval"] = I("post.member_eval");
                $log["member_eval_time"] = date("Y-m-d H:i:s");
                $model->  where("pk=".I('post.pk'))  ->setField($log);

                $id = $model->field("order_id")->where("pk=".I("post.pk")) ->find();

                //录入成功
                $this->success("恭喜您，评论成功！",__MODULE__."/Member/consultList/u/".$id['order_id']);
            }else{
                $model =M("consult_item");
                $info = $model->field("a.time_number,a.pk,a.times,a.count_times,a.status,a.start_time,a.end_time,a.teacher_eval,a.member_eval,a.member_eval_time,b.real_name")->join("as a left join teacher as b on a.teacher_id = b.pk")-> where("a.pk=".I("get.u")) -> select();
                $this -> assign("info",$info);

                $this->display();
            }

        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }

    }



    //我的订单
    function myOrder(){
        if (session("?c_id")){
            $model =M("video_order");
            $count      = $model->field("b.name,a.order_number,a.request_time,a.status,a.pk")->join("as a left join video_category as b on a.video_category_id = b.pk")->where("a.member_id =".session("c_id")) ->order("a.request_time desc")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list = $model->field("b.name,a.order_number,a.request_time,a.status,a.pk")->join("as a left join video_category as b on a.video_category_id = b.pk")->where("a.member_id =".session("c_id")) ->order("a.request_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('list', $list);// 赋值数据集
            $this->assign('page', $show);// 赋值分页输出

            $this->display();
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }
    }

    //订单详情
    function orderDetail(){
        if (session("?c_id")){
            $model =M("video_order");
            if ($model->where("pk=".I("get.u")) ->find() == null){
                $this->error("页面无法访问！");
            }else{
                if (!empty($_POST)) {

                    //实例化
                    $model = new \Model\Video_orderModel();
                    //验证数据 Video_orderModel
                    $z = $model -> create();
                    if (!$z){
                        //show_bug($model -> getError());
                        $this->error("您录入的数据格式错误！");
                        exit();
                    }
                    $log["comment"] = I("post.comment");
                    $model->  where("pk=".I('post.pk'))  ->setField($log);

                    //录入成功
                    $this->success("恭喜您，备注修改成功！");

                }else{
                    $info = $model->field("a.*,b.name")->join("as a left join video_category as b on a.video_category_id = b.pk")->where("a.pk=".I("get.u")) ->find();
                    $this->assign('info', $info);
                    $this->display();
                }

            }
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }
    }

    //我的课程
    function myCourse(){
        if (session("?c_id")){
            $model =M("video_limit");
            $member = session("c_id");
//            $member = 2;
            $course = $model->distinct(true)->join("as a LEFT JOIN video_category as b on a.video_category_id = b.pk ")->field("a.video_category_id,b.name")->where("member_id=".$member) -> select();
            foreach ($course as $k =>$m){
                $info[$k]['name'] = $m["name"];
                $info[$k]['sub'] = $model->join("as a left join video as b on a.video_id = b.pk")->field("a.limit_count,a.current_count,b.title,b.pk")->where("a.member_id = ".$member." and a.video_category_id=".$m["video_category_id"])->select();
            }

            $this->assign('info', $info);// 赋值分页输出
            $this->display();
        }else{
            redirect(__MODULE__."/Index/login");
            //$this->error("请登录后在操作！",__MODULE__."/Index/login");
        }
    }


}