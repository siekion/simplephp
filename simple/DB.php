<?php

namespace Simple;


use Simple\Core\Model;

class DB extends Model
{
  private $ins;
  private $pdo;    //用于存放PDO的一个对象
  private $table; //表名


  /**
   *功能：设置表名
   */
  public static function table($table)
  {
//    $conf = include('../config/databases.php');
//    $pdo = self::getInstance($conf['mysql']);
//    p($pdo);
//    exit;
  }


}
