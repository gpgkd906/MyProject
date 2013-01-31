<?php
/**
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
class module {
  private $include_path=array();
  private $context;
  public $module_root="";
  public $imported=array();
  

  public function __construct(){
    $this->context=controller::getSingletonInstance();
    $this->module_root=My::classes."module/";
  }

  public function include_path($path=null){
    if(isset($path[0])){
      if(!in_array($path,$this->include_path)){
	$this->include_path[]=$path;
      }
    }else{
      return $this->include_path;
    }
  }

  public function import($dir,$_file=null){
    $files=array();
    $cn=array();
    $files[]=$this->module_root.join("/",array($dir,$_file));
    $cn[]=$_file;
    $files[]=$this->module_root.$dir;
    $cn[]=$dir;
    $imported=false;
    foreach($files as $key=>$file){
      if(is_file($file)){
	require_once $file;
	$this->imported[]=$cn[$key];
	$imported=true;
	break;
      }
    }
    if(!$imported){
      throw new Exception("module import failed : ".($_file==null?$dir:$dir."/".$_file));
    }
    return $imported;
  }

  public function getInstance($class){
    if(class_exists($class)){
      return $this->context->getInstance($class);
    }else{
      throw new Exception("moduler is try to get a Instance of undefined class");
    }
  }

  public function getCache(){
    if(defined("My::cache")){
      $cache=controller::getSingletonInstance()->getInstance(My::cache);
    }else{
      $cache=controller::getSingletonInstance()->getInstance("cache");
    }
    if(defined(My::cache_file)){
      $cache->connect(My::cache_file);
    }else{
      $cache->connect("localhost");
    }
    return $cache;
  }

}