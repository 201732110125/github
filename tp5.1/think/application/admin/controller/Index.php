<?php

namespace app\admin\controller;

use think\Controller;

//控制器只是前端页面与后端Model的中转站，只负责判断Model传回来的信息，并把情况传给前台，最好不要写业务逻辑
class Index extends Controller
{
    public function index()
    {
//       $result=mailto('1960196797@qq.com','index测试','index测试');
//       halt($result);
    }

    //后台登陆
    public function login()
    {
        if (request()->isAjax()) {  //如果是Ajax请求，request()表示请求方式
            $data = [  //打包数据
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
    //控制器的返回结果会交给Ajax请求
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
            //从Admin里面返回的数据赋值给$result，要么是1表示注册成功，要么是别的信息，注册失败
            $result=model("Admin")->register($data);
            if($result==1){
                //注册成功，返回登陆界面
                $this->success('注册成功！','admin/index/login');
            }else{
                //把返回结果给它
                $this->error($result);
            }
        }
        //显示注册模板
        return view();
    }

    //忘记密码，发送验证码
    public function forget(){
        if(request()->isAjax()){

            $code=mt_rand(1000,9999);   //生成一个随机数
            session('code',$code);  //把数据存到session里面
            $content='您的重置密码验证码是'.$code;
            $result=mailto(input('post.email'),'重置密码验证码',$content);

            if($result){    //要是邮件发送成功
                $this->success("验证码发送成功！");
            }else{
                $this->error('验证码发送失败！');
            }
        }
        return view();
    }
    //忘记密码，重置密码，当访问这个操作的时候，一定是ajax提交过来的
    public function reset(){
       $data=[
           'code'=>input('post.code'),
           'email'=>input('post.email')
       ];
       $result=model('Admin')->reset($data);
        if($result==1){
            $this->success("密码重置成功！请去邮箱查看新密码",'admin/index/login');
        }else{//当重置不成功时，$result里面就是model层返回出来的错误信息
            $this->error($result);
        }
    }

}
