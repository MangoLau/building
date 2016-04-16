<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction{		
	// 框架首页
	public function index() {
		$this->display();
	}
    
    function del(){
    	echo 'index下面的删除';
    }
    
    function add(){
    	echo 'index下面的添加';
    }
    
    function update(){
    	echo 'index下面的更新';	
    }
    
    function login(){
    	$this -> display();
    }
}
?>
