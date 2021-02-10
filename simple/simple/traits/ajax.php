<?php

namespace simple\traits;

trait ajax
{
    public function ajaxSuccess($data = '', $msg = '', $status = 1, $data2 = '', $data3 = '')
    {
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:text/html; charset=utf-8');
        $info = array();
        $info['data'] = $data; //返回数据
        $info['data2'] = $data2; //返回数据2
        $info['data3'] = $data2; //返回数据3
        $info['msg'] = $msg; //提示消息
        $info['status'] = $status; //返回状态
        exit(json_encode($info));
    }

    public function ajaxError($msg = '', $status = 0)
    {
        // 返回JSON数据格式到客户端 包含状态信息
        header('Content-Type:text/html; charset=utf-8');
        $info = array();
        $info['msg'] = $msg; //提示消息
        $info['status'] = $status; //返回状态
        exit(json_encode($info));
    }

}