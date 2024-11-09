<?php
include_once('../../config.ini.php');
include_once('../list.php');
$PATH = "migration/status";

if (isset($_POST['did'])  && isset($_POST['flag'])) {
    $params = "";

    $did = $_POST['did'];
    $flag = $_POST['flag'];

    $params = [
        'migration_number' => $did,
        'status' => $flag,
    ];

    $fields = json_encode($params);

    $API_POST_PATH = $URL . $PATH;
    $mData = curlRequestPost($API_POST_PATH, $fields);
    $rData = json_decode($mData);

    $returnValue = $rData;

    http_response_code($returnValue->status);
    $output = [
        'data' => $returnValue,
    ];
    echo json_encode($output);
} else {
    http_response_code(422);
    $output = array(
        'status' => 422,
        'message' => "Fields is required",
    );
    echo json_encode($output);
}
