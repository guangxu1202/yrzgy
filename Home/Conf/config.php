<?php
return array(
	//'配置项'=>'配置值'

    //开启调试日志模式
    "SHOW_PAGE_TRACE" =>  true,

    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    //错误页面模板
    'TMPL_ACTION_ERROR'     =>  'Public:error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  'Public:success', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   =>  APP_PATH.'Home/View/Public/exception.html', // 异常页面的模板文件
);