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


//最底层的方法，接入一个sql语句，返回结果二维数组。all()就是根据$this->opt数组信息组合了一下sql语句，传给query()
//返回的还是query()方法查询到的结果数组，其他一些查询方法也只是修改了一下$this->opt数组，最终使得all()里面的sql语句发生变化而已
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
  public function findall(){//all()方法的别名
    return $this->all();
  }
//根据字段名查找，而不是查找全部*
//满足M('tablename')->field('字段名')->all();链式查找
  public function field($field){
    $this->opt['field']=$field;
    return $this;//把当前对象return 出去，使之可以后面链式查找 ->all()
  }

//按照where 条件查找，其实就是覆盖了$this->opt['where']
public function where($where){
  $this->opt['where'] =' WHERE '.$where;//注意要留下空格
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

//只查找一条数据,就相当与只取一条fetch_assoc()出来，返回的是一个一维关联数组
//而M('table_name')->limit(1)->all()取出来的虽然只有条记录，但是是个二维数组，用起来不太方便
//因为all()方法实际上是调用了query()方法，而query()方法，其实就是将sql语句查询到的结果
//一条条的压倒$rows[]数组里，使之变成二维数组，以返回。
public function find(){
  $data=$this->limit(1)->all();
  $data=current($data);//
  return $data;
}
//find()方法的别名
public function one(){
  return $this->find();
}

//这个方法就是用来归位$this->opt数组的
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

//没有结果集的操作方法，增删改
public function exe($sql){
  self::$sqls[]=$sql;//类静态变量记录我所用到的sql语句
  $link=self::$link;//数据库连接标识符
  $bool=$link->query($sql);//存放返回的信息
  $this->_opt();//把$this->opt[]数组归位，前面可能会有更改，现在把他初始化一下
  if(is_object($bool)){//如果$link->query()返回的结果是个对象，就说明用户用错方法了，用成查了
    halt("请用query方法发送查询sql");//提醒用户，方法用错了
  }

//当返回结果是bool值的时候，说明操作正确了
  if($bool){//当执行成功时
    //当$link->insert_id存在时，说明执行的时插入操作，那就返回插入数据的id
    //当其不存在的时候，说明执行的时更行或是删除操作，就返回受影响的条数
    return $link->insert_id?$link->insert_id:$link->affected_rows;
  }else{//执行错误时
    halt('mysql错误：'.$link->error.'<br/>SQL：'.$sql);
  }
}

//基于exe()底层方法的方法，实现删除操作
public function delete(){
  //在执行数据库的删除操作的时候，一定要写上where条件，表明我要删除那些数据
  //要是不写的话就是全部删除了，这很危险。所以要强制的要求delete()方法执行时
  //一定要加上where条件，这个条件是在$this->opt['where']里面
  if(empty($this->opt['where'])){//当为空时，说明就是没有指定where条件
    halt("删除语句必须要有where条件");
  }
  //组合sql语句
  $sql=" DELETE FROM ".$this->table.$this->opt['where'];
  return $this->exe($sql);//把sql语句交给底层方法exe来执行
}

//安全处理，防止sql注入
private function _safe_str($str){
  //我们要用mysqli的转义，但是php系统会可能自动转义
  //所以要先判断系统有没有自动转义，若有，就反转义回来
  //若未开启自动转义，那就更好，我直接用mysli来转义
  if(get_magic_quotes_gpc()){//这个函数返回的就是系统是否开启自动转义
    $str=stripslashes($str);//反转义回来
  }
  return self::$link->real_escape_string($str);//用mysqli自带的转义函数实现转义
}

//实现  M('users')->add();自动添加数据
public function add(){
  $data=$_POST;//取出$_POST内的数据
  $fields='';
  $values='';
  foreach($data as $f =>$v){
    $fields.='`'.$this->_safe_str($f).'` ,';
    $values.="'".$this->_safe_str($v)."' ,";
  }
  $fields=trim($fields,',');//把最后一个逗号给去掉
  $values=trim($values,',');

  $sql="INSERT INTO ".$this->table.' ( '.$fields.' ) VALUES ('.$values.')';
  return $this->exe($sql);

}










}

 ?>
