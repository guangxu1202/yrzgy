<?php
namespace Home\Controller;
use Think\Controller;


class IndexController extends CommonController {

    //测试邮件发送
    function mail_send() {

        $mail = '95149313@qq.com';  //收件人邮箱
        $title = '新的测试循环邮件'; //邮件标题
        $content = '循环邮件正文！';
        
        //邮件发送代码
        $smtp = M("smtp")->field("email,host_name,password,sender")->where("is_used = 1")->order("custom_sort desc")->select();
        foreach ($smtp as $m =>$k){
            if (sendMail($mail,$title,$content,$k['host_name'],$k['email'],$k['password'],$k['email'],$k['sender'])){
                echo "发送成功".$k['email'];
                break;
            }else{
                echo "发送失败".$k['email'];
            }
        }

    }
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

        //技术团队
        $person_intro = M("person_intro");
        $tech_team = $person_intro ->field("thumbnail,photo_describe,pk") -> where("is_show = 1") ->order("custom_sort desc") -> select();
        $this -> assign("tech_team",$tech_team);


        //获取文章类型
        $article = M("article");
        //服务指南
        $fwzn = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 3 and a.is_show = 1")->order('a.update_time desc')->limit(5)->select();
        $this -> assign("fwzn",$fwzn);


        //技术之窗
        $jszc = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 23 and a.is_show = 1")->order('a.update_time desc')->limit(5)->select();
        $this -> assign("jszc",$jszc);

        //学校教育
        $xxjy = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 1 and a.is_show = 1")->order('a.update_time desc')->limit(6)->select();
        $this -> assign("xxjy",$xxjy);

        //心身疾病治疗
        $xsjbzl = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 13 and a.is_show = 1")->order('a.update_time desc')->limit(5)->select();
        $this -> assign("xsjbzl",$xsjbzl);

        //家庭教育
        $jtjy = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 7 and a.is_show = 1")->order('a.update_time desc')->limit(5)->select();
        $this -> assign("jtjy",$jtjy);

        //专家答疑
        $zjdy = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 17 and a.is_show = 1")->order('a.update_time desc')->limit(5)->select();
        $this -> assign("zjdy",$zjdy);

        //学生问题
        $xswt = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 11 and a.is_show = 1")->order('a.update_time desc')->limit(10)->select();
        $this -> assign("xswt",$xswt);

        //学生学习
        $xsxx = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 6 and a.is_show = 1")->order('a.update_time desc')->limit(10)->select();
        $this -> assign("xsxx",$xsxx);

        //考生心理
        $ksxl = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 8 and a.is_show = 1")->order('a.update_time desc')->limit(10)->select();
        $this -> assign("ksxl",$ksxl);


        //技术背景
        $jsbj = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 4 and a.is_show = 1")->order('a.update_time desc')->limit(10)->select();
        $this -> assign("jsbj",$jsbj);

        //科研园地
        $kyyd = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 10 and a.is_show = 1")->order('a.update_time desc')->limit(10)->select();
        $this -> assign("kyyd",$kyyd);

        //技术档案
        $jsda = $article->field("a.title,a.pk,a.is_title_bold,a.title_color") -> join("as a LEFT JOIN article_model as m ON a.pk = m.article_id LEFT JOIN model ON m.model_id = model.pk") ->where("model.pk = 16 and a.is_show = 1")->order('a.update_time desc')->limit(10)->select();
        $this -> assign("jsda",$jsda);

        //首页banner图片
        $ad = M("ad");
        $adbar = $ad-> field("ad_path,link,ad_name")->where("position = 0 and expired = 0") ->find();
        $this -> assign("adbar",$adbar);

        //首页模块图片
        $model_image = M("model_image");
        $modelImgs = $model_image->field("code,img_path") -> select();
        $this -> assign("modelImgs",$modelImgs);


        $this -> display();
    }

    //注册
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

                //邮件内容配置
                $mail_title = I("post.nickname")."欢迎来到元认知心理干预技术网";
                $mail_content = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>元认知心理干预技术注册成功</title></head><body><style>.FriendTip {border: 1px solid #FCDC89; margin-bottom: 10px; padding-left: 15px; line-height: 30px; color: #e92201; background-color: #FDF5DB; font-size: 12px;}.Order {padding: 10px; background-color: #e2ffc6; border: 1px solid #a1ff45; margin-bottom: 10px;}.Order h6 {font-size: 14px; font-weight: bold; border-bottom: 1px solid #ccc; height: 24px; line-height: 24px; padding-left: 15px; margin-bottom: 10px;}.Order table {margin-bottom: 10px; width: 100%; font-size: 12px;}.Order table th {text-align: right;}.Order table th,.Order table td {padding: 5px;}</style>	<h2 style="font-size: 14px; font-weight: bold; margin: 10px; margin-top: 60px;">亲爱的'.I("post.nickname").', 您好:</h2>	<div class="main" style="margin: 10px;">		<p class="FriendTip">			欢迎来到<a href="'.SITE_URL.'" target="_blank">元认知心理干预技术网</a>.		</p>		<div class="Order">			<h6>我们很高兴通知您, 您已经是<a href="'.SITE_URL.'" target="_blank">元认知心理干预技术网</a>的会员了.</h6>			<h6>您的帐号信息如下</h6>			<table>				<colgroup width="150px"></colgroup>				<tr><th>帐号：</th><td>'.I("post.username").'</td></tr>				<tr><th>密码：</th><td>'.I("post.password").'</td></tr>			</table>			<h6>欢迎<a href="'.SITE_URL.'/Home/Index/login" target="_blank">登录</a><a href="'.SITE_URL.'" target="_blank">元认知心理干预技术网</a>, 您可以在<a href="'.SITE_URL.'/Home/Member/baseEdit" target="_blank">个人中心</a>里修改个人信息.</h6>		</div>		<p class="FriendTip">			中国元认知心理干预技术研究所是在辽宁师范大学金洪源教授创立的元认知心理干预技术及其理论的专业基础上创立的科研、技术推广与社会服务为主要任务与功能的心理学专业机构。			目前, 本研究所从管理干部到每一位专业技术人员, 都是金洪源教授亲自培养并毕业获得硕士学位的研究生。他们共同怀着深入研发、完善元认知心理干预技术的决心, 以越来越高效和越来越多功...			<a href="'.SITE_URL.'/Home/System/introduction">[详细]</a>		</p>	</div></body></html>';

                //邮件发送代码
                $smtp = M("smtp")->field("email,host_name,password,sender,pk")->where("is_used = 1")->order("custom_sort desc")->select();
                foreach ($smtp as $m =>$k){
                    if (sendMail(I("post.email"),$mail_title,$mail_content,$k['host_name'],$k['email'],$k['password'],$k['email'],$k['sender'])){
                        //记录邮件发送
                        $sent_mail = M("sent_mail");
                        $save['content'] = $mail_content;
                        $save['sent_time'] = date("Y-m-d H:i:s");
                        $save['title'] = $mail_title;
                        $save['member_id'] = $id;
                        $save['smtp_id'] = $k['pk'];
                        $sent_mail->add($save);
                        break;
                    }
                }


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
                        $this->success("恭喜您，".session("c_real_name")."登录成功！",__MODULE__."/Member/main");
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
        $this -> display();

    }

    //忘记密码
    function forget(){
        if (!empty($_POST)){
            //验证码校验

            $verify = new \Think\Verify();
            if (!$verify->check(I("post.verify"))){
                $verinfo = "验证码错误！";
                $info["username"] = I("post.username");
                $info["ver"] = $verinfo;
                $this->assign('verinfo',$info);
            }else{
                //通过验证码验证
                if(I("post.username")!="")
                {
                    $user = M("member");
                    //查找匹配帐号
                    $attr = $user->where("(username='".I("post.username")."' or email='".I("post.username")."') and state <> 3") -> find();
                    //show_bug($attr);
                    if($attr != null){

      
                        //邮件内容配置
                        $mail_title = "元认知心理干预技术网密码重置";
                        $mail_content = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>元认知心理干预技术网密码找回</title></head><body><style>.FriendTip {border: 1px solid #FCDC89; margin-bottom: 10px; padding-left: 15px; line-height: 30px; color: #e92201; background-color: #FDF5DB; font-size: 12px;}.Order {padding: 10px; background-color: #e2ffc6; border: 1px solid #a1ff45; margin-bottom: 10px;}.Order h6 {font-size: 14px; font-weight: bold; border-bottom: 1px solid #ccc; line-height: 24px; padding-left: 15px; margin-bottom: 10px; padding-bottom: 10px;}.Order table {margin-bottom: 10px; width: 100%; font-size: 12px;}.Order table th {text-align: right;}.Order table th,.Order table td {padding: 5px;}</style>	<h2 style="font-size: 14px; font-weight: bold; margin: 10px; margin-top: 60px;">亲爱的'.$attr['nickname'].', 您好:</h2>	<div class="main" style="margin: 10px;">		<div class="Order">			<h6>				您在元认知心理干预技术网申请修改密码, 请点击<a href="'.SITE_URL.'/Home/Index/reset/u/'.$attr['username'].'/cv/'.$attr['activation_code'].'" target="_blank">这里</a>, 如果链接没有跳转, 请将下面地址<br><br>				<span style="color: red;">'.SITE_URL.'/Home/Index/reset/u/'.$attr['username'].'/cv/'.$attr['activation_code'].'</span><br><br>				手动复制到浏览器地址栏, 之后敲击回车.<br><br>				注意: 此激活链接只限使用一次.<br><br>				如果不是您的操作, 请将此邮件忽略, 我们深表歉意.			</h6>			<h6>欢迎<a href="'.SITE_URL.'/Home/Index/login" target="_blank">登录</a><a href="'.SITE_URL.'" target="_blank">元认知心理干预技术网</a></h6>		</div>		<p class="FriendTip">			中国元认知心理干预技术研究所是在辽宁师范大学金洪源教授创立的元认知心理干预技术及其理论的专业基础上创立的科研、技术推广与社会服务为主要任务与功能的心理学专业机构。			目前, 本研究所从管理干部到每一位专业技术人员, 都是金洪源教授亲自培养并毕业获得硕士学位的研究生。他们共同怀着深入研发、完善元认知心理干预技术的决心, 以越来越高效和越来越多功...			<a href="'.SITE_URL.'/Home/System/introduction">[详细]</a>		</p>	</div></body></html>';

                        //邮件发送代码
                        $smtp = M("smtp")->field("email,host_name,password,sender,pk")->where("is_used = 1")->order("custom_sort desc")->select();
                        foreach ($smtp as $m =>$k){
                            if (sendMail($attr['email'],$mail_title,$mail_content,$k['host_name'],$k['email'],$k['password'],$k['email'],$k['sender'])){
                                //记录邮件发送
                                $sent_mail = M("sent_mail");
                                $save['content'] = $mail_content;
                                $save['sent_time'] = date("Y-m-d H:i:s");
                                $save['title'] = $mail_title;
                                $save['member_id'] = $attr['pk'];
                                $save['smtp_id'] = $k['pk'];
                                $sent_mail->add($save);
                                break;
                            }
                        }




                        //登录成功，跳转欢迎页
                        $this->success("恭喜您，操作成功！",__MODULE__."/Index/forgetTip");
                        exit();
                    }else{
                        $verinfo = "没有找到您输入的用户名或注册邮箱！";
                        $info["username"] = I("post.username");
                        $info["ver"] = $verinfo;
                        $this->assign('verinfo',$info);
                    }
                }
                else{
                    $verinfo = "用户名或注册邮箱不能为空！";
                    $info["username"] = I("post.username");
                    $info["ver"] = $verinfo;
                    $this->assign('verinfo',$info);
                }
            }
        }

        $this -> display();

    }

    //重置密码
    function  reset(){
        $member = M("member");
        $check = $member->field("pk,activation_code,username,email")->where("activation_code='".I("get.cv")."' and username = '".I("get.u")."'")->find();


        if ($check == null){
            $this -> error("您的密码重置链接有错误，请重新检查！",__MODULE__."/Index/login",5);
        }else{
            //正确的重置密码链接
            if (!empty($_POST)){
                //验证码校验
                if (I("password")!=''){

                    $log["activation_code"] = sha1(date("Y-m-d H:i:s").rand(1,999));
                    $log["password"] = sha1(I("post.password"));
                    $member->where('pk = '.$check['pk'])->save($log); // 根据条件更新记录


                    //邮件内容配置
                    $mail_title = "元认知心理干预技术网新密码";
                    $mail_content = '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>密码重置成功</title></head><body><style>.FriendTip {border: 1px solid #FCDC89; margin-bottom: 10px; padding-left: 15px; line-height: 30px; color: #e92201; background-color: #FDF5DB; font-size: 12px;}.Order {padding: 10px; background-color: #e2ffc6; border: 1px solid #a1ff45; margin-bottom: 10px;}.Order h6 {font-size: 14px; font-weight: bold; border-bottom: 1px solid #ccc; height: 24px; line-height: 24px; padding-left: 15px; margin-bottom: 10px;}.Order table {margin-bottom: 10px; width: 100%; font-size: 12px;}.Order table th {text-align: right;}.Order table th,.Order table td {padding: 5px;}</style>	<h2 style="font-size: 14px; font-weight: bold; margin: 10px; margin-top: 60px;">亲爱的蛔蛔, 您好:</h2>	<div class="main" style="margin: 10px;">		<p class="FriendTip">			您在<a href="'.SITE_URL.'" target="_blank">元认知心理干预技术网</a>的密码已经重置, 再次登录请使用新密码.		</p>		<div class="Order">			<h6>您的帐号信息如下</h6>			<table>				<colgroup width="150px"></colgroup>				<tr><th>帐号：</th><td>'.$check['username'].'</td></tr>				<tr><th>密码：</th><td>'.I("post.password").'</td></tr>			</table>			<h6>欢迎<a href="'.SITE_URL.'/Home/Index/login" target="_blank">登录</a><a href="'.SITE_URL.'" target="_blank">元认知心理干预技术网</a>, 您可以在<a href="'.SITE_URL.'/Home/Member/baseEdit" target="_blank">个人中心</a>里修改密码.</h6>		</div>		<p class="FriendTip">			中国元认知心理干预技术研究所是在辽宁师范大学金洪源教授创立的元认知心理干预技术及其理论的专业基础上创立的科研、技术推广与社会服务为主要任务与功能的心理学专业机构。			目前, 本研究所从管理干部到每一位专业技术人员, 都是金洪源教授亲自培养并毕业获得硕士学位的研究生。他们共同怀着深入研发、完善元认知心理干预技术的决心, 以越来越高效和越来越多功...			<a href="'.SITE_URL.'/Home/System/introduction">[详细]</a>		</p>	</div></body></html>';

                    //邮件发送代码
                    $smtp = M("smtp")->field("email,host_name,password,sender,pk")->where("is_used = 1")->order("custom_sort desc")->select();
                    foreach ($smtp as $m =>$k){
                        if (sendMail($check['email'],$mail_title,$mail_content,$k['host_name'],$k['email'],$k['password'],$k['email'],$k['sender'])){
                            //记录邮件发送
                            $sent_mail = M("sent_mail");
                            $save['content'] = $mail_content;
                            $save['sent_time'] = date("Y-m-d H:i:s");
                            $save['title'] = $mail_title;
                            $save['member_id'] = $check['pk'];
                            $save['smtp_id'] = $k['pk'];
                            $sent_mail->add($save);
                            break;
                        }
                    }

                    $this -> success("密码重置成功！",__MODULE__."/Index/resetTip");

                }else{
                    $this -> error("您的操作有误，请重新检查！");
                }
            }else{
                $this -> assign("info",$check);
                $this->display();
            }
        }


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
            //'useNoise'    =>    false, // 关闭验证码杂点
            'useCurve'    =>    false, // 是否使用混淆曲线 默认为true
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