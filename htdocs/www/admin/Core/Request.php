<?php

class Request {

    public $url;
    public $index = "home";

    function __construct()
    {
        $this->url = (isset($_GET['url']) ? $_GET['url']  : $this->index . "/index_action" );
//        print_r($this->_url);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }



}



?>