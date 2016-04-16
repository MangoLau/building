<?php 
class NodeAction extends CommonAction{
	
	function index(){
	
		
		$node = M('node');
		$level1 = $node -> where('level=1') -> field('id,title,display') -> select();
		
		foreach($level1 as $key1 => $vo1 ){
		
			$data['level'] = 2;
			$data['pid'] = $vo1[id];
			$level2[] = $node -> where($data) -> field('id,title,display') ->order('sort asc') -> select();
			
			foreach($level2[$key1] as $key2 => $vo2){
				
				$data['level'] = 3;
				$data['pid'] = $vo2[id];
				$level3[$key1][] = $node -> where($data) -> field('id,title,display') -> select();
				foreach($level3[$key1][$key2] as $key3 => $vo3){
// 					$check = in_array($vo3[id], $role_level3) ? 'checked' : null ;
// 					$level3[$key1][$key2][$key3][check] = $check;
				}
			}
		}
		$this -> assign('level1',$level1);
		$this -> assign('level2',$level2);
		$this -> assign('level3',$level3);

		//$this -> assign('list',$list);
		$this -> display();
	}
	
	//节点资料更新
	function nodeInfoAlter() {
	
		$node = M('node');
		$where[id] = $_GET[id];
		$nlist = $node -> where($where) -> select();
		if($_POST[alter]){
			$where[id] = $_POST[id];
			$data[title] = $_POST[title];
			$data[remark] = $_POST[remark];
			$data[display] = $_POST[display];
			$l = $node -> where($where) -> save($data);
			if($l){
					
				$this -> success('修改成功！');
			}else{
				$this -> error('修改失败！');
			}
	
		}
	
		$this -> assign('nlist',$nlist);
		$this -> display();
	}
	
	
	
	
}


?>