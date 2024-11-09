<?php
include '../config.ini.php';
include '../ajaxfiles/payment/mail_process.php';

//set api key
$stripe = array(
    "secret_key"      => STRIPE_SECRET_KEY,
    "publishable_key" => STRIPE_PUBLISHABLE_KEY
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);

$body = file_get_contents('php://input');
$event_response = json_decode($body);
$event = \Stripe\Event::retrieve($event_response->id);

if (!empty($event)) {


    switch ($event->type) {
        case "invoice.payment_succeeded": // success

            $email = $event->data->object->customer_email;
            $currency = $event->data->object->currency;
            $amount_paid = $event->data->object->amount_paid;
            //$subscription = $event->data->object->subscription;
            $subscription = $event->data->object->id;
            $status = 'succeeded';
            $date = date("Y-m-d H:i:s");
            $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m") + 1, date("d"), date("Y")));
            $price = ($amount_paid / 100);
            $payment_type = "monthly";
            
            $mt = $dbh->prepare("SELECT id,name FROM users WHERE email=? ");
            $mt->bindValue(1, $email, PDO::PARAM_STR);
            $tmp = $mt->execute();
            $list = [];

            if ($tmp && $mt->rowCount() > 0) {
                $list = $mt->fetch(PDO::FETCH_OBJ);
                $id = $list->id;
                $fname = $list->name;
            }            
            
            mailsendUser($auth, $email, $fname, $status, $price, "Card Payment");
            mailSend($auth, $fname, "", $status, $price, "Stripe Payment");


            $query = "INSERT INTO payments (id_user,transaction_id, fname, lname, country, address, city, state, zipcode, plan_type,payment_type, date_paid, date_expire, amount, status,action_type) VALUES (?, ?, ? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $id, PDO::PARAM_INT);
            $stmt->bindValue(2, $subscription, PDO::PARAM_STR);
            $stmt->bindValue(3, $fname, PDO::PARAM_STR);
            $stmt->bindValue(4, "", PDO::PARAM_STR);
            $stmt->bindValue(5, "", PDO::PARAM_STR);
            $stmt->bindValue(6, "", PDO::PARAM_STR);
            $stmt->bindValue(7, "", PDO::PARAM_STR);
            $stmt->bindValue(8, "", PDO::PARAM_STR);
            $stmt->bindValue(9, "", PDO::PARAM_STR);
            $stmt->bindValue(10, $payment_type, PDO::PARAM_STR);
            $stmt->bindValue(11, "stripe", PDO::PARAM_STR);
            $stmt->bindValue(12, $date, PDO::PARAM_STR);
            $stmt->bindValue(13, $date_expire, PDO::PARAM_STR);
            $stmt->bindValue(14, $price, PDO::PARAM_STR);
            $stmt->bindValue(15, $status, PDO::PARAM_STR);
            $stmt->bindValue(16, "subscription", PDO::PARAM_STR);
            $stmt->execute();            

            http_response_code(200);
            break;
        case "invoice.payment_failed":
            break;
    }
}
