<?php

class dockerAPI {

    private $c;
    private $host;
    private $db;

    function __construct($host) {
        $this->host = $host;
        $this->db = new DbHandler();
        $this->c = curl_init();
    }
 
    private function setCurlOpt($method, $data, $post = false) {
        curl_setopt($c, CURLOPT_URL, $url);

        if($post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        }

        curl_setopt($c, CURLOPT_RETURNTRANSFER,1);
    }

    public function getList($username) {
        $containerInfo = $this->db->getMultiRecord("select cid,name,address,types,created from users_container where username='$username'");
        

        return $containerInfo; 
    }

    public function checkStatus($id) {

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
