<?php

//引入框架启动文件
include('simple/start.php');

//获取Simple对象
$simple = start::getObject();

//设置调试模式(true:打开;false:关闭;)
$simple->debug(true);

//创建应用模块
$simple->createModule('home');

//运行框架并设置默认启动的(模块、控制器、方法)
$simple->run('home', 'index', 'index');