<?php
//include_once('paypal_config.php');
require_once '../../config.ini.php';
require_once '../../PayPal-PHP-SDK/autoload.php';
//require_once "../vendor/paypal/autoload.php";

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


if (isset($_POST['page_id']) && isset($_POST['payment_type']) && isset($_POST['token_key']) && isset($_POST['coupon_id']) && isset($_POST['coupon_flag'])) {


    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(PAYPAL_CLIENT_ID, PAYPAL_CLIENT_SECRET)
    );

    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $currency = "USD";
    $page = $_POST['page_id'];
    $payment_type = $_POST['payment_type'];
    $coupon_id = $_POST['coupon_id'];
    $coupon_flag = $_POST['coupon_flag'];
    $itemName = "Planiversity.com - " . $payment_type;

    if ($userdata['account_type'] == 'Individual') {
        if ($_POST['payment_type'] == 'monthly')
            $price = 4.99;
        else
            $price = 49.99;
    } else {
        if ($_POST['payment_type'] == 'monthly')
            $price = 24.99;
        else
            $price = 249.99;
    }


    if (!empty($coupon_id) && ($coupon_flag == 1)) {

        $stmt = $dbh->prepare("SELECT percent FROM coupon WHERE sha1(id)=? and status=? and CURDATE()  >=  start_date  and  CURDATE() <= end_date ");
        $stmt->bindValue(1, $coupon_id, PDO::PARAM_STR);
        $stmt->bindValue(2, 'active', PDO::PARAM_STR);
        $tmp = $stmt->execute();
        $aux = '';
        $list = [];

        if ($tmp && $stmt->rowCount() > 0) {
            $list = $stmt->fetch(PDO::FETCH_OBJ);
            $price = number_format(($price - ($price * $list->percent / 100)), 2);
        }
    }

    // Set payment amount
    $amount = new Amount();
    $amount->setCurrency("$currency")
        ->setTotal($price);

    // Set transaction object
    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setDescription("$itemName")
        ->setInvoiceNumber(uniqid());


    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($page . "?process=succes&id=" . uniqid())
        ->setCancelUrl($page . "?process=failed");

    // Add NO SHIPPING OPTION
    $inputFields = new InputFields();
    $inputFields->setNoShipping(1);

    $webProfile = new WebProfile();
    $webProfile->setName('test' . uniqid())->setInputFields($inputFields);

    $webProfileId = $webProfile->create($apiContext)->getId();

    $payment = new Payment();
    $payment->setExperienceProfileId($webProfileId); // no shipping
    $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));

    $request = clone $payment;

    try {
        $payment->create($apiContext);
    } catch (Exception $ex) {

        //ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
        echo $ex;
        exit(1);
    }

    $approvalUrl = $payment->getApprovalLink();

    // ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);

    $data['id'] = $payment->id;

    echo json_encode($data);
} else {
    header("Location: /");
}
