<?php
// 本类由系统自动生成，仅供测试用途
class PublicAction extends CommonAction {
	//生成验证码
	Public function verify(){
	
		import("ORG.Util.Image");
	
		Image::buildImageVerify();
	
	}

}
?>