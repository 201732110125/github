<?php
//作为框架核心类的父类，用于其他核心类继承
class Controller{
  private $var=array();//给assign()分派函数用


  protected function success($msg,$url=null,$time=3){
    $url=$url?"window.location.href='".$url."'":'window.history.back(-1)';
    include APP_TPL_PATH.'/success.html';
    die;
  }

  protected function error($msg,$url=null,$time=3){
    $url=$url?"window.location.href='".$url."'":'window.history.back(-1)';

    //把数组里的键作为变量名，值作为变量值实现出来

    include APP_TPL_PATH.'/error.html';
    die;
  }

  //分派函数，把传入的变量的键和值赋给类常量数组$var,
  //实现跨页面的传值效果
  protected function assign($var,$value){
    $this->var[$var]=$value;
  }
  //这个方法用于控制器载入模板
  protected function display($tpl=null){
    if(is_null($tpl)){
      //如果没有参数，默认载入
      // Tpl/控制器名/方法名.html
      $path=APP_TPL_PATH.'/'.CONTROLLER.'/'.ACTION.".html";
      // echo $path;
    }else{ //用户指定要载入哪个文件
      $suffix=strrchr($tpl,".");//后缀名
      //默认是载入.html文件，但要是用户指定了要载入文件的类型，就按照他的来
      $tpl=empty($suffix)?$tpl.".html":$tpl;
      $path=APP_TPL_PATH.'/'.CONTROLLER.'/'.$tpl;
    }

    //如果要载入的文件不存在
    if(!is_file($path)) halt($path."模板文件不存在");
    extract($this->var);
    include $path;//经过上面的验证后，发现$path存在，那就导入
  }
  //有些时候父类的构造方法一定要执行
  //但是子类也有自己的构造方法，于是会覆盖掉父类原有的构造方法
  //使得父类构造方法失效，框架无法执行
  public function __construct(){
    //method_exists()判断当前对象中是否含有__init()方法
    if(method_exists($this,'__init')){
      //如果有__init()，就调用这个方法
      //在被子类继之后，把子类的构造方法里的操作放到__init()函数里
      //这样就不会产生冲突了。即父类子类的构造方法都能完成
      //只不过是子类的构造方法变成了__init()
      $this->__init();

    //有可能会用到多个类继承，那么__init()也会被覆盖
    //那就在定义一个__auto()函数作为孙类的构造函数
    if(method_exists($this,'__auto')){
      $this->__auto();
    }
    }
  }
}
?>
