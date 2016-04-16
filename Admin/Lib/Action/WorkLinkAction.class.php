<?php
class WorkLinkAction extends CommonAction{

	function WorkLinkSet(){
		$l = M("Custom");
		$link = $l -> select();
		$this -> assign("link", $link);
	
		$this -> display();
		
	}
	
	
	function addLink(){
		if($_POST[add]){
			$l= M("Custom");
			$l-> name = $_POST[name];
			$l -> link = $_POST[link];
			$f = $l-> add();
			if($f){
				$this -> success('添加成功！');
			}else{
				$this -> error('添加失败！');
			}
		}
	}
	
	function updateLink(){
		$l = M('Custom');
		$where[id] = $_POST[id];
		$data[name] = $_POST[name];
		$data[link] = $_POST[link];
		$flag = $l-> where($where) -> save($data);
		if($flag){
			$this -> success('更新成功！');
		}else{
			$this -> error('更新失败！');
		}
		
	}
	
	function delLink(){
		$l = M('Custom');
		$where[id] = $_GET[id];
		$where[type] = 4;

		$flag = $l -> where($where) -> delete();	 //删除本身
		if($flag){
			$this -> success('删除成功！');
		}else{
			$this -> error('删除失败！');
		
		}
	}
	
}
?>