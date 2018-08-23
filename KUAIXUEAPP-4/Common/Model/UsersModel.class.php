<?php
class UsersModel extends Model{
  public $table="users";//覆盖了父类Model的$table属性
  public function get_all_data(){
    return $this->all();
  }
}
 ?>
