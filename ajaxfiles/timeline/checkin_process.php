<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
include("../../class/class.Timeline.php");
include("../../class/class.PlanTrip.php");
include("../list_process.php");

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}

if (isset($_POST['id']) && !empty($_POST['id'])) {

    $timeline = new Timeline();

    $date_time = date('Y-m-d H:i:s'); // Get the current datetime

    $id = $_POST["id"];
    $title = $_POST["title"];
    $trip_generated = $_POST["trip_generated"];
    $trip_u_id = $_POST["trip_u_id"];
    $trip_title = $_POST["trip_title"];

    $hold = $timeline->checkin_process($id, $date_time);

    if ($trip_generated == 1) {

        $data = [
            "UserId" => $trip_u_id,
            "NotificationTitle" => "Event Check in",
            "NotificationBody" => notificationBodyProcess("checkin", "$title event", $trip_title)
        ];

        $fields = json_encode($data);

        $mData = curlRequestPost($API_URL, $TOKEN, $fields);
    }

    if ($timeline->error) {

        $response = array(
            'status' => 422,
            'message' => "A system error has been encountered. Please try again",
        );

        http_response_code(422);
    } else {

        $response = array(
            "status" => 200,
            "message" => "Schedule Successfully Checked in",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
