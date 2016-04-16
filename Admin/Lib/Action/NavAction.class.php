<?php
class NavAction extends CommonAction{
	
	
	function setNav(){
		$link = M("Link");
		$nav = $link -> where("type=1 and lev=0") -> select();	
		foreach ($nav as $val){echo $val['c_id'];
			$str = array();
			if ($val['c_id']!='0'){
				$w['lev']=$val['c_id'];
				$str=$link -> where($w) -> select();
			}
			$dao[]=array("id"=>"{$val['id']}","type"=>"{$val['type']}","name"=>"{$val['name']}","file"=>"{$val['file']}","link"=>"{$val['link']}","status"=>"{$val['status']}","display"=>"{$val['dispaly']}","sort"=>"{$val['sort']}","c_id"=>$val['c_id'],"lev"=>"{$val['lev']}","zi"=>$str);
		}	
// 		print_r($dao);exit;
		$home = M('Homemodel');
		$model = $home -> where("pid=0") -> select();
		
		$this -> assign("var", $model);
		$clist = $this -> classList();
		$this -> assign("nav", $dao);
		$this -> assign("clist", $clist);
		$this -> display();
		
	}
	//Ajax
	function find(){
		$home = M('Homemodel');
		$where['pid']=$_GET['id'];
		$id=$_GET['i'];
		$act = $home -> where($where) -> select();
		$str = '<option value="">= 选择方法  =</option>';
		foreach ($act as $val){
			$str.='<option value="'.$val['name'].'" class="a" id="'.$id.'">'.$val['title'].'</option>';
		}
		echo $str;
	}
	
	function addNav(){
		if($_POST['add']){
			$item = trim($_POST['link']);			 
			$link = M("Link");
			$link -> name = $_POST['name'];
			if(empty($_POST['lev'])){
				$link -> lev ='0';
			}else{
				$link -> lev = $_POST['lev'];
			}
			$link -> type = 1;
			$link -> link = $item;
			if(empty($_POST['c_id'])){
				$link -> c_id ='0';
			}else{
				$link -> c_id = $_POST['c_id'];
			}			
			if(!empty($item)){
				if($link -> where("link='".$item."'") -> select()){
					$this -> error('网址有重复，请检查后再添加！');
				}else{
					$f = $link -> add();
					if($f){
						$this -> success('添加成功！');
					}else{
						$this -> error('添加失败！');
					}
				}
			}else{
				$f = $link -> add();
				if($f){
					$this -> success('添加成功！');
				}else{
					$this -> error('添加失败！');
				}
			}
		}
			
	}
	
	function updateNav(){
		$link = M('Link');
		$where['id'] = $_POST['id'];
		$data['name'] = $_POST['name'];
		$data['link'] = $_POST['link'];
		$flag = $link -> where($where) -> save($data);
		if($flag){
			$this -> success('更新成功！');
		}else{
			$this -> error('更新失败！');
		}
		
	}
	
	function delNav(){
		$link = M('Link');
		$where['id'] = $_GET['id'];
		$flag = $link -> where($where) -> delete();	 //删除本身
		if($flag){
			$this -> success('删除成功！');
		}else{
			$this -> error('删除失败！');
		
		}
	}
	
	function navPosition(){
		
		$link = M("Link");
		
		$nav = $link -> where("type=1 and lev=0") -> order("sort asc") -> select();		
		
		$this -> assign('nav',$nav);
		
		$this -> display();
		
	}
	
	
	/**
	 * 
	 +----------------------
	 *ajax异步修改位置
	 +----------------------
	 * 
	 * */
	function ajaxSetPosition(){
		if( isset($_POST['sort']) ){
			$link = M("Link");
			foreach($_POST['sort'] as $key => $vo){
				
				$data['sort'] = $key;
				$data['type'] = 1;
				$f = $link -> where("id=".$vo) -> save($data);
				
			}
				
		}
		
		$respone = "设置成功！";	
		echo $respone;
	}
	
}
?>