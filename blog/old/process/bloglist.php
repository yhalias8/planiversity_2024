<?php
include_once('list.php');
$PATH = "blog-post";

$parms = "";
if (isset($_GET['category'])) {
    $parms .= "?category=" . $_GET['category'];
}

if (isset($_GET['search'])) {
    $parms .= "&search=" . $_GET['search'];
}

if (isset($_GET['page'])) {
    $parms .= "&page=" . $_GET['page'];
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

$responseList = processBlogData($returnValue, $FILE_PATH);

$output = [
    'responseList' => $responseList,
    'total_count' => $total_count,
    'next_page' => $after,
    'results' => $rData->data,
];

$output = array("data" => $output);

echo json_encode($output);
