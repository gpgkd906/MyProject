<?php
/* 軽量のPHPフレームワークMy project */
/* コントローラ始動(errorハンドリング開始) */
require_once My::classes."controller.class.php";
$chen=controller::getSingletonInstance(); 
/* 前置き処理(config読み込み,基本モジュール読み込む) */
$chen->getInstance("booter")->bootStrap();
/* ユーザリクエストをアプリにマップする */
if( !$chen->application(My::$app2url))
  {
    if(My::$config["debug"]){
      $chen->getInstance("logger")->display($chen->request);
    }
    $pageNotFound="404.tpl";
    header("HTTP/1.0 404 Not Found");
    $chen->view->setTemplate($pageNotFound);
    $chen->view->display();
    //ここで処理が終了する
  }
else
  {
    /* データベース */
    $chen->getInstance("booter")->MyDO();
    
    /*************************************************************** 
     * 処理プロセサ－を呼び出す，グロバル環境から脱出。
     ****************************************************************/
    $chen->process();
  }
//============================================================//
//はい，処理が終わりました，お疲れ \(^_^)/ \(^_^)/ \(^_^)/ \(^_^)/ \(^_^)/
//============================================================//
