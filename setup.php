#!/usr/bin/php
<?php
   /**
    *
    *   Licensed under the MIT license:
    *   http://www.opensource.org/licenses/mit-license.php
    *
    *   author: chenhan,gpgkd906@gmail.com
    *   website: http://dev.gpgkd906.com/MyProject/
    */

function overwrite($file,$content,$force=true){
  if(is_file($file)){
    if(!$force){
      return false;
    }
    unlink($file);
  }
  file_put_contents($file,$content);
}

function cleandir($dir){
    if(!preg_match("/\/$/",$dir))$dir=$dir."/";
    $handle=opendir($dir);
    while($s=readdir($handle)) {
      if(($s!=".")and($s!="..")){
	if(is_dir($dir.$s)){
	  cleandir($dir.$s);
	  rmdir($dir.$s);
	}else{
	  unlink($dir.$s);
	}
      }
    }	        
}


$ds=DIRECTORY_SEPARATOR;
$root=dirname(__FILE__);
$root.=$ds;
$domain="";
$baseurl="";
$action_tag="req";
if(is_file("setup.config.php")){
  require "setup.config.php";
}
//index
$public_index= <<<INDEX
<?php
  /**
   *
   *   Licensed under the MIT license:
   *   http://www.opensource.org/licenses/mit-license.php
   *
   *   author: chenhan,gpgkd906@gmail.com
   *   website: http://dev.gpgkd906.com/MyProject/
   */
//error_reporting(0);
/*================ 　  获取设定文件     ===================*/
  require_once '{$root}config/config.meta.php';
/*================    处理转交控制器    ====================*/
require_once '{$root}script/controller.php';
INDEX;

$public_index_file=$root."public_html/index.php";
overwrite($public_index_file,$public_index);

//config
$origin_config=<<<CONFIG
<?php
  /**
   *
   *
   *   Licensed under the MIT license:
   *   http://www.opensource.org/licenses/mit-license.php
   *
   *   author: chenhan,gpgkd906@gmail.com
   *   website: http://dev.gpgkd906.com/MyProject/
   */
  
  /**
   * 框架基本路径
   */
  abstract class My_Origin {
  const root='{$root}';
  const core='{$root}script/classes/core/';
  const classes='{$root}script/classes/';
  const interfaces='{$root}script/classes/interface/';
  const app='{$root}script/app/';
  const www='{$root}public_html/';
  const data='{$root}data/';
  const template='{$root}template/';
  const template_c='{$root}template_c/';
  const cacher='{$root}public_html/cache/';
  
  /**
   * 以下部分为系统安装时所需内容
   */
  const domain='{$domain}';
  const baseurl='{$baseurl}';
  
  /**
   * 数据库参数
   */
  const DB_STATUS='valid';
  const DB_DNS='';
  const DB_USER='';
  const DB_PASS='';
  
  /**
   * 缓存用参数
   */
  const cache="cache";
  const cache_file="localhost";
  
  /**
   * 开发用debug设定(error_reporting参数)
   */
  const debug_level=32767;
  /**
   *Dispatcher
   */
  const Dispatcher="myDispatcher";
  const actionTag='{$action_tag}';
  /**
   * 系统安装时的一些默认参数
   */
  public static \$config=array(
			       "debug"=>true,
			       );

  /**
   * url route
   */
  public static \$app2url=array(
				"/"=>"index"
				);

}
CONFIG;

$origin_config_file=$root."config/config.meta.origin";
overwrite($origin_config_file,$origin_config);

$meta_config=<<<MCONFIG
<?php

require_once "{$root}config/config.meta.origin";

class My extends My_Origin {


}
MCONFIG;

$meta_config_file=$root."config/config.meta.php";
overwrite($meta_config_file,$meta_config,false);

//.htaccess
$htaccess=<<<HTACCESS
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
ReWriteBase {$baseurl}                                                      
ReWriteRule ^(.*)$ index.php?{$action_tag}=$1&%{QUERY_STRING} [L]
HTACCESS;
$htaccess_file=$root."public_html/.htaccess";
overwrite($htaccess_file,$htaccess);

//clean data && template_c 
if(!is_dir($root."data/")){
  mkdir($root."data/");
}
if(!is_dir($root."template_c")){
  mkdir($root."template_c");
}
cleandir($root."data/");
cleandir($root."template_c/");

//chmod
chmod($root."data/",0757);
chmod($root."template_c",0757);
