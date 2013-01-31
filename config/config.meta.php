<?php

require_once "/var/www/My/config/config.meta.origin";

class My extends My_Origin {

  const domain="http://vm2/My/public_html/";

  //  const DB_STATUS="invalid";
  const DB_DNS="mysql:host=localhost;dbname=mare";
  const DB_USER="root";
  const DB_PASS="";

  const cache="Memcache";
  const cache_file="localhost";

  const timeZone="Asia/Tokyo";
  const logger="Log.log";
  
  const debug_level=30711;

  public static $app2url=array(
			       "/"=>array("index","index"),
			       "schedule/"=>array("schedule","index"),
			       "schedule/.*"=>array("schedule","index"),
			       "calendar/.*"=>array("schedule","index"),
			       "input/"=>array("input","index"),
			       "input/.*"=>array("input","index"),
			       "low/index.html"=>array("low","index"),
			       );
  
  public static function escape($data) {
    if(is_array($data)){
      foreach($data as $key => $value){
	$data[$key]=self::escape($value);
      }
      return $data;
    }elseif(is_string($data)){
      return htmlspecialchars($data,ENT_QUOTES);
    }else{
      return $data;
    }
  }
  
}