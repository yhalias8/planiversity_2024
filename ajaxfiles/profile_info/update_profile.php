<?php

include '../../config.ini.php';

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if ($_POST['email_address'] && $_POST['mobile_number']) {

    $uid = $userdata['id'];

    $email_address = filter_var($_POST["email_address"], FILTER_SANITIZE_STRING);
    $mobile_number = filter_var($_POST["mobile_number"], FILTER_SANITIZE_STRING);

    $error = '';

    $query = "UPDATE users SET email = ?, mobile_no = ?  WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $email_address, PDO::PARAM_STR);
    $stmt->bindValue(2, $mobile_number, PDO::PARAM_STR);
    $stmt->bindValue(3, $uid, PDO::PARAM_INT);
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
            "message" => "Successfully Profile Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
