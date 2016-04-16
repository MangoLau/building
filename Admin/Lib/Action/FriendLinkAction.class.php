<?php
class FriendLinkAction extends CommonAction{
	
	
	/**
	 +--------------------
	 *友情链接,在thinbk_index_class中的c_id是34
	 +--------------------
	 * 
	 * 
	 * */
	function setLink(){
		$link = M("Link");
		$f_link = $link -> where("c_id=34") -> select();
		$this -> assign("f_link", $f_link);

		$this -> display();
		
	}
	
	
	function addLink(){
		if($_POST[add]){
			$link = M("Link");
			$link -> name = $_POST[name];
			$link -> c_id = 34;
			$link -> link = $_POST[link];
			$f = $link -> add();
			if($f){
				$this -> success('添加成功！');
			}else{
				$this -> error('添加失败！');
			}
		}
	}
	
	function updateLink(){
		$link = M('Link');
		$where[id] = $_POST[id];
		$data[name] = $_POST[name];
		$data[link] = $_POST[link];
		$flag = $link -> where($where) -> save($data);
		if($flag){
			$this -> success('更新成功！');
		}else{
			$this -> error('更新失败！');
		}
		
	}
	
	function delLink(){
		$link = M('Link');
		$where[id] = $_GET[id];
		$flag = $link -> where($where) -> delete();	 //删除本身
		if($flag){
			$this -> success('删除成功！');
		}else{
			$this -> error('删除失败！');
		
		}
	}
		
}
?>