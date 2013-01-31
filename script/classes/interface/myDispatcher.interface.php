<?php
/**
 * Dispatcher的interface
 */

interface dispatcherInterface {

  public static function isValidReq($req);

  public static function map($map,$req,$logic,$tpl);

  public static function build($app);

  public static function link($controll,$request,$param);

}