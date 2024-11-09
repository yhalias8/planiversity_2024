<?php
include_once('list.php');
$PATH = "blog/categories";
$SITE = SITE . "blog";

$API_GET_PATH = $URL . $PATH;

$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);

$value = "";
if (isset($_GET['category'])) {
    $value = $_GET['category'];
}


if (!empty($rData->data)) {
    $returnValue = $rData->data;
    $total_count = $rData->total_count;
} else {
    $returnValue = [];
    $total_count = 0;
}

$responseList = processBlogCategoryData($returnValue, $SITE, $value);

$output = [
    'list' => $responseList,
    'total_count' => $total_count,
    'message' => "Succeed",
];

$output = array("data" => $output);

echo json_encode($output);
