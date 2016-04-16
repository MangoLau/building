<?php
// 本类由系统自动生成，仅供测试用途
class PhotoAction extends CommonAction{	
	function photolist(){
		//显示logo
		$link=M('Link');
		$where_logo['type']='0';
		$where_logo['display']='1';
		$arr_logo=$link->where($where_logo)->find();
		$this->assign('logo',$arr_logo);
		
		//导航
		$class = M('Newsclass');
		$where['c_id']=$_GET['c_id'];
		$n=$class->field('c_id,name,pid')->where($where)->limit(1)->find();
		if($n['pid']=='0'){
			$nav = $this -> _nav($n['name']);	//来自CommonAction
		}else{
			$wherep['c_id']=$n['pid'];
			$m=$class->field('c_id,name')->where($wherep)->limit(1)->find();
			$nav = $this -> _nav($m['name']);	//来自CommonAction
		}
		$this->assign('nav',$nav);
		
		//图片列表
		$photo=M('Resource');
		$wherep['type']='2';
		import('ORG.Util.Page');// 导入分页类
		$count=$photo->where($wherep)->count();// 查询满足要求的总记录数
		$page=new Page($count,9);// 实例化分页类 传入总记录数和每页显示的记录数
		$page->setConfig('header','条记录');//修好显示内容
		$show=$page->show();// 分页显示输出
		$arr=$photo->where($wherep)->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($arr as $val){
			$w=preg_split('[\.]',$val['name']);
			$list[]=array("id"=>"{$val['id']}","name"=>_sort($w[0], 5),"title"=>"$w[0]","time"=>date('Y/m/d',$val['time']),"url"=>"{$val['url']}");
		}
		$this->assign('page',$show);
		$this->assign('list',$list);
		
		//最近新闻
		$base=M('Newsbase');
		$where_news['c_id']='18';
		$where_news['status']='1';
		$arr_news=$base->where($where_news)->order('date_time desc')->limit(10)->select();
		foreach ($arr_news as $n){
			$nl[]=array("c_id"=>"{$n['c_id']}","id"=>"{$n['id']}","title"=>_sort($n[news_title], 15),"time"=>date('Y/m/d',$n['date_time']));
		}
		$this->assign('newslist',$nl);
		
		//联系我们
		$where_contact['news_title']='联系我们';
		$arr_contact=$base->field('content')->where($where_contact)->find();
		$this->assign('contact',$arr_contact['content']);
		
		//友谊链接
		$link=M('Link');
		$wherel['type']='2';
		$l=$link->where($wherel)->order('id desc')->select();
		$this->assign('link',$l);
		
		$this->display();
	}
	
	function photoshow(){
		//显示logo
		$link=M('Link');
		$where_logo['type']='0';
		$where_logo['display']='1';
		$arr_logo=$link->where($where_logo)->find();
		$this->assign('logo',$arr_logo);
		
		//导航
		$class = M('Newsclass');
		$where['c_id']=$_GET['c_id'];
		$n=$class->field('c_id,name,pid')->where($where)->limit(1)->find();
		if($n['pid']=='0'){
			$nav = $this -> _nav($n['name']);	//来自CommonAction
		}else{
			$wherep['c_id']=$n['pid'];
			$m=$class->field('c_id,name')->where($wherep)->limit(1)->find();
			$nav = $this -> _nav($m['name']);	//来自CommonAction
		}
		$this->assign('nav',$nav);
		
		//图片信息
		$photo=M('Resource');
		$wherep['id']=$_GET['id'];
		$wherep['type']='2';
		$con=$photo->where($wherep)->select();
		foreach ($con as $pl){
			$w=preg_split('[\.]',$pl['name']);
			$pic[]=array("id"=>"{$pl['id']}","name"=>"$w[0]","time"=>date('Y/m/d',$pl['time']),"url"=>"{$pl['url']}","remark"=>"{$pl['remark']}");
		}
		$this->assign('con',$pic);
		
		//最近新闻
		$base=M('Newsbase');
		$where_news['c_id']='18';
		$where_news['status']='1';
		$arr_news=$base->where($where_news)->order('date_time desc')->limit(10)->select();
		foreach ($arr_news as $n){
			$nl[]=array("c_id"=>"{$n['c_id']}","id"=>"{$n['id']}","title"=>_sort($n[news_title], 15),"time"=>date('Y/m/d',$n['date_time']));
		}
		$this->assign('newslist',$nl);
		
		//联系我们
		$where_contact['news_title']='联系我们';
		$arr_contact=$base->field('content')->where($where_contact)->find();
		$this->assign('contact',$arr_contact['content']);
		
		//友谊链接
		$link=M('Link');
		$wherel['type']='2';
		$l=$link->where($wherel)->order('id desc')->select();
		$this->assign('link',$l);
		
		$this->display();
	}
}
?>