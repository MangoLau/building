<?php 

 class NewsclassModel extends Model{
 	protected $_validate = array(
 			array('name','require','没填写分类名称！',1),
 	);
 	protected $_auto = array(
 		array('path','tclm',3,'callback'),		
 	);
 	
 	
 	function tclm(){
 		
 		$pid = isset($_POST[pid]) ? (int)$_POST[pid] : 0;
 		
 		if($pid==0){
 			return 0;	//
 		}else{
 			$list = $this-> where("c_id=$pid") ->find();
 		
	 		$data = $list['path'].'-'.$list['c_id'];
	 		
	 		return $data;
 		}
 		
 	
 	}
 }

 ?>