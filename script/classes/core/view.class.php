<?php 
/**
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
require_once "chemplate.class.php";

class view extends chemplate {
  private $theme="";
  private $template_file;
  private $ori_template_dir;
  private $ori_template_chen_dir;
  private $ori_cache_dir;

  public function init(){
    $this->ori_template_dir=$this->_template_dir;
    $this->ori_template_chen_dir=$this->_template_chen_dir;
    $this->ori_cache_dir=$this->_cache_dir;
  }

  public function setTemplate($template){
    $this->template_file=$template;
  }

  public function getTemplate(){
    return $this->template_file;
  }

  public function useTheme($theme){
    if(empty($theme)){
      return false;
    }
    $tdir=$this->ori_template_dir.$theme."/";
    $tcdir=$this->ori_template_chen_dir.$theme."/";
    $cdir=$this->ori_cache_dir.$theme."/";
    $this->theme=$theme;
    if(!is_dir($tdir)){
      throw new exception("not found Theme");
    }
    if(!is_dir($tcdir)){
      mkdir($tcdir,null,true);
      clearstatcache();
    }
    if(!is_dir($cdir)){
      mkdir($cdir,null,true);
      clearstatcache();
    }
    $this->_template_dir=$tdir;
    $this->_template_chen_dir=$tcdir;
    $this->_cache_dir=$cdir;
  }

  public function whichTheme(){
    return $this->theme;
  }

  public function display(){
    if(isset($this->template_file)){
      parent::display($this->template_file);
    }
  }
  
}