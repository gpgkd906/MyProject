<?php
/**
 *   核心部件在初始化时需要获得上下文实体，通过本库实装并进行继承。 
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
abstract class prototype {
  
  public function __construct($controller){
    $this->context=$controller;
  }
  
  public function __destruct(){
    $this->clear();
  }
  
  //自清空
  public function clear(){
    foreach($this as $key=>$value){
      $this->$key=null;
    }
    return $this;
  }  
}

?>