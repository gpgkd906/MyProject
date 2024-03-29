<?php
/**
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
final class errorHandler {
  private static $handler=array();
  private static $logger=null;
  private static $style="border-color:#000000;border-width:1px;border-style:solid;color:red;padding:10px;margin:10px";
  private static $typeLevel=array(
				  E_ERROR=>"重大な実行時エラーが発生しました,プロセスが中断されました。\n\r",
				  E_WARNING=>"実行時警告(waring)が発生しました。\n\r",
				  E_NOTICE=>"実行時警告(notice)が発生しました。\n\r",
				  E_STRICT=>"スクリプト中，将来のPHPバージョンに互換性を持たないしかねないのコードを検出されました。\n\r",
				  E_USER_ERROR=>"ユーザが定義した実行時エラーが発生しました,プロセスが中断されました。\n\r",
				  E_USER_WARNING=>"ユーザが定義した実行時警告(waring)が発生しました。\n\r",
				  E_USER_NOTICE=>"ユーザが定義した実行時警告(notice)が発生しました。\n\r",
				  );
  private static $handlerLevel=E_ALL;
  private static $debugMode=true;
  private static $handlerFilter="self::defaultFilter";
  private static $exceptionHandler="errorHandler::defaultExceptionHandler";
  private static $errorHandler="errorHandler::defaultErrorHandler";
  private static $fatalErrorHandler="errorHandler::defaultFatalErrorHandler";

  public static function setHandlerLevel($level){
    error_reporting($level);
    self::$handlerLevel=$level;
  }
  
  public static function setHandlerLogger($logger){
    self::$logger=$logger;
  }

  public static function setHandlerFilter($filter){
    self::$handlerFilter=$filter;
  }

  public static function getHandlerLevel(){
    return self::$handlerLevel;
  }

  public static function setErrorHandler($handler){
    self::$errorHandler=$handler;
  }
  
  public static function setExceptionHandler($handler){
    self::$exceptionHandler=$handler;
  }

  public static function setDefaultHandler(){
    if(isset(My::$config["debug"])){
      self::$debugMode=My::$config["debug"];
    }
    set_exception_handler("errorHandler::proxyExceptionHandler");
    set_error_handler("errorHandler::proxyErrorHandler");
    register_shutdown_function("errorHandler::proxyFatalErrorHandler");
  }
  
  public static function Logger($logger){
    self::$logger=$logger;
  }
  
  public static function proxyExceptionHandler($e){
    if(!self::$debugMode || !call_user_func(self::$handlerFilter)){
      return false;
    }
    call_user_func_array(self::$exceptionHandler,array($e));
  }

  public static function defaultExceptionHandler($e){
    $log=array();
    $log[]="例外が発生しました。\n\r";
    $log[]="File : ".$e->getFile()."\n\r";
    $log[]="Line : ".$e->getLine()."\n\r";
    $log[]="Message : ".$e->getMessage()."\n\r";
    $log[]="Trace:\n\r".$e->getTraceAsString()."\n\r";
    $content=join("",$log);
    if(isset(self::$logger)){
      self::$logger->except($content);
      self::$logger->write();
    }
    echo nl2br("<div style='".self::$style."'>".$content."</div>");
    return true;
  }
  
  public static function proxyErrorHandler($errno,$errstr,$errfile,$errline,$errcontext){
    if(!self::$debugMode || !call_user_func(self::$handlerFilter)){
      return false;
    }
    if(!(self::$handlerLevel & $errno)){
      return false;
    }
    call_user_func_array(self::$errorHandler,array($errno,$errstr,$errfile,$errline,$errcontext));
  }

  public static function defaultErrorHandler($errno,$errstr,$errfile,$errline,$errcontext){
    $log=array();
    if(empty(self::$typeLevel[$errno])){
      $log[]="不明なエラーが発生しました\n\r";
    }else{
      $log[]=self::$typeLevel[$errno];
    }
    $log[]="File : ".$errfile."\n\r";
    $log[]="Line : ".$errline."\n\r";
    $log[]="Message : ".$errstr."\n\r";
    $content=join("",$log);
    if(isset(self::$logger)){
      self::$logger->error($content);
      self::$logger->write();
    }
    echo nl2br("<div style='".self::$style."'>".$content."</div>");
    return true;
  }
  
  public static function proxyFatalErrorHandler(){
    if( !self::$debugMode || !call_user_func(self::$handlerFilter) ){
      return false;
    }
    call_user_func(self::$fatalErrorHandler);
  }

  public static function defaultFatalErrorHandler(){
    $error=error_get_last();
    if($error===null){
      return false;
    }
    if(( E_WARNING | E_NOTICE | E_USER_WARNING | E_USER_NOTICE | E_STRICT ) & $error["type"]){
      return false;
    }
    $log=array();
    $log[]="回復不能なエラーが発生しました。\n\r";
    $log[]="File : ".$error["file"]."\n\r";
    $log[]="Line : ".$error["line"]."\n\r";
    $log[]="Message : ".$error["message"]."\n\r";
    $content=join("",$log);
    if(isset(self::$logger)){
      self::$logger->fatal($content);
      self::$logger->write();
    }
    return true;
  }
  
  private static function defaultFilter(){
    return true;
  }
  
}

/*
class MyException extends Exception {
  
  
}
*/
