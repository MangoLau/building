<?php
class LinkAction extends CommonAction{
	
	

	function weiboLink(){
		$in = M('Link');
		$links = $in -> where('type=3') -> select();
		$this -> assign('links',$links);
		$this -> display();
	
	}

	function friendLink(){
		$in = M('Link');
		$links = $in -> where('type=2') -> select();
		$this -> assign('links',$links);
		$this -> display();
	
	}
	function addLink(){
		$in = M('Link');
	
		if($_POST['add']){
		
				if(empty($_POST['url'])){
					$in = M('Link');
					
					$data['type'] = $_POST['type'];	//logo设置在think_index_class的id
					$data['name'] = $_POST['name'];
					$data['link'] = $_POST['link'];
					$vo = $in -> add($data);
					if($vo){
						$this -> success('添加成功！');
					
					}else{
						$this -> error('添加失败！');
					}
				}else{
					//过滤检测
					$info = $this -> up();												
					if(isset($info)){
																		
						//------------------------------插入操作------------------------
						
						$in = M('Link');
						$data['file'] = $info[0]['savename'];

						$data['type'] = $_POST['type'];	//logo设置在think_index_class的id
						$data['name'] = $_POST['name'];
						$data['link'] = $_POST['link'];
						$vo = $in -> add($data);
						if($vo){
								$this -> success('添加成功！');
								
						}else{
								$this -> error('添加失败！');
						}
						
						//-------------------------------end 插入操作 --------------------------
					}else{
						$this -> error('上传文件有异常，请与系统管理员联系');
					}
				}
				
					
			}
			
		//$this -> assign('links', $links);
		//$this -> display();

	}

	function updateLink(){

			$in = M('Link');
			$data['name'] = $_POST['name'];
			$data['link'] = $_POST['link'];					
			$rs = $in -> where('id='.$_POST['id']) -> save($data);
			if($rs){
				$this -> success('更新成功！');
								
			}else{
				$this -> error('更新失败！');
			}
	
	}
	function delLink(){
		
			$in = M('Link');
			$rs = $in -> where('id='.$_GET['id']) -> delete();

			
			if($rs){
				$this -> success('删除成功！');
								
			}else{
				$this -> error('删除失败！');
			}
		

	
	}
	//过滤检测函数
	private function up(){
		import('ORG.Net.UploadFile');
		$upload=new UploadFile();
		$upload->maxSize= '10485760';  //10M //是指上传文件的大小，默认为-1,不限制上传文件大小bytes
		$upload->savePath= './Public/upload/';       //上传保存到什么地方？路径建议大家已主文件平级目录或者平级目录的子目录来保存
		$upload->saveRule=time;    //上传文件的文件名保存规则  time uniqid  com_create_guid  uniqid
		//$upload->autoCheck=false   ;  //是否自动检测附件
		$upload->uploadReplace=true;     //如果存在同名文件是否进行覆盖
		$upload->allowExts=array('jpg','jpeg','png','gif');     //准许上传的文件后缀
		$upload->allowTypes=array('image/png','image/jpg','image/pjpeg','image/gif','image/jpeg');  //检测mime类型
		$upload->thumb=true;   //是否开启图片文件缩略
		$upload->thumbMaxWidth='1000';  //以字串格式来传，如果你希望有多个，那就在此处，用,分格，写上多个最大宽
		$upload->thumbMaxHeight='700';	//最大高度
		$upload->thumbPrefix='b_';//缩略图文件前缀
		//$upload->thumbSuffix='_s,_m';  //文件后缀
		//$upload->thumbPath='' ;  // 如果留空直接上传至
		//$upload->thumbFile   在数据库当中也存一个文件名即可
		$upload->thumbRemoveOrigin=true;  //如果生成缩略图，是否删除原图
		// 			$upload->autoSub=true;   //是否使用子目录进行保存上传文件
		// 			$upload->subType='hash' ;  //子目录创创方式默认为hash 也可以设为date
		// 			$upload->dateFormat='';  //子目录方式date的指定日期格式
		// 			$upload->hashLevle='2';
			
		//upload()  如果上传成功，返回tur,失败返回false
			
		if($upload->upload()){
			//局部变量，你可以在此处，保存到一个超全局变量当中去进行返回
			$info=$upload->getUploadFileInfo();
			return $info;
		}else{
			//是专门来获取上传的错误信息的
			$this->error($upload->getErrorMsg());

		}
	}
	
	

	
	
	
}
?>