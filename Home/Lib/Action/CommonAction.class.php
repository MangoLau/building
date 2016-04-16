<?php
class CommonAction extends Action {
	
	function _nav($name=''){
		$link = M('Link');
		$nav = $link -> where('type=1 and lev=0') -> order('sort asc')  -> select();
        $list = array();
		foreach ($nav as $val){
			$arr = array();
			if ($val['c_id']!='0'){
				$w['lev']=$val['c_id'];
				$arr=$link -> where($w) -> select();
			}
			if($val['name']==$name){
				$act='1';
			}else{
				$act='';
			}
			$list[]=array("c_id"=>$val['c_id'],"link"=>$val['link'],"name"=>$val['name'],"act"=>$act,"zi"=>$arr, 'lev'=>$val['lev']);
		}
//        print_r($list);die;
		return $list;
	}
	
	/**
	 *
	 +----------------------
	 *取得固定条数新闻的内容
	 *@dbname 数据库表名
	 *@c_id 新闻所属的类别c_id
	 *@limit 截取多少条
	 +---------------------
	 *
	*/
	protected function _showNews($dbname, $c_id, $limit,$order = 'id desc'){

		$news = M($dbname);
		
		$arr = $news -> limit($limit) -> order( $order ) -> where("status=1 and c_id=".$c_id)-> select();
		return $arr;

	}
	/**
	 * 
	 +--------
	 *查询出用户的基本资料然后存入数组
	 +--------
	 * 
	 */
	function userInfo(){
		$id = $_SESSION[C('USER_AUTH_KEY')];
		$user = M("User");
		$arr = $user -> where("id=".$id) -> select();
		return $arr;
	}
	
	//显示fck
	function _fck(){
		
		//------------------------------------fck----------------------
		import("ORG.Util.editor.fckeditor");
	
		$path = '__ROOT__/ThinkPHP/Lib/ORG/Util/editor/';
	
		$ed = new FCKeditor('content') ;
		$ed -> BasePath = $path ;
	
		$ed -> Width = "1000px";
		$ed -> Height = "400px";
		$ed -> Value = '请输入新闻内容！';
	
		$FCKeditor = $ed->CreateHtml();			//调用创建html的方法
		//--------------------------end fck-----------------------
	
		
		
		$this -> assign('FCKeditor',$FCKeditor);
	
	}
	
	
	
	
	
}
?>