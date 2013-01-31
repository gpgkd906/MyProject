<?php
/**
 * 
 */
class prime {
  public function mul($arr){
    if(empty($arr)){
      return 0;
    }
    $mul=1;
    foreach($arr as $v){
      $mul*=$v;
    }
    return $mul;
  }
  
  public function childOf($num){
    if(!is_numeric($num)){
      return false;
    }
    $_sqrt=intval(sqrt($num));
    $_arr=array();
    for($div=2;$div<=$_sqrt;$div++){
      if($num%$div===0){
	$_arr[]=$div;
	$num=$num/$div;
      }
    }
    if($num!==1){
      $_arr[]=$num;
    }
    return $_arr;
  }
  
  public function mulRate($arr){
    $_arr=array();
    while($_cur=array_shift($arr)){
      $m1=$_cur;
      foreach($arr as $item){
	$m1*=$item;
	$_arr[]=$_cur*$item;
	$_arr[]=$m1;
      }
    }
    $_arr=array_unique($_arr);
    return $_arr;
  }

  //ここの計算は問題あり，要チェック
  public function orSearch($arr1,$arr2){
    if(!is_array($arr1)){
      $arr1=array($arr1);
    }
    $res=$arr1;
    foreach($arr1 as $v){
      $res=array_merge($res,$this->makeRateOne($v,$arr2));
    }
    $res=array_unique($res);
    return $res;
  }

  public function makeRateOne($num,$arr){
    $_arr=array_diff($arr,array($num));
    $_arr=array_merge($_arr,$this->mulRate($_arr));
    $res=array();
    foreach($_arr as $v){
      $res[]=$v*$num;
    }
    return $res;
  }

  public function getNextPrime($start=1){
    
    if($start==0){
      return 2;
    }
    while($start++ > 0){
      $range=intval(sqrt($start))+1;
      $dived=false;
      for($i=2;$i<$range;$i++){
	if($start%$i===0){
	  $dived=true;
	  break;
	}
      }
      if($dived===false){
	return $start;
      }
    }
  }


}