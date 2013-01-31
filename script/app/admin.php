<?php

class adminMyController extends application {

  public function meta(){
    $this->bm->set("actionStart");
    $meta=$this->super->getInstance("meta");
    //formを生成する
    $this->getFormHelper();
    $this->bm->set("module loaded");
    $form=$this->formHelper->create("meta");
    if($form->isSubmitted()){
      $data=$form->getData();
      if(isset($data["old"])){
	foreach($data["old"] as $key=>$_data){
	  if(!isset($_data["del_flg"][0])){
	    $_data["del_flg"]="0";
	  }
	  $meta->update($_data,array("id"=>$key));
	}
      }
      if(isset($data["new"])){
	foreach($data["new"] as $_data){
	  $meta->add($_data);
	}
      }
    }
    $this->bm->set("checkSubmitted");
    $allmeta=$meta->get();
    $this->bm->set("get meta Data");
    foreach($allmeta as $_meta){
      $form->addText("old[{$_meta['id']}][metaid]",$_meta["metaid"]);
      $form->addText("old[{$_meta['id']}][relatives]",$_meta["relatives"]);
      $form->addText("old[{$_meta['id']}][name]",$_meta["name"]);
      $form->addText("old[{$_meta['id']}][status]",$_meta["status"]);
      $form->addText("old[{$_meta['id']}][tables]",$_meta["tables"]);
      $form->addCheckbox("old[{$_meta['id']}][del_flg]",1);
      $form->setDefault("old[{$_meta['id']}][del_flg]",$_meta["del_flg"]);
      $iterator[]=$_meta["id"];
    }
    //空の入力フォームを生成する
    $range=range(1,5);
    foreach($range as $item){
      $form->addText("new[{$item}][metaid]");
      $form->addText("new[{$item}][relatives]");
      $form->addText("new[{$item}][name]");
      $form->addText("new[{$item}][status]");
      $form->addText("new[{$item}][tables]");
      $form->addCheckbox("new[{$item}][del_flg]",0);
      $form->setDefault("new[{$item}][del_flg]",1);
    }
    $this->bm->set("form designed");
    $this->set("iterator",$iterator);
    $this->set("range",$range);
  }

  public function mailManage(){
    switch($this->param["category"]){
    case "template":
      $this->mail_template();
      break;
    case "mail":
      $this->mail_mail();
      break;
    default:
      break;
    }
  }
  
  private function mail_template(){
    $this->setTpl("admin/mail/template.tpl");
    $mailMagazine=$this->super->getInstance("mailMagazine");
    //formを生成する
    $this->getFormHelper();
    $form=$this->formHelper->create("magazineManage");
    $self=$this;
    $form->submit(function($form) use ($mailMagazine,$self){
	$data=$form->getData();
	if(isset($data["old"])){
	  foreach($data["old"] as $id=>$old){
	    $old["del_flg"]=$old["del_flg"]?$old["del_flg"]:0;
	    $mailMagazine->updateMagazine($id,$old["name"],$old["invalue"],$old["option"],$old["del_flg"]);
	  }
	}
	if(isset($data["new"])){
	  foreach($data["new"] as $new){
	    $new["del_flg"]=$new["del_flg"]?$new["del_flg"]:0;
	    $mailMagazine->createMagazine($new["name"],$new["invalue"],$new["option"],$new["del_flg"]);
	  }
	}
      });
    $magazine=$mailMagazine->getMagazine();
    foreach($magazine as $maga){
      $form->addTexts(array(
			   "old[{$maga['id']}][name]"=>$maga["name"],
			   "old[{$maga['id']}][option][subject]"=>$maga["option"]["subject"],
			   "old[{$maga['id']}][option][cc]"=>$maga["option"]["cc"],
			   "old[{$maga['id']}][option][bcc]"=>$maga["option"]["bcc"],
			   "old[{$maga['id']}][option][from]"=>$maga["option"]["from"],
			   ));
      $form->addTextArea("old[{$maga['id']}][invalue]",$maga["invalue"]);
      $form->addCheckbox("old[{$maga['id']}][del_flg]",1);
      $form->setDefault("old[{$maga['id']}][del_flg]",$maga["del_flg"]);
      $form->changeType("old[{$maga['id']}][invalue]","textarea");
      $form->changeType("old[{$maga['id']}][del_flg]","checkbox");
      $iterator[]=$maga["id"];
    }
    $this->set("iterator",$iterator);
    $form->addTexts(array(
			  "new[0][name]"=>null,
			  "new[0][invalue]"=>null,
			  "new[0][option][subject]"=>null,
			  "new[0][option][cc]"=>null,
			  "new[0][option][bcc]"=>null,
			  "new[0][option][from]"=>null,
			  "new[0][del_flg]"=>1,
			  ));
    $form->addTextArea("new[0][invalue]");
    $form->addCheckbox("new[0][del_flg]",1);
    $form->setDefault("new[0][del_flg]",0);
    $form->changeType("new[0][invalue]","textarea");
    $form->changeType("new[0][del_flg]","checkbox");
  }
  
  private function mail_mail(){
    
  }

}