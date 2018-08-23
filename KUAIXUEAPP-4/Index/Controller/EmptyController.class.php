<?php
//当用户输入的控制器名不存在，就会自动跳转到这个控制器当中
class EmptyController extends Controller{

  public function __empty(){
    echo "方法不存在";
  }
  public function index(){
    echo "CLASS is not exists";
  }
}

 ?>
