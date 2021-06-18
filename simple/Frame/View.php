<?php

namespace Simple\Frame;

use LogicException;

class View
{

  /**
   * 模板显示和跳转页面
   * @param null $page
   */
  public static function display($page)
  {
    $file = '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . Route::$model . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . $page . '.html';
    if (is_file($file)) {
      include($file);
    } else {
      $url_string = $_SERVER['REQUEST_URI'];
      $url = explode('/', trim($url_string, '/'));
      if (count($url) == 1) {
        echo '请输入控制器和方法!';
      } elseif (count($url) == 2) {
        echo '请输入方法!';
      }
    }
  }
}

