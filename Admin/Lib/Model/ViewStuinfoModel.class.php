<?php 
class  ViewStuinfoModel extends ViewModel  

{ 

  public $viewFields = array( 

  'User'=>array('id','username'), 

  'StudentDetail'=>array('age','number','birth_date','native_place','academy','dept','major','classtitle','school_length','degree_level','political_status', '_on'=>'User.id=StudentDetail.stu_id'), 

  

 ); 

} 
?>

