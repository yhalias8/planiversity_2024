<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
include("../../class/class.Timeline.php");
include("../../class/class.PlanTrip.php");
include("../list_process.php");
include("../../includes/helpers.php");

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['event_name'] && $_POST['event_date'] && $_POST['event_time'] && $_POST['timeline_idtrip']) {

    $timeline = new Timeline();

    $writeRes = false;
    $file_name = '';

    if (isset($_FILES["trip_document"]) && $_FILES["trip_document"]["error"] == 0) {
        $validExtensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'docx', 'ppt', 'pptx');
        $_tmp = 'ajaxfiles/uploads/';

        $file_name = $_FILES['trip_document']['name'];
        $file_extn = strtolower(end(explode('.', $file_name)));
        $file_temp = $_FILES['trip_document']['tmp_name'];

        $upload_dir = getRootPath() . $_tmp;

        $file_name = rand(1000, 1000000) . '_' . $file_name;
        $final_path = $upload_dir . $file_name;

        if (in_array($file_extn, $validExtensions)) {
            $writeRes = move_uploaded_file($file_temp, $final_path);
        }
    }

    $name = filter_var($_POST["event_name"], FILTER_SANITIZE_STRING);
    $date_time = $_POST['event_date'] . " " . $_POST['event_time'];
    $date_time = date('Y-m-d H:i:s', strtotime($date_time));
    $id = filter_var($_POST["timeline_idtrip"], FILTER_SANITIZE_STRING);

    $trip_generated = $_POST["trip_generated"];
    $trip_u_id = $_POST["trip_u_id"];
    $trip_title = $_POST["trip_title"];

    $trip_note = $_POST['trip_note'] ?? null;
    $trip_doc_name = null;
    if ($writeRes && $file_name) $trip_doc_name = $file_name;

    $plan_linked = $_POST["plan_linked"];
    $checkin_flag = $_POST["checkin_flag"];
    $lat_to = $_POST["lat_to"];
    $lng_to = $_POST["lng_to"];
    $event_address = $_POST["event_address"];

    $last_id = $timeline->put_data($id, $name, $date_time, $plan_linked, $checkin_flag, $trip_note, $trip_doc_name);

    if ($plan_linked == 1) {
        $plan_type = "Things to do";
        $flag = 1;
        $plantrip = new PlanTrip();
        $checkin_flag = $checkin_flag ? 'true' : 'false';
        $plantrip->put_data($id, $name, $plan_type, $event_address, $checkin_flag, $date_time, $lat_to, $lng_to, $flag, $last_id, $flag);
    }

    if ($trip_generated == 1) {

        $data = [
            "UserId" => $trip_u_id,
            "NotificationTitle" => "New Event Added",
            "NotificationBody" => notificationBodyProcess("add", "event", $trip_title)
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
            "id" => $last_id,
            "message" => "Successfully Schedule Added",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
