<?php

$app->post('/getResource', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $db = new DbHandler();
        $resources = $db->getMultiRecord("select ip,quantity from docker_resource where 1");

        $response['data'] = $resources;
        $response['status'] = "success";
        
        echoResponse(200, $response);
    }
});

$app->post('/addResource', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $ip = $r->ip;
        
        if (!filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $data = array(
            "ip" => $ip,
            "quantity" => 0
            );

            $db = new DbHandler();
            $tabble_name = "docker_resource";
            $column_names = array('ip', 'quantity');
            $result = $db->insertIntoTable($data, $column_names, $tabble_name);
            if(!$result) {
                $response['data'] = $data;
                $response['status'] = "success";
                $response['message'] = "Add resource successfully";
            }
            else {
                $response['status'] = "error";
                $response['message'] = "$ip is already existed";
            }
        }
        else {
            $response['status'] = "error";
            $response['message'] = "$ip is not a valid IP address";
        }        
        echoResponse(200, $response);
    }
});


$app->post('/removeResource', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $ip = $r->ip;
       
        $db = new DbHandler();
        $db->deleteFromTable('ip', $ip, 'docker_resource');

        $response['status'] = "success";
        $response['message'] = "Remove resource successfully";
                
        echoResponse(200, $response);
    }
});

