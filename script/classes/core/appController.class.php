<?php
/**
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */

abstract class appController {
  protected $super;
  protected $request;
  protected $get;
  protected $post;
  protected $param;
  protected $name;
  static protected $dispatcher;

  public function __construct(){
    $this->super=controller::getSingletonInstance();
    $this->request=$_REQUEST;
    $this->get=$_GET;
    $this->post=$_POST;
    $this->param=$this->super->request["param"];
    //環境に定義しないこともありうるので，ここでは必ずbooterから情報をもってくるように
    self::$dispatcher=$this->super->getInstance("booter")->getDispatcher();
    $this->set("app",$this);
  }
  
  public function set($name,$val){
    $this->super->view->assign($name,$val);
  }

  public function get($name){
    return $this->super->view->assigned($name);
  }

  protected function setTpl($tpl){
    $this->super->view->setTemplate($tpl);
  }
  
  protected function getTpl(){
    return $this->super->view->getTemplate();
  }

  public function beforeAction(){}

  public function afterAction(){}

  public function beforeRender(){}

  public function afterRender(){}

}