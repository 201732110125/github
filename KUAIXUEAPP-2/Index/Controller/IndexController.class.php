<?php
class indexController extends Controller{ //继承某个类，实现公用方法
  public function index(){
    $data=M('users')->field('password')->where('username="bob"')->limit(1)->all();
    p($data);
  }

}

?>
