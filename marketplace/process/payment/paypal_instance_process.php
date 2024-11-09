<?php
include_once('../list.php');
$PATH = "payment/paypal/instance-create";

if (isset($_POST['token']) && isset($_POST['service_uuid'])) {

    $params = "";
    $token = $_POST['token'];
    $service_uuid = $_POST['service_uuid'];
    $uuid = $_POST['uuid'];
    $u_number = $_POST['u_number'];

    $params = [
        'token' => $token,
        'service_uuid' => $service_uuid,
        'uuid' => $uuid,
        'u_number' => $u_number,
    ];

    $fields = json_encode($params);
    

    $API_GET_PATH = $URL . $PATH;

    $mData = curlRequestPost($API_GET_PATH, $fields);
    $rData = json_decode($mData);

    // if (!empty($rData->data)) {
    //     $returnValue = $rData;
    // } else {
    //     $returnValue = [];
    // }

    $returnValue = $rData;

    //http_response_code($returnValue->status);
    $output = [
        'results' => $returnValue,
    ];
    echo json_encode($output);
} else {

    http_response_code(422);
    $output = array(
        'status' => 422,
        'message' => "All fields is required",
    );
    echo json_encode($output);
}

//$output = array("data" => $output);
