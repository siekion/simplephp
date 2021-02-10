<?php

namespace simple\traits;

trait error
{
    public function __construct()
    {
        //使用自定义的错误处理
        set_error_handler("error_handler");
    }

    public function error($errorId, $message = '您访问的页面不存在!', $pages = '')
    {
        if (DEBUG) {
            if ($errorId == 404) {
                echo "<b>错误序号：</b>" . $errorId . "<br/>";
                echo "<b>错误提示：</b>" . $message . "<br/>";
//                if (!empty(MODEL) and !empty(CONTROLLER) and !empty(ACTION)) {
//                    echo "<b>错误控制器：</b>" . MODEL . '/' . CONTROLLER . '/' . ACTION . "<br/>";
//                }
                if ($pages) {
                    echo "<b>错误模板文件：</b>" . $pages . "<br/>";
                }
            }
        } else {
            echo '您访问的页面不存在' . "<br/>";
        }
    }

    /**
     * 自定义错误处理
     * @param $errno //错误号
     * @param $errstr //错误内容
     * @param $errfile //错误文件
     * @param $errline //错误行
     */
    function error_handler($errno, $errstr, $errfile, $errline)
    {
        echo "<b>错误提示：</b>" . $errstr . "<br/>";
        echo "<b>错误位置：</b>" . $errfile . "（第" . $errline . "行）<br/>";
    }

    /**
     * 错误日志
     * @param $msg //错误消息
     * @param $filePath //错误日志路径
     */
    public function errorLog($msg, $filePath)
    {
        error_log($msg, 3, $filePath);
    }

}