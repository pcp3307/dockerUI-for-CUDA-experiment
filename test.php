<?php

$c = curl_init();
$url = "http://192.168.0.200:4243/containers/json?all=1&fileter=id=af15ee25e";

curl_setopt($c, CURLOPT_URL, $url);

curl_setopt($c, CURLOPT_RETURNTRANSFER,1);

$temp=curl_exec($c);
curl_close($c);

$response = json_decode($temp);
echo json_encode($response[0]->Status);
