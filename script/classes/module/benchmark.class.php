<?php
/**
 *
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */
class benchmark {
  private $point;
  private $key=0;
  private $option=array(
			"断点",
			"总时间",
			"分子时间",
			"内存消耗"
			);

  public function __construct(){
    $this->set("benchmark start");
  }

  public function set($name=null){
    $key=$this->key=$this->key+1;
    if(!$name){
      $this->point[$key]["key"]="Breakpoint_".$key;
    }else{
      $this->point[$key]["key"]=$name;
    }
    $this->point[$key]["time"]=microtime(true);
    if($key === 1){
      $this->point[1]["totaltime"]=$this->point[1]["showtime"]=sprintf('%0.5f',0);
    }else{
      $this->point[$key]["showtime"]=sprintf('%0.5f',$this->point[$key]["time"]-$this->point[$key-1]["time"]);
      $this->point[$key]["totaltime"]=sprintf('%0.5f',$this->point[$key]["time"]-$this->point[1]["time"]);
    }
    $this->point[$key]["usage"]=sprintf( '%01.2f MB',memory_get_usage()/1048576 );
  }

  public function display($queryInfo=null){
    echo "<div>ページ生成ベンチマークツール<br/>";
    echo "<table border='1'>";
    echo "<tr>";
    /* echo "<th>断点</th>"; */
    /* echo "<th>总时间</th>"; */
    /* echo "<th>分子时间</th>"; */
    /* echo "<th>内存消耗</th>"; */
    echo "<th>ブレークポイント</th>";
    echo "<th>総時間</th>";
    echo "<th>単位時間</th>";
    echo "<th>メモリ消費</th>";
    echo "</tr>";
    foreach($this->point as $key=>$value){
      echo "<tr>";
      echo "<td>".$value["key"]."</td>";
      echo "<td>".$value["totaltime"]."</td>";
      echo "<td>".$value["showtime"]."</td>";
      echo "<td>".$value["usage"]."</td>";
      echo "</tr>";
    }
    echo "</table></div>";
  }
}