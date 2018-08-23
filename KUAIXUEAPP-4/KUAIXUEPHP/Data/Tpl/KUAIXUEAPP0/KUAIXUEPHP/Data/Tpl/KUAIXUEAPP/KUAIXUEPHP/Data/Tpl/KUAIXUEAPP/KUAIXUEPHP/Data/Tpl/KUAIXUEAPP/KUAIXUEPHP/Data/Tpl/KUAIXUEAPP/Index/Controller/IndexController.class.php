<?php
class indexController extends Controller{ //继承某个类，实现公用方法
  public function index(){
    if(IS_POST){
      // p($_POST);
      M('users')->add();
      $this->success('添加成功');
    }
    $this->display();

  }

}

?>
