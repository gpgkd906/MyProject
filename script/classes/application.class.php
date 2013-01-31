<?php

class application extends appController {
  
  public function __construct(){
    parent::__construct();
    $this->bm=$this->super->getInstance("benchmark");
  }

  public function afterAction(){
    parent::afterAction();
    /* $view=$this->super->view; */
    /* $view->force_compile=false; */
    /* $view->_cache=true; */
    $this->bm->set("actionEnd");
  }

  public function beforeRender(){
    parent::beforeRender();
    $this->request=My::escape($this->request);
    $this->set("request",$this->request);
    $this->get=My::escape($this->get);
    $this->set("get",$this->get);
    $this->post=My::escape($this->post);
    $this->set("post",$this->post);
  }
  
  public function afterRender(){
    parent::afterRender();
    $this->bm->set("renderEnd");
    $this->bm->display();
  }

  public function getFormHelper(){
    $this->super->import("Checker");
    $this->formHelper=$this->super->getInstance("myForm");
    $this->set("form",$this->formHelper);
  }

  static public function link($controll=null,$request=null,$param=array()){
    return call_user_func_array(array(self::$dispatcher,"link"),array($controll,$request,$param));
  }

  public function error($title,$content){
    $this->setTpl("error.tpl");
    $this->set("error",array("title"=>$title,"content"=>$content));
  }

  public function getLastQuery(){
    return $this->super->getInstance("MyDO")->getLastQuery();
  }

}