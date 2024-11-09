<?php
include_once('list.php');
$PATH = "service/";

$parms = "";
if (isset($_GET['service_uuid'])) {
    $parms .= $_GET['service_uuid'];
}

$API_GET_PATH = $URL . $PATH . $parms;

$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);

if (!empty($rData->data)) {
    $returnValue = $rData->data;
    $service_price = price_calculation($rData->data);
} else {
    $returnValue = [];
    $service_price = null;
}

$responseList = processOrderData($returnValue, $FILE_PATH);

$output = [
    'responseList' => $responseList,
    'service_price' => $service_price,
];

$output = array("data" => $output);

echo json_encode($output);
