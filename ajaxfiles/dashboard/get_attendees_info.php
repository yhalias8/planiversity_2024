<?php
include '../../config.ini.php';
include_once('list.php');

$PATH = "trip-info/attendees";
if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_GET['id_trip'])) {
    $parms = "?trip_id=" . $_GET['id_trip'];

    $API_GET_PATH = $URL . $PATH . $parms;

    $mData = curlRequestGet($API_GET_PATH);
    $rData = json_decode($mData);



    if (!empty($rData->data)) {
        $trip_info = $rData->data->trip_info;
        $user_list = $rData->data->user_list;
        $user_count = $rData->data->user_count;
    } else {
        $trip_info = [];
        $user_list = [];
        $user_count = 0;
    }

    $output = [
        'trip_info' => $trip_info,
        'user_list' => $user_list,
        'user_count' => $user_count,
    ];


    $output = array("data" => $output);

    echo json_encode($output);
}
