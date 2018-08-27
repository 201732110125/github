<?php
//此类作为框架和Smarty类的连接类
class SmartyView{
  private static $smarty=NULL;


  public function __construct(){
    if(!is_null(self::$smarty)){//如果已经有$smarty了，我就不需要在new 了
       return ;
    }
    $smarty=new Smarty();
    //重新配置Smarty的模板目录
    $smarty->template_dir=APP_TPL_PATH.'/'.CONTROLLER.'/';
    //编译目录
    $smarty->compile_dir=APP_COMPILE_PATH;
    //缓存目录
    $smarty->cache_dir=APP_CACHE_PATH;
    //左右配置符
    $smarty->left_delimiter=C('LEFT_DELIMITER');
    $smarty->right_delimiter=C('RIGHT_DELIMITER');
    //是否开启缓存

    $smarty->caching=C("CACHE_ON");

    //设置缓存时间
    $smarty->cache_lifetime=C('CACHE_TIME');

    //配置完选项之后，把对象放到类静态属性里面，这样其他类方法就可以共享这个属性了
    self::$smarty=$smarty;//把对象赋值给类静态变量
  }
  //Smarty里面也有display和assign方法
  protected function display($tpl){
    self::$smarty->display($tpl,$_SERVER['REQUEST_URI']);
  }
  protected function assign($var,$value){
    self::$smarty->assign($var,$value);
  }

  //判断Smarty缓存是否失效
  protected function is_cached($tpl=NULL){
    if(!C('SMARTY_ON')){
      halt('请先开启Smarty!');
    }
    $tpl=$this->get_tpl($tpl);
    return self::$smarty->is_cached($tpl,$_SERVER['REQUEST_URI']);
  }
}

 ?>
