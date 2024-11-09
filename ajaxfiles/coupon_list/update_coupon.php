<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['title']) && isset($_POST['start_date']) && isset($_POST['coupon_code']) && isset($_POST['end_date']) && isset($_POST['percent']) && isset($_POST['status']) && isset($_POST['access']) && isset($_POST['auth_level'])) {


    $title = filter_var($_POST["title"], FILTER_SANITIZE_STRING);
    $start_date = filter_var($_POST["start_date"], FILTER_SANITIZE_STRING);
    $coupon_code = filter_var($_POST["coupon_code"], FILTER_SANITIZE_STRING);
    $end_date = filter_var($_POST["end_date"], FILTER_SANITIZE_STRING);
    $percent = filter_var($_POST["percent"], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST["status"], FILTER_SANITIZE_STRING);
    $access = filter_var($_POST["access"], FILTER_SANITIZE_STRING);
    $auth_level = filter_var($_POST["auth_level"], FILTER_SANITIZE_STRING);
    $plan_level = filter_var($_POST["plan_level"], FILTER_SANITIZE_STRING);
    $bulk = filter_var($_POST["bulk"], FILTER_SANITIZE_STRING);
    $prefix = filter_var($_POST["prefix"], FILTER_SANITIZE_STRING);
    $postfix = filter_var($_POST["postfix"], FILTER_SANITIZE_STRING);
    $id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);
    $bulk = filter_var($_POST["bulk"], FILTER_SANITIZE_STRING);
    $prefix = filter_var($_POST["prefix"], FILTER_SANITIZE_STRING);
    $postfix = filter_var($_POST["postfix"], FILTER_SANITIZE_STRING);

    if ($bulk == 0) {
        $prefix = null;
        $postfix = null;
    }


    $error = '';

    $query = "UPDATE coupon SET title = ?, coupon_code = ?,percent = ?,start_date = ?,end_date = ?,status = ?,lifetime = ?,target_auth_level = ?,bulk_coupon = ?,coupon_prefix = ?,coupon_postfix = ?,target_plan_level = ? WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $coupon_code, PDO::PARAM_STR);
    $stmt->bindValue(3, $percent, PDO::PARAM_INT);
    $stmt->bindValue(4, $start_date, PDO::PARAM_STR);
    $stmt->bindValue(5, $end_date, PDO::PARAM_STR);
    $stmt->bindValue(6, $status, PDO::PARAM_STR);
    $stmt->bindValue(7, $access, PDO::PARAM_STR);
    $stmt->bindValue(8, $auth_level, PDO::PARAM_STR);
    $stmt->bindValue(9, $bulk, PDO::PARAM_INT);
    $stmt->bindValue(10, $prefix, PDO::PARAM_STR);
    $stmt->bindValue(11, $postfix, PDO::PARAM_STR);
    $stmt->bindValue(12, $plan_level, PDO::PARAM_STR);
    $stmt->bindValue(13, $id, PDO::PARAM_INT);
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
            "message" => "Successfully Coupon Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
