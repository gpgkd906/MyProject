<?php
/**
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
class trigger {
  private $queue=array();
  private $error=array();
  private $pageQueue=array();

  public function register($event,$callback,$param=array()){
    if( isset($event) && isset($callback) ){
      $this->queue[$event][]=array(
				   $callback,
				   $param
				   );
    }
    return $this;
  }
  
  public function registerPage($page,$callback){
    $this->pageQueue[join("/",$page)][]=$callback;
  }

  public function isRegisterPage($page){
    $_page=join("/",$page);
    if(isset($this->pageQueue[$_page])){
      return true;
    }
  }

  public function callPageTrigger($page){
    $_page=join("/",$page);
    foreach($this->pageQueue[$_page] as $call){
      call_user_func($call);
    }
  }

  public function remove($event,$callback=null){
    if(isset($callback)){
      foreach($this->queue[$event] as $key=>$val){
	if($callback==$val[0]){
	  unset($this->queue[$event][$key]);
	}
      }
    }else{
      if(isset($this->queue[$event])){
	unset($this->queue[$event]);
      }
    }
    return $this;
  }
  
  public function removeAll(){
    $this->queue=array();
    return $this;
  }

  public function DispatchEvent($event){
    if( isset($this->queue[$event]) ){
      foreach($this->queue[$event] as $call){
	if(is_callable($call[0])){
	  try{
	    call_user_func_array($call[0],$call[1]);
	  }catch(Exception $e){
	    $this->error[]="Error in $callback...\n".
	      "error detail:".$e->getMessage()."\n";
	  }
	}//if(is_callable
      }//foreach
    }//if(isset
    return $this;
  }

  //返回错误记录
  public function getError(){
    if(!empty($this->error)){
      return $this->error;
    }
  }
  
  //直接打印错误记录
  public function showError(){
    if(empty($this->error)){
      echo "<pre>no error in event...</pre>";
    }else{
      echo "<pre>";
      print_r($this->error);
      echo "</pre>";
    }
  }
}
