<?php
include_once('../../config.ini.php');
include_once('../list.php');
$PATH = "migration/process";

if (isset($_POST['migration_customer_number'])  && isset($_POST['migration_packet_number']) && isset($_POST['pid'])) {
    $params = "";

    $customer_number = $_POST['migration_customer_number'];
    $packet_number = $_POST['migration_packet_number'];
    $people_id = $_POST['pid'];
    $sender_id = $userdata['id'];

    $params = [
        'customer_number' => $customer_number,
        'packet_number' => $packet_number,
        'people_id' => $people_id,
        'user_id' => $sender_id
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
