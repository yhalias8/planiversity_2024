<?php
include_once('list.php');
$PATH = "service";

$parms = "";
if (isset($_GET['category'])) {
    $parms .= "?category=" . $_GET['category'];
}

if (isset($_GET['lat'])) {
    $parms .= "&lat=" . $_GET['lat'];
}

if (isset($_GET['lng'])) {
    $parms .= "&lng=" . $_GET['lng'];
}

if (isset($_GET['search'])) {
    $parms .= "&search=" . $_GET['search'];
}

if (isset($_GET['page'])) {
    $parms .= "&page=" . $_GET['page'];
}

if (isset($_GET['uuid'])) {
    $parms .= "&uuid=" . $_GET['uuid'];
}


$API_GET_PATH = $URL . $PATH . $parms;

$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);


if (!empty($rData->data->data)) {
    $returnValue = $rData->data->data;
    $after = $_GET['page'] + 1;
    $total_count = $rData->total_count;
} else {
    $returnValue = [];
    $after = null;
    $total_count = 0;
}

$responseList = processServiceData($returnValue, $FILE_PATH);

$output = [
    'responseList' => $responseList,
    'total_count' => $total_count,
    'next_page' => $after,
    'results' => $rData->data,
];

$output = array("data" => $output);

echo json_encode($output);
