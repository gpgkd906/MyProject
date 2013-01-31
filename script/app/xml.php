<?php

class xmlMyController extends application {
  
  public function feed(){
    $xml=$this->super->getInstance("xml");
    $xml->url("http://www.j-gasshuku.jp/?feed=rss2");
    $arr=$xml->fetch(2);
    echo json_encode($arr,JSON_HEX_APOS);
    die();
  }

}