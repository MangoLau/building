<?php
/**
 * 截取中文字符串
 * @param $_string
 * @param $_num
 * @return string
 */
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