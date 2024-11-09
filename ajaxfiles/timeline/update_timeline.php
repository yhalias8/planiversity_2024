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


if ($_POST['timeline_name'] && $_POST['timeline_date'] && $_POST['timeline_time'] && $_POST['item_id']) {

    $timeline = new Timeline();

    $write_res = false;
    $file_name = '';
    $document_exists_in_files = isset($_FILES["timeline_document"]) && $_FILES["timeline_document"]["error"] == 0;
    $remove_document = !$document_exists_in_files && !isset($_POST['timeline_document']);

    if ($document_exists_in_files) {
        $validExtensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'docx', 'ppt', 'pptx');

        $file_name = $_FILES['timeline_document']['name'];
        $file_extn = strtolower(end(explode('.', $file_name)));
        $file_temp = $_FILES['timeline_document']['tmp_name'];

        $upload_dir = getRootPath() . 'ajaxfiles/uploads/';
        $file_name = rand(1000, 1000000) . '_' . $file_name;
        $final_path = $upload_dir . $file_name;

        if (in_array($file_extn, $validExtensions)) {
            $write_res = move_uploaded_file($file_temp, $final_path);
        }
    }

    $trip_checkin = $_POST['timeline_checkin'] ?? false;
    $trip_note = $_POST['timeline_note'] ?? '';

    $trip_doc_name = null;
    if ($write_res) {
        $trip_doc_name = $file_name;
    } else if ($remove_document) {
        $trip_doc_name = '';
    }

    $name = filter_var($_POST["timeline_name"], FILTER_SANITIZE_STRING);
    $date_time = $_POST['timeline_date'] . " " . $_POST['timeline_time'];
    $date_time = date('Y-m-d H:i:s', strtotime($date_time));
    $id = filter_var($_POST["item_id"], FILTER_SANITIZE_STRING);

    $trip_generated = $_POST["trip_generated"];
    $trip_u_id = $_POST["trip_u_id"];
    $trip_title = $_POST["trip_title"];

    $plan_linked_flag = $_POST["plan_linked_flag"];

    $timeline->edit_data($id, $name, $date_time, $trip_checkin, $trip_note, $trip_doc_name);

    if ($plan_linked_flag == 1) {
        $plantrip = new PlanTrip();
        $plantrip->modify_plan($id, $name);
    }

    if ($trip_generated == 1) {

        $data = [
            "UserId" => $trip_u_id,
            "NotificationTitle" => "Event Updated",
            "NotificationBody" => notificationBodyProcess("update", "event", $trip_title)
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
            "message" => "Successfully Schedule Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
