<?php

namespace Simple;

use Simple\Frame\Create;
use Simple\Frame\Env;
use Simple\Frame\Route;


class Start
{
  //运行框架
  public static function run()
  {
    Start::debug(); //设置调式模式
    //设置用户自定义的错误处理程序
    register_shutdown_function('Simple\Frame\MyError::error_last_log');
    set_error_handler("Simple\Frame\MyError::error_handler");
    Env::load();  //加载env文件
    self::conf(); //加载配置文件
    route::index();//启动路由
  }

  //创建App下的模块
  public static function createApp($module)
  {
    create::createTemplate($module);
  }

  //调试模式
  public static function debug()
  {
    if (env('SIMPLE_DEBUG')) {
      ini_set('display_errors', 1);
      error_reporting(E_ALL);
    } else {
      ini_set('display_errors', 0);
    }
  }

  //加载配置文件
  public static function conf()
  {
    $conf = '..' . DIRECTORY_SEPARATOR . 'simple' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR;
    $handler = opendir($conf);
    while (($filename = readdir($handler)) !== false) {
      //略过linux目录的名字为'.'和‘..'的文件
      if ($filename != "." && $filename != "..") {
        //加载配置文件
        include_once($conf . $filename);
      }
    }
    closedir($handler);
  }
}
