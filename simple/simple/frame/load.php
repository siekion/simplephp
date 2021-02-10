<?php

namespace simple\frame;
class Load
{
    private static $instance = null;
    public static $vendorMap = array(
        'simple' => 'simple\simple'
    );

    /**
     * 功能：返回单例对象
     * @return null
     */
    public static function getObject()
    {
        $cName = get_called_class();
        if (!static::$instance instanceof self) {
            static::$instance = new $cName();
        }
        return static::$instance;
    }

    /**
     *功能：自动加载
     * autoload constructor.
     */
    public static function autoload()
    {
        spl_autoload_register(__CLASS__ . '::autoloadFile');
    }

    /**
     * 功能：设置加载路径
     * @param $attribute
     */
    public function setVendorMap($attribute)
    {
        self::$vendorMap = array_merge(self::$vendorMap, $attribute);
    }

    /**
     * 功能：手动加载文件
     */
    public static function load($file)
    {
        self::includeFile($file);
    }

    /**
     * 功能：自动加载器
     * autoload constructor.
     * @param $class
     */
    public static function autoloadFile($class)
    {
        $file = self::findFile($class);
        if (file_exists($file)) {
            self::includeFile($file);
        }
    }

    /**
     * 功能：解析文件路径
     * @param $class
     * @return string
     */
    private static function findFile($class)
    {
        $vendor = substr($class, 0, strpos($class, '\\')); // 顶级命名空间
        $vendorDir = self::$vendorMap[$vendor]; // 文件基目录
        $filePath = substr($class, strlen($vendor)) . '.php'; // 文件相对路径
        return strtr($vendorDir . $filePath, '\\', DIRECTORY_SEPARATOR); // 文件标准路径
    }

    /**
     *功能：引入文件
     * @param $file
     */
    private static function includeFile($file)
    {
        if (is_file($file)) {
            include $file;
        }
    }
}