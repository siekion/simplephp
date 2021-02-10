<?php

namespace simple\traits;

use LogicException;

trait view
{
    protected $blobkName;
    protected $menu;
    protected $show = array();
    protected $layout = null;

    /**
     * 设置模块布局
     * @param $name
     */
    public function layout($name)
    {
        if (isset($this->show[$name])) {
            echo $this->show[$name];
        }
    }

    /**
     * 开始记录模板内容
     * @param $name
     */
    public function start($name)
    {
        //判断区块是否已经记录
        if (array_key_exists($name, $this->show)) {
            echo('区块名重复!');
            exit;
        } else {
            $this->blobkName = $name;
            ob_start();
        }
    }

    /**
     * 结束记录模板内容
     */
    public function end()
    {
        $content = ob_get_clean();

        if (is_null($this->blobkName)) {
            throw new LogicException(
                '您必须先启动块，然后才能停止它。'
            );
        }

        if (!isset($this->blobkName)) {
            $this->blobkName = '';
        }

        if (!isset($this->show[$this->blobkName])) {
            $this->show[$this->blobkName] = '';
        }

        $this->show[$this->blobkName] = $content;
        $this->blobkName = null;
    }


    /**
     * 给模板赋值
     * @param $key
     * @param $value
     */
    public function assign($key, $value)
    {
        if (!empty($value)) {
            $GLOBALS[$key] = $value;
        } elseif ($value == 0) {
            $GLOBALS[$key] = 0;
        } else {
            $GLOBALS[$key] = null;
        }
    }

    /**
     * 模板显示和跳转页面
     * @param null $page
     * @param string $template
     */
    public function display($page = null, $template = 'layout')
    {
        if ($page) {
            $file = MODEL . '/view/' . $page . '.html';
        } else {
            $file = MODEL . '/view/' . ACTION . '.html';
        }
        
        if (is_file($file)) {
            include($file);
            if (!is_null($template)) {
                //判断是否有公共模板，如果有就那导入
                $templatePublic = MODEL . '/view/' . $template . '.html';
                if (is_file($templatePublic)) {
                    include($templatePublic);
                }
            }
        } else {
            $message = '错误提示：您访问的资源文件不存在!';
            $filePath = $file;
            $this->error('404', $message, $filePath);
        }
    }


    /**
     * 返回http格式的完整url路径
     * @param $url
     * @return string
     */
    public function http_url($url)
    {
        $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/' . MODEL . '/' . $url;
        return $url;
    }

    /**
     * 跳转函数
     * @param string $url
     * @param string $msg
     * @param int $time
     */
    public function jump($url = '', $msg = '', $time = 3)
    {
        $url = $this->http_url($url);
        $color = color();

        if (!empty($msg)) {
            //刷新跳转,给出提示
            echo <<<TIAOZHUAN
<html>
<head>
<meta charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>提示</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
 <style type='text/css'>
    div {width:390px; height:287px;}
    div h2 {width:100%; height:40px; line-height:40px; background-color:$color; font-size:20px; color:#FFF; text-indent:10px;}
    div p {height:120px; line-height:120px; text-align:center;}
    div p strong {font-size:26px;}
</style>
<br>
<br>
    <div style="border:1px #09C solid;padding:0;" class="col-12 m-auto" >
      <h2>提示信息</h2>
    <p>
        <strong>$msg</strong><br />
        页面在<span id="second">$time</span>秒后会自动跳转，或点击<a id="tiao" href="$url">立即跳转</a>
    </p>
</div>

</div>

<script type="text/javascript">
    var url = document.getElementById('tiao').href;
    function daoshu(){
        var scd = document.getElementById('second');
        var time = --scd.innerHTML;
        if(time<=0){
            window.location.href = url;
            clearInterval(mytime);
        }
    }
    var mytime = setInterval("daoshu()",1000);
</script>

</body>
</html>

TIAOZHUAN;
            die;
        } else {
            header("location:{$url}");
            die;
        }
    }


}

