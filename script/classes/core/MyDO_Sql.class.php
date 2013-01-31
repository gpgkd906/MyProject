<?php
/**
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
class myDO_Driver implements IteratorAggregate {
  private $db=null;
  private $sth=null;
  private $sql=null;
  private $args=array();
  protected $table_info=array();
  protected $set=array();
  protected $set_args=array();
  protected $find=array();
  protected $from=null;
  protected $where=array();
  protected $by=array();
  protected $limit=array();
  protected $lastQuery=array();
  
  public function __construct(){
    $this->db=new PDO(My::DB_DNS,My::DB_USER,My::DB_PASS);
    //基本的にUTF8でいきましょう
    $charset=defined("My::DB_CHARSET")?My::DB_CHARSET:"utf8";
    $this->db->query("SET NAMES ".$charset);
  }
  
  public function getDriver(){
    return $this->db;
  }

  public function __destruct(){
    $this->db=null;
  }
  
  public function from($table){
    $this->reset(true);
    $this->from='`'.$table.'`';
    return $this;
  }

  public function find($where,$bind=null){
    $this->find[]=array($where,$bind);
    return $this;
  }

  //遅的にバンドする
  protected function _find($where,$bind=null){
    if(strpos($where,",")!==false){
      $where=explode(",",$where);
    }else{
      $where=array($where);
    }
    $bind=(array)$bind;
    $_b=array();
    $arglen=count(func_get_args());
    if($arglen===1){
      //do nothing
    }elseif(count($where)===1 && count($bind)!==1){
      $_v=array();
      foreach($bind as $dummy){
	$_v[]="?";
      }
      $_b=$bind;
      $where[0]="`".$where[0]."` in (".join(",",$_v).")";
    }else{
      foreach($bind as $key=>$val){
	if(!isset($where[$key])){
	  break;
	}
	$_w=$where[$key];
	if(is_array($val)){
	  $_v=array();
	  foreach($val as $dummy){
	    $_v[]="?";
	  }
	  $_w="`".$_w."` in (".join(",",$_v).")";
	  $_b=array($b,$val);
	}else{
	  if(strpos($_w,"=?")===false){
	    $_w="`".$_w."`=?";
	  }
	  $_b[]=$val;
	}
	$where[$key]=$_w;
      }
    }
    $this->where[]="(".join(" OR ",$where).")";
    $this->args=array_merge($this->args,$_b);
    return $this;
  }

  public function set($set,$bind=null){
    $this->set[]="`".$set."`";
    $_bind=(array)$bind;
    if(empty($_bind)){
      $_bind=array($bind);
    }
    $this->set_args=array_merge($this->set_args,$_bind);
    return $this;
  }

  public function orderBy($order){
    $this->by[]="ORDER BY {$order}";
    return $this;
  }

  public function groupby($group){
    $this->by[]="GROUP BY {$group}";
    return $this;
  }
  
  public function limit($l1,$l2=null){
    $this->limit[]=$l1;
    empty($l2) || ($this->limit[]=$l2);
    return $this;
  }

  public function select(){
    $set=func_get_args();
    $halfSql=array();
    $halfSql[]="SELECT";
    $halfSql[]=empty($set)?"*":join(",",$set);
    $halfSql[]="FROM ".$this->from;
    $this->sql=$this->buildSql($halfSql);
    $sth=$this->query($this->sql,$this->args);
    $this->reset();
    return $sth;
  }
  
  public function insert($args=null){
    $sql=array();
    $sql[]="INSERT INTO";
    $sql[]=$this->from;
    $set=array();
    $_set="(".join(",",$this->set).")";
    $sql[]=$_set;
    $sql[]="VALUES";
    $sql[]="(".preg_replace("/[^,]++/","?",$_set).")";
    $this->sql=join(" ",$sql);
    $this->args=array_merge(array_merge($this->set_args,$this->args),(array)$args);
    $this->query($this->sql,$this->args);
    return $this->reset();
  }

  public function update($args=null){
    $halfSql=array();
    $halfSql[]="UPDATE";
    $halfSql[]=$this->from;
    $halfSql[]="SET";
    $set=array();
    $_args=array();
    foreach($this->set as $item){
      if(strpos($item,"=")===false){
	$item=$item."=?";
      }
      $set[]=$item;
    }
    $halfSql[]=join(",",$set);
    $this->sql=$this->buildSql($halfSql);
    $this->args=array_merge(array_merge($this->set_args,$this->args),(array)$args);
    $this->query($this->sql,$this->args);
    return $this->reset();
  }

  public function delete($args=null){
    $halfSql=array();
    $halfSql[]="DELETE FROM";
    $halfSql[]=$this->from;
    $this->sql=$this->buildSql($halfSql);
    $this->args=array_merge($this->args,(array)$args);
    $this->query($this->sql,$this->args);
    return $this->reset();
  }
  
  protected function buildSql($halfSql){
    foreach($this->find as $obj){
      $this->_find($obj[0],$obj[1]);
    }
    if(!empty($this->where)){
      $halfSql[]="WHERE ".join(" AND ",$this->where);
    }
    $halfSql[]=join(" ",$this->by);
    if(!empty($this->limit)){
      $halfSql[]="LIMIT ".join(",",$this->limit);
    }
    return join(" ",$halfSql);
  }

  protected function reset($deep=false){
    $this->sth=null;
    $this->sql=null;
    $this->args=array();
    $this->set=array();
    $this->set_args=array();
    $this->find=array();
    $this->where=array();
    $this->by=array();
    $this->limit=array();
    return $this;
  }
 
  //sql run
  public function query($sql,$data=null){
    $this->lastQuery=array($sql,$data);
    $this->sth=$this->db->prepare($sql);
    if(empty($data)){
      $this->sth->execute();
    }else{
      $this->sth->execute($data);
    }
    return $this->sth;
  }

  //Iterator
  public function getIterator(){
    $sth=$this->select();
    return new ArrayIterator($sth->fetchall());
  }

  public function getLastQuery(){
    return $this->lastQuery;
  }

  public function lastId(){
    return $this->db->lastInsertId();
  }

  //short cut!!
  public function search(Array $condition){
    foreach($condition as $key=>$cond){
      $this->find[]=array($key,$cond);
    }
    return $this->select();
  }

  public function put(Array $data){
    foreach($data as $key=>$item){
      $this->set[]="`".$key."`";
      $this->set_args=array_merge($this->set_args,(array)$item);
    }
    return $this;
  }

  public function setAll(Array $data){
    return $this->put($data)->insert();
  }

  public function getAll(){
    return $this->select()->fetchAll(2);
  }
  
  public function begin(){
    $this->db->beginTransaction();
  }
  
  public function commit(){
    $this->db->commit();
  }

  public function rollback(){
    $this->db->rollback();
  }

}