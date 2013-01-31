<?php

class mareMyController extends application {


  public function sql(){
    $db=$this->super->db;
    $this->set("shop",$db->from("MARE_shop")->select()->fetchall());
  }
}