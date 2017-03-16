<?php
namespace Home\Controller;
use Think\Controller;
class VideoController extends VideopubController {
    //首页
    function index(){
        if (!empty($_POST)){
            //验证码校验

            $verify = new \Think\Verify();
            if (!$verify->check(I("post.verify"))){
                $verinfo = "验证码错误！";
                $info["username"] = I("post.username");
                $info["password"] = I("post.password");
                $info["ver"] = $verinfo;
                $this->assign('verinfo',$info);
            }else{
                //通过验证码验证
                if(I("post.username")!="" && I("post.password")!="")
                {
                    $user = M("member");
                    //查找匹配帐号
                    $attr = $user->where("username='".I("post.username")."' and state <> 3")->find();
                    //show_bug($attr);
                    if(sha1(I("post.password"))==$attr["password"]){

                        //修改登录时间及IP
                        $data = array('ip'=>get_client_ip(),'last_login_time'=>date("Y-m-d H:i:s"));
                        $user-> where('pk='.$attr["pk"])->setField($data);

                        //插入登录数据
                        $member_login_info = M("member_login_info");
                        $log["ip"] = get_client_ip();
                        $log["login_time"] = date("Y-m-d H:i:s");
                        $log["member_id"] = $attr["pk"];
                        $member_login_info->data($log)->add();

                        // 存入session
                        session("c_username",$attr["username"]);
                        session("c_id",$attr["pk"]);
                        session("c_real_name",$attr["real_name"]);

                        //登录成功，跳转欢迎页
                        $this->success("恭喜您，".session("c_real_name")."登录成功！");
                        exit();
                    }
                    else
                    {
                        $verinfo = "用户名或密码错误！";
                        $info["username"] = I("post.username");
                        $info["password"] = I("post.password");
                        $info["ver"] = $verinfo;
                        $this->assign('verinfo',$info);
                    }
                }
                else{
                    $verinfo = "用户名或密码不能为空！";
                    $info["username"] = I("post.username");
                    $info["password"] = I("post.password");
                    $info["ver"] = $verinfo;
                    $this->assign('verinfo',$info);
                }
            }
        }
        if (session("?c_id")){
            $member = M("member")->field("nickname,last_login_time")->where("pk=".session("c_id"))->find();
            $this->assign('member',$member);
        }

        $article = M("article");

        //学习公告
        $notice = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 21 and a.is_show = 1")->order('a.update_time desc')->limit(5)->select();
        $this->assign('notice',$notice);

        //研究所简介
        $introduction = M("introduction")->field("summary,summary_title,pk") ->find();
        $this->assign('introduction',$introduction);

        //技术团队
        $person_intro = M("person_intro");
        $tech_team = $person_intro ->field("thumbnail,photo_describe,pk") -> where("is_show = 1") ->order("custom_sort desc") -> select();
        $this -> assign("tech_team",$tech_team);

        

        $this -> display();
    }


    //最新课程列表
    function cateList(){

        $video_category = M("video_category");
        $count      = $video_category->field("pk,update_time,name") ->where("is_show=1") ->order("update_time desc")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $video_category->field("pk,update_time,name") ->where("is_show=1") ->order("update_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this -> display();
    }

    //最新课程详情
    function cate(){
        $video_category = M("video_category");
        if ($video_category->where("is_show = 1 and pk=".I("get.u"))->find() == null) {
            //错误ID
            $this->error("页面无法访问！");
        } else {
            $info = $video_category->where("pk=".I("get.u"))->find();
            $this->assign('info', $info);

            if (I("get.free") == 1){
                $video = M("video")->order("custom_sort desc,update_time asc")->field("title,pk,is_free")->where("is_show=1 and is_free = 1 and video_category_id=".I("get.u"))->select();
            }else{
                $video = M("video")->order("custom_sort desc,update_time asc")->field("title,pk,is_free")->where("is_show=1 and video_category_id=".I("get.u"))->select();
            }

            $this->assign('video', $video);
            $this->display();
        }
    }

    //详细课程列表
    function showList(){

        $video = M("video");
        $count      = $video->field("pk,update_time,title") ->where("is_show=1") ->order("update_time desc")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,C("PAGE_NUM"));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list = $video->field("pk,update_time,title") ->where("is_show=1") ->order("update_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出

        $this -> display();
    }

    //视频详情
    function show(){
        $video = M("video");
        if ($video->where("is_show = 1 and pk=".I("get.u"))->find() == null) {
            //错误ID
            $this->error("页面无法访问！");
        } else {
            $info = $video->where("pk=".I("get.u"))->find();
            $this->assign('info', $info);

            $cate = M("video_category")->field("name,pk")->where("pk=".$info['video_category_id'])->find();
            $this->assign('cate', $cate);

            $vlist = M("video")->order("custom_sort desc,update_time asc")->field("title,pk,is_free")->where("is_show=1 and video_category_id=".$cate['pk'])->select();
            $this->assign('vlist', $vlist);


            // 1：可以观看；2：未登录提示；3：无权限； 4：次数已经用完；
            //免费判断
            if ($info['is_free'] == 1){
                $limit['tips'] = 1;
            }else{
                //登录判断
                if(!session('?c_id')){
                    //未登录
                    $limit['tips'] = 2;
                }else{
                    $video_limit = M("video_limit")->where("a.member_id = ".session('c_id')." AND a.video_id =".I("get.u"))->field("a.current_count,a.limit_count")->join(" as a LEFT JOIN member as b ON a.member_id = b.pk LEFT JOIN video as c ON a.video_id = c.pk")->find();
                    //权限判断
                    if ($video_limit == null){
                        //无权限
                        $limit['tips'] = 3;
                    }else{
                        if ($video_limit['limit_count'] - $video_limit['current_count'] > 0){
                            //正常观看
                            $limit['tips'] = 1;
                            $limit['count'] = $video_limit['limit_count'] - $video_limit['current_count'];
                        }else{
                            //次数已经用完
                            $limit['tips'] = 4;
                            $limit['count'] = 0;
                        }
                    }
                }
            }

            $this->assign('limit', $limit);

            $this->display();
        }
    }

}