<?php

class mailMagazine {
  private $driver;
  private $mydo;
  private $table="mailMagazine";
  private $cache;

  public function __construct(){
    $context=controller::getSingletonInstance();
    $this->mydo=$context->MyDO;
    $this->dbdriver=$this->mydo->getDriver();
    $this->cache=$context->getInstance("module")->getCache();
    //init
    $create="CREATE TABLE IF NOT EXISTS `".$this->table."` ("
      ."`id` int(11) NOT NULL AUTO_INCREMENT,"
      ."`type` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',"
      ."`name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '认知名',"
      ."`invalue` text COLLATE utf8_unicode_ci NOT NULL COMMENT '内容值',"
      ."`option` text  COLLATE utf8_unicode_ci NOT NULL COMMENT '其他内容',"
      ."`del_flg` tinyint(1) NOT NULL,"
      ."`reg_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,"
      ."PRIMARY KEY (`id`)"
      .") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
    $this->dbdriver->query($create);
  }
  
  public function createMagazine($name,$template=null,$option=null,$del_flg=0){
    if(empty($name) && empty($template)){
      $error="入力値は存在しません，更新は行いません";
      return $error;
    }
    $this->mydo->from($this->table);
    $this->mydo->set("name",$name)->set("type","magazine")->set("del_flg",$del_flg);
    if($template){
      $this->mydo->set("invalue",$template);
    }
    if($option){
      $this->mydo->set("option",serialize($option));
    }
    $this->mydo->insert();
  }

  public function updateMagazine($id,$name,$template,$option=null,$del_flg=0){
    if(empty($name) && empty($template)){
      $error="入力値は存在しません，更新は行いません";
      return $error;
    }
    $this->mydo->from($this->table);
    $this->mydo->find("id",$id)->find("type","magazine")->set("name",$name)->set("invalue",$template)->set("del_flg",$del_flg);
    if($option){
      $this->mydo->set("option",serialize($option));
    }
    $this->mydo->update();
  }

  public function getMagazine($name=null){
    $cacheKey="mailMagazine_getMagazine_".$name;
    $res=$this->cache->get($cacheKey);
    if(!$res){
      $this->mydo->from($this->table)->find("type","magazine");
      if($name){
	$this->mydo->find("name",$name);
      }
      $magazine=array();
      foreach($this->mydo->select()->fetchAll(2) as $item){
	$item["option"]=unserialize($item["option"]);
	$magazine[]=$item;
      }
      $res=$magazine;
      $this->cache->set($cacheKey,$res,86400);
    }
    return $res;
  }

  public function addMail($magazine_name,$mail,$option=null){
    $this->mydo->from($this->table);
    $this->mydo->set("type","mail")->set("name",$magazine_name)->set("invalue",$mail);
    if($option!==null){
      $option=serialize($option);
      $this->mydo->set("option",$option);
    }
    $this->mydo->insert();
  }

  public function getMail($mail=null,$magazine_name=null){
    $this->mydo->from($this->table)->find("type","mail");
    if($mail!==null){
      $this->mydo->find("invalue",$mail);      
    }
    if($magazine_name!==null){
      $this->mydo->find("name",$magazine_name);
    }
    return $this->mydo->select()->fetch(2);
  }
  
  public function removeMail($mail,$magazine_name=null){
    $this->mydo->from($this->table);
    if($magazine_name!==null){
      $this->mydo->find("name",$magazine_name);
    }
    $this->mydo->find("type","mail")->find("invalue",$mail)->set("del_flg",1);
    $this->mydo->update();
  }
  
  public function publishMagazine($name){
    $this->mydo->from($this->table);
    $this->mydo->find("type","mail")->find("name",$name)->find("del_flg",0);
    $mails=$this->mydo->select("invalue as mail,option as uoption")->fetchAll(2);
    $this->mydo->from($this->table);
    $this->mydo->find("type","magazine")->find("name",$name)->find("del_flg",0);
    $template=$this->mydo->select("invalue as template,option as toption")->fetch(2);
    if(empty($template)){
      return false;
    }
    $template["toption"]=unserialize($template["toption"]);
    $this->sendMails($mails,$template);
  }

  public function sendMails($mails,$template){
    foreach($mails as $mail){
      list($mailAddress,$uoption)=$mail;
      $uoption=unserialize($uoption);
      $this->sendMail($mailAddress,$template,$option);
    }
  }

  public function sendMail($mail,$template,$option){
    list($template,$topt)=$template;
    if(!empty($option["var"])){
      foreach($option["var"] as $var=>$val){
	$template=str_replace($val,$val);
      }
    }
    $subject=isset($option["title"][0])?$option["title"]:$topt["title"];
    $body=$template;
    $head="FROM:";
    $head.=isset($option["from"][0])?$option["from"]:$topt["from"]."\r\n";
    if($cc=isset($option["cc"][0])?$option["cc"]:$topt["cc"]){
      $head.="Cc:".$cc."\r\n";
    }
    if($bcc=isset($option["bcc"][0])?$option["bcc"]:$topt["bcc"]){
      $head.="Bcc:".$bcc."\r\n";
    }
    var_dump($mail,$subject,$body,$head);
    //mail($mail,$subject,$body,$head);
  }

}