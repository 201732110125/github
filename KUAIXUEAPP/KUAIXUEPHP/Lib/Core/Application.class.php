<?php
//应用类
final class Application{
  public static function run(){
    self::_init(); //初始话框架

    //类似于自动加载函数，当代码报错的时候，自动执行error()函数
    //放到初始化框架后面，因为框架不可能出错，而下面的其他设置，是关于用户的
    //有出错的可能，所以放到他们前面，当后面一发生error()里面提前定义的错误
    //就进入到我自定义的错误界面里

    //这个fatal_error()函数，当发生致命错误的时候会调用，但是
    //就算没有发生致命错误，在整个php框架结束之后他也会自动被调用
    //所哟在函数里面要先把判断以下
    register_shutdown_function(array(__CLASS__,'fatal_error'));//致命性错误
    set_error_handler(array(__CLASS__,'error'));//一般性错误

    self::_user_import();//载入用户文件的函数
    self::_set_url();//设置外部路径

    //注册类的自动加载函数为函数 _autoload()函数
    //当实例化没有找到类的时候就去找_autoload函数，并把类名($className)传给这个自动加载函数
    spl_autoload_register(array(__CLASS__,'_autoload'));

    self::_create_demo();//在Index/Controller下面创建indexController.class.php类文件
    self::_app_run();//实例化应用控制器
  }




//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  //初始化框架
  private static function _init(){
    //首先，加载配置项，并且使用户的优先级更高
    C(include CONFIG_PATH.'/config.php');//用C函数加载系统配置项

    //加载公共配置项common
    $commonPath=COMMON_CONFIG_PATH.'/config.php';
    $commonConfig=<<<str
<?php
return array(
  //配置项 => 配置值

);
?>
str;
    is_file($commonPath)||file_put_contents($commonPath,$commonConfig);
    C(include $commonPath);

    //因为用户配置文件不一定存在，要是直接像系统配置文件那样用C()处理
    //因为不存在文件，所以会报错，故要对文件做一个是否存在的检测
    $userPath=APP_CONFIG_PATH.'/config.php';
    $userConfig= <<<str
    <?php
    return array(
      //配置项=>配置值
    );
    ?>
str;
    //当文件不存在时，创建文件，并写入$userConfig里面的内容
    is_file($userPath)||file_put_contents($userPath,$userConfig);
    //经过上面的处理，可以保证，用户配置项一定存在，下面就可以放心大胆 用C()
    //函数来加载用户配置项了，因为是在系统配置文件之后加载，实现了用户优先级
    //高于系统优先级的目标
    //加载配置项
    C(include $userPath);


    //设置默认时区
    //这里设置时区的值是C函数从配置文件里取来的
    date_default_timezone_set(C('DEFAULT_TIME_ZONE'));

    //是否开启session
    C('SESSION_AUTO_START')&&session_start();
  }

  //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  public static function fatal_error(){//这个函数没有参数，因为都致命了，还要啥参数呀？

    //这个error_get_last()函数，返回到是上一次发生错误的错误信息
    //是一个包含$errno 错误类型/等级，$error 错误信息 ，$file 错误的文件 ，$line 错误行号
    //的数组。当没有错误发生的时候，返回为false，
    //此函数用于检测是否发生了错误，还是php代码跑到最后自动加载了这个fatal_error()函数
    if($e=error_get_last()){//如果真的发生了错误
      //$e里面包含了返回的错误信息
      //array(4) { ["type"]["message"] ["file"] ["line"] }
      //其实就是对应error函数里面的四个参数
      //还是调用error()函数，只不过set_error_handler(array(__CLASS__,'error'))
      //抓不到致命错误，所以要用  register_shutdown_function(array(__CLASS__,'fatal_error'))
      //来抓，抓到后把数据封装以下，传给error()函数
      //也就是说，error()函数，一方面接接收set_error_handler(array(__CLASS__,'error'))自动传来的错误
      //另一方面又接收fatal_error()传来的封装好的错误信息
      self::error($e['type'],$e['message'],$e['file'],$e['line']);
    }
  }
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//类似于自动加载函数，当代码报错的时候，自动执行error()函数，
//让错误不是直接反馈到页面上，而是跑到我这边来进行处理
//$errno 错误类型/等级，$error 错误信息 ，$file 错误的文件 ，$line 错误行号
public static function error($errno,$error,$file,$line){
  switch ($errno) {//根据接到的错误类型分类
    case E_ERROR://致命性错误
    case E_PARSE://语句解析错误
    case E_CORE_ERROR://启动初始化过程中的致命错误
    case E_COMPILE_ERROR://编译的时候产生的错误
    case E_USER_ERROR://用户自定义的错误
      echo "enter fatal_error";
      $msg=$error.$file."第{$line}行";

      halt($msg);//发生致命性错误，直接停止，并写入日志文件
      break;

    case E_STRICT://代码标准化错误
    echo 1;
    case E_USER_WARNING://警告性错误
    echo 2;
    case E_USER_NOTICE://通知性错误
    echo 3;
    default:
      if(DEBUG){//如果是在debug模式下，就把错误模板载入
        include DATA_PATH.'/Tpl/notice.html';
      }
      break;
  }
}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//载入用户文件，用户自己定义的方法或类文件
//根据AUTO_LOAD_FILE配置项里的文件名载入
private static function _user_import(){
    $fileArr=C('AUTO_LOAD_FILE');

    //如果是个数组(因为用户会在Common文件中乱定义，且用户优先级高)
    //且数组不为空，就循环载入文件
    //且数组为空，就啥都不载入
    if(is_array($fileArr)&&!empty($fileArr)){
      foreach($fileArr as $v){

        // echo COMMON_LIB_PATH.'/'.$v."<br/>";
        //循环载入
        require_once COMMON_LIB_PATH.'/'.$v;
      }
    }
  }


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //设置外部路径，引入css等文件
    //这个url相当于网络路径，与前面的物理路径不同
    private static function _set_url(){

      //首页url地址
      //$_SERVER['SCRIPT_NAME']，表示脚本路径。因为这文件目前都是在index.php
      //入口文件下被引入的，所以url为index.php
      $path='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
      $path=str_replace("\\","/",$path);

      //__APP__：http://localhost/KUAIXUEAPP/index.php
      define('__APP__',$path);


      //根目录，与配置文件中物理路径的ROOT_PATH，APP_PATH不同的是
      //配置文件中的目录是文件目录 APP_PATH :C:/phpStudy/PHPTutorial/WWW/KUAIXUEAPP/Admin
      //而这里的是指url地址。浏览器所访问的地址 __APP__: http://localhost/KUAIXUEAPP/admin.php
      define('__ROOT__',dirname(__APP__));

      define('__TPL__',__ROOT__.'/'.APP_NAME.'/Tpl');
      define('__PUBLIC__',__TPL__.'/Public');
    }




