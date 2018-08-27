<?php
final class KUAIXUEPHP{

  public static function run(){

    self::_set_const(); //定义框架所需常量,主要为框架里的文件的物理路径

    //检测DEBUG常量是否存在，若存在则说明在入口文件处开启了DEBUG模式
    //若不存在，则说明入口处没有开启DEBUG模式，这里就把他设置为false
    defined("DEBUG")||define("DEBUG",false);

    //debug模式下，显示所有错误，并把整个项目所需要的文件重新安装一遍(如用户的应用)
    if(DEBUG){
      self::_create_dir();//创建目录,文件夹
      self::_import_file();//载入框架所需核心文件，再debug模式下第一次载入，并把所有要载入的文件全都合并到Temp下的~boot.php文件中，下次引入那个文件就好了
    }else{
      //表示在非debug模式下，屏蔽掉所有错误
      //不再创建文件夹，并引用Temp下的~boot.php文件来加载框架所需文件

      error_reporting(0);//屏蔽所有错误显示在页面上

      //这里是在第一次加载完配置文件后，框架把所有要引入的文件全部放到TEMP目录的~boot.php文件中
      //下次再非debug模式下，只要直接引用这个文件就可以了，不用再一个一个的载入系统核心文件了
      require TEMP_PATH.'/~boot.php';
    }

    Application::run();
  }

  //设置框架所需常量，物理路径
  private static function _set_const(){
    // C:\phpStudy\PHPTutorial\WWW\KUAIXUEAPP\KUAIXUEPHP\KUAIXUEPHP.php"
    // var_dump(__FILE__); 魔术常量获得路径
    $path=str_replace("\\","/",__FILE__);

    // 得到框架目录WWW/KUAIXUEAPP/KUAIXUEPHP
    define("KUAIXUEPHP_PATH",dirname($path));

    //定义框架文件的目录
    define("CONFIG_PATH",KUAIXUEPHP_PATH.'/Config');
    define('DATA_PATH',KUAIXUEPHP_PATH.'/Data');

    define("LIB_PATH",KUAIXUEPHP_PATH.'/Lib');
    define("CORE_PATH",LIB_PATH.'/Core');
    define("FUNCTION_PATH",LIB_PATH."/Function");

    define("ROOT_PATH",dirname(KUAIXUEPHP_PATH));//整个项目的根目录

    //临时目录
    define('TEMP_PATH',ROOT_PATH.'/Temp');
    //日志目录
    define('LOG_PATH',TEMP_PATH.'/Log');

    //项目的应用目录，由项目根目录加上入口文件传来的应用名称组合起来的
    define("APP_PATH",ROOT_PATH."/".APP_NAME);
    define("APP_CONFIG_PATH",APP_PATH."/Config");
    define("APP_CONTROLLER_PATH",APP_PATH."/Controller");
    define("APP_TPL_PATH",APP_PATH."/Tpl");//模板目录
    define("APP_PUBLIC_PATH",APP_TPL_PATH."/Public");

    define('APP_COMPILE_PATH',TEMP_PATH.'/'.APP_NAME.'/Compile');//Smarty编译目录
    define('APP_CACHE_PATH',TEMP_PATH.'/'.APP_NAME.'/Cache');//Smarty的缓存目录
    // echo APP_COMPILE_PATH."<br/>";
    // echo APP_CACHE_PATH;

    //判断是否为post提交数据，用于从页面上取的$_POST[]里面的信息
    //如果为true，说明了已经提交了数据，我就可以直接去$_POST[]
    //里面取数据，
    define('IS_POST',($_SERVER['REQUEST_METHOD'])=='POST'?TRUE:FALSE);


    //判断$_SERVER['HTTP_X_REQUESTED_WITH']是否==XMLHttpRequest
    //若等于，则表示存在ajax异步请求，那么就IS_AJAx==true,
    //否则，IS_AJAX就为false
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest'){
      define('IS_AJAX',TRUE);
    }else{
      define('IS_AJAX',FALSE);
    }


    //创建公共文件夹
    define('COMMON_PATH',ROOT_PATH.'/Common');
    //公共配置项文件夹
    define('COMMON_CONFIG_PATH',COMMON_PATH.'/Config');
    //公共模型文件夹
    define('COMMON_MODEL_PATH',COMMON_PATH.'/Model');
    //公共库文件夹
    define('COMMON_LIB_PATH',COMMON_PATH.'/Lib');

    //框架扩展的目录，因为是框架本生的目录，不用创建，本来就有的
    define('EXTENDS_PATH',KUAIXUEPHP_PATH.'/Extends');
    define('ORG_PATH',EXTENDS_PATH.'/Org');
    define('TOOL_PATH',EXTENDS_PATH.'/Tool');


    define("KUAIXUEAPP_VERSION","1.0");//框架版本
  }

  //创建应用目录
  private static function _create_dir(){
    $arr=array(//模块文件数组，用于集中创建创建文件夹
      COMMON_PATH,
      COMMON_CONFIG_PATH,
      COMMON_MODEL_PATH,
      COMMON_LIB_PATH,
      APP_PATH,
      APP_CONFIG_PATH,
      APP_CONTROLLER_PATH,
      APP_TPL_PATH,
      APP_PUBLIC_PATH,
      TEMP_PATH,
      APP_COMPILE_PATH,
      APP_CACHE_PATH,
      LOG_PATH,

    );
    foreach($arr as $v){//文件创建
      //如果是个文件那就不创建了，如果不是就创建一个文件
      is_dir($v)||mkdir($v,0777,true); //0777代表最高权限，true代表递归创建
    }
    //把框架模板复制到应用用户模块下，如果有了，就不复制了
    is_file(APP_TPL_PATH.'/success.html')||copy(DATA_PATH.'/Tpl/success.html',APP_TPL_PATH.'/success.html');
    is_file(APP_TPL_PATH.'/error.html')||copy(DATA_PATH.'/Tpl/error.html',APP_TPL_PATH.'/error.html');




  }

  //载入框架所需文件
  private static function _import_file(){
    $fileArr=array(
      FUNCTION_PATH.'/function.php', //先载入函数库
      ORG_PATH.'/Smarty/Smarty.class.php',//载入Smarty第三方库
      CORE_PATH.'/SmartyView.class.php',//载入框架与Smarty类的联系类
      CORE_PATH.'/Controller.class.php',//载入父类文件
      CORE_PATH.'/Application.class.php',//再载入应用类
      CORE_PATH.'/Log.class.php',//载入日志类，有write()方法
    );
    $str='';
    foreach($fileArr as $v){ //循环载入数组中的文件
      //把所有要加载的文件组合成一个字符串，存到$str里面，注意是最后-4不是-2
      $str.=trim(substr(file_get_contents($v),5,-4));
      require_once $v;
    }

    $str="<?php\r\n".$str."?>";
    //把前边组合好的$str内容字符串，放到Temp下的~boot.php文件里
    file_put_contents(TEMP_PATH.'/~boot.php',$str)||die('access not allow');
  }


}
KUAIXUEPHP::run();
 ?>
