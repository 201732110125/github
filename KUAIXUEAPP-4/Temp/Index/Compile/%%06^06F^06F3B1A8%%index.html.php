<?php /* Smarty version 2.6.31, created on 2018-08-23 12:54:31
         compiled from C:/phpStudy/PHPTutorial/WWW/KUAIXUEAPP/Index/Tpl/Index/index.html */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <h1><?php echo $this->_tpl_vars['var']; ?>
</h1>
  <form action="" method="post">
    <label >
      用户名：<input type='text' name='username'>
    </label>
    <label for="">
      密码：<input type='text' name='password'>
    </label>
    <input type="submit" value="提交">
  </form>
</body>
</html>