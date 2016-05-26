<?php

$c = curl_init();
$url = "http://192.168.0.200:4243/containers/json?all=1";

curl_setopt($c, CURLOPT_URL, $url);

curl_setopt($c, CURLOPT_RETURNTRANSFER,1);

$temp=curl_exec($c);
$info = curl_getinfo($c);
curl_close($c);


$response = json_decode($temp);
$id = "80d29fc018ea574679d8793224c1dc6690a245e636413c502e05f9f31bfe640d";
foreach($response as $value) {
    if($value->Id == $id){
        $Ports = $value->Ports;
        for ($i = 0; $i < sizeof($Ports); $i++) {
        $privateport = $Ports[$i]->PrivatePort;
        if($privateport == '22') {
            $publicport = $Ports[$i]->PublicPort;
            break;
        }
        }
        break;

    }
   
}
echo $publicport;
