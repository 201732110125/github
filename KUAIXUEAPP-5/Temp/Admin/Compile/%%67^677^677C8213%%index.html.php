<?php /* Smarty version 2.6.31, created on 2018-08-27 19:08:36
         compiled from C:/phpStudy/PHPTutorial/WWW/KUAIXUEAPP/Admin/Tpl/Index/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../Common/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<body>
    <div class="container-fluid">
        <div class="row" style="margin-bottom: 0px;">
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-fire"></span></a>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="#"><span style="color: #F1F1F1;font-weight: bold;font-size: 26px;">快学网</span><span class="sr-only">(current)</span></a></li>
                            <li><a href="#"><span style="color: #E0E7F0;font-weight: bold;font-size: 22px;">栏目管理</span></a></li>
                            <li><a href="#"><span style="color: #E0E7F0;font-weight: bold;font-size: 22px;">文章管理</span></a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><span style="font-size: 25px;font-weight: bold;line-height: 54px;margin-right: 30px;"><?php echo $_SESSION['uname']; ?>
</span></li>
                            <li><a href="<?php echo @__APP__; ?>
?c=Login&a=out" style="font-size: 20px;font-weight: bold;"><span class="glyphicon glyphicon-off" style="top: 3px;color: #F05C5C"></span>退出登陆</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <div class="row" style="height: 760px;">
            <div class="col-md-2" style="height: 760px;background-color: #f3f3f5">
                <div>
                    <h3 style="margin:35px "><span class="glyphicon glyphicon-th-large" >功能模块</span></h3>
                </div>
                <ul class="list-unstyled list-group">
                    <li class="list-group-item"><a href="<?php echo @__APP__; ?>
?c=Category&a=add" target="iFrame1">添加栏目</a></li>
                    <li class="list-group-item" target="iFrame1"><a href="<?php echo @__APP__; ?>
?c=Category" target="iFrame1">栏目管理</li>
                </ul>
            </div>
           <!--  <div class="col-md-10" style="height: 760px;" >
              <iframe  name= "iFrame1"  width="100%" height="100%" src="{$smarty.const.__APP__}?a=welcome"  frameborder="0" scrolling="auto"></iframe>
            </div> -->
        <div class="embed-responsive embed-responsive-16by9" >
                <iframe name="iFrame1" class="embed-responsive-item" src="<?php echo @__APP__; ?>
?a=welcome"></iframe>
        </div>

 
        </div>
    </div>

</body>
</html>