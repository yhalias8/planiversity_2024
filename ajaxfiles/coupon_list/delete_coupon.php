<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['id']) && !empty($_POST['id'])) {


    $id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);

    $error = '';

    $query = "DELETE FROM coupon WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $id, PDO::PARAM_INT);
    $tmp = $stmt->execute();

    if (!$tmp) {
        $error = 'error_fail';
    }

    if ($error) {

        http_response_code(422);
        $response = array(
            'status' => 422,
            'message' => "A system error has been encountered. Please try again",
        );
    } else {

        $response = array(
            "status" => 200,
            "message" => "Successfully Plan Deleted",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
