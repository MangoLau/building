<?php
		$role = M('Role');
		$count  = $role -> count();
		$Page       = new Page($count,10); //  实例化分页类 传入总记录数和每页显示的记录数
		$page       = $Page->show(); //  分页显示输出
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		
		$list = $role ->  limit($Page->firstRow.','.$Page->listRows)-> select();
		
		$this->assign('page',$page ); //  赋值分页输出
		$this -> assign('list',$list);
		$this -> display();
			$i=0;
				$level2[0][]=array("id"=>"{$val['id']}","title"=>"{$val['title']}","check"=>"checked");
				$where3['pid']=$val['id'];
				$str3=$node->field('id,title')->where($where3)->select();
				foreach ($str3 as $val){
					$level3[0][$i][]=array("id"=>"{$val['id']}","title"=>"{$val['title']}","check"=>"checked");
				}
			$this -> assign('level2',$level2);
			$this -> assign('level3',$level3);
			$role_level1 = $this -> _roleLevelList($_REQUEST[roleId], 1);
			$role_level2 = $this -> _roleLevelList($_REQUEST[roleId], 2);
			$role_level3 = $this -> _roleLevelList($_REQUEST[roleId], 3);
			$this -> _levelList($role_level1, $role_level2, $role_level3);
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
	}