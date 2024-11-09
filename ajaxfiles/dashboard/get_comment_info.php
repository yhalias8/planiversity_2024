<?php
include '../../config.ini.php';
include_once('list.php');

$PATH = "trip-info/comments";

if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_GET['id_trip'])) {
    $parms = "?trip_id=" . $_GET['id_trip'];

    $API_GET_PATH = $URL . $PATH . $parms;
    //$API_GET_PATH = 'https://www.planiversity.com/staging/trip_info.php' . $parms;

    $mData = curlRequestGet($API_GET_PATH);
    $rData = json_decode($mData);


    if (!empty($rData->data)) {
        $trip_info = $rData->data;
    } else {
        $trip_info = [];
    }


    $output = array("data" => $trip_info);

    echo json_encode($output);
}
