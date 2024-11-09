<?php
include '../../config.ini.php';
//include '../../config.ini.curl.php';
include 'mail_process.php';

function updateIndividualTotalPrice($teamMembers)
{
    switch (true) {
        case ($teamMembers <= 10):
            return [
                'oneTime' => 4.99,
                'monthly' => 10.99,
                'annual' => 120.00
            ];
        case ($teamMembers <= 50):
            return [
                'oneTime' => 9.99,
                'monthly' => 19.99,
                'annual' => 219.00
            ];
        default:
            return [
                'oneTime' => 19.99,
                'monthly' => 34.99,
                'annual' => 384.00
            ];
    }
}


function updateTotalPrice($teamMembers)
{
    $totalPrice = 0;

    switch (true) {
        case ($teamMembers <= 10):
            $totalPrice = 49.99;
            break;
        case ($teamMembers <= 50):
            $totalPrice = 79.99;
            break;
        case ($teamMembers <= 150):
            $totalPrice = 149.99;
            break;
        default:
            $totalPrice = 300.00;
    }

    return $totalPrice;
}

if (isset($_POST['payment_type']) && isset($_POST['payment-option']) && isset($_POST['payment_cardnumber']) && isset($_POST['payment_expmonth']) && isset($_POST['payment_expyear']) && isset($_POST['payment_cvc']) && isset($_POST['stripeToken']) && isset($_POST['check']) && isset($_POST['date'])) {


    $saved_agree = 0;

    $enable_payment_option = 0;
    $type = "regular";

    if (isset($_POST['enable_payment_option']) && !empty($_POST['enable_payment_option'])) {
        $enable_payment_option = 1;
    }


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
    $coupon_context = filter_var($_POST["coupon_context"], FILTER_SANITIZE_STRING);
    $token = filter_var($_POST["stripeToken"], FILTER_SANITIZE_STRING);

    $fname = filter_var($_POST["payment_fname"], FILTER_SANITIZE_STRING);
    $lname = filter_var($_POST["payment_lname"], FILTER_SANITIZE_STRING);
    $team_member = filter_var($_POST["teamMembers"], FILTER_SANITIZE_STRING);

    $name = $fname . ' ' . $lname;

    $uid = $userdata['id'];

    $customer = \Stripe\Customer::create(array(
        'name' => $name,
        'email' => $userdata['email'],
        'source' => $token
    ));

    //item information
    $itemName = "Planiversity.com - " . $payment_type . " plan";


    if ($userdata['account_type'] == 'Individual') {
        $price_plans = updateIndividualTotalPrice($team_member);
        if ($payment_type == 'monthly')
            $price = $price_plans['monthly'];
        else if ($payment_type == 'one_time')
            $price = $price_plans['oneTime'];
        else
            $price = $price_plans['annual'];
    } else {
        if ($payment_type == 'monthly') {
            $price = updateTotalPrice($team_member);
        } else {
            $get_price = updateTotalPrice($team_member);
            $process_price = ($get_price * 11);
            $process_price = sprintf("%.2f", $process_price);
            $price = $process_price;
        }
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

        $mt = $dbh->prepare("SELECT percent,lifetime,target_plan_level FROM coupon WHERE sha1(id)=? and status=? and CURDATE()  >=  start_date  and  CURDATE() <= end_date ");
        $mt->bindValue(1, $coupon_id, PDO::PARAM_STR);
        $mt->bindValue(2, 'active', PDO::PARAM_STR);
        $tp = $mt->execute();
        $aux = '';
        $list = [];

        if ($tp && $mt->rowCount() > 0) {
            $list = $mt->fetch(PDO::FETCH_OBJ);

            $plan_level = array($payment_type, 'either');

            if (in_array($payment_type, $plan_level)) {
                $price_calculation = ($price - ($price * $list->percent / 100));
                $price =  sprintf("%.2f", $price_calculation);
            }

            if (($payment_type == 'annual') && ($list->lifetime == 1)) {
                $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d"), date("Y") + 100));
            }
        }
    }


    //echo json_encode($price);
    $itemPrice = str_replace(".", "", $price);
    $currency = "usd";
    $chargeJson = [];



    if ($payment_type == 'monthly' && $enable_payment_option == '1') {

        $plan = \Stripe\Plan::create(array(
            'product' => array(
                'name' => 'Dynamic Plan'
            ),
            'amount' => $itemPrice, // convert dollars to cents
            'interval' => 'month',
            'currency' => 'usd',
        ));

        $subscription = \Stripe\Subscription::create(array(
            'customer' => $customer->id,
            'items' => array(array('plan' => $plan->id)),
        ));

        $balance_transaction = $subscription->id;


        $stmt = $dbh->prepare("UPDATE users SET subscription_id=?,subscription_gateway=?,team_members=? WHERE id = ?");
        $stmt->bindValue(1, $balance_transaction, PDO::PARAM_STR);
        $stmt->bindValue(2, "stripe", PDO::PARAM_STR);
        $stmt->bindValue(3, $team_member, PDO::PARAM_STR);
        $stmt->bindValue(4, $uid, PDO::PARAM_INT);
        $tmp = $stmt->execute();
        if ($tmp) {

            $response = array(
                "status" => 200,
                "type" => "subscription",
                "id_value" => $balance_transaction,
                "message" => "Subscription created successfully",
            );
        } else {
            http_response_code(422);
            $response = array(
                'status' => 422,
                'message' => "Transaction has been failed",
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


        if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {


            if (isset($_POST['saved_agree'])) {
                $saved_agree = $_POST['saved_agree'];
            }

            //order details 
            $amount = $chargeJson['amount'];
            $balance_transaction = $chargeJson['balance_transaction'];
            $currency = $chargeJson['currency'];
            $status = $chargeJson['status'];
            $date = date("Y-m-d H:i:s");

            // $fname = filter_var($_POST["payment_fname"], FILTER_SANITIZE_STRING);
            // $lname = filter_var($_POST["payment_lname"], FILTER_SANITIZE_STRING);
            $country = filter_var($_POST["payment_country"], FILTER_SANITIZE_STRING);
            $address = filter_var($_POST["payment_address"], FILTER_SANITIZE_STRING);
            $city = filter_var($_POST["payment_city"], FILTER_SANITIZE_STRING);
            $state = filter_var($_POST["payment_state"], FILTER_SANITIZE_STRING);
            $zcode = filter_var($_POST["payment_zipcode"], FILTER_SANITIZE_STRING);

            //insert tansaction data into the database
            $query = "INSERT INTO payments (id_user,transaction_id, fname, lname, country, address, city, state, zipcode, plan_type, payment_type, date_paid, date_expire, amount, ip_address, status, save_info, action_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
            $stmt->bindValue(2, $balance_transaction, PDO::PARAM_STR);
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
            $stmt->bindValue(15, $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
            $stmt->bindValue(16, $status, PDO::PARAM_STR);
            $stmt->bindValue(17, $saved_agree, PDO::PARAM_INT);
            $stmt->bindValue(18, "payment", PDO::PARAM_STR);
            $stmt->execute();

            $stmt_another = $dbh->prepare("UPDATE users SET team_members=? WHERE id = ?");
            $stmt_another->bindValue(1, $team_member, PDO::PARAM_STR);
            $stmt_another->bindValue(2, $uid, PDO::PARAM_INT);
            $stmt_another->execute();

            mailsendUser($auth, $userdata['email'], $fname, $status, $price, "Card Payment");
            mailSend($auth, $fname, $lname, $status, $price, "Stripe Payment");

            http_response_code(200);

            $response = array(
                "status" => 200,
                "type" => "payment",
                "id_value" => $balance_transaction,
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
