<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["name"] = $session['name'];
    echoResponse(200, $response);
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('username', 'password'),$r->user);
    $password = $r->user->password;
    $username = $r->user->username;
    
    $db = new DbHandler();
    $session = $db->getOwncloudSession($username, $password);

    if($session === 'true') {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['name'] = $username;
        $_SESSION['passwd']= $password;

        $response['status'] = 'success';
        $response['message'] = 'Logged in successfully.';
        $response['name'] = $username;
    }
    else {
        $response['status'] = 'error';
        $response['message'] = 'Log in failed. Incorrect credentials';
    }

    echoResponse(200, $response);
});
$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Logged out successfully";
    echoResponse(200, $response);
});
?>
