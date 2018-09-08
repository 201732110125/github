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
        if(!$validate->scene('Register')->check($data)){//要是未通过验证
            //返回错误信息
            return $validate->getError();
        }
        //allowField(true)表示虽然$data里面有很多数据，但是我只写入数据库里面有的
        //字段，还有种方法就是unset($data['conpass'])，把conpass给删掉，剩下的全都插入数据库
        $result=$this->allowField(true)->save($data);
        if($result){
            //注册成功发个邮件给注册人
            mailto($data['email'],'注册管理员账户成功！','亲爱的'.$data['nickname'].'，您注册管理员账户成功');
            return 1;//插入成功
        }else{
            return "注册失败";
        }
    }

    //验证用户输入的验证码和给定的验证码是否相同
    //重置密码
    public function reset($data){
        $validate=new \app\common\validate\Admin(); //实例化验证器
        //先对验证码输入进行判断，是否为空
        if(!$validate->scene('Reset')->check($data)){
            return $validate->getError();
        }else{
//            把当前的符合该邮箱的用户信息查出来
//            现在就是用户已经输入了邮箱，得到了验证码

            //判断验证码是否正确
            if($data['code']!=session('code')){
                return '验证码不正确！';
            }
            $adminInfo=model('admin')->where('email',$data['email'])->find();
            $password=mt_rand(10000,99999);//随机生成一个密码
            $adminInfo->password=$password;
            $result=$adminInfo->save();
            if($result){
                $content="恭喜您，密码重置成功！<br>用户名：".$adminInfo['username'].
                "<br>新密码为：".$password;
                mailto($data['email'],'密码重置成功',$content);
                return 1;
            }else{
                return "重置密码失败！";
            }
        }
    }
}
