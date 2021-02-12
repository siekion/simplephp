<?php

namespace simple\frame;
class create
{
    private static $instance = null;

    /**
     * 功能：返回单例对象
     * @return null
     */
    public static function getObject()
    {
        if (!static::$instance instanceof self) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * 功能：创建模板文件
     * @param $project
     */
    public function createTemplate($project)
    {
        //创建目录
        $this->createDir('./' . $project . '/model');
        $this->createDir('./' . $project . '/controller');
        $this->createDir('./' . $project . '/view');
        //创建文件
        $this->createFile('./' . $project . '/model/index.php', $project, 'model');
        $this->createFile('./' . $project . '/controller/index.php', $project, 'controller');
        $this->createFile('./' . $project . '/view/layout.html', $project, 'layout');
        $this->createFile('./' . $project . '/view/index.html', $project, 'index.html');
    }

    /**
     * 功能：创建模板目录
     * @param $project
     */
    public function createDir($project)
    {
        header("Content-type:text/html;charset=utf-8");
        //检查app目录是否存在
        if (!is_dir($project)) {
            mkdir($project, 0777, true);
        }
    }

    /**
     * 功能：创建模板文件
     * @param $file
     * @param $model
     * @param $suffix
     */
    public function createFile($file, $model, $suffix)
    {
        //判断文件是否存在
        if (!file_exists($file)) {
            $one = "\r\n";
            $two = "\r\n\r\n";
            if ($suffix == 'model') {
                $content = '<?php' . $two .
                    'namespace ' . $model . '\model;' . $two .
                    'use simple\model;' . $two .
                    'class index extends model' . $one .
                    '{' . $one .
                    '    public function index()' . $one .
                    '    {' . $one .
                    '        $this->display();' . $one .
                    '    }' . $one .
                    "}" . $one;
            } elseif ($suffix == 'controller') {
                $content = '<?php' . $two .
                    'namespace ' . $model . '\controller;' . $two .
                    'use simple\controller;' . $two .
                    'class index extends controller' . $one .
                    '{' . $one .
                    '    public function index()' . $one .
                    '    {' . $one .
                    '        $this->display();' . $one .
                    '    }' . $one .
                    "}" . $one;
            } elseif ($suffix == 'layout') {
                $content = "<!doctype html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width,user-scalable=no,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie = edge'>
    <title>simplePHP Frame</title>
    <?= " . '$this->layout("css"); ?>' . "
</head>
<body>" . $one . '<?= $this->layout("content"); ?>' . $one . '<?= $this->layout("js"); ?>' . "
</body>
</html>";
            } elseif ($suffix == 'html.php') {
                $content = '<?=$this->start("css")?>' . $one .
                    '<?=$this->end()?>' . $two .
                    '<?=$this->start("content")?>' . $one .
                    '<h1>Welcome SimplePHP</h1>' . $one .
                    '<?=$this->end()?>' . $two .
                    '<?=$this->start("js")?>' . $one .
                    '<?=$this->end()?>' . $two;
            }
            file_put_contents($file, $content);
        }
    }
}