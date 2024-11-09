<?php

include '../../config.ini.php';


if (isset($_GET['email_trigger'])) {

    $email = $_GET['email_trigger'];

    $stmt = $dbh->prepare("SELECT name,email,mobile_no,picture,customer_number FROM users WHERE email=?");
    $stmt->bindValue(1, $email, PDO::PARAM_STR);
    $tmp = $stmt->execute();
    $aux = '';
    $timelines = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $timelines = $stmt->fetch(PDO::FETCH_OBJ);
    }

    if ($timelines) {

        $response = array(
            "data" => $timelines,
            "status" => 200,
            "message" => "Successfully Process Data",
        );

        http_response_code(200);
    } else {

        $response = array(
            'status' => 422,
            'message' => "No Data Found",
        );

        http_response_code(422);
    }



    echo json_encode($response);
}
