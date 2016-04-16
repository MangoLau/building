<?php 
 class WordsModel extends Model{
 	protected $_auto = array(
		array('time','time',1,'function'),
 		array('ip','get_client_ip',Model::MODEL_BOTH,'function'),
 	);
 	
 	protected $_validate = array(
		array('author','require','请填写您的昵称！',1),
		array('content','require','请填写留言内容！',1),
	); 
 }
 ?>