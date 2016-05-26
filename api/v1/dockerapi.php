<?php

class dockerAPI {

    private $c;
    private $host;
    private $db;

    function __construct($host) {
        $this->host = $host;
        $this->db = new DbHandler();
    }
 
    private function Curl($method, $data = null, $post = false, $statusCode = false) {

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
        curl_setopt($this->c, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($this->c, CURLOPT_RETURNTRANSFER,1);
        $response = curl_exec($this->c);
        $info = curl_getinfo($this->c);
        if($statusCode) {
            return $info["http_code"];
        }
        curl_close($this->c);
        $response = json_decode($response);
        $response->http_code = $info["http_code"];
        
        return $response;
    }

    public static function getList($username) {
        $db = new DbHandler();
        $containerInfo = $db->getMultiRecord("select cid,name,ip,types,created from users_container where username='$username'");
        
        return $containerInfo; 
    }
    
    public static function getVolumeList($username) {
        $db = new DbHandler();
        $volumeInfo = $db->getMultiRecord("select cid,name,created from users_volume where username='$username'");
        
        return $volumeInfo; 
    }

    public static function checkStatus($id, $ip) {
        $method = "/containers/json";
        $data = "?all=1";
        $url = "http://" . $ip . ":4243" . $method . $data;
 
        $c = curl_init();

        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER,1);

        $response = json_decode(curl_exec($c));
        curl_close($c);
        $publicport = "";
        foreach($response as $value) {
            if($value->Id == $id){
                $Ports = $value->Ports;
                for ($i = 0; $i < sizeof($Ports); $i++) {
                    $privateport = $Ports[$i]->PrivatePort;
                    if($privateport == '22') {
                        $publicport = $Ports[$i]->PublicPort;
                        break;
                    }
                }
                $statusString = $value->Status;
                break;
            }   
        }
        $statusString = strtok($statusString, " ");
        if($statusString == "Up") {
            $status = "true";
        }
        else {
            $status = "false";
        }
        $Info = array(
            "status" => $status,
            "port" => $publicport
        );
        return $Info;
    }
    
    public function create($data=null) {
        $method = "/containers/create";
        $post_data = array();
        $post_data["Image"] = "ubuntu_ssh:v2";
        $post_data["HostConfig"] = array(
            "PublishAllPorts" => true,
            "Privileged" => true,
        );
        $post_data = json_encode($post_data);

        $response = $this->Curl($method, $post_data, true, false);
        return $response;
    }
    
    public function start($id) { 
        $method = "/containers/" . $id . "/start";
        $statusCode = $this->Curl($method,null,true,true);
        return $statusCode;
    }

    public function stop($id){
        $method = "/containers/" . $id . "/stop?";
        $statusCode = $this->Curl($method,null,true,true);
        return $statusCode;
    }

    public static function remove($id, $ip) {
        $method = "/containers/" . $id . "?force=1";
        $url = "http://" . $ip . ":4243" . $method;

        $c = curl_init();

        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($c, CURLOPT_CUSTOMREQUEST, "DELETE");

        curl_exec($c);

        $info = curl_getinfo($c);
        curl_close($c);

        return $info["http_code"];
    }
}
