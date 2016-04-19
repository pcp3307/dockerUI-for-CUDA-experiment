<?php
require_once 'dockerapi.php';

$app->post('/getList', function() use ($app) {
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();
    $host = 'http://192.168.0.200:4243';
    $dockerAPI = new dockerAPI($host);
    $containerInfo = $dockerAPI->getList($r->username);
    $response['data'] = $containerInfo;
    $response['status'] = "success";
    
    echoResponse(200, $response);
});

$app->post('/start', function() use ($app) {
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();
    $host = 'http://192.168.0.200:4243';
    $dockerAPI = new dockerAPI($host);
    $statusCode = $dockerAPI->start($r->cid);
    if($statusCode == 204) {
        $response['status'] = "success";
        $response['message'] = "Container start successfully";
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
});

$app->post('/stop', function() use ($app) {
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();
    $host = 'http://192.168.0.200:4243';
    $dockerAPI = new dockerAPI($host);
    $statusCode = $dockerAPI->stop($r->cid);
    if($statusCode == 204) {
        $response['status'] = "success";
        $response['message'] = "Container stop successfully";
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
});
?>
