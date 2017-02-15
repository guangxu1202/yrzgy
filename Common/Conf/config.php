<?php
return array(
	//'配置项'=>'配置值'

    // URL路由模式
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    "URL_MODEL"  => 1,

    // 日志追踪
    "SHOW_PAGE_TRACE" => true,
    //数据库连接配置
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'psy',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  '',    // 数据库表前缀
    'DB_PARAMS'          	=>  array(), // 数据库连接参数
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    //错误页面模板
    'TMPL_ACTION_ERROR'     =>  'Public:error', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   =>  'Public:success', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   =>  APP_PATH.'House/View/Public/exception.html', // 异常页面的模板文件
    /* SESSION设置 */
    'SESSION_OPTIONS'       =>  array(), // session 配置数组 支持type name id path expire domain 等参数


);