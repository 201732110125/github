<?php
/*
继承CommonController类后，以后执行一个动作，都会检验你是否登陆了
就是调用了CommonController里面的__init()伪构造方法
 */
class indexController extends CommonController{ //继承某个类，实现公用方法

/*
后台默认显示首页
 */
public function index(){

	$this->display();
}
/*
欢迎界面
 */
public function welcome(){
	$this->display();
}

/*
用于测试的方法
 */
public function test(){
	$this->display();
}
}
?>
