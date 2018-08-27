190
a:4:{s:8:"template";a:1:{s:68:"C:/phpStudy/PHPTutorial/WWW/KUAIXUEAPP/Index/Tpl/Index/index_tpl.php";b:1;}s:9:"timestamp";i:1535114503;s:7:"expires";i:1535114505;s:13:"cache_serials";a:0:{}}<!DOCTYPE html>
<!-- 个性化错误页面 -->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-http-equiv="Content-Type" content='text/html';charset=UTF-8>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>错误提示</title>
  <style type='text/css'>
    div.hdphp_notice{
      border:1px solid #990000;
      font-family: Monaco,Menlo,Consolas;
      padding-left: 20px;
      margin:10px;
    }
    div.hdphp_notice h3{
      font-size: 18px;
      margin:20px,0;
    }
    div.hdphp_notice p{
      font-size: 14px;
      margin:15px 0;
    }
  </style>
</head>
<body>
  <div class="hdphp_notice">
    <h3 style="font-size:18px">unlink(C:/phpStudy/PHPTutorial/WWW/KUAIXUEAPP/Temp/Index/Compile\%%0F^0FF^0FF88DF4%%index_tpl.php.php): No such file or directory</h3>
    <p>
      Severity: 2    </p>
    <p>
      File: C:\phpStudy\PHPTutorial\WWW\KUAIXUEAPP\KUAIXUEPHP\Extends\Org\Smarty\internals\core.write_file.php    </p>
    <p>
      Line:44    </p>
    <p style="color:#999">
      KUANXUEPHP开源框架 版本：1.0    </p>
  </div>
</body>
</html>
<!--页头说明-->
<div class="row">
    <div class="col-md-8">
        <div class="page-header">
            <h2>
                <?php echo isset($catName) ? strtoupper($catName):'';?>最新博文</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="page-header">
            <h2> <small>阅读排行榜</small></h2>
        </div>
    </div>

</div>


<!--左边列表-->
<div class="row" >
    <div class="col-md-8">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php foreach($artList as $art) :?>

            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                           <?php echo $art['title']; //标题 ?>
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <?php

                         //将内容实体转标签
                        $content = htmlspecialchars_decode($art['content']); //博文内容

                        //截取内容前的120个中文做为简介
                        $content = mb_substr($content,0,120,'utf-8');


                         echo  $content;

                         ?>
                        <a  class="btn btn-info btn-xs" href="/index.php<?php echo $art['title_url']; //详情 ?>" role="button">点击阅读 >></a>
                    </div>
                </div>
            </div>

            <?php endforeach; ?>
        </div>
    </div>

<!--右边推荐列表-->
    <?php include 'public/inc/right_inc.php' ?>
</div>

<!--分页类模板调度-->
<?php

    //获取当前页数
    //如果get存在page参数则将当前页设置为get值,否则设置为1,即默认为第1页
    $pageCurrent = isset($_GET['page'])?$_GET['page']:1;
    //加载首页的分页模板
    include 'public/tpl/page_tpl.php';

?>

