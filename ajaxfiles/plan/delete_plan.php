<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
include("../../class/class.PlanTrip.php");
include("../../class/class.Timeline.php");

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['id']) && !empty($_POST['id'])) {


    $plan_id = $_POST['id'];
    $schedule_linked = $_POST['schedule_linked'];
    $schedule_id = $_POST['schedule_id'];

    $plantrip = new PlanTrip();

    $plantrip->del_data($plan_id);

    if ($schedule_linked == 1) {
        $timeline = new Timeline();
        $timeline->del_data($_POST['schedule_id']);
    }

    if ($plantrip->error) {

        $response = array(
            'status' => 422,
            'message' => "A system error has been encountered. Please try again",
        );

        http_response_code(422);
    } else {

        $response = array(
            "status" => 200,
            "message" => "Successfully Plan Deleted",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
