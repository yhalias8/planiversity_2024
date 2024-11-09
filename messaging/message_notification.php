<?php
include_once('../config.ini.php');
$uid = $userdata['id'];
include_once('list.php');
$PATH = "message/notification";

$parms = "";
$parms .= "?uuid=" . $userdata['id'];

$API_GET_PATH = $URL . $PATH . $parms;

$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);


if (!empty($rData->data->data)) {
    $returnValue = $rData->data->data;
} else {
    $returnValue = [];
    $after = null;
}

$output = [
    'results' => $rData->data,
];

$output = array("data" => $output);

echo json_encode($output);
