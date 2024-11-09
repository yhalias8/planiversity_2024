<?php
include '../config.ini.php';
include '../ajaxfiles/payment/mail_process.php';

$body = file_get_contents('php://input');
$event = json_decode($body);

if (!empty($event)) {

    switch ($event->event_type) {

        case "PAYMENT.SALE.COMPLETED": // success

            $userid = $event->resource->custom;
            $subscription = $event->id;
            $price = $event->resource->amount->total;
            $status = 'succeeded';
            $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m") + 1, date("d"), date("Y")));
            $payment_type = "monthly";

            $mt = $dbh->prepare("SELECT id,name,email FROM users WHERE sha1(id)=? ");
            $mt->bindValue(1, $userid, PDO::PARAM_STR);
            $tmp = $mt->execute();
            $list = [];

            if ($tmp && $mt->rowCount() > 0) {
                $list = $mt->fetch(PDO::FETCH_OBJ);
                $id = $list->id;
                $email = $list->email;
                $fname = $list->name;
            }

            mailsendUser($auth, $email, $fname, $status, $price, "Paypal Payment");
            mailSend($auth, $fname, "", $status, $price, "Paypal Payment");


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
            $stmt->bindValue(11, "paypal", PDO::PARAM_STR);
            $stmt->bindValue(12, $date, PDO::PARAM_STR);
            $stmt->bindValue(13, $date_expire, PDO::PARAM_STR);
            $stmt->bindValue(14, $price, PDO::PARAM_STR);
            $stmt->bindValue(15, $status, PDO::PARAM_STR);
            $stmt->bindValue(16, "subscription", PDO::PARAM_STR);
            $stmt->execute();

            break;
    }
}
