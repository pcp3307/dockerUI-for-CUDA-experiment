<?php

class dockerAPI {

    private $c;
    private $host;
    private $db;

    function __construct($host) {
        $this->host = $host;
        $this->db = new DbHandler();
    }
 
    private function setCurlOpt($method, $data, $post = false) {

        $this->c = curl_init();

        if($post) {
            $url = $this->host . $method;
            curl_setopt($this->c, CURLOPT_POST, true);
            curl_setopt($this->c, CURLOPT_POSTFIELDS, $data);
        }
        else {
            $url = $this->host . $method . $data;
        }
        curl_setopt($this->c, CURLOPT_URL, $url);
        curl_setopt($this->c, CURLOPT_RETURNTRANSFER,1);
        $response = curl_exec($this->c);
        curl_close($this->c);
        
        return json_decode($response);
    }

    public function getList($username) {
        $containerInfo = $this->db->getMultiRecord("select cid,name,address,types,created from users_container where username='$username'");
        
        foreach($containerInfo as $key=>$info) {
            $id = $info["cid"];
            $status = $this->checkStatus($id);
            $containerInfo[$key]["status"] = $status;
        }
        return $containerInfo; 
    }

    public function checkStatus($id) {
        $method = "/containers/json";
        $id = "id=".$id;
        $data = "?all=1&filter=" . $id;
        
        $response = $this->setCurlOpt($method, $data);
        $statusString = json_encode($response[0]->Status);
        $statusString = strtok($statusString, " ");

        if($statusString == "\"Up") {
            $status = "true";
        }
        else {
            $status = "false";
        }
        return $status;
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
