<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();
    }

    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();    
    }

    /**
     * Fetching multi record
     */
    public function getMultiRecord($query) {
        $result = array();
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
        $count = 0;
        while($row = $r->fetch_assoc()) {
            $result[$count] = $row;
            $count++;
        }
        return $result;    
    }

    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
        
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
        foreach($column_names as $desired_key){ // Check the obj received. If blank insert blank into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else{
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }
        $query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            $new_row_id = $this->conn->insert_id;
            return $new_row_id;
            } else {
            return NULL;
        }
    }

    public function deleteFromTable($key, $value, $table_name) {
        $query = "DELETE FROM " . $table_name . " WHERE " . $key . " = '" . $value . "'";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
        return $r;
    }

    public function updateDataFromTable($query) {
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
        return $r;
    }

    public function getOwncloudSession($username, $password) {
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
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($c);
        curl_close($c);
        return $result; 
    }


    public function getSession() {
        if (!isset($_SESSION)) {
            session_start();
        }
        $sess = array();

        if(isset($_SESSION['name']))
        {
            $userExists = $this->getOwncloudSession($_SESSION['name'], $_SESSION['passwd']);
            if($userExists === 'true') {
                $sess["name"] = $_SESSION['name'];
            }
            else {
               $sess["name"] = "Guest";
            }
        }
        else {
            $sess["name"] = "Guest"; 
        }
        return $sess;
    }


    public function destroySession() {
        if (!isset($_SESSION)) {
        session_start();
        }
        if(isSet($_SESSION['name']))
        {
            unset($_SESSION['name']);
            $info='info';
            if(isSet($_COOKIE[$info]))
            {
                setcookie ($info, '', time() - $cookie_time);
            }
            $msg="Logged Out Successfully...";
        }
        else
        {
            $msg = "Not logged in...";
        }
        return $msg;
    }
 
}

?>
