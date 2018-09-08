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
    //注册登陆功能路由，直接访问www.tp.com/admin 就相当于访问www.tp.com/admin/index/login方法
   Route::rule("/","admin/index/login","get|post");

   //注册 注册功能路由  访问www.tp.com/admin/register 相当于访问www.tp.com/admin/index/register方法
    Route::rule("register",'admin/index/register','get|post');

    //注册 找回密码的路由
    Route::rule("forget","admin/index/forget","get|post");

    //重置密码的路由，只允许post方式访问
    Route::rule('reset','admin/index/reset','post');

});
return [

];
