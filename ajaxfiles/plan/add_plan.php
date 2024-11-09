<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
include("../../class/class.PlanTrip.php");
// include("../../class/class.Timeline.php");

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['plan_title'] && $_POST['plan_type'] && $_POST['plan_address'] && $_POST['location_to_lat'] && $_POST['location_to_lng'] && $_POST['plans_idtrip']) {

    $plantrip = new PlanTrip();

    $id_plan = filter_var($_POST["plan_id"], FILTER_SANITIZE_STRING);

    $plan_title = filter_var($_POST["plan_title"], FILTER_SANITIZE_STRING);
    $plan_type = filter_var($_POST["plan_type"], FILTER_SANITIZE_STRING);
    $plan_address = filter_var($_POST["plan_address"], FILTER_SANITIZE_STRING);
    $plan_lat = filter_var($_POST["location_to_lat"], FILTER_SANITIZE_STRING);
    $plan_lng = filter_var($_POST["location_to_lng"], FILTER_SANITIZE_STRING);
    $schedule_flag = filter_var($_POST["schedule_flag"], FILTER_SANITIZE_STRING);


    $id = filter_var($_POST["plans_idtrip"], FILTER_SANITIZE_STRING);
    $flag = 0;

    $date_time = null;

    if (!empty($_POST['event_date'])) {
        $date_time = $_POST['event_date'] . " " . $_POST['event_time'] ?? '';
        $date_time = date('Y-m-d H:i:s', strtotime($date_time));
    }

    $checkin_flag = filter_var($_POST["event_checkin"], FILTER_SANITIZE_STRING);

    $reservation_flag = filter_var($_POST["reservation"], FILTER_SANITIZE_STRING);
    $transportation_flag = filter_var($_POST["transportation"], FILTER_SANITIZE_STRING);


    $action = null;
    $id_place = null;
    $schedule_id = null;

    if (!empty($id_plan)) {
        $plantrip->edit_data($id_plan, $plan_title, $plan_type, $plan_address, $checkin_flag, $date_time, $plan_lat, $plan_lng, $reservation_flag, $transportation_flag);
        $action = 'Update';
        $id_place = $id_plan;
    } else {

        // if ($schedule_flag == 1) {
        //     $timeline = new Timeline();
        //     $flag = 1;

        //     $date_time = $_POST['event_date'] . " " . $_POST['event_time'];
        //     $date_time = date('Y-m-d H:i:s', strtotime($date_time));
        //     $schedule_id =  $timeline->put_data($id, $plan_title, $date_time, 0, 0);
        // }

        $plantrip->put_data($id, $plan_title, $plan_type, $plan_address, $checkin_flag, $date_time, $plan_lat, $plan_lng, $flag, $schedule_id, 0, $reservation_flag, $transportation_flag);
        $action = 'Added';
        $id_place = $plantrip->lastid;
    }

    if ($plantrip->error) {

        $response = array(
            'status' => 422,
            'action' => null,
            'flag' => $flag,
            "id" => $id_place,
            'message' => "A system error has been encountered. Please try again",
        );

        http_response_code(422);
    } else {

        $response = array(
            "status" => 200,
            "action" => $action,
            "flag" => $flag,
            "id" => $id_place,
            "message" => "Successfully Plan $action",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
