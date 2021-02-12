<?php

namespace simple\frame;
class route
{
    public $model; //模块
    public $controller; //控制器
    public $action; //方法

    public function index($default)
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
            $url_count = count($url);
            if ($url_count > 3) {
                $SpecialTreatment = stripos($url[2], '?');
                if ($SpecialTreatment) {
                    $urlParameter = explode('?', $url[2]);
                    $url[2] = $urlParameter[0];
                }
            }
            if (empty($url[0])) {
                $this->model = $default['model'];
            } else {
                $this->model = $url[0];
            }
            if (empty($url[1])) {
                $this->controller = $default['controller'];
            } else {
                $this->controller = $url[1];
            }
            if (empty($url[2])) {
                $this->action = $default['action'];
            } else {
                $this->action = $url[2];
            }
            if ($url_count > 2) {
                $i = 3;
                while ($i < $url_count) {
                    if (isset($url[$i + 1])) {
                        $_GET[$url[$i]] = $url[$i + 1];
                    }
                    $i = $i + 2;
                }
            }
        } else {
            $this->model = $default['model'];
            $this->controller = $default['controller'];
            $this->action = $default['action'];
        }
        $controller_path = $this->model . '\controller\\' . $this->controller;
        $model = new $controller_path();
        if (empty($model)) {
            echo '模块不存在!';
        } else {
            if (!method_exists($model, $this->action)) {
                echo '方法不存在!';
            } else {
                define('MODEL', $this->model);
                define('CONTROLLER', $this->controller);
                define('ACTION', $this->action);
                if (method_exists($model, 'init')) {
                    $model->init();
                }
                $action = $this->action;
                $model->$action();
            }
        }

    }
}
