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


if (isset($_POST['page_id']) && isset($_POST['payment_type']) && isset($_POST['token_key']) && isset($_POST['coupon_id']) && isset($_POST['coupon_flag'])) {
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

    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $currency = "USD";
    $page = $_POST['page_id'];
    $payment_type = $_POST['payment_type'];
    $coupon_id = $_POST['coupon_id'];
    $coupon_flag = $_POST['coupon_flag'];
    $team_member = $_POST['teamMembers'];
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
            $price = $process_price;
        }
    }


    if (!empty($coupon_id) && ($coupon_flag == 1)) {

        $mt = $dbh->prepare("SELECT percent,target_plan_level FROM coupon WHERE sha1(id)=? and status=? and CURDATE()  >=  start_date  and  CURDATE() <= end_date ");
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
                $price =  $price_calculation;
            }
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

    $data['paymentID'] = $payment->id;

    echo json_encode($data);
} else {
    header("Location: /");
}
