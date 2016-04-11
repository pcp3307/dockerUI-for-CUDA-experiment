<?php
$host = "192.168.1.104:4243"
$app->post('/getList', function() use ($app) {
    $r = json_decode($app->request->getBody());
    $response = array();
    $db = new DbHandler();
    $dockerAPI = new dockerAPI($host);
    $containInfo = $dockerAPI->getList($r->username)
    
    $response['status'] = "success";
    $response['cid'] = $containInfo['cid'];
    $response['name'] = $containInfo['name'];
    $response['address'] = $containInfo['address'];
    $response['types'] = $containInfo['types'];
    $response['createdAt'] = $containInfo['created'];
    
    echoResponse(200, $response);
});

?>
