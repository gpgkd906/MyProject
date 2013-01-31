<?php 

class timeMyController extends application {

  public function server(){
    $newyear=mktime(0,0,0,1,1,2013);
    $this->set("newYear",$newyear);
  }

}