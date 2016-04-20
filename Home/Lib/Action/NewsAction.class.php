<?php
// 本类由系统自动生成，仅供测试用途
class NewsAction extends CommonAction{
    Public function _initialize(){
        //显示logo
        $link=M('Link');
        $where_logo['type']='0';
        $where_logo['display']='1';
        $arr_logo=$link->where($where_logo)->find();
        $this->assign('logo',$arr_logo);

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

        //显示底部版权信息
        $copyright = $base->where(array('id' => 39))->find();
        $this->assign('copyright', $copyright);

        //友谊链接
        $link=M('Link');
        $wherel['type']='2';
        $l=$link->where($wherel)->order('id desc')->select();
        $this->assign('link',$l);


        //输出导航
        $c_id = $_GET['c_id'];
        $id = $_GET['id'];
        if (empty($c_id) && empty($id)) {
            $this->redirect('/Index/index');
        }
        if (!empty($c_id)) {
            $names = M('Link')->where('c_id='.$c_id)->find();
            $classArrs = M('Newsclass')->where('c_id='.$c_id)->find();
            if ($names['lev'] != 0) {
                $names = M('Link')->where('c_id='.$names['lev'])->find();
            }
            $pathArr = explode('-', $classArrs['path']);
            $pathArr[] = $c_id;
            $paths = array();                       //面包屑导航
            foreach($pathArr as $v) {
                $linkArr = M('Link')->where('c_id='.$v)->find();
                if ($linkArr['c_id'] == $c_id) {
                    $linkArr['act'] = 1;
                }
                $paths[] = $linkArr;
            }
            $leftPaths = M('Link')->where('lev='.$names['c_id'])->select();                   //左侧导航
            foreach ($leftPaths as $key=>$leftPath) {
                if ($leftPath['c_id'] == $c_id) {
                    $leftPaths[$key]['act'] = 1;
                }
            }
        } else {
            $sql = 'SELECT think_newsclass.name,think_newsclass.c_id,think_newsclass.pid
                    FROM think_newsclass,think_newsbase
                    WHERE think_newsclass.c_id=think_newsbase.c_id AND think_newsbase.id='.$id;
            $names = M()->query($sql);
            $names = $names[0];
            $c_id = $names['c_id'];
            if ($names['pid'] != 0) {
                $names = M('Newsclass')->where('c_id='.$names['pid'])->find();
            }
            $classArrs = M('Newsclass')->where('c_id='.$names['c_id'])->find();
            $pathArr = explode('-', $classArrs['path']);
            $pathArr[] = $names['c_id'];
            $paths = array();                       //面包屑导航
            foreach($pathArr as $v) {
                $linkArr = M('Link')->where('c_id='.$v)->find();
                if ($linkArr['c_id'] == $names['c_id']) {
                    $linkArr['act'] = 1;
                }
                $paths[] = $linkArr;
            }
            $leftPaths = M('Link')->where('lev='.$names['c_id'])->select();                     //左侧导航
            foreach ($leftPaths as $key=>$leftPath) {
                if ($leftPath['c_id'] == $c_id) {
                    $leftPaths[$key]['act'] = 1;
                }
            }
        }
        $name = $names['name'];
        $nav = $this -> _nav($name);	            //来自CommonAction
        $this->assign('nav',$nav);
        $this->assign('paths', $paths);             //面包屑导航-当前位置
        $this->assign('leftTop', $name);            //左侧副导航顶部文字
        $this->assign('leftPaths', $leftPaths);
    }

	//各类模块
	public function newtab(){
		if(empty($_GET['c_id']) && empty($_GET['id'])){
			$this->redirect('/Index/index');
		}
		//模块内容
		$base=M('Newsbase');
		if(empty($_GET['id'])){
			$con_id['c_id'] = intval($_GET['c_id']);
			$con_id['status']='1';
			$news=$base->where($con_id)->order('date_time desc')->limit(1)->select();
			$this->assign('new',$news);
            $seo['title'] = $news[0]['news_title'];
            $this->assign('seo', $seo);
			
			//上一条
			$up['id']=array('gt',$news[0]['id']);
			$up['c_id']=$_GET['c_id'];
			$upcon=$base->where($up)->limit(1)->select();
			$this->assign('up',$upcon);
			
			//下一条
			$down['id']=array('lt',$news[0]['id']);
			$down['c_id']=$_GET['c_id'];
			$downcon=$base->where($down)->limit(1)->select();
			$this->assign('up',$upcon);
			$this->assign('down',$downcon);
		}else{
			$con_id['id'] = intval($_GET['id']);
			$con_id['status']='1';
			$news=$base->where($con_id)->limit(1)->select();
			foreach ($news as $n){
				$file = M('File');
				$where_file['n_id'] = $n['id'];
				$arr_files = $file ->where($where_file) ->select(); 
				$new[] = array("id"=>$n['id'],"c_id"=>$n['c_id'],"news_title"=>$n['news_title'],"autho"=>$n['autho'],"date_time"=>$n['date_time'],"content"=>$n['content'],"hit"=>$n['hit'],"file"=>$arr_files );
			}
			$this->assign('new',$new);
            $seo['title'] = $new[0]['news_title'];
            $this->assign('seo', $seo);
			
			//上一条
			$up['id']=array('gt',$_GET['id']);
			$up['c_id']=$new[0]['c_id'];
			$upcon=$base->where($up)->limit(1)->select();
			$this->assign('up',$upcon);
			
			//下一条
			$down['id']=array('lt',$_GET['id']);
			$down['c_id']=$new[0]['c_id'];
			$downcon=$base->where($down)->limit(1)->select();
			$this->assign('up',$upcon);
			$this->assign('down',$downcon);
			
			$this->assign('c_id',$_GET['c_id']);
		}
        $this->display();
	}
	
