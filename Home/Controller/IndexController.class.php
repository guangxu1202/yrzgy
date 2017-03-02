<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    //首页
    function index(){

        //获取banner
        $flash_scroll = M("flash_scroll");
        $banner = $flash_scroll->field("description_heading as title,description_paragraph as str,link,url")-> where("is_show = 1") ->order("custom_sort desc") -> select();
        $this -> assign("banner",$banner);

        //获取图片故事
        $picture_story = M("picture_story");
        $story = $picture_story->field("thumbnail,title,pk")-> where("is_show = 1") ->order("custom_sort desc")-> limit("1")->select() ;
        $this -> assign("story",$story);

        //获取知名媒体专题
        $friendship_links = M("friendship_links");
        $media = $friendship_links->where("position = 1")->field("name,url,title_bold,title_color") ->limit("5") ->order("custom_sort desc") ->select();
        $this -> assign("media",$media);



        $this -> display();
    }

    function regsiter(){
        //实例化
        $user = new \Model\MemberModel();
        if (!empty($_POST)){
            //验证数据 MemberModel
            $z = $user -> create();
            if (!$z){
                show_bug($user -> getError());
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
                $log["identity_card"] = $info['savepath'].$info['savename'];
            }

            // 上传资质证明
            $info3   =   $upload->uploadOne($_FILES['certificationFile']);
            if(!$info3) {// 上传错误提示错误信息
                //$this->error($upload->getError());
            }else{// 上传成功 获取上传文件信息
                $log["certification"] = $info['savepath'].$info['savename'];
            }

            //数据录入
            $log["activation_code"] = sha1(date("Y-m-d H:i:s").rand(1,999));
            $log["address"] = I("post.address");
            if (I("post.birthday")!=''){
                $log["birthday"] = I("post.birthday");
            }
            $log["create_time"] = date("Y-m-d H:i:s");
            $log["email"] = I("post.email");
            $log["gender"] = I("post.gender");
            $log["ip"] = get_client_ip();
            $log["mobile"] = I("post.mobile");
            $log["msn"] = I("post.msn");
            $log["nickname"] = I("post.nickname");
            $log["organization"] = I("post.organization");
            $log["password"] = sha1(I("post.password"));
            $log["qq"] = I("post.qq");
            $log["real_name"] = I("post.real_name");
            $log["state"] = 0;
            $log["summary"] = I("post.summary");
            $log["telephone"] = I("post.telephone");
            $log["username"] = I("post.username");
            $result =$user->data($log)->add();

            if ($user){
                $id = $result; // 获取数据库写入数据的主键
                //注册成功后自动登录

                //修改登录时间及IP
                $data1 = array('ip'=>get_client_ip(),'last_login_time'=>date("Y-m-d H:i:s"));
                $user-> where('pk='.$id)->setField($data1);

                //插入登录数据
                $member_login_info = M("member_login_info");
                $date["ip"] = get_client_ip();
                $date["login_time"] = date("Y-m-d H:i:s");
                $date["member_id"] = $id;
                $member_login_info->data($date)->add();

                // 存入session
                session("c_username",I("post.username"));
                session("c_id",$id);
                session("c_real_name",I("post.real_name"));

                //录入成功
                $this->success("恭喜您，用户注册成功，即将跳转到个人中心！",__MODULE__."/Member/main");
            }else{
                $this->error("数据录入失败！");
                exit();
            }

        }else{
            $this -> display();
        }
    }

    //登录
    function login(){
        if (!empty($_POST)){
            //验证码校验

            $verify = new \Think\Verify();
            if (!$verify->check(I("post.verify"))){
                $verinfo = "验证码错误！";
                $this->assign('verinfo',$verinfo);
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
                        $this->success("恭喜您，".session("c_real_name")."登录成功！",__MODULE__."/Member/main");
                        exit();
                    }
                    else
                    {
                        $verinfo = "用户名或密码错误！";
                        $this->assign('verinfo',$verinfo);
                    }
                }
                else{
                    $verinfo = "用户名或密码不能为空！";
                    $this->assign('verinfo',$verinfo);
                }
            }
        }
        $this -> display();

    }

    //退出系统
    function logout() {
        session('c_username',null);
        session('c_id',null);
        session('c_real_name',null);
        $this->success('成功退出系统！');
    }

    //验证码
    function verifyImg(){
        ob_clean();
        $config =    array(
            'fontSize'    =>    20,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'imageH'      =>    40,
            'imageW'      =>    140,
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }

    //查找username是否重名
    function checkuser(){
        $user = M("member"); // 实例化User对象
        $info = $user->where('username="'.I("post.username").'"')->find();
        if (!empty($info)){
            $data['valid'] = false;
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }

    //查找email是否重名
    function checkemail(){
        $user = M("member"); // 实例化User对象
        $info = $user->where('email="'.I("post.email").'"')->find();
        if (!empty($info)){
            $data['valid'] = false;
        }else{
            $data['valid'] = true;
        }
        $this->ajaxReturn($data);
    }

    //联系我们
    function contact_us(){
        //获取联系我们
        $contact_us = M("contact_us");
        $info = $contact_us -> select();
        $this -> assign("info",$info);

        $this -> display();
    }

}