<?php
include_once('../config.ini.php');
include_once('list.php');
$PATH = "message/head";
$parms = "";
$parms .= "?uuid=" . $userdata['id'];

if (isset($_GET['page'])) {
    $parms .= "&page=" . $_GET['page'];
}

$API_GET_PATH = $URL . $PATH . $parms;

$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);

if (!empty($rData->data->data)) {
    $returnValue = $rData->data->data;
    $after = $_GET['page'] + 1;
} else {
    $returnValue = [];
    $after = null;
}

$responseList = processHeadData($returnValue, $FILE_PATH);

$output = [
    'responseList' => $responseList,
    'next_page' => $after,
    'results' => $rData->data,
];

$output = array("data" => $output);

echo json_encode($output);
