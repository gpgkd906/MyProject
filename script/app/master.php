<?php

class master extends appController {
  private $item;
  
  public function __construct(){
    if(isset($_GET["item"]))$this->item=str_replace("m_","",$_GET["item"]);
  }

  public function index(){
    if(isset($_GET["item"][0])){
      $this->_index($this->item);
      $this->setTpl("master.{$this->item}.tpl");
    }
  }

  public function form(){
    $this->_form($this->item);
    $this->setTpl("master.form.{$this->item}.tpl");
  }

  public function conf(){
    $check=array(
		 "course"=>array("name"=>"コース名","price"=>"金額","count"=>"回数"),
		 "shop"=>array("name"=>"店舗名")
		 );
    $this->_form($this->item);
    $this->_conf($this->item,$check[$this->item]);
  }

  public function comp(){
    $this->form();
    $this->_comp($this->item);
  }

  public function delete(){
    $this->_form($this->item);
    $this->setTpl("master.delete.{$this->item}.tpl");
  }
  
  public function up(){
    $this->_sortRank();
  }

  public function down(){
    $this->_sortRank();
  }

  private function _sortRank(){
    if($this->request["mode"]=="rank" && !empty($this->request["id"]) ){
      //目標の現在表示順を取得
      $db=$this->super->getInstance("MyDO");
      $target=$db->from("shop")->find("shop_id",$this->request["id"])->select()->fetch(2);
      //表示順を上げる場合，目標よりrankが一個大きい店舗とrank値を交換する,下げる場合は逆
      $db->from("shop")->find("del_flg",0);
      switch($this->request["status"]){
      case "up":
	$db->find("rank",$target["rank"]-1);
	break;
      case "down":
	$db->find("rank",$target["rank"]+1);
	break;
      default:
	//pass
	break;
      }
      $changer=$db->select()->fetch(2);
      if( !empty($changer)){
	$db->from("shop")->set("rank",$changer["rank"])->find("shop_id",$target["shop_id"])->update();
	$db->from("shop")->set("rank",$target["rank"])->find("shop_id",$changer["shop_id"])->update();
      }
    }
    $this->_index($this->item);
    $this->setTpl("master.{$this->item}.tpl");
  }

  private function _index($item){
    $db=$this->super->getInstance("MyDO");
    if ( !empty($_POST['delete']) && !empty($_POST['id']) ) {
      $db->from($item)->set("del_flg",1)->find("{$item}_id",$_POST["id"]);
      if($item=="course"){
	$db->set("updtime=now()");
      }
      $db->update();
    }      
    $num=$db->from($item)->find("del_flg",0)->select("count(*) as reccnt")->fetch();
    $plim=10;
    $max=ceil($num["reccnt"]/$plim);
    $page=1;
    if(isset($_GET["page"])){
      $page=intval($_GET["page"]);
    }
    $pager=$this->super->getInstance("page");
    $pager->request($page);
    $pager->max($max);
    $pager->length(3);
    $pager->url("./?cat=master&item=m_{$item}&page=<{page}>");
    $start=($page-1)*$plim;
    $selector=$db->from($item)->find("del_flg",0)->limit($start,$plim);
    if($item=="shop"){
      $selector->orderby("rank");
    }
    $records=$selector->select()->fetchall(2);
    $this->set("pager",$pager);
    $this->set($item,$records);
  }

  private function _form($item){
    $mode=array(
		"value"=>$this->request["mode"],
		"string"=>$this->request["mode"]=="new"?"新規登録":"編集",
		);
    $this->request["mode"]=$mode;
    if(!empty($this->request["id"])){
      $db=$this->super->getInstance("MyDO");
      $rows=$db->from($item)->find("del_flg",0)->find("{$item}_id",$this->request["id"])->select()->fetch(2);
      $this->request=array_merge($rows,$this->request);
    }
  }

  private function _conf($item,$check){
    $error=array();
    $request=$this->request;
    foreach($check as $k=>$v){
      if(empty($request[ $k ])){
	$error[ $k ]="<font color='#ff0000'>{$v}を入力して下さい。</font>";
      }
    }
    $this->set("error",$error);
    $this->setTpl("master.conf.{$item}.tpl");
  }

  private function _comp($item){
    if(empty($_POST["back"])){
      $request=$this->request;
      $db=$this->super->getInstance("MyDO");
      $db->from($item);
      if ( !empty($_POST['comp']) ) {
	$db->set("name",$request["name"]);
	if($item==="course"){
	  $db->set("price",$request["price"])->set("count",$request["count"]);
	}
	if ( $request["mode"]["value"] == 'new' ) {
	  if($item==="shop"){
	    $db->set("rank",0);
	  }
	  $db->set("del_flg",0)->insert();
	}elseif( $request["mode"]["value"]=="edit"){
	  if($item==="course"){
	    $db->set("updtime=now()");
	  }
	  $db->find("{$item}_id",$request["id"])->update();
	}
      }
      $this->setTpl("master.comp.{$item}.tpl");
    }
  }

}