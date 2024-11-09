<?php

include '../config.ini.php';

if (!$auth->isLogged()) {
    $res['txt'] = '';
    $res['error'] = 'You do not have access to this function';
    echo json_encode($res);
    exit;
}

if (isset($_POST['flag']) && in_array($_POST['flag'], array('0', '1'))) {


    $uid = $userdata['id'];
    $flag = $_POST['flag'];


    if ($flag == 0) {


        $usr = $dbh->prepare("SELECT subscription_id,subscription_gateway FROM users WHERE id=?");
        $usr->bindValue(1, $uid, PDO::PARAM_INT);
        $ump = $usr->execute();
        $aux = '';
        $data_line = $usr->fetch(PDO::FETCH_OBJ);

        if (!empty($data_line->subscription_id) && (!empty($data_line->subscription_gateway))) {

            if ($data_line->subscription_gateway == "stripe") {

                $stripe = array(
                    "secret_key"      => STRIPE_SECRET_KEY,
                    "publishable_key" => STRIPE_PUBLISHABLE_KEY
                );

                \Stripe\Stripe::setApiKey($stripe['secret_key']);

                $subscription = \Stripe\Subscription::retrieve($data_line->subscription_id);
                $subscription->delete();
            } else {

                $clientId = PAYPAL_CLIENT_ID;
                $clientSecret = PAYPAL_CLIENT_SECRET;
                $apiEndpoint = 'https://api.paypal.com';

                // Set up the subscription ID
                $subscriptionId = $data_line->subscription_id;

                // Set up the request headers
                $headers = array(
                    'Content-Type: application/json',
                    'Authorization: Basic ' . base64_encode($clientId . ':' . $clientSecret),
                );

                // Set up the pause/cancel request body
                $requestBody = array(
                    'reason' => 'Server Remove', // Optional reason for the pause/cancel
                );

                // Make the API call to pause or cancel the subscription
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiEndpoint . '/v1/billing/subscriptions/' . $subscriptionId . '/cancel');
                curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);

                // Check the API response
                $responseData = json_decode($response, true);

            }
        }
    }


    $stmt = $dbh->prepare("UPDATE users SET recurring_payment=?,subscription_id=?,subscription_gateway=? WHERE id = ?");
    $stmt->bindValue(1, $flag, PDO::PARAM_INT);
    $stmt->bindValue(2, NULL, PDO::PARAM_STR);
    $stmt->bindValue(3, NULL, PDO::PARAM_STR);
    $stmt->bindValue(4, $uid, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $aux = '';
    $data = $flag == 1 ? 'Enabled ' : 'Disabled';
    if ($tmp) {

        $response = array(
            "status" => 200,
            "data" => $data,
            "message" => "$data automatic payment",
        );

        http_response_code(200);
    } else {

        $response = array(
            'status' => 422,
            'message' => "No data found",
        );
        http_response_code(422);
    }

    echo json_encode($response);
}
