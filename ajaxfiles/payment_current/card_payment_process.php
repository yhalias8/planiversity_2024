<?php
include '../../config.ini.php';
//include '../../config.ini.curl.php';
include 'mail_process.php';


// if (
//     !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
// ) {

if (isset($_POST['payment_type']) && isset($_POST['payment-option']) && isset($_POST['payment_cardnumber']) && isset($_POST['payment_expmonth']) && isset($_POST['payment_expyear']) && isset($_POST['payment_cvc']) && isset($_POST['stripeToken']) && isset($_POST['check']) && isset($_POST['date'])) {

    echo "1";
    //set api key
    $stripe = array(
        "secret_key"      => STRIPE_SECRET_KEY,
        "publishable_key" => STRIPE_PUBLISHABLE_KEY
    );

    \Stripe\Stripe::setApiKey($stripe['secret_key']);

    $payment_type = filter_var($_POST["payment_type"], FILTER_SANITIZE_STRING);
    $payment_option = filter_var($_POST["payment-option"], FILTER_SANITIZE_STRING);
    $coupon_id = filter_var($_POST["coupon_id"], FILTER_SANITIZE_STRING);
    $coupon_flag = filter_var($_POST["coupon_flag"], FILTER_SANITIZE_STRING);
    $token = filter_var($_POST["stripeToken"], FILTER_SANITIZE_STRING);

    $customer = \Stripe\Customer::create(array(
        'email' => $userdata['email'],
        'source' => $token
    ));

    //item information
    $itemName = "Planiversity.com - " . $payment_type . " plan";


    if ($userdata['account_type'] == 'Individual') {
        $plan = STRIPE_INDIVIDUAL_PLAN_ID;
        if ($_POST['payment_type'] == 'monthly')
            $price = 4.99;
        else
            $price = 49.99;
    } else {
        $plan = STRIPE_BUSINESS_PLAN_ID;
        if ($_POST['payment_type'] == 'monthly')
            $price = 24.99;
        else
            $price = 249.99;
    }





    if (!empty($coupon_id) && ($coupon_flag == 1)) {

        $stmt = $dbh->prepare("SELECT start_date,end_date,percent,stripe_individual_plan_id,stripe_business_plan_id FROM coupon WHERE sha1(id)=? and status=? and CURDATE()  >=  start_date  and  CURDATE() <= end_date ");
        $stmt->bindValue(1, $coupon_id, PDO::PARAM_STR);
        $stmt->bindValue(2, 'active', PDO::PARAM_STR);
        $tmp = $stmt->execute();
        $aux = '';
        $list = [];

        if ($tmp && $stmt->rowCount() > 0) {
            $list = $stmt->fetch(PDO::FETCH_OBJ);
            $price = number_format(($price - ($price * $list->percent / 100)), 2);

            if ($userdata['account_type'] == 'Individual') {
                $plan = $list->stripe_individual_plan_id;
            } else {
                $plan = $list->stripe_business_plan_id;
            }
        }
    }


    //echo json_encode($price);
    $itemPrice = str_replace(".", "", $price);
    $currency = "usd";
    $chargeJson = [];


    if ($payment_type == "monthly") {

        try {
            $subscription = \Stripe\Subscription::create(array(
                "customer" => $customer->id,
                "items" => array(
                    array(
                        //"plan" => STRIPE_SUBSCRIPTION_PLAN_ID,
                        "plan" => $plan,
                    ),
                ),
            ));


            $subsJson = $subscription->jsonSerialize();

            if ($subsJson['id']) {

                http_response_code(200);
                $response = array(
                    "status" => 200,
                    "type" => "subscription",
                    "id" => $subsJson['id'],
                    "message" => "Subscription has been made successfully",
                );
            } else {
                http_response_code(422);
                $response = array(
                    'status' => 422,
                    'message' => "Error Occured",
                );
            }
        } catch (Exception $e) {

            http_response_code(422);
            $response = array(
                'status' => 422,
                'message' => "Error Occured",
            );
        }
    } else {


        //charge a credit or a debit card
        $charge = \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $itemPrice,
            'currency' => $currency,
            'description' => $itemName,
        ));

        $chargeJson = $charge->jsonSerialize();

        //check whether the charge is successful
        if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {

            //order details 
            $amount = $chargeJson['amount'];
            $balance_transaction = $chargeJson['balance_transaction'];
            $currency = $chargeJson['currency'];
            $status = $chargeJson['status'];
            $date = date("Y-m-d H:i:s");
            $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d") - 1, date("Y")));
            $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d"), date("Y") + 1));

            $fname = filter_var($_POST["payment_fname"], FILTER_SANITIZE_STRING);
            $lname = filter_var($_POST["payment_lname"], FILTER_SANITIZE_STRING);
            $country = filter_var($_POST["payment_country"], FILTER_SANITIZE_STRING);
            $address = filter_var($_POST["payment_address"], FILTER_SANITIZE_STRING);
            $city = filter_var($_POST["payment_city"], FILTER_SANITIZE_STRING);
            $state = filter_var($_POST["payment_state"], FILTER_SANITIZE_STRING);
            $zcode = filter_var($_POST["payment_zipcode"], FILTER_SANITIZE_STRING);

            mailsendUser($auth, $userdata['email'], $fname, $status, $price, "Card Payment");
            mailSend($auth, $fname, $lname, $status, $price, "Stripe Payment");

            //insert tansaction data into the database
            $query = "INSERT INTO payments (id_user,transaction_id, fname, lname, country, address, city, state, zipcode, plan_type,payment_type, date_paid, date_expire, amount, status) VALUES (?, ? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
            $stmt->bindValue(14, $price, PDO::PARAM_STR);
            $stmt->bindValue(15, $status, PDO::PARAM_STR);
            $stmt->execute();

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
    }

    echo json_encode($response);
}
