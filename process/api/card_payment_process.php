<?php
include '../../config.ini.php';
//include '../../config.ini.curl.php';
include 'list.php';


// if (
//     !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
// ) {

if (isset($_POST['payment_fname']) && isset($_POST['payment_lname']) && isset($_POST['payment_email']) && isset($_POST['token_key']) && isset($_POST['off_request_id']) && isset($_POST['off_id']) && isset($_POST['stripeToken']) && isset($_POST['check']) && isset($_POST['date'])) {


    //set api key
    $stripe = array(
        "secret_key"      => STRIPE_SECRET_KEY,
        "publishable_key" => STRIPE_PUBLISHABLE_KEY
    );

    \Stripe\Stripe::setApiKey($stripe['secret_key']);

    $payment_fname = filter_var($_POST["payment_fname"], FILTER_SANITIZE_STRING);
    $payment_lname = filter_var($_POST["payment_lname"], FILTER_SANITIZE_STRING);
    $payment_email = filter_var($_POST["payment_email"], FILTER_SANITIZE_STRING);
    $off_request_id = filter_var($_POST["off_request_id"], FILTER_SANITIZE_STRING);
    $off_id = filter_var($_POST["off_id"], FILTER_SANITIZE_STRING);
    $token = filter_var($_POST["stripeToken"], FILTER_SANITIZE_STRING);

    //print_r($_POST);

    //die();

    $customer = \Stripe\Customer::create(array(
        'email' => $payment_email,
        'source' => $token
    ));


    $url = "offers/" . $off_id;

    $API_GET_PATH = $API_PATH . $url;

    $mData = curlRequestGet($API_GET_PATH, $TOKEN);
    $rData = json_decode($mData);
    $singleNode = $rData->data->slices[0];

    // echo "<pre>";

    // print_r($rData);


    //item information
    $itemName = "Planiversity.com | Flight Payment - " . $off_id;


    $price = price_calculate($rData->data->total_amount);

    //echo json_encode($price);
    $itemPrice = str_replace(".", "", $price);
    $currency = "usd";
    $chargeJson = [];

    //charge a credit or a debit card
    $charge = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => $itemPrice,
        'currency' => $currency,
        'description' => $itemName,
    ));

    $chargeJson = $charge->jsonSerialize();

    $params = [
        //"type" => 'balance',
        "currency" => $rData->data->total_currency,
        "amount" => $price
    ];

    $params = array("data" => $params);

    $fields = json_encode($params);

    print_r($fields);

    $API_POST_PATH = $API_PATH . "payments/payment_intents";

    //check whether the charge is successful
    if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {

        //order details 
        $amount = $chargeJson['amount'];
        $balance_transaction = $chargeJson['balance_transaction'];
        $currency = $chargeJson['currency'];
        $status = $chargeJson['status'];
        $date = date("Y-m-d H:i:s");


        $mData = curlRequestPost($API_POST_PATH, $TOKEN, $fields);

        echo "<pre>";
        print_r($mData);

        if ($mData) {

            $query = "INSERT INTO flight_booking (offer_request_id,offer_id,payment_transaction_id, first_name, last_name, email, amount , date_paid, ip_address,origin_address,origin_lat,origin_long,destination_address,destination_lat,destination_long,trip_mode,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?, ?, ?, ?, ? , ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $off_request_id, PDO::PARAM_STR);
            $stmt->bindValue(2, $off_id, PDO::PARAM_STR);
            $stmt->bindValue(3, $balance_transaction, PDO::PARAM_STR);
            $stmt->bindValue(4, $payment_fname, PDO::PARAM_STR);
            $stmt->bindValue(5, $payment_lname, PDO::PARAM_STR);
            $stmt->bindValue(6, $payment_email, PDO::PARAM_STR);
            $stmt->bindValue(7, $amount, PDO::PARAM_STR);
            $stmt->bindValue(8, $date, PDO::PARAM_STR);
            $stmt->bindValue(9, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
            $stmt->bindValue(10, $singleNode->origin->name, PDO::PARAM_STR);
            $stmt->bindValue(11, $singleNode->origin->latitude, PDO::PARAM_STR);
            $stmt->bindValue(12, $singleNode->origin->longitude, PDO::PARAM_STR);
            $stmt->bindValue(13, $singleNode->destination->name, PDO::PARAM_STR);
            $stmt->bindValue(14, $singleNode->destination->latitude, PDO::PARAM_STR);
            $stmt->bindValue(15, $singleNode->destination->longitude, PDO::PARAM_STR);
            $stmt->bindValue(16, tripModeCalculate(count($rData->data->slices)), PDO::PARAM_STR);
            $stmt->bindValue(17, $status, PDO::PARAM_STR);
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
                'message' => "Payment Intent has beed failed",
            );
        }
    } else {

        http_response_code(422);
        $response = array(
            'status' => 422,
            'message' => "Transaction has been failed",
        );
    }


    //echo json_encode($response);
}
