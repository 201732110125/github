<?php

namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $arr = ['a' => 'apple', 'b' => 'banana'];
        halt($arr);
    }

    //后台登陆
    public function login()
    {
        if (request()->isAjax()) {//如果是Ajax请求，request()表示请求方式
            $data = [
                //接收数据用input()方法获取post传来的username和password
                //从post中抓取数据下来
                'username' => input('post.username'),
                'password' => input('post.password'),
            ];
            //助手函数
            //这个助手model()显示再admin模块下找common/model/Admin模型类，找不到就去application/model/common下找Admin模型类
            //$result 保存了Admin模型类的login方法返回的数据
            //从控制器中把抓取到的数据传给模型类进行判断处理
            $result = model('Admin')->login($data);
            if ($result == 1) {//返回1，表示登陆成功了

                //注意，当是ajax方法的时候，会自动的把error和success
                //当前请求为ajax请求（view页面上的jquery中的ajax请求）
                //返回的结果转化为json格式信息，给前台jquery的$.ajax


                $this->success("登陆成功");
            } else {//返回的不是1 ，表示登陆不成功,或者此账号被禁用了
                //把login方法返回的错误信息error一下
                $this->error($result);
            }
        }
        return view();
    }

    //后台注册
    public function register()
    {
        if(request()->isAjax()){//如果有Ajax提交数据
            //接收一下数据
            $data=[
                'username'=>input('post.username'),
                'password'=>input('post.password'),
                'conpass'=>input('post.conpass'),
                'nickname'=>input('post.nickname'),
                'email'=>input('post.email')
            ];
            //把数据传到Admin模型里面的register方法
            $result=model("Admin")->register($data);
        }
        //显示注册模板
        return view();
    }


}
