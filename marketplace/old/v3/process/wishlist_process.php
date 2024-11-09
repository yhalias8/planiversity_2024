<?php
include_once('list.php');
$PATH = "wishlist";

$parms = "";

$wish_id = $_POST['wish_id'];
$service_id = $_POST['service_id'];
$uuid = $_POST['uuid'];

$params = [
    'wish_id' => $wish_id,
    'service_id' => $service_id,
    'uuid' => $uuid,
];

//$params = array($params);

$fields = json_encode($params);

$API_GET_PATH = $URL . $PATH;

$mData = curlRequestPost($API_GET_PATH, $fields);
$rData = json_decode($mData);


//print_r($fields);

if (!empty($rData->data)) {
    $returnValue = $rData;
} else {
    $returnValue = [];
}

$output = [
    'results' => $returnValue,
];

//$output = array("data" => $output);

echo json_encode($output);
