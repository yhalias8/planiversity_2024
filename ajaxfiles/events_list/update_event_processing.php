<?php
include '../../config.ini.php';

if (isset($_POST['title']) && isset($_POST['event_date_from']) && isset($_POST['event_date_to']) && isset($_POST['eid'])) {

    $e_deposit = 0;

    if (isset($_POST['e_deposit'])) {
        $e_deposit = $_POST['e_deposit'];
    }

    $title = filter_var($_POST["title"]);
    $event_date_from = date('Y-m-d', strtotime($_POST['event_date_from']));
    $event_date_to = date('Y-m-d', strtotime($_POST['event_date_to']));
    $event_time_from = filter_var($_POST["event_time_from"]);
    $event_time_to = filter_var($_POST["event_time_to"]);
    $customer_name = filter_var($_POST["customer_name"]);
    $customer_number = filter_var($_POST["customer_number"]);
    $address = filter_var($_POST["address"]);
    $location = filter_var($_POST["location"]);    
    $overview = filter_var($_POST["overview"]);
    $instructions = filter_var($_POST["instructions"]);    
    $deposit_amount = filter_var($_POST["deposit_amount"]);
    $id = filter_var($_POST["eid"]);


    $error = '';

    $query = "UPDATE events SET event_title = ?, customer_name = ?,customer_number = ?,customer_address = ?,event_time_from = ?,event_time_to = ?,event_location = ?,event_overview = ?,special_instructions = ?,deposit = ?,deposit_amount = ?,event_date_from = ?,event_date_to = ? WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $customer_name, PDO::PARAM_STR);
    $stmt->bindValue(3, $customer_number, PDO::PARAM_STR);
    $stmt->bindValue(4, $address, PDO::PARAM_STR);
    $stmt->bindValue(5, $event_time_from, PDO::PARAM_STR);
    $stmt->bindValue(6, $event_time_to, PDO::PARAM_STR);
    $stmt->bindValue(7, $location, PDO::PARAM_STR);
    $stmt->bindValue(8, $overview, PDO::PARAM_STR);
    $stmt->bindValue(9, $instructions, PDO::PARAM_STR);
    $stmt->bindValue(10, $e_deposit, PDO::PARAM_STR);
    $stmt->bindValue(11, $deposit_amount, PDO::PARAM_STR);
    $stmt->bindValue(12, $event_date_from, PDO::PARAM_STR);
    $stmt->bindValue(13, $event_date_to, PDO::PARAM_STR);    
    $stmt->bindValue(14, $id, PDO::PARAM_INT);
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
            "message" => "Successfully Event Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
