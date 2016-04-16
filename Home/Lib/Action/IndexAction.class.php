<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction {
    Public function _initialize(){
        $seo['title'] = '建材网站首页';
        $seo['keywords'] = '建筑材料 质量保证';
        $seo['description'] = '建筑材料 质量保证';
        $this->assign('seo', $seo);
    }

    public function index(){
    	//显示logo
		$link=M('Link');
		$where_logo['type']='0';
		$where_logo['display']='1';
		$arr_logo=$link->where($where_logo)->find();
		$this->assign('logo',$arr_logo);


    	//输出导航
		$nav = $this -> _nav('网站首页');	//来自CommonAction
		$this->assign('nav',$nav);
		
		//首页图片
		$base=M('newsbase');
		$file=M('File');
		$where_huo['c_id']='16';
		$where_huo['status']='1';
		$where_huo['display']='0';
		$arr_huos=$base->field('id,news_title,date_time')->where($where_huo)->order('date_time desc')->limit(5)->select();
		foreach ($arr_huos as $arr_huo){
			$where_file['n_id']=$arr_huo['id'];
			$where_file['type']=1;
			$arr_file=$file->where($where_file)->find();
			$arr_files[]=array("title"=>_sort($arr_huo['news_title'],36),"time"=>date('Y/m/d',$arr_huo['date_time']),"name"=>$arr_file['name']);
		}
		$this->assign('huo',$arr_files);

		//新闻动态
        $sql = 'SELECT think_newsbase.id,think_newsbase.news_title,think_newsbase.content,think_newsbase.date_time,think_file.f_id,think_file.name
                FROM think_newsbase,think_file
                WHERE think_newsbase.id=think_file.n_id AND think_newsbase.c_id=10 AND think_newsbase.status=1 AND think_file.type=1
                GROUP BY think_file.n_id
                ORDER BY think_newsbase.date_time DESC
                LIMIT 5';
        $nl = M()->query($sql);
		$this->assign('newslist',$nl);
//        var_dump($nl);die;
		
		//公司介绍
        $where_introduce['id'] = 6;
        $where_introduce_img['n_id'] = 6;
        $introduce = $base->where($where_introduce)->find();
        $introduce['img'] = $file->where($where_introduce_img)->find();
        $this->assign('introduce', $introduce);

        //联系我们
        $where_us['id'] = 7;
        $us = $base->where($where_us)->find();
        $this->assign('us', $us);

        //企业文化
        $where_culture['id'] = 8;
        $culture = $base->where($where_culture)->find();
        $this->assign('culture', $culture);

        //工程案例
        $sql = 'SELECT think_newsbase.id,think_newsbase.news_title,think_file.name
                FROM think_newsbase,think_file
                WHERE think_file.n_id=think_newsbase.id AND think_newsbase.c_id=8 AND think_newsbase.status=1
                ORDER BY think_newsbase.date_time DESC
                LIMIT 10';
        $projects = M()->query($sql);
        $this->assign('projects', $projects);

        //行业动态
        $where_industry['c_id'] = 11;
        $where_industry['status'] = 1;
        $industry = $base->field('id,news_title,date_time')->where($where_industry)->limit(8)->select();
        $this->assign('industry', $industry);

        //服务承诺
        $where_promise['id'] = 16;
        $promise = $base->where($where_promise)->find();
        $this->assign('promise', $promise);

		//友谊链接
		$link=M('Link');
		$wherel['type']='2';
		$l=$link->where($wherel)->order('id desc')->select();
		$this->assign('link',$l);
		
    	$this->display();
    }
    
    //本站搜索
    public function search(){
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
    	
    	//显示所在位置
    	if($n['pid']=='0'){
    		$fname=$n['name'];
    		$zname='';
    	}else{
    		$wheref['c_id']=$n['pid'];
    		$f=$class->field('c_id,name')->where($wheref)->limit(1)->find();
    		$fname=$f['name'];
    		$zname=$n['name'];
    	}
    	$this->assign('fname','站内搜索');
    	$this->assign('zname',$zname);

    	$base=M('Newsbase');
    	//搜索
    	if (trim($_POST['news_title'])!=null) {
    		$where_search=explode(" ", $_POST['news_title']);
    		foreach ($where_search as $kw){
    			$ws[]='%'.$kw.'%';
    		}
    		$map['news_title']=array('like',$ws,'OR');
    		$map['status']='1';
    		import('ORG.Util.Page');// 导入分页类
    		$count=$base->where($map)->order('date_time desc')->count();// 查询满足要求的总记录数
    		$page=new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
    		$page->setConfig('header','条记录');//修好显示内容
    		$show=$page->show();// 分页显示输出
    		$nei=$base->where($map)->order('date_time desc')->limit($page->firstRow.','.$page->listRows)->select();
    		foreach ($nei as $all){
    			$file=M('File');//检查是否有文件
    			$f['n_id']=$all['id'];
    			$file=$file->where($f)->select();
    			if(!empty($file)&&empty($all['content'])){
    				$have='1';
    			}else{
    				$have='0';
    			}
    			$where_class['c_id']=$all['c_id'];
    			$class_name=$class->field('name')->where($where_class)->find();
    			foreach ($where_search as $k_ws){
    				$all['news_title']=preg_replace("/($k_ws)/i", "<font color=red>\\1</font>", $all['news_title']);
    			}
    			$arr[]=array("class_name"=>"{$class_name['name']}","id"=>"{$all['id']}","c_id"=>"{$all['c_id']}","title"=>"{$all['news_title']}","sort"=>$all['news_title'],"time"=>date('Y-m-d',$all['date_time']),"file"=>$file,"have"=>$have);
    		}
    		$this->assign('list',$arr);
    		$this->assign('page',$show);
    	}
    	 
    	//最近新闻
		$where_news['c_id']='1';
		$where_news['status']='1';
		$arr_news=$base->where($where_news)->order('date_time desc')->limit(10)->select();
		foreach ($arr_news as $n){
			$nl[]=array("c_id"=>"{$n['c_id']}","id"=>"{$n['id']}","title"=>"{$n[news_title]}","t_sort"=>_sort($n[news_title], 16),"time"=>date('Y/m/d',$n['date_time']));
		}
		$this->assign('newslist',$nl);
    	
    	
    	//友谊链接
    	$wherel['type']='2';
    	$l=$link->where($wherel)->order('id desc')->select();
    	$this->assign('link',$l);
    	
    	$this->display();
    }
    
}