<?php
class TeacherModel extends Model{
	//自动验证
	protected $_validate=array(
			//array(验证字段,验证规则,错误提示,[验证条件,附加规则,验证时间])
			array('name','require','姓名必须填！'), //默认情况下用正则进行验证
			array('name','','该老师记录已存在！',0,'unique',1), // 在新增的时候验证username字段是否唯一
			array('education','require','学历必须填！'), //默认情况下用正则进行验证
			array('email','require','邮箱必须填！'), //默认情况下用正则进行验证
			array('email','email','邮箱格式不正确'), //验证邮箱（email）格式
			array('tell','/(?:13\d{1}|15[03689])\d{8}$/','手机号码不正确！',0,'regex',1), //手机号码是否符合
	);
}
?>