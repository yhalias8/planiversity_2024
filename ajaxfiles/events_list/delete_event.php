<?php

include '../../config.ini.php';
include '../../class/class.Googlecalendar.php';
include '../../class/class.MicrosoftGraph.php';


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['id']) && !empty($_POST['id'])) {

    $integration_id = $dbh->prepare("SELECT gcalendar_id, outlook_event_id FROM events WHERE id=?");
    $integration_id->bindValue(1, $_POST['id'], PDO::PARAM_INT);
    $tmp1 = $integration_id->execute();
    $data_id = [];
    $data_id = $integration_id->fetch(PDO::FETCH_OBJ);
    $data_id->gcalendar_id;
    $data_id->outlook_event_id;


    $integration = $dbh->prepare("SELECT sync_googlecalendar, sync_outlookcalendar FROM users WHERE id=?");
    $integration->bindValue(1, $userdata['id'], PDO::PARAM_INT);
    $tmp2 = $integration->execute();
    $data = [];
    $data = $integration->fetch(PDO::FETCH_OBJ);
    $data->sync_googlecalendar;
    $data->sync_outlookcalendar;

    if ($data->sync_googlecalendar && $data_id->gcalendar_id) {
        $googleCalendarAPI = new GoogleCalendar();
        $googleCalendarAPI->deleteData($data_id->gcalendar_id);
        // echo 'test 1';
    }

    if ($data->sync_outlookcalendar && $data_id->outlook_event_id) {
        $googleCalendarAPI = new MicrosoftGraph();
        $googleCalendarAPI->deleteData($data_id->outlook_event_id);
        // echo 'test 2';
    }

    $tmp = '';
    $error = '';
    $query = "DELETE FROM events WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $_POST['id'], PDO::PARAM_INT);
    $tmp = $stmt->execute();

    if (!$tmp) {

        $response = array(
            'status' => 422,
            'message' => "A system error has been encountered. Please try again",
        );

        http_response_code(422);
        echo json_encode($response);
    } else {

        $response = array(
            "status" => 200,
            "message" => "Successfully Event Deleted",
        );

        http_response_code(200);
        echo json_encode($response);
    }
    }

