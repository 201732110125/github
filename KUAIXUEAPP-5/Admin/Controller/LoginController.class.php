<?php 
class LoginController extends Controller{
	public function index(){
		if(IS_POST){//如果有post提交数据

			//K()函数，传入一个模型类名，返回一个类对象，不用再自己new 对象了
			if($userInfo=K("User")->validate($_POST['username'],$_POST['password'])){//如果返回的值不为false,就说明登陆成功
				//登陆成功，success，跳转到后台首页，就是admin.php，然后就是实例化
				//Admin/Controller/IndexController类，并执行index方法
				//就是相当于重来一遍
				$_SESSION['uid']=$userInfo['uid'];
				$_SESSION['uname']=$userInfo['username'];
				
				$this->success('登陆成功',__APP__);
			}else{
				$this->error('登陆失败');
			}
			
		}
		$this->display();
	}

	/*
	退出登陆
	 */
	public function out(){
		session_unset();
		session_destroy();
		$this->success("退出成功",__APP__.'?c=Login');
	}
}

 ?>