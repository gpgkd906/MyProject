<?php
/**
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
class myCsv {
  private $fileHandler;
  private $readHandler="fgetcsv";
  private $writeHandler="fputcsv";

  public function __construct(){
    $args=func_get_args();
    $this->fileHandler=$args;
  }

  public function get(){
    return call_user_func_array($this->readHandler,$this->fileHandler);
  }

  public function setReadHandler($handler){
    if(is_callable($handler)){
      $this->readHandler=$handler;
    }
  }
  
  public function getAll(){
    $data=array();
    while( $row=$this->get() ){
      $data[]=$row;
    }
    return $data;
  }

  public function setFileHandler($handler){
    if(gettype($handler)!=="resource"){
      throw new Exception("ファイルハンドラーではありません");
    }
    $this->fileHandler=$handler;
  }

  public function write($data){
    return call_user_func_array($this->writeHandler,array($this->fileHandler,$data));
  }

  public function setWriteHandler($handler){
    if(is_callable($handler)){
      $this->writeHandler=$handler;
    }    
  }
  
}