<?php

class demoMyController extends application {
  
  public function calendar(){
    $cal=$this->super->getInstance("calendar");
    $this->set("cal",$cal);
  }
  
  public function panasonic(){}

  public function sleep(){}
  
  public function socket(){}

  public function serverTime(){
    var_dump(mktime(0,0,0,2,1,2013));
    
  }

  public function upload(){
    
  }
}