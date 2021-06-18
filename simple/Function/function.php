<?php

//功能：打印数据
function p($data)
{
  if (is_bool($data)) {
    var_dump($data);
  } elseif (is_null($data)) {
    var_dump(NULL);
  } else {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
  }
}

//功能：删除缓存
function cache_delete()
{
  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
  header("Cache-Control: no-cache, must-revalidate");
  header("Pragma: no-cache");
}

/**
 * 功能：返回不同格式的年月日时分秒
 * @param null $format
 * @return false|string
 */
function datetime($format = null)
{
  if ($format == '-') {
    return date('Y-m-d H:i:s', time());
  } elseif ($format == '/') {
    return date('Y' . "/" . 'm' . '/' . 'd H:i:s', time());
  } elseif ($format == '.') {
    return date('Y.m.d H:i:s', time());
  } else {
    return date('Y年m月d日H时i分s秒', time());
  }
}


/**
 * 功能：返回两个时间的时间差
 * @param $start (2019-09-24 00:00:00)
 * @param $end (2020-11-30 01:01:01)
 */
function datetime_difference($start, $end, $returnType = '')
{
  $startTime = strtotime($start); //开始时间
  $endTime = strtotime($end); //结束时间

  $diffTime = $endTime - $startTime; //相差的时间

  $year = floor($diffTime / 3600 / 24 / 365); //年

  $temp = $diffTime - $year * 365 * 24 * 3600;
  $month = floor($temp / 3600 / 24 / 30); //月

  $temp = $temp - $month * 30 * 24 * 3600;
  $day = floor($temp / 3600 / 24); //日

  $temp = $temp - $day * 3600 * 24;
  $hour = floor($temp / 3600); //时

  $temp = $temp - $hour * 3600;
  $minute = floor($temp / 60); //分

  $secondl = $temp - $minute * 60; //秒

  return '相差' . $year . '年' . $month . '月' . $day . '日' . $hour . '时' . $minute . '分' . $secondl . '秒';
}

/**
 * 功能：返回微妙数
 * @param bool $state
 * @return float|string
 */
function datetime_micro($state = true)
{
  return microtime($state);
}


/**
 * 功能：返回当前0点或者24点的时间
 * @param $value
 * @return false|string
 */
function datetime_today($value)
{
  if ($value == 0) {
    return date('Y-m-d 00:00:00', time());
  } else {
    return date('Y-m-d 23:59:59', time());
  }
}

/*
* 功能：返回当前时间以后的数据
* @param $value //值
* @param $type //添加类型
*/
function datetime_add($value, $type)
{

  if ($type == 'Y') {
    //返回*年后的时间
    return date('Y-m-d H:i:s', strtotime("+" . $value . " year"));
  } elseif ($type == 'M') {
    //返回*月后的时间
    return date('Y-m-d H:i:s', strtotime("+" . $value . " month"));
  } elseif ($type == 'D') {
    //返回*天后的时间
    return date('Y-m-d H:i:s', strtotime("+" . $value . " day"));
  } elseif ($type == 'H') {
    //返回*小时后的时间
    return date('Y-m-d H:i:s', strtotime("+" . $value . " hours"));
  } elseif ($type == 'I') {
    //返回*分钟后的时间
    return date('Y-m-d H:i:s', strtotime("+" . $value . " minutes"));
  } elseif ($type == 'S') {
    //返回*秒后的时间
    return date('Y-m-d H:i:s', strtotime("+" . $value . " seconds"));
  }


// #1
//    echo strtotime("now");  // 获取当前时间戳
//    echo date('Y-m-d H:i:s', strtotime("now"));
// #2
//    echo strtotime("2015-06-11 10:11:00");  // 获取指定的时间戳
//    echo date('Y-m-d H:i:s', strtotime("2015-06-11 10:11:00"));
// #3
//    echo strtotime("3 October 2005");   // 获取指定的时间戳[等同于strtotime("2005-10-03")]
//    echo date('Y-m-d H:i:s', strtotime("3 October 2005"));
// #4
//    echo strtotime("+5 hours"); // 当前时间加五个小时 [对比#1]
//    echo date('Y-m-d H:i:s', strtotime("+5 hours"));
// #5
//    echo strtotime("+1 day");   // 当前时间加1天 [对比#1]
//    echo date('Y-m-d H:i:s', strtotime("+1 day"));
// #6
//    echo strtotime("+2 days");  // 当前时间加多天 名词变复数 [对比#1]
//    echo date('Y-m-d H:i:s', strtotime("+2 days"));
// #7
//    echo strtotime("+1 week 3 days 7 hours 5 seconds"); // 当前时间加 1周 3天 7小时 5秒 [对比#1]
//    echo date('Y-m-d H:i:s', strtotime("+1 week 3 days 7 hours 5 seconds"));
// #8
//    echo strtotime("next Monday");  // 当前时间下一个周一
//    echo date('Y-m-d H:i:s', strtotime("next Monday"));
// #9
//    echo strtotime("last Sunday");  // 当前时间前一个周日
//    echo date('Y-m-d H:i:s', strtotime("last Sunday"));
// #10
//    echo strtotime("-1 day", strtotime("2018-07-01 10:11:00"));  // 给定时间 减去一天
//    echo date('Y-m-d H:i:s', strtotime("-1 day", strtotime("2018-07-01 10:11:00")));
}


/**
 * 功能：加密函数
 * @param $string
 * @param string $key
 * @return string|string[]
 */
function recode($string, $key = 'SIMPLE')
{
  if (empty($string)) {
    return '';
  } else {
    $key = md5($key);
    $key_length = strlen($key);
    $string = substr(md5($string . $key), 0, 8) . $string;
    $string_length = strlen($string);
    $rndkey = $box = array();
    $result = '';
    for ($i = 0; $i <= 255; $i++) {
      $rndkey[$i] = ord($key[$i % $key_length]);
      $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
      $j = ($j + $box[$i] + $rndkey[$i]) % 256;
      $tmp = $box[$i];
      $box[$i] = $box[$j];
      $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
      $a = ($a + 1) % 256;
      $j = ($j + $box[$a]) % 256;
      $tmp = $box[$a];
      $box[$a] = $box[$j];
      $box[$j] = $tmp;
      $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    return str_replace('=', '', base64_encode($result));
  }
}

/**
 * 功能：解密函数
 * @param $string
 * @param string $key
 * @return false|string
 */
function decode($string, $key = 'SIMPLE')
{
  if (empty($string)) {
    return '';
  } else {
    $key = md5($key);
    $key_length = strlen($key);
    $string = base64_decode($string);
    $string_length = strlen($string);
    $rndkey = $box = array();
    $result = '';
    for ($i = 0; $i <= 255; $i++) {
      $rndkey[$i] = ord($key[$i % $key_length]);
      $box[$i] = $i;
    }
    for ($j = $i = 0; $i < 256; $i++) {
      $j = ($j + $box[$i] + $rndkey[$i]) % 256;
      $tmp = $box[$i];
      $box[$i] = $box[$j];
      $box[$j] = $tmp;
    }
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
      $a = ($a + 1) % 256;
      $j = ($j + $box[$a]) % 256;
      $tmp = $box[$a];
      $box[$a] = $box[$j];
      $box[$j] = $tmp;
      $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
      return substr($result, 8);
    } else {
      return '';
    }
  }
}

/**
 * 功能：创建一个调试的文本文件
 * @param $fileName //文件名
 * @param $content //写入内容
 */
function debug($content)
{
  $title = '【' . date("H:i:s") . '】————————————————————————————————————————————————————————————' . "\r\n";
  $fileName = './log/debug/' . date("Y-m-d") . '.txt';
  if (is_array($content)) {
    $data = 'array(' . "\r\n";
    foreach ($content as $k => $v) {
      $data .= '[' . $k . ']=>' . $v . "\r\n";
    }
    $data = $data . ')';
    $txt = $title . $data . "\r\n\r\n";
  } else {
    $txt = $title . $content . "\r\n\r\n";
  }

  file_put_contents($fileName, $txt, FILE_APPEND);
}

/**
 * 功能：创建sql语句日志
 * @param $fileName //文件名
 * @param $content //写入内容
 */
function sqlLog($content)
{
  $title = '【' . date("H:i:s") . '】————————————————————————————————————————————————————————————' . "\r\n";
  $fileName = './log/sql/' . date("Y-m-d") . '.txt';
  if (is_array($content)) {
    $data = 'array(' . "\r\n";
    foreach ($content as $k => $v) {
      $data .= '[' . $k . ']=>' . $v . "\r\n";
    }
    $data = $data . ')';
    $txt = $title . $data . "\r\n\r\n";
  } else {
    $txt = $title . $content . "\r\n\r\n";
  }

  file_put_contents($fileName, $txt, FILE_APPEND);
}

/**
 * 模板url跳转
 * @param $url
 */
function u($url)
{
  $url = '/' . MODEL . '/' . $url;
  return $url;
}

/**
 * 模板赋值
 * @param $value
 * @param bool $type
 * @return bool|false|mixed|string|string[]|void
 */
function v($value, $type = false)
{
  $value_array = explode('.', $value);
  $value_count = count($value_array);
  if ($value_count == 0) {
    return false;
  } elseif ($value_count == 1) {
    if (!empty($GLOBALS[$value]) or $GLOBALS[$value] == 0) {
      $returnValue = $GLOBALS[$value];
    } else {
      return false;
    }
  } elseif ($value_count == 2) {
    $one = $value_array[0];
    $two = $value_array[1];
    $returnValue = $GLOBALS[$one][$two];
  } elseif ($value_count == 3) {
    $one = $value_array[0];
    $two = $value_array[1];
    $three = $value_array[2];
    $returnValue = $GLOBALS[$one][$two][$three];
  }
  switch ($type) {
    case 'recode':
      return recode($returnValue);
      break;
    case 'decode':
      return decode($returnValue);
      break;
    case 'images':
      return substr($returnValue, 1);
      break;
    case 'html_recode':
      return htmlspecialchars($returnValue);
    case 'html_decode':
      return htmlspecialchars_decode($returnValue);
      break;
    default:
      return $returnValue;
      break;
  }
}

/**
 * 在模板中引入页面
 * @param $page
 * @return string
 */
function i($page)
{
  include('app/' . MODEL . '/view/' . $page . '.php');
}

/**
 * 获取环境变量值
 * @access public
 * @param string $name 环境变量名（支持二级 . 号分割）
 * @param string $default 默认值
 * @return mixed
 */
function env($name, $value = '')
{
  if ($value) {
    return Simple\Frame\Env::set($name, $value);
  } else {
    return Simple\Frame\Env::get($name);
  }

}
