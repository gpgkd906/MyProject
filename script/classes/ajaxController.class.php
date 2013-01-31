<?php

class ajaxController extends appController {
  protected $response;

  public function __construct(){
    $this->super=controller::getSingletonInstance();
    $this->get=$_GET;
    $this->post=$_POST;
  }

  protected function response($data,$helper){
    if(is_array($data)){
      $data=array("response"=>$data);
    }
    $this->response=array_merge($data,$helper);
  }

  public function success($data){
    $helper=array("myStatus"=>"success","myResCode"=>0);
    $this->response($data,$helper);
  }
  
  public function failed($data){
    $helper=array("myStatus"=>"failed","myResCode"=>1);
    $this->response($data,$helper);
  }
  
  public function error($data){
    $helper=array("myStatus"=>"error","myResCode"=>2);
    $this->response($data,$helper);    
  }

  public function render(){
    echo json_encode($this->response);
  }
} 