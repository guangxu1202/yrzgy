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
    'KEYWORDS'              =>  '元认知,元认知干预，元认知心理，元认知心理干预，元认知心理干预技术，金洪源', // 公共关键词
    'DESCRIPTION'           =>  '元认知研究所由金洪源教授任所长，面向全国提供心理技术服务，解决学生偏科、厌学、马虎、考试焦虑、拖延等学习障碍问题；改善人际关系，解决亲子、家庭问题；提供失眠、焦虑、强迫、抑郁等辅助性干预；优化性格系统。主要业务：个体咨询、团体辅导、技术培训、进驻学校推进心理健康技术化改革。下设临床服务部、研修生部、驻校服务部、中小学教研部、技术研发与培训部。', // 公共描述
    // 邮件配置


);