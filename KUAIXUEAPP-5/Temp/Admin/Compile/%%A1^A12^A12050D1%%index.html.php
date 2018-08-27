<?php /* Smarty version 2.6.31, created on 2018-08-27 17:25:13
         compiled from C:/phpStudy/PHPTutorial/WWW/KUAIXUEAPP/Admin/Tpl/Login/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../Common/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
    <div class="container">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info" style="margin-top: 55px">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">登陆</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">用户名</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="username" id="inputEmail3" placeholder="Username" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">密码</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="Password" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> 记住我
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-default">登陆</button>
                            </div>
                        </div>
                    </form>
                  
                </div>
            </div>
        </div>
</body>

</html>