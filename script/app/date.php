<?php

class date extends appController {

  public function index(){
    $file=My::data."dateManage";
    $rest=$this->getRest($file);
    $this->set("rest",$rest);
  }

  public function manage(){
    $file=My::data."dateManage";
    $rest=$this->getRest($file);
    if(isset($this->request["rest"])){
      $rest=array_unique(array_merge($rest,$this->request["rest"]));
      file_put_contents($file,serialize($rest));
    }
    $this->set("rest",$rest);
  }

  private function getRest($file){
    if(is_file($file)){
      $string=file_get_contents($file);
      $rest=unserialize($string);
    }else{
      $rest=array();
    }
    return $rest;
  }

}