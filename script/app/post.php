<?php

class postMyController extends application{
  
  public function test(){
    $post=$this->super->getInstance("postZip");
    
    $res=$post->findByCity("荒本");
    
    var_dump($res);
    
  }
}