<?php
//主验证用户登录和退出的
//不需要通过认证管理器 的方法都可以写在这里
class PublicAction extends Action{
		
	// 检查用户是否登录
	
	protected function checkUser() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->assign('jumpUrl','Public/login');
			$this->error('没有登录');
		}
	}
	
	// 菜单页面
	public function menu() {
		$this->checkUser();
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			//显示菜单项
			$menu  = array();
			if(isset($_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]])) {
					
				//如果已经缓存，直接读取缓存
				$menu   =   $_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]];
			}else {
				//读取数据库模块列表生成菜单项
				$node    =   M("Node");
				
				$id	=	$node->getField("id");

				$where['level']=2;
				$where['status']=1;
				$where['pid']=$id;
				$where['display'] = 1;
				$list	=	$node->where($where)->field('id,name,title')->order('sort asc')->select();
				$accessList = $_SESSION['_ACCESS_LIST'];	//在RBAC类里面获取的该用户的所有权限列表
				foreach($list as $key=>$module) {
					if(isset($accessList[strtoupper(APP_NAME)][strtoupper($module['name'])]) || $_SESSION['administrator']) {
						//设置模块访问权限
						$module['access'] =   1;
						$menu[$key]  = $module;
						if($_SESSION['administrator']){
							$wherep['pid']=$module['id'];
							$str=$node->field('id,name')->where($wherep)->select();
							foreach ($str as $val){
								$w['id']=$val['id'];
								$w['status']=1;
								$w['display'] = 1;
								$listzi	=	$node->where($w)->field('id,name,title')->order('sort asc')->select();
								$menu[$key][son][$val['name']] = $listzi;
							}
						}else{
							//自己加的的部分level3
							foreach($accessList[strtoupper(APP_NAME)][strtoupper($module['name'])] as $k => $val){
								$w['id']=$val;
								$w['status']=1;
								$w['display'] = 1;
								$listzi	=	$node->where($w)->field('id,name,title')->order('sort asc')->select();
								$menu[$key][son][$k] = $listzi;
							}
						}												
					}
				}

			}
			if(!empty($_GET['tag'])){
				$this->assign('menuTag',$_GET['tag']);
			}
			
			$this->assign('menu',$menu);
		}
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$this -> messagesNum();
		$this->display();
	}
	
	function messagesNum(){
		
		$pro = M('CheckProject');
		$user = M('User');

		$plist = $pro -> field('stu_id') -> where('status = 0') -> group('stu_id') -> select(); 
		$num = count($plist);
		//如果是管理员的话显示管理员的消息提示
		$ulist= $user -> field('username,nickname,category') -> where('id='.$_SESSION[C('USER_AUTH_KEY')]) -> select();
		
		$this -> assign('ulist',$ulist);
		$this -> assign('num',$num);
	
	}
	
	function messages(){
		
		$stu = M('ViewStuinfo');
		$pro = M('CheckProject');
		$m = M('ClassManager');
		$c = M('Class');
		$user = M('User');
		//查出管理员的所管辖的班级
		$clist = $m -> where('u_id = '.$_SESSION[C('USER_AUTH_KEY')]) -> select();
		//拼凑出sql in里面的语句
		$class_in = '(';
		foreach($clist as $v){
			$name = $c -> where('c_id = '.$v['c_id']) -> select();
			if($class_in == '('){
				$class_in = $class_in.$name[0]['name'];
			}else{
				$class_in = $class_in.','.$name[0]['name'];
			}
		}
		
		$class_in = $class_in.')';
		dump($class_in);
		

		$slist = $pro -> query('select count(p.p_id) num ,p.stu_id,v.classtitle,v.username,v.nickname  from think_check_project p,think_view_stuinfo v where p.stu_id=v.stu_id and p.status=0 and v.classtitle in '.$class_in.' group by p.stu_id');
		
		
		
		if($slist == false){
			//如果不是管理员的话消息列表只有他一个人的信息
			$sql = 'select count(p_id) num,stu_id from think_check_project where status = 0 and stu_id='.$_SESSION['category'].' group by stu_id';
			$slist = $pro -> query($sql);
		}
		
		$category = $user -> field('category') -> where('id='.$_SESSION[C('USER_AUTH_KEY')]) -> select();
		$this -> assign('slist',$slist);
		dump($slist);
		$this -> assign('category',$category[0]['category']);
		$this->display('main');
	}
	
	// 后台首页 查看系统信息
	public function main() {
		$info = array(
				'操作系统'=>PHP_OS,
				'运行环境'=>$_SERVER["SERVER_SOFTWARE"],
				'PHP运行方式'=>php_sapi_name(),
				'ThinkPHP版本'=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
				//'上传附件限制'=>ini_get('upload_max_filesize'),
				//'执行时间限制'=>ini_get('max_execution_time').'秒',
				'服务器时间'=>date("Y年n月j日 H:i:s"),
				'北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
				
				//'服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
				//'剩余空间'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
				//'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
				//'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
				//'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
		);

		

		$this->assign('info',$info);
		
		$this->display();
	}
	
	//用户登录页面
	public function login() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->display();
		}else{
			$this->redirect('Index/index');
		}
	}

	public function index()
	{
		//如果通过认证跳转到首页
		redirect(__APP__);
	}
	
	//生成验证码
	Public function verify(){
	
		import("ORG.Util.Image");
	
		Image::buildImageVerify();
	
	}
	
		
	//登陆检测
	function checkLogin(){
		
		Load('extend');
	
		
		
		if(empty($_POST['username'])) {
			$this->error('帐号错误！');
		}elseif (empty($_POST['password'])){
			$this->error('密码必须！');
		}elseif (empty($_POST['verify'])){
			$this->error('验证码必须！');
		}
		//生成认证条件
		$map            =   array();
		// 支持使用绑定帐号登录
		$map['username']	= $_POST['username'];

		$map['status']	=	array('eq',1);
		if($_SESSION['verify'] != md5($_POST['verify'])) {
			$this->error('验证码错误！');
		}
		import ( 'ORG.Util.RBAC' );
        $authInfo = RBAC::authenticate($map);
		//使用用户名、密码和状态的方式进行认证
		//修改 FALSE 改为 NULL 
		if( NULL === $authInfo) {
			$this->error('帐号不存在或已禁用！');
		}else {
			if($authInfo['password'] != md5($_POST['password'])) {
				$this->error('密码错误！');
			}
			$_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
			$_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
			$_SESSION['login_count']	=	$authInfo['login_count'];
			if($authInfo['username']=='admin') {
				$_SESSION['administrator']		=	true;
			}
			//保存登录信息,可用于登陆的时候更新数据
			$User	=	M('User');
			$ip		=	get_client_ip();
			$time	=	time();
			$data = array();
			$data['id']	=	$authInfo['id'];
			$data['last_login_time']	=	$time;
			$data['login_count']	=	array('exp','login_count+1');
			$data['last_login_ip']	=	$ip;
			$User->save($data);
		
			// 缓存访问权限
			RBAC::saveAccessList();
			$this -> assign("jumpUrl",__APP__);
			$this->success('登录成功！');
		
		}
	}
	
	
	
	function loginout(){
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION);
			session_destroy();
			$this->assign("jumpUrl",__URL__.'/login/');
			$this->success('登出成功！');
		}else {
			$this->error('已经登出！');
		}
	}
	
	/*
		作用：异步获取服务器时间
	*/
	function ajaxTime(){
		echo json_encode( date('Y-m-d H:i:s',time()) );
	}
	
	//上传图片
	public function uping(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Public/uface/';// 设置附件上传目录
		if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		$_name=$info[0]['savename'];
		$_namel=__ROOT__.'/Public/uface/'.$info[0]['savename'];
		echo "<script>window.opener.document.getElementById('url').value='$_name';window.opener.document.getElementById('image_url').src='$_namel';window.close();</script>";
		exit();
	}
	
}
?>