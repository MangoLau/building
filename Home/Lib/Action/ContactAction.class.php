<?php
// 本类由系统自动生成，仅供测试用途
class ContactAction extends CommonAction {
    Public function _initialize(){
        $seo['title'] = '联系我们-建材网站';
        $seo['keywords'] = '建筑材料 质量保证';
        $seo['description'] = '建筑材料 质量保证';
        $this->assign('seo', $seo);

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

        //输出导航
        $nav = $this -> _nav('联系我们');	//来自CommonAction
        $this->assign('nav',$nav);

        //友谊链接
        $link=M('Link');
        $wherel['type']='2';
        $l=$link->where($wherel)->order('id desc')->select();
        $this->assign('link',$l);
    }

    /**
     * 添加留言
     */
	function contactus(){
		if(IS_POST){
//			if (empty($_POST['verify'])){
//                $return['code'] = 502;
//                $return['success'] = false;
//                $return['msg'] = '验证码必须！';
//                $this->ajaxReturn($return);
//			}
//			if($_SESSION['verify'] != md5($_POST['verify'])) {
//                $return['code'] = 502;
//                $return['success'] = false;
//                $return['msg'] = '验证码错误！';
//                $this->ajaxReturn($return);
//			}
			$words = D('Words');
			if(!$words->create()) {
                $return['code'] = 502;
                $return['success'] = false;
				$return['msg'] = $words->getError();
                $this->ajaxReturn($return);
			}else{
				$lastid=$words->add();
				if($lastid){
                    $return['code'] = 200;
                    $return['success'] = true;
                    $return['msg'] = '留言成功！';
                    $this->ajaxReturn($return);
				}else{
                    $return['code'] = 502;
                    $return['success'] = false;
                    $return['msg'] = $words->getError();
                    $this->ajaxReturn($return);
				}
			}		
		}

        //联系我们内容
		$list = M('newsbase')->where(array('id' => 7))->find();
        $this->assign('list', $list);
		$this->display();
	}
}
?>