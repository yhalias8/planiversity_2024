<?php

include '../../config.ini.php';
include '../../config.ini.curl.php';


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['flag'] != "" && $_POST['id']) {


    $flag = filter_var($_POST["flag"], FILTER_SANITIZE_STRING);
    $image_url = filter_var($_POST["image_url"], FILTER_SANITIZE_STRING);
    $id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);

    $error = '';

    $query = "UPDATE trips SET cover_image = ?, cover_image_url = ?,cover_image_type = ?  WHERE id_trip = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $flag, PDO::PARAM_INT);
    $stmt->bindValue(2, $image_url, PDO::PARAM_STR);
    $stmt->bindValue(3, 0, PDO::PARAM_INT);
    $stmt->bindValue(4, $id, PDO::PARAM_INT);
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
            "message" => "Successfully Cover Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
