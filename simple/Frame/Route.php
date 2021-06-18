<?php

namespace Simple\Frame;

use App\Admin\Controller\IndexController;
use ReflectionMethod;

class Route extends View
{
  public static $model; //模块
  public static $controller; //控制器
  public static $action; //方法

  public static function index()
  {
    $url_string = $_SERVER['REQUEST_URI'];
    if (isset($url_string) and $url_string !== '/') {
      $request_url = str_replace(array('.html', '.php', '.asp', '.htm', '.js', 'http://', 'https://', $_SERVER['SERVER_NAME']), '', $url_string);
      if (strpos($request_url, '?') !== false) {
        $str = explode('?', trim($request_url, '?'));
        $url = explode('/', substr($str[0], 1));
      } else {
        $url = explode('/', trim($request_url, '/'));
      }
      if (count($url) == 1 and !empty($url[0])) {
        self::$model = ucfirst(env('DEFAULT_MODEL'));
        self::$controller = ucfirst(env('DEFAULT_CONTROLLER'));
        self::$action = $url[0];
      } elseif (count($url) == 2 and !empty($url[1])) {
        self::$model = $url[0];
        self::$controller = $url[1];
      } else {
        self::url($url);
      }
    } else {
      self::url();
    }
    self::run();
  }

  public static function url($url = '')
  {
    if (empty($url)) {
      self::$model = ucfirst(env('DEFAULT_MODEL'));
      self::$controller = ucfirst(env('DEFAULT_CONTROLLER'));
      self::$action = env('DEFAULT_ACTION');
    } else {
      if (!empty($url[0])) {
        self::$model = ucfirst($url[0]);
      } else {
        self::$model = ucfirst(env('DEFAULT_MODEL'));
      }

      if (!empty($url[1])) {
        self::$controller = ucfirst($url[1]);
      } else {
        self::$controller = ucfirst(env('DEFAULT_CONTROLLER'));
      }

      if (!empty($url[2])) {
        self::$action = $url[2];
      } else {
        self::$action = env('DEFAULT_ACTION');
      }

      if (count($url) > 2) {
        $i = 3;
        while ($i < count($url)) {
          if (isset($url[$i + 1])) {
            $_GET[$url[$i]] = $url[$i + 1];
          }
          $i = $i + 2;
        }
      }
    }
  }

  public static function run()
  {
    $file = '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . self::$model . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . self::$controller . 'Controller.php';
    if (file_exists($file)) {
      $controller_path = 'App\\' . self::$model . '\Controller\\' . self::$controller . 'Controller';
      $model = new $controller_path();
      if (empty($model)) {
        echo '【' . self::$model . '】模块不存在!';
      } else {
        if (!method_exists($model, self::$action)) {
          View::display(self::$action);
        } else {
          define('MODEL', self::$model);
          define('CONTROLLER', self::$controller);
          define('ACTION', self::$action);
          if (method_exists($model, 'init')) {
            $model->init();
          }
          $action = self::$action;
          $model->$action();
        }
      }
    } else {
      echo '404文件不存在';
    }
  }

}
