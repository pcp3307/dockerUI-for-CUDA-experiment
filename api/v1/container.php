<?php
require_once 'dockerapi.php';

$app->post('/getList', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $db = new DbHandler();
        $session = $db->getSession();
        $containerInfo = dockerAPI::getList($session['name']);

        foreach($containerInfo as $key=>$info) {
            $id = $info["cid"];
            $ip = $info["ip"];
            $Info = dockerAPI::checkStatus($id,$ip);
            $containerInfo[$key]["status"] = $Info["status"];
            $containerInfo[$key]["port"] = $Info["port"];
        }

        $response['data'] = $containerInfo;
        $response['status'] = "success";
        
        echoResponse(200, $response);
    }
});

$app->post('/start', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $host = 'http://' . $r->ip . ':4243';
        $dockerAPI = new dockerAPI($host);
        $statusCode = $dockerAPI->start($r->cid);
        $Info = dockerAPI::checkStatus($r->cid, $r->ip);
        if($statusCode == 204) {
            $response['status'] = "success";
            $response['message'] = "Container start successfully";
            $response['containerStatus'] = $Info['status'];
            $response['port'] = $Info['port'];
        }
        else if($statusCode == 304) {
            $response['status'] = "info";
            $response['message'] = "Container already started";
        }
        else {
            $response['status'] = "error";
            $response['message'] = "Container not found";
        }
        
        echoResponse(200, $response);
    }
});

$app->post('/stop', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $host = 'http://' . $r->ip . ':4243';
        $dockerAPI = new dockerAPI($host);
        $statusCode = $dockerAPI->stop($r->cid);
        $Info = dockerAPI::checkStatus($r->cid, $r->ip);
        if($statusCode == 204) {
            $response['status'] = "success";
            $response['message'] = "Container stop successfully";
            $response['containerStatus'] = $Info['status'];
            $response['port'] = $Info['port'];
        }
        else if($statusCode == 304) {
            $response['status'] = "info";
            $response['message'] = "Container already stop";
        }
        else {
            $response['status'] = "error";
            $response['message'] = "Container not found";
        }
        
        echoResponse(200, $response);
    }
});

$app->post('/create', function() use ($app) {
    if(checkSession()) {
        
        $db = new DbHandler();

        $r = json_decode($app->request->getBody());
        $createData = $r->data;
        $name = $createData->name;
        $types = $createData->image;
        $username = $r->username;

        $response = array();
        $config = getConfig();
        $hosts = $config['dockerServerIP'];
        $rand_key = array_rand($hosts, 1);
        $ip = $hosts[$rand_key]; 
        $host = 'http://' . $ip . ':4243';

        $dockerAPI = new dockerAPI($host);
        $containerInfo = $dockerAPI->create();
        $id = $containerInfo->Id;
        $statusCode = $containerInfo->http_code;
        $dockerAPI->start($id);
        $Info = dockerAPI::checkStatus($id, $ip);

        $data = array(
            "cid" => $id,
            "username" => $username,
            "name" => $name,
            "ip" => $ip,
            "types" => $types
        );

        $data = json_decode(json_encode($data));
       
        
        if($statusCode == 201) {
            $tabble_name = "users_container";
            $column_names = array('cid', 'username', 'name', 'ip', 'types');
            $result = $db->insertIntoTable($data, $column_names, $tabble_name);
            
            $response['status'] = "success";
            $response['message'] = "Container create successfully";
            $data->status = $Info['status'];
            $data->port = $Info['port'];
            $response['data'] = $data;
        }
        else {
            $response['status'] = "error";
            $response['message'] = "Container create failed";
        }
        
        echoResponse(200, $response);
    }
});


$app->post('/remove', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $id = $r->cid;
        $statusCode = dockerAPI::remove($id, $r->ip);
       
        $db = new DbHandler();
        $db->deleteFromTable('cid', $id, 'users_container');

        if($statusCode == 204) {
            $response['status'] = "success";
            $response['message'] = "Container remove successfully";
        }
        else {
            $response['status'] = "error";
            $response['message'] = "Container not found";
        }
        
        echoResponse(200, $response);
    }
});

