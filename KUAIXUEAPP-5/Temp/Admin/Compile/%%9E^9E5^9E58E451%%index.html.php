<?php /* Smarty version 2.6.31, created on 2018-08-27 19:57:53
         compiled from C:/phpStudy/PHPTutorial/WWW/KUAIXUEAPP/Admin/Tpl/Category/index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../Common/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<body>
    <div class="container-fluid">
        <table class="table table-bordered">
            <thead>
                <tr class="info">
                    <th>栏目名称</th>
                    <th width="200">管理操作</th>
                </tr>
            </thead>
            <?php $_from = ($this->_tpl_vars['data']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
            <tr>
                <td><?php echo $this->_tpl_vars['v']['cname']; ?>
</td>
                <td>
                    <!-- 就是通过把栏目的cid给存放到get数组里面，然后跳转到对应的操作，根据传入的值来进行对应的修改操作 -->
                    <a href="<?php echo @__APP__; ?>
?c=Category&a=edit&cid=<?php echo $this->_tpl_vars['v']['cid']; ?>
" class="btn btn-success btn-sm">修改</a>
                    <a href="javascript:;" class="btn btn-danger btn-sm" onclick="cate_del(<?php echo $this->_tpl_vars['v']['cid']; ?>
)">删除</a>
                </td>

            </tr>
            <?php endforeach; endif; unset($_from); ?>
        </table>
    </div>
    <script type="text/javascript">
        function cate_del(cid){
            if(confirm("确认删除吗？")){
                 location.href="<?php echo @__APP__; ?>
?c=Category&a=del&cid="+cid;
            }
        }
    </script>
</body>

</html>