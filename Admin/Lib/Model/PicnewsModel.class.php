<?php 
 class PicnewsModel extends Model{
 	protected $_auto = array(
 		//array('path','tclm',1,'callback'),	
		array('date_time','time',1,'function'),
		array('hit',0,1),
		array('status',0,1),
		array('display',0,1),
 	);
 	
 	
 	/*function tclm(){
 		
 		$pid = isset($_POST[pid]) ? (int)$_POST[pid] : 0;
 		
 		if($pid==0){
 			return 0;	//×î¶¥²ã  pid 0 path 0 
 		}
 		
 		$list = $this-> where("id=$pid") ->find();
 		
//  		dump($list);
 		
 		$data = $list['path'].'-'.$list['id'];
 		
 		return $data;
 	}*/
 }

 ?>