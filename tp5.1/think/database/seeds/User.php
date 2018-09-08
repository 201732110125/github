<?php

use think\migration\Seeder;

class User extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        //使用db()方法，定义往那个表里填充数据，
        //使用insert()方法，设置插入的数据
        db("user")->insert(['username'=>"后盾人",'password'=>md5("admin888"),'nick'=>'人人']);
        db("user")->insert(['username'=>"后盾网",'password'=>md5("admin888")]);
    }
}