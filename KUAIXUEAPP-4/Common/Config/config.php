<?php
return array(
  //配置项 => 配置值
  //验证码位数
  'CODE_LEN' =>5,

  'AUTO_LOAD_FILE'=>array('func1.php','func2.php','People.class.php'),

  //数据库配置文件
  'DB_CHARSET' =>'UTF8',//数据库字符集
  'DB_HOST' => '127.0.0.1',
  'DB_POST' => 3306,
  'DB_USER' => 'root',
  'DB_PASSWORD'=>'root',
  'DB_DATABASE'=>'book',//要选择的数据库名
  'DB_PREFIX'=>'',//表前缀
);
?>
