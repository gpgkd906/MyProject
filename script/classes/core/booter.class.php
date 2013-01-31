<?php
/**
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
class booter {
  private $dispatcher=null;
  private $context;

  public function __construct(){
    $this->context=controller::getSingletonInstance();
  }

  public function bootStrap(){
    $this->config();
    $this->module();
    $this->defaultAssign();
    //$this->plugin();
  }
  
  public function config(){
    if(!My::$config){
      die("config error");
    }
    $this->context->config=My::$config;
  }

  /**
   * 模板默认变量赋值
   */
  public function defaultAssign(){
    $this->context->view->assign("title",$this->context->request['app']);
    $this->apiAssign();
  }

  /**
   * api中需要的相关模板变量
   */
  public function apiAssign(){
    $path=array(
		"css"=>My::domain.'style/',
		"image"=>My::domain.'images/',
		"js"=>My::domain.'js/',
		"img"=>My::domain.'img/',
		"config"=>My::$config,
		);
    $this->context->view->assign_array($path);
  }
  
  public function module(){
    //Logger導入したか否か
    if(defined("My::logger")){
      $this->context->getInstance("logger");
      $this->context->logger->setLogFile(My::data.My::logger);
      errorHandler::setHandlerLogger($this->context->logger);
    }
    //module導入
    $this->context->getInstance("module");
    $this->context->module->include_path(My::classes."module/");
    //template:内部的にchemplateを使用，Smartyに切り替えも可能。
    $this->context->view=$this->context->getInstance("view");
    if(My::$config["debug"]){
      $this->context->view->force_compile=true;
    }
    $this->context->view->_template_dir=My::template;
    $this->context->view->_template_chen_dir=My::template_c;
    $this->context->view->_cache_dir=My::cacher;
    $this->context->view->init();
    //Dispatcher
    if(defined("My::Dispatcher") && My::Dispatcher!==null){
      $this->dispatcher=My::Dispatcher;
    }else{
      $this->dispatcher="simpleDispatcher";
    }
    $this->context->import($this->dispatcher);
    //appController
    $this->context->import("appController");
  }
  /*
  public function plugin(){
    $plugin_dir=My::root."script/plugin/";
    $handle=opendir($plugin_dir);
    while($s=readdir($handle)) {
      if(($s!=".")and($s!="..")){
	require $plugin_dir.$s;
      }
    }	        
  }
  */
  public function MyDO(){
    if(defined("My::DB_STATUS") && My::DB_STATUS==="invalid"){
      return false;
    }
    if(!defined("My::MyDO_DRIVER")){
      $this->context->import("MyDO_Sql");
     }else{
      $this->context->import(My::MyDO_DRIVER);
    }
    $this->context->db=$this->context->getInstance("MyDO");
  }
  
  public function getDispatcher(){
    return $this->dispatcher;
  }
  
}