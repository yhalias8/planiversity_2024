<?php
include_once("../config.ini.php");
$uuid = $_GET['uuid'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planiversity</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_once("includes/include_head.php"); ?>
</head>

<body>

    <?php
    $heading_title = "Order";
    include_once("includes/include_order_header.php");
    ?>

    <section class="shopping-cart dark">
        <div class="container">
            <div class="block-heading">
                <p>If you are a planiversity member, you will receive an additional 10% discount..</p>
            </div>
            <div class="content">
                <div class="row service_load">

                    <div class="loading_section" style="display: none;">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>

                    <div class="col-md-12 col-lg-8">
                        <div class="items">
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="summary" style="display: none;">
                            <h3>Summary</h3>
                            <div class="summary-item"><span class="text">Subtotal</span><span class="price">$<span id="subtotal">0</span></span></div>
                            <div class="summary-item"><span class="text">Discount</span><span class="price">$0</span></div>
                            <div class="summary-item"><span class="text">Shipping</span><span class="price">$0</span></div>
                            <div class="summary-item"><span class="text">Total</span><span class="price total">$<span id="total">0</span></span></div>
                            <button type="button" class="btn btn-primary btn-lg btn-block e_button guest">Guest Checkout</button>
                            <button type="button" class="btn btn-primary btn-lg btn-block e_button member">Member Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once("includes/include_order_script.php"); ?>
    <?php include_once("includes/include_footer.php"); ?>

</body>

</html>