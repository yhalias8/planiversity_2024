<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['confirm_password']) {

    $uid = $userdata['id'];

    $options = [
        'cost' => 10
    ];

    $password = filter_var($_POST["confirm_password"], FILTER_SANITIZE_STRING);

    $current_hash = password_hash($password, PASSWORD_BCRYPT, $options);

    $error = '';

    $query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $current_hash, PDO::PARAM_STR);
    $stmt->bindValue(2, $uid, PDO::PARAM_INT);
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
            "message" => "Successfully Password Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