//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //实现自动载入功能，$clssName就是当前要载入的类的名字
    //现在再_app_run()函数里，要实例化一个名叫$c的类，
    //但是这个类在模块下的Controller文件夹下，目前还没有include进来，会报错
    //通过前面的spl_autoload_register(array(__CLASS__,'_autoload'))
    //_autoload()函数可以知道我当前要实例化的类的名字
    //而且所有的类都在固定文件夹下，通过这样(知要实例化类的名字)，
    //include 进来相应的文件夹
    private static function _autoload($className){

      //要自动载入的可能是控制器类(类名以Controller结尾)，
      //也可能是其他的Extends扩展类库里面的类，不确定~~
      switch (true) {
        case strlen($className)>10&&substr($className,-10)=='Controller':
        //以上条件说明是个控制器类（但是可能是EmptyController类）
          $path=APP_CONTROLLER_PATH.'/'.$className.'.class.php';

          //如果组合起来的路径不存在，那么就执行两个选择
          //1.如果用户创建了EmptyController文件，用于处理控制器类文件不存在的情况，
          //那么加载EmptyController类，其实在前面的_app_run中就有过一次分类了

          //2.若没有创建EmptyController文件，就直接halt()报错。
          if(!is_file($path)){

            //设置空类的路径
            $emptyPath=APP_CONTROLLER_PATH.'/EmptyController.class.php';
            if(is_file($emptyPath)){
              //如果空类存在，就引入，用于处理控制器类找不到的情况
              //但是空类也可能不存在，用户可能压根没有创建
              include $emptyPath;
              return ;
            }else{
               halt($path."控制器未找到");
            }

          }else{
              include $path;//控制器路径存在，就引入
          }


          // code...
          break;

        default:
        //目前除了控制器就是工具类了，放在TOOL_PATH下面。
        $path=TOOL_PATH.'/'.$className.'.class.php';
        if(!is_file($path)) halt($path."类未找到");
        include $path;
          // code...
          break;
      }
      // echo $className;
      // echo APP_CONTROLLER_PATH.'/'.$className.'.class.php';
    }



//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//在Index/Controller下面创建indexController.class.php类文件
    private static function _create_demo(){
      $path=APP_CONTROLLER_PATH.'/IndexController.class.php';
      $str= <<<str
<?php
class indexController extends Controller{ //继承某个类，实现公用方法
public function index(){
  header('content-type:text/html;charset=utf-8');
  echo "<h2>欢迎使用ZJ框架 (:!</h2>";
}
}
?>
str;
      is_file($path) || file_put_contents($path,$str);
    }


  //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  //
  //实例化应用控制器
  private static function _app_run(){
    //你传入什么控制器类和其中的方法，我就实例化哪个类(自动include类函数所在文件)，
    //并通过控制器类的对象，防卫其中的方法
    //$c表示控制器 $a表示操作方法
    $c=isset($_GET[C('VAR_CONTROLLER')])?$_GET[C('VAR_CONTROLLER')]:'Index';
    $a=isset($_GET[C('VAR_ACTION')])?$_GET[C('VAR_ACTION')]:'index';

    //根据所传入的控制器与方法，动态定义控制器常量和操作方法常量
    define('CONTROLLER',$c);
    define('ACTION',$a);
    $c.='Controller';
    if(class_exists($c)){//class_exists()函数会自动调用_autoload()自动加载函数
      //如果用户在浏览器中输入的类存在(在KUAIXUEAPP/Index/Controller/)，但只是没有引入

      //因为上面把传来的控制器类的名字放在变量$c里面
      //所以我要实例化这个控制类的时候，直接new $c()便可
      $obj=new $c();//因为没有引入，所以触发_autoload()函数，并把$c作为$className参数传入
      if(!method_exists($obj,$a)){//如果类中没有这个方法
        if(method_exists($obj,'__empty')){
          $obj->__empty(); //用自定义的错误显示给用户看
        }else{//用户访问的控制器里没有定义$a方法，而且也没有定义__empty()函数，说明就直接报错就好了
          halt($c."控制器中".$c."方法不存在！");
        }
      }else{//类中有这个方法
        //同上，控制类的要执行的操作方法名已经放在变量$a里边了
        //要访问该控制器类下面的方法只要$obj->$a();
        $obj->$a();//
      }

    }else{
      //用户乱输入，就是在_autoload()函数里面也找不到，无法引入
      //实例化EmptyController()类，让这个笑面客来处理这类用户
      $obj=new EmptyController();//也会触发_autoload()函数，把EmptyController作为$className参数传入
      $obj->index();
    }

  }






}
?>
