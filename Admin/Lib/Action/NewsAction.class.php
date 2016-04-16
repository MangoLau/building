<?php 
class NewsAction extends CommonAction{
	
	/*
		作用：添加新闻
	*/
	function newsAdd(){
		/* $fck = $this -> _fck(); */
		$class_list = $this -> classList();
		if($_POST['insert_base']){
			$base = D('Newsbase');
			$file = M('File');
			
			$flag = 1;
			//不开启表单令牌验证，但是用这个方法可以进行数据的自动获取和自动验证
			if(!$base -> create()){
				$flag = 0;
				$this -> error($base -> getError());
				exit();
			}	
			
			$base -> date_time = strtotime($_POST['date_time']);
			if(!$id = $base -> add()){
				
				$flag = 0;
			}

			if($_POST['img_name']){
				for($i=0;$i<count($_POST['img_name']);$i++){
					if($_POST['img_name'][$i] != 'undefined'){
						$data['type'] = 1;
						$data['n_id'] = $id;
						$data['size'] = $_POST['img_size'][$i];
						$data['title'] = $_POST['img_title'][$i];
						$data['name'] = $_POST['img_name'][$i];
						if(!$file -> add($data)){
							$flag = 0;
						}
					}
				}
			}

			if($_POST['attach_name']){
				for($i=0;$i<count($_POST['attach_name']);$i++){
					if($_POST['attach_name'][$i] != 'undefined'){
						$data['type'] = 2;
						$data['n_id'] = $id;
						$data['size'] = $_POST['attach_size'][$i];
						$data['title'] = $_POST['attach_title'][$i];
						$data['name'] = $_POST['attach_name'][$i];
						if(!$file -> add($data)){
							$flag = 0;
						}
					}
				}
			}
			if($flag == 1){
				$this -> success("新闻发表成功！");
			}else{
				$this -> error("新闻发表失败！");
			}
		}
		
		
		$user = $this -> _userInfo();
		$CK =  editor();
		$this -> assign('class_list',$class_list);
		$this -> assign('fck',$CK);
		
		$this -> assign('user',$user);
		$this -> display();
	}
				

	//未审核新闻列表
	function unauditedNews(){
		
		import("ORG.Util.Page"); //  导入分页类
		$base = M('Newsbase');
		$file = M('File');
		if($_GET['c_id'] != 0){
			$c_id_sql = 'and think_newsbase.c_id='.$_GET['c_id'];
		}else{
			$c_id_sql = '';
		}
		$count = $base ->  join(' think_newsclass ON think_newsclass.c_id = think_newsbase.c_id' ) -> where('think_newsbase.status=0 '.$c_id_sql) -> order('istop desc,id desc') -> count();
		$Page       = new Page($count,10); //  实例化分页类 传入总记录数和每页显示的记录数
		$page = $Page -> show();
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		$list = $base -> join(' think_newsclass ON think_newsclass.c_id = think_newsbase.c_id ' ) -> where('think_newsbase.status=0 '.$c_id_sql) -> order('istop desc,id desc')-> limit($Page->firstRow.','.$Page->listRows) -> select();

		foreach($list as $k => $v){
			$list[$k]['img'] = $file -> where('type=1 and n_id='.$v['id']) ->  select();
			$list[$k]['attach'] = $file -> where('type=2 and n_id='.$v['id']) ->  select();

		
		}
		
		$clist = $this -> classList();

		$this->assign('page',$page ); //  赋值分页输出
		$this -> assign('list',$list);
		$this -> assign('clist',$clist);
		$this -> assign('c_id',$_GET['c_id']);
		$this -> display();
	}
	
	//未审核新闻审核通过
	function passNews(){
		
		$base = M('newsbase');
		
		$where = array();
		$where['id'] = $_GET['pass_id'];
		$where['status'] = 1;
		$where['audit_time'] = time();
		if($base -> save($where)){
			$this -> success('审核成功！');
		}else{
			$this -> error('审核失败！');
		}	
	}
	
	//新闻删除
	function newsRemove(){
		
		$base = M('newsbase');
		
		$where = array();
		$where['id'] = $_GET['remove_id'];
		
		if($base -> where($where) -> delete()){
		
			$this -> success('删除成功！');

		}else{
		
			$this -> error('删除失败！');

		}
		
	}
	
