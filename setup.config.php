<?php

#user define




#sys define
if(!isset($domain[0])){
  $domain="http://localhost/".str_replace("/var/www/","",str_replace("/var/www/html/","",$root))."public_html/";
}
if(!isset($baseurl[0])){
  $baseurl=str_replace("/var/www","",str_replace("/var/www/html","",$root))."public_html/";
}
//action tag
if(!isset($action_tag[0])){
  $action_tag="req";
}