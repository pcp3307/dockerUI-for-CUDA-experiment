<?php
$app->post('/getUserList', function() use ($app) {
    if(checkSession()) {
        $db = new DbHandler();
        $userInfo = $db->getMultiRecord("select uid,name,email,registered from users_auth where 1");
        $response['data'] = $userInfo;
        $response['status'] = "success";
        
        echoResponse(200, $response);
    }
});

$app->post('/createUser', function() use ($app) {
    if(checkSession()) {
        $response = array();
        $r = json_decode($app->request->getBody());
        verifyRequiredParams(array('name', 'password', 'email'),$r->user);
        require_once 'passwordHash.php';
        
        $db = new DbHandler();
        $name = $r->user->name;
        $email = $r->user->email;
        $password = $r->user->password;
        $r->user->registered = 'true';
        $isUserExists = $db->getOneRecord("select 1 from users_auth where name='$name'");
        if(!$isUserExists){
            $r->user->password = passwordHash::hash($password);
            $tabble_name = "users_auth";
            $column_names = array('name', 'email', 'password', 'registered');
            $result = $db->insertIntoTable($r->user, $column_names, $tabble_name);
            if ($result != NULL) {
                $response["status"] = "success";
                $response["message"] = "User account created successfully";
                $response["uid"] = $result;
                $response["data"] = $r->user;

                echoResponse(200, $response);
            } else {
                $response["status"] = "error";
                $response["message"] = "Failed to create user. Please try again";
                echoResponse(201, $response);
            }            
        }
        else {
            $response["status"] = "error";
            $response["message"] = "An user with the provided name exists!";
            echoResponse(201, $response);
        }
    }
});

$app->post('/removeUser', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $name = $r->user->name;
       
        $db = new DbHandler();
        $result = $db->deleteFromTable('name', $name, 'users_auth');
        
        if($result){
            $response['status'] = "success";
            $response['message'] = "User remove successfully";
        }
        echoResponse(200, $response);
    }
});

$app->post('/registerUser', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $uid = $r->user->uid;
       
        $db = new DbHandler();
        $result = $db->updateDataFromTable('registered', 'true', 'users_auth', 'uid', $uid);
        
        if($result){
            $response['status'] = "success";
            $response['message'] = "User registered successfully";
        }
        echoResponse(200, $response);
    }
});

$app->post('/modifyEmail', function() use ($app) {
    if(checkSession()) {
        $r = json_decode($app->request->getBody());
        $response = array();
        $uid = $r->uid;
        $email = $r->email;
      
        $db = new DbHandler();
        $session = $db->getSession();
        if($session['uid'] == $uid) {
            $result = $db->updateDataFromTable('email', $email, 'users_auth', 'uid', $uid);
            
            if($result){
                $_SESSION['email'] = $email;
                $response['status'] = "success";
                $response['message'] = "Email modify successfully";
            }
        }
        else {
            $response['status'] = "error";
            $response['message'] = "Email modify failed";
        }
        echoResponse(200, $response);
    }
});

$app->post('/modifyPassword', function() use ($app) {
    if(checkSession()) {
        require_once 'passwordHash.php';
        $r = json_decode($app->request->getBody());
        $response = array();
        $uid = $r->uid;
        $password = passwordHash::hash($r->password);
      
        $db = new DbHandler();
        $session = $db->getSession();
        if($session['uid'] == $uid) {
            $result = $db->updateDataFromTable('password', $password, 'users_auth', 'uid', $uid);
            
            if($result){
                $response['status'] = "success";
                $response['message'] = "Password modify successfully";
            }
        }
        else {
            $response['status'] = "error";
            $response['message'] = "Password modify failed";
        }
        echoResponse(200, $response);
    }
});
