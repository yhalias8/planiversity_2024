<?php

include_once("config.ini.php");

// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
// Set this to 0 once you go live or don't require logging.
define("DEBUG", 0);
// Set to 0 once you're ready to go live
define("LOG_FILE", "./ipn.log");
// Read POST data
// reading posted data directly from $_POST causes serialization
// issues with array data in POST. Reading raw POST data from input stream instead.
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2)
        $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if (function_exists('get_magic_quotes_gpc')) {
    $get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
    if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value));
    } else {
        $value = urlencode($value);
    }
    $req .= "&$key=$value";
}
// Post IPN data back to PayPal to validate the IPN data is genuine
// Without this step anyone can fake IPN data
//$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
$paypal_url = "https://www.paypal.com/cgi-bin/webscr";

$ch = curl_init($paypal_url);
if ($ch == FALSE) {
    return FALSE;
}
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
if (DEBUG == true) {
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
}
// CONFIG: Optional proxy configuration
//curl_setopt($ch, CURLOPT_PROXY, $proxy);
//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
// Set TCP timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
// of the certificate as shown below. Ensure the file is readable by the webserver.
// This is mandatory for some environments.
//$cert = __DIR__ . "./cacert.pem";
//curl_setopt($ch, CURLOPT_CAINFO, $cert);
$res = curl_exec($ch);
if (curl_errno($ch) != 0) // cURL error
{
    if (DEBUG == true) {
        error_log(date('[Y-m-d H:i e] ') . "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
    }
    $error = "Problem: Can't connect to PayPal to validate IPN message";
    curl_close($ch);
    exit;
} else {
    // Log the entire HTTP response if debug is switched on.
    if (DEBUG == true) {
        error_log(date('[Y-m-d H:i e] ') . "HTTP request of validation request:" . curl_getinfo($ch, CURLINFO_HEADER_OUT) . " for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
        error_log(date('[Y-m-d H:i e] ') . "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
    }
    curl_close($ch);
}
// Inspect IPN validation result and act accordingly
// Split response headers and payload, a better way for strcmp
$tokens = explode("\r\n\r\n", trim($res));
$res = trim(end($tokens));
if (strcmp($res, "VERIFIED") == 0) {
    // check whether the payment_status is Completed
    // check that txn_id has not been previously processed
    // check that receiver_email is your PayPal email
    // check that payment_amount/payment_currency are correct
    // process payment and mark item as paid.
    // assign posted variables to local variables		

    $_tmp = explode('-', $_POST['item_name']);
    $item_plan = trim($_tmp[1]);
    $item_number = $_POST['item_number'];
    $_aux = explode('-', $_POST['item_number']);
    $item_customer = trim($_aux[0]);
    $item_idtrip = trim($_aux[1]);

    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $payment_fname = $_POST["first_name"];
    $payment_lname = $_POST["last_name"];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];

    $date = date("Y-m-d H:i:s");
    $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d") - 1, date("Y")));

    if ($item_plan == 'Monthly Plan') $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m") + 1, date("d"), date("Y")));
    if ($item_plan == 'Annual Plan') $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d"), date("Y") + 1));

    //insert tansaction data into the database
    $query = "INSERT INTO payments (id_user, fname, lname, country, address, city, state, zipcode, plan_type, date_paid, date_expire, amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $item_customer, PDO::PARAM_INT);
    $stmt->bindValue(2, $payment_fname, PDO::PARAM_STR);
    $stmt->bindValue(3, $payment_lname, PDO::PARAM_STR);
    $stmt->bindValue(4, $country, PDO::PARAM_STR);
    $stmt->bindValue(5, $address, PDO::PARAM_STR);
    $stmt->bindValue(6, $city, PDO::PARAM_STR);
    $stmt->bindValue(7, $state, PDO::PARAM_STR);
    $stmt->bindValue(8, $zcode, PDO::PARAM_STR);
    $stmt->bindValue(9, $item_plan, PDO::PARAM_STR);
    $stmt->bindValue(10, $date, PDO::PARAM_STR);
    $stmt->bindValue(11, $date_expire, PDO::PARAM_STR);
    $stmt->bindValue(12, $payment_amount, PDO::PARAM_STR);
    $stmt->bindValue(13, $payment_status, PDO::PARAM_STR);
    $stmt->execute();

    // send notification payment email
    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
    $mail->From = $receiver_email;
    $mail->FromName = 'Planiversity.com';
    $mail->addAddress($payer_email);
    $mail->isHTML(true);
    $mail->Subject = 'Planiversity.com - PayPal Payment';
    $mail->Body = 'Hello ' . $payment_fname . ',<br/><br/> Thank you for your payment. <br /><br /> Payment Status: ' . $payment_status . '<br />Payment Amount: $' . $payment_amount;
    $mail->send();
    //mail($payer_email, "Planiversity.com - PayPal Payment: " . $payment_status, "Hi ".$payment_fname .": <br />Thank you for your payment. <br /><br /> Payment Status: ".$payment_status."<br />Payment Amount".$payment_amount);
    $mail2 = new PHPMailer;
    $mail2->CharSet = 'UTF-8';
    $mail2->From = $receiver_email;
    $mail2->FromName = 'Planiversity.com';
    $mail2->addAddress($receiver_email);
    $mail2->addAddress('planiversitymgmt@gmail.com');
    $mail2->isHTML(true);
    $mail2->Subject = 'Planiversity.com - PayPal Payment';
    $mail2->Body = 'Hello ,<br/><br/> You received a payment from Planiversity.com. <br /><br /> Payment From: ' . $payment_fname . ' ' . $payment_lname . '<br />Payment Status: ' . $payment_status . '<br />Payment Amount: $' . $payment_amount;
    $mail2->send();
    //mail($receiver_email, "Planiversity.com - PayPal Payment: " . $payment_status, "Hi : <br />You received a payment from Planiversity.com. <br /><br /> Payment From: ".$payment_fname." ".$payment_lname."<br />Payment Status: ".$payment_status."<br />Payment Amount".$payment_amount);

    if (DEBUG == true) {
        error_log(date('[Y-m-d H:i e] ') . "Verified IPN: $req " . PHP_EOL, 3, LOG_FILE);
    }
} else if (strcmp($res, "INVALID") == 0) {
    // log for manual investigation
    // Add business logic here which deals with invalid IPN messages
    $error = "Problem: Invalid IPN";
    if (DEBUG == true) {
        error_log(date('[Y-m-d H:i e] ') . "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
    }
}
