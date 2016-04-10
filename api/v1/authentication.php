<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    $response["name"] = $session['name'];
    echoResponse(200, $session);
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('username', 'password'),$r->user);
    $response = array();
    $db = new DbHandler();
    $password = $r->user->password;
    $username = $r->user->username;
    $userinfo = $db->getOneRecord("select uid,name,password,email,created from users_auth where name='$username'");
    if ($userinfo != NULL) {
        if(passwordHash::check_password($userinfo['password'],$password)){
        $response['status'] = "success";
        $response['message'] = 'Logged in successfully.';
        $response['name'] = $userinfo['name'];
        $response['uid'] = $userinfo['uid'];
        $response['email'] = $userinfo['email'];
        $response['createdAt'] = $userinfo['created'];
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['uid'] = $userinfo['uid'];
        $_SESSION['email'] = $userinfo['email'];
        $_SESSION['name'] = $username;
        } 
        else {
            $response['status'] = "error";
            $response['message'] = 'Login failed. Incorrect credentials';
        }
    }
    else {
            $response['status'] = "error";
            $response['message'] = 'No such user is registered';
    }
    echoResponse(200, $response);
});
$app->post('/signUp', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('name', 'password', 'email'),$r->user);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $name = $r->user->name;
    $email = $r->user->email;
    $password = $r->user->password;
    $isUserExists = $db->getOneRecord("select 1 from users_auth where name='$name'");
    if(!$isUserExists){
        $r->user->password = passwordHash::hash($password);
        $tabble_name = "users_auth";
        $column_names = array('name', 'email', 'password');
        $result = $db->insertIntoTable($r->user, $column_names, $tabble_name);
        if ($result != NULL) {
            $response["status"] = "success";
            $response["message"] = "User account created successfully";
            $response["uid"] = $result;
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['uid'] = $response["uid"];
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
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
});
$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Logged out successfully";
    echoResponse(200, $response);
});
?>
