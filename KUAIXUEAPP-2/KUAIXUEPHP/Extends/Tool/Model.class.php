<?php
// 数据库类
class Model{

  //保存连接信息
  public static $link=NULL;
  //保存表名
  protected $table=NULL;
  //初始化表信息
  private $opt;
  //记录发送的sql
  public static $sqls=array();

  public function __construct($table=NULL){
    $this->table=is_null($table)?C('DB_PREFIX').$this->table:C('DB_PREFIX').$table;

    //连接数据库
    $this->_connect();
    //初始化sql信息
    $this->_opt();
  }

  public function query($sql){
    self::$sqls[]=$sql;//把数据数组填入数组里
    $link=self::$link;
    $result=$link->query($sql);
    if($link->errno){
      halt('mysql错误   '.$link->error."<br/>SQL: ".$sql);
    }
    $rows=array();
    while($row=$result->fetch_assoc()){
      $rows[]=$row;
    }
    $result->free();//释放结果集
    $this->_opt();//归位表信息
    return $rows;
  }

//根据opt数组里的信息，组合sql语句，再交给query()函数，查询全部信息，返回查到的结果集
  public function all(){ //要在外部调用，用public
    $sql="select ".$this->opt['field']." from ".$this->table.$this->opt['where']
    .$this->opt['group'].$this->opt['having'].$this->opt['order'].$this->opt['limit'];
    echo $sql;
    return $this->query($sql);
  }

//根据字段名查找，而不是查找全部*
//满足M('tablename')->field('字段名')->all();链式查找
  public function field($field){
    $this->opt['field']=$field;
    return $this;//把当前对象return 出去，使之可以后面链式查找 ->all()
  }

//按照where 条件查找，其实就是覆盖了$this->opt['where']
public function where($where){
  $this->opt['where'] =' WHERE '.$where;
  return $this;//返回当前对象，用于调用all()方法
}
//按照order条件
public function order($order){
  $this->opt['order']= ' ORDER BY '.$order;
  return $this;//返回当前对象，用于调用all()方法
}
//按照limit条件
public function limit($limit){
  $this->opt['limit']=' LIMIT '.$limit;
  return $this;//返回当前对象，用于调用all()方法
}


  private function _opt(){
    $this->opt=array(
      'field'=>'*',
      'where'=>'',
      'group'=>'',
      'having'=>'',
      'order'=>'',
      'limit'=>'',
    );
  }

  //连接
  private function _connect(){
    //如果$link为null，则表示数据库还未连接过，我就连接一下
    //但是如果不为null，则表示之前已经调用过这个类了，已经有$link连接标识了
    //就不用再次连接
    if(is_null(self::$link)){
      $db=C('DB_DATABASE');//注意，empty()函数变量只能是个变量，不能是函数的返回值
      if(empty($db)){
        //如果用户忘记配置数据库
        halt("请先配置数据库");
      }

      $link=new mysqli(C('DB_HOST'),C('DB_USER'),C('DB_PASSWORD'),C('DB_DATABASE'),C('DB_PORT'));
      if($link->connect_error){
        halt("数据库连接错误，请检查配置项");
      }
      $link->set_charset(C('DB_CHARSET'));//设置字符集
      self::$link=$link;//把结果保存在静态属性里面
    }
  }
















}

 ?>
