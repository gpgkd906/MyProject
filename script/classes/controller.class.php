<?php
require_once My::classes."core/core.class.php";

class controller extends core {

  protected function __contruct(){
    parent::__contruct();
    ini_set('session.gc_maxlifetime',7*86400);
    ini_set('session.cache_expire',7*86400);    
  }

  public static function getSingletonInstance(){
    if(isset(self::$instance)){
      return self::$instance;
    }
    $class=__CLASS__;
    return self::$instance = new $class;
  }

}