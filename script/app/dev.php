<?php

class devMyController extends application {

    
  public function low(){
    $this->super->view->addTag("input",function($match){
	$attr=explode(" ",$match);
	return "echo '".join("|",$attr)."'";
      });
  }

  public function gmap(){
    $gmap=$this->super->getInstance("googleMap");
    $response=$gmap->getGeoByAddr("長崎県佐世保市八幡町1番10号");
    echo "<pre>";
    var_dump($response);
    die();
  }

}