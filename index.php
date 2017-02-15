<?php

//定义公共路径常量
define("SITE_URL","http://127.0.0.1"); // 域名
define("WEB_URL",SITE_URL."/Public/web");  //前台资源路径
define("ADMIN_URL",SITE_URL."/Public/admin");  //后台资源路径
define("PUBLIC_URL",SITE_URL."/Public");  //公共资源路径



//开发调试模式true,生产模式false
define("APP_DEBUG", true);

//引入框架核心程序
include "../ThinkPHP/ThinkPHP.php";

function show_bug($msg){
    echo "<pre style='color:#cc0000'>";
    var_dump($msg);
    echo "</pre>";
}

function checkBit($d){
    if ($d =="true"){
        return true;
    }else{
        return false;
    }
}