	//新闻修改
	function newsAlter(){
		$base = D('Newsbase');
		$file = M('File');
		if($_POST['alter']){
		
			if($base -> create()){//第二个if
				$id = $_POST['id'];
				$file = M('File');
				$file -> where('n_id='.$id) -> delete(); //先删掉再重新添加，这样做逻辑简单				
				$flag = 1;
				//不开启表单令牌验证，但是用这个方法可以进行数据的自动获取和自动验证
				if(!$base -> create()){
					$flag = 0;
					$this -> error($base -> getError());
					exit();
				}	
				
				$base -> date_time = strtotime($_POST['date_time']);
				$base -> audit_time = strtotime($_POST['audit_time']);
				if(!$base -> save()){
					
					$flag = 0;
				}

				if($_POST['img_name']){
					for($i=0;$i<count($_POST['img_name']);$i++){
						if($_POST['img_name'][$i] != 'undefined'){
							$data['type'] = 1;
							$data['n_id'] = $id;
							$data['title'] = $_POST['img_title'][$i];
							$data['name'] = $_POST['img_name'][$i];
							if(!$file -> add($data)){
								$flag = 0;
							}
						}
					}
				}

				if($_POST['attach_name']){
					for($i=0;$i<count($_POST['attach_name']);$i++){
						if($_POST['attach_name'][$i] != 'undefined'){
							$data['type'] = 2;
							$data['n_id'] = $id;
							$data['title'] = $_POST['attach_title'][$i];
							$data['name'] = $_POST['attach_name'][$i];
							if(!$file -> add($data)){
								$flag = 0;
							}
						}
					}
				}
				if($flag == 1){
					$this -> success("新闻修改成功！");
				}else{
					$this -> error("新闻修改失败！");
				}

			}else{
				$this -> error($base -> getError());
			}//end 第二个if
			
		}
		

		$blist = $base -> join(' think_newsclass ON think_newsclass.c_id = think_newsbase.c_id' )  -> where('id='.$_GET['alter_id']) -> select();
		
		
		$imgs = $file -> where('type=1 and n_id='.$_GET['alter_id']) -> select();
		$attachs = $file -> where('type=2 and n_id='.$_GET['alter_id']) -> select();
		$CK = editor('content',$blist[0]['content']);
		$class = $this -> classList();
			
		$this -> assign('clist',$class);
		$this -> assign('blist',$blist);
		$this -> assign('fck',$CK);
		$this -> assign('attachs',$attachs);
		$this -> assign('imgs',$imgs);

		
		$this -> display();
		
		
	}
	
		
	
	//已审核新闻列表
	function newsList(){
		
		import("ORG.Util.Page"); //  导入分页类
		$base = M('Newsbase');
		$file = M('File');
		if($_GET['c_id']){
			$c_id_sql = ' and think_newsbase.c_id='.$_GET['c_id'];
		}else{
			$c_id_sql = '';
		}
		$count =  $base -> join(' think_newsclass ON think_newsclass.c_id = think_newsbase.c_id ') -> where('think_newsbase.status=1'.$c_id_sql) -> order('istop desc,id desc') -> count();
		$Page       = new Page($count,10); //  实例化分页类 传入总记录数和每页显示的记录数
		$page = $Page -> show();
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		$list = $base -> join(' think_newsclass ON think_newsclass.c_id = think_newsbase.c_id ')-> where('think_newsbase.status=1'.$c_id_sql) -> order('istop desc,id desc') ->  limit($Page->firstRow.','.$Page->listRows) -> select();
		
		foreach($list as $k => $v){
			
			$list[$k]['img'] = $file -> where('type=1 and n_id='.$v['id']) ->  select();
			$list[$k]['attach'] = $file -> where('type=2 and n_id='.$v['id']) ->  select();

		}
		$clist = $this -> classList();

		$this->assign('page',$page ); //  赋值分页输出
		$this -> assign('list',$list);
		$this -> assign('clist',$clist);
		$this -> assign('c_id',$_GET['c_id']);
		$this -> display();
	
		
	}
	
	//异步图片上传
	function ajaxImgUp(){
		
		$file = M('File');
		
		//过滤检测
		
		$info = $this -> up();
		//dump($info);
		if($info[0]['flag'] != false){
			$info[0]['mes'] = '上传成功！';
			echo json_encode($info[0]);

		}else{
			//$info[0]['mes'] = '预览失败！';
			$info[0]['mes'] = $info[0]['error'];
			echo json_encode($info[0]);
		}
	
	
	}

	//异步附件上传
	function ajaxAttachUp(){
		
		$file = M('File');
		
		//过滤检测
		
		$info = $this -> upAttach();
		if($info[0]['flag'] != false){
			$info[0]['mes'] = '上传成功！';
			echo json_encode($info[0]);

		}else{
			$info[0]['mes'] = $info[0]['error'];
			echo json_encode($info[0]);
		}
	
	
	}

	/*
		异步根据条件获取新闻内容
	*/
	function ajaxNews(){
		import("ORG.Util.Page"); //  导入分页类
		$base = M('Newsbase');
				
		if($_GET['c_id']){
			$c_id_sql = 'and c_id='.$_GET['c_id'];
		}else{
			$c_id_sql = '';
		}
		switch($_GET['img_val']){
			case 1 : $img_sql = 'type=1';break;	//有图片
			case -1 : $img_sql = 'type!=1 and type!=2';break;	//没有图片，但是也要排除附件的，留下left join的NULL部分
			case 0 : $img_sql = 'type!=2';break;	//图片和没图片都行，排除附件的情况
		}

		switch($_GET['attach_val']){
			//left join 之后用and连接会返回and后面的条件和null的行，如果用where则直接过滤，null的不会被保留
			case 2 : $attach_sql = 'type=2';break;
			case -1 : $attach_sql = 'type!=2 and type!=1';break;
			case 0 : $attach_sql = 'type!=1';break;
		}

		//只要有一个为零那肯定是与操作
		if($_GET['img_val']==0 && $_GET['attach_val']==0){
			
		}else{
			$list = $base -> query("SELECT * FROM `think_newsbase` LEFT JOIN think_file on n_id=id and ".$img_sql." and id in (select id from think_newsbase left join think_file on id=n_id and ".$attach_sql.") ");
		}
		$this -> display('newsList');
	}

	function newsSortList(){

		if($_POST['add_class']){
			$sort = D('Newsclass');
			$vo = $sort -> create();
			if($vo){
				if($sort -> add()){
					$this -> success('添加成功！');
				}else{
					$this -> error('添加失败！');
				}
			
			
			}else{
				$this -> error($sort -> getError());
			}
		}
		
		
		$class = $this -> classList();
			
		
		$this -> assign('alist',$class);
	
		$this -> display();
	}
	
	//新闻分类操作
	function newsClassAdd(){				
			$sort = D('Newsclass');
			$vo = $sort -> create();
			if($vo){
				if($sort -> add()){
					$this -> success('添加成功！');
				}else{
					$this -> error('添加失败！');
				}
			
			
			}else{
				$this -> error($sort -> getError());
			}						
	}
	
	//新闻分类更新
	function newsClassUpdate(){
	
		$class = M('Newsclass');

		$where['c_id'] = $_POST['c_id'];
		$data['name'] = $_POST['name'];
		
		$flag = $class -> where($where) -> save($data);
		if($flag){
			$this -> success('更新成功！');
		
		}else{
			$this -> error('更新失败！');
			
		}

	}
	
	//新闻分类删除
	function newsClassDelete(){
		
		$class = M('Newsclass');
				
		$where['c_id'] = $_GET['c_id'];
		
 		$bpath = $class -> field("c_id,path,concat(path,'-',c_id) as bpath") -> where($where) -> select();

		$flag = $class -> where($where) -> delete();	 //删除本身
		
		if($flag){

			$data['path'] = array('like',$bpath[0]['bpath'].'%');		//删除一下的子类
			$flag = $class -> where($data) -> delete();
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
		$upload->savePath='./Public/upload/news/';       //上传保存到什么地方？路径建议大家已主文件平级目录或者平级目录的子目录来保存
		$upload->saveRule=time;    //上传文件的文件名保存规则  time uniqid  com_create_guid  uniqid
		//$upload->autoCheck=false   ;  //是否自动检测附件
		$upload->uploadReplace=true;     //如果存在同名文件是否进行覆盖
		$upload->allowExts=array('jpg','jpeg','png','gif');     //准许上传的文件后缀
		$upload->allowTypes=array('image/png','image/jpg','image/pjpeg','image/gif','image/jpeg');  //检测mime类型
		$upload->thumb=true;   //是否开启图片文件缩略
		$upload->thumbMaxWidth='600,140';  //以字串格式来传，如果你希望有多个，那就在此处，用,分格，写上多个最大宽
		$upload->thumbMaxHeight='300,94';	//最大高度
		$upload->thumbPrefix='b_,s_';//缩略图文件前缀
		//$upload->thumbSuffix='_s,_m';  //文件后缀
		//$upload->thumbPath='' ;  // 如果留空直接上传至
		//$upload->thumbFile   在数据库当中也存一个文件名即可
		$upload->thumbRemoveOrigin=false;  //如果生成缩略图，是否删除原图
		// 			$upload->autoSub=true;   //是否使用子目录进行保存上传文件
		// 			$upload->subType='hash' ;  //子目录创创方式默认为hash 也可以设为date
		// 			$upload->dateFormat='';  //子目录方式date的指定日期格式
		// 			$upload->hashLevle='2';
			
		//upload()  如果上传成功，返回tur,失败返回false
			
		if($upload->upload()){
			//局部变量，你可以在此处，保存到一个超全局变量当中去进行返回
			$info=$upload->getUploadFileInfo();
			$info[0]['flag'] = true;
			return $info;
		}else{
			//是专门来获取上传的错误信息的
			//$this->error($upload->getErrorMsg());
			$info[0]['flag'] = false;
			$info[0]['error'] = $upload->getErrorMsg();
			return $info;
		}
	}

	//过滤检测函数
	private function upAttach(){
		import('ORG.Net.UploadFile');
		$upload=new UploadFile();
		$upload->maxSize= '10485760';//10485760';  //10M //是指上传文件的大小，默认为-1,不限制上传文件大小bytes
		$upload->savePath='./Public/upload/news/';       //上传保存到什么地方？路径建议大家已主文件平级目录或者平级目录的子目录来保存
		$upload->saveRule=time;    //上传文件的文件名保存规则  time uniqid  com_create_guid  uniqid
		//$upload->autoCheck=false   ;  //是否自动检测附件
		$upload->uploadReplace=true;     //如果存在同名文件是否进行覆盖
		$upload->allowExts=array('txt','ppt','xlsx','docx','pptx','png','gif','jpeg','jpg','doc','rar','xls','zip','gz');     //准许上传的文件后缀
		$upload->allowTypes=array('text/plain','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.ms-powerpoint','image/png','image/pjpeg','application/msword','image/x-png','image/x-png','image/gif','image/jpeg','application/vnd.ms-excel','application/octet-stream','application/zip','application/x-gzip','application/x-zip-compressed');  //检测mime类型
		//$upload->thumb=true;   //是否开启图片文件缩略
		//$upload->thumbMaxWidth='600';  //以字串格式来传，如果你希望有多个，那就在此处，用,分格，写上多个最大宽
		//$upload->thumbMaxHeight='300';	//最大高度
		//$upload->thumbPrefix='b_';//缩略图文件前缀
		//$upload->thumbSuffix='_s,_m';  //文件后缀
		//$upload->thumbPath='' ;  // 如果留空直接上传至
		//$upload->thumbFile   在数据库当中也存一个文件名即可
		//$upload->thumbRemoveOrigin=true;  //如果生成缩略图，是否删除原图
		// 			$upload->autoSub=true;   //是否使用子目录进行保存上传文件
		// 			$upload->subType='hash' ;  //子目录创创方式默认为hash 也可以设为date
		// 			$upload->dateFormat='';  //子目录方式date的指定日期格式
		// 			$upload->hashLevle='2';
			
		//upload()  如果上传成功，返回tur,失败返回false
		
		if($upload->upload()){
			//局部变量，你可以在此处，保存到一个超全局变量当中去进行返回
			$info=$upload->getUploadFileInfo();
			$info[0]['flag'] = true;
			return $info;
		}else{
			//是专门来获取上传的错误信息的
			//$this->error($upload->getErrorMsg());
			$info[0]['flag'] = false;
			$info[0]['error'] = $upload->getErrorMsg();
			return $info;
		}
	}
	
	
	
	//未审核图片新闻删除
	function picNewsRemove(){	
		$picnews = M('picnews');
	
		$remove_where['id'] = $_GET['remove_id'];
		$arr =  $picnews -> where($remove_where) -> select();
		
		if($picnews -> where($remove_where) -> delete()){
			
			$this -> success('删除成功！');
			return;
		}else{
	
			$this -> error('删除失败！');
			return;
		}
	}
	
	
	//已审核图片新闻删除
	function picNewsDelete(){
		if($_GET['id']){
			// 			dump("!");
			$where['id'] = $_GET['id'];
			$picnews = M("picnews");
			if($picnews -> where($where) -> delete()){
				$this -> success('删除成功！');
			}else{
				$this -> error('删除失败！');
			}
	
		}
	
	}
	
	
	
	
	
	/*
		作用 ： 显示新闻类别
		return array
	*/
	function classList(){
	
		$class = M('Newsclass');
			
		$list = $class -> field("c_id,name,pid,path,concat(path,'-',c_id) as bpath")->where('type=0') -> order('bpath') -> select();
		
		foreach($list as $key => $value){
			$list[$key]['count'] = count(explode('-', $value['bpath']));
		}
		
		return $list;
	}


	/*
	 +-------
	 *下载文件函数
	 +-------
	 */
	function downfile(){
		$base = M("Newsbase");
		$file_arr = $base -> where('id='.$_GET['id']) -> select();
		$this -> _file($file_arr[0]);
		//dump($file_arr);
		
	}
	
	/**
	 +-------
	 *下载文件所调用的函数，设置浏览器头部信息
	 *参数：$file : 传入的文件信息
	 +-------
	 */
	protected function _file($file){
		if(!file_exists($file['url'])){
			exit('没有该文件');
		}else{
			$fp=fopen($file['url'],"r");
		
			//从路径获得文件名
			$arr = explode("/",$file['url']);
			$filename = $arr[count($arr)-1];			
			//下载文件需要用到的头			
			Header("Content-type: "."application/octet-stream;charset=utf-8");//告诉浏览器是文件流格式
			Header("Content-Disposition: attachment; filename= ".$filename);
			Header("Accept-Ranges: bytes");
			Header("Accept-Length: ".filesize($file['url']));
			
			echo fread($fp,$file['size']); //把内容输出到浏览器
			
			fclose($fp);
			
		}
		
		
	}

	//新闻搜索
	function searchNews(){
		if($_POST['searchNews']){
			
			import("ORG.Util.Page"); //  导入分页类
			$base = M('Newsbase');
			$file = M('File');
			$class = M('Newsclass');
			
			$count = $base ->  where("(think_newsbase.status=1 and think_newsbase.news_title like '%".$_POST['searchText']."%') or (think_newsbase.status=1 and think_newsbase.content like '%".$_POST['searchText']."%')")-> order("date_time desc") -> count();
			$Page = new Page($count,5); //  实例化分页类 传入总记录数和每页显示的记录数
			$page = $Page -> show();
			$list = $base ->  where("(think_newsbase.status=1 and think_newsbase.news_title like '%".$_POST['searchText']."%') or (think_newsbase.status=1 and think_newsbase.content like '%".$_POST['searchText']."%')")-> order("date_time desc")  -> limit($Page->firstRow.','.$Page->listRows) -> select();	//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		
			foreach($list as $k => $v){
				$s = $class -> where('c_id='.$v['c_id']) -> select();
				$list[$k]['cname'] = $s[0]['name'];
			}
			
			$this -> assign('list',$list);

			$this -> assign('page',$page);

			//$this -> assign('nav',$nav);
		}	

		$this -> display();
	}
}
?>