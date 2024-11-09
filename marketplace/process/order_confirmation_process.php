<?php
include_once('list.php');
$PATH = "order/";

$parms = "";
if (isset($_GET['order_number'])) {
    $parms .= $_GET['order_number'];
}

$flag = "wish_redirect";

$API_GET_PATH = $URL . $PATH . $parms;

$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);


if (!empty($rData->data)) {
    $returnValue = $rData->data;
} else {
    $returnValue = [];
}

$responseList = processOrderConfirmationData($returnValue);

$output = [
    'responseList' => $responseList,
];

$output = array("data" => $output);

echo json_encode($output);
