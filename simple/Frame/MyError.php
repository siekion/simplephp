<?php

namespace Simple\Frame;
class MyError
{
  public static function error_handler($errno, $errstr, $errfile, $errline)
  {
    if (env('SIMPLE_DEBUG')) {
      echo '<br/>';
      echo "<b>【USER】错误编号：</b>" . $errno . "<br/>";
      echo "<b>【USER】错误提示：</b>" . $errstr . "<br/>";
      echo "<b>【USER】错误位置：</b>" . $errfile . "（" . $errline . "）<br/>";
      die();
    } else {
      echo '404';
    }
  }

  public static function error_try($e, $sql = '')
  {
    if (env('SIMPLE_DEBUG')) {
      echo '<br/>';
      echo "<b>【TRY】错误编号：</b>" . $e->getCode() . "<br/>";
      echo "<b>【TRY】错误提示：</b>" . $e->getMessage() . "<br/>";
      for ($i = 1; $i < count($e->getTrace()); $i++) {
        echo "<b>【TRY】错误位置{$i}：</b>" . $e->getTrace()[$i]['file'] . '(' . $e->getTrace()[$i]['line'] . ')' . "<br/>";
      }
      if (!empty($sql)) {
        echo "<b>【TRY】错误SQL：</b>" . $sql . "<br/>";
      }
    } else {
      echo '404';
    }
  }

  public static function error_last_log()
  {
    if (env('SIMPLE_DEBUG')) {
      $e = error_get_last();
      if (!empty($e)) {
        $type = '';
        switch ($e['type']) {
          case 1:
            //E_ERROR
            $type = '致命的运行时错误';
            break;
          case 2:
            //E_WARNING
            $type = '运行时警告 (非致命错误)';
            break;
          case 4:
            //E_PARSE
            $type = '编译时语法解析错误';
            break;
          case 8:
            //E_NOTICE
            $type = '运行时通知';
            break;
          case 16:
            //E_CORE_ERROR
            $type = '在 PHP 初始化启动过程中发生的致命错误';
            break;
          case 32:
            //E_CORE_WARNING
            $type = 'PHP 初始化启动过程中发生的警告 (非致命错误)';
            break;
          case 64:
            //E_COMPILE_ERROR
            $type = '致命编译时错误';
            break;
          case 128:
            //E_COMPILE_WARNING
            $type = '编译时警告 (非致命错误)';
            break;
          case 256:
            //E_USER_ERROR
            $type = '用户产生的错误信息';
            break;
          case 512:
            //E_USER_WARNING
            $type = '用户产生的警告信息';
            break;
          case 1024:
            //E_USER_NOTICE
            $type = '用户产生的通知信息';
            break;
          case 2048:
            //E_STRICT
            $type = '启用 PHP 对代码的修改建议，以确保代码具有最佳的互操作性和向前兼容性。';
            break;
          case 4096:
            //E_RECOVERABLE_ERROR
            $type = '可被捕捉的致命错误';
            break;
          case 8192:
            //E_DEPRECATED
            $type = '运行时通知';
            break;
          case 16384:
            //E_USER_DEPRECATED
            $type = '用户产生的警告信息';
            break;
        }
        echo '<br/>';
        echo "<b>【LAST】错误编号：</b>" . $e['type'] . "<br/>";
        echo '<b>【LAST】错误类型：</b>' . $type . '<br/>';
        echo "<b>【LAST】错误位置：</b>" . $e['file'] . "（" . $e['line'] . "）<br/>";
        echo "<b>【LAST】错误提示：</b>" . $e['message'] . "<br/>";
      }
    }
  }

}
