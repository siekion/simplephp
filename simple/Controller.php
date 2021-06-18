<?php

namespace Simple;

use Simple\Frame\View;
use Simple\Traits\Ajax;
use Simple\Traits\Verify;

class Controller extends View
{
  use Ajax, Verify;

  public function __construct()
  {
    //清除缓存
    cache_delete();
    //设置北京时区
    date_default_timezone_set('PRC');
  }

//    public function ftp($localPath, $serverPath)
//    {
//        $config = [
//            'host' => '',
//            'user' => '',
//            'pass' => ''
//        ];
//        $ftp = new  \simple\library\core\Ftp($config);
//        $result = $ftp->connect();
//        if (!$result) {
//            echo $ftp->get_error_msg();
//        } else {
//            if ($ftp->upload('index.php', $serverPath)) {
//                return true;
//            } else {
//                return false;
//            }
//        }
//    }
//
//    public function db($myModel = null, $dbType = 'mysql', $dbConf = '1')
//    {
//        if ($dbType == 'mysql') {
//            switch ($dbConf) {
//                case '1':
//                    $database = array(
//                        //数据库配置
//                        'dbms' => '', //数据库类型
//                        'host' => '', //数据库地址
//                        'dbname' => '', //数据库名称
//                        'user' => '', //数据库账号
//                        'pass' => '', //数据库密码
//                        'charset' => '', //数据库字符集
//                        'port' => '', //数据库端口
//                    );
//                    break;
//                default:
//                    return '未找到数据库配置!';
//            }
//        } elseif ($dbType == 'sqlsrv') {
//            switch ($dbConf) {
//                case '1':
//                    $database = array(
//                        //数据库配置
//                        'dbms' => '', //数据库类型
//                        'server' => '', //数据库地址
//                        'database' => '', //数据库名称
//                        'user' => '', //数据库账号
//                        'pass' => '', //数据库密码
//                        'charset' => '', //数据库字符集
//                        'port' => '', //数据库端口
//                    );
//                    break;
//                default:
//                    return '未找到数据库配置!';
//            }
//        }
//
//        if (!empty($myModel)) {
//            $getIns = '\app\\' . MODEL . '\model\\' . $myModel;
//            $model = $getIns::getInstance($database);
//        } else {
//            $model = \simple\model::getInstance($database);
//        }
//        return $model;
//    }
}
