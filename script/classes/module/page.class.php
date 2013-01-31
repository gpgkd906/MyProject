<?php
/**
 * 分页库
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
class page {
  private $max_pages=0;
  private $request_page=1;
  private $pages_length=5;
  private $pages=null;
  private $proto_url;
  private $replace='<{page}>';
  private $split='&nbsp;';
  private $frontStyle=null;
  private $backStyle=null;
  private $format=null;
  private $formatHandler;
  private $autoQuery=true;
  private $query=array();
  private $unQuery=array();

  public function __construct($request=1){
    $this->request_page=intval($request);
    $this->formatHandler=array($this,"defaultFormat");
  }

  final public function max($max){
    $this->max_pages=intval($max);
    return $this;
  }
  
  /**
   * 设置或取得当前页数
   */
  final public function request($page=false){
    if($page){
      $this->request_page=intval($page);
    }else{
      return $this->request_page;
    }
    return $this;
  }

  /**
   * 设置分页长度
   */
  final public function length($len){
    $this->pages_length=intval($len);
    /* $this->pages_length=$len%2?$len:$len+1; */
    return $this;
  }

  /**
   * @计算移除该方法
   */
  final public function wrap($front=null,$back=null){
    if($front)$this->frontStyle=$front;
    if($back)$this->backStyle=$back;
    return $this;
  }

  /**
   * 设置分页链接，默认参数下为当前页面，并自行生成queryString
   */
  final public function url($url="",$param=null,$split=null){
    if($param)$this->replace=$param;
    if($split)$this->split=$split;
    if($this->autoQuery){
      foreach($_GET as $key=>$value){
	$this->query[$key]=$value;
      }
      $this->query["page"]=$this->replace;
      foreach($this->unQuery as $rm){
	unset($this->query[$rm]);
      }
      if(strpos($url,"?")!==false){
	$tempUrl=explode("?",$url);
	$url=$tempUrl[0];
      }
      $url=$url."?".http_build_query($query,"","&amp;");
    }
    $this->proto_url=$url;
    return $this;
  }

  /**
   * 关闭queryString自动生成
   */
  final public function disableQuery(){
    $this->autoQuery=false;
  }

  /**
   * 移除queryString变量
   */
  final public function removeQuery($key){
    $this->unQuery[]=$key;
  }

  /**
   * 增加queryString变量
   */
  final public function addQuery($key,$value){
    $this->query[$key]=$value;
  }

  /**
   * 分页计算
   */
  final public function flip(){
    $data["request"]=$this->request_page;
    if($this->max_pages<=$this->pages_length){
      //如果要求页数全部在可见范围之内。
      $data["first"]=1;
      $data["last"]=$this->max_pages;
      $data["pre"]=false;
      $data["next"]=false;
    }else{
      //要求页数总数超过可见范围，即存在pre（上一页）或者next（下一页），或者两者兼有
      $num=intval(ceil(($this->pages_length-1)/2));
      $first_temp=$this->request_page-$num;
      $last_temp=$this->request_page+$num;
      //可见范围内最后一页
      if($last_temp<$this->pages_length){
	$data["last"]=$this->pages_length;
	$data["next"]=$this->pages_length+1;
      }elseif($last_temp<$this->max_pages){
	$data["last"]=$last_temp;
	$data["next"]=$last_temp+1;	
      }else{
	$data["last"]=$this->max_pages;
	$data["next"]=false;
      }
      //可见范围内最初一页
      if($data["last"]===$this->max_pages){
	$data["first"]=$data["last"]-$num*2;
	$data["pre"]=$data["last"]-$this->pages_length;
      }elseif($first_temp>1){
	$data["first"]=$first_temp;
	$data["pre"]=$first_temp-1;
      }else{
	$data["first"]=1;
	$data["pre"]=false;
      }
    }
    $this->pages=$data;
    return $this;
  }

  //可通过回调函数自定义渲染方式
  private function format(){
    if(!$this->pages)$this->flip();
    //返回最终分页代码
    $this->format=call_user_func_array($this->formatHandler,array($this->pages));
  }
  
  public function setFormatHandler($handler){
    $this->formatHandler=$handler;
  }

  private function defaultFormat($pages){
    $flip=array();
    if($pages["request"]>1)
      {
	$flip[]=$this->frontStyle."<a href='".str_replace($this->replace,($pages["request"]-1),$this->proto_url)."' class=\"prevnext disablelink\">&#171; 前</a>".$this->backStyle;
      }
    for($i=$pages["first"];$i<=$pages["last"];$i++)
      {
	if($i===$pages["request"])
	  {
	    $flip[]=$this->frontStyle."<span class=\"currentpage\">".$i."</span>".$this->backStyle;
	  }
	else
	  {
	    $flip[]=$this->frontStyle."<a href='".str_replace($this->replace,$i,$this->proto_url)."'>$i</a>".$this->backStyle;
	  }
      }
    if($pages["request"]<$pages['last'])
      {
	$flip[]=$this->frontStyle."<a href='".str_replace($this->replace,($pages["request"]+1),$this->proto_url)."' class=\"prevnext\">次 &#187;</a>".$this->backStyle;
      }
    return join($this->split,$flip);
  }

  /**
   *　返回FLIP数据，可用于模板。
   */
  public function get(){
    if(!$this->pages)$this->flip();
    return $this->pages;
  }
  
  /**
   * 直接输出通过回调函数渲染的分页样式
   */
  public function show(){
    if(!$this->proto_url)$this->url();
    if(!$this->format)$this->format();
    echo $this->format;
  }
  
  /**
   * 获取回调函数渲染的分页样式而不输出
   */
  public function fetch(){
    if(!$this->format)$this->format();
    return $this->format;
  }
  
  /**
   * 获取当前第一页
   */
  public function first(){
    return $this->pages['first'];
  }
  
  /**
   * 获取当前最后一页
   */
  public function last(){
    return $this->pages['last'];
  }
}