	public function newlist(){
		//新闻列表
        if(empty($_GET['c_id'])){
            $this->redirect('/Index/index');
        }
		$base=M('Newsbase');
		$wherem['c_id'] = intval($_GET['c_id']);
		$wherem['status']='1';
		import('ORG.Util.Page');// 导入分页类
		$count=$base->where($wherem)->order('date_time desc')->count();// 查询满足要求的总记录数
		$page=new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
		$page->setConfig('header','条记录');//修好显示内容
		$show=$page->show();// 分页显示输出
		$nei=$base->where($wherem)->order('date_time desc')->limit($page->firstRow.','.$page->listRows)->select();
		foreach ($nei as $all){
			$file=M('File');
			$f['n_id']=$all['id'];
			$file=$file->where($f)->select();
			if(!empty($file)&&empty($all['content'])){
				$have='1';
			}else{
				$have='0';
			}
			$arr[]=array("id"=>"{$all['id']}","c_id"=>"{$all['c_id']}","title"=>"{$all['news_title']}","sort"=>_sort($all['news_title'], 32),"time"=>date('Y-m-d',$all['date_time']),"file"=>$file,"have"=>$have);
		}

		$this->assign('list',$arr);
		$this->assign('page',$show);
        $seo['title'] = self::getClassName((int)$_GET['c_id']);
        $this->assign('seo', $seo);
		
		$this->display();
	}

    /**
     * 图片新闻列表
     */
    public function imgnewlist() {
        if(empty($_GET['c_id'])){
            $this->redirect('/Index/index');
        }
        $base=M('Newsbase');
        $wherem['c_id'] = intval($_GET['c_id']);
        $wherem['status']='1';
        import('ORG.Util.Page');// 导入分页类
        $count=$base->where($wherem)->order('date_time desc')->count();// 查询满足要求的总记录数
        $page=new Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数
        $page->setConfig('header','条记录');//修好显示内容
        $show=$page->show();// 分页显示输出
        $nei=$base->where($wherem)->order('date_time desc')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($nei as $all){
            $file=M('File');
            $f['n_id'] = $all['id'];
            $f['type'] = 1;
            $file=$file->where($f)->find();
            if(!empty($file) && empty($all['content'])){
                $have='1';
            }else{
                $have='0';
            }
            $arr[]=array("id"=>$all['id'],"c_id"=>$all['c_id'],"title"=>$all['news_title'],"sort"=>_sort($all['news_title'], 32),"time"=>date('Y-m-d',$all['date_time']),"file"=>$file,"have"=>$have);
        }
        $this->assign('list',$arr);
        $this->assign('page',$show);
        $seo['title'] = self::getClassName((int)$_GET['c_id']);
        $this->assign('seo', $seo);
        $this->display();
    }

    /**
     * 图片新闻内容显示
     */
    public function imgnewtab() {
        if(empty($_GET['id'])){
            $this->redirect('/Index/index');
        }
        $base = M('Newsbase');
        $con_id['id']=intval($_GET['id']);
        $con_id['status']='1';
        $news=$base->where($con_id)->limit(1)->find();
        $file = M('File');
        $where_file['n_id'] = $news['id'];
        $where_file['type'] = 1;
        $arr_files = $file ->where($where_file) ->select();


        $this->assign('new',$news);
        $this->assign('img', $arr_files);
        $seo['title'] = $news['news_title'];
        $this->assign('seo', $seo);

//        print_r($news);die;

        //上一条
        $up['id'] = array('gt',$_GET['id']);
        $up['c_id'] = $news['c_id'];
        $upcon = $base->where($up)->limit(1)->select();
        $this->assign('up',$upcon);

        //下一条
        $down['id'] = array('lt',$_GET['id']);
        $down['c_id'] = $news['c_id'];
        $downcon = $base->where($down)->limit(1)->select();
        $this->assign('up',$upcon);
        $this->assign('down',$downcon);

        $this->display();
    }

	//文件下载
	public function downloadF(){
		$file=M('File');
		$where['f_id']=$_GET['fid'];
		$arr=$file->where($where)->find();
		//更新资源点击数
		$data['hit'] = $arr['hit'] + 1;
		$where_file['id'] = $arr['id'];
		$file ->where($where_file) ->data($data) ->save();
		$filename='./Public/upload/news/'.$arr['name'];
		if (file_exists($filename)) {
		    header('Content-Description: File Transfer');
			header("Content-Type: application/force-download");
		    header('Content-Type: application/octet-stream');
		    header("Content-Type: application/download");
		    header('Content-Disposition: attachment; filename='.$arr['title']);
		    header('Content-Transfer-Encoding: binary');
		    header("Accept-Ranges: bytes");
		    header('Expires: 0');
		    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		    header('Pragma: public');
		    header('Content-Length:'.($arr['size'])+0);
// 		    ob_clean();
		    flush();
		    readfile($filename);
		    exit;
		}
		else{
			echo "<span style='color:red;font-size:22px;'>The file is not exist!!!!</span>";
		}
	}

    private function getClassName($cid) {
        $class = M('Newsclass')->where(array('c_id' => $cid))->find();
        if ($class['name']) {
            return $class['name'];
        } else {
            return false;
        }
    }
}
?>
