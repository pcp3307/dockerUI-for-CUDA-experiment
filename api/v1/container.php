<?php
require_once 'dockerapi.php';

$app->post('/getList', function() use ($app) {
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();
    $host = 'http://192.168.0.200:4243';
    $dockerAPI = new dockerAPI($host);
    $containInfo = $dockerAPI->getList($r->username);
    $response['data'] = $containInfo;
    $response['status'] = "success";
    
    echoResponse(200, $response);
});

?>
