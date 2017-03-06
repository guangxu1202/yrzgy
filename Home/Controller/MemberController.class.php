<?php
namespace Home\Controller;
use Think\Controller;
class MemberController extends CommonController {
    function main(){
        //获取个人信息
        $member = M("member");
        $info = $member->where("pk=".session("c_id")) -> find();
        $this -> assign("info",$info);

        $this -> display();
    }
}