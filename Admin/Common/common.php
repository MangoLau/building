<?php
/**
 * fck
 */
function editor($name='content',$value='',$editorName='Editor',$width='1000',$height='400') {
	import("ORG.Util.Ckeditor");
	import("ORG.Util.Ckfinder");
	//实例化CKeditor方法,传入参数为编辑器所在位置public/ckeditor
	$ckeditor = new CKEditor(__ROOT__.'/Public/admin/ckeditor/');
	//设置模式为输出,否则下面的editor方法没有返回值,而是直接输出,无法显示在我们想要显示编辑器的位置
	$ckeditor->returnOutput=true;
	$configArray=array(
			'height'=>$height,  //编辑器高度
			'width'=>$width     //编辑器宽度
	);
	$_SESSION['CKFINDER']['baseurl'] = __ROOT__.'/Public/upload/news/';
	//CKfinder与CKEditor整合,参数1为上面实例化的CKEditor对象,参数2为CKfinder的位置public.ckfinder
	CKFinder::SetupCKEditor($ckeditor, __ROOT__.'/Public/admin/ckfinder/') ; //无需上传功能则跳过
	//创建编辑器并返回代码,用于分配到页面
	$CK = $ckeditor->editor($name,$value,$configArray);
	//分配编辑器到页面,但未显示
	return $CK;
}


function _sort($_string,$_num){
	if(mb_strlen($_string,'utf-8')>$_num){
		$_string=mb_substr($_string,0,$_num,'utf-8').'...';
	}
	return $_string;
}


/**
 * 递归重组节点信息为多维数组
 * @param unknown $node  要处理的节点数组
 * @param number $pid	 父级id
 */
function node_merge($node, $access = NULL, $pid = 0) {
	$arr = array();

	foreach ($node as $v) {
		if (is_array($access)) {
			$v['access'] = in_array($v['id'], $access) ? 1 : 0;
		}
		if ($v['pid'] == $pid) {
			$v['child'] = node_merge($node, $access, $v['id']);
			$arr[] =$v;
		}
	}

	return $arr;
}

?>