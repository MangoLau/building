<?php
//	������ļ�

	define('THINK_PATH', './ThinkPHP/');
	
	define('APP_PATH', './admin/');
	
	define('APP_NAME', 'admin');
	
// 	ָ������ʱ�Ļ����ļ�·��������
// 	define('RUNTIME_PATH', '../admin/temp');

	
// 	�Ƿ��ȥ�����ļ��Ŀո�
// 	define('STRIP_RUNTIME_SPACE', false);

// 	�Ƿ�������
// 	define('NO_CACHE_RUNTIME', true);
	
	require THINK_PATH.'ThinkPHP.php';
	
//  �������ļ��ײ�ṹ���ƹ�ȥ
	App::run()
?>
