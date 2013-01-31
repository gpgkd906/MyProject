<?php
/**
 *
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
final class chemplate_compiler {
  protected $ld;
  protected $rd;
  protected $cd;
  protected $td;
  protected $tcd;
  protected $last_section="";
  protected $plugin=array();
  protected $option_table=array(
				'replace'=>'str_replace',
				'date_format'=>'date',
				'reg_replace'=>'preg_replace',
				'isset'=>'isset',
				'empty'=>'empty',
				'is_array'=>'is_array',
				'join'=>'join',
				'implode'=>'join',
				);

  public function __construct($engine){
    $this->rese=&$engine->Reserve;
    $this->ld=&$engine->left_delimiter;
    $this->rd=&$engine->right_delimiter;
    $this->cd=&$engine->_cache_dir;
    $this->td=&$engine->_template_dir;
    $this->tcd=&$engine->_template_chen_dir;
    $this->engine=&$engine;
  }
  
  /**
   *编译模式
   */

  public function compile($file,$write=true){
    $text=file_get_contents($this->td.$file);
    if(strpos($text,$this->ld.'extends')!==false)$text=$this->method_extends($text);
    if(strpos($text,$this->ld.'/block')!==false)$text=$this->method_block($text);
    $text=$this->convert_comment($text);
    if(strpos($text,$this->ld.'components')!==false)$text=$this->method_components($text);
    if(strpos($text,$this->ld.'/if')!==false)$text=$this->method_if($text);
    if(strpos($text,$this->ld.'/for')!==false)$text=$this->method_for($text);   
    if(strpos($text,$this->ld.'/section')!==false)$text=$this->method_section($text);
    if(strpos($text,$this->ld.'/range')!==false)$text=$this->method_range($text);
    if(strpos($text,$this->ld.'include')!==false)$text=$this->method_include($text);
    if(strpos($text,$this->ld.'hook')!==false)$text=$this->method_hook($text);
    if(strpos($text,$this->ld.'/php')!==false)$text=$this->method_php($text);
    if(strpos($text,$this->ld.'assign')!==false)$text=$this->method_assign($text);
    $text=$this->extension($text);
    $text=$this->convert_vars($text);
    $text=trim($text);
    //生成php文件,转录tpl文件路径
    if(!$write){ return $text; }
    if(!is_dir($this->tcd))
      {
	mkdir($this->tcd);
      }
    $file=$this->tcd.'php_'.preg_replace('/\.[a-zA-Z]++$/','.php',str_replace("/","_",$file));
    if( file_put_contents($file,$text) === false){
      throw new Exception("chemplate can not write file:".$file);
    }
    return $file;
  }
  
  /**
   *置换所有注释，默认注释风格为 {开始模板标签* xxxx *关闭模板标签}
   */
  protected function convert_comment($text){
    return preg_replace('/'.$this->ld.'\*[\s\S]*?\*'.$this->rd.'/S','',$text);
  }

  /**
   *置换所有模板变数及模板标签
   */
  protected function convert_vars($text){
    $text=preg_replace('/'.$this->ld.'((?:[\'"])?\\$.+?)'.$this->rd.'/S','<?php echo $1 ?>',$text);
    $text=str_replace(array($this->rd,$this->ld),array(' ?>','<?php '),$text);
    $text=preg_replace_callback('/\\$(?<![\']\\$)(\w++(?:\.\w++)*)((?:\|[^|\s]++)*)/S',array(&$this,'template_var'),$text);
    //变量名保护
    $text=preg_replace('/\'(\\$.*?)\'(?<!\[\')(?!\])/S','$1',$text);
    $text=str_replace('@@','$',$text);
    return $text;
  }

  protected function template_var($match){
    $option[1]=$option[2]="";
    if(isset($match[2][1])){
      $option=$this->option($match[2]);
      $option["1"]=preg_replace_callback('/\\$(?<![\']\\$)(\w++(?:\.\w++)*)((?:\|[^|\s]++)*)/S',array(&$this,'template_var'),$option["1"]);
      $option["2"]=preg_replace_callback('/\\$(?<![\']\\$)(\w++(?:\.\w++)*)((?:\|[^|\s]++)*)/S',array(&$this,'template_var'),$option["2"]);
    }
    if(!isset($option['default'])){      $option['default']='';    }
    
    $set=explode('.',$match[1]);
    if(isset($set[1]) && $set[1]=='const'){
      return $option['1'].$set[2].$option['default'].$option['2'];
    }
    return $option['1'].'$this->_vars[\''.join('\'][\'',$set).'\']'.$option['default'].$option['2'];
  }
  
  protected function option($str){
    $options=array_reverse(explode('|',$str));
    $default=$le=$re="";
    foreach($options as $vars){
      $ops=explode(':',$vars);
      $fp_len=count($ops);
      switch($ops[0]){
      case "escape":
	if(isset($ops[1])){
	  switch($ops[1]){
	  case "'html'":case '"html"':
	    $le.='htmlspecialchars(';
	    $re=',ENT_QUOTES)'.$re;
	    break;
	  case "'htmlall'":case '"htmlall"':
	    $le.='htmlentities(';
	    $re=',ENT_QUOTES)'.$re;
	    break;
	  case "'url'":case '"url"':
	    $le.='rawurlencode(';
	    $re=')'.$re;	
	    break;
	  default:break;
	  }
	}else{
	  $le.='htmlspecialchars(';
	  $re=',ENT_QUOTES)'.$re;
	}
	break;
      case "unescape":
	if(isset($ops[1])){
	  switch($ops[1]){
	  case "'html'":case '"html"':
	    $le.='htmlspecialchars_decode(';
	    $re=',ENT_QUOTES)'.$re;
	    break;
	  case "'htmlall'":case '"htmlall"':
	    $le.='html_entity_decode(';
	    $re=',ENT_QUOTES)'.$re;
	    break;
	  case "'url'":case '"url"':
	    $le.='rawurldecode(';
	    $re=')'.$re;	
	    break;
	  default:break;
	  }
	}else{
	  $le.='htmlspecialchars(';
	  $re=',ENT_QUOTES)'.$re;
	}	
	break;
      case "count_words":
	$le.='count(preg_split("/[\s,;.]/",';
	$re='))'.$re;
	break;
      case "count_the_word":
	if(isset($ops[1])){
	  $le.='mb_substr_count(';
	  $re=','.$ops[1].')'.$re;
	}
	break;
      case "truncate":
	$offset=isset($ops[1])?(int)$ops[1]:80;
	$replacement=isset($ops[2])?$ops[2]:"'...'";
	$le.='mb_strimwidth(';
	$re=',0,'.$offset.','.$replacement.',\'utf-8\')'.$re;
	break;
      case "default":
	$str='';
	for($i=1;$i<$fp_len;$i++){
	  $str.=','.$ops[$i];
	}
	$le.='chemplate::makedefault(';
	$re=$str.')'.$re;
      	break;
      default:
	if(isset($this->option_table[$ops[0]])){
	  $str='';
	  for($i=1;$i<$fp_len;$i++){
	    $str.=$ops[$i].',';
	  }
	  $le.=$this->option_table[$ops[0]].'('.$str;
	  $re=')'.$re;
	}elseif( class_exists('Func') && method_exists('Func',$ops[0]) ){
	  $str='';
	  for($i=1;$i<$fp_len;$i++){
	    $str.=','.$ops[$i];
	  }
	  $le.='Func::'.$ops[0].'(';
	  $re=$str.')'.$re;
	}elseif( function_exists($ops[0]) ){
	  $str='';
	  for($i=1;$i<$fp_len;$i++){
	    $str.=','.$ops[$i];
	  }
	  $le.=$ops[0].'(';
	  $re=$str.')'.$re;
	}
      }
    }
    return array('1'=>$le,'2'=>$re,'default'=>$default);
  }

  /**
   * if方法的匹配与置换
   */
  protected function method_if($text){
    $pattern="/".$this->ld."(?:(?>\/?)if|else(?:if)?).*?".$this->rd."/S";
    preg_match_all($pattern,$text,$match);
    foreach($match[0] as $key=>$strings){
      $ori=$strings=str_replace(array($this->rd,$this->ld),array("",""),$strings);
      if($strings==='/if'){
	$strings=str_replace('/if','}',$strings);
      }elseif($strings==='else'){
	$strings=str_replace('else','}else{',$strings);
      }else{
	$strings=str_replace('else if','}elseif',$strings);
	$strings=str_replace(';','',$strings);
	$strings=str_replace('if','if(',$strings);
	$strings.=" ){";
      }
      $text=str_replace($this->ld.$ori.$this->rd,$this->ld.$strings.$this->rd,$text);
    }
    return $text;
  }
  
  /**
   * for方法的匹配与置换
   *@支持多重for
   */
  protected function method_for($text){
    preg_match_all('/'.$this->ld.'for\s+\\$(\w++)\s+in\s+\\$(\w+(?:\.\w+)*)'.$this->rd.'/S',$text,$block);
    foreach($block[0] as $key=>$tpl){
      $item=$block[1][$key];
      $obj1=$block[2][$key];
      $obj2=str_replace('.','"]["',$obj1);
      $replace=$this->ld.'if(!empty($'.$obj1.')){'
	.'foreach($'.$obj1.' as $'.$this->rese.'["for"]["'.$obj2.'"]["index"]=>$'.$item.'){'.$this->rd;
      $text=str_replace($tpl,$replace,$text);
    }
    $text=str_replace($this->ld.'/for'.$this->rd,$this->ld.'}}'.$this->rd,$text);   
    return $text;
  }
  
  protected function method_range($text){
    preg_match_all("/".$this->ld."range(?::(\w+))?\s+(?:(\d+)\s+)?to\s+(\d+)\s*(?:by\s+(\d+))?\s*".$this->rd."/",$text,$block);
    foreach($block[0] as $key=>$tpl){
      $name=$block[1][$key]?"@@this->_vars['range']['".$block[1][$key]."']":"@@this->_vars['range']";
      $start=$block[2][$key];
      $end=$block[3][$key];
      $step=$block[4][$key]?$block[4][$key]:1;
      $replace=$this->ld
	."foreach(range($start,$end,$step) as $name){"
	.$this->rd;
      $text=str_replace($tpl,$replace,$text);
    }
    $text=str_replace("/range","}",$text);
    return $text;
  }

  protected function method_include($text){
    if(substr_count($text,"include_cache")){
      $text=preg_replace("/include_cache file=(\S+?)\s*".$this->rd."/S","@@this->include_cache_display($1);".$this->rd,$text);
    }
    if(substr_count($text,"include")){
      $text=preg_replace("/include file=(\S+?)\s*".$this->rd."/S","@@this->include_display($1);".$this->rd,$text);
    }
    return $text;
  }
  
  protected function method_extends($text,$pblocks=array()){
    $block=array();
    if(substr_count($text,'/block')){
      preg_match_all("/".$this->ld."block:([\w\.]++)".$this->rd."([\s\S]*?)".$this->ld."\/block".$this->rd."/",$text,$match);
      $block=array_combine($match[1],$match[2]);
    }
    $newblock=array_merge($block,$pblocks);
    foreach($newblock as $key=>$val){
      if(isset($pblocks[$key])){
	if(substr_count($val,$this->ld.'parent::block'.$this->rd)){
	  $newblock[$key]=str_replace($this->ld.'parent::block'.$this->rd,$block[$key],$newblock[$key]);
	}
      }
    }
    preg_match("/".$this->ld."extends\s+file=['\"](\S+?)['\"]".$this->rd."/",$text,$match);
    $extends=$match[1];
    if(is_file($this->td.$extends)){
      $text=file_get_contents($this->td.$extends);
    }else{
      die("错误:模板继承不存在对象文件");
    }
    if(substr_count($text,$this->ld.'extends')){
      return $this->method_extends($text,$newblock);
    }else{
      if(substr_count($text,'/block')){
	preg_match_all("/".$this->ld."block:([\w\.]++)".$this->rd."([\s\S]*?)".$this->ld."\/block".$this->rd."/",$text,$match);
	$block=array_combine($match[1],$match[2]);
	foreach($block as $key=>$b){
	  if(isset($newblock[$key])){
	    if(substr_count($newblock[$key],$this->ld.'parent::block'.$this->rd)){
	      $newblock[$key]=str_replace($this->ld.'parent::block'.$this->rd,$block[$key],$newblock[$key]);
	    }
	    $text=str_replace($this->ld."block:".$key.$this->rd.$b,$this->ld."block:".$key.$this->rd.$newblock[$key],$text);
	  }
	}
      }
      return $text;
    }
  }

  protected function method_block($text){
    $text=preg_replace("/".$this->ld."block:([\w\.]++)".$this->rd."/","",$text);
    $text=str_replace($this->ld.'/block'.$this->rd,"",$text);
    return $text;
  }

  /**
   *　替换模板中的钩子
   */
  protected function method_hook($text){
    $text = preg_replace("/hook(?: type=auto)? trigger=['\"]([^'\"]++)['\"]/S","@@this->trigger('$1')",$text);
    return preg_replace("/hook\s++['\"]?(\S++)['\"]?\s*/","@@this->trigger('$1')",$text);
  }

  /**
   * PHP tag
   */
  protected function method_php($text){
    preg_match_all("/".$this->ld."php".$this->rd."([\s\S]*?)".$this->ld."\/php".$this->rd."/",$text,$match);
    foreach($match[0] as $tpl){
      $php=str_replace("$","@@",$tpl);
      $php=str_replace($this->ld."php".$this->rd,"<?php ",str_replace($this->ld."/php".$this->rd," ?>",$php));
      $text=str_replace($tpl,$php,$text);
    }
    return $text;
  }

  /**
   * assign tag
   */
  protected function method_assign($text){
    preg_match_all("/".$this->ld."assign\s++([\s\S]+?)".$this->rd."/S",$text,$match);
    foreach($match[0] as $key=>$tpl){
      list($var,$val)=preg_split("/\s+/",$match[1][$key]);
      $var=preg_replace("/^[\"']|[\"']$/","",str_replace("var=","",$var));
      $val=preg_replace("/^[\"']|[\"']$/","",str_replace("value=","",$val));
      if(strpos($val,"$")===0){
	$text=str_replace($tpl,"<?php @@this->assign('".$var."',".$val."); ?>",$text);	
      }else{
	$text=str_replace($tpl,"<?php @@this->assign('".$var."',\"".$val."\"); ?>",$text);
      }
    }
    return $text;
  }

  /**
   * components
   * 实验性追加tag
   */
  protected function method_components($text){
    preg_match_all("/".$this->ld."components:(\S+)([\s\S]+?)".$this->rd."/S",$text,$match);
    $componTX=array();
    foreach($match[0] as $key=>$original){
      $filename=$match[1][$key];
      $components=isset($componTX[$filename])?$componTX[$filename]:"";
      if($components===""){
	$target=$this->td."components/".$filename.".tpl";
	if(is_file($target)){
	  $components=file_get_contents($target);
	  $componTX[$filename]=$components;
	}
      }
      if($components!==""){
	$param=parse_ini_string(str_replace(" "," \n",trim($match[2][$key])));
	preg_match_all("/".$this->ld."[\s\S]*?".$this->rd."/S",$components,$subMatch);
	foreach($subMatch[0] as $m){
	  $key=str_replace('<{$',"",str_replace("}>","",$m));
	  $value="";
	  if(isset($param[$key])){
	    $value=$param[$key];
	  }
	  $components=str_replace($m,$value,$components);
	}
      }
      $text=str_replace($original,$components,$text);
    }
    return $text;
  }

  protected function extension($text){
    foreach($this->plugin as $tag=>$_call){
      if(is_callable($_call)){
	preg_match_all('/'.$this->ld."(".$tag."((?!".$this->rd.").*?))".$this->rd.'/S',$text,$matches);
	foreach($matches[0] as $key=>$pattern){
	  $replacements=call_user_func_array($_call,array($matches[2][$key]));
	  $text=str_replace($matches[1][$key],$replacements,$text);
	}
      }
    }
    return $text;
  }

  public function setPlugin($plugin){
    $this->plugin=$plugin;
  }

}