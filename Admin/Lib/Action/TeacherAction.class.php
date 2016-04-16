<?php
class TeacherAction extends CommonAction{
	function index(){
		$teacher=M('Teacher');
		import("ORG.Util.Page"); //  导入分页类
		$count  = $teacher -> count();
		$Page       = new Page($count,10); //  实例化分页类 传入总记录数和每页显示的记录数
		$page       = $Page->show(); //  分页显示输出
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		$str = $teacher -> limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($str as $val){
			if($val['sex']=='1'){
				$val['sex']='男';
			}else{
				$val['sex']='女';
			}
			$list[]=array("id"=>"{$val['id']}","name"=>"{$val['name']}","sex"=>"{$val['sex']}","education"=>"{$val['education']}","email"=>"{$val['email']}","tell"=>"{$val['tell']}");
		}
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->display();
	}
	
	//添加名单
	function addTeacher(){
		if ($_POST[add_teacher]){
			$teacher=D('Teacher');
			$teacher->create();  //自动创建数据
			if(!$teacher->create()){
				$this->error($teacher->getError());
			}
			$lastid=$teacher->add();
			if($lastid){
				$this -> success("添加成功！");
			}else{
				$this -> error("添加失败！");
			}
		}
		$this->display();
	}
	
	//更新名单
	function teacherInfoAlter(){
		$teacher=M('Teacher');
		$where['id']=$_GET['id'];
		$list=$teacher->where($where)->select();
		$this->assign('list',$list);
		
		if($_POST[update_teacher]){
			$teacher=D('Teacher');
			$teacher->create();  //自动创建数据
			if(!$teacher->create()){
				$this->error($teacher->getError());
			}
			$wherex['id']=$_POST['id'];
			$lastid=$teacher->where($wherex)->save();
			if($lastid){
				$this -> success("添加成功！");
			}else{
				$this -> error("添加失败！");
			}
		}
		
		$this->display();
	}
	
	//删除名单
	function teacherDelete(){
		$teacher=M('Teacher');
		$where['id'] = $_GET['id'];
		$m=$teacher->field('url')->where($where)->find();
		if(empty($m['url'])){
			if($teacher -> where($where) -> delete()){
				$this -> success('删除成功！');
			}else{
				$this -> error('删除失败！');
			}
		}else{
			$m['url']='./Public/uface/'.$m['url'];
			if($teacher -> where($where) -> delete()){
				if(file_exists($m['url'])){
					unlink($m['url']);
					$this -> success('删除成功！');
				}else{
					$this->error('不存在该文件!');
				}
			}else{
				$this -> error('删除失败！');
			
			}
		}
	}
}
?>