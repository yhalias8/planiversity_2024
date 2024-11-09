<?php
include_once('list.php');
$PATH = "category";
$API_GET_PATH = $URL . $PATH;


$mData = curlRequestGet($API_GET_PATH);
$rData = json_decode($mData);


if (!empty($rData->data)) {
    $returnValue = $rData->data;
} else {
    $returnValue = [];
}

//print_r($returnValue);

$responseList = processCategoryData($returnValue, $FILE_PATH);

$output = [
    'list' => $responseList,
    'message' => "Succeed",
];

$output = array("data" => $output);

echo json_encode($output);
