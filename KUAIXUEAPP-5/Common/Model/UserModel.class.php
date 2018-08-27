<?php 
/*
这个Model是在KUAIXUEPHP/Extends/Tool下面的工具类
在自动加载函数里面，defalut里面就是引入这种类
而这个Model类，包含很多种对数据库操作方法
 */
class UserModel extends Model{
	public $table='user'; //要操作的表名
	/*
	验证函数，判断输入的用户名的密码是否在数据库中
	 */
	public function validate($username,$password){
		//如果用户名为空，返回错误
		if(!$username){
			return false;
		}
		//总的来说还是安全的，虽然注入会绕过用户名检验，但是，
		//任然需要你的知道密码
		//$this就是当前模型，用它直接调用继承自Model类的方法where
		$userInfo=$this->where('username="'.$username.'"')->find();

		//如果查询的结果不存在，用户名不存在，返回false
		if(!$userInfo) return false;
		//密码错误
		if($userInfo['password']!=md5($password)) return false;
		//经过九九八十一难，说明你确实是个管理员
		
		return $userInfo;//因为外面需要uid和uname,所以我把$userInfo返回出去
	}
}

 ?>