<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    header('location: ../../');
}

if (isset($_POST['group_name']) && isset($_POST['id'])) {

    $group_name = $_POST['group_name'];
    $description = $_POST['description'];
    $id = $_POST['id'];

    $query = "UPDATE travel_groups SET group_name = ?, description = ? WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $group_name, PDO::PARAM_STR);
    $stmt->bindValue(2, $description, PDO::PARAM_STR);
    $stmt->bindValue(3, $id, PDO::PARAM_INT);
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
            "message" => "Successfully Group Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
