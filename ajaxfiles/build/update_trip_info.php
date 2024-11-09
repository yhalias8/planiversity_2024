<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';
include_once("../../class/class.Plan.php");
$plan = new Plan();


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['name_title'] != "" && $_POST['id']) {

    if ($userdata['account_type'] == 'Individual') {
        $user_payment_status = $plan->individual_check_plan($userdata['id']);
    } else {
        $user_payment_status = $plan->check_plan($userdata['id']);
    }


    if ($user_payment_status == 0) {

        $response = array(
            'status' => 422,
            'message' => "Your plan has expired. Please upgrade your plan to reactivate it.",
        );

        http_response_code(422);
    } else {

        $name = filter_var($_POST["name_title"], FILTER_SANITIZE_STRING);
        $id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);
        $localhost_full_path = filter_var($_POST["localhost_full_path"], FILTER_SANITIZE_STRING);


        if (!empty($localhost_full_path)) {
            update_full_path($dbh, $localhost_full_path, id);
        }


        $error = '';

        $query = "UPDATE trips SET title = ? WHERE id_trip = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $name, PDO::PARAM_STR);
        $stmt->bindValue(2, $id, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if (!$tmp) {
            $error = 'error_fail';
        }

        if ($error) {
            $response = array(
                'status' => 422,
                'message' => "A system error has been encountered. Please try again",
            );

            http_response_code(422);
        } else {

            $response = array(
                "status" => 200,
                "message" => "Successfully Process",
            );

            http_response_code(200);
        }
    }


    echo json_encode($response);
}


function update_full_path($dbh, $full_path, $id)
{

    $query = "UPDATE trips SET full_path = ? WHERE id_trip = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $full_path, PDO::PARAM_STR);
    $stmt->bindValue(2, $id, PDO::PARAM_INT);
    $tmp = $stmt->execute();
}
