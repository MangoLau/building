<?php// 角色模块class RoleAction extends CommonAction {	function _filter(&$map){		$map['name'] = array('like',"%".$_POST['name']."%");	}		function index(){		import("ORG.Util.Page"); //  导入分页类
		$role = M('Role');
		$count  = $role -> count();
		$Page       = new Page($count,10); //  实例化分页类 传入总记录数和每页显示的记录数
		$page       = $Page->show(); //  分页显示输出
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		
		$list = $role ->  limit($Page->firstRow.','.$Page->listRows)-> select();
		
		$this->assign('page',$page ); //  赋值分页输出
		$this -> assign('list',$list);
		$this -> display();			}		function alertPower(){		if($_REQUEST[roleId]=='1'){			$this -> _roleInfo($_REQUEST[roleId]);			$node=M('Node');			$where1['level']='1';			$str1=$node->field('id,title')->where($where1)->select();			foreach ($str1 as $val){				$level1[]=array("id"=>"{$val['id']}","title"=>"{$val['title']}","check"=>"checked");			}			$where2['level']='2';			$str2=$node->field('id,title')->where($where2)->order('sort asc')->select();
			$i=0;			foreach ($str2 as $val){
				$level2[0][]=array("id"=>"{$val['id']}","title"=>"{$val['title']}","check"=>"checked");
				$where3['pid']=$val['id'];				$where3['level']='3';
				$str3=$node->field('id,title')->where($where3)->select();
				foreach ($str3 as $val){
					$level3[0][$i][]=array("id"=>"{$val['id']}","title"=>"{$val['title']}","check"=>"checked");
				}				$i++;			}					$this -> assign('level1',$level1);
			$this -> assign('level2',$level2);
			$this -> assign('level3',$level3);			$this -> assign('all',1);		}else{			$this -> _roleInfo($_REQUEST[roleId]);
			$role_level1 = $this -> _roleLevelList($_REQUEST[roleId], 1);
			$role_level2 = $this -> _roleLevelList($_REQUEST[roleId], 2);
			$role_level3 = $this -> _roleLevelList($_REQUEST[roleId], 3);
			$this -> _levelList($role_level1, $role_level2, $role_level3);		}// 		$this -> assign('level1', $level1);						if($_POST[sub_alert]){// 			echo "!!";			if($_POST[level1]){				if($_POST[level2]){					if($this -> _isPid($_POST[level1], $_POST[level2])){						if($_POST[level3] ){							if($this -> _isPid($_POST[level2], $_POST[level3])){										if($_POST[role_name] || $_POST[role_remark]){									$role = M('role');									$data[name] = $_POST[role_name];									$data[remark] = $_POST[role_remark];									$where[id] = $_POST[roleId];									$role -> where($where) -> save($data);								}										$this -> _insert_del_level ($_POST[level1], $role_level1, 1, $_POST[roleId]);								$this -> _insert_del_level ($_POST[level2], $role_level2, 2, $_POST[roleId]);								$this -> _insert_del_level ($_POST[level3], $role_level3, 3, $_POST[roleId]);																$this -> success('您已成功对角色资料进行了修改!');									}else{								$this -> error('您勾选了第三级权限,但是与第二级权限不对应!');							}								}else{							$this -> error('您在有些项目中只勾选了第一、二级权限,但是还没有勾选第三级权限!');						}					}else{						$this -> error('您勾选了第二级权限,但是与第一级权限不对应!');					}						}else{					$this -> error('您勾选了第一级权限,但是您还没有勾选第二级权限!');				}			}else{				$this -> error('您还没有勾选第一级权限!');			}		}						$this -> display();	}		protected function _levelList($role_level1, $role_level2, $role_level3){				$node = M('node');		$level1 = $node -> where('level=1') -> field('id,title') -> select();				foreach($level1 as $key1 => $vo1 ){			$check = in_array($vo1[id], $role_level1) ? 'checked' : null ;			$level1[$key1][check] = $check;					$data['level'] = 2;			$data['pid'] = $vo1[id];			$level2[] = $node -> where($data) -> field('id,title') ->order('sort asc') -> select();						foreach($level2[$key1] as $key2 => $vo2){				$check = in_array($vo2[id], $role_level2) ? 'checked' : null ;				$level2[$key1][$key2][check] = $check;								$data['level'] = 3;				$data['pid'] = $vo2[id];				$level3[$key1][] = $node -> where($data) -> field('id,title') -> select();				foreach($level3[$key1][$key2] as $key3 => $vo3){					$check = in_array($vo3[id], $role_level3) ? 'checked' : null ;					$level3[$key1][$key2][$key3][check] = $check;				}			}		}		$this -> assign('level1',$level1);		$this -> assign('level2',$level2);		$this -> assign('level3',$level3);		return;	}			/**	 * 显示出该角色的名称和描述	 * $id	传过来的角色id	 * $l level几	 */	function _roleInfo($id){		$role = M('role');		$data[id] = $id;		$list = $role -> where($data) -> select();		$this -> assign('roleInfo',$list);			return;	}			/**	 * 查出该角色原来拥有的节点ID并用于模板的勾选check	 * $id	传过来的角色id	 * $l level几	 * 最终返回的是角色某个level的节节点id组成一维数组。	 */	function _roleLevelList($role_id, $l){		$access = M('access');		$data['role_id'] = array('eq', $role_id);				$data['level'] = array('eq', $l);		$level = $access -> where($data) -> field('node_id') ->  select();				foreach($level as $vo){			$list[] = $vo[node_id];		}		return $list;	}		/**	 *  判断下一级权限是否对应着上一级的权限	 *	$pidArr		上一级权限	 * 	$idArr		这一级权限	 * 	 */		function _isPid ($pidArr, $idArr) {		foreach($idArr as $id){			$node = M('node');			$data[id] = $id;			$arr = $node -> where($data) -> field('pid') -> find();			if(!in_array($arr[pid], $pidArr)){				return false;			}		}		return true;	}			/**	 * 对原有授权的修改	 *	$post_level		提交过来的节点id数组	 *	$role_level		已经读出来的这个角色的原来的节点id数组	 *	$level			什么级别的权限	 *	$role_id		这个角色的id	 */	function _insert_del_level ($post_level, $role_level, $level, $role_id) {		$access = M('access');		foreach($post_level as $val){			if(!in_array($val, $role_level)){				$where[role_id] =  $role_id;				$where[node_id] = $val;				$where[level] = $level;				$access ->  add($where);			}		}		foreach($role_level as $val){			if(!in_array($val, $post_level)){				$data[role_id] =  $role_id;				$data[node_id] = $val;				$access -> where($data) -> delete();			}		}	}	//角色资料更新	function roleInfoAlter() {				$role = M('role');		$where[id] = $_GET[id];		$rlist = $role -> where($where) -> select();			if($_POST[alter]){			$where[id] = $_POST[id];			$data[title] = $_POST[title];			$data[remark] = $_POST[remark];			$l = $role -> where($where) -> save($data);			if($l){									$this -> success('修改成功！');			}else{				$this -> error('修改失败！');			}			}			$this -> assign('rlist',$rlist);		$this -> display();	}		//角色禁用	function roleForbid(){			$role = M('role');		$where[id] = $_GET[id];		$data[status] = 0;		$l = $role -> where($where) -> save($data);		if($l){			$this -> success('禁用成功！');		}else{			$this -> error('禁用失败！');		}	}		//角色启用	function roleStart(){			$role = M('role');		$where[id] = $_GET[id];		$data[status] = 1;		$l = $role -> where($where) -> save($data);		if($l){			$this -> success('启用成功！');		}else{			$this -> error('启用失败！');		}	}		//角色添加	public function addRole() {		// 创建数据对象		if($_POST[add_role]){			$Role	 =	 D("Role");			if(!$Role->create()) {				$this->error($Role -> getError());			}else{				// 写入数据				if($result	 =	 $Role->add()) {										$this->success('角色添加成功！');				}else{					$this->error('角色添加失败！');				}			}		}		$this->display();	}		//角色删除	function roleDelete(){
		if($_GET[id]){
			$role = M('role');
			$where[id] = $_GET[id];
			if($role -> where($where) -> delete()) {
				$access=M('Access');
				$where1[role_id] = $_GET[id];
				if($access -> where($where1) -> delete()) {
					$this->success("角色删除成功！");
				}else{
					$this->error('角色删除成功！');
				}	
			}else {
				$this->error('角色删除成功！');
			}
		}
	}	}?>