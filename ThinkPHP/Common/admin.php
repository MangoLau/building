<?php
//	主入口文件

	define('THINK_PATH', './ThinkPHP/');
	
	define('APP_PATH', './admin/');
	
	define('APP_NAME', 'admin');
	
// 	指定运行时的缓存文件路径和名称
// 	define('RUNTIME_PATH', '../admin/temp');

	
// 	是否除去缓存文件的空格
// 	define('STRIP_RUNTIME_SPACE', false);

// 	是否开启缓存
// 	define('NO_CACHE_RUNTIME', true);
	
	require THINK_PATH.'ThinkPHP.php';
	
//  把整个文件底层结构复制过去
	App::run()
?>
