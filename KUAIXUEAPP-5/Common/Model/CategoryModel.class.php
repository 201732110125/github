<?php 
/*
对栏目类的操作方法
这个模型是专门对应category表的，里面的操作也是基于category表来进行的
 */
class CategoryModel extends Model{
	public $table='category';

	public function add_data(){
		//返回的是插入数据的id，或是受影响(更新删除)数据的条数
		return $this->add();
		
	}

	//获得表里面的全部数据，用于在展现全部栏目的时候
	public function get_all(){
		return $this->all();
	}
	//根据栏目id，删除表里的某一条数据
	public function remove($cid){
		$this->where('cid='.$cid)->delete();
	}
	//根据栏目cid，取得一条数据
	public function get_one($cid){
		return $this->where('cid='.$cid)->find();
	}

}

 ?>