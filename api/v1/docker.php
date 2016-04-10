<?php

class dockerApi {

    private $c = curl_init();
    private $host;
    
    function __construct($host) {
        $this->host = $host
    }
 
    private function setCurlOpt($method, $data, $post = false) {
        curl_setopt($c, CURLOPT_URL, $url);

        if($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }

        curl_setopt($c, CURLOPT_RETURNTRANSFER,1);
    }

    public function getList() {
        
    }
    
    public function createContainer($data) {

    }
    
    public function start($id) {
    }

    public function stop($id){
    }

    public function remove($id, $volume) {
    }
}
