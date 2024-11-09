<?php
include_once("../config.ini.php");
//$uuid = $_GET['uuid'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planiversity</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_once("includes/include_head.php"); ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
</head>

<body>

    <?php
    $heading_title = "Guest Checkout";
    include_once("includes/include_order_header.php");
    ?>

    <section class="shopping-cart dark">

        <div class="container">



            <div class="content">

                <div class="mb-2">
                    <button type="button" class="btn btn-info back_button"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>
                </div>
                <div class="row service_load">

                    <div class="loading_section" style="display: none;">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>

                    <div class="col-md-12 col-lg-8">
                        <div class="items payment">

                            <form id="payment-form" class="payment-form">





                                <div class="form-row">

                                    <div class="col-xl-6 col-12 padding_left">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" name="fname" id="fname" class="form-control reset_value valid" placeholder="john" required>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-12">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" name="lname" id="lname" class="form-control reset_value" placeholder="Doe" required>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 col-12 padding_left">

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" id="email" class="form-control reset_value valid" placeholder="janedoe@gmail.com" required>
                                        </div>

                                    </div>

                                    <div class="col-xl-12 col-12 padding_left">

                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" id="address" class="form-control reset_value valid" placeholder="185 Berry St" required>
                                        </div>

                                    </div>

                                    <div class="col-xl-4 col-12">
                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" name="city" id="city" class="form-control reset_value" placeholder="San Francisco" required>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-12">
                                        <div class="form-group">
                                            <label>State</label>
                                            <input type="text" name="state" id="state" class="form-control reset_value" placeholder="CA" required>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-12">
                                        <div class="form-group">
                                            <label>Zip</label>
                                            <input type="text" name="zip" id="zip" class="form-control reset_value" placeholder="94107" required>
                                        </div>
                                    </div>

                                </div>


                                <div class="form-row">
                                    <div id="card-element">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <!-- Used to display form errors. -->
                                    <div id="card-errors" role="alert"></div>
                                </div>

                                <button class="btn pay-btn stripe_process"><i class="fa fa-lock"></i> Make Payment</button>
                            </form>

                            <div class="payment-option-divider">OR</div>

                            <div class="paypal-payment">
                                <div id="paypal-button-container"></div>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">



                        <div class="summary">

                            <div class="order-summary">

                                <div class="service-image">
                                    <img src="https://localhost/master/admin-app/public/storage/uploads/images/service/cnroZ9ZSwpydQCVCCGTbQUjytQT7a5BudSwhFmCf-1672921623.jpg">
                                </div>

                                <div class="service-title">
                                    <h4>I will create modern flat design illustration</h4>
                                </div>

                                <div class="service-price">
                                    $120
                                </div>


                            </div>

                            <div class="summary-item"><span class="text">Subtotal</span><span class="price">$<span id="subtotal">120</span></span></div>
                            <div class="summary-item"><span class="text">Discount</span><span class="price">$0</span></div>
                            <div class="summary-item"><span class="text">Shipping</span><span class="price">$0</span></div>
                            <div class="summary-item"><span class="text">Total</span><span class="price total">$<span id="total">120</span></span></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include_once("includes/include_guest_script.php"); ?>
    <?php include_once("includes/include_footer.php"); ?>

</body>

</html>