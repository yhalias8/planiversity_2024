<?php
include '../../config.ini.php';
//include '../../config.ini.curl.php';
include 'mail_process.php';


if (isset($_POST['payment_type']) && isset($_POST['payment-option']) && isset($_POST['check']) && isset($_POST['date'])) {


    $saved_agree = 0;

    if (isset($_POST['saved_agree'])) {
        $saved_agree = $_POST['saved_agree'];
    }

    //set api key
    $stripe = array(
        "secret_key"      => STRIPE_SECRET_KEY,
        "publishable_key" => STRIPE_PUBLISHABLE_KEY
    );


    $payment_type = filter_var($_POST["payment_type"], FILTER_SANITIZE_STRING);
    $payment_option = filter_var($_POST["payment-option"], FILTER_SANITIZE_STRING);
    $coupon_id = filter_var($_POST["coupon_id"], FILTER_SANITIZE_STRING);
    $coupon_flag = filter_var($_POST["coupon_flag"], FILTER_SANITIZE_STRING);
    $coupon_context = filter_var($_POST["coupon_context"], FILTER_SANITIZE_STRING);

    if ($userdata['account_type'] == 'Individual') {
        if ($payment_type == 'monthly')
            $price = 10.99;
        else if ($payment_type == 'one_time')
            $price = 4.99;
        else
            $price = 120.00;
    } else {
        if ($payment_type == 'monthly')
            $price = 49.99;
        else
            $price = 549;
    }


    $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d") - 1, date("Y")));

    if ($payment_type == 'monthly') {
        $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m") + 1, date("d"), date("Y")));
    } elseif ($payment_type == 'one_time') {
        $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i') + 30, date('s'), date("m"), date("d"), date("Y")));
    } else {
        $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d"), date("Y") + 1));
    }


    if (!empty($coupon_id) && ($coupon_flag == 1)) {

        $mt = $dbh->prepare("SELECT percent,lifetime FROM coupon WHERE sha1(id)=? and status=? and CURDATE()  >=  start_date  and  CURDATE() <= end_date ");
        $mt->bindValue(1, $coupon_id, PDO::PARAM_STR);
        $mt->bindValue(2, 'active', PDO::PARAM_STR);
        $tp = $mt->execute();
        $aux = '';
        $list = [];

        if ($tp && $mt->rowCount() > 0) {
            $list = $mt->fetch(PDO::FETCH_OBJ);
            $price = number_format(($price - ($price * $list->percent / 100)), 2);

            if (($payment_type == 'annual') && ($list->lifetime == 1)) {
                $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d"), date("Y") + 100));
            }
        }
    }




    //check whether the charge is successful
    if ($price) {

        //order details 
        $amount = 1;
        $balance_transaction = rand();
        $currency = "usd";
        $status = "succeeded";
        $date = date("Y-m-d H:i:s");

        $fname = filter_var($_POST["payment_fname"], FILTER_SANITIZE_STRING);
        $lname = filter_var($_POST["payment_lname"], FILTER_SANITIZE_STRING);
        $country = filter_var($_POST["payment_country"], FILTER_SANITIZE_STRING);
        $address = filter_var($_POST["payment_address"], FILTER_SANITIZE_STRING);
        $city = filter_var($_POST["payment_city"], FILTER_SANITIZE_STRING);
        $state = filter_var($_POST["payment_state"], FILTER_SANITIZE_STRING);
        $zcode = filter_var($_POST["payment_zipcode"], FILTER_SANITIZE_STRING);

        //insert tansaction data into the database
        $query = "INSERT INTO payments (id_user,transaction_id, fname, lname, country, address, city, state, zipcode, plan_type, payment_type, date_paid, date_expire, amount, ip_address, status, save_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
        $stmt->bindValue(2, $balance_transaction, PDO::PARAM_INT);
        $stmt->bindValue(3, $fname, PDO::PARAM_STR);
        $stmt->bindValue(4, $lname, PDO::PARAM_STR);
        $stmt->bindValue(5, $country, PDO::PARAM_STR);
        $stmt->bindValue(6, $address, PDO::PARAM_STR);
        $stmt->bindValue(7, $city, PDO::PARAM_STR);
        $stmt->bindValue(8, $state, PDO::PARAM_STR);
        $stmt->bindValue(9, $zcode, PDO::PARAM_STR);
        $stmt->bindValue(10, $payment_type, PDO::PARAM_STR);
        $stmt->bindValue(11, "stripe", PDO::PARAM_STR);
        $stmt->bindValue(12, $date, PDO::PARAM_STR);
        $stmt->bindValue(13, $date_expire, PDO::PARAM_STR);
        $stmt->bindValue(14, $amount, PDO::PARAM_STR);
        $stmt->bindValue(15, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $stmt->bindValue(16, $status, PDO::PARAM_STR);
        $stmt->bindValue(17, $saved_agree, PDO::PARAM_INT);
        $stmt->execute();

        mailsendUser($auth, $userdata['email'], $fname, $status, $price, "Card Payment");
        mailSend($auth, $fname, $lname, $status, $price, "Stripe Payment");

        http_response_code(200);

        $response = array(
            "status" => 200,
            "type" => "payment",
            "id" => $balance_transaction,
            "message" => "Payment has been made successfully",
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
