<?php
/**
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
class Checker {
  //check handler number
  const Exists=0;
  const Number=1;
  const Jtext=2;
  const Kana=3;
  const Email=4;
  const PostZip=5;
  const Tel=6;
  const Int=7;
  const Float=8;
  const Ip=9;
  const Url=10;
  const Boolean=11;
  const AlphaNum=12;
  const Jname=13;
  const TagCheck=14;
  //filter
  private static $validate=array(
			  "bool"=>258,
			  "mail"=>274,
			  "email"=>274,
			  "float"=>259,
			  "int"=>257,
			  "ip"=>275,
			  "url"=>273,
			  "regexp"=>272,
			  );
  private static $sanitize=array(
			  "mail"=>517,
			  "encode"=>514,
			  "magic_quotes"=>521,
			  "float"=>520,
			  "int"=>519,
			  "html"=>515,
			  "string"=>513,
			  "url"=>518,
			  );
  private static $flag=array(
		      "strip_low"=>4,
		      "strip_hign"=>8,
		      "fraction"=>4096,
		      "thousand"=>8192,
		      "scientific"=>16384,
		      "quotes"=>128,
		      "encode_low"=>16,
		      "encode_hign"=>32,
		      "amp"=>64,
		      "octal"=>1,
		      "hex"=>2,
		      "IPv4"=>1048576,
		      "IPv6"=>2097152,
		      "no_private static "=>8388608,
		      "no_res"=>4194304,
		      "host"=>131072,
		      "path"=>262144,
		      "required"=>524288,
		      "return_null"=>134217728,
		      "return_array"=>60108864,
		      "require_array"=>16777216,
		      );
  private static $reg=array(
		     "name"=>"/^[あ-んァ-ヾ一-龠]+$/",
		     "mail"=>"/^[\w\-\.]+@[\w-]+(\.[\w]+)+$/",
		     "email"=>"/^[\w\-\.]+@[\w-]+(\.[\w]+)+$/",
		     "kana"=>"/^[あ-んァ-ヾ]*$/",
		     "url"=>"/^https?:\/\/([^/:]+)(:(\d+))?(\/.*)?$/",
		     "id"=>"/^[a-zA-Z0-9]$/",
		     "number"=>"/^\s*(\d+([-\s]\d+)*)$/",
		     "Jtext"=>"/^[あ-んァ-ヾ一-龠\w\s,、，。@\-]*$/",
		     "filter"=>"/<[^\d](?:\"[^\"]*\"|'[^']*'|[^'\">*])*>/",
		     );

  public static function check($val,$flag=null){
    if($flag===null){
      trigger_error("YOU SHOULD ASSIGNATION A FLAG FOR CHECKER",E_USER_NOTICE);
      return;
    }
    switch(true){
    case isset(self::$validate[$flag]):
      return self::validate($val,$flag);
      break;
    case isset(self::$reg[$flag]):
      return self::regCheck($val,$flag);
      break;
    default:
      return self::regFilter($val);
      break;
    }
  }

  public static function notNull($val){
    if(empty($val)){
      return false;
    }
    return true;
  }
  
  private static function validate($val,$flag){
    $_val=filter_var($val,self::$validate[$flag]);
    if($_val!==$val){
      return false;
    }
    return true;
  }

  private static function regCheck($val,$flag){
    if(!preg_match(self::$reg[$flag],$val)){
      return false;
    }
    return true;
  }

  private static function regFilter($val){
    if(!preg_match(self::$reg["filter"],$val)){
      return false;
    }
    return true;
  }

  public static function myFormCheck($val,$set){
    $res=array(
	       "status"=>"success",
	       "message"=>$set["message"]
	       );
    switch($set["rule"]){
    case self::Exists:
      if(!self::notNull($val)){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※必須項目";
      }
      break;
    case self::Number:
      if(!self::regCheck($val,"number")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※数字を入力してください";
      }
      break;
    case self::Jtext:
      if(!self::regCheck($val,"Jtext")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※日本語文章を入力してください";
      }
      break;
    case self::Kana:
      if(!self::regCheck($val,"kana")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※ふりかなを入力してください";
      }
      break;
    case self::Email:
      if(!self::validate($val,"mail")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※不正なメールアドレス形式";
      }
      break;
    case self::PostZip:
      if(!self::regCheck($val,"number")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※不正な郵便番号";
      }
      break;
    case self::Tel:
      if(!self::regCheck($val,"number")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※不正な電話番号";
      }
      break;
    case self::Int:
      if(!self::validate($val,"int")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※不正な入力値";
      }      
      break;
    case self::Float:
      if(!self::validate($val,"float")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※不正な入力値";
      }            
      break;
    case self::Ip:
      if(!self::validate($val,"ip")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※不正なIPアドレス";
      }                  
      break;
    case self::Url:
      if(!self::validate($val,"url")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※不正なURL";
      }                        
      break;
    case self::Boolean:
      if(!self::validate($val,"url")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※不正な値";
      }                              
      break;
    case self::AlphaNum:
      if(!self::regCheck($val,"id")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※英数字だけを入力してください";
      }
      break;
    case self::Jname:
      if(!self::regCheck($val,"name")){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※漢字とふりかなを入力してください";
      }
      break;
    case self::TagCheck:
    default:
      if(!self::regFilter($val)){
	$res["status"]="error";
	$res["message"]=$res["message"]?$res["message"]:"※不正な文章";	
      }
      break;
    }
    return $res;
  }
  
}