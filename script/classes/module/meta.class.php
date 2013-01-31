<?php

/**
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */

class meta {
  private $dbdriver;
  private $mydo;
  private $cache;

  public function __construct(){
    $context=controller::getSingletonInstance();
    $this->mydo=$context->MyDO;
    $this->dbdriver=$this->mydo->getDriver();
    $this->cache=$context->getInstance("module")->getCache();
    //init
    $create="CREATE TABLE IF NOT EXISTS `myMeta` ("
      ."`id` int(11) NOT NULL AUTO_INCREMENT,"
      ."`metaid` int(11) NOT NULL COMMENT '关系项的值',"
      ."`relatives` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '关系项名',"
      ."`name` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '关系项值的认知名',"
      ."`status` text COLLATE utf8_unicode_ci NOT NULL COMMENT '其他',"
      ."`storage` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '和外表的关联',"
      ."`del_flg` tinyint(1) NOT NULL,"
      ."`reg_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,"
      ."PRIMARY KEY (`id`)"
      .") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
    $this->dbdriver->query($create);
  }
  
  public function get($data=null,$limit=null){

    $cacheKey="meta_get_".serialize($data);
    $res=$this->cache->get($cacheKey);
    if(!$res){
      $this->mydo->from("myMeta");
      if($data!==null && $this->validate($data)){
	$this->_find($data);
      }
      if($limit!=null){
	call_user_func_array(array($this->mydo,"limit"),$limit);
      }
      $res=$this->mydo->select()->fetchall(2);
      $this->cache->set($cacheKey,$res,86400);
    }
    return $res;
  }

  public function add($data){
    if(!$this->validate($data)){
      return false;
    }
    $this->mydo->from("myMeta");
    $this->_set($data);
    return $this->mydo->insert();    
  }
  
  public function update($data,$find){
    if(!$this->validate($data)){
      return false;
    }
    if(!$this->validate($find)){
      return false;
    }
    $this->mydo->from("myMeta");
    $this->_set($data);
    $this->_find($find);
    return $this->mydo->update();    
  }

  public function remove($data){
    if(!$this->validate($data)){
      return false;
    }
    $this->mydo->from("myMeta");
    $this->_find($data);
    return $this->mydo->delete();
  }

  private function _find($data){
    foreach($data as $key=>$val){
      $this->mydo->find($key,$val);
    }
  }

  private function _set($data){
    foreach($data as $key=>$val){
      $this->mydo->set($key,$val);
    }
  }
    
  private function validate($data){
    $res=false;
    foreach($data as $key=>$val){
      if(isset($val[0]) || !empty($val)){
	$res=true;
	break;
      }
    }
    return $res;
  }


}