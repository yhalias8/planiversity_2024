<?php
include_once('../config.ini.php');
include_once('list.php');
$PATH = "message/process";

if (isset($_POST['conversation_id'])  && isset($_POST['message'])) {
    $params = "";

    $conversation_id = $_POST['conversation_id'];
    $message = $_POST['message'];
    $sender_id = $userdata['id'];

    $params = [
        'conversation_id' => $conversation_id,
        'message' => $message,
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
