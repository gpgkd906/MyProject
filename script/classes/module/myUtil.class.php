<?php

class myUtil {

  //委托login
  public function login(){
    
  }

  /**
   * logout:
   * 清除session，并跳转至前页（需检查前页是否跨域，若非本域则跳转至本域首页，要求定义框架参数domain）
   */
  public function logout($redirect=null){
    if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(),'',time()-60*60*24*365, '/');
    }
    $_SESSION=array();
    session_unset();
    session_destroy();
    if($redirect){
      $this->redirect($redirect);
    }else{
      if( isset($_SERVER["HTTP_REFERER"]) && preg_match("/".preg_quote(My::domain,'/')."/",$_SERVER["HTTP_REFERER"]) ){
	$from=$_SERVER["HTTP_REFERER"];
      }else{
	$from=My::domain;
      }
      $this->redirect($from,true);
    }
  }

  /**
   *  重定向
   */
  public function redirect($to="",$url=false){
    if($url){
      header('Location:'.$to);
    }else{
      header('Location:'.My::domain.$to);
    }
    die();
  }

  /**
   * tracking
   */
  public function tracking(){
    $access=array();
    if( isset($_SESSION["access"]) ){
      $access=$_SESSION['access'];
      $this->agent=$access["agent"];
    }else{
      $access=array();
      $agent=$_SERVER["HTTP_USER_AGENT"];
      $this->agent["mobile"]=preg_match("/DoCoMo|SoftBank|Vodafone|KDDI|WILLCOM|emobile/",$agent)?true:false;
      $this->agent["smartphone"]=preg_match("/Android|iPad|iPhone|iPod/",$agent)?true:false;
      $access["agent"]=$this->agent;
      $access["ip"]=$_SERVER["REMOTE_ADDR"];
    }
    $access["tracking"][]=$_SERVER["REQUEST_URI"];
    $access["time"][]=$_SERVER["REQUEST_TIME"];
    $_SESSION["access"]=$access;
  }

  public function isMobile(){
    if(!isset($this->agent))$this->access();
    return $this->agent["mobile"] && !$this->agent["smartphone"];
  }
  
  /**
   *  检查请求是否本网域的子页面
   *  App为Appertaining.
   */
  public function isApp($url){
    return preg_match('/'.preg_quote(My::domain,'/').'/',$url);
  }

}