<?php

class myCounter {
  private $db;
  private $driver;
  private $super;
  private $table="myCounter";

  public function __construct(){
    $this->super=controller::getSingletonInstance();
    $this->db=$this->super->getInstance("MyDO");
    $this->driver=$this->db->getDriver();

  }
  
  public function plus($name,$type,$num=1){
    
  }

  public function minus($name,$type,$num=1){
    
  }

}