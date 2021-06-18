<?php

namespace Simple\Frame;

use Exception;

class Env
{
  const ENV_PREFIX = 'PHP_';

  /**
   * 加载ENV配置文件
   * @access public
   * @param string $filePath 配置文件路径
   * @return void
   */
  public static function load(): void
  {
    $filePath = '../.env';
    if (!file_exists($filePath)) throw new Exception('配置文件' . $filePath . '不存在');
    //返回二位数组
    $env = parse_ini_file($filePath, true);
    foreach ($env as $key => $val) {
      $prefix = static::ENV_PREFIX . strtoupper($key);
      if (is_array($val)) {
        foreach ($val as $k => $v) {
          $item = $prefix . '_' . strtoupper($k);
          putenv("$item=$v");
        }
      } else {
        putenv("$prefix=$val");
      }
    }
  }

  /**
   * 获取环境变量
   * @access public
   * @param string $name 环境变量名（支持二级 . 号分割）
   * @param string $default 默认值
   * @return mixed
   */
  public static function get(string $name)
  {
    $result = getenv(static::ENV_PREFIX . strtoupper(str_replace('.', '_', $name)));
    if (false !== $result) {
      if ('false' === $result) {
        $result = false;
      } elseif ('true' === $result) {
        $result = true;
      }
    }
    return $result;
  }

  /**
   * 功能：设置环境变量
   * @param string $name
   * @param $val
   * @return array|false|string
   */
  public static function set(string $name, $val)
  {
    $prefix = static::ENV_PREFIX . strtoupper($name);
    if (is_array($val)) {
      foreach ($val as $k => $v) {
        $item = $prefix . '_' . strtoupper($k);
        putenv("$item=$v");
      }
    } else {
      putenv("$prefix=$val");
    }
    return getenv(strtoupper(str_replace('.', '_', $name)));
  }


}
