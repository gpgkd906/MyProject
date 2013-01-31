<?php

class pass extends appController {

  public function index(){
    if(empty($_POST)){
      return;
    }

    $db=$this->super->getInstance("MyDO");
    //admin
    if(isset($this->request["send"])){
      if(!isset($this->request["pass"][0])){
	$error["pass"] = '<font style="color:#FF0000;">新しいパスワードを入力して下さい！</font>';
      }else{
	$db->from("accounts")->find("id",11)->set("passwd",$this->request["pass"]);
	$db->update();
	$error["pass"] = '<font style="color:#FF0000;">パスワードを変更しました</font>';
      }
    }
    //user
    if(isset($this->request["usend"])){
      if(!isset($this->request["upass"][0])){
	$error["upass"] = '<font style="color:#FF0000;">新しいパスワードを入力して下さい！</font>';
      }else{
	$db->from("accounts")->find("id",12)->set("passwd",$this->request["pass"]);
	$db->update();
	$error["upass"] = '<font style="color:#FF0000;">パスワードを変更しました</font>';
      }
    }
    $this->set("error",$error);
  }
  
}