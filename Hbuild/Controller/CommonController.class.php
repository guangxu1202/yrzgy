<?php
namespace Hbuild\Controller;
use Think\Controller;
class CommonController extends Controller {
    public function _initialize(){
        // 自动运行方法

        if(!session('?b_id')){
            //未登录
            $this->error("您需要登录后才能访问！",__MODULE__."/index/login");
        }

    }
}