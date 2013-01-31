<?php

class test {

  public function index(){
    die("test/index");
  }

}

controller::getSingletonInstance()->register("admin","test",function(){
    $test=new test();
    $test->index();
  });

?>