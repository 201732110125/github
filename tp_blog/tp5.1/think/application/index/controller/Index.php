<?php
namespace app\index\controller;
use app\common\model\User;
use think\Db;
use think\facade\Env;

class Index
{
    public function index()
    {
      //货物user表中所有的数据
//       $user=User::select();
//       halt("$user");
//      根据id获取数据
//        $user=User::find(1);
        $user=User::where('nick','=',"人人")->select();
//        $user=Db::table('user')->select();
//        $user=db('user')->find(1);
        halt($user);
//        echo Env::get('app_path');
//        halt(Env::get());
//       return view('',compact("user"));

    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
    public function news(){
        echo "i an news";
    }
}
