<?php
session_start();
include_once("../config.ini.php");
if (isset($_SESSION['service_uuid'])) {
    $_SESSION['service_uuid'] = null;
}

if (isset($_GET['order_number']) && !empty($_GET['order_number'])) {
    $order_number = $_GET['order_number'];
} else {
    header('location: ../');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planiversity</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_once("includes/include_head.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
</head>

<body>

    <?php
    $heading_title = "Order Confirmation";
    include_once("includes/include_order_header.php");
    ?>

    <section class="shopping-cart dark">

        <div class="container">

            <div class="content">

                <div class="row service_load">

                    <div class="loading_section" style="display: none;">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>

                    <div class="col-md-12 col-lg-12">
                        <div class="items payment">

                            <div class="confirmation-section service_load">

                                <div class="order-place" style="display: none;">
                                    <div class="order-images">
                                        <img src="<?= SITE ?>assets/images/insurance.png" class="img-responsive">
                                    </div>
                                    <div class="order-content">
                                        <h2>Thank you for your order</h2>

                                        <div class="order-info">

                                        </div>



                                    </div>
                                </div>


                            </div>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <?php include_once("includes/include_order_confirmation.php"); ?>
    <?php include_once("includes/include_footer.php"); ?>

</body>

</html>