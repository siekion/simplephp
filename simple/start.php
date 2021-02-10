<?php

require_once('simple/frame/load.php');

class start
{
    private static $instance = null;

    //初始化
    public function __construct()
    {
        //自动加载所有文件
        $simpleLoad = simple\frame\load::getObject();
        $simpleLoad::autoload();
        //手动加载函数库
        $this->load('./simple/simple/function/function.php');
    }

    //返回单例对象
    public static function getObject()
    {
        $cName = get_called_class();
        if (!static::$instance instanceof self) {
            static::$instance = new $cName();
        }
        return static::$instance;
    }

    //运行框架
    public function run($model, $controller, $action)
    {
        $route = new simple\frame\route();
        $route->index(array('model' => $model, 'controller' => $controller, 'action' => $action));
    }

    //创建模块
    public function createModule($module)
    {
        $simpleFrame = simple\frame\create::getObject();
        $simpleFrame->createTemplate($module);
        $arrKey = array($module);
        $arrValue = array($module);
        $space = array_combine($arrKey, $arrValue);
        $simpleLoad = simple\frame\load::getObject();
        $simpleLoad->setVendorMap($space);
        $simpleLoad::autoload();
    }

    //自动加载
    public function autoLoad($space)
    {
        $simpleLoad = simple\frame\load::getObject();
        $simpleLoad->setVendorMap($space);
        $simpleLoad::autoload();
    }

    //手动加载
    public function load($file)
    {
        $simpleLoad = simple\frame\load::getObject();
        $simpleLoad::load($file);
    }

    //调试模式
    public function debug($debug)
    {
        if ($debug) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
        }
    }

}