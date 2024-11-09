<?php

include '../../config.ini.php';


if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}


if (isset($_POST['title']) && isset($_POST['start_date']) && isset($_POST['coupon_code']) && isset($_POST['end_date']) && isset($_POST['percent']) && isset($_POST['status']) && isset($_POST['stripe_individual_plan_id']) && isset($_POST['stripe_business_plan_id']) && isset($_POST['paypal_individual_plan_id']) && isset($_POST['paypal_business_plan_id']) && isset($_POST['id'])) {


    $title = filter_var($_POST["title"], FILTER_SANITIZE_STRING);
    $start_date = filter_var($_POST["start_date"], FILTER_SANITIZE_STRING);
    $coupon_code = filter_var($_POST["coupon_code"], FILTER_SANITIZE_STRING);
    $end_date = filter_var($_POST["end_date"], FILTER_SANITIZE_STRING);
    $percent = filter_var($_POST["percent"], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST["status"], FILTER_SANITIZE_STRING);
    $stripe_individual_plan_id = filter_var($_POST["stripe_individual_plan_id"], FILTER_SANITIZE_STRING);
    $stripe_business_plan_id = filter_var($_POST["stripe_business_plan_id"], FILTER_SANITIZE_STRING);
    $paypal_individual_plan_id = filter_var($_POST["paypal_individual_plan_id"], FILTER_SANITIZE_STRING);
    $paypal_business_plan_id = filter_var($_POST["paypal_business_plan_id"], FILTER_SANITIZE_STRING);    
    $id = filter_var($_POST["id"], FILTER_SANITIZE_STRING);

    $error = '';

    $query = "UPDATE coupon SET title = ?, coupon_code = ?,percent = ?,start_date = ?,end_date = ?,status = ?,stripe_individual_plan_id = ?,stripe_business_plan_id = ?,paypal_individual_plan_id = ?,paypal_business_plan_id = ? WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $title, PDO::PARAM_STR);
    $stmt->bindValue(2, $coupon_code, PDO::PARAM_STR);
    $stmt->bindValue(3, $percent, PDO::PARAM_INT);
    $stmt->bindValue(4, $start_date, PDO::PARAM_STR);
    $stmt->bindValue(5, $end_date, PDO::PARAM_STR);
    $stmt->bindValue(6, $status, PDO::PARAM_STR);
    $stmt->bindValue(7, $stripe_individual_plan_id, PDO::PARAM_STR);
    $stmt->bindValue(8, $stripe_business_plan_id, PDO::PARAM_STR);
    $stmt->bindValue(9, $paypal_individual_plan_id, PDO::PARAM_STR);
    $stmt->bindValue(10, $paypal_business_plan_id, PDO::PARAM_STR);    
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
            "message" => "Successfully Coupon Updated",
        );

        http_response_code(200);
    }

    echo json_encode($response);
}
