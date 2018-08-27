<?php 
/*
栏目管理
*/
 class CategoryController extends CommonController{

 	private $_model;
 	public function __auto(){
 		$this->_model=K('Category');
 	}
	//继承CommonController,只是为了验证登陆，大部分的功能还是要用到爷类Controller类
 	public function add(){
 		if(IS_POST){
 			$this->_model->add_data();
 			$this->success("添加成功",__APP__."?c=Category&a=index");
 		}
 		$this->display();
 	}
 	public function index(){
 		//为了展示所有栏目，所以要先把数据从数据库里调出来
 		$data=$this->_model->get_all();//返回的二维数组
 		//得到了数据了，那该怎么把数据分配过去呢
 		//这就要用到assign函数
 		$this->assign('data',$data);//厉害呀，还能这样分配。
 		$this->display();
 	}

 	public function del(){
 		// 因为前面定义了类变量$_model，且在构造方法里面把其赋值为K('')函数返回的对象了
 		$cid=(int)$_GET['cid'];
 		$this->_model->remove($cid);
 		$this->success('栏目删除成功');//不传地址，默认返回当前页面
 		//返回当前页面，就是栏目tpl下的index.html，而这个页面是在CategoryController下的index()方法调用的
 		//也就是说，重新加载index()方法。所以会重新从数据库里取值。
 		//然后重新根据数据库里的数据，重新填充表格
 	}

 	/*
 	编辑栏目
 	 */
 	public function edit(){
 		$cid=(int)$_GET['cid'];
 		$oldData=$this->_model->get_one($cid);
 		$this->assign("oldData",$oldData);//把旧数据分配过去
 		$this->display();//载入Admin/Tpl/Categroy/edit.html
 	}
 }


 ?>