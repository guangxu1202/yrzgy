<?php
namespace House\Controller;
use Think\Controller;
class IndexController extends Controller {
    //登录
    function login(){
        if (!empty($_POST)){
            //验证码校验

            $verify = new \Think\Verify();
            if (!$verify->check($_POST["verify"])){
                $verinfo = "验证码错误！";
                $this->assign('verinfo',$verinfo);
            }else{
                //通过验证码验证
                if($_POST["username"]!="" && $_POST["password"]!="")
                {
                    $user = M("user");
                    //查找匹配帐号
                    $attr = $user->where("username='".$_POST["username"]."' and locked = 0")->find();
                    //show_bug($attr);
                    if(sha1($_POST["password"])==$attr["password"]){

                        //修改登录时间及IP
                        $data = array('ip'=>get_client_ip(),'last_login_time'=>date("Y-m-d H:i:s"));
                        $user-> where('pk='.$attr["pk"])->setField($data);

                        //插入登录数据
                        $user_login_info = M("user_login_info");
                        $log["ip"] = get_client_ip();
                        $log["login_time"] = date("Y-m-d H:i:s");
                        $log["user_id"] = $attr["pk"];
                        $user_login_info->data($log)->add();

                        // 存入session
                        session("a_username",$attr["username"]);
                        session("a_id",$attr["pk"]);
                        session("a_real_name",$attr["real_name"]);

                        //登录成功，跳转欢迎页
                        $this->success("恭喜您，".session("a_real_name")."登录成功！",__MODULE__."/Main/index",5);
                        exit();
                    }
                    else
                    {
                        $verinfo = "用户名或密码错误！";
                        $this->assign('verinfo',$verinfo);
                    }
                }
                else
                {
                    $verinfo = "用户名或密码不能为空！";
                    $this->assign('verinfo',$verinfo);
                }
            }
        }
        $this -> display();

    }


    //验证码
    function verifyImg(){
        ob_clean();
        $config =    array(
            'fontSize'    =>    18,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'imageH'      =>    34,
            'imageW'      =>    140,
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }

}