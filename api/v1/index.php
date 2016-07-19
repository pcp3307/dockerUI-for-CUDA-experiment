<?php

require_once 'dbHandler.php';
require_once 'passwordHash.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$user_id = NULL;

require_once 'authentication.php';
require_once 'container.php';



/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields,$request_params) {
    $error = false;
    $error_fields = "";
    foreach ($required_fields as $field) {
        if (!isset($request_params->$field) || strlen(trim($request_params->$field)) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["status"] = "error";
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(200, $response);
        $app->stop();
    }
}

function getOwncloudSession($username, $password) {
    $c = curl_init();

    $host = "http://140.129.25.141/owncloud/index.php";
    $api = "/apps/user_permission/api/getSession";
    $url = $host . $api;


    $post_data["username"] = $username;
    $post_data["password"] = $password;
    $verifyString = $username . ":" . $password;

    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($c, CURLOPT_POST, 1);
    curl_setopt($c, CURLOPT_USERPWD, $verifyString);
    curl_setopt($c, CURLOPT_POSTFIELDS, $post_data);

    $result = curl_exec($c);
    curl_close($c);
    return $result; 
}

function echoResponse($status_code, $response = null) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');
    if($response != null) {
        echo json_encode($response);
    }
}

function checkSession(){
    $db = new DbHandler();
    $session = $db->getSession();
    if($session['name'] == ''){
        $response = array(
            'message' => 'Not Found'
        );
        echoResponse(404, $response);
        return false;
    }
    return true;
}

function getConfig() {
    require_once 'config.php';

    return $CONFIG;
}

$app->run();
?>
