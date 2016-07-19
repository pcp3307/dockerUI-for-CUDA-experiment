<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["name"] = $session['name'];
    echoResponse(200, $session);
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('username', 'password'),$r->user);
    $password = $r->user->password;
    $username = $r->user->username;
    
    $response = getOwncloudSession($username, $password);
    if($response) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['name'] = $username;
    } 

    echoResponse(200);
});
$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Logged out successfully";
    echoResponse(200, $response);
});
?>
