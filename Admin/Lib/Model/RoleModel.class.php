<?php

class RoleModel extends CommonModel{
	public $_validate	=	array(
		array('name','/^[a-z\d]\w{3,}$/i','角色名必须是字母或数字，且3位以上！'),
		//array('password','require','密码必须'),
		//array('nickname','require','昵称必须'),
		//array('repassword','require','确认密码必须'),
		//array('repassword','password','确认密码不一致',self::EXISTS_VAILIDATE,'confirm'),
		array('username','','角色已经存在',0,'unique',1), // 在新增的时候验证username字段是否唯一
		//array('name','','角色已经存在',self::EXISTS_VAILIDATE,'unique',self::MODEL_INSERT),	//self指向当前类的指针
		);

	public $_auto		=	array(
		
		//array('password','md5',self::MODEL_BOTH,'function'),
		//array('register_time','time',self::MODEL_INSERT,'function'),	//调用php系统函数time()
		//array('last_login_time','time',self::MODEL_BOTH,'function'),
		//array('last_login_ip','get_client_ip',self::MODEL_BOTH,'function'),
		);

	public $_link = array(
		'RoleUser' => array(
			'mapping_type' => HAS_MANY,
			'foreign_key' => 'user_id',
			
		),
	);
}


?>