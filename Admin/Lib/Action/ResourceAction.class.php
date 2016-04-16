<?php
class ResourceAction extends CommonAction{
	//视频模块
	function video(){
		$resource=M('Resource');
		import("ORG.Util.Page"); //  导入分页类
		$ws['type']='1';
		$count  = $resource -> where($ws) -> count();
		$Page       = new Page($count,10); //  实例化分页类 传入总记录数和每页显示的记录数
		$page       = $Page->show(); //  分页显示输出
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		$str = $resource -> where($ws) -> limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($str as $val){
			$comments=M('Comments');
			$where_c['r_id']=$val['id'];
			$num  = $comments -> where($where_c) -> count();
			$list[]=array("id"=>"{$val['id']}","name"=>"{$val['name']}","url"=>"{$val['url']}","remark"=>_sort($val['remark'],8),"jian"=>"{$val['remark']}","status"=>"{$val['status']}","time"=>date('Y-m-d',$val['time']),"ping"=>$num);
		}
		$this->assign('page',$page);
		$this->assign('list',$list);
		
		if($_POST[add_video]){
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->allowExts = array('flv','wmv','rm','rmvb','avi','AVI','mpeg','mp4');			
			$upload->savePath = './Public/video/';
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$vedio=M('Resource');
				$data['name']=$info[0]['name'];
				$data['remark']=$_POST['remark'];
				$data['type']='1';
				$data['time']=time();
				$data['url']=$info[0]['savename'];
				$lastid=$vedio->add($data);
				if($lastid){
					$this->success('添加成功!');
				}else{
					$this->error('添加失败!');
				}
			}
		}
		
		if ($_POST[update_vedio]){
			$vedio=M('Resource');
			$xiu['id']=$_POST['id'];
			$datas['remark']=$_POST['remark'];
			$lastid=$vedio->where($xiu)->save($datas);
			if($lastid){
				$this->success('更新成功!');
			}else{
				$this->error('更新失败!');
			}
		}
		$this->display();
	}
	
	//视频禁用
	function vedioForbid(){
	
		$vedio = M('Resource');
		$where[id] = $_GET[id];
		$data[status] = 0;
		$l = $vedio -> where($where) -> save($data);
		if($l){
			$this -> success('禁用成功！');
		}else{
			$this -> error('禁用失败！');
		}
	}
	
	//视频启用
	function vedioStart(){
	
		$vedio = M('Resource');
		$where[id] = $_GET[id];
		$data[status] = 1;
		$l = $vedio -> where($where) -> save($data);
		if($l){
			$this -> success('启用成功！');
		}else{
			$this -> error('启用失败！');
		}
	}
	
	//视频删除
	function vedioDelete(){
		$vedio = M('Resource');
		$where['id'] = $_GET['id'];
		$m=$vedio->field('url')->where($where)->find();
		$m['url']='./Public/video/'.$m['url'];
		if($vedio -> where($where) -> delete()){
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
	
	//图片模块
	function photo(){
		$resource=M('Resource');
		import("ORG.Util.Page"); //  导入分页类
		$ws['type']='2';
		$count  = $resource -> where($ws) -> count();
		$Page       = new Page($count,10); //  实例化分页类 传入总记录数和每页显示的记录数
		$page       = $Page->show(); //  分页显示输出
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		$str = $resource -> where($ws) -> limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($str as $val){
			$comments=M('Comments');
			$where_c['r_id']=$val['id'];
			$num  = $comments -> where($where_c) -> count();
			$list[]=array("id"=>"{$val['id']}","name"=>"{$val['name']}","url"=>"{$val['url']}","remark"=>_sort($val['remark'],8),"jian"=>"{$val['remark']}","status"=>"{$val['status']}","time"=>date('Y-m-d',$val['time']),"ping"=>$num);
		}
		$this->assign('page',$page);
		$this->assign('list',$list);
		
		if($_POST[add_photo]){
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->allowExts = array('jpg', 'gif', 'png', 'jpeg','bmp');
			$upload->savePath = './Public/photo/';
			if(!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
				$info =  $upload->getUploadFileInfo();
				$photo=M('Resource');
				$data['name']=$info[0]['name'];
				$data['remark']=$_POST['remark'];
				$data['type']='2';
				$data['time']=time();
				$data['url']=$info[0]['savename'];
				$lastid=$photo->add($data);
				if($lastid){
					$this->success('添加成功!');
				}else{
					$this->error('添加失败!');
				}
			}
		}
		
		if ($_POST[update_photo]){
			$vedio=M('Resource');
			$xiu['id']=$_POST['id'];
			$datas['remark']=$_POST['remark'];
			$lastid=$vedio->where($xiu)->save($datas);
			if($lastid){
				$this->success('更新成功!');
			}else{
				$this->error('更新失败!');
			}
		}
		
		$this->display();
	}
	
	//图片删除
	function photoDelete(){
		$vedio = M('Resource');
		$where['id'] = $_GET['id'];
		$m=$vedio->field('url')->where($where)->find();
		$m['url']='./Public/photo/'.$m['url'];
		if($vedio -> where($where) -> delete()){
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
	
	//查看评论
	function lookcomment(){
		$comment=M('Comments');
		import("ORG.Util.Page"); //  导入分页类
		$w['r_id']=$_GET['id'];
		$count  = $comment ->where($w)  -> count();
		$Page       = new Page($count,10); //  实例化分页类 传入总记录数和每页显示的记录数
		$page       = $Page->show(); //  分页显示输出
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		$str = $comment ->where($w)  -> limit($Page->firstRow.','.$Page->listRows)->select();
		foreach ($str as $val){
			$person=M('Person');
			$where['id']=$val['p_id'];
			$n=$person->field('username')->where($where)->find();
			$list[]=array("id"=>"{$val['id']}","name"=>"{$n['username']}","content"=>"{$val['content']}","short"=>_sort($val['content'], 12),"time"=>date('Y-m-d',$val['time']),"status"=>"{$val['status']}");
		}
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this->display();
	}
	
	//评论禁用
	function commentForbid(){
	
		$comment=M('Comments');
		$where[id] = $_GET[id];
		$data[status] = 0;
		$l = $comment -> where($where) -> save($data);
		if($l){
			$this -> success('禁用成功！');
		}else{
			$this -> error('禁用失败！');
		}
	}
	
	//评论启用
	function commentStart(){
	
		$comment=M('Comments');
		$where[id] = $_GET[id];
		$data[status] = 1;
		$l = $comment -> where($where) -> save($data);
		if($l){
			$this -> success('启用成功！');
		}else{
			$this -> error('启用失败！');
		}
	}
	
	//评论删除
	function commentDelete(){
		$comment=M('Comments');
		$where[id] = $_GET[id];
		$l = $comment -> where($where) -> delete();
		if($l){
			$this -> success('删除成功！');
		}else{
			$this -> error('删除失败！');
		}
	}
}
?>