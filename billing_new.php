<!-- Start TradeDoubler Landing Page Tag Insert on all landing pages to handle first party cookie-->
<script language="JavaScript">
(function(i,s,o,g,r,a,m){i['TDConversionObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script', 'https://svht.tradedoubler.com/tr_sdk.js?org=2307051&prog=324547&dr=true&rand=' + Math.random(), 'tdconv');
</script>
<!-- End TradeDoubler tag-->

<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    if ($_GET['idtrip'])
        $_SESSION['redirect'] = 'billing/' . $_GET['idtrip'];
    else
        $_SESSION['redirect'] = 'billing';
    header("Location:" . SITE . "login");
}

$statusMsg = '';

require 'PayPal-PHP-SDK/autoload.php';
include("class/class.Plan.php");
$plan = new Plan();
if ($plan->check_plan($userdata['id'])) { // if you have a plan export PDF
    if (isset($_GET['idtrip']) && !empty($_GET['idtrip']))
        header("Location:" . SITE . "trip/pdf/" . $_GET['idtrip']);
}
//set api key
$stripe = array(
    "secret_key"      => STRIPE_SECRET_KEY,
    "publishable_key" => STRIPE_PUBLISHABLE_KEY
);
\Stripe\Stripe::setApiKey($stripe['secret_key']);

/***************** PAYPAL *********************************/
$PAYPAL_EMAIL = 'planiversitymgmt@gmail.com';
$PAYPAL_URL = 'https://www.paypal.com/cgi-bin/webscr'; // 'https://www.paypal.com/cgi-bin/webscr';

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        PAYPAL_CLIENT_ID,
        PAYPAL_CLIENT_SECRET
    )
);
if (!empty($_GET['status'])) {
    if ($_GET['status'] == "success") {
        $token = $_GET['token'];
        $agreement = new \PayPal\Api\Agreement();
        try {
            // Execute agreement
            $agreement->execute($token, $apiContext);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    } else {
        echo "user canceled agreement";
        sleep(3);
    }
    header("Location:" . SITE . "welcome");
}

if (!empty($_POST["subscribe"])) {
    require_once "billing_paypal_subscription_plan.php";
}



//check whether stripe token is not empty
if (!empty($_POST['stripeToken'])) {
    //get token, card and user info from the form
    $token  = $_POST['stripeToken'];
    //add customer to stripe
    $customer = \Stripe\Customer::create(array(
        'email' => $userdata['email'],
        'source'  => $token
    ));

    //item information
    $itemName = "Planiversity.com - " . $_POST["payment_type"];
    if ($userdata['account_type'] == 'Individual') {
        if ($_POST['payment_type'] == 'Monthly Plan')
            $Price = '4.99';
        else
            $Price = '1.49';
    } else {
        if ($_POST['payment_type'] == 'Monthly Plan')
            $Price = '24.99';
        else
            $Price = '249.00';
    }
    $itemPrice = str_replace(".", "", $Price);
    $currency = "usd";
    $chargeJson = [];
    if ($userdata['account_type'] != 'Individual' && $_POST['payment_type'] == 'Monthly Plan') {
        try {
            $subscription = \Stripe\Subscription::create(array(
                "customer" => $customer->id,
                "items" => array(
                    array(
                        "plan" => STRIPE_SUBSCRIPTION_PLAN_ID,
                    ),
                ),
            )); ?>
            <!-- Start TradeDoubler Conversion Tag Insert on confirmation Page -->
			<script language="JavaScript">
				tdconv('init', '2307051', {'element': 'iframe' });
				tdconv('track', 'sale', {'transactionId':'<?= rand(9999, 10000) ?>', 'ordervalue':<?= $itemPrice ?>, 'voucher':'FREEDEL', 'currency':'USD', 'event':419142});
			</script>
			<!-- End TradeDoubler tag-->
            <?php sleep(3);
            if (isset($_GET['idtrip']) && !empty($_GET['idtrip']))
                header("Location:" . SITE . "trip/pdf/" . $_GET['idtrip']);
            else { // show mesage and redirect in 5 sec
                header("Location:" . SITE . "welcome");
            }
        } catch (Exception $e) {
            print_r($e);
            die();
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
        if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) { ?>
        
			<!-- Start TradeDoubler Conversion Tag Insert on confirmation Page -->
			<script language="JavaScript">
				tdconv('init', '2307051', {'element': 'iframe' });
				tdconv('track', 'sale', {'transactionId':'<?= rand(9999, 10000) ?>', 'ordervalue':<?= $chargeJson['amount'] ?>, 'voucher':'FREEDEL', 'currency':'USD', 'event':419142});
			</script>
			<!-- End TradeDoubler tag-->

            <?php //order details 
            $amount = $chargeJson['amount'];
            $balance_transaction = $chargeJson['balance_transaction'];
            $currency = $chargeJson['currency'];
            $status = $chargeJson['status'];
            $date = date("Y-m-d H:i:s");
            $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d") - 1, date("Y")));

            if ($_POST['payment_type'] == 'Monthly Plan') $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m") + 1, date("d"), date("Y")));
            if ($_POST['payment_type'] == 'Annual Plan') $date_expire = date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m"), date("d"), date("Y") + 1));

            $fname = filter_var($_POST["payment_fname"], FILTER_SANITIZE_STRING);
            $lname = filter_var($_POST["payment_lname"], FILTER_SANITIZE_STRING);
            $country = filter_var($_POST["payment_country"], FILTER_SANITIZE_STRING);
            $address = filter_var($_POST["payment_address"], FILTER_SANITIZE_STRING);
            $city = filter_var($_POST["payment_city"], FILTER_SANITIZE_STRING);
            $state = filter_var($_POST["payment_state"], FILTER_SANITIZE_STRING);
            $zcode = filter_var($_POST["payment_zipcode"], FILTER_SANITIZE_STRING);
            $plantype = filter_var($_POST["payment_type"], FILTER_SANITIZE_STRING);

            // send notification payment email
            $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            $mail->From = $auth->config->site_email;
            $mail->FromName = $auth->config->site_name;
            $mail->addAddress($userdata['email']);
            $mail->isHTML(true);
            $mail->Subject = 'Planiversity.com - Card Payment';
            $mail->Body = 'Hello ' . $fname . ',<br/><br/> Thank you for your payment. <br /><br /> Payment Status: ' . $status . '<br />Payment Amount: $' . $Price;
            $mail->send();
            //mail($userdata['email'], "Planiversity.com - Stripe Payment: " . $status, "Hi ".$fname .": <br />Thank you for your payment. <br /><br /> Payment Status: ".$status."<br />Payment Amount".$Price);
            $mail2 = new PHPMailer;
            $mail2->CharSet = 'UTF-8';
            $mail2->From = $auth->config->site_email;
            $mail2->FromName = $auth->config->site_name;
            $mail2->addAddress($auth->config->site_email);
            $mail2->addAddress('planiversitymgmt@gmail.com');
            $mail2->isHTML(true);
            $mail2->Subject = 'Planiversity.com - Stripe Payment';
            $mail2->Body = 'Hello,<br/><br/> You received a payment from Planiversity.com. <br /><br /> Payment From: ' . $fname . ' ' . $lname . '<br />Payment Status: ' . $status . '<br />Payment Amount: $' . $Price;
            $mail2->send();
            //mail($receiver_email, "Planiversity.com - PayPal Payment: " . $status, "Hi : <br />You received a payment from Planiversity.com. <br /><br /> Payment From: ".$fname." ".$lname."<br />Payment Status: ".$status."<br />Payment Amount".$Price);

            //insert tansaction data into the database
            $query = "INSERT INTO payments (id_user, fname, lname, country, address, city, state, zipcode, plan_type, date_paid, date_expire, amount, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
            $stmt->bindValue(2, $fname, PDO::PARAM_STR);
            $stmt->bindValue(3, $lname, PDO::PARAM_STR);
            $stmt->bindValue(4, $country, PDO::PARAM_STR);
            $stmt->bindValue(5, $address, PDO::PARAM_STR);
            $stmt->bindValue(6, $city, PDO::PARAM_STR);
            $stmt->bindValue(7, $state, PDO::PARAM_STR);
            $stmt->bindValue(8, $zcode, PDO::PARAM_STR);
            $stmt->bindValue(9, $plantype, PDO::PARAM_STR);
            $stmt->bindValue(10, $date, PDO::PARAM_STR);
            $stmt->bindValue(11, $date_expire, PDO::PARAM_STR);
            $stmt->bindValue(12, $Price, PDO::PARAM_STR);
            $stmt->bindValue(13, $status, PDO::PARAM_STR);
            $stmt->execute();
            //$statusMsg = "<h2>The transaction was successful.</h2>";
            if (isset($_GET['idtrip']) && !empty($_GET['idtrip']))
                header("Location:" . SITE . "trip/pdf/" . $_GET['idtrip']);
            else { // show mesage and redirect in 5 sec
                header("Location:" . SITE . "welcome");
            }
        } else {
            $statusMsg = "Transaction has been failed";
        }
    }
}

include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <title>PLANIVERSITY - BILLING PAGE</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?php echo SITE; ?>'
    </script>
    <script src="<?php echo SITE; ?>js/v1.js"></script>
    <script src="https://js.stripe.com/v3/"></script>

    <?php include('new_head_files.php'); ?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146873572-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-146873572-1');
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PBF3Z2D');
    </script>
    <!-- End Google Tag Manager -->
</head>

<body class="custom_billing">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include('new_backend_header.php'); ?>
    </header>
    <style>
        .sorry-text {
            text-align: left;
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
            margin-top: 3rem;
        }

        .manage-text {
            font-size: 16px;
            text-align: left;
            color: #F4A033;
            margin: 0;
            font-weight: bold;
        }

        .background-F2F2F2 {
            background-color: #F2F2F2;
        }

        .radio-tile label {
            background: transparent;
            width: 100%;
            border-radius: 7px;
            color: #0676ED;
            font-weight: bold;
            border: 2px solid #0676ED;
            padding: 20px 10px 20px 50px;
        }

        .radio-tile label::before {
            left: 35px;
            border: 1px solid #ddd;
            width: 25px;
            height: 25px;
            top: 17px;
        }

        .radio-tile label::after {
            left: 42px;
            top: 24px;
            background: #ddd;
            display: block;
        }

        .radio-tile input:checked+label::before {
            background: #058BEF;
            border: 1px solid #058BEF;
        }

        .radio-tile input:checked+label {
            background: #0676ED;
            width: 100%;
            border-radius: 7px;
            color: white;
            padding: 20px 10px 20px 50px;
        }

        #pay_credit_card img {
            margin-top: -6px;
        }
    </style>
    <div class="billing-wrapper background-F2F2F2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <?php if ($userdata['account_type'] == 'Individual') { ?>
                        <h3 class="sorry-text">Sorry, one last thing ...</h3>
                    <?php } else { ?>
                        <h3 class="sorry-text">Select Your Business Billing Plan </h3>
                    <?php } ?>
                </div>
            </div>
            <form action="" method="POST" id="payment_form">
                <div class="row">
                    <div class="col-sm-12">
                        <?php if ($userdata['account_type'] != 'Individual') { ?>
                            <h3 class="manage-text">Unlimited Uses and Manage Your Own Database</h3><br>
                        <?php } ?>
                        <!--<div class="billing-header-card-box">-->
                        <div class="mb-4">
                            <div class="page-title-box">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mgn-top">
                                            <div class="radio radio-tile">
                                                <?php if ($userdata['account_type'] == 'Individual') { ?>
                                                    <input id="radio-case-by-case" name="payment_type" type="radio" value="Case by Case" class="rad2" checked="checked">
                                                    <label for="radio-case-by-case">Case by Case &nbsp;&nbsp;$1.49</label>
                                                <?php } else { ?>
                                                    <input id="radio-monthly-plan" name="payment_type" type="radio" value="Monthly Plan" class="rad2" checked="checked">
                                                    <label for="radio-monthly-plan">Monthly Plan &nbsp;&nbsp;Only $24.99 per plan</label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mgn-top">
                                            <div class="radio radio-tile mgn-top-20">
                                                <?php if ($userdata['account_type'] == 'Individual') { ?>
                                                    <input id="radio-monthly-plan-ind" name="payment_type" type="radio" value="Monthly Plan" class="rad2">
                                                    <label for="radio-monthly-plan-ind">Monthly Plan &nbsp;&nbsp;Unlimited uses per month for $4.99</label>
                                                <?php } else { ?>
                                                    <input id="radio-annual-plan" name="payment_type" type="radio" value="Annual Plan" class="rad2">
                                                    <label for="radio-annual-plan">Annual Plan &nbsp;&nbsp;Only $249.00</label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-lg-7">
                        <div class="card-box padding-30" id="pay_credit_card">
                            <div class="payment-type-header-wrapper">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="pay-option-wrapper">
                                            <div class="radio form-check-inline mgn-top-20">
                                                <input type="radio" id="radio-visa" value="visa" name="payment-option" checked>
                                                <label for="radio-visa"><img src="<?php echo SITE; ?>assets/images/payment1.png" alt="visa"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="pay-option-wrapper">
                                            <div class="radio form-check-inline mgn-top-20">
                                                <input type="radio" id="radio-mastercard" value="mastercard" name="payment-option">
                                                <label for="radio-mastercard"><img src="<?php echo SITE; ?>assets/images/payment2.png" alt="visa"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="pay-option-wrapper">
                                            <div class="radio form-check-inline mgn-top-20">
                                                <input type="radio" id="radio-amex" value="amex" name="payment-option">
                                                <label for="radio-amex"><img src="<?php echo SITE; ?>assets/images/payment3.png" alt="visa"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="pay-option-wrapper">
                                            <div class="radio form-check-inline mgn-top-20">
                                                <input type="radio" id="radio-discover" value="discover" name="payment-option">
                                                <label for="radio-discover"><img src="<?php echo SITE; ?>assets/images/payment4.png" alt="visa"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-wrap">
                                <fieldset>
                                    <label class="billing-label" for="payment_fname">Full Name</label>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input name="payment_fname" id="payment_fname" maxlength="20" type="text" class="account-form-control form-control input-lg inp1" placeholder="First Name" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input name="payment_lname" id="payment_lname" maxlength="20" type="text" class="account-form-control form-control input-lg inp1" placeholder="Last Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label" for="payment_country">Country</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="select-style">
                                                    <select name="payment_country" id="payment_country" class="input-lg inp1">
                                                        <optgroup id="country-optgroup-Africa" label="Africa">
                                                            <option value="DZ" label="Algeria">Algeria</option>
                                                            <option value="AO" label="Angola">Angola</option>
                                                            <option value="BJ" label="Benin">Benin</option>
                                                            <option value="BW" label="Botswana">Botswana</option>
                                                            <option value="BF" label="Burkina Faso">Burkina Faso</option>
                                                            <option value="BI" label="Burundi">Burundi</option>
                                                            <option value="CM" label="Cameroon">Cameroon</option>
                                                            <option value="CV" label="Cape Verde">Cape Verde</option>
                                                            <option value="CF" label="Central African Republic">Central African Republic</option>
                                                            <option value="TD" label="Chad">Chad</option>
                                                            <option value="KM" label="Comoros">Comoros</option>
                                                            <option value="CG" label="Congo - Brazzaville">Congo - Brazzaville</option>
                                                            <option value="CD" label="Congo - Kinshasa">Congo - Kinshasa</option>
                                                            <option value="CI" label="Côte d’Ivoire">Côte d’Ivoire</option>
                                                            <option value="DJ" label="Djibouti">Djibouti</option>
                                                            <option value="EG" label="Egypt">Egypt</option>
                                                            <option value="GQ" label="Equatorial Guinea">Equatorial Guinea</option>
                                                            <option value="ER" label="Eritrea">Eritrea</option>
                                                            <option value="ET" label="Ethiopia">Ethiopia</option>
                                                            <option value="GA" label="Gabon">Gabon</option>
                                                            <option value="GM" label="Gambia">Gambia</option>
                                                            <option value="GH" label="Ghana">Ghana</option>
                                                            <option value="GN" label="Guinea">Guinea</option>
                                                            <option value="GW" label="Guinea-Bissau">Guinea-Bissau</option>
                                                            <option value="KE" label="Kenya">Kenya</option>
                                                            <option value="LS" label="Lesotho">Lesotho</option>
                                                            <option value="LR" label="Liberia">Liberia</option>
                                                            <option value="LY" label="Libya">Libya</option>
                                                            <option value="MG" label="Madagascar">Madagascar</option>
                                                            <option value="MW" label="Malawi">Malawi</option>
                                                            <option value="ML" label="Mali">Mali</option>
                                                            <option value="MR" label="Mauritania">Mauritania</option>
                                                            <option value="MU" label="Mauritius">Mauritius</option>
                                                            <option value="YT" label="Mayotte">Mayotte</option>
                                                            <option value="MA" label="Morocco">Morocco</option>
                                                            <option value="MZ" label="Mozambique">Mozambique</option>
                                                            <option value="NA" label="Namibia">Namibia</option>
                                                            <option value="NE" label="Niger">Niger</option>
                                                            <option value="NG" label="Nigeria">Nigeria</option>
                                                            <option value="RW" label="Rwanda">Rwanda</option>
                                                            <option value="RE" label="Réunion">Réunion</option>
                                                            <option value="SH" label="Saint Helena">Saint Helena</option>
                                                            <option value="SN" label="Senegal">Senegal</option>
                                                            <option value="SC" label="Seychelles">Seychelles</option>
                                                            <option value="SL" label="Sierra Leone">Sierra Leone</option>
                                                            <option value="SO" label="Somalia">Somalia</option>
                                                            <option value="ZA" label="South Africa">South Africa</option>
                                                            <option value="SD" label="Sudan">Sudan</option>
                                                            <option value="SZ" label="Swaziland">Swaziland</option>
                                                            <option value="ST" label="São Tomé and Príncipe">São Tomé and Príncipe</option>
                                                            <option value="TZ" label="Tanzania">Tanzania</option>
                                                            <option value="TG" label="Togo">Togo</option>
                                                            <option value="TN" label="Tunisia">Tunisia</option>
                                                            <option value="UG" label="Uganda">Uganda</option>
                                                            <option value="EH" label="Western Sahara">Western Sahara</option>
                                                            <option value="ZM" label="Zambia">Zambia</option>
                                                            <option value="ZW" label="Zimbabwe">Zimbabwe</option>
                                                        </optgroup>
                                                        <optgroup id="country-optgroup-Americas" label="Americas">
                                                            <option value="AI" label="Anguilla">Anguilla</option>
                                                            <option value="AG" label="Antigua and Barbuda">Antigua and Barbuda</option>
                                                            <option value="AR" label="Argentina">Argentina</option>
                                                            <option value="AW" label="Aruba">Aruba</option>
                                                            <option value="BS" label="Bahamas">Bahamas</option>
                                                            <option value="BB" label="Barbados">Barbados</option>
                                                            <option value="BZ" label="Belize">Belize</option>
                                                            <option value="BM" label="Bermuda">Bermuda</option>
                                                            <option value="BO" label="Bolivia">Bolivia</option>
                                                            <option value="BR" label="Brazil">Brazil</option>
                                                            <option value="VG" label="British Virgin Islands">British Virgin Islands</option>
                                                            <option value="CA" label="Canada">Canada</option>
                                                            <option value="KY" label="Cayman Islands">Cayman Islands</option>
                                                            <option value="CL" label="Chile">Chile</option>
                                                            <option value="CO" label="Colombia">Colombia</option>
                                                            <option value="CR" label="Costa Rica">Costa Rica</option>
                                                            <option value="CU" label="Cuba">Cuba</option>
                                                            <option value="DM" label="Dominica">Dominica</option>
                                                            <option value="DO" label="Dominican Republic">Dominican Republic</option>
                                                            <option value="EC" label="Ecuador">Ecuador</option>
                                                            <option value="SV" label="El Salvador">El Salvador</option>
                                                            <option value="FK" label="Falkland Islands">Falkland Islands</option>
                                                            <option value="GF" label="French Guiana">French Guiana</option>
                                                            <option value="GL" label="Greenland">Greenland</option>
                                                            <option value="GD" label="Grenada">Grenada</option>
                                                            <option value="GP" label="Guadeloupe">Guadeloupe</option>
                                                            <option value="GT" label="Guatemala">Guatemala</option>
                                                            <option value="GY" label="Guyana">Guyana</option>
                                                            <option value="HT" label="Haiti">Haiti</option>
                                                            <option value="HN" label="Honduras">Honduras</option>
                                                            <option value="JM" label="Jamaica">Jamaica</option>
                                                            <option value="MQ" label="Martinique">Martinique</option>
                                                            <option value="MX" label="Mexico">Mexico</option>
                                                            <option value="MS" label="Montserrat">Montserrat</option>
                                                            <option value="AN" label="Netherlands Antilles">Netherlands Antilles</option>
                                                            <option value="NI" label="Nicaragua">Nicaragua</option>
                                                            <option value="PA" label="Panama">Panama</option>
                                                            <option value="PY" label="Paraguay">Paraguay</option>
                                                            <option value="PE" label="Peru">Peru</option>
                                                            <option value="PR" label="Puerto Rico">Puerto Rico</option>
                                                            <option value="BL" label="Saint Barthélemy">Saint Barthélemy</option>
                                                            <option value="KN" label="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                            <option value="LC" label="Saint Lucia">Saint Lucia</option>
                                                            <option value="MF" label="Saint Martin">Saint Martin</option>
                                                            <option value="PM" label="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                            <option value="VC" label="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                                            <option value="SR" label="Suriname">Suriname</option>
                                                            <option value="TT" label="Trinidad and Tobago">Trinidad and Tobago</option>
                                                            <option value="TC" label="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                            <option value="VI" label="U.S. Virgin Islands">U.S. Virgin Islands</option>
                                                            <option value="US" label="United States">United States</option>
                                                            <option value="UY" label="Uruguay">Uruguay</option>
                                                            <option value="VE" label="Venezuela">Venezuela</option>
                                                        </optgroup>
                                                        <optgroup id="country-optgroup-Asia" label="Asia">
                                                            <option value="AF" label="Afghanistan">Afghanistan</option>
                                                            <option value="AM" label="Armenia">Armenia</option>
                                                            <option value="AZ" label="Azerbaijan">Azerbaijan</option>
                                                            <option value="BH" label="Bahrain">Bahrain</option>
                                                            <option value="BD" label="Bangladesh">Bangladesh</option>
                                                            <option value="BT" label="Bhutan">Bhutan</option>
                                                            <option value="BN" label="Brunei">Brunei</option>
                                                            <option value="KH" label="Cambodia">Cambodia</option>
                                                            <option value="CN" label="China">China</option>
                                                            <option value="CY" label="Cyprus">Cyprus</option>
                                                            <option value="GE" label="Georgia">Georgia</option>
                                                            <option value="HK" label="Hong Kong SAR China">Hong Kong SAR China</option>
                                                            <option value="IN" label="India">India</option>
                                                            <option value="ID" label="Indonesia">Indonesia</option>
                                                            <option value="IR" label="Iran">Iran</option>
                                                            <option value="IQ" label="Iraq">Iraq</option>
                                                            <option value="IL" label="Israel">Israel</option>
                                                            <option value="JP" label="Japan">Japan</option>
                                                            <option value="JO" label="Jordan">Jordan</option>
                                                            <option value="KZ" label="Kazakhstan">Kazakhstan</option>
                                                            <option value="KW" label="Kuwait">Kuwait</option>
                                                            <option value="KG" label="Kyrgyzstan">Kyrgyzstan</option>
                                                            <option value="LA" label="Laos">Laos</option>
                                                            <option value="LB" label="Lebanon">Lebanon</option>
                                                            <option value="MO" label="Macau SAR China">Macau SAR China</option>
                                                            <option value="MY" label="Malaysia">Malaysia</option>
                                                            <option value="MV" label="Maldives">Maldives</option>
                                                            <option value="MN" label="Mongolia">Mongolia</option>
                                                            <option value="MM" label="Myanmar [Burma]">Myanmar [Burma]</option>
                                                            <option value="NP" label="Nepal">Nepal</option>
                                                            <option value="NT" label="Neutral Zone">Neutral Zone</option>
                                                            <option value="KP" label="North Korea">North Korea</option>
                                                            <option value="OM" label="Oman">Oman</option>
                                                            <option value="PK" label="Pakistan">Pakistan</option>
                                                            <option value="PS" label="Palestinian Territories">Palestinian Territories</option>
                                                            <option value="YD" label="People's Democratic Republic of Yemen">People's Democratic Republic of Yemen</option>
                                                            <option value="PH" label="Philippines">Philippines</option>
                                                            <option value="QA" label="Qatar">Qatar</option>
                                                            <option value="SA" label="Saudi Arabia">Saudi Arabia</option>
                                                            <option value="SG" label="Singapore">Singapore</option>
                                                            <option value="KR" label="South Korea">South Korea</option>
                                                            <option value="LK" label="Sri Lanka">Sri Lanka</option>
                                                            <option value="SY" label="Syria">Syria</option>
                                                            <option value="TW" label="Taiwan">Taiwan</option>
                                                            <option value="TJ" label="Tajikistan">Tajikistan</option>
                                                            <option value="TH" label="Thailand">Thailand</option>
                                                            <option value="TL" label="Timor-Leste">Timor-Leste</option>
                                                            <option value="TR" label="Turkey">Turkey</option>
                                                            <option value="™" label="Turkmenistan">Turkmenistan</option>
                                                            <option value="AE" label="United Arab Emirates">United Arab Emirates</option>
                                                            <option value="UZ" label="Uzbekistan">Uzbekistan</option>
                                                            <option value="VN" label="Vietnam">Vietnam</option>
                                                            <option value="YE" label="Yemen">Yemen</option>
                                                        </optgroup>
                                                        <optgroup id="country-optgroup-Europe" label="Europe">
                                                            <option value="AL" label="Albania">Albania</option>
                                                            <option value="AD" label="Andorra">Andorra</option>
                                                            <option value="AT" label="Austria">Austria</option>
                                                            <option value="BY" label="Belarus">Belarus</option>
                                                            <option value="BE" label="Belgium">Belgium</option>
                                                            <option value="BA" label="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                            <option value="BG" label="Bulgaria">Bulgaria</option>
                                                            <option value="HR" label="Croatia">Croatia</option>
                                                            <option value="CY" label="Cyprus">Cyprus</option>
                                                            <option value="CZ" label="Czech Republic">Czech Republic</option>
                                                            <option value="DK" label="Denmark">Denmark</option>
                                                            <option value="DD" label="East Germany">East Germany</option>
                                                            <option value="EE" label="Estonia">Estonia</option>
                                                            <option value="FO" label="Faroe Islands">Faroe Islands</option>
                                                            <option value="FI" label="Finland">Finland</option>
                                                            <option value="FR" label="France">France</option>
                                                            <option value="DE" label="Germany">Germany</option>
                                                            <option value="GI" label="Gibraltar">Gibraltar</option>
                                                            <option value="GR" label="Greece">Greece</option>
                                                            <option value="GG" label="Guernsey">Guernsey</option>
                                                            <option value="HU" label="Hungary">Hungary</option>
                                                            <option value="IS" label="Iceland">Iceland</option>
                                                            <option value="IE" label="Ireland">Ireland</option>
                                                            <option value="IM" label="Isle of Man">Isle of Man</option>
                                                            <option value="IT" label="Italy">Italy</option>
                                                            <option value="JE" label="Jersey">Jersey</option>
                                                            <option value="LV" label="Latvia">Latvia</option>
                                                            <option value="LI" label="Liechtenstein">Liechtenstein</option>
                                                            <option value="LT" label="Lithuania">Lithuania</option>
                                                            <option value="LU" label="Luxembourg">Luxembourg</option>
                                                            <option value="MK" label="Macedonia">Macedonia</option>
                                                            <option value="MT" label="Malta">Malta</option>
                                                            <option value="FX" label="Metropolitan France">Metropolitan France</option>
                                                            <option value="MD" label="Moldova">Moldova</option>
                                                            <option value="MC" label="Monaco">Monaco</option>
                                                            <option value="ME" label="Montenegro">Montenegro</option>
                                                            <option value="NL" label="Netherlands">Netherlands</option>
                                                            <option value="NO" label="Norway">Norway</option>
                                                            <option value="PL" label="Poland">Poland</option>
                                                            <option value="PT" label="Portugal">Portugal</option>
                                                            <option value="RO" label="Romania">Romania</option>
                                                            <option value="RU" label="Russia">Russia</option>
                                                            <option value="SM" label="San Marino">San Marino</option>
                                                            <option value="RS" label="Serbia">Serbia</option>
                                                            <option value="CS" label="Serbia and Montenegro">Serbia and Montenegro</option>
                                                            <option value="SK" label="Slovakia">Slovakia</option>
                                                            <option value="SI" label="Slovenia">Slovenia</option>
                                                            <option value="ES" label="Spain">Spain</option>
                                                            <option value="SJ" label="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                            <option value="SE" label="Sweden">Sweden</option>
                                                            <option value="CH" label="Switzerland">Switzerland</option>
                                                            <option value="UA" label="Ukraine">Ukraine</option>
                                                            <option value="SU" label="Union of Soviet Socialist Republics">Union of Soviet Socialist Republics</option>
                                                            <option value="GB" label="United Kingdom">United Kingdom</option>
                                                            <option value="VA" label="Vatican City">Vatican City</option>
                                                            <option value="AX" label="Åland Islands">Åland Islands</option>
                                                        </optgroup>
                                                        <optgroup id="country-optgroup-Oceania" label="Oceania">
                                                            <option value="AS" label="American Samoa">American Samoa</option>
                                                            <option value="AQ" label="Antarctica">Antarctica</option>
                                                            <option value="AU" label="Australia">Australia</option>
                                                            <option value="BV" label="Bouvet Island">Bouvet Island</option>
                                                            <option value="IO" label="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                            <option value="CX" label="Christmas Island">Christmas Island</option>
                                                            <option value="CC" label="Cocos [Keeling] Islands">Cocos [Keeling] Islands</option>
                                                            <option value="CK" label="Cook Islands">Cook Islands</option>
                                                            <option value="FJ" label="Fiji">Fiji</option>
                                                            <option value="PF" label="French Polynesia">French Polynesia</option>
                                                            <option value="TF" label="French Southern Territories">French Southern Territories</option>
                                                            <option value="GU" label="Guam">Guam</option>
                                                            <option value="HM" label="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                                                            <option value="KI" label="Kiribati">Kiribati</option>
                                                            <option value="MH" label="Marshall Islands">Marshall Islands</option>
                                                            <option value="FM" label="Micronesia">Micronesia</option>
                                                            <option value="NR" label="Nauru">Nauru</option>
                                                            <option value="NC" label="New Caledonia">New Caledonia</option>
                                                            <option value="NZ" label="New Zealand">New Zealand</option>
                                                            <option value="NU" label="Niue">Niue</option>
                                                            <option value="NF" label="Norfolk Island">Norfolk Island</option>
                                                            <option value="MP" label="Northern Mariana Islands">Northern Mariana Islands</option>
                                                            <option value="PW" label="Palau">Palau</option>
                                                            <option value="PG" label="Papua New Guinea">Papua New Guinea</option>
                                                            <option value="PN" label="Pitcairn Islands">Pitcairn Islands</option>
                                                            <option value="WS" label="Samoa">Samoa</option>
                                                            <option value="SB" label="Solomon Islands">Solomon Islands</option>
                                                            <option value="GS" label="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                                            <option value="TK" label="Tokelau">Tokelau</option>
                                                            <option value="TO" label="Tonga">Tonga</option>
                                                            <option value="TV" label="Tuvalu">Tuvalu</option>
                                                            <option value="UM" label="U.S. Minor Outlying Islands">U.S. Minor Outlying Islands</option>
                                                            <option value="VU" label="Vanuatu">Vanuatu</option>
                                                            <option value="WF" label="Wallis and Futuna">Wallis and Futuna</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label" for="payment_address">Address</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input name="payment_address" id="payment_address" maxlength="50" type="text" class="account-form-control form-control input-lg inp1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label" for="payment_city">City</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input name="payment_city" id="payment_city" maxlength="20" type="text" class="account-form-control form-control input-lg inp1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label" for="payment_state">State</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input name="payment_state" id="payment_state" type="text" class="account-form-control form-control input-lg inp1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label" for="payment_zipcode">Zip Code</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input name="payment_zipcode" id="payment_zipcode" maxlength="6" type="text" class="account-form-control form-control input-lg inp1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="billing-label" for="payment_cardnumber">Card Number</label>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input name="payment_cardnumber" id="payment_cardnumber" maxlength="20" type="text" class="account-form-control form-control input-lg inp1" required autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <label class="billing-label" for="payment_expmonth">Expiration Date</label>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="select-style">
                                                        <select name="payment_expmonth" id="payment_expmonth" class="input-lg inp1">
                                                            <option value="01" selected>January</option>
                                                            <option value="02">February</option>
                                                            <option value="03">March</option>
                                                            <option value="04">April</option>
                                                            <option value="05">May</option>
                                                            <option value="06">June</option>
                                                            <option value="07">July</option>
                                                            <option value="08">August</option>
                                                            <option value="09">September</option>
                                                            <option value="10">October</option>
                                                            <option value="11">November</option>
                                                            <option value="12">December</option>
                                                            <?php
                                                            /*$month = array('January','February','March','April','May','June','July','August','September','October','November','December');
        														 for ($i=0; $i<count($month); $i++)
        														   { echo '<option value="'.date('m',strtotime($month[$i])).'">'.$month[$i].'</option>';
        														   }*/
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="select-style">
                                                        <select name="payment_expyear" id="payment_expyear" class="input-lg inp1">
                                                            <?php
                                                            $year = date('Y');
                                                            for ($i = 1; $i <= 7; $i++) {
                                                                echo '<option value="' . substr($year, -2) . '" ' . ($i == 1 ? 'selected' : '') . '>' . $year . '</option>';
                                                                $year++;
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="billing-label" for="payment_cvc">CVC</label>&nbsp;&nbsp;&nbsp;<span title="Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.">What's This</span>
                                            <div class="form-group">
                                                <input type="text" name="payment_cvc" id="payment_cvc" maxlength="4" class="account-form-control form-control input-lg inp1" required autocomplete="off">
                                            </div>
                                        </div>
                                    </div><br>
                                    <!--<div class = "row">-->
                                    <!--    <div class="col-sm-6">-->
                                    <!--        <div class="form-group">-->
                                    <!--            <p class="whats-this-text"><a onClick="$('#win_details10').toggle('slow')">What's This</a></p>-->
                                    <!--            <div id="win_details10" style="display:none">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <style>
                                        span[title] {
                                            color: #888;
                                        }

                                        span[title]:hover::after {
                                            content: attr(title);
                                            position: absolute;
                                            top: 30px;
                                            background-color: grey;
                                            padding: 15px;
                                            border-radius: 5px;
                                            color: white;
                                            z-index: 10000;
                                            left: 0;
                                        }

                                        span[title]:hover::before {
                                            content: '';
                                            position: absolute;
                                            top: 25px;
                                            background-color: grey;
                                            z-index: 10000;
                                            left: 30%;
                                            width: 20px;
                                            height: 20px;
                                            transform: rotate(45deg);
                                        }

                                        #payment_submit1 {
                                            background-image: linear-gradient(180deg, #FACD61 0%, #F39F32 100%);
                                            border-radius: 5.4px;
                                            font-size: 14px;
                                            color: #333;
                                            border: 0;
                                            width: 100%;
                                            font-weight: bold;
                                        }

                                        .subscribe-btn,
                                        .subscribe-btn:hover,
                                        .subscribe-btn:focus {
                                            background-color: #058BEF;
                                            border: 0;
                                            border-radius: 5px;
                                        }

                                        .pay-with {}
                                    </style>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <span class="payment-errors"></span><br clear="all">
                                            <button name="payment_submit1" id="payment_submit1" type="button" class="process-payment-btn" data-toggle="modal" data-target="#myModal">Process My Payment</button>
                                            <!-- <div id="pay_loading" style="display:none"><img src="<?= SITE; ?>images/loading.gif" /></div> -->
                                            <br clear="all" />
                                            <div class="payment_modal_loader_sec">
                                                <div class="modal fade" id="pay_loading1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <!-- Modal body -->
                                                            <div class="modal-body">
                                                                <!-- <img src="<?= SITE; ?>images/loading.gif" /> -->
                                                                <div class="icons_loading">
                                                                    <a href=""><i class="fa fa-map-marker" aria-hidden="true"></i></a>&nbsp;
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href=""><i class="fa fa-fighter-jet" aria-hidden="true"></i></a>&nbsp;
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    <a href="" class="box_line"></a>
                                                                    &nbsp;<a href=""><i class="fa fa-map-marker" aria-hidden="true"></i></a>
                                                                </div>
                                                                <h4>Your payment is processing.<span>Please Wait.</span></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
            </form>
            <div class="col-lg-5 custommargin">
                <div class="card-box" id="pay_paypal">
                    <div class="pay-with-paypal">
                        <p class="pay-with">Pay with</p>
                        <img src="<?php echo SITE; ?>assets/images/paypal.png" alt="pay with paypal">
                        <form action="<?php echo $PAYPAL_URL; ?>" method="post" id="paymentPP_form">
                            <input type="hidden" name="cmd" value="_xclick" />
                            <input type="hidden" name="business" value="<?php echo $PAYPAL_EMAIL; ?>" />
                            <input type="hidden" name="item_name" id="item_name" value="<?php if ($userdata['account_type'] == 'Individual') echo 'Planiversity.com - Case by Case';
                                                                                        else echo 'Planiversity.com - Monthly Plan'; ?>" />
                            <input type="hidden" name="item_number" id="item_number" value="<?php echo $userdata['id'] . '-' . $_GET['idtrip']; ?>" />
                            <input type="hidden" name="amount" id="amount" value="<?php if ($userdata['account_type'] == 'Individual') echo '1.49';
                                                                                    else echo '24.99'; ?>" />
                            <input type="hidden" name="currency_code" value="USD" />
                            <input type="hidden" name="button_subtype" value="services" />
                            <input type="hidden" name="return" value="<?php echo ($_GET['idtrip'] ? SITE . 'trip/pdf/' . $_GET['idtrip'] : SITE . 'billing'); ?>" />
                            <input type="hidden" name="cancel_return" value="<?php echo SITE; ?>welcome" />
                            <input type="hidden" name="notify_url" value="<?php echo SITE; ?>notify_url.php" />
                            <center><input name="pay" type="submit" id="pay" value="CLICK BELOW TO BE DIRECTED TO PAYPAL" class="goto-paypal-btn subscribe-btn" /></center>
                        </form>
                    </div>
                </div><br clear="all">
                <div class="card-box" id="pay_paypal_monthly" style="margin-top:-20px;">
                    <div class="pay-with-paypal">
                        <p class="pay-with">Pay with</p>
                        <img src="<?php echo SITE; ?>assets/images/paypal.png" alt="pay with paypal">
                        <form action="<?php echo $PAYPAL_URL; ?>" method="post" id="paymentPP_form">
                            <input type="hidden" name="cmd" value="_xclick" />
                            <input type="hidden" name="business" value="<?php echo $PAYPAL_EMAIL; ?>" />
                            <input type="hidden" name="item_name" id="item_name" value="<?php if ($userdata['account_type'] == 'Individual') echo 'Planiversity.com - Case by Case';
                                                                                        else echo 'Planiversity.com - Monthly Plan'; ?>" />
                            <input type="hidden" name="item_number" id="item_number" value="<?php echo $userdata['id'] . '-' . $_GET['idtrip']; ?>" />
                            <input type="hidden" name="amount" id="amount" value="<?php if ($userdata['account_type'] == 'Individual') echo '1.49';
                                                                                    else echo '24.99'; ?>" />
                            <input type="hidden" name="currency_code" value="USD" />
                            <input type="hidden" name="button_subtype" value="services" />
                            <input type="hidden" name="return" value="<?php echo ($_GET['idtrip'] ? SITE . 'trip/pdf/' . $_GET['idtrip'] : SITE . 'billing'); ?>" />
                            <input type="hidden" name="cancel_return" value="<?php echo SITE; ?>welcome" />
                            <input type="hidden" name="notify_url" value="<?php echo SITE; ?>notify_url.php" />
                            <center><input name="pay" type="submit" id="pay" value="Subscribe" class="goto-paypal-btn subscribe-btn" style="width: 289px;margin: 28px auto;font-size: 15px;" /></center>
                        </form>
                    </div>
                </div><br clear="all">
            </div>


            <div id="myModal" class="modal fade show modal-custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog">
                    <div class="modal-content custom-modal-content">
                        <div class="modal-header custom-modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="checkbox checkbox-primary">
                                <input id="checkbox2" type="checkbox">
                                <label for="checkbox2">
                                    <p id="popupmess" class="mod-p">
                                        <?php
                                        if ($userdata['account_type'] == 'Individual')
                                            echo 'I authorize Planiversity, LLC to charge me a one-time payment of $1.49.';
                                        else
                                            echo 'I authorize Planiversity, LLC to bill this account monthly. I also understand that any requests for changes to billing must be submitted to Planiversity customer service.';
                                        ?>
                                    </p>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input name="payment_submit" id="payment_submit" type="submit" disabled class="accept-btn disabled-btn" value="I Accept" />
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function(e) {

                    $("#pay_loading1").modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: false
                    });

                });
            </script>
            <script type="text/javascript">
                //callback to handle the response from stripe
                function stripeResponseHandler(status, response) {
                    if (response.error) {
                        //enable the submit button
                        $('#payment_submit').removeAttr("disabled");
                        $('#payment_submit').removeClass('disabled-btn');
                        $('#payment_submit1').removeAttr("disabled");
                        $('#payment_submit1').removeClass('disabled-btn');
                        $('#pay_loading1').modal('hide');
                        //display the errors on the form
                        $(".payment-errors").html(response.error.message);
                    } else {
                        var form$ = $("#payment_form");
                        //get token id
                        var token = response['id'];
                        //insert the token into the form
                        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                        //submit form to the server
                        form$.get(0).submit();
                    }
                }
                Stripe.setPublishableKey('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
                $(document).ready(function() {
                    //on form submit
                    //$("#payment_form").submit(function(event) {
                    $("#payment_submit").click(function(event) {
                        //disable the submit button to prevent repeated clicks
                        $('#myModal').modal('hide');
                        $('.modal-backdrop').removeClass('modal-backdrop-custom');
                        document.getElementById('checkbox2').checked = false;
                        $('#payment_submit').attr("disabled", "1");
                        $('#payment_submit').addClass('disabled-btn');
                        $('#payment_submit1').attr("disabled", "1");
                        $('#payment_submit1').addClass('disabled-btn');
                        $('#pay_loading1').modal('show');

                        //create single-use token to charge the user
                        Stripe.createToken({
                            number: $('#payment_cardnumber').val(),
                            cvc: $('#payment_cvc').val(),
                            exp_month: $('#payment_expmonth').val(),
                            exp_year: $('#payment_expyear').val()
                        }, stripeResponseHandler);
                        return false;
                    });

                    $('#radio-case-by-case').click(function() {
                        $('#amount').val('1.49');
                        $('#item_name').val('Planiversity.com - Case by Case');
                        $('#popupmess').html('I authorize Planiversity, LLC to charge me a one-time payment of $1.49.');
                    });
                    $('#radio-monthly-plan-ind').click(function() {
                        $('#amount').val('4.99');
                        $('#item_name').val('Planiversity.com - Monthly Plan');
                        $('#popupmess').html('I authorize Planiversity, LLC to bill this account monthly. I also understand that any requests for changes to billing must be submitted to Planiversity customer service.');
                    });
                    $('#radio-monthly-plan').click(function() {
                        $('#amount').val('24.99');
                        $('#item_name').val('Planiversity.com - Monthly Plan');
                        $('#popupmess').html('I authorize Planiversity, LLC to bill this account monthly. I also understand that any requests for changes to billing must be submitted to Planiversity customer service.');
                    });
                    $('#radio-annual-plan').click(function() {
                        $('#amount').val('249.00');
                        $('#item_name').val('Planiversity.com - Annual Plan');
                        $('#popupmess').html('I authorize Planiversity, LLC to bill this account annual. I also understand that any requests for changes to billing must be submitted to Planiversity customer service.');
                    });
                    $('#checkbox2').click(function() {
                        if (document.getElementById('checkbox2').checked == true) {
                            $('#payment_submit').removeAttr('disabled');
                            $('#payment_submit').removeClass('disabled-btn');
                        } else {
                            $('#payment_submit').attr('disabled', '1');
                            $('#payment_submit').addClass('disabled-btn');
                        }
                    });
                });
                $('.modal-custom').on('show.bs.modal', function(e) {
                    setTimeout(function() {
                        $('.modal-backdrop').addClass('modal-backdrop-custom');
                    });
                });
                // paypal
                var account_type = '<?php echo $userdata['account_type']; ?>';

                function paypal_config() {
                    var monthly_plan_checked = $("#radio-monthly-plan").is(":checked");
                    if (monthly_plan_checked && account_type !== 'Individual') {
                        $("#pay_paypal").css("display", "none");
                        $("#pay_paypal_monthly").css("display", "block");
                    } else {
                        $("#pay_paypal").css("display", "block");
                        $("#pay_paypal_monthly").css("display", "none");
                    }
                }
                paypal_config();
                $(document).on("click", "#radio-monthly-plan, #radio-annual-plan", function() {
                    paypal_config();
                });
            </script>

            <?php include('new_backend_footer.php'); ?>
</body>

</html>
