<?php
/**
 * Created by PhpStorm.
 * User: 郑江
 * Date: 2018/9/7
 * Time: 17:10
 */

namespace app\common\validate;

//对管理员的验证器类
use think\Validate;
//先继承tp框架自带的验证类Validate
class Admin extends Validate
{
    //定义通用验证规则，就是说，我这里面要对这些字段要做验证。但是不一定在哪个场景下验证
    protected $rule=[
        //定义验证规则
        //require说明两个字段都不得为空
        //字段管道符后面的信息就是用来显示提示信息的
        'username|管理员账户'=>'require',//提示管理员账户不得为空
        'password|密码'=>'require',
        //确认密码规则：不得为空且要和password一样
        'conpass|确认密码'=>'require|confirm:password',
        'nickname|昵称'=>'require',
        //邮箱的规则：必填且要是邮箱的格式
        //设置用户邮箱也是唯一的，因为找回密码的时候，是根据邮箱来找的
        'email|邮箱'=>'require|email|unique:admin',
        'code|验证码'=>'require',
    ];

    //登陆验证场景，以scene开头
    //$this>only(array())，表示只验证数组里的字段，不管上面的$rule里添加多少验证规则
    //我就只验证这些字段
    public function sceneLogin(){
        //登陆场景下只验证username和password
        return $this->only(['username','password']);
    }

    //注册验证场景，以scene开头
    public function sceneRegister(){
        //在这个注册场景下，要验证五个字段
        return $this->only(['username','password','conpass','nickname','email'])
            ->append('username','unique:admin');
        //设置username这个字段相对于admin表是唯一的

    }

    //重置密码验证场景
    public function sceneReset(){
        return $this->only(['code']);
    }
}