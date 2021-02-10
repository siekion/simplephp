<?php

namespace simple\traits;

trait verify
{
    public $data = array();

    public function create($data)
    {
        foreach ($data as $post_key => $post_var) {
            $this->data[$post_key] = $post_var;
            $dataArray[$post_key] = $post_var;
        }
        return $dataArray;
    }

    /**
     * 功能：参数判断
     * @param $value
     * @param $condition
     * @param $len
     * @return bool
     */
    public function judge($value, $url, $msg = '', $condition = '!empty', $len = '')
    {
        switch ($condition) {
            case 'empty':
                if (empty($value)) {
                    return true;
                } else {
                    $this->jump($url, $msg);
                }
                break;
            case '!empty':
                if (empty($value)) {
                    $this->jump($url, $msg);
                } else {
                    return true;
                }
                break;
            case 'null':
                if (is_null($value) or $value == 'null') {
                    return true;
                } else {
                    $this->jump($url, $msg);
                }
                break;
            case '!null':
                if (is_null($value) or $value == 'null') {
                    $this->jump($url, $msg);
                } else {
                    return true;
                }
                break;
            case 'len':
                if (strlen($value) == $len) {
                    return true;
                } else {
                    $this->jump($url, $msg);
                }
                break;
            case '0':
                if ($value == '0') {
                    return true;
                } else {
                    $this->jump($url, $msg);
                }
                break;
        }
    }
}
