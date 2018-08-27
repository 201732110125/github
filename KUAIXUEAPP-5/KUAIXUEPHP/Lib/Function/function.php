<?php


function halt($error,$level='ERROR',$type=3,$dest=null){
  //先把错误信息写入日志
  if(is_array($error)){//如果错误信息为数组
    Log::write($error['message'],$level,$type,$dest);
  }else{//不是数组，那么就是字符串了，直接写入
    Log::write($error,$level,$type,$dest);
  }

  //进行错误追踪
  //$e用于存放错误信息
  $e=array();
  //如果开启debug
  //显示详细的错误信息
  if(DEBUG){
    if(!is_array($error)){//如果不是数组，而是个字符串
      $trace=debug_backtrace(); //这个函数返回文件的一些追踪引导
      $e["message"]=$error;
      $e['file']=$trace[0]['file'];
      $e['line']=$trace[0]['line'];
      $e['class']=isset($trace[0]['class'])?$trace[0]['class']:'';
      $e['function']=isset($trace[0]['function'])?$trace[0]['function']:'';

      ob_start();//开启缓冲区

      //相当于前面的debug_backtrace()函数的一个汇总
      //前面的函数是以数组形式返回跟踪内容
      //而这里则是先当与拼在一起写了
      //因为前面开启了缓冲区，所以不会显示到页面上，而是在缓冲区里
      debug_print_backtrace();
      $e['trace']=htmlspecialchars(ob_get_clean());
    }else{ //$error已经是数组了，已经在外部组合好了
      $e=$error;//直接赋值
    }
  }else{
    //不在debug模式下(用户模式下)，
    //此时页面发生问题则不能把前面那些详细的信息显示出来，页面上就显示我在配置文件里设定的内容
    if($url=C('ERROR_URL')){
      //如果用户在配置文件里定义了发生错误的跳转url
      go($url);
      return ;
    }else{
      //当用户没有定义发生错误时的跳转url时
      //就把配置文件的错误提示信息给$e['message']
      //用于以后页面上显示错误。
      $e['message']=C('ERROR_MSG');
    }
  }
  include DATA_PATH.'/Tpl/halt.html';
  die;//发生错误了，网页就没必要走下去了
}


//这个p函数，只要当框架的function.php文件被加载，就一定可以使用。
function p($arr){
  if(is_bool($arr)){//如果是布尔值
    var_dump($arr);
  }else if(is_null($arr)){//如果是null值
    var_dump(NULL);
  }else{
    echo '<pre style="padding:10px;border-radius:5px
    ;background:#f5f5f5;border:1px solid #ccc;
    font-size:14px;">'.print_r($arr,true)."</pre>";
  }

}

//页面跳转函数 $msg表示跳转的时候的提示信息
function go($url,$time=0,$msg=''){
  if(!headers_sent()){ //当已经发送过头部
    $time=0?header('Location:'.$url):header("refresh:{$time};url={$url}");
    die($msg);
  }else{
    echo "<meta http-euqiv='Refresh' content='{$time};URL={$url}'>";
    if($time) die($msg);
  }
}
//函数功能 C函数，表示config配置函数
//1。加载配置项，让用户配置项优先级更高
//C($sysConfig) C($useConfig);
//2。读取配置项  读取CODE_LEN的值
//C("CODE_LEN")
//3。临时动态改变配置项  改成20
//C("CODE_LEN",20);
//4.返回所有配置
//C()
function C($var=NULL,$value=NULL){//有默认值就可以什么都不传
  static $config=array();//用静态数组保存配置项

//载入配置项，并实现优先级
  if(is_array($var)){
    //为了保证用户优先级更高，先加载系统的配置项，再加载用户的，
    //用array_merge()函数实现覆盖
    //array_change_key_case()函数，把数组的键名转化，
    //这里CASE_UPPER表示转化为大写

    $config=array_merge($config,array_change_key_case($var,CASE_UPPER));
    return ;//return 只是让函数不走下面，不是返回什么东西
  }

//读取与动态改变单个配置项
  if(is_string($var)){
    $var=strtoupper($var);//转化为大写

    //两个参数传递，就是第二个参数不为空
    if(!is_null($value)){
      $config[$var]=$value;
      return;
    }

    //第二个参数为空时，表示查询，返回相应的配置项
    return isset($config[$var])?$config[$var]:null;
  }

  //什么都不传，表示查询全部配置项
  if(is_null($var)&&is_null($value)){
    return $config;
  }
}

//打印所有define常量
function print_const(){
  //当参数为true时，表示以二维数组的方式获得常量(以常量的属性分类)。这时候就可以通过
  //$const['user']的方式获得用户定义常量了
  $const=get_defined_constants(true);
  p($const['user']);
}

//数据库函数，在函数里就直接实例化好了，返回一个实例化对象
function M($table){
  $obj=new Model($table);
  return $obj;
}

//公共Model便捷的,传入一个公共模板类的类名，组合一下，
//在函数里创建对象，激发_autoload()函数，引入路径
//返回一个类的对象出去，就可以直接调用类方法了
function K($model){
  $model.='Model';
  return new $model();
}
?>
