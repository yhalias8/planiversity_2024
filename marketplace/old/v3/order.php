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

    <?php include_once("includes/include_order_header.php"); ?>




    <section class="shopping-cart dark">
        <div class="container">
            <div class="block-heading">
                <p>If you are a planiversity member, you will receive an additional 10% discount..</p>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="items">
                            <div class="product">
                                <div class="row">
                                    <div class="col-md-3">
                                        <img class="img-fluid mx-auto d-block image" src="https://admin.planiversity.com/public/storage/uploads/images/service/01.jpg">
                                    </div>
                                    <div class=" col-md-8">
                                        <div class="info">
                                            <div class="row">
                                                <div class="col-md-8 product-name">
                                                    <div class="product-name">
                                                        <a href="#">I will design best modern websites in figma</a>
                                                        <div class="product-info">
                                                            <div>Category: <span class="value">Courses & Learning</span></div>
                                                            <div>Author: <span class="value">Wanda Runo</span></div>
                                                            <div>Reviews: <span class="value">684 reviews</span></div>
                                                            <div>Quantity: <span class="value">1</span></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 price">
                                                    <span>$360</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="summary">
                            <h3>Summary</h3>
                            <div class="summary-item"><span class="text">Subtotal</span><span class="price">$360</span></div>
                            <div class="summary-item"><span class="text">Discount</span><span class="price">$0</span></div>
                            <div class="summary-item"><span class="text">Shipping</span><span class="price">$0</span></div>
                            <div class="summary-item"><span class="text">Total</span><span class="price">$360</span></div>
                            <button type="button" class="btn btn-primary btn-lg btn-block">Guest Checkout</button>
                            <button type="button" class="btn btn-primary btn-lg btn-block">Member Checkout</button>
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