<?php

require_once 'PHPUnit/Autoload.php';
require_once dirname(__FILE__)."/../config/config.meta.php";

class myDispatcherTest extends PHPUnit_Framework_TestCase {
  private $dispatcher;
  
  public function __construct(){
    parent::__construct();
    $this->dispatcher=My::Dispatcher;
    require_once My::core.$this->dispatcher.".class.php";
  }

  public function testMap(){
    $_GET["req"]="";
    $return=call_user_func_array(array($this->dispatcher,"map"),array(My::$app2url,My::app,My::template));
    $this->assertArrayHasKey("app",$return);
    $this->assertArrayHasKey("logic",$return);
    $this->assertArrayHasKey("view",$return);
    $this->assertArrayHasKey("appController",$return);
    $this->assertArrayHasKey("appRequest",$return);
    $_GET["req"]="user/love";
    $return=call_user_func_array(array($this->dispatcher,"map"),array(My::$app2url,My::app,My::template));
    $this->assertArrayHasKey("app",$return);
    $this->assertArrayHasKey("logic",$return);
    $this->assertArrayHasKey("view",$return);
    $this->assertArrayHasKey("appController",$return);
    $this->assertArrayHasKey("appRequest",$return);
    $_GET["req"]="user/love?id=33";
    $return=call_user_func_array(array($this->dispatcher,"map"),array(My::$app2url,My::app,My::template));
    $this->assertArrayHasKey("app",$return);
    $this->assertArrayHasKey("logic",$return);
    $this->assertArrayHasKey("view",$return);
    $this->assertArrayHasKey("appController",$return);
    $this->assertArrayHasKey("appRequest",$return);
  }
  
  public function testBuild(){

    $this->markTestIncomplete("build");
  }

  public function testLink(){

    $this->markTestIncomplete("link");
  }
  
}