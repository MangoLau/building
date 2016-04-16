<?php
class UserAction extends CommonAction{
	/*public function index(){
	 echo 'user下面的显示';
	
	}*/
	/**
	 +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
	 +----------------------------------------------------------
	 * @access protected
	 +----------------------------------------------------------
	 * @param Model $model 数据对象
	 * @param HashMap $map 过滤条件
	 * @param string $sortBy 排序
	 * @param boolean $asc 是否正序
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 * @throws ThinkExecption
	 +----------------------------------------------------------
	 */
	protected function _list($model, $map, $sortBy = '', $asc = false) {
		//排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		//取得满足条件的记录数
		$count = $model->where ( $map )->count ( 'id' );
		if ($count > 0) {
			import ( "ORG.Util.Page" );
			//创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = '10';
			}
			$p = new Page ( $count, $listRows );
			//分页查询数据
	
			$voList = $model-> where($map)->order( "`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();
			//dump($voList);
			foreach($voList as $key => $val){
				$role = M('role');
				$where['id'] = $val['m_id'];
				$l = $role -> field('title') -> where($where) ->select();
				$voList[$key]['title'] = $l[0]['title'];
			}
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			//分页显示
			$page = $p->show();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$this->assign ( 'list', $voList );
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( "page", $page );
			
		}
		//Cookie::set ( '_currentUrl_', __SELF__ );
		return;
	}
	

	//用户资料更新
	function userInfoAlter(){
		
		$user = M('user');
		if($_GET['id']){
			$where['id'] = $_GET['id'];
		}else{
			$where['id'] = $_SESSION[C('USER_AUTH_KEY')];
		}
		$ulist = $user -> where($where) -> select();
		
		if($_POST['alter']){
			$where['id'] = $_POST['id'];

			$data['nickname'] = $_POST['nickname'];
			$data['remark'] = $_POST['remark'];
			$l = $user -> where($where) -> save($data);
			if($l){
			
				$this -> success('修改成功！');
			}else{
				$this -> error('修改失败！');
			}
				
		}
		
		$this -> assign('ulist',$ulist);
		$this -> display();
	}
	
	//用户禁用
	function userForbid(){
	
		$user = M('user');
		$where['id'] = $_GET['id'];
		$data['status'] = 0;
		$l = $user -> where($where) -> save($data);
		if($l){
			$this -> success('禁用成功！');
		}else{
			$this -> error('禁用失败！');
		}
	}
	
	//用户启用
	function userStart(){
	
		$user = M('user');
		$where['id'] = $_GET['id'];
		$data['status'] = 1;
		$l = $user -> where($where) -> save($data);
		if($l){
			$this -> success('启用成功！');
		}else{
			$this -> error('启用失败！');
		}
	}
	
	//index.html里面的列表的默认index方法来自CommonAction方法里面。
	
	//用于过滤，如果有查询就要过滤掉其他剩下查询的
	function _filter(&$map){
		$map['id'] = array('egt',2);	//id=1是admin
		$map['username'] = array('like',"%".$_POST['username']."%");	//有查询就是传过来的，没有就是null
	}

	public function addUser() {
		// 创建数据对象
		if($_POST['add_user']){
			$User	 =	 D("User");
			if(!$User->create()) {
				$this->error($User->getError());
			}else{
				// 写入帐号数据
				$result = $User->add();
				if($result) {
					$this->addRole($result);
					$this->success('用户添加成功！');
				}else{
					$this->error('用户添加失败！');
				}
			}
		}
		
		$this->display();
		
	}
	
	protected function addRole($userId) {
		//新增用户自动加入相应权限组
		$RoleUser = M("RoleUser");
		$RoleUser->user_id	=	$userId;
		// 默认加入网站编辑组
		$RoleUser->role_id	=	3;
		$RoleUser->add();
	}
	
	//更改用户角色
	function changeRole() {
	
		
		
		
		if(!empty($_POST['userId'])&&!empty($_POST['roleId'])){
			$RoleUser = M("RoleUser");
			$data['user_id'] = $_POST['userId'];
			$data['role_id'] = $_POST['roleId'];
			$flag = $RoleUser->save($data);
			if($flag){
				$this->assign('jumpUrl','__URL__');
				$this->success('更改成功!');
			}else{
				$this->assign('jumpUrl','__URL__');
				$this->error('更改失败!');
			}
		}
		
		//通过关联模型查出role_id
		$user = D('User');
		$data['id'] = $_GET['id'];
		$list = $user -> relation(true) -> where($data) -> select();				
		
		//查出用户所属的角色名
		$role = M('Role');
		$rList = $role -> select();
		
		
		$this -> assign('role_id',$list[0]['role_id']);
		$this -> assign('rList',$rList);
		$this -> assign('userId',$_GET['id']);
		$this->display ();
		
	
	}
	
	
	//重置密码
	public function userPasswordAlter()
	{	
		
		$user = M('User');
		$uid = $_GET['id'];	//userInfoAlter传过来的
		if($_POST['alter_password']){
			
			$password = $_POST['password'];	
			$id = $_POST['id'];
			
			$where['id'] = $id;
			
			$result	=	$user -> where($where) -> field('password') -> select();
			
			if(md5($_POST['opassword']) !== $result[0]['password']){
				
				$this->error('原密码错误！');
				return;
			}
			
			if(''== trim($password)) {
				$this->error('密码不能为空！');
				return;
			}
			if($_POST['repassword']!= $_POST['password']){
				$this->error('重复密码和密码不一致！');
			}else{
					$data['password']	=	md5($password);
					$where[id]			=	$id;
					$result	=	$user -> where($where) -> save($data);
					if(false !== $result) {
// 						$this -> assign('jumpUrl','__URL__');
						$this->success("密码修改为$password");
					}else {
						$this->error('重置密码失败！');
					}
			}
			
		}
		

		$this -> assign('id',$uid);
		$this -> display();
	}
	
	//用户删除
	function userDelete(){
		if($_GET['id']){
			$user = M('user');
			$where['id'] = $_GET[id];
			if($user -> where($where) -> delete()) {
				$role_user=M('Role_user');
				$where1['user_id'] = $_GET['id'];
				if($role_user -> where($where1) -> delete()) {
					$this->success("用户删除成功！");
				}else{
					$this->error('用户删除成功！');
				}				
			}else {
				$this->error('用户删除成功！');
			}
		}
	}
		
	
}
?>