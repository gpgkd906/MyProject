<?php
/**
 * 对GD库的简单封装
 * 以便于开发者用最小的成本实现基本的图像操作
 *   Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 *   author: chenhan,gpgkd906@gmail.com
 *   website: http://dev.gpgkd906.com/MyProject/
 */

class image {

  public function resize($file,$width="510",$height="340",$color=null){
    $fullSize = getimagesize($file);
    switch($fullSize[2]){
    case 1:
      $fullImage = imagecreatefromgif($file);
      break;
    case 2:
      $fullImage = imagecreatefromjpeg($file);
      break;
    case 3:
    default:
      $fullImage = imagecreatefrompng($file);
      break;
    }
    $resRadio=$fullSize[0]/$fullSize[1];
    $dstRadio=$width/$height;
    $scale = $resRadio>$dstRadio ? ($fullSize[0]/$width):($fullSize[1]/$height);
    $dstX=0;
    $dstY=0;

    if($color!==null){
      $tnImage = imagecreatetruecolor($width, $height);
      $color=imagecolorallocate($tnImage,$color[0],$color[1],$color[2]);
      imagefill($tnImage,0,0,$color);
      $type = $resRadio>$dstRadio ? 1 : 2;
      if($type===1){
	$dstX = 0;
	$dstY = intval(($height-($fullSize[1]/$scale))/2);
      }elseif($type==2){
	$dstX = intval(($width-($fullSize[0]/$scale))/2);    
	$dstY = 0;
      }
    }else{
      $tnImage = imagecreatetruecolor($fullSize[0]/$scale,$fullSize[1]/$scale);      
    }
    
    imagecopyresampled($tnImage,$fullImage,$dstX,$dstY,0,0,$fullSize[0]/$scale,$fullSize[1]/$scale,$fullSize[0],$fullSize[1]);
    imagejpeg($tnImage, $file);
    
    imagedestroy($fullImage);
    imagedestroy($tnImage);
  }

  
}