<?php
class indexController extends Controller{ //继承某个类，实现公用方法
  public function index(){
    header('content-type:text/html;charset=utf-8');
    echo "<h2>欢迎使用ZJ框架 (:!</h2>";
  }
}
?>