<?php
//modelが必要かな


class uploadMyController extends application {
  
  //一覧ページ
  public function index(){
    $db=$this->super->db;
    $tag=$db->from("tag")->getAll();
    $tagCloud=$this->super->getInstance("tagcloud");
    $app=$this;
    $tags=array_map(function($tag) use ($app){
	$item=array(
		    "url"=>$app->link("upload","index",array("tag"=>$tag["val"])),
		    "keyword"=>$tag["key"],
		    );
	return $item;
      },$tag);
    $tagCloud->load($tags);
    $this->set("tag",$tag);
    $this->set("tagCloud",$tagCloud);

    $category=$db->from("category")->getAll();
    $this->set("category",$category);

    $articleModel=$db->from("article");
    if(isset($this->param["tag"])){
      //タグの場合はprimeのORsearchによる検索する
      $prime=$this->super->getInstance("prime");
      $tagval=array_map(function($tag){
	  return $tag["val"];
	},$tag);
      $target=$prime->orSearch($this->param["tag"],$tagval);
      //配列に入れる場合はtag in (?,?,?...)になる
      $articleModel->find("tag",$target);
    }
    if(isset($this->param["cate"])){
      $articleModel->find("category",$this->param["cate"]);      
    }
    $article=$articleModel->limit(10)->orderBy("reg_time desc")->getAll();
    $this->set("article",$article);
  }
  
  //各作品ページ
  public function article(){
    $db=$this->super->db;
    $article=$this->getArticleById($this->param["id"]);
    $article=$this->getArticleInfo($article);
    $this->set("article",$article);
  }

  public function download(){
    $db=$this->super->db;
    $article=$this->getArticleById($this->param["id"]);
    $download=$this->super->getInstance("download");
    $download->name($article["originalName"])->file($article["path"].$article["filename"])->start();
    exit;
  }

  private function getArticleById($id){
    $db=$this->super->db;
    return $db->from("article")->find("id",$id)->select()->fetch(2);
  }

  private function getArticleInfo($art){
    $prime=$this->super->getInstance("prime");
    $db=$this->super->db;
    //cate
    $catVal=$prime->childOf($art["category"]);
    $category=$db->from("category")->find("val",$catVal)->select()->fetchAll(2);
    $art["category"]=$category;
    //tag
    $tagVal=$prime->childOf($art["tag"]);
    $tags=$db->from("tag")->find("val",$tagVal)->select()->fetchAll(2);
    $art["tags"]=$tags;
    //aurhor
    $author=$db->from("user")->find("id",$art["author"])->select()->fetch(2);
    $art["author"]=$author;
    return $art;
  }

  //アップロードページ
  public function upload(){
    $db=$this->super->db;
    $this->getFormHelper();
    $form=$this->formHelper->create("upload");
    $form->addText("name");
    $form->setPlaceholder("name","名前");
    $form->addText("nick");
    $form->setPlaceholder("nick","ニックネーム");
    //無限カテゴリ
    $cates=$db->from("category")->select()->fetchAll(2);
    $defCat=array(array(""),array(0));
    if(!empty($cates)){
      list($names,$vals)=array_reduce($cates,function($init,$item){
	  $init[0][]=$item["key"];
	  $init[1][]=$item["val"];
	  return $init;
	},$defCat);
      $form->addSelect("category",$vals,$names);
      $form->setDefault("category",0); 
    }
    $form->addText("addcat");
    $form->setPlaceholder("addcat","未登録カテゴリ");
    //無限タグ
    $defTag=array(array(),array());
    $tags=$db->from("tag")->select()->fetchAll(2);
    list($names,$vals)=array_reduce($tags,function($init,$item){
	$init[0][]=$item["key"];
	$init[1][]=$item["val"];
	return $init;
      },$defTag);
    $form->addCheckbox("tag[]",$vals,$names);
    $form->addText("addtag");
    $form->setPlaceholder("addtag","未登録タグ");
    
    $form->addFile("article");
    $this->set("form",$this->super->myForm);
    if($form->isSubmitted()){
      $subData=array();
      $form->confirm(function($type,$name,$data) use ($db,&$subData,$form){
	  if(empty($data)){
	    return false;
	  }
	  switch($name){
	  case "addcat":
	    
	    $subData[$name]=$this->_getCatOrTag($data,"category",$db);
	    break;
	  case "addtag":
	    
	    $subData[$name]=$this->_getCatOrTag(explode(",",$data),"tag",$db);
	    break;
	  case "name":
	    //名前は重複可能としよう
	    $subData[$name]=$data;
	    break;
	  case "nick":
	    //ニックネームは一意の値としよう
	    $res=$db->from("user")->find("nick",$data)->select()->fetch(2);
	    if(empty($res)){
	      $ip=ip2long($_SERVER["REMOTE_ADDR"]);
	      $db->from("user")->set("nick",$data)->set("ip",$ip)->set("del_flg",0)->insert();
	      $data=$db->lastId();
	    }else{
	      $data=$res["id"];
	    }
	    $subData["uid"]=$data;
	    break;
	  case "article":
	    list($type,$ext)=explode("/",$data["type"]);
	    $filename=uniqid("upload");
	    switch($type){
	    case "image":
	      $filename=$filename.".".$ext;
	      move_uploaded_file($data["tmp_name"],My::www."img/".$filename);
	      $subData[$name]=$filename;
	      $subData["originalName"]=$data["name"];
	      $subData["type"]=$type;
	      $subData["path"]=My::www."img/";
	      $form->addImg("uArticle",My::baseurl."img/".$filename);
	      break;
	    default:
	      break;
	    }
	    break;
	  default:
	    $subData[$name]=$data;    
	    break;
	  }
	  unset($name);
	  unset($data);
	});
      //uidと名前情報があれば,ユーザプロファイルに更新する
      if(isset($subData["uid"]) && isset($subData["name"][0])){
	$db->from("user")->find("id",$subData["uid"])->set("name",$subData["name"])->update();
      }
      //作品の情報を最終整理し，prime検索機能適用
      if(isset($subData["addcat"])){
	$subData["category"]=$subData["addcat"];
      }
      if(isset($subData["addtag"])){
	$subData["tag"][]=$subData["addtag"];
	$prime=$this->super->getInstance("prime");
      }
      $subData["tag"]=array_product($subData["tag"]);
      //整理したデータを更新する
      $db->from("article")
	->set("filename",$subData["article"])
	->set("path",$subData["path"])
	->set("originalName",$subData["originalName"])
	->set("tag",$subData["tag"])
	->set("category",$subData["category"])
	->set("author",$subData["uid"])
	->set("type",$subData["type"])
	->set("status","public")
	->set("del_flg",0)
	->insert();
      var_dump($db->getLastQuery());
    }
  }

  private function _getCatOrTag($data,$table,$db){
    $res=$db->from($table)->find("key",$data)->select()->fetchAll(2);
    if(empty($res)){
      $maxPrime=$db->from($table)->select("max(val) as mval")->fetch(2);
      $prime=$this->super->getInstance("prime");
      $nextPrime=$maxPrime["mval"];
      if(is_array($data)){
	$newData=array();
	foreach($data as $d){
	  $nextPrime=$prime->getNextPrime($nextPrime);
	  $db->from($table)->set("key",$d)->set("val",$nextPrime)->insert();
	  $newData[]=$nextPrime;
	}
	$data=array_product($newData);
      }else{
	$nextPrime=$prime->getNextPrime($nextPrime);
	$db->from($table)->set("key",$data)->set("val",$nextPrime)->insert();
	$data=$nextPrime;
      }
    }
    return $data;
  }

}