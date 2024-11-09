<?php
include '../../config.ini.php';

if (isset($_POST['job_name']) && isset($_POST['job_category']) && isset($_POST['job_details'])) {


    $title = filter_var($_POST["job_name"]);
    $job_category = filter_var($_POST["job_category"]);
    $job_details = filter_var($_POST["job_details"]);
    $job_cnumbers = filter_var($_POST["job_cnumbers"]);        
    $job_address = filter_var($_POST["job_address"]);    
    $job_city = filter_var($_POST["job_city"]);
    $job_state = filter_var($_POST["job_state"]);        
    $job_zcode = filter_var($_POST["job_zcode"]);        
    $id = filter_var($_POST["eid"]);


    $error = '';

    $query = "UPDATE jobs SET name = ?, category = ?,details = ?,contact_number = ?,address = ?,city = ?,state = ?,zip_code = ? WHERE id_job = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $job_category, PDO::PARAM_STR);        
    $stmt->bindValue(3, $job_details, PDO::PARAM_STR);
    $stmt->bindValue(4, $job_cnumbers, PDO::PARAM_STR);
    $stmt->bindValue(5, $job_address, PDO::PARAM_STR);
    $stmt->bindValue(6, $job_city, PDO::PARAM_STR);
    $stmt->bindValue(7, $job_state, PDO::PARAM_STR);
    $stmt->bindValue(8, $job_zcode, PDO::PARAM_STR);   
    $stmt->bindValue(9, $id, PDO::PARAM_INT);
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
            "message" => "Successfully Job Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
