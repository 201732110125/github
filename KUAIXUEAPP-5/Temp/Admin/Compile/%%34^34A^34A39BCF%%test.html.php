<?php /* Smarty version 2.6.31, created on 2018-08-27 19:01:02
         compiled from C:/phpStudy/PHPTutorial/WWW/KUAIXUEAPP/Admin/Tpl/Index/test.html */ ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    	<meta charset="UTF-8">
    	<title>testLoad</title>
    	<script type="text/javascript" src="http://libs.baidu.com/jquery/1.7.2/jquery.min.js"></script>
    	<script type="text/javascript">
    	<?php echo '

    	$(function(){
    		$("#sub").click(function(){
    			$("p").load("test.txt",aClick());
    		});
    	})
    	function aClick () {
    		 window.location = "http://www.baidu.com";
    	}
    	'; ?>

    	</script>
    </head>
    <body>
    	<h2>点击按钮load</h2>
    	<p>this is paragraph to load</p>
    	<input type="button" name="sub" value="load" id="sub"></input><br>
    	<a href="javascript:void(0)" onclick="aClick()">百度</a>
    </body>
    </html>