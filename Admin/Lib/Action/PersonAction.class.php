<?php
class PersonAction extends CommonAction{
	function index(){
		$person=M('Person');
		import("ORG.Util.Page"); //  导入分页类
		$count  = $person -> count();
		$Page       = new Page($count,10); //  实例化分页类 传入总记录数和每页显示的记录数
		$page       = $Page->show(); //  分页显示输出
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		$str = $person -> limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($str as $val){
			if($val['sex']=='1'){
				$val['sex']='男';
			}else{
				$val['sex']='女';
			}
			$list[]=array("id"=>"{$val['id']}","username"=>"{$val['username']}","name"=>"{$val['name']}","sex"=>"{$val['sex']}","login_count"=>"{$val['login_count']}","status"=>"{$val['status']}","register_time"=>"{$val['register_time']}","last_login_time"=>"{$val['last_login_time']}","last_login_ip"=>"{$val['last_login_ip']}");
		}
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->display();
	}
	
	//会员禁用
	function personForbid(){
	
		$person = M('Person');
		$where[id] = $_GET[id];
		$data[status] = 0;
		$l = $person -> where($where) -> save($data);
		if($l){
			$this -> success('禁用成功！');
		}else{
			$this -> error('禁用失败！');
		}
	}
	
	//会员启用
	function personStart(){
	
		$person = M('Person');
		$where[id] = $_GET[id];
		$data[status] = 1;
		$l = $person -> where($where) -> save($data);
		if($l){
			$this -> success('启用成功！');
		}else{
			$this -> error('启用失败！');
		}
	}
	
	//会员删除
	function personDelete(){
	
		$person = M('Person');
		$where[id] = $_GET[id];
		$l = $person -> where($where) -> delete();
		if($l){
			$this -> success('删除成功！');
		}else{
			$this -> error('删除失败！');
		}
	}
}
?>