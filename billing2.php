<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'welcome';
    header("Location:" . SITE . "login");
}

include('include_doctype.php');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Commission Genie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <?php include('dashboard/include/css.php'); ?>

    <script src="<?= SITE ?>dashboard/js/jquery-3.6.0.js"></script>

    <script>
        var SITE = '<?= SITE; ?>';
    </script>

    <!--Facebook Pixel Code-->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };

            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';

            n.queue = [];
            t = b.createElement(e);
            t.async = !0;

            t.src = v;
            s = b.getElementsByTagName(e)[0];

            s.parentNode.insertBefore(t, s)
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '871547440200746');

        fbq('track', 'PageView');
    </script>

    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=871547440200746&ev=PageView&noscript=1" />
    </noscript>
    <!--End Facebook Pixel Code-->
    <?php include('include_google_analitics.php') ?>
</head>

<?php

$img = SITE . 'images/my_profile_icon.png';
if ($userdata['picture']) $img = SITE . 'ajaxfiles/profile/' . $userdata['picture'];
?>

<body>
    <section class="dashboard_mail_sec">
        <div class="container-fluid">
            <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
                <div class="app-header header-shadow">
                    <div class="app-header__logo">
                        <div class="logo-src">
                            <h3>Planiversity</h3>
                        </div>
                        <div class="header__pane ml-auto">
                            <div>
                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="app-header__mobile-menu">
                        <div>
                            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="app-header__content">

                        <div class="app-header-right">
                            <div class="header-btn-lg pr-0">
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left user_account_infor">
                                            <div class="btn-group">
                                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                                    <i class="bi bi-chevron-down"></i>
                                                </a>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                                    <ul class="list-unstyled">
                                                        <?php
                                                        if ($userdata['account_type'] == 'Admin') {
                                                        ?>
                                                            <li>
                                                                <a class="dropdown-item drop-menu-item" href="<?= SITE; ?>apanel/users" target="_blank">
                                                                    Admin
                                                                </a>
                                                            </li>
                                                        <?php } ?>

                                                        <li>
                                                            <a href="<?= SITE; ?>about-us" class="dropdown-item drop-menu-item" target="_blank">
                                                                About Us
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE; ?>select-your-payment" class="dropdown-item drop-menu-item" target="_blank">
                                                                What It Costs
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE; ?>faq" class="dropdown-item drop-menu-item" target="_blank">
                                                                FAQs
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE; ?>data-security" class="dropdown-item drop-menu-item" target="_blank">
                                                                Data Security
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE; ?>contact-us" class="dropdown-item drop-menu-item" target="_blank">
                                                                Contact Us
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE ?>blog" class="dropdown-item drop-menu-item" target="_blank">
                                                                Blog
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE; ?>leave" class="dropdown-item drop-menu-item" target="_blank">
                                                                Delete Account
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE; ?>logout" class="dropdown-item drop-menu-item">
                                                                Logout
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-content-left  ml-3 header-user-info">
                                            <div class="widget-heading">
                                                <h6><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?></h6>
                                            </div>
                                        </div>
                                        <div class="header_users widget-content-right header-user-info ml-3">
                                            <div class="heade_user_img">
                                                <img src="<?= $img ?>" class="rounded-circle">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-main">
                    <div class="row dashboard_main_row">
                        <div class="col-xl-2 left_menu_colom3">
                            <div class="navbar_left_sec">
                                <div class="app-sidebar sidebar-shadow left_menubar_sec">
                                    <div class="app-header__logo">
                                        <div class="logo-src"></div>
                                        <div class="header__pane ml-auto">
                                            <div>
                                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                                    <span class="hamburger-box">
                                                        <span class="hamburger-inner"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="app-header__mobile-menu">
                                        <div>
                                            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                                <span class="hamburger-box">
                                                    <span class="hamburger-inner"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="scrollbar-sidebar">
                                        <?php include('dashboard/include/navigation.php'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-10 right_content_colom9">
                            <div class="app-main__outer">
                                <div class="app-main__inner">
                                    <div class="">
                                        <div class="launch_commision_item_sec">
                                            <div class="dashboard_user_sec">
                                                <div class="user_header">
                                                    <div class="row">
                                                        <div class="col-xl-6">
                                                            <div class="user_header_left_item">
                                                                <div class="user_header_list">
                                                                    <!-- <div class="user_header_img">
                                                                        <img src="<?= $img ?>">
                                                                    </div> -->
                                                                    <div class="user_header_text">
                                                                        <h4><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?> <span class="business_user"><?= $userdata['account_type']; ?> USER</span></h4>
                                                                        <h6>Customer#: <?= strtoupper($userdata['customer_number']) ?></h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6">
                                                            <div class="start_a_plan_btn">
                                                                <a href="<?= SITE; ?>trip/how-are-you-traveling"><span>Start a new plan</span>&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <section class="billing_page_content_sec">
                                                    <div class="row">
                                                        <div class="col-xl-5 col-12">
                                                            <div class="billing_content_left_item">
                                                                <div class="billing_content_heading">
                                                                    <h6>planversity</h6>
                                                                    <h2>Billing History</h2>
                                                                </div>
                                                                <div class="current_plan_item">
                                                                    <h2>Current Plan</h2>
                                                                    <h6>Expires 25/08/2022</h6>
                                                                </div>
                                                                <div class="plan_history_item_seec">
                                                                    <ul class="list-unstyled">
                                                                        <li>
                                                                            <div class="plan_history_items">
                                                                                <div class="row">
                                                                                    <div class="col-xl-8 col-8">
                                                                                        <h3>Plan History</h3>
                                                                                    </div>
                                                                                    <div class="col-xl-4 col-4">
                                                                                        <h5>Credit: $ 0.00</h5>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="plan_history_items">
                                                                                <div class="row">
                                                                                    <div class="col-xl-8 col-8">
                                                                                        <h4>Plan 01 - Business Account</h4>
                                                                                        <p>15/01/2022 - 15/01/2023</p>
                                                                                    </div>
                                                                                    <div class="col-xl-4 col-4">
                                                                                        <h5>$280.00</h5>
                                                                                        <h6>Paid</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="plan_history_items">
                                                                                <div class="row">
                                                                                    <div class="col-xl-8 col-8">
                                                                                        <h4>Plan 02 - Business Account</h4>
                                                                                        <p>15/01/2022 - 15/01/2022</p>
                                                                                    </div>
                                                                                    <div class="col-xl-4 col-4">
                                                                                        <h5>$280.00</h5>
                                                                                        <h6>Paid</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        <li>
                                                                            <div class="plan_history_items">
                                                                                <div class="row">
                                                                                    <div class="col-xl-8 col-8">
                                                                                        <h4>Plan 02 - Business Account</h4>
                                                                                        <p>15/01/2022 - 15/01/2021</p>
                                                                                    </div>
                                                                                    <div class="col-xl-4 col-4">
                                                                                        <h5>$280.00</h5>
                                                                                        <h6>Paid</h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                    <div class="upgrade_account_btn">
                                                                        <button type="submit" class="btn_btn">Upgrade Account</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-7 col-12">
                                                            <div class="renew_select_your_personal_sec">
                                                                <div class="billing_content_heading">
                                                                    <h6>&nbsp;</h6>
                                                                    <h2><span>Renew or Select</span> your personal billing plan</h2>
                                                                </div>
                                                                <div class="monthly_plan_radio">
                                                                    <ul class="list-unstyled">
                                                                        <li class="nav_active">
                                                                            <label class="redio_button_desin"><span>Monthly Plan</span> Only $14.99 per plan
                                                                                <input type="radio" checked="checked" name="radio">
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </li>
                                                                        <li>
                                                                            <label class="redio_button_desin"><span>Annual Plan</span> Only $149.00
                                                                                <input type="radio" name="radio">
                                                                                <span class="checkmark"></span>
                                                                            </label>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="billing_information_sec">
                                                                    <div class="billing_sub_head">
                                                                        <h4>Billing Information</h4>
                                                                    </div>
                                                                    <div class="billing_infomation_input">
                                                                        <ul class="list-unstyled">
                                                                            <li>
                                                                                <input type="radio" id="visa" name="biling_card" value="Visa">
                                                                                <label for="visa" class="visa_img">
                                                                                    <img src="<?php echo SITE; ?>/assets/billing2/images/visa_img.png">
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <input type="radio" id="mastercard" name="biling_card" value="Mastercard">
                                                                                <label for="mastercard" class="mastercard_img">
                                                                                    <img src="<?php echo SITE; ?>/assets/billing2/images/mastercard_img.png">
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <input type="radio" id="discover_img" name="biling_card" value="Discover">
                                                                                <label for="discover_img" class="discover_img">
                                                                                    <img src="<?php echo SITE; ?>/assets/billing2/images/discover_img.png">
                                                                                </label>
                                                                            </li>
                                                                            <li>
                                                                                <input type="radio" id="americal_img" name="american_express" value="American express">
                                                                                <label for="americal_img" class="americal_img">
                                                                                    <img src="<?php echo SITE; ?>/assets/billing2/images/americal_img.png">
                                                                                </label>
                                                                            </li>
                                                                        </ul>
                                                                        <div class="by_with_apple_btn">
                                                                            <button type="submit" class="btn_apple">Buy wit <i class="bi bi-apple"></i>Pay</button>
                                                                        </div>
                                                                        <div class="billing_paypal_img text-center">
                                                                            <img src="<?php echo SITE; ?>/assets/billing2/images/paypal_img.png">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="personal_information_form_sec">
                                                                    <div class="billing_sub_head">
                                                                        <h4>Personal Information</h4>
                                                                    </div>
                                                                    <div class="personal_information_form">
                                                                        <form action="" method="post">
                                                                            <div class="personal_information_form_item">
                                                                                <div class="row">
                                                                                    <div class="col-xl-6 col-12 padding_left form_group">
                                                                                        <label>First Name</label>
                                                                                        <input type="text" name="" class="form-control" placeholder="Enter First Name">
                                                                                    </div>
                                                                                    <div class="col-xl-6 col-12 form_group">
                                                                                        <label>Last Name</label>
                                                                                        <input type="text" name="" class="form-control" placeholder="Enter Second Name">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-xl-12 padding_left form_group">
                                                                                        <label>Country</label>
                                                                                        <select name="" class="form-control">
                                                                                            <option selected>Select Country</option>
                                                                                            <option value="">India</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-xl-12 padding_left form_group">
                                                                                        <label>Address</label>
                                                                                        <input type="text" name="" class="form-control" placeholder="Address">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-xl-12 padding_left form_group">
                                                                                        <label>City</label>
                                                                                        <input type="text" name="" class="form-control" placeholder="Enter your city">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-xl-12 padding_left form_group">
                                                                                        <label>State</label>
                                                                                        <input type="text" name="" class="form-control" placeholder="Residential">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-xl-12 padding_left form_group">
                                                                                        <label>Zip Code</label>
                                                                                        <input type="tel" name="" class="form-control" placeholder="Enter your Zip Code">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="inter_details_card">
                                                                                <div class="row">
                                                                                    <div class="col-xl-12 padding_left form_group">
                                                                                        <label>Card Number</label>
                                                                                        <input type="tel" name="" class="form-control" placeholder="Enter your Card Number">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-xl-4 col-12 padding_left form_group">
                                                                                        <label>Expiration Date</label>
                                                                                        <select class="form-control" name="">
                                                                                            <option selected>Month</option>
                                                                                            <option value="">January</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-xl-4 col-12 form_group">
                                                                                        <label>&nbsp;</label>
                                                                                        <select class="form-control" name="">
                                                                                            <option selected>Year</option>
                                                                                            <option value="">2022</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-xl-4 col-12 form_group">
                                                                                        <label>CVC <span class="what_is_this">What’s This?</span></label>
                                                                                        <input type="tel" class="form-control" placeholder="Enter CVC">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="Have_coupon_item">
                                                                                    <div class="row">
                                                                                        <div class="col-xl-12 padding_left form_group">
                                                                                            <button type="button" class="btn_coupon" data-toggle="collapse" data-target="#promo_code">
                                                                                                <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                                                                                                &nbsp;Have a coupon? Click here to enter your code</button>
                                                                                            <div class="coupon_code_input_item collapse" id="promo_code">
                                                                                                <form action="" method="post">
                                                                                                    <div class="row">
                                                                                                        <div class="col-xl-8 col-lg-8 col-7 form_group">
                                                                                                            <input type="text" class="form-control" name="" placeholder="Coupon code">
                                                                                                        </div>
                                                                                                        <div class="col-xl-4 col-5 form_group">
                                                                                                            <button type="submit" class="btn_coupon">Apply coupon</button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-xl-12 padding_left form_group">
                                                                                        <label>Order notes (optional)</label>
                                                                                        <textarea name="" rows="5" class="form-control" placeholder="Notes about your order e.g. Special Delivery"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="process_payment_sec">
                                                                                    <button type="submit" class="btn_btn">Process Payment</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard_footer">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="privacy_and_policy_list">
                                            <a href="">Privacy</a>
                                            <a href="">Terms of Service</a>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="copy_write">
                                            <p>&copy;Copyright.2015-2020 Planiversity, LLC. All Right Reserved.</p>
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

    <script type="text/javascript" src="<?= SITE ?>dashboard/js/main.js"></script>
    <script src="<?= SITE ?>dashboard/js/jquery-ui.js"></script>

    <script>
        var timezone_offset_minutes = new Date().getTimezoneOffset();
        timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;

        function delete_trip(id) {
            var valid = confirm('Are you sure? want to delete');
            if (valid) {
                $('<form action="" method="POST"></form>').append('<input name="group_del" value="' + id + '" />').appendTo('body').submit();
            } else {
                return false;
            }
        }

        function change_scale(scale) {
            $('<form action="" method="POST"></form>').append('<input name="scale" value="' + scale + '" />').appendTo('body').submit();
        }

        function google_sync(e) {
            var gval = '';
            if (e == 1) {
                gval = 1;
            } else {
                gval = 0;
            }
            $('<form action="" method="POST"></form>')
                .append('<input name="ggcal" value="' + gval + '" />')
                .append('<input name="ggcaltmp" value="checked" />')
                .append('<input name="ggcaltimezone" value="' + timezone_offset_minutes + '" />')
                .appendTo('body').submit();
        }

        $(document).ready(function() {
            $(".trips").click(function() {
                $(".fc-trips-button").click();
            });
            $(".events").click(function() {
                $(".fc-events-button").click();
            });
            $(".meetings").click(function() {
                $(".fc-meetings-button").click();
            });
            $(".all").click(function() {
                $(".fc-alls-button").click();
            });

            $("#toggle-password-1").click(function() {
                if ($("#myprofile_cpass").attr("type") == 'password') {
                    $("#myprofile_cpass").attr("type", "text");

                } else if ($("#myprofile_cpass").attr("type") == 'text') {
                    $("#myprofile_cpass").attr("type", "password");
                }
            });

            $("#toggle-password-2").click(function() {
                if ($("#myprofile_npass").attr("type") == 'password') {
                    $("#myprofile_npass").attr("type", "text");

                } else if ($("#myprofile_npass").attr("type") == 'text') {
                    $("#myprofile_npass").attr("type", "password");
                }
            });

            $(document).on("click", "a.fc-day-grid-event", function() {
                event.stopImmediatePropagation();
                event.stopPropagation();
                var url = $(this).attr("href");
                window.open(url, "_blank");
                return false;
            });

        });
    </script>
</body>

</html>