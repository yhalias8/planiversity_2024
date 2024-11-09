<?php
include '../../config.ini.php';

if (isset($_POST['title']) && isset($_POST['event_time_from']) && isset($_POST['event_time_to']) && isset($_POST['eid'])) {


    $title = filter_var($_POST["title"]);
    $event_date = date('Y-m-d', strtotime($_POST['event_date']));
    $event_time_from = filter_var($_POST["event_time_from"]);
    $event_time_to = filter_var($_POST["event_time_to"]);
    $customer_name = filter_var($_POST["customer_name"]);        
    $location = filter_var($_POST["location"]);    
    $overview = filter_var($_POST["overview"]);
    $instructions = filter_var($_POST["instructions"]);        
    $id = filter_var($_POST["eid"]);


    $error = '';

    $query = "UPDATE events SET event_title = ?, customer_name = ?,event_time_from = ?,event_time_to = ?,event_location = ?,event_overview = ?,special_instructions = ?,event_date = ?,event_date_from = ?,event_date_to = ? WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $customer_name, PDO::PARAM_STR);        
    $stmt->bindValue(3, $event_time_from, PDO::PARAM_STR);
    $stmt->bindValue(4, $event_time_to, PDO::PARAM_STR);
    $stmt->bindValue(5, $location, PDO::PARAM_STR);
    $stmt->bindValue(6, $overview, PDO::PARAM_STR);
    $stmt->bindValue(7, $instructions, PDO::PARAM_STR);
    $stmt->bindValue(8, $event_date, PDO::PARAM_STR);
    $stmt->bindValue(9, $event_date, PDO::PARAM_STR);
    $stmt->bindValue(10, $event_date, PDO::PARAM_STR);    
    $stmt->bindValue(11, $id, PDO::PARAM_INT);
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
            "message" => "Successfully Meeting Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
