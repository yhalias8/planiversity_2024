<?php
include_once("config.ini.php");
include("class/class.TripPlan.php");

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://aviation-edge.com/v2/public/flights?key=555cba-fc0abd&flightIata=W8519',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
