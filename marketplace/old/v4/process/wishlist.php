<?php
include_once('list.php');
$PATH = "service/wishlist";

$parms = "";
if (isset($_GET['uuid'])) {
    $parms .= "?uuid=" . $_GET['uuid'];
}

$API_GET_PATH = $URL . $PATH . $parms;

$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);


if (!empty($rData->data->data)) {
    $returnValue = $rData->data->data;
    $after = 1;
} else {
    $returnValue = [];
    $after = null;
}

$responseList = processServiceData($returnValue, $FILE_PATH);

$output = [
    'responseList' => $responseList,
    'next_page' => $after,
    'results' => $rData->data,
];

$output = array("data" => $output);

echo json_encode($output);
