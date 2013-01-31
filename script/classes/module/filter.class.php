<?php
/**
 *   过滤器
 *   之前版本均以正则表达式实现，但从本版本开始，为了获得更好的性能和更好的安全性，改为封装PHP5的原生过滤器。
 *   关于PHP5原生过滤器，参考这里:http://www.php.net/manual/en/filter.filters.validate.php
 *   [少量扩展部分还是以正则表达来实现。]
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */


class filter {
  protected static $option=array(
			    "validate_bool"=>258,
			    "validate_mail"=>274,
			    "validate_float"=>259,
			    "validate_int"=>257,
			    "validate_ip"=>275,
			    "validate_url"=>273,
			    "validate_regexp"=>272,
			    "sanitize_mail"=>517,
			    "sanitize_encode"=>514,
			    "sanitize_magic_quotes"=>521,
			    "sanitize_float"=>520,
			    "sanitize_int"=>519,
			    "sanitize_html"=>515,
			    "sanitize_string"=>513,
			    "sanitize_url"=>518,
			    "flag_strip_low"=>4,
			    "flag_strip_hign"=>8,
			    "flag_fraction"=>4096,
			    "flag_thousand"=>8192,
			    "flag_scientific"=>16384,
			    "flag_quotes"=>128,
			    "flag_encode_low"=>16,
			    "flag_encode_hign"=>32,
			    "flag_amp"=>64,
			    "flag_octal"=>1,
			    "flag_hex"=>2,
			    "flag_IPv4"=>1048576,
			    "flag_IPv6"=>2097152,
			    "flag_no_private"=>8388608,
			    "flag_no_res"=>4194304,
			    "flag_host"=>131072,
			    "flag_path"=>262144,
			    "flag_required"=>524288,
			    "flag_return_null"=>134217728,
			    "flag_return_array"=>60108864,
			    "flag_require_array"=>16777216,
			  );

  protected static $hint=array(
			"validate_bool"=>"逻辑值验证",
			"validate_mail"=>"邮件格式验证",
			"validate_float"=>"浮点数验证",
			"validate_int"=>"整数验证",
			"validate_ip"=>"ip验证",
			"validate_url"=>"url验证",
			"validate_regexp"=>"正则式验证",
			"sanitize_mail"=>"电子邮件内容过滤",
			"sanitize_encode"=>"urlencode过滤",
			"sanitize_magic_quotes"=>"addslashes()过滤",
			"sanitize_float"=>"浮点数过滤",
			"sanitize_int"=>"整数过滤",
			"sanitize_html"=>"html过滤",
			"sanitize_string"=>"字符串过滤",
			"sanitize_url"=>"url过滤",
			"flag_strip_low"=>"去除ASCII值在32以下的字符",
			"flag_strip_hign"=>"去除ASCII值在127以上的字符",
			"flag_fraction"=>"允许数值检查中出现小数点",
			"flag_thousand"=>"允许数值使用3位记法,ext:123,456,789",
			"flag_scientific"=>"允许科学记述法",
			"flag_quotes"=>"不转义引号(无论单双)",
			"flag_encode_low"=>"转义ASCII值在32以下的字符",
			"flag_encode_hign"=>"转义ASCII值在127以上的字符",
			"flag_amp"=>"转义&",
			"flag_octal"=>"只允许8进制",
			"flag_hex"=>"只允许16进制",
			"flag_IPv4"=>"只允许IPv4",
			"flag_IPv6"=>"只允许IPv6",
			"flag_no_private"=>"不允许私有IP",
			"flag_no_res"=>"不允许保留IP",
			"flag_host"=>"要求必须包含主机",
			"flag_path"=>"要求包含路径",
			"flag_required"=>"要求url包含query文",
			"flag_return_null"=>"返回值由false改为null",
			"flag_return_array"=>"返回值整形为数组",
			"flag_require_array"=>"要求输入值必须为数组",
			);
  protected static $debug=array();

  public function filter_var($data,$option=null,$flag=null){
    $option= $option===null?self::$option["validate_html"]:self::$option[$option];
    $flag= $flag===null?null:self$option[$flag];
    return filter_var($data,$option,$flag);
  }
  

  public function get_hint($type=null){
    echo "<pre>";
    var_dump($this->hint);
  }
  
  

  public function filter_array($data,$type){
    if(array_intersect_key($data,$type)!==$data)
      {
	$this->debug["filter_array"]="需要校验的数据与提供的校验类型并不完全一致";
	return false;
      }
    if(array_intersect_key($type,$this->option)!==$type)
      {
	$this->debug["filter_array"]="提供的校验类型不包含在任何已知类型之中";
	return false;
      }
    $args=array();
    foreach($type as $name=>$options){
      if(is_array($options))
	{
	  foreach($options as $key=>$value)
	    {
	      if(is_array($value))
		{
		  $args[$name][$key]=$value;
		}
	      else
		{
		  $args[$name][$key]=$this->option[$value];
		}
	    }
	}
      else
	{
	  $args[$name]=$this->option[$options];
	}
    }
    $this->debug["filter_args"]=$args;
    //选项整形完毕以后进行过滤并输出
    return filter_var_array($data,$args);
  }
}