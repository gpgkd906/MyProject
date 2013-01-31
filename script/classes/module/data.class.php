<?php
/**
 *   借鉴wordpress的get_option,add_option,update_option,结合MyDO实现的快速数据读取及获取
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
class data {
  protected $file=null;
  protected $data=null;
  protected $encoded=false;
  protected $decoded=false;
  public $debug=false;

  public function __construct($data=null){
    $this->data=$data;
  }  

  public function encode($type="store"){
    switch($type)
      {
      case "store":
	$this->data=serialize($this->data);
	$this->encoded=true;
	break;
      case "base64":
	$this->data=base64_encode($this->data);
	$this->encoded=true;
	break;
      case "json":
	$this->data=json_encode($this->data);
	$this->encoded=true;
	break;
      default:
	/* do nothing*/;
	break;
      }
    return $this;
  }
  
  public function decode($type="store"){
    switch($type)
      {
      case "store":
	$this->data=unserialize($this->data);
	$this->decoded=true;
	break;
      case "base64":
	$this->data=base64_decode($this->data);
	$this->decoded=true;
	break;
      case "json":
	$this->data=json_decode($this->data,true);
	$this->decoded=true;
	break;
      default:
	/* do nothing*/;
	break;
      }
    return $this;
  }

  public function open($file){
    $this->file=$file;
    return $this;
  }
  
  public function load($data=false){
    if($data){
      $this->data=$data;
    }elseif(is_file($this->file)){
      $this->data=file_get_contents($this->file);
    }
    return $this;
  }
  
  public function write($data=false){
    if($data!==false){
      $this->data=$data;
    }
    if($this->encoded===false){
      $this->encode();
    }
    if($this->debug){
      echo $this->data." >> ".$this->file."\n\r";
    }
    file_put_contents($this->file,$this->data);
    return $this;
  }
  
  public function delete(){
    if( $this->file && is_file($this->file) ){
      unlink($this->file);
    }
    return $this;
  }
  
  public function clean(){
    $this->data=null;
    return $this;
  }

  public function show(){
    echo '<pre>';
    print_r($this->data);
    echo '</pre>';
    return $this;
  }

  public function fetch(){
    if($this->decoded===false){
      $this->decode();
    }
    if(!isset($this->data)){
      $this->load();
    }
    return $this->data;
  }
}