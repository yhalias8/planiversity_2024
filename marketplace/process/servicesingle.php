<?php
include_once('list.php');
$PATH = "service/";

$parms = "";
$member = 0;
if (isset($_GET['service_uuid'])) {
    $parms .= $_GET['service_uuid'];
}

if (isset($_GET['member'])) {
    $member = $_GET['member'];
}

$API_GET_PATH = $URL . $PATH . $parms;

$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);

if (!empty($rData->data)) {
    $returnValue = $rData->data;
    $service_price = price_calculation($rData->data, $member);
    $popup_activation = $rData->data->popup_active;
} else {
    $returnValue = [];
    $service_price = null;
    $popup_activation = 0;
}

$responseList = processOrderData($returnValue, $FILE_PATH, $member);

$output = [
    'responseList' => $responseList,
    'service_price' => $service_price,
    'popup_activation' => $popup_activation,
];

$output = array("data" => $output);

echo json_encode($output);
