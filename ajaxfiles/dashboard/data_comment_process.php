<?php
include '../../config.ini.php';
include_once('list.php');

$PATH = "trip-info/comment";

if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_POST['commentfield']) && isset($_POST['id_trip'])) {
    $parms = "";

    $commentfield = $_POST['commentfield'];
    $id_trip = $_POST['id_trip'];

    $user_id = null;
    if ($auth->isLogged()) {
        $user_id = $userdata['id'];
    }

    $params = [
        'id_trip' => $id_trip,
        'commentfield' => $commentfield,
        'user_id' => $user_id,
    ];

    $fields = json_encode($params);

    $API_POST_PATH = $URL . $PATH;

    $mData = curlRequestPost($API_POST_PATH, $fields);
    $rData = json_decode($mData);

    if (!empty($rData)) {
        $trip_info = $rData;
    } else {
        $trip_info = [];
    }


    $output = array("data" => $trip_info);

    echo json_encode($output);
}
