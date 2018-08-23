
<?php
class indexController extends Controller{ //继承某个类，实现公用方法
  public function index(){
    if(!$this->is_cached()){//如果失效，失效时间为配置项里面的的两秒，那就重新分配
      $this->assign('var',time());

    }
    $this->display();

  }
}

?>
