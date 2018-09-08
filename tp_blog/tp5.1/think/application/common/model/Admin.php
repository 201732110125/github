<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Admin extends Model
{
    //引用软删除
    //我感觉要引用Model里的方法就要引用命名空间
    //再使用它就可以了
    use SoftDelete;

    //登陆校验
    //传过来的是json格式数据很通用
    public function login($data){
        //实例化Admin验证类
        $validate=new \app\common\validate\Admin();
        //如果没有通过验证
        //这里的格式是验证器对象->scene('{场景名称}')表示验证场景->check({要验证的数据})
        //返回是 是否通过验证的结果true  or  false
        //还是要在最后的->check($data)，方法里面传入要验证的数据
        if(!$validate->scene('login')->check($data)){
            //没通过验证就返回错误信息，也是在验证类的对象的方法里返回的
            return $validate->getError();
        }else{//验证通过，说明输入合法，那么就去数据库里查询
            //因为这个类继承了框架自带的Model类，所以可以直接用父类的方法
            $result=$this->where($data)->find();
            if($result){//要是查到结果
                //判断用户是否可用
                if($result['status']!=1){
                    return '此账户被禁用！';
                }
                else{
                    //返回1 表示用户名和密码正确了
                    //登陆成功时，把用户信息保存在session里面
                    $sessionData=[
                        'id'=>$result['id'],
                        'nickname'=>$result['nickname'],
                        'is_super'=>$result['is_super']
                    ];
                    //session助手函数。
                    session('admin',$sessionData);
                    return 1;
                }

            }else{
                return '用户名或密码错误！';
            }
        }
    }

    //注册账户
    public function register($data){
        //实例化验证器
        $validate=new \app\common\validate\Admin();


    }

}
