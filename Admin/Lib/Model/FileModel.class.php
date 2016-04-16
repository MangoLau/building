<?php 
 class FileModel extends Model{
 	
 	protected $_validate=array(
 			
 		array('filename','require','显示名称不能为空！'),
 			
 	);
 	
 	protected $_auto = array(
 		//array('path','tclm',1,'callback'),
 	
		array('date_time','time',1,'function'),
		array('hit',0,1),
		array('status',1,1),
		array('display',1,1),
 		
 	);
 	
 }

 ?>