<?php
require_once 'dockerapi.php';

$app->post('/getList', function() use ($app) {
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();
    $host = '192.168.1.104:4243';
    $dockerAPI = new dockerAPI($host);
    $containInfo = $dockerAPI->getList($r->username);
    $response['test'] = $containInfo;
    $response['status'] = "success";
    $response['cid'] = $containInfo['cid'];
    $response['name'] = $containInfo['name'];
    $response['address'] = $containInfo['address'];
    $response['types'] = $containInfo['types'];
    $response['createdAt'] = $containInfo['created'];
    
    echoResponse(200, $response);
});

?>
