<?php
include_once('../config.ini.php');
include_once('list.php');
$PATH = "message/start";

if (isset($_POST['action_value'])) {
    $params = "";
    $action_value = $_POST['action_value'];
    $sender_id = $userdata['id'];

    $params = [
        'recipient_id' => $action_value,
        'sender_id' => $sender_id
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
