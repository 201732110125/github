<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//第一个参数是定义的路由规则
//第二个参数是该路由规则调用的方法或找的控制器中的方法
Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/index/hello');

//路由分组
Route::group("admin",function(){
    //注册登陆功能路由
   Route::rule("/","admin/index/login","get|post");

   //注册 注册功能路由
    Route::rule("register",'admin/index/register','get|post');
});
return [

];
