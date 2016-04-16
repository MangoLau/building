<?php 
 class NewsbaseModel extends Model{
 	protected $_auto = array(
		array('hit',0,1),
		array('status',0,1),
 	);
 	
 	protected $_validate = array(
		array('news_title','require','没填写新闻标题！',1),
	); 

 }

 ?>