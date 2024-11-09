<?php
require_once '../../config.ini.php';
require_once '../../PayPal-PHP-SDK/autoload.php';
include 'mail_process.php';

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\WebProfile;
use PayPal\Api\InputFields;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;


if (isset($_POST['page_id']) && isset($_POST['payerID']) && isset($_POST['paymentID']) && isset($_POST['payment_type']) && isset($_POST['token_key']) && isset($_POST['coupon_id']) && isset($_POST['coupon_flag']) && isset($_POST['coupon_context'])) {

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(PAYPAL_CLIENT_ID, PAYPAL_CLIENT_SECRET)
    );
    
    $apiContext->setConfig(
        array(
            'log.LogEnabled' => true,
            'log.FileName' => 'PayPal.log',
            'log.LogLevel' => 'DEBUG',
            'mode' => 'live'
        )
    );    

    $paymentId = $_POST['paymentID'];
    $payerID = $_POST['payerID'];
    $payment_type = $_POST['payment_type'];
    $coupon_id = $_POST['coupon_id'];
    $coupon_flag = $_POST['coupon_flag'];
    $coupon_context = $_POST['coupon_context'];
    $team_member = $_POST['teamMembers'];
    $payment = Payment::get($paymentId, $apiContext);

    $execution = new PaymentExecution();
    // $execution->setPayerId($request->payerID);
    $execution->setPayerId($payerID);

    try {
        $result = $payment->execute($execution, $apiContext);
    } catch (Exception $ex) {
        echo $ex;
        exit(1);
    }

    $decode_result = json_decode($result);

    $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d") - 1, date("Y")));

    if ($payment_type == 'monthly') {
        $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m") + 1, date("d"), date("Y")));
    } elseif ($payment_type == 'one_time') {
        $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i') + 30, date('s'), date("m"), date("d"), date("Y")));
    } else {
        $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d"), date("Y") + 1));
    }

    if (!empty($coupon_id) && ($coupon_flag == 1) && ($payment_type == 'annual') && ($coupon_context == 1)) {
        $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d"), date("Y") + 100));
    }


    if ($result->state == 'approved' && empty($result->failed_transactions)) {

        $price = $decode_result->transactions[0]->amount->total;
        $currency = $decode_result->transactions[0]->amount->currency;
        $status = "succeeded";
        $date = date("Y-m-d H:i:s");
        $fname = $userdata['name'];
        $uid = $userdata['id'];

        $query = "INSERT INTO payments (id_user,transaction_id, fname, lname, country, address, city, state, zipcode, plan_type, payment_type, date_paid, date_expire, amount, ip_address, status,action_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $uid, PDO::PARAM_INT);
        $stmt->bindValue(2, $paymentId, PDO::PARAM_INT);
        $stmt->bindValue(3, $fname, PDO::PARAM_STR);
        $stmt->bindValue(4, "", PDO::PARAM_STR);
        $stmt->bindValue(5, "", PDO::PARAM_STR);
        $stmt->bindValue(6, "", PDO::PARAM_STR);
        $stmt->bindValue(7, "", PDO::PARAM_STR);
        $stmt->bindValue(8, "", PDO::PARAM_STR);
        $stmt->bindValue(9, "", PDO::PARAM_STR);
        $stmt->bindValue(10, $payment_type, PDO::PARAM_STR);
        $stmt->bindValue(11, "paypal", PDO::PARAM_STR);
        $stmt->bindValue(12, $date, PDO::PARAM_STR);
        $stmt->bindValue(13, $date_expire, PDO::PARAM_STR);
        $stmt->bindValue(14, $price, PDO::PARAM_STR);
        $stmt->bindValue(15, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindValue(16, $status, PDO::PARAM_STR);
        $stmt->bindValue(17, "payment", PDO::PARAM_STR);
        $stmt->execute();
        
        $stmt_another = $dbh->prepare("UPDATE users SET team_members=? WHERE id = ?");
        $stmt_another->bindValue(1, $team_member, PDO::PARAM_STR);
        $stmt_another->bindValue(2, $uid, PDO::PARAM_INT);
        $stmt_another->execute();        

        mailsendUser($auth, $userdata['email'], $userdata['name'], $status, $price, "Paypal Payment");
        mailSend($auth, $userdata['name'], "", $status, $price, "Paypal Payment");

        $response = array(
            "status" => 200,
            "message" => "successfully done",
            "type" => "payment",
            "transition_id" => $paymentId
        );
        http_response_code(200);
        
    } else {

        $response = array(
            'status' => 422,
            'message' => "failed occured",
            'transition_id' => "none"
        );
        http_response_code(422);
    }

    echo json_encode($response);
} else {

    header("Location: /");
}
