<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_POST['group_name'])) {

    $group_name = $_POST['group_name'];
    $description = $_POST['description'];

    $user_id = null;
    if ($auth->isLogged()) {
        $user_id = $userdata['id'];
    }


    $query = "INSERT INTO travel_groups (group_name, description, user_id) VALUES (?, ?, ?)";
    $stmt = $dbh->prepare($query);

    $stmt->bindValue(1, $group_name, PDO::PARAM_STR);
    $stmt->bindValue(2, $description, PDO::PARAM_STR);
    $stmt->bindValue(3, $user_id, PDO::PARAM_INT);
    $tmp = $stmt->execute();

    $error = "";

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
            "message" => "Successfully Group Added",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
