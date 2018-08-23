<?php
return array(
  //验证码位数
  'CODE_LEN' =>4,

  //默认时区
  'DEFAULT_TIME_ZONE' =>'PRC',

  //session自动开启
  'SESSION_AUTO_START' =>TRUE,

  'VAR_ACTION'=> 'a',
  'VAR_CONTROLLER'=>'c',

  //是否开启日志
  'SAVE_LOG'=>TRUE,

  //发生错误时跳转的地址
  'ERROR_URL'=>'',
  //错误提示的信息
  'ERROR_MSG'=>'网站出错了。。。请稍后再试',

  //自动加载Common/Lib目录下的文件，可以载入多个
  'AUTO_LOAD_FILE'=>array(),

  //数据库配置文件
  'DB_CHARSET' =>'UTF8',//数据库字符集
  'DB_HOST' => '127.0.0.1',
  'DB_POST' => 3306,
  'DB_USER' => 'root',
  'DB_PASSWORD'=>'root',
  'DB_DATABASE'=>'',//要选择的数据库名
  'DB_PREFIX'=>'',//表前缀
)
 ?>
