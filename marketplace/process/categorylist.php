<?php
include_once('list.php');
$PATH = "category";
$API_GET_PATH = $URL . $PATH;


$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);


if (!empty($rData->data)) {
    $returnValue = $rData->data;
    $total_count = $rData->total_count;
} else {
    $returnValue = [];
    $total_count = 0;
}

//print_r($returnValue);

$responseList = processCategoryData($returnValue, $FILE_PATH);

$output = [
    'list' => $responseList,
    'total_count' => $total_count,
    'message' => "Succeed",
];

$output = array("data" => $output);

echo json_encode($output);
