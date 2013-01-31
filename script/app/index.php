<?php 

class indexMyController extends application {
  
  public function index(){
    $this->getFormHelper();
    $form=$this->formHelper->create("test");
    $form->addText("name");
    $form->setPlaceholder("name","名前");
    $form->addText("age");
    $form->setPlaceholder("age","年齢");
    $form->addRadio("sex",array(2,3),array("男","女"));
    $form->setDefault("sex",3);
    $form->addSelect("category",array(2,3,5,7),array("記事1","記事2","記事3","記事4"));
    $form->setDefault("category",5);
    $form->addTextArea("comment");
    $form->addFile("thumnil");
    //$form->addImg("thumnil",My::baseurl."image/test.jpg","400","400");
    /* $form->addCheckRule("name",Checker::Jname); */
    /* $form->addCheckRule("name",Checker::Exists); */
    /* $form->addCheckRule("age",Checker::Number); */
    /* $form->addCheckRule("age",Checker::Exists); */
    /* $form->addCheckRule("comment",Checker::Exists); */
    /* $form->addCheckRule("comment",Checker::Jtext); */
    $this->super->myForm->copy("test","contact");
    $form=$this->super->myForm->find("contact");
    $this->set("form",$this->super->myForm);
    if($form->isSubmitted()){
      $form->confirm(function($type,$name,$data){
	  
	});
    }
  }

}