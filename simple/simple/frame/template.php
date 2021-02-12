<?php

namespace simple\frame;
class template
{

    public $delim = "#";

    public $template_path = "./home/view/";

    public $template_cache = "./cache";

    public $cache_time = 86400;

    public $template_surfix = ".html";

    private $current_template = "";

    private $cache_file = "";

    private $data = array();


    public function __get($name)
    {

        return isset($this->data[$name]) ? $this->data[$name] : false;

    }


    public function __set($name, $value)
    {

        $this->data[$name] = $value;

    }


    public function assign($name, $value)
    {
        $this->$name = $value;
    }

    public function display($template)
    {

        $this->current_template = $template;

        if ($this->chk_cache()) {

            @extract($this->data);

            include($this->cache_file);

        } else {

            $this->compile();

        }

    }


    private function token($str)
    {

        $tok = strtok($str, $this->delim);

        while ($tok !== false) {

            $ret = substr($tok, 0, strpos($tok, "\n"));

            $tok = strtok($this->delim);

            $retarr[$ret] = token_get_all("<!--p " . $ret . "-->");

        }

        return $retarr;

    }


    private function parse($str)
    {
        $retarr = $this->token($str);
        foreach ($retarr as $key => $v) {
            $tokenname = @token_name($v[1][0]);
            switch ($tokenname) {
                case 'T_VARIABLE':
                    $str = str_replace("#" . $key, "<?php echo {$v[1][1]} ?>", $str);
                    break;
                default:
                    $str = str_replace("#" . $key, "<?php $key ?>", $str);
            }
        }
        return ($str);
    }


    private function compile()
    {
        $source = file_get_contents($this->template_path . "/" . $this->current_template . $this->template_surfix);
        $result = $this->parse($source);
        file_put_contents($this->cache_file, $result);
        @extract($this->data);
        include($this->cache_file);

    }


    private function chk_cache()
    {
        $this->cache_file = $this->template_cache . "/" . md5($this->current_template) . ".php";
        if (file_exists($this->cache_file) && time() - filemtime($this->cache_file) < $this->cache_time) {
            return true;
        }
        return false;
    }

}
