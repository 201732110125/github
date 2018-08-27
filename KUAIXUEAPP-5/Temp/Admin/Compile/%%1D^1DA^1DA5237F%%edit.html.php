<?php /* Smarty version 2.6.31, created on 2018-08-27 20:14:30
         compiled from C:/phpStudy/PHPTutorial/WWW/KUAIXUEAPP/Admin/Tpl/Category/edit.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../Common/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>
    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title text-center" style="font-weight: bold;font-size: 25px;">修改栏目</h2>
            </div>
            <div class="panel-body">
               <div class="container-fluid">
               	 <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <label class="sr-only" for="Category">Category</label>
                        <div class="col-md-12">
                        	<input type="text" name="cname" class="form-control" id="Category" value="<?php echo $this->_tpl_vars['oldData']['cname']; ?>
" required="">
                        </div>
                    </div>
                    <div class="from-group">
                    	<button type="submit" class="btn btn-success">提交</button>
                    	<button type="reset" class="btn btn-default">取消</button>
                    </div>
                </form>
               </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>