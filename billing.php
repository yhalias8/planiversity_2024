<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'billing/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}

include("class/class.Plan.php");
$plan = new Plan();
if ($plan->check_plan($userdata['id'])) { // if you have a plan export PDF
    header("Location:" . SITE . "trip/pdf/" . $_GET['idtrip']);
}

$secret_key = "sk_live_leKgOUwzYSORsCYXdQ7Tg3Oa"; //"sk_test_oKvUrVpbN31qQPs9T4KwH9XQ"; //
$publishable_key = "pk_live_wbt9OmxTfkNhl2a4eSMsYTBU"; //"pk_test_ziATmfXWa7k9OQ1D5itJmgE0"; //

$statusMsg = '';

//check whether stripe token is not empty
if (!empty($_POST['stripeToken'])) {
    //get token, card and user info from the form
    $token  = $_POST['stripeToken'];

    //set api key
    $stripe = array(
        "secret_key"      => $secret_key,
        "publishable_key" => $publishable_key
    );

    \Stripe\Stripe::setApiKey($stripe['secret_key']);

    //add customer to stripe
    $customer = \Stripe\Customer::create(array(
        'email' => $userdata['email'],
        'source'  => $token
    ));

    //item information
    $itemName = "Planiversity.com - " . $_POST["payment_type"];
    if ($userdata['account_type'] == 'Individual') {
        if ($_POST['payment_type'] == 'Monthly Plan')
            $Price = '9.99';
        else
            $Price = '1.29';
    } else {
        if ($_POST['payment_type'] == 'Monthly Plan')
            $Price = '10.99';
        else
            $Price = '99.99';
    }
    $itemPrice = str_replace(".", "", $Price);
    $currency = "usd";

    //charge a credit or a debit card
    $charge = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => $itemPrice,
        'currency' => $currency,
        'description' => $itemName,
    ));

    //retrieve charge details
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
        header("Location:" . SITE . "trip/pdf/" . $_GET['idtrip']);
    } else {
        $statusMsg = "Transaction has been failed";
    }
}

include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <title>PLANIVERSITY - BILLING PAGE</title>

    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?= SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
    <script src="<?= SITE; ?>js/responsive-nav.js"></script>

    <script src="<?= SITE; ?>js/jquery-1.11.3.js"></script>
    <script src="<?= SITE; ?>js/v1.js"></script>
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

<body class="inner_page inner_page2">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="content">

        <?php include('include_header.php') ?>

        <header class="cont_in bill_page_cont">
            <?php if ($userdata['account_type'] == 'Individual') { ?>
                <h2 class="style3">Sorry, one last thing ...</h2>
            <?php } else { ?>
                <h2 class="style3">Select Your Business Billing Plan</h2>
                <h1 class="style4">Unlimited Uses and Manage Your Own Travel Database</h1>
            <?php } ?>
        </header>

        <section class="cont_in marg_b2">

            <form action="" method="POST" id="payment_form">
                <div class="bill_cont_blue">
                    <?php if ($userdata['account_type'] == 'Individual') { ?>
                        <p><input name="payment_type" type="radio" value="Case by Case" class="rad2" checked="checked"><label><span>Case by Case</span><br />Only $1.29 per plan</label></p>
                        <p><input name="payment_type" type="radio" value="Monthly Plan" class="rad2"><label><span>Monthly Plan</span><br />Unlimited uses per month for $9.99</label></p>
                    <?php } else { ?>
                        <p><input name="payment_type" type="radio" value="Monthly Plan" class="rad2" checked="checked"><label><span>Monthly Plan</span><br />$10.99</label></p>
                        <p><input name="payment_type" type="radio" value="Annual Plan" class="rad2"><label><span>Annual Plan</span><br />$99.99</label></p>
                    <?php } ?>
                </div>

                <div class="bill_cont_l bill_cont_l_emp">
                    <span class="payment-errors"></span>
                    <div class="cont_card">
                        <!--<input name="card_type" type="radio" value="MasterCard" class="rad3" checked="checked">--><img src="<?= SITE; ?>images/img6.jpg" alt="" />
                        <!--<input name="card_type" type="radio" value="Visa" class="rad3">--><img src="<?= SITE; ?>images/img7.jpg" alt="" />
                        <!--<input name="card_type" type="radio" value="Amex" class="rad3">--><img src="<?= SITE; ?>images/img8.jpg" alt="" />
                        <!--<input name="card_type" type="radio" value="Discover" class="rad3">--><img src="<?= SITE; ?>images/img9.jpg" alt="" />
                    </div>
                    <div class="bill_cont_l_in">
                        <label class="label1">Name</label>
                        <input name="payment_fname" id="payment_fname" size="20" type="text" class="inp1 inp2" placeholder="First Name" value=""><input name="payment_lname" id="payment_lname" size="20" type="text" class="inp1 inp2" placeholder="Last Name" value=""><br />
                        <label class="label1">Country</label><input name="payment_country" id="payment_country" size="20" class="inp1" value=""><br />
                        <label class="label1">Address</label><input name="payment_address" id="payment_address" size="50" type="text" class="inp1" value=""><br />
                        <label class="label1">City</label><input name="payment_city" id="payment_city" size="20" type="text" class="inp1" value=""><br />
                        <label class="label1">State</label><input name="payment_state" id="payment_state" size="20" type="text" class="inp1 inp2" value=""><br />
                        <label class="label1">Zip Code</label><input name="payment_zipcode" id="payment_zipcode" size="6" type="text" class="inp1" value=""><br />
                        <label class="label1">Card Number</label><input name="payment_cardnumber" id="payment_cardnumber" size="20" type="text" class="inp1" value="" autocomplete="off"><br />
                        <label class="label1">Expiration Date</label>
                        <select class="inp1 inp2" name="payment_expmonth" id="payment_expmonth">
                            <?php
                            $month = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                            for ($i = 0; $i < count($month); $i++) {
                                echo '<option value="' . date('m', strtotime($month[$i])) . '">' . $month[$i] . '</option>';
                            }
                            ?>
                        </select>
                        <select class="inp1 inp2" name="payment_expyear" id="payment_expyear">
                            <?php
                            $year = date('Y');
                            for ($i = 1; $i <= 6; $i++) {
                                echo '<option value="' . date('y', strtotime($year)) . '">' . $year . '</option>';
                                $year++;
                            }
                            ?>
                        </select><br />
                        <label class="label1">CVC</label><input name="payment_cvc" id="payment_cvc" size="4" type="text" class="inp1 inp2" value="" autocomplete="off">
                        <a onClick="$('#win_details10').toggle('slow')" class="add">What's That</a>
                        <div id="win_details10" style="display:none">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
                        <input name="payment_submit" id="payment_submit" type="submit" value="Process My Payment" class="button button2">
                    </div>

                </div>
            </form>

            <script type="text/javascript">
                Stripe.setPublishableKey('<?= $publishable_key; ?>');

                //callback to handle the response from stripe
                function stripeResponseHandler(status, response) {
                    if (response.error) {
                        //enable the submit button
                        $('#payment_submit').removeAttr("disabled");
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
                $(document).ready(function() {
                    //on form submit
                    $("#payment_form").submit(function(event) {
                        //disable the submit button to prevent repeated clicks
                        $('#payment_submit').attr("disabled", "disabled");

                        //create single-use token to charge the user
                        Stripe.createToken({
                            number: $('#payment_cardnumber').val(),
                            cvc: $('#payment_cvc').val(),
                            exp_month: $('#payment_expmonth').val(),
                            exp_year: $('#payment_expyear').val()
                        }, stripeResponseHandler);

                        //submit from callback
                        return false;
                    });
                });
            </script>

            <div class="bill_cont_r">
                <?= $statusMsg; ?>
                <!--<h1>Pay with<img src="images/img_paypal.png" alt="" /></h1>
             <a href="#" class="button bt_blue">CLICK HERE TO BE DIRECTED TO PAYPAL</a>-->
            </div>



        </section>

    </div>


    <footer class="footer"><?php include('include_footer.php'); ?></footer>

</body>

</html>