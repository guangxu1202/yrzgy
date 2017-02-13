<?php
namespace House\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize(){
        // 自动运行方法
        $a = session("a_id");
        if(!isset($a)){
            //未登录
            $this->error("您需要登录后才能访问！",__MODULE__."/index/login",5);
        }
    }
}