<?php
$arr1 = array(
		//'配置项'=>'配置值'
		'URL_MODEL' =>'2',
		'URL_PATHINFO_MODEL' => '2',
	    'APP_DEBUG' => true,
        'URL_HTML_SUFFIX'    => '.html',  // URL伪静态后缀设置

		'TOKEN_ON'     =>   false, //是否开启令牌验证
		//	模板左右标签
		'TMPL_L_DELIM'=>'{',
		'TMPL_R_DELIM'=>'}',

		/* 运行时间设置 */
		'SHOW_RUN_TIME'=>false,          // 运行时间显示
		'SHOW_ADV_TIME'=>false,          // 显示详细的运行时间
		'SHOW_DB_TIMES'=>false,          // 显示数据库查询和写入次数
		'SHOW_CACHE_TIMES'=>false,       // 显示缓存操作次数
		'SHOW_USE_MEM'=>false,           // 显示内存开销
		'SHOW_PAGE_TRACE'=>false,        // 显示页面Trace信息 由Trace文件定义和Action操作赋值
		'APP_FILE_CASE'  =>   true, // 是否检查文件的大小写 对Windows平台有效
//    'ERROR_PAGE' =>'/Public/error.html'

);

$arr2 = include './config.inc.php';

return array_merge($arr1,$arr2);
?>