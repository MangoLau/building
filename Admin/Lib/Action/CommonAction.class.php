<?php
class CommonAction extends Action {
	function _initialize() {
		// 用户权限检查
		
		if (C ( 'USER_AUTH_ON' ) && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
			import ( 'ORG.Util.RBAC' );
			if (! RBAC::AccessDecision ()) {
				//检查认证识别号
				if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
					//跳转到认证网关
					redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
				}
				// 没有权限 抛出错误
				if (C ( 'RBAC_ERROR_PAGE' )) {
					// 定义权限错误页面
					redirect ( C ( 'RBAC_ERROR_PAGE' ) );
				} else {
					if (C ( 'GUEST_AUTH_ON' )) {
						$this->assign ( 'jumpUrl', PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
					}
					// 提示错误信息
					$this->error ( L ( '_VALID_ACCESS_' ) );
				}
			}
		}
		
	}

	
		
	

	//关联到User的index
	public function index() {
		//列表过滤器，生成查询Map对象
		$map = $this->_search ($map);
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );	//用于查询
		}
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ($model,$map);	//方法在下面
		}
		$this->display ();
		
		return;
	}
	/**
     +----------------------------------------------------------
	 * 取得操作成功后要返回的URL地址
	 * 默认返回当前模块的默认操作
	 * 可以在action控制器中重载
     +----------------------------------------------------------
	 * @access public
     +----------------------------------------------------------
	 * @return string
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	/*function getReturnUrl() {
		return __URL__ . '?' . C ( 'VAR_MODULE' ) . '=' . MODULE_NAME . '&' . C ( 'VAR_ACTION' ) . '=' . C ( 'DEFAULT_ACTION' );
	}
*/
	/**
     +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
     +----------------------------------------------------------
	 * @access protected
     +----------------------------------------------------------
	 * @param string $name 数据对象名称
     +----------------------------------------------------------
	 * @return HashMap
     +----------------------------------------------------------
	 * @throws ThinkExecption
     +----------------------------------------------------------
	 */
	protected function _search($name = '') {
		//生成查询条件
		if (empty ( $name )) {
			$name = $this->getActionName();		//getActionName() --> Action方法里面的函数
		}
		$name=$this->getActionName();
		$model = D ($name);
		$map = array ();
		foreach ( $model->getDbFields () as $key => $val ) {
			if (isset ( $_REQUEST [$val] ) && $_REQUEST [$val] != '') {
				$map [$val] = $_REQUEST [$val];
			}
		}
		return $map;

	}

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
	
	/* protected function _list($model, $map, $sortBy = '', $asc = false) {
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
				$listRows = '2';
			}
			$p = new Page ( $count, $listRows );
			//分页查询数据

			$voList = $model->where($map)->order( "`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->findAll ( );
			//echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			//分页显示
			$page = $p->show ();
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
		Cookie::set ( '_currentUrl_', __SELF__ );
		return;
	}	 */		
	
	/**
	 * 
	 +--------
	 *查询出用户的基本资料然后存入session
	 +--------
	 * 
	 */
	function _userInfo(){
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$user = M("User");
		$arr = $user -> field("username,nickname,login_count,register_time,last_login_time,last_login_ip") -> where("id=".$id) -> select();
		
		return $arr[0];
	}
	
	
	/*
		作用：调用fck插件
		@parm $content 初始化的内容
		@parm $width 宽度
		@parm $height 高度
		@parm content 表单的name
		return var 
	 */
	function _fck($value='', $width='1000px', $height='400px',$content='content'){


		import("ORG.Util.editor.fckeditor");
	
		$path = '__ROOT__/ThinkPHP/Lib/ORG/Util/editor/';
	
		$ed = new FCKeditor($content) ;
		$ed -> BasePath = $path ;
	
		$ed -> Width = $width;
		$ed -> Height = $height;
		$ed -> Value = $value;
	
		$FCKeditor = $ed->CreateHtml();		

	
		return $FCKeditor;

	
	}
	
	
	
	// 后台首页 查看系统信息
    public function adminInfo() {
        $info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式'=>php_sapi_name(),
            'ThinkPHP版本'=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign('info',$info);
        $this->display();
    }

	/*
		作用 ： 显示新闻类别
		return array
	*/
	function classList(){
	
		$class = M('Newsclass');
			
		$list = $class -> field("c_id,name,pid,path,concat(path,'-',c_id) as bpath") -> order('bpath') -> select();
		
		foreach($list as $key => $value){
			$list[$key][count] = count(explode('-', $value[bpath]));
		}
		
		return $list;
	}
	
}
?>