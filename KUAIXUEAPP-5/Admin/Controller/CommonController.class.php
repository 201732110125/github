<?php 
/*
以后只要是要登陆的地方，都可以继承CommonController，
要是没有登陆，自动跳转到登陆界面。
 */
class CommonController extends Controller{
	/*
	这个__init()也是相当于构造函数，因为在执行Controller的构造方法的时候，自动加载了
	$this->__init();
	这个方法的目的就是，检验是否登陆，如果没有登陆就跳到登陆页面，不让你直接访问后台
	 */
	public function __init(){
		/*
		如果uid或uname不存在，就说明没有登陆，跳转到登陆页面。
		 */
		// session_unset();
		// p($_SESSION);die;
		if(!isset($_SESSION['uid'])||!isset($_SESSION['uname'])){
			go(__APP__.'?c=Login');//自动跳转到LoginController控制器上
		}
	}
}


 ?>