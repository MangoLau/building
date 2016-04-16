<?php 
class WordsAreaAction extends CommonAction{
	
	function wordsList(){
		
		//互动信息列表
		$words = M('Words');
// 		$where_w['status']='1';
		$where_w['pid']='0';
		import('ORG.Util.Page');// 导入分页类
		$count=$words->where($where_w)->count();// 查询满足要求的总记录数
		$page=new Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数
		// 		$page->setConfig('theme',"%upPage% %downPage%");//修好显示内容
		$show=$page->show();// 分页显示输出
		$arrs=$words->where($where_w)->limit($page->firstRow.','.$page->listRows)->select();
		$user = $this -> _userInfo();
		$this -> assign('user', $user);
		$this->assign('page',$show);
		$this->assign('list',$arrs);
		$this->display();
	}
	
	/*
		作用：增加对应留言的回复
	*/
	function wordsReply(){
		$words = D('Words');
		$_POST['time'] = strtotime($_POST['time']);
		if($words -> create()){
			if($words -> add()){
				$this -> success('回复成功！');
			}else{
				$this -> error('回复失败！');
			}
		}else{
			$this -> error($words -> getError());
		}

	}

	/*
		作用：将留言的内容修改
	*/
	function wordsEdit(){
		$words = D('Words');
		$_POST['time'] = strtotime($_POST['time']);
		if($words -> create()){
			if($words -> save()){
				$this -> success('留言修改成功！');
			}else{
				$this -> error('留言修改失败！');
			}
		}else{
			$this -> error($words -> getError());
		}
	}
	
	/*
		作用：将回复的内容修改
	*/
	function replyEdit(){
		$words = D('Words');
		$_POST['time'] = strtotime($_POST['time']);
		if($words -> create()){
			if($words -> save()){
				$this -> success('回复修改成功！');
			}else{
				$this -> error('回复修改失败！');
			}
		}else{
			$this -> error($words -> getError());
		}
	}


	/*
		作用：留言显示
	*/
	function wordsDisplay(){
		$words = M('Words');
		$where[id] = $_GET[id];
		$where[display] = 1;
		if($words -> save($where)){
			$this -> success('显示成功！');
		}else{
			$this -> error('显示失败！');
		}
		
	}
	
	/*
		作用：留言隐藏
	*/
	function wordsHide(){
		$words = M('Words');
		$where[id] = $_GET[id];
		$where[display] = 0;
		if($words -> save($where)){
			$this -> success('隐藏成功！');
		}else{
			$this -> error('隐藏失败！');
		}
		
	}
	
	/*
		作用：留言删除
	*/
	function wordsDelete(){
		$words = M('Words');
		$where['id'] = $_GET[id];
		
		if($words -> where($where) -> delete()){
			$where1['pid'] = $_GET['id'];
			$arrs = $words ->where($where1) ->select();
			foreach ($arrs as $v){
				$where2['pid'] = $v['id'];
				$words ->where($where2) ->delete();				
			}
			$words ->where($where1) ->delete();
			$this -> success('删除成功！');
		}else{
			$this -> error('删除失败！');
		}
		
	}
	
	/**
	 * 该管理员发反馈信息
	 */
	function sendmassege(){
		$user = $this -> _userInfo();
		$words = M('Words');		
		$sql2 = 'SELECT count(A.id) num
				FROM think_words A
				LEFT JOIN think_words B ON A.id = B.pid
				WHERE A.pid =0 AND A.author=\''.$user[nickname]."'";
		
		$count = $words -> query($sql2);
		import("ORG.Util.Page"); //  导入分页类
		
		$count = $count[0][num];
		$Page = new Page($count,5); //  实例化分页类 传入总记录数和每页显示的记录数
		$page = $Page -> show();
		
		
		$sql = 'SELECT A. * , B.id reply_id, B.pid reply_pid, B.content reply, B.author answerer, B.time reply_time
				FROM think_words A
				LEFT JOIN think_words B ON A.id = B.pid
				WHERE A.pid =0 AND A.author=\''.$user[nickname].'\' order by id desc limit '.$Page->firstRow.','.$Page->listRows;
		//  进行分页数据查询 注意 limit 方法的参数要使用 Page 类的属性
		
		$list = $words -> query($sql);				
		$this -> assign('user', $user);
		$this -> assign('list', $list);
		$this->assign('page',$page ); //  赋值分页输出
		$this->display();
	}
	function sending(){
		$user = M('User');
		$where['id']=$_SESSION[C('USER_AUTH_KEY')];
		$n=$user->field('nickname')->where($where)->find();
		$words = M('Words');
		$data['author'] = $n['nickname'];
		$data['content'] = $_POST['content'];
		$data['time'] = strtotime($_POST['time']);
		$lastid=$words->add($data);
		if($lastid){
			$this -> success('发送成功！');
		}else{
			$this -> error('发送失败！');
		}
	}
}
?>