<?php

require_once '../../config.ini.php';

if (isset($_POST['page_id']) && isset($_POST['payment_type']) && isset($_POST['token_key'])  && isset($_POST['subscription_id'])) {


    $uid = $userdata['id'];
    $subscription_id = $_POST['subscription_id'];
    $subscription_gateway = "paypal";

    $stmt = $dbh->prepare("UPDATE users SET subscription_id=?,subscription_gateway=?,team_members=? WHERE id = ?");
    $stmt->bindValue(1, $subscription_id, PDO::PARAM_STR);
    $stmt->bindValue(2, $subscription_gateway, PDO::PARAM_STR);
    $stmt->bindValue(3, $team_member, PDO::PARAM_INT);
    $stmt->bindValue(4, $uid, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    if ($tmp) {

        $response = array(
            "status" => 200,
            "type" => "subscription",
            "id_value" => $subscription_id,
            "message" => "Subscription created successfully",
        );
    } else {
        http_response_code(422);
        $response = array(
            'status' => 422,
            'message' => "Transaction has been failed",
        );
    }

    echo json_encode($response);
}
