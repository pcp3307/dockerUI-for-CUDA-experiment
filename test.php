<?php

$c = curl_init();
$url = "http://192.168.1.104:4243/containers/json?all=1&fileter='id=488ea6'";

curl_setopt($c, CURLOPT_URL, $url);

curl_setopt($c, CURLOPT_RETURNTRANSFER,1);

$temp=curl_exec($c);
curl_close($c);

$response = json_decode($temp);
echo json_encode($response[0]);
