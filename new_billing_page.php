<?php
include_once("config.ini.php");
include("class/class.Plan.php");
$plan = new Plan();

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'welcome';
    header("Location:" . SITE . "login");
}

$statusMsg = '';
$plan = new Plan();
$ref = null;

if (isset($_GET['idtrip']) && !empty($_GET['idtrip'])) {
    $ref = $_GET['idtrip'];
}

include('include_doctype.php');

$msg = '';

if (isset($_POST['group_del']) && !empty($_POST['group_del'])) {
    $query = "DELETE FROM `trips` WHERE `trips`.`id_trip` in (" . $_POST['group_del'] . ")";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
    $tmp = $stmt->execute();
}

$output = '';
if (isset($_POST['myprofile_submit'])) {
    $curr_password = $_POST['myprofile_cpass'];
    $new_password = $_POST['myprofile_npass'];
    $confirm_password = $_POST['myprofile_nrpass'];
    $result = $auth->changePassword($userdata['id'], $curr_password, $new_password, $confirm_password, null);

    $output = $result['message'];
}

$member_count = 10;

$stmt_now = $dbh->prepare("SELECT team_members FROM users WHERE id = ? ");
$stmt_now->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$tmp_now = $stmt_now->execute();
$timelines_now = [];
$timelines_now = $stmt_now->fetch(PDO::FETCH_OBJ);
if ($timelines_now->team_members != "0") {
    $member_count = $timelines_now->team_members;
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <title>Planiversity | Billing Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= SITE; ?>images/favicon.png">

    <?php include('dashboard/include/css.php'); ?>
    <link href="<?= SITE ?>assets/billing/css/mystyle.css?v=20230621" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">

    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <script src="<?= SITE ?>dashboard/js/jquery-3.6.0.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>

    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

    <script>
        var SITE = '<?= SITE; ?>';
        var ref = '<?= $ref; ?>';
    </script>

    <script src="https://www.paypal.com/sdk/js?client-id=AYxollChVXGGuLHujuvXj1rm3aBWpimsBMKqcnR27pyYvA1RPkudsfU8sraPvLv47weg3Iro5ZGUb4Pc&vault=true&intent=subscription&disable-funding=credit,card" data-sdk-integration-source="button-factory"></script>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>

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
    <?php //include('include_google_analitics.php') 
    ?>

    <?php

    function change_date_format($mDate)
    {
        $date = date_create($mDate);
        return date_format($date, "F j, Y");
    }

    if ($userdata['account_type'] == 'Individual') {
        $account_type = "individual";
        $plan_type = "Individual Account";
        $plan_id = PAYPAL_INDIVIDUAL_PLAN_ID;
        $plan_hold = "personal billing plan";
        $user_payment_status = $plan->individual_check_plan($userdata['id']);
        $credit = $plan->get_total_credit($userdata['id']);
        $user_active_plan = $plan->get_current_plan($userdata['id']);
        $monthly_price = 10.99;
        $annual_price = 120.00;
        $expire_label = "FREE";
    } else {
        $account_type = "business";
        $plan_type = "Business Account";
        $plan_id = PAYPAL_BUSINESS_PLAN_ID;
        $plan_hold = "your business billing plan";
        $user_payment_status = $plan->check_plan($userdata['id']);
        $credit = $plan->get_total_credit(40);
        $user_active_plan = $plan->get_current_plan($userdata['id']);
        $monthly_price = 49.99;
        $annual_price = 549;
        $expire_label = "Need to Add Credit";
    }


    $htmt = $dbh->prepare("SELECT plan_type,DATE_FORMAT(date_paid,'%Y-%m-%d') as date_paid,DATE_FORMAT(date_expire,'%Y-%m-%d') as date_expire,amount FROM payments WHERE id_user=? and status=?");
    $htmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
    $htmt->bindValue(2, "succeeded", PDO::PARAM_STR);
    $tmpp = $htmt->execute();
    $aux = '';
    $paymentListData = [];
    if ($tmpp && $htmt->rowCount() > 0) {
        $paymentListData = $htmt->fetchAll(PDO::FETCH_OBJ);
    }

    $paymentListCount = count($paymentListData);

    ?>


    <style>
        .toggle-password {
            position: absolute;
            cursor: pointer;
            z-index: 9999;
            right: 25px;
            top: 5px;
        }

        .make-payment-btn,
        .make-payment-btn:focus {
            padding: 6px 10px;
            display: block;
            font-size: 13px;
            text-align: center;
            border-radius: 50px;
            color: #fff !important;
            text-decoration: none !important;
            outline: none;
            margin-top: -5px;
            background: linear-gradient(to right, #eea849, #f46b45);
        }

        .btn-primary.focus,
        .btn-primary:focus {
            color: #000;
            background-color: transparent;
            border-color: #0062cc;
            box-shadow: none;
        }

        .modal-backdrop {
            z-index: 9;
        }

        #upload-demo {
            margin: auto;
        }

        label.error {
            color: red;
            position: relative;
            bottom: 15px;
        }

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
            font-size: 14px;
            z-index: 10000;
            left: 0;
            text-align: justify;
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

        .checkbox label {
            display: inline-block;
            padding-left: 5px;
            position: relative;
            font-weight: 500;
            font-size: 12px;
            cursor: pointer;
            left: 20px;
        }

        .custom-modal-header {
            border-bottom-width: 1px !important;
            border-bottom: none !important;
            margin: 0 !important;
            padding: 28px 28px 0;
        }

        .mod-p {
            color: #333;
            font-size: 15px;
            font-weight: normal;
            margin: -4px 10px;
            position: relative;
            bottom: 13px;
            left: 10px;
            text-align: justify;
            padding: 0px 5px;
        }

        #payment_auth_confirm .modal-dialog .modal-content .modal-body {
            padding: 20px 20px;
            background: #f8f9fa;
        }

        #payment_auth_confirm .modal-footer {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end;
            padding: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .accept-btn,
        .accept-btn:focus,
        .make-payment-btn,
        .make-payment-btn:focus,
        .dismiss-btn {
            padding: 5px 18px;
            font-size: 17px;
            border-radius: 0px;
            color: #fff;
            outline: none;
            background: #f7973d;
            border: 1px solid #f7973d;
            cursor: pointer;
        }

        .accept-btn:hover,
        .make-payment-btn:hover,
        .dismiss-btn:hover {
            padding: 5px 18px;
            font-size: 17px;
            border-radius: 0px;
            color: #fff;
            outline: none;
            background: #ec8a2d;

        }

        .payment_modal_loader_sec .modal.show {
            background-color: rgb(0 45 84 / 83%) !important;
        }

        .payment_modal_loader_sec .modal {
            margin-bottom: 0px !important;
            top: 0px;
            z-index: 9999;
        }

        .payment_modal_loader_sec .modal-dialog {
            box-shadow: none;
        }

        .payment_modal_loader_sec .modal-content {
            margin-top: 118px;
            background: none;
            box-shadow: none;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            border: 0;
        }

        .payment_modal_loader_sec .icons_loading {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .payment_modal_loader_sec .icons_loading a {
            color: #fff;
            font-size: 25px;
        }

        .payment_modal_loader_sec h4 {
            color: #fff;
            font-size: 25px;
            font-weight: 600;
            line-height: 34px;
        }

        .payment_modal_loader_sec h4 span {
            display: block;
        }

        .payment_modal_loader_sec .icons_loading .box_line {
            width: 10px;
            height: 4px;
            background-color: #fff;
            display: block;
            margin-right: 5px;
            border-radius: 4px;
        }

        .payment_modal_loader_sec .icons_loading a {
            color: #fff;
            font-size: 25px;
        }

        .sweet-alert h2 {
            font-size: 18px;
        }

        #payment_auth {
            padding: 0;
            margin: 0;
        }

        p.coupon_response {
            color: #df2d2d;
            font-size: 14px;
        }

        span.code_place {
            color: #0753a9;
            padding: 5px;
        }

        span.old-price {
            text-decoration: line-through;
            font-weight: normal;
        }

        label.redio_button_desin p.price-breakdown {
            display: inline-block;
            font-weight: normal;
            margin: 0;
        }

        .plan_history_item_seec ul {
            min-height: 200px;
            height: 300px;
            overflow-y: scroll;
            margin-right: 10px;
        }

        .plan_history_item_seec ul::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        .plan_history_item_seec ul::-webkit-scrollbar-track {
            background: #f1f1f1;

        }

        /* Handle */
        .plan_history_item_seec ul::-webkit-scrollbar-thumb {
            background: linear-gradient(#fac85c, #f5ab3f);
        }

        /* Handle on hover */
        .plan_history_item_seec ul::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(#fac85c, #f5ab3f);
        }

        .payment_separeate p {
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #1d74b7;
        }

        .form_group.menu_checkbox {
            display: block;
        }

        input.form-control.load_previous {
            background: #4c7fb8 !important;
        }

        input.form-control.enable_payment {
            background: #4c7fb8 !important;
        }

        input#enable_payment_option:disabled {
            opacity: 0.5;
            background: #546370 !important;
        }

        input#confirm {
            position: relative;
            top: 5px;
        }

        .auto-payment {
            background: #0c246c;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
        }

        .auto-payment h2 {
            color: #fff;
        }

        .saved {
            width: 20px;
            height: 20px;
        }
        }

        }
    </style>
</head>

<?php

$img = SITE . 'images/my_profile_icon.png';
if ($userdata['picture']) $img = SITE . 'ajaxfiles/profile/' . $userdata['picture'];

function get_employee($id_employee)
{
    global $dbh;
    $stmt = $dbh->prepare("SELECT * FROM `employees` WHERE `id_employee` = ?");
    $stmt->bindValue(1, $id_employee, PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $employee = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $employee[0]->f_name . ' ' . $employee[0]->l_name;
}

if (isset($_GET['del']) && !empty($_GET['del'])) {
    $query = "DELETE FROM `trips` WHERE `trips`.`id_trip` = " . $_GET['del'];
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
    $tmp = $stmt->execute();
}

$s_email = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $s_email .= " AND `title` LIKE '%" . $_GET['search'] . "%' OR `transport` LIKE '%" . $_GET['search'] . "%' OR `location_from` LIKE '%" . $_GET['search'] . "%' OR `location_to` LIKE '%" . $_GET['search'] . "%'";
}

$delete_message = "'Deleted plans will be removed permanently, this action can not be undone!!'";

$stmt = $dbh->prepare("SELECT * FROM trips WHERE id_user=?" . $s_email . " ORDER BY `id_trip` DESC");
$stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$tmp = $stmt->execute();

$stmt_event = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='event'");
$stmt_event->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$tmp_event = $stmt_event->execute();

$stmt_meeting = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='meeting'");
$stmt_meeting->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$tmp_meeting = $stmt_meeting->execute();

?>

<body>
    <section class="dashboard_mail_sec">
        <div class="container-fluid">
            <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
                <div class="app-header header-shadow">
                    <div class="app-header__logo">
                        <div class="logo-src">
                            <h3><span class="only-mob hamburger hamburger--elastic mobile-toggle-nav"><img src="<?= SITE; ?>/dashboard/images/arrow-planhd.svg"></span> Planiversity</h3>
                        </div>
                        <div class="header__pane ml-auto">
                            <div class="show-mob">
                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="app-header__mobile-menu">
                        <div class=" btn-group">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                <span class="hamburger-inner"></span>
                            </a>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right onmobile-drop">
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
                                        <a href="<?= SITE; ?>" class="dropdown-item drop-menu-item" target="_blank">
                                            Home
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
                                                            <a href="<?= SITE; ?>" class="dropdown-item drop-menu-item" target="_blank">
                                                                Home
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
                                            <div class="heade_user_img uploaded_image">
                                                <img src="<?= $img ?>" class="rounded-circle profile_picture_place">
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
                                                    <span class="hamburger-box1">
                                                        <span class="hamburger-inner"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="app-header__mobile-menu">
                                        <div>
                                            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                                                <span class="hamburger-box2">
                                                    <span class="hamburger-inner"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </div> -->

                                    <div class="scrollbar-sidebar">
                                        <?php
                                        $page_index = "billing";
                                        include('dashboard/include/navigation.php');
                                        ?>
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
                                                    <div class="row align-self-center">
                                                        <div class="col-xl-6 col-9">
                                                            <div class="user_header_left_item">
                                                                <div class="user_header_list">
                                                                    <div class="user_header_img only-mob uploaded_image">
                                                                        <img src="<?= $img ?>">
                                                                    </div>
                                                                    <div class="user_header_text">
                                                                        <h4><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?> <span class="business_user"><?= $userdata['account_type']; ?> USER</span></h4>
                                                                        <h6>Customer#: <?= strtoupper($userdata['customer_number']) ?></h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-3">
                                                            <div class="start_a_plan_btn">
                                                                <a href="<?= SITE; ?>trip/how-are-you-traveling"><span>Start a new plan</span>
                                                                    <div class="plus-icon"><img src="<?= SITE; ?>/dashboard/images/plus-circle.svg"></div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <form id="payment_form">
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
                                                                        <?php if ($user_payment_status == 0) { ?>
                                                                            <h6><?= $expire_label; ?></h6>
                                                                        <?php } else { ?>
                                                                            <h6>Expires <?= change_date_format($user_active_plan->date_expire); ?></h6>
                                                                        <?php } ?>
                                                                    </div>


                                                                    <div class="plan_history_item_seec">
                                                                        <ul class="list-unstyled">
                                                                            <li>
                                                                                <div class="plan_history_items">
                                                                                    <div class="row">
                                                                                        <div class="col-xl-8 col-8">
                                                                                            <h3>Plan History</h3>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </li>

                                                                            <?php if (!empty($paymentListData)) {
                                                                                $i = 1;
                                                                                foreach ($paymentListData as $data) {
                                                                            ?>


                                                                                    <li>
                                                                                        <div class="plan_history_items">
                                                                                            <div class="row">
                                                                                                <div class="col-xl-8 col-8">
                                                                                                    <h4>Payment <?= $i ?> - <?= $plan_type ?></h4>
                                                                                                    <p><i class="bi bi-calendar-minus"></i> <?= $data->date_paid; ?> - <i class="bi bi-calendar-check"></i> <?= $data->date_expire; ?></p>
                                                                                                </div>
                                                                                                <div class="col-xl-4 col-4">
                                                                                                    <h5>$<?= $data->amount ?></h5>
                                                                                                    <h6>Paid</h6>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                <?php
                                                                                    $i++;
                                                                                }
                                                                            } else { ?>

                                                                                <li>
                                                                                    <div class="plan_history_items">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-12 col-12">
                                                                                                <p style="text-align: center;"><i class="bi bi-search"></i> No History</p>
                                                                                            </div>

                                                                                        </div>
                                                                                    </div>
                                                                                </li>

                                                                            <?php } ?>


                                                                        </ul>
                                                                        <?php if (($user_payment_status == 0) && ($userdata['account_type'] == 'Individual')) { ?>
                                                                            <div class="upgrade_account_btn">
                                                                                <button type="button" class="btn_btn" id="upgrade-button">Upgrade Account</button>
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>

                                                                    <div class="auto-payment">

                                                                        <div class="billing_content_heading mt-4">
                                                                            <h2>Enable automatic payments</h2>
                                                                        </div>

                                                                        <div class="col-xl-12 col-12 padding_left">
                                                                            <div class="form_group menu_checkbox">
                                                                                <input type="checkbox" name="enable_payment_option" id="enable_payment_option" class="form-control enable_payment">
                                                                            </div>
                                                                        </div>

                                                                    </div>



                                                                </div>



                                                            </div>

                                                            <div class="col-xl-7 col-12">
                                                                <div class="renew_select_your_personal_sec">

                                                                    <div class="billing_content_heading">
                                                                        <h6>&nbsp;</h6>
                                                                        <h2><span>Renew or Select</span> <?php echo $plan_hold ?> </h2>
                                                                    </div>



                                                                    <div class="business-billing">

                                                                        <div class="slider-container">
                                                                            <img src="<?php echo SITE; ?>assets/images/team-color.png" alt="User Icon" id="userIcon" width="45" height="45">
                                                                            <input type="range" min="1" max="<?= $account_type === 'individual' ? '200' : '500' ?>" step="1" value="<?= $member_count ?>" class="price-slider" id="userSlider">
                                                                        </div>

                                                                        <div class="team-member-block">
                                                                            <?php if ($account_type !== 'individual') { ?>
                                                                                <div class="price-contact">
                                                                                    <h5 class="price-info" id="learn_more"> <i class="fa fa-info-circle" aria-hidden="true"></i> Learn More </h5>
                                                                                    <h5 class="price-info contact-agent" id="contact_agent"> <i class="fa fa-info-circle" aria-hidden="true"></i> Contact Sales </h5>
                                                                                </div>
                                                                            <?php }; ?>
                                                                            <h5>Team Member : <span id="member_count">0</span></h5>
                                                                        </div>

                                                                    </div>

                                                                    <div class="monthly_plan_radio">
                                                                        <ul class="list-unstyled plans_list">

                                                                            <?php
                                                                            $checked_item = "checked=checked";
                                                                            $class = "nav_active";
                                                                            if ($account_type == 'individual') {
                                                                                $checked_item = "null";
                                                                                $class = "";
                                                                            ?>
                                                                                <li class="nav_active">

                                                                                    <label class="redio_button_desin"><span>One Time Use Only</span>
                                                                                        Only <p id="one-time-breakdown" class="price-breakdown"> $4.99 </p>
                                                                                        <input class="payment_type" type="radio" checked="checked" name="payment_type" value="one_time">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>

                                                                                </li>

                                                                            <?php }

                                                                            ?>

                                                                            <li class="<?= $class; ?>">

                                                                                <label class="redio_button_desin"><span>30 Days Unlimited Use</span>
                                                                                    Only <p id="monthly-breakdown" class="price-breakdown"> $<?= $monthly_price; ?> </p>
                                                                                    <input class="payment_type" type="radio" <?= $checked_item; ?> name="payment_type" value="monthly">
                                                                                    <span class="checkmark"></span>
                                                                                </label>

                                                                            </li>
                                                                            <li>

                                                                                <label class=" redio_button_desin"><span>12 Months Unlimited Use</span>
                                                                                    Only <p id="annual-breakdown" class="price-breakdown"> $<?= $annual_price; ?> ( Get 12 months but only pay for 11 ) </p>
                                                                                    <input class="payment_type" type="radio" name="payment_type" value="annual">
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
                                                                                    <input type="radio" id="visa" name="payment-option" value="visa" checked>
                                                                                    <label for="visa" class="visa_img">
                                                                                        <img src="<?php echo SITE; ?>/assets/billing2/images/visa_img.png">
                                                                                    </label>
                                                                                </li>
                                                                                <li>
                                                                                    <input type="radio" id="mastercard" name="payment-option" value="mastercard">
                                                                                    <label for="mastercard" class="mastercard_img">
                                                                                        <img src="<?php echo SITE; ?>/assets/billing2/images/mastercard_img.png">
                                                                                    </label>
                                                                                </li>
                                                                                <li>
                                                                                    <input type="radio" id="discover" name="payment-option" value="discover">
                                                                                    <label for="discover" class="discover_img">
                                                                                        <img src="<?php echo SITE; ?>/assets/billing2/images/discover_img.png">
                                                                                    </label>
                                                                                </li>
                                                                                <li>
                                                                                    <input type="radio" id="amex" name="payment-option" value="amex">
                                                                                    <label for="amex" class="americal_img">
                                                                                        <img src="<?php echo SITE; ?>/assets/billing2/images/americal_img.png">
                                                                                    </label>
                                                                                </li>
                                                                            </ul>

                                                                            <div class="payment_separeate">
                                                                                <p>OR</p>
                                                                            </div>

                                                                            <!-- <div class="by_with_apple_btn">
                                                                                <button type="button" class="btn_apple">Buy with <i class="bi bi-apple"></i>Pay</button>
                                                                            </div> -->
                                                                            <div class="billing_paypal_img text-center">
                                                                                <!-- <img src="<?php echo SITE; ?>/assets/billing2/images/paypal_img.png"> -->
                                                                                <div id="paypal-button-container"></div>
                                                                                <div id="paypal-button-container-subscribe" style="display: none;"></div>
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

                                                                                        <div class="col-xl-12 col-12 padding_left">
                                                                                            <div class="form_group menu_checkbox">
                                                                                                <label>Load billing information previously used</label>
                                                                                                <input type="checkbox" id="load_personal_data" class="form-control load_previous">
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-xl-6 col-12 padding_left">
                                                                                            <div class="form_group">
                                                                                                <label>First Name</label>
                                                                                                <input type="text" name="payment_fname" id="payment_fname" class="form-control reset_value" placeholder="Enter First Name">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-xl-6 col-12">
                                                                                            <div class="form_group">
                                                                                                <label>Last Name</label>
                                                                                                <input type="text" name="payment_lname" id="payment_lname" class="form-control reset_value" placeholder="Enter Last Name">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-xl-12 padding_left form_group">
                                                                                            <label>Country</label>
                                                                                            <select name="payment_country" id="payment_country" class="form-control reset_value">
                                                                                                <option value='' selected>Select Country</option>

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
                                                                                    <div class="row">
                                                                                        <div class="col-xl-12 padding_left form_group">
                                                                                            <label>Address</label>
                                                                                            <input type="text" name="payment_address" id="payment_address" class="form-control reset_value" placeholder="Address">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-xl-12 padding_left form_group">
                                                                                            <label>City</label>
                                                                                            <input type="text" name="payment_city" id="payment_city" class="form-control reset_value" placeholder="Enter your city">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-xl-12 padding_left form_group">
                                                                                            <label>State</label>
                                                                                            <input type="text" name="payment_state" id="payment_state" class="form-control reset_value" placeholder="Residential">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-xl-12 padding_left form_group">
                                                                                            <label>Zip Code</label>
                                                                                            <input type="tel" name="payment_zipcode" id="payment_zipcode" class="form-control reset_value" placeholder="Enter your Zip Code">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="inter_details_card">
                                                                                    <div class="row">
                                                                                        <div class="col-xl-12 padding_left form_group">
                                                                                            <label>Card Number</label>
                                                                                            <input type="number" name="payment_cardnumber" id="payment_cardnumber" class="form-control payment_card" placeholder="Enter your Card Number" maxlength="19">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col-xl-4 col-12 padding_left">
                                                                                            <div class="form_group">
                                                                                                <label>Expiration Date</label>
                                                                                                <select class="form-control payment_card" name="payment_expmonth" id="payment_expmonth">
                                                                                                    <option value='' selected>Month</option>
                                                                                                    <option value="01">January</option>
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
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-xl-4 col-12 ">
                                                                                            <div class="form_group">
                                                                                                <label>&nbsp;</label>
                                                                                                <select class="form-control payment_card" name="payment_expyear" id="payment_expyear">
                                                                                                    <option value='' selected>Year</option>
                                                                                                    <?php
                                                                                                    $year = date('Y');
                                                                                                    for ($i = 1; $i <= 7; $i++) {
                                                                                                        echo '<option value="' . substr($year, -2) . '" ' . ($i == 1 ? 'selected' : '') . '>' . $year . '</option>';
                                                                                                        $year++;
                                                                                                    } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-xl-4 col-12">
                                                                                            <div class="form_group">
                                                                                                <label class="billing-label">CVC <span class="what_is_this" title="The Card Verification Code, or CVC*, is an extra code printed on your debit or credit card. With most cards (Visa, MasterCard, bank cards, etc.) it is the final three digits of the number printed on the signature strip on the reverse of your card.On American Express (AMEX) cards, it is usually a four digit code on the front.">What's This</span></label>
                                                                                                <input type="number" name="payment_cvc" id="payment_cvc" class="form-control payment_card" placeholder="Enter CVC" maxlength="4">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="Have_coupon_item">
                                                                                        <div class="row">
                                                                                            <div class="col-xl-12 padding_left form_group">
                                                                                                <button type="button" class="btn_coupon" data-toggle="collapse" data-target="#promo_code">
                                                                                                    <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                                                                                                    &nbsp;Have a coupon? Click here to enter your code</button>
                                                                                                <div class="coupon_code_input_item collapse" id="promo_code">
                                                                                                    <form id="coupon_form">
                                                                                                        <div class="row">
                                                                                                            <div class="col-xl-8 col-lg-8 col-7 form_group">
                                                                                                                <input type="text" class="form-control" name="coupon_code" id="coupon_code" placeholder="Coupon code" value="">
                                                                                                                <p class="coupon_response"></p>
                                                                                                            </div>
                                                                                                            <div class="col-xl-4 col-5 form_group">
                                                                                                                <button type="button" class="btn_coupon" id="coupon_submit">Apply coupon</button>
                                                                                                            </div>

                                                                                                            <input type="hidden" name="coupon_id" id="coupon_id" readonly>
                                                                                                            <input type="hidden" name="coupon_flag" id="coupon_flag" readonly value="0">
                                                                                                            <input type="hidden" name="coupon_percent" id="coupon_percent" readonly value="0">
                                                                                                            <input type="hidden" name="coupon_context" id="coupon_context" readonly value="0">
                                                                                                            <input type="hidden" name="coupon_breakdown" id="coupon_breakdown" readonly value="0">

                                                                                                            <div class="col-xl-8 col-lg-8 col-7">
                                                                                                                <div class="page-alerts" style="display:none">
                                                                                                                    <div class="alert alert-success page-alert" id="alert-1">
                                                                                                                        <button type="button" class="close" id="coupon-reset"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                                                                                                        <span id="notification"></span>
                                                                                                                    </div>
                                                                                                                </div>

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

                                                                                        <div class="col-xl-12 mt-3">
                                                                                            <label>Save payment information for all future Planiversity purchases?</label>

                                                                                            <div class="form_group menu_checkbox">
                                                                                                <input type="checkbox" name="saved_agree" id="saved_agree" value="1" class="form-control enable_payment">
                                                                                            </div>

                                                                                        </div>

                                                                                    </div>
                                                                                    <input type="hidden" name="token" id="token" value="l00CsUFS-ITdleTKb0-DEEUE3H3-FB1VB5u" readonly>
                                                                                    <div class="process_payment_sec">
                                                                                        <button type="submit" name="payment_process" id="payment_process" class="btn_btn">Process Payment</button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>


                                                                </div>




                                                            </div>







                                                        </div>
                                                    </section>
                                                </form>

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
                                            <p>&copy;Copyright. 2015 - <?= date('Y'); ?> Planiversity, LLC. All Rights Reserved.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($userdata['account_type'] != 'Admin') { ?>
                                <!-- <div class="currnet-planboxt show-mob">
                                    <div class="current-plan">
                                        <div class="form-row justify-content-center pad-20">
                                            <div class="col-lg-8 col-7">
                                                <h4> Current Plan </h4>
                                                <h6>Expires 25/08/2022</h6>
                                            </div>
                                            <div class="col-lg-4 col-5 align-self-center">
                                                <div class="personal-userbtn"> <?= strtoupper($userdata['account_type']); ?> USER </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12">
                                                <div class="add-new-event-btn"> Add New Event <div class="add-evnt-icon"><img src="https://www.planiversity.com//dashboard/images/plus-circle.svg"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <?php if ($userdata['account_type'] != 'Admin') { ?>
        <!-- <div class="mobile-fixed-footer show-mob">
            <div class="form-row">
                <div class="col-lg-3 col-3">
                    <div class="footer-box active">
                        <a href="<?= SITE ?>welcome">
                            <div class="footer-icon"><img src="<?= SITE; ?>/dashboard/images/dashboard.svg"></div>
                            <p>Dashboard</p>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-3">
                    <div class="footer-box">
                        <a href="#">
                            <div class="footer-icon"><img src="<?= SITE; ?>/dashboard/images/calendar.svg"></div>
                            <p>My Plans </p>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-3">
                    <div class="footer-box">
                        <a href="#">
                            <div class="footer-icon"><img src="<?= SITE; ?>/dashboard/images/profile.svg"></div>
                            <p>My Profile</p>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-3">
                    <div class="footer-box">
                        <a href="#">
                            <div class="footer-icon"><img src="<?= SITE; ?>/dashboard/images/settings.svg"></div>
                            <p>Settings</p>
                        </a>
                    </div>
                </div>
            </div>
        </div> -->
    <?php } ?>

    <form id="formId" name="formId" action="" method="post" enctype="multipart/form-data" class="d-none">
        <input type="file" id="upload" value="Choose a file" accept="image/*" style="display: none;">
    </form>

    <button id="btn-crop-image" data-toggle="modal" class="d-none" data-target="#cropImagePop">Open Modal</button>
    <div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Edit Photo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="upload-demo" class="center-block"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cropImageBtn" class="btn btn-primary">Save Photo</button>
                    <button type="button" class="btn btn-danger btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>



    <div id="payment_auth_confirm" class="modal fade show modal-custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-content">
                <div class="modal-header custom-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form id="payment_auth">
                    <div class="modal-body">
                        <div class="checkbox checkbox-primary">


                            <p id="popupmess" class="mod-p">
                                <?php
                                if ($account_type == 'individual') {
                                ?>
                                    I understand that this is a one-time-payment and that I will not be automatically charged again. If I wish to setup recurring monthly payments, I understand that I have to activate the recurring payment option.
                                <?php } else { ?>
                                    I understand that Planiversity will charge me the monthly account price as advertised, and to cancel that option, I will have to manually deactivate the recurring option.
                                <?php } ?>
                            </p>


                            <label for="confirm">
                                <input id="confirm" type="checkbox" name="confirm">
                                <p id="popupmess" class="mod-p">
                                    Check box
                                </p>
                            </label>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="payment_auth_submit" id="payment_auth_submit" class="accept-btn disabled-btn" value="I Accept" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="payment_modal_loader_sec">
        <div class="modal fade" id="payment_loading_screen" data-keyboard="false" data-backdrop="static">
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


    <!-- Contact Agent Modal Start-->
    <div class="modal fade modal-blur" id="contactAgentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Contact Sales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-title text-center">
                        <h4></h4>
                    </div>
                    <div class="d-flex flex-column text-center">

                        <form id="contactform" class="payment-form">
                            <div class="form-group">
                                <label>Your Name *</label>
                                <input type="text" class="form-control" name="name" placeholder="Your Name">
                            </div>
                            <div class="form-group">
                                <label>Your Email *</label>
                                <input type="email" class="form-control" name="email" placeholder="Your Email">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownSelectLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="emoji">🇺🇸</span>
                                        </button>
                                        <ul class="dropdown-menu scrollable-menu" aria-labelledby="dropdownSelectLink">
                                            <li class="">
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇺🇸</span>
                                                    <span class="country-code sr-only">US</span>
                                                    <span class="country-name truncate col-9">United States</span>
                                                    <span class="dial-code col-2 text-right">+1</span>
                                                    <span class="example-number sr-only">(201) 555-0123</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇧</span>
                                                    <span class="country-code sr-only">GB</span>
                                                    <span class="country-name truncate col-9">United Kingdom</span>
                                                    <span class="dial-code col-2 text-right">+44</span>
                                                    <span class="example-number sr-only">07400 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇦</span>
                                                    <span class="country-code sr-only">CA</span>
                                                    <span class="country-name truncate col-9">Canada</span>
                                                    <span class="dial-code col-2 text-right">+1</span>
                                                    <span class="example-number sr-only">(204) 234 5678</span>
                                                </button>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇫</span>
                                                    <span class="country-code sr-only">AF</span>
                                                    <span class="country-name truncate col-9">Afghanistan</span>
                                                    <span class="dial-code col-2 text-right">+93</span>
                                                    <span class="example-number sr-only">070 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇽</span>
                                                    <span class="country-code sr-only">AX</span>
                                                    <span class="country-name truncate col-9">Åland Islands</span>
                                                    <span class="dial-code col-2 text-right">+358</span>
                                                    <span class="example-number sr-only">041 2345678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇱</span>
                                                    <span class="country-code sr-only">AL</span>
                                                    <span class="country-name truncate col-9">Albania (Shqipëri)</span>
                                                    <span class="dial-code col-2 text-right">+355</span>
                                                    <span class="example-number sr-only">066 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇩🇿</span>
                                                    <span class="country-code sr-only">DZ</span>
                                                    <span class="country-name truncate col-9">Algeria (‫الجزائر‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+213</span>
                                                    <span class="example-number sr-only">0551 23 45 67</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇸</span>
                                                    <span class="country-code sr-only">AS</span>
                                                    <span class="country-name truncate col-9">American Samoa</span>
                                                    <span class="dial-code col-2 text-right">+1684</span>
                                                    <span class="example-number sr-only">(684) 733-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇩</span>
                                                    <span class="country-code sr-only">AD</span>
                                                    <span class="country-name truncate col-9">Andorra</span>
                                                    <span class="dial-code col-2 text-right">+376</span>
                                                    <span class="example-number sr-only">312 345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇴</span>
                                                    <span class="country-code sr-only">AO</span>
                                                    <span class="country-name truncate col-9">Angola</span>
                                                    <span class="dial-code col-2 text-right">+244</span>
                                                    <span class="example-number sr-only">923 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇮</span>
                                                    <span class="country-code sr-only">AI</span>
                                                    <span class="country-name truncate col-9">Anguilla</span>
                                                    <span class="dial-code col-2 text-right">+1264</span>
                                                    <span class="example-number sr-only">(264) 235-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇶</span>
                                                    <span class="country-code sr-only">AQ</span>
                                                    <span class="country-name truncate col-9">Antarctica</span>
                                                    <span class="dial-code col-2 text-right">+672</span>
                                                    <span class="example-number sr-only">55 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇬</span>
                                                    <span class="country-code sr-only">AG</span>
                                                    <span class="country-name truncate col-9">Antigua &amp; Barbuda</span>
                                                    <span class="dial-code col-2 text-right">+1268</span>
                                                    <span class="example-number sr-only">(268) 464-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇷</span>
                                                    <span class="country-code sr-only">AR</span>
                                                    <span class="country-name truncate col-9">Argentina</span>
                                                    <span class="dial-code col-2 text-right">+54</span>
                                                    <span class="example-number sr-only">011 15-2345-6789</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇲</span>
                                                    <span class="country-code sr-only">AM</span>
                                                    <span class="country-name truncate col-9">Armenia (Հայաստան)</span>
                                                    <span class="dial-code col-2 text-right">+374</span>
                                                    <span class="example-number sr-only">077 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇼</span>
                                                    <span class="country-code sr-only">AW</span>
                                                    <span class="country-name truncate col-9">Aruba</span>
                                                    <span class="dial-code col-2 text-right">+297</span>
                                                    <span class="example-number sr-only">560 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇨</span>
                                                    <span class="country-code sr-only">AC</span>
                                                    <span class="country-name truncate col-9">Ascension Island</span>
                                                    <span class="dial-code col-2 text-right">+247</span>
                                                    <span class="example-number sr-only">1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇺</span>
                                                    <span class="country-code sr-only">AU</span>
                                                    <span class="country-name truncate col-9">Australia</span>
                                                    <span class="dial-code col-2 text-right">+61</span>
                                                    <span class="example-number sr-only">0412 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇹</span>
                                                    <span class="country-code sr-only">AT</span>
                                                    <span class="country-name truncate col-9">Austria (Österreich)</span>
                                                    <span class="dial-code col-2 text-right">+43</span>
                                                    <span class="example-number sr-only">0664 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇿</span>
                                                    <span class="country-code sr-only">AZ</span>
                                                    <span class="country-name truncate col-9">Azerbaijan (Azərbaycan)</span>
                                                    <span class="dial-code col-2 text-right">+994</span>
                                                    <span class="example-number sr-only">040 123 45 67</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇸</span>
                                                    <span class="country-code sr-only">BS</span>
                                                    <span class="country-name truncate col-9">Bahamas</span>
                                                    <span class="dial-code col-2 text-right">+1242</span>
                                                    <span class="example-number sr-only">(242) 359-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇭</span>
                                                    <span class="country-code sr-only">BH</span>
                                                    <span class="country-name truncate col-9">Bahrain (‫البحرين‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+973</span>
                                                    <span class="example-number sr-only">3600 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇩</span>
                                                    <span class="country-code sr-only">BD</span>
                                                    <span class="country-name truncate col-9">Bangladesh (বাংলাদেশ)</span>
                                                    <span class="dial-code col-2 text-right">+880</span>
                                                    <span class="example-number sr-only">01812-345678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇧</span>
                                                    <span class="country-code sr-only">BB</span>
                                                    <span class="country-name truncate col-9">Barbados</span>
                                                    <span class="dial-code col-2 text-right">+1246</span>
                                                    <span class="example-number sr-only">(246) 250-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇾</span>
                                                    <span class="country-code sr-only">BY</span>
                                                    <span class="country-name truncate col-9">Belarus (Беларусь)</span>
                                                    <span class="dial-code col-2 text-right">+375</span>
                                                    <span class="example-number sr-only">8 029 491-19-11</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇪</span>
                                                    <span class="country-code sr-only">BE</span>
                                                    <span class="country-name truncate col-9">Belgium (België)</span>
                                                    <span class="dial-code col-2 text-right">+32</span>
                                                    <span class="example-number sr-only">0470 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇿</span>
                                                    <span class="country-code sr-only">BZ</span>
                                                    <span class="country-name truncate col-9">Belize</span>
                                                    <span class="dial-code col-2 text-right">+501</span>
                                                    <span class="example-number sr-only">622-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇯</span>
                                                    <span class="country-code sr-only">BJ</span>
                                                    <span class="country-name truncate col-9">Benin (Bénin)</span>
                                                    <span class="dial-code col-2 text-right">+229</span>
                                                    <span class="example-number sr-only">622-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇲</span>
                                                    <span class="country-code sr-only">BM</span>
                                                    <span class="country-name truncate col-9">Bermuda</span>
                                                    <span class="dial-code col-2 text-right">+1441</span>
                                                    <span class="example-number sr-only">(441) 370-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇹</span>
                                                    <span class="country-code sr-only">BT</span>
                                                    <span class="country-name truncate col-9">Bhutan (འབྲུག)</span>
                                                    <span class="dial-code col-2 text-right">+975</span>
                                                    <span class="example-number sr-only">17 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇴</span>
                                                    <span class="country-code sr-only">BO</span>
                                                    <span class="country-name truncate col-9">Bolivia</span>
                                                    <span class="dial-code col-2 text-right">+591</span>
                                                    <span class="example-number sr-only">71234567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇦</span>
                                                    <span class="country-code sr-only">BA</span>
                                                    <span class="country-name truncate col-9">Bosnia &amp; Herzegovina (Босна и Херцеговина)</span>
                                                    <span class="dial-code col-2 text-right">+387</span>
                                                    <span class="example-number sr-only">061 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇼</span>
                                                    <span class="country-code sr-only">BW</span>
                                                    <span class="country-name truncate col-9">Botswana</span>
                                                    <span class="dial-code col-2 text-right">+267</span>
                                                    <span class="example-number sr-only">71 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇻</span>
                                                    <span class="country-code sr-only">BV</span>
                                                    <span class="country-name truncate col-9">Bouvet Island</span>
                                                    <span class="dial-code col-2 text-right">+47</span>
                                                    <span class="example-number sr-only">406 12 345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇷</span>
                                                    <span class="country-code sr-only">BR</span>
                                                    <span class="country-name truncate col-9">Brazil (Brasil)</span>
                                                    <span class="dial-code col-2 text-right">+55</span>
                                                    <span class="example-number sr-only">(11) 96123-4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇴</span>
                                                    <span class="country-code sr-only">IO</span>
                                                    <span class="country-name truncate col-9">British Indian Ocean Territory</span>
                                                    <span class="dial-code col-2 text-right">+246</span>
                                                    <span class="example-number sr-only">380 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇻🇬</span>
                                                    <span class="country-code sr-only">VG</span>
                                                    <span class="country-name truncate col-9">British Virgin Islands</span>
                                                    <span class="dial-code col-2 text-right">+1284</span>
                                                    <span class="example-number sr-only">(284) 300-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇳</span>
                                                    <span class="country-code sr-only">BN</span>
                                                    <span class="country-name truncate col-9">Brunei</span>
                                                    <span class="dial-code col-2 text-right">+673</span>
                                                    <span class="example-number sr-only">712 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇬</span>
                                                    <span class="country-code sr-only">BG</span>
                                                    <span class="country-name truncate col-9">Bulgaria (България)</span>
                                                    <span class="dial-code col-2 text-right">+359</span>
                                                    <span class="example-number sr-only">048 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇫</span>
                                                    <span class="country-code sr-only">BF</span>
                                                    <span class="country-name truncate col-9">Burkina Faso</span>
                                                    <span class="dial-code col-2 text-right">+226</span>
                                                    <span class="example-number sr-only">70 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇮</span>
                                                    <span class="country-code sr-only">BI</span>
                                                    <span class="country-name truncate col-9">Burundi (Uburundi)</span>
                                                    <span class="dial-code col-2 text-right">+257</span>
                                                    <span class="example-number sr-only">79 56 12 34</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇭</span>
                                                    <span class="country-code sr-only">KH</span>
                                                    <span class="country-name truncate col-9">Cambodia (កម្ពុជា)</span>
                                                    <span class="dial-code col-2 text-right">+855</span>
                                                    <span class="example-number sr-only">091 234 567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇲</span>
                                                    <span class="country-code sr-only">CM</span>
                                                    <span class="country-name truncate col-9">Cameroon (Cameroun)</span>
                                                    <span class="dial-code col-2 text-right">+237</span>
                                                    <span class="example-number sr-only">6 71 23 45 67</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇻</span>
                                                    <span class="country-code sr-only">CV</span>
                                                    <span class="country-name truncate col-9">Cape Verde (Kabu Verdi)</span>
                                                    <span class="dial-code col-2 text-right">+238</span>
                                                    <span class="example-number sr-only">991 12 34</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇶</span>
                                                    <span class="country-code sr-only">BQ</span>
                                                    <span class="country-name truncate col-9">Caribbean Netherlands</span>
                                                    <span class="dial-code col-2 text-right">+599</span>
                                                    <span class="example-number sr-only">318 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇾</span>
                                                    <span class="country-code sr-only">KY</span>
                                                    <span class="country-name truncate col-9">Cayman Islands</span>
                                                    <span class="dial-code col-2 text-right">+1345</span>
                                                    <span class="example-number sr-only">(345) 323-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇫</span>
                                                    <span class="country-code sr-only">CF</span>
                                                    <span class="country-name truncate col-9">Central African Republic (République centrafricaine)</span>
                                                    <span class="dial-code col-2 text-right">+236</span>
                                                    <span class="example-number sr-only">70 01 23 45</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇩</span>
                                                    <span class="country-code sr-only">TD</span>
                                                    <span class="country-name truncate col-9">Chad (Tchad)</span>
                                                    <span class="dial-code col-2 text-right">+235</span>
                                                    <span class="example-number sr-only">63 01 23 45</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇱</span>
                                                    <span class="country-code sr-only">CL</span>
                                                    <span class="country-name truncate col-9">Chile</span>
                                                    <span class="dial-code col-2 text-right">+56</span>
                                                    <span class="example-number sr-only">09 6123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇳</span>
                                                    <span class="country-code sr-only">CN</span>
                                                    <span class="country-name truncate col-9">China (中国)</span>
                                                    <span class="dial-code col-2 text-right">+86</span>
                                                    <span class="example-number sr-only">131 2345 6789</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇽</span>
                                                    <span class="country-code sr-only">CX</span>
                                                    <span class="country-name truncate col-9">Christmas Island</span>
                                                    <span class="dial-code col-2 text-right">+61</span>
                                                    <span class="example-number sr-only">0412 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇨</span>
                                                    <span class="country-code sr-only">CC</span>
                                                    <span class="country-name truncate col-9">Cocos (Keeling) Islands</span>
                                                    <span class="dial-code col-2 text-right">+61</span>
                                                    <span class="example-number sr-only">0412 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇴</span>
                                                    <span class="country-code sr-only">CO</span>
                                                    <span class="country-name truncate col-9">Colombia</span>
                                                    <span class="dial-code col-2 text-right">+57</span>
                                                    <span class="example-number sr-only">321 1234567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇲</span>
                                                    <span class="country-code sr-only">KM</span>
                                                    <span class="country-name truncate col-9">Comoros (‫جزر القمر‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+269</span>
                                                    <span class="example-number sr-only">321 23 45</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇬</span>
                                                    <span class="country-code sr-only">CG</span>
                                                    <span class="country-name truncate col-9">Congo (Republic) (Congo-Brazzaville)</span>
                                                    <span class="dial-code col-2 text-right">+242</span>
                                                    <span class="example-number sr-only">0991 234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇩</span>
                                                    <span class="country-code sr-only">CD</span>
                                                    <span class="country-name truncate col-9">Congo (DRC) (Jamhuri ya Kidemokrasia ya Kongo)</span>
                                                    <span class="dial-code col-2 text-right">+243</span>
                                                    <span class="example-number sr-only">06 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇰</span>
                                                    <span class="country-code sr-only">CK</span>
                                                    <span class="country-name truncate col-9">Cook Islands</span>
                                                    <span class="dial-code col-2 text-right">+682</span>
                                                    <span class="example-number sr-only">71 234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇷</span>
                                                    <span class="country-code sr-only">CR</span>
                                                    <span class="country-name truncate col-9">Costa Rica</span>
                                                    <span class="dial-code col-2 text-right">+506</span>
                                                    <span class="example-number sr-only">8312 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇮</span>
                                                    <span class="country-code sr-only">CI</span>
                                                    <span class="country-name truncate col-9">Côte d’Ivoire</span>
                                                    <span class="dial-code col-2 text-right">+225</span>
                                                    <span class="example-number sr-only">01 23 45 67</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇭🇷</span>
                                                    <span class="country-code sr-only">HR</span>
                                                    <span class="country-name truncate col-9">Croatia (Hrvatska)</span>
                                                    <span class="dial-code col-2 text-right">+385</span>
                                                    <span class="example-number sr-only">091 234 5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇺</span>
                                                    <span class="country-code sr-only">CU</span>
                                                    <span class="country-name truncate col-9">Cuba</span>
                                                    <span class="dial-code col-2 text-right">+53</span>
                                                    <span class="example-number sr-only">05 1234567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇼</span>
                                                    <span class="country-code sr-only">CW</span>
                                                    <span class="country-name truncate col-9">Curaçao</span>
                                                    <span class="dial-code col-2 text-right">+599</span>
                                                    <span class="example-number sr-only">9 518 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇾</span>
                                                    <span class="country-code sr-only">CY</span>
                                                    <span class="country-name truncate col-9">Cyprus (Κύπρος)</span>
                                                    <span class="dial-code col-2 text-right">+357</span>
                                                    <span class="example-number sr-only">96 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇿</span>
                                                    <span class="country-code sr-only">CZ</span>
                                                    <span class="country-name truncate col-9">Czech Republic (Česká republika)</span>
                                                    <span class="dial-code col-2 text-right">+420</span>
                                                    <span class="example-number sr-only">601 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇩🇰</span>
                                                    <span class="country-code sr-only">DK</span>
                                                    <span class="country-name truncate col-9">Denmark (Danmark)</span>
                                                    <span class="dial-code col-2 text-right">+45</span>
                                                    <span class="example-number sr-only">20 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇩🇬</span>
                                                    <span class="country-code sr-only">DG</span>
                                                    <span class="country-name truncate col-9">Diego Garcia</span>
                                                    <span class="dial-code col-2 text-right">+246</span>
                                                    <span class="example-number sr-only">0412 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇩🇯</span>
                                                    <span class="country-code sr-only">DJ</span>
                                                    <span class="country-name truncate col-9">Djibouti</span>
                                                    <span class="dial-code col-2 text-right">+253</span>
                                                    <span class="example-number sr-only">77 83 10 01</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇩🇲</span>
                                                    <span class="country-code sr-only">DM</span>
                                                    <span class="country-name truncate col-9">Dominica</span>
                                                    <span class="dial-code col-2 text-right">+1767</span>
                                                    <span class="example-number sr-only">(767) 225-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇩🇴</span>
                                                    <span class="country-code sr-only">DO</span>
                                                    <span class="country-name truncate col-9">Dominican Republic (República Dominicana)</span>
                                                    <span class="dial-code col-2 text-right">+1</span>
                                                    <span class="example-number sr-only">(809) 234-5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇪🇨</span>
                                                    <span class="country-code sr-only">EC</span>
                                                    <span class="country-name truncate col-9">Ecuador</span>
                                                    <span class="dial-code col-2 text-right">+593</span>
                                                    <span class="example-number sr-only">099 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇪🇬</span>
                                                    <span class="country-code sr-only">EG</span>
                                                    <span class="country-name truncate col-9">Egypt (‫مصر‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+20</span>
                                                    <span class="example-number sr-only">0100 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇻</span>
                                                    <span class="country-code sr-only">SV</span>
                                                    <span class="country-name truncate col-9">El Salvador</span>
                                                    <span class="dial-code col-2 text-right">+503</span>
                                                    <span class="example-number sr-only">7012 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇶</span>
                                                    <span class="country-code sr-only">GQ</span>
                                                    <span class="country-name truncate col-9">Equatorial Guinea (Guinea Ecuatorial)</span>
                                                    <span class="dial-code col-2 text-right">+240</span>
                                                    <span class="example-number sr-only">222 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇪🇷</span>
                                                    <span class="country-code sr-only">ER</span>
                                                    <span class="country-name truncate col-9">Eritrea</span>
                                                    <span class="dial-code col-2 text-right">+291</span>
                                                    <span class="example-number sr-only">07 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇪🇪</span>
                                                    <span class="country-code sr-only">EE</span>
                                                    <span class="country-name truncate col-9">Estonia (Eesti)</span>
                                                    <span class="dial-code col-2 text-right">+372</span>
                                                    <span class="example-number sr-only">5123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇪🇹</span>
                                                    <span class="country-code sr-only">ET</span>
                                                    <span class="country-name truncate col-9">Ethiopia</span>
                                                    <span class="dial-code col-2 text-right">+251</span>
                                                    <span class="example-number sr-only">091 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇫🇰</span>
                                                    <span class="country-code sr-only">FK</span>
                                                    <span class="country-name truncate col-9">Falkland Islands (Islas Malvinas)</span>
                                                    <span class="dial-code col-2 text-right">+500</span>
                                                    <span class="example-number sr-only">51234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇫🇴</span>
                                                    <span class="country-code sr-only">FO</span>
                                                    <span class="country-name truncate col-9">Faroe Islands (Føroyar)</span>
                                                    <span class="dial-code col-2 text-right">+298</span>
                                                    <span class="example-number sr-only">211234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇫🇯</span>
                                                    <span class="country-code sr-only">FJ</span>
                                                    <span class="country-name truncate col-9">Fiji</span>
                                                    <span class="dial-code col-2 text-right">+679</span>
                                                    <span class="example-number sr-only">701 2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇫🇮</span>
                                                    <span class="country-code sr-only">FI</span>
                                                    <span class="country-name truncate col-9">Finland (Suomi)</span>
                                                    <span class="dial-code col-2 text-right">+358</span>
                                                    <span class="example-number sr-only">041 2345678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇫🇷</span>
                                                    <span class="country-code sr-only">FR</span>
                                                    <span class="country-name truncate col-9">France</span>
                                                    <span class="dial-code col-2 text-right">+33</span>
                                                    <span class="example-number sr-only">06 12 34 56 78</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇫</span>
                                                    <span class="country-code sr-only">GF</span>
                                                    <span class="country-name truncate col-9">French Guiana (Guyane française)</span>
                                                    <span class="dial-code col-2 text-right">+594</span>
                                                    <span class="example-number sr-only">0694 20 12 34</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇫</span>
                                                    <span class="country-code sr-only">PF</span>
                                                    <span class="country-name truncate col-9">French Polynesia (Polynésie française)</span>
                                                    <span class="dial-code col-2 text-right">+689</span>
                                                    <span class="example-number sr-only">87 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇦</span>
                                                    <span class="country-code sr-only">GA</span>
                                                    <span class="country-name truncate col-9">Gabon</span>
                                                    <span class="dial-code col-2 text-right">+241</span>
                                                    <span class="example-number sr-only">06 03 12 34</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇲</span>
                                                    <span class="country-code sr-only">GM</span>
                                                    <span class="country-name truncate col-9">Gambia</span>
                                                    <span class="dial-code col-2 text-right">+220</span>
                                                    <span class="example-number sr-only">301 2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇪</span>
                                                    <span class="country-code sr-only">GE</span>
                                                    <span class="country-name truncate col-9">Georgia (საქართველო)</span>
                                                    <span class="dial-code col-2 text-right">+995</span>
                                                    <span class="example-number sr-only">555 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇩🇪</span>
                                                    <span class="country-code sr-only">DE</span>
                                                    <span class="country-name truncate col-9">Germany (Deutschland)</span>
                                                    <span class="dial-code col-2 text-right">+49</span>
                                                    <span class="example-number sr-only">01512 3456789</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇭</span>
                                                    <span class="country-code sr-only">GH</span>
                                                    <span class="country-name truncate col-9">Ghana (Gaana)</span>
                                                    <span class="dial-code col-2 text-right">+233</span>
                                                    <span class="example-number sr-only">023 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇮</span>
                                                    <span class="country-code sr-only">GI</span>
                                                    <span class="country-name truncate col-9">Gibraltar</span>
                                                    <span class="dial-code col-2 text-right">+350</span>
                                                    <span class="example-number sr-only">57123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇷</span>
                                                    <span class="country-code sr-only">GR</span>
                                                    <span class="country-name truncate col-9">Greece (Ελλάδα)</span>
                                                    <span class="dial-code col-2 text-right">+30</span>
                                                    <span class="example-number sr-only">691 234 5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇱</span>
                                                    <span class="country-code sr-only">GL</span>
                                                    <span class="country-name truncate col-9">Greenland (Kalaallit Nunaat)</span>
                                                    <span class="dial-code col-2 text-right">+299</span>
                                                    <span class="example-number sr-only">22 12 34</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇩</span>
                                                    <span class="country-code sr-only">GD</span>
                                                    <span class="country-name truncate col-9">Grenada</span>
                                                    <span class="dial-code col-2 text-right">+1473</span>
                                                    <span class="example-number sr-only">(473) 403-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇵</span>
                                                    <span class="country-code sr-only">GP</span>
                                                    <span class="country-name truncate col-9">Guadeloupe</span>
                                                    <span class="dial-code col-2 text-right">+590</span>
                                                    <span class="example-number sr-only">0690 30-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇺</span>
                                                    <span class="country-code sr-only">GU</span>
                                                    <span class="country-name truncate col-9">Guam</span>
                                                    <span class="dial-code col-2 text-right">+1671</span>
                                                    <span class="example-number sr-only">(671) 300-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇹</span>
                                                    <span class="country-code sr-only">GT</span>
                                                    <span class="country-name truncate col-9">Guatemala</span>
                                                    <span class="dial-code col-2 text-right">+502</span>
                                                    <span class="example-number sr-only">5123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇬</span>
                                                    <span class="country-code sr-only">GG</span>
                                                    <span class="country-name truncate col-9">Guernsey</span>
                                                    <span class="dial-code col-2 text-right">+44</span>
                                                    <span class="example-number sr-only">07781 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇳</span>
                                                    <span class="country-code sr-only">GN</span>
                                                    <span class="country-name truncate col-9">Guinea (Guinée)</span>
                                                    <span class="dial-code col-2 text-right">+224</span>
                                                    <span class="example-number sr-only">601 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇼</span>
                                                    <span class="country-code sr-only">GW</span>
                                                    <span class="country-name truncate col-9">Guinea-Bissau (Guiné Bissau)</span>
                                                    <span class="dial-code col-2 text-right">+245</span>
                                                    <span class="example-number sr-only">955 012 345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇬🇾</span>
                                                    <span class="country-code sr-only">GY</span>
                                                    <span class="country-name truncate col-9">Guyana</span>
                                                    <span class="dial-code col-2 text-right">+592</span>
                                                    <span class="example-number sr-only">609 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇭🇹</span>
                                                    <span class="country-code sr-only">HT</span>
                                                    <span class="country-name truncate col-9">Haiti</span>
                                                    <span class="dial-code col-2 text-right">+509</span>
                                                    <span class="example-number sr-only">34 10 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇭🇲</span>
                                                    <span class="country-code sr-only">HM</span>
                                                    <span class="country-name truncate col-9">Heard &amp; McDonald Islands</span>
                                                    <span class="dial-code col-2 text-right">+672</span>
                                                    <span class="example-number sr-only">0412 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇭🇳</span>
                                                    <span class="country-code sr-only">HN</span>
                                                    <span class="country-name truncate col-9">Honduras</span>
                                                    <span class="dial-code col-2 text-right">+504</span>
                                                    <span class="example-number sr-only">9123-4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇭🇰</span>
                                                    <span class="country-code sr-only">HK</span>
                                                    <span class="country-name truncate col-9">Hong Kong (香港)</span>
                                                    <span class="dial-code col-2 text-right">+852</span>
                                                    <span class="example-number sr-only">5123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇭🇺</span>
                                                    <span class="country-code sr-only">HU</span>
                                                    <span class="country-name truncate col-9">Hungary (Magyarország)</span>
                                                    <span class="dial-code col-2 text-right">+36</span>
                                                    <span class="example-number sr-only">(20) 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇸</span>
                                                    <span class="country-code sr-only">IS</span>
                                                    <span class="country-name truncate col-9">Iceland (Ísland)</span>
                                                    <span class="dial-code col-2 text-right">+354</span>
                                                    <span class="example-number sr-only">611 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇳</span>
                                                    <span class="country-code sr-only">IN</span>
                                                    <span class="country-name truncate col-9">India (भारत)</span>
                                                    <span class="dial-code col-2 text-right">+91</span>
                                                    <span class="example-number sr-only">099876 54321</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇩</span>
                                                    <span class="country-code sr-only">ID</span>
                                                    <span class="country-name truncate col-9">Indonesia</span>
                                                    <span class="dial-code col-2 text-right">+62</span>
                                                    <span class="example-number sr-only">0812-345-678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇷</span>
                                                    <span class="country-code sr-only">IR</span>
                                                    <span class="country-name truncate col-9">Iran (‫ایران‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+98</span>
                                                    <span class="example-number sr-only">0912 345 6789</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇶</span>
                                                    <span class="country-code sr-only">IQ</span>
                                                    <span class="country-name truncate col-9">Iraq (‫العراق‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+964</span>
                                                    <span class="example-number sr-only">0791 234 5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇪</span>
                                                    <span class="country-code sr-only">IE</span>
                                                    <span class="country-name truncate col-9">Ireland</span>
                                                    <span class="dial-code col-2 text-right">+353</span>
                                                    <span class="example-number sr-only">085 012 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇲</span>
                                                    <span class="country-code sr-only">IM</span>
                                                    <span class="country-name truncate col-9">Isle of Man</span>
                                                    <span class="dial-code col-2 text-right">+44</span>
                                                    <span class="example-number sr-only">07924 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇱</span>
                                                    <span class="country-code sr-only">IL</span>
                                                    <span class="country-name truncate col-9">Israel (‫ישראל‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+972</span>
                                                    <span class="example-number sr-only">050-123-4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇮🇹</span>
                                                    <span class="country-code sr-only">IT</span>
                                                    <span class="country-name truncate col-9">Italy (Italia)</span>
                                                    <span class="dial-code col-2 text-right">+39</span>
                                                    <span class="example-number sr-only">312 345 6789</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇯🇲</span>
                                                    <span class="country-code sr-only">JM</span>
                                                    <span class="country-name truncate col-9">Jamaica</span>
                                                    <span class="dial-code col-2 text-right">+1876</span>
                                                    <span class="example-number sr-only">(876) 210-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇯🇵</span>
                                                    <span class="country-code sr-only">JP</span>
                                                    <span class="country-name truncate col-9">Japan (日本)</span>
                                                    <span class="dial-code col-2 text-right">+81</span>
                                                    <span class="example-number sr-only">090-1234-5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇯🇪</span>
                                                    <span class="country-code sr-only">JE</span>
                                                    <span class="country-name truncate col-9">Jersey</span>
                                                    <span class="dial-code col-2 text-right">+44</span>
                                                    <span class="example-number sr-only">07797 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇯🇴</span>
                                                    <span class="country-code sr-only">JO</span>
                                                    <span class="country-name truncate col-9">Jordan (‫الأردن‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+962</span>
                                                    <span class="example-number sr-only">07 9012 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇿</span>
                                                    <span class="country-code sr-only">KZ</span>
                                                    <span class="country-name truncate col-9">Kazakhstan (Казахстан)</span>
                                                    <span class="dial-code col-2 text-right">+7</span>
                                                    <span class="example-number sr-only">8 (771) 000 9998</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇪</span>
                                                    <span class="country-code sr-only">KE</span>
                                                    <span class="country-name truncate col-9">Kenya</span>
                                                    <span class="dial-code col-2 text-right">+254</span>
                                                    <span class="example-number sr-only">0712 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇮</span>
                                                    <span class="country-code sr-only">KI</span>
                                                    <span class="country-name truncate col-9">Kiribati</span>
                                                    <span class="dial-code col-2 text-right">+686</span>
                                                    <span class="example-number sr-only">72012345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇽🇰</span>
                                                    <span class="country-code sr-only">XK</span>
                                                    <span class="country-name truncate col-9">Kosovo</span>
                                                    <span class="dial-code col-2 text-right">+383</span>
                                                    <span class="example-number sr-only"></span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇼</span>
                                                    <span class="country-code sr-only">KW</span>
                                                    <span class="country-name truncate col-9">Kuwait (‫الكويت‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+965</span>
                                                    <span class="example-number sr-only">500 12345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇬</span>
                                                    <span class="country-code sr-only">KG</span>
                                                    <span class="country-name truncate col-9">Kyrgyzstan (Кыргызстан)</span>
                                                    <span class="dial-code col-2 text-right">+996</span>
                                                    <span class="example-number sr-only">0700 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇦</span>
                                                    <span class="country-code sr-only">LA</span>
                                                    <span class="country-name truncate col-9">Laos (ລາວ)</span>
                                                    <span class="dial-code col-2 text-right">+856</span>
                                                    <span class="example-number sr-only">020 23 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇻</span>
                                                    <span class="country-code sr-only">LV</span>
                                                    <span class="country-name truncate col-9">Latvia (Latvija)</span>
                                                    <span class="dial-code col-2 text-right">+371</span>
                                                    <span class="example-number sr-only">21 234 567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇧</span>
                                                    <span class="country-code sr-only">LB</span>
                                                    <span class="country-name truncate col-9">Lebanon (‫لبنان‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+961</span>
                                                    <span class="example-number sr-only">71 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇸</span>
                                                    <span class="country-code sr-only">LS</span>
                                                    <span class="country-name truncate col-9">Lesotho</span>
                                                    <span class="dial-code col-2 text-right">+266</span>
                                                    <span class="example-number sr-only">5012 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇷</span>
                                                    <span class="country-code sr-only">LR</span>
                                                    <span class="country-name truncate col-9">Liberia</span>
                                                    <span class="dial-code col-2 text-right">+231</span>
                                                    <span class="example-number sr-only">0770 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇾</span>
                                                    <span class="country-code sr-only">LY</span>
                                                    <span class="country-name truncate col-9">Libya (‫ليبيا‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+218</span>
                                                    <span class="example-number sr-only">091-2345678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇮</span>
                                                    <span class="country-code sr-only">LI</span>
                                                    <span class="country-name truncate col-9">Liechtenstein</span>
                                                    <span class="dial-code col-2 text-right">+423</span>
                                                    <span class="example-number sr-only">660 234 567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇹</span>
                                                    <span class="country-code sr-only">LT</span>
                                                    <span class="country-name truncate col-9">Lithuania (Lietuva)</span>
                                                    <span class="dial-code col-2 text-right">+370</span>
                                                    <span class="example-number sr-only">(8-612) 34567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇺</span>
                                                    <span class="country-code sr-only">LU</span>
                                                    <span class="country-name truncate col-9">Luxembourg</span>
                                                    <span class="dial-code col-2 text-right">+352</span>
                                                    <span class="example-number sr-only">628 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇴</span>
                                                    <span class="country-code sr-only">MO</span>
                                                    <span class="country-name truncate col-9">Macau (澳門)</span>
                                                    <span class="dial-code col-2 text-right">+853</span>
                                                    <span class="example-number sr-only">6612 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇰</span>
                                                    <span class="country-code sr-only">MK</span>
                                                    <span class="country-name truncate col-9">Macedonia (FYROM) (Македонија)</span>
                                                    <span class="dial-code col-2 text-right">+389</span>
                                                    <span class="example-number sr-only">072 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇬</span>
                                                    <span class="country-code sr-only">MG</span>
                                                    <span class="country-name truncate col-9">Madagascar (Madagasikara)</span>
                                                    <span class="dial-code col-2 text-right">+261</span>
                                                    <span class="example-number sr-only">032 12 345 67</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇼</span>
                                                    <span class="country-code sr-only">MW</span>
                                                    <span class="country-name truncate col-9">Malawi</span>
                                                    <span class="dial-code col-2 text-right">+265</span>
                                                    <span class="example-number sr-only">0991 23 45 67</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇾</span>
                                                    <span class="country-code sr-only">MY</span>
                                                    <span class="country-name truncate col-9">Malaysia</span>
                                                    <span class="dial-code col-2 text-right">+60</span>
                                                    <span class="example-number hidden" 012-345-6789></span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇻</span>
                                                    <span class="country-code sr-only">MV</span>
                                                    <span class="country-name truncate col-9">Maldives</span>
                                                    <span class="dial-code col-2 text-right">+960</span>
                                                    <span class="example-number sr-only">771-2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇱</span>
                                                    <span class="country-code sr-only">ML</span>
                                                    <span class="country-name truncate col-9">Mali</span>
                                                    <span class="dial-code col-2 text-right">+223</span>
                                                    <span class="example-number sr-only">65 01 23 45</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇹</span>
                                                    <span class="country-code sr-only">MT</span>
                                                    <span class="country-name truncate col-9">Malta</span>
                                                    <span class="dial-code col-2 text-right">+356</span>
                                                    <span class="example-number sr-only">9696 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇭</span>
                                                    <span class="country-code sr-only">MH</span>
                                                    <span class="country-name truncate col-9">Marshall Islands</span>
                                                    <span class="dial-code col-2 text-right">+692</span>
                                                    <span class="example-number sr-only">235-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇶</span>
                                                    <span class="country-code sr-only">MQ</span>
                                                    <span class="country-name truncate col-9">Martinique</span>
                                                    <span class="dial-code col-2 text-right">+596</span>
                                                    <span class="example-number sr-only">0696 20 12 34</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇷</span>
                                                    <span class="country-code sr-only">MR</span>
                                                    <span class="country-name truncate col-9">Mauritania (‫موريتانيا‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+222</span>
                                                    <span class="example-number sr-only">22 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇺</span>
                                                    <span class="country-code sr-only">MU</span>
                                                    <span class="country-name truncate col-9">Mauritius (Moris)</span>
                                                    <span class="dial-code col-2 text-right">+230</span>
                                                    <span class="example-number sr-only">5251 2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇾🇹</span>
                                                    <span class="country-code sr-only">YT</span>
                                                    <span class="country-name truncate col-9">Mayotte</span>
                                                    <span class="dial-code col-2 text-right">+262</span>
                                                    <span class="example-number sr-only">0639 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇽</span>
                                                    <span class="country-code sr-only">MX</span>
                                                    <span class="country-name truncate col-9">Mexico (México)</span>
                                                    <span class="dial-code col-2 text-right">+52</span>
                                                    <span class="example-number sr-only">044 22 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇫🇲</span>
                                                    <span class="country-code sr-only">FM</span>
                                                    <span class="country-name truncate col-9">Micronesia</span>
                                                    <span class="dial-code col-2 text-right">+691</span>
                                                    <span class="example-number sr-only">350 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇩</span>
                                                    <span class="country-code sr-only">MD</span>
                                                    <span class="country-name truncate col-9">Moldova (Republica Moldova)</span>
                                                    <span class="dial-code col-2 text-right">+373</span>
                                                    <span class="example-number sr-only">0621 12 345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇨</span>
                                                    <span class="country-code sr-only">MC</span>
                                                    <span class="country-name truncate col-9">Monaco</span>
                                                    <span class="dial-code col-2 text-right">+377</span>
                                                    <span class="example-number sr-only">06 12 34 56 78</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇳</span>
                                                    <span class="country-code sr-only">MN</span>
                                                    <span class="country-name truncate col-9">Mongolia (Монгол)</span>
                                                    <span class="dial-code col-2 text-right">+976</span>
                                                    <span class="example-number sr-only">8812 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇪</span>
                                                    <span class="country-code sr-only">ME</span>
                                                    <span class="country-name truncate col-9">Montenegro (Crna Gora)</span>
                                                    <span class="dial-code col-2 text-right">+382</span>
                                                    <span class="example-number sr-only">067 622 901</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇸</span>
                                                    <span class="country-code sr-only">MS</span>
                                                    <span class="country-name truncate col-9">Montserrat</span>
                                                    <span class="dial-code col-2 text-right">+1664</span>
                                                    <span class="example-number sr-only">(664) 492-3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇦</span>
                                                    <span class="country-code sr-only">MA</span>
                                                    <span class="country-name truncate col-9">Morocco (‫المغرب‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+212</span>
                                                    <span class="example-number sr-only">0650-123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇿</span>
                                                    <span class="country-code sr-only">MZ</span>
                                                    <span class="country-name truncate col-9">Mozambique (Moçambique)</span>
                                                    <span class="dial-code col-2 text-right">+258</span>
                                                    <span class="example-number sr-only">82 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇲</span>
                                                    <span class="country-code sr-only">MM</span>
                                                    <span class="country-name truncate col-9">Myanmar (Burma) (မြန်မာ)</span>
                                                    <span class="dial-code col-2 text-right">+95</span>
                                                    <span class="example-number sr-only">09 212 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇦</span>
                                                    <span class="country-code sr-only">NA</span>
                                                    <span class="country-name truncate col-9">Namibia (Namibië)</span>
                                                    <span class="dial-code col-2 text-right">+264</span>
                                                    <span class="example-number sr-only">081 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇷</span>
                                                    <span class="country-code sr-only">NR</span>
                                                    <span class="country-name truncate col-9">Nauru</span>
                                                    <span class="dial-code col-2 text-right">+674</span>
                                                    <span class="example-number sr-only">555 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇵</span>
                                                    <span class="country-code sr-only">NP</span>
                                                    <span class="country-name truncate col-9">Nepal (नेपाल)</span>
                                                    <span class="dial-code col-2 text-right">+977</span>
                                                    <span class="example-number sr-only">984-1234567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇱</span>
                                                    <span class="country-code sr-only">NL</span>
                                                    <span class="country-name truncate col-9">Netherlands (Nederland)</span>
                                                    <span class="dial-code col-2 text-right">+31</span>
                                                    <span class="example-number sr-only">06 12345678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇨</span>
                                                    <span class="country-code sr-only">NC</span>
                                                    <span class="country-name truncate col-9">New Caledonia (Nouvelle-Calédonie)</span>
                                                    <span class="dial-code col-2 text-right">+687</span>
                                                    <span class="example-number sr-only">75.12.34</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇿</span>
                                                    <span class="country-code sr-only">NZ</span>
                                                    <span class="country-name truncate col-9">New Zealand</span>
                                                    <span class="dial-code col-2 text-right">+64</span>
                                                    <span class="example-number sr-only">021 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇮</span>
                                                    <span class="country-code sr-only">NI</span>
                                                    <span class="country-name truncate col-9">Nicaragua</span>
                                                    <span class="dial-code col-2 text-right">+505</span>
                                                    <span class="example-number sr-only">8123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇪</span>
                                                    <span class="country-code sr-only">NE</span>
                                                    <span class="country-name truncate col-9">Niger (Nijar)</span>
                                                    <span class="dial-code col-2 text-right">+227</span>
                                                    <span class="example-number sr-only">93 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇬</span>
                                                    <span class="country-code sr-only">NG</span>
                                                    <span class="country-name truncate col-9">Nigeria</span>
                                                    <span class="dial-code col-2 text-right">+234</span>
                                                    <span class="example-number sr-only">0802 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇺</span>
                                                    <span class="country-code sr-only">NU</span>
                                                    <span class="country-name truncate col-9">Niue</span>
                                                    <span class="dial-code col-2 text-right">+683</span>
                                                    <span class="example-number sr-only">1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇫</span>
                                                    <span class="country-code sr-only">NF</span>
                                                    <span class="country-name truncate col-9">Norfolk Island</span>
                                                    <span class="dial-code col-2 text-right">+672</span>
                                                    <span class="example-number sr-only">3 81234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇵</span>
                                                    <span class="country-code sr-only">MP</span>
                                                    <span class="country-name truncate col-9">Northern Mariana Islands</span>
                                                    <span class="dial-code col-2 text-right">+1670</span>
                                                    <span class="example-number sr-only">(670) 234-5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇵</span>
                                                    <span class="country-code sr-only">KP</span>
                                                    <span class="country-name truncate col-9">North Korea (조선 민주주의 인민 공화국)</span>
                                                    <span class="dial-code col-2 text-right">+850</span>
                                                    <span class="example-number sr-only">0192 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇳🇴</span>
                                                    <span class="country-code sr-only">NO</span>
                                                    <span class="country-name truncate col-9">Norway (Norge)</span>
                                                    <span class="dial-code col-2 text-right">+47</span>
                                                    <span class="example-number sr-only">406 12 345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇴🇲</span>
                                                    <span class="country-code sr-only">OM</span>
                                                    <span class="country-name truncate col-9">Oman (‫عُمان‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+968</span>
                                                    <span class="example-number sr-only">9212 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇰</span>
                                                    <span class="country-code sr-only">PK</span>
                                                    <span class="country-name truncate col-9">Pakistan (‫پاکستان‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+92</span>
                                                    <span class="example-number sr-only">0301 2345678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇼</span>
                                                    <span class="country-code sr-only">PW</span>
                                                    <span class="country-name truncate col-9">Palau</span>
                                                    <span class="dial-code col-2 text-right">+680</span>
                                                    <span class="example-number sr-only">620 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇸</span>
                                                    <span class="country-code sr-only">PS</span>
                                                    <span class="country-name truncate col-9">Palestinian Territories (‫فلسطين‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+970</span>
                                                    <span class="example-number sr-only">0599 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇦</span>
                                                    <span class="country-code sr-only">PA</span>
                                                    <span class="country-name truncate col-9">Panama (Panamá)</span>
                                                    <span class="dial-code col-2 text-right">+507</span>
                                                    <span class="example-number sr-only">6001-2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇬</span>
                                                    <span class="country-code sr-only">PG</span>
                                                    <span class="country-name truncate col-9">Papua New Guinea</span>
                                                    <span class="dial-code col-2 text-right">+675</span>
                                                    <span class="example-number sr-only">681 2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇾</span>
                                                    <span class="country-code sr-only">PY</span>
                                                    <span class="country-name truncate col-9">Paraguay</span>
                                                    <span class="dial-code col-2 text-right">+595</span>
                                                    <span class="example-number sr-only">0961 456789</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇪</span>
                                                    <span class="country-code sr-only">PE</span>
                                                    <span class="country-name truncate col-9">Peru (Perú)</span>
                                                    <span class="dial-code col-2 text-right">+51</span>
                                                    <span class="example-number sr-only">912 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇭</span>
                                                    <span class="country-code sr-only">PH</span>
                                                    <span class="country-name truncate col-9">Philippines</span>
                                                    <span class="dial-code col-2 text-right">+63</span>
                                                    <span class="example-number sr-only">0905 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇳</span>
                                                    <span class="country-code sr-only">PN</span>
                                                    <span class="country-name truncate col-9">Pitcairn Islands</span>
                                                    <span class="dial-code col-2 text-right">+672</span>
                                                    <span class="example-number sr-only">0412 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇱</span>
                                                    <span class="country-code sr-only">PL</span>
                                                    <span class="country-name truncate col-9">Poland (Polska)</span>
                                                    <span class="dial-code col-2 text-right">+48</span>
                                                    <span class="example-number sr-only">512 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇹</span>
                                                    <span class="country-code sr-only">PT</span>
                                                    <span class="country-name truncate col-9">Portugal</span>
                                                    <span class="dial-code col-2 text-right">+351</span>
                                                    <span class="example-number sr-only">912 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇷</span>
                                                    <span class="country-code sr-only">PR</span>
                                                    <span class="country-name truncate col-9">Puerto Rico</span>
                                                    <span class="dial-code col-2 text-right">+1</span>
                                                    <span class="example-number sr-only">(787) 234-5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇶🇦</span>
                                                    <span class="country-code sr-only">QA</span>
                                                    <span class="country-name truncate col-9">Qatar (‫قطر‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+974</span>
                                                    <span class="example-number sr-only">3312 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇷🇪</span>
                                                    <span class="country-code sr-only">RE</span>
                                                    <span class="country-name truncate col-9">Réunion (La Réunion)</span>
                                                    <span class="dial-code col-2 text-right">+262</span>
                                                    <span class="example-number sr-only">0692 12 34 56</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇷🇴</span>
                                                    <span class="country-code sr-only">RO</span>
                                                    <span class="country-name truncate col-9">Romania (România)</span>
                                                    <span class="dial-code col-2 text-right">+40</span>
                                                    <span class="example-number sr-only">0712 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇷🇺</span>
                                                    <span class="country-code sr-only">RU</span>
                                                    <span class="country-name truncate col-9">Russia (Россия)</span>
                                                    <span class="dial-code col-2 text-right">+7</span>
                                                    <span class="example-number sr-only">8 (912) 345-67-89</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇷🇼</span>
                                                    <span class="country-code sr-only">RW</span>
                                                    <span class="country-name truncate col-9">Rwanda</span>
                                                    <span class="dial-code col-2 text-right">+250</span>
                                                    <span class="example-number sr-only">0720 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇼🇸</span>
                                                    <span class="country-code sr-only">WS</span>
                                                    <span class="country-name truncate col-9">Samoa</span>
                                                    <span class="dial-code col-2 text-right">+685</span>
                                                    <span class="example-number sr-only">601234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇲</span>
                                                    <span class="country-code sr-only">SM</span>
                                                    <span class="country-name truncate col-9">San Marino</span>
                                                    <span class="dial-code col-2 text-right">+378</span>
                                                    <span class="example-number sr-only">66 66 12 12</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇹</span>
                                                    <span class="country-code sr-only">ST</span>
                                                    <span class="country-name truncate col-9">São Tomé &amp; Príncipe (São Tomé e Príncipe)</span>
                                                    <span class="dial-code col-2 text-right">+239</span>
                                                    <span class="example-number sr-only">981 2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇦</span>
                                                    <span class="country-code sr-only">SA</span>
                                                    <span class="country-name truncate col-9">Saudi Arabia (‫المملكة العربية السعودية‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+966</span>
                                                    <span class="example-number sr-only">051 234 5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇳</span>
                                                    <span class="country-code sr-only">SN</span>
                                                    <span class="country-name truncate col-9">Senegal (Sénégal)</span>
                                                    <span class="dial-code col-2 text-right">+221</span>
                                                    <span class="example-number sr-only">70 123 45 67</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇷🇸</span>
                                                    <span class="country-code sr-only">RS</span>
                                                    <span class="country-name truncate col-9">Serbia (Србија)</span>
                                                    <span class="dial-code col-2 text-right">+381</span>
                                                    <span class="example-number sr-only">060 1234567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇨</span>
                                                    <span class="country-code sr-only">SC</span>
                                                    <span class="country-name truncate col-9">Seychelles</span>
                                                    <span class="dial-code col-2 text-right">+248</span>
                                                    <span class="example-number sr-only">2 510 123</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇱</span>
                                                    <span class="country-code sr-only">SL</span>
                                                    <span class="country-name truncate col-9">Sierra Leone</span>
                                                    <span class="dial-code col-2 text-right">+232</span>
                                                    <span class="example-number sr-only">(025) 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇬</span>
                                                    <span class="country-code sr-only">SG</span>
                                                    <span class="country-name truncate col-9">Singapore</span>
                                                    <span class="dial-code col-2 text-right">+65</span>
                                                    <span class="example-number sr-only">8123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇽</span>
                                                    <span class="country-code sr-only">SX</span>
                                                    <span class="country-name truncate col-9">Sint Maarten</span>
                                                    <span class="dial-code col-2 text-right">+1721</span>
                                                    <span class="example-number sr-only">(721) 520-5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇰</span>
                                                    <span class="country-code sr-only">SK</span>
                                                    <span class="country-name truncate col-9">Slovakia (Slovensko)</span>
                                                    <span class="dial-code col-2 text-right">+421</span>
                                                    <span class="example-number sr-only">0912 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇮</span>
                                                    <span class="country-code sr-only">SI</span>
                                                    <span class="country-name truncate col-9">Slovenia (Slovenija)</span>
                                                    <span class="dial-code col-2 text-right">+386</span>
                                                    <span class="example-number sr-only">031 234 567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇧</span>
                                                    <span class="country-code sr-only">SB</span>
                                                    <span class="country-name truncate col-9">Solomon Islands</span>
                                                    <span class="dial-code col-2 text-right">+677</span>
                                                    <span class="example-number sr-only">74 21234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇴</span>
                                                    <span class="country-code sr-only">SO</span>
                                                    <span class="country-name truncate col-9">Somalia (Soomaaliya)</span>
                                                    <span class="dial-code col-2 text-right">+252</span>
                                                    <span class="example-number sr-only">7 1123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇿🇦</span>
                                                    <span class="country-code sr-only">ZA</span>
                                                    <span class="country-name truncate col-9">South Africa</span>
                                                    <span class="dial-code col-2 text-right">+27</span>
                                                    <span class="example-number sr-only">071 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇷</span>
                                                    <span class="country-code sr-only">KR</span>
                                                    <span class="country-name truncate col-9">South Korea (대한민국)</span>
                                                    <span class="dial-code col-2 text-right">+82</span>
                                                    <span class="example-number sr-only">010-0000-0000</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇸</span>
                                                    <span class="country-code sr-only">SS</span>
                                                    <span class="country-name truncate col-9">South Sudan (‫جنوب السودان‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+211</span>
                                                    <span class="example-number sr-only">0977 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇪🇸</span>
                                                    <span class="country-code sr-only">ES</span>
                                                    <span class="country-name truncate col-9">Spain (España)</span>
                                                    <span class="dial-code col-2 text-right">+34</span>
                                                    <span class="example-number sr-only">612 34 56 78</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇰</span>
                                                    <span class="country-code sr-only">LK</span>
                                                    <span class="country-name truncate col-9">Sri Lanka (ශ්‍රී ලංකාව)</span>
                                                    <span class="dial-code col-2 text-right">+94</span>
                                                    <span class="example-number sr-only">071 234 5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇧🇱</span>
                                                    <span class="country-code sr-only">BL</span>
                                                    <span class="country-name truncate col-9">St. Barthélemy (Saint-Barthélemy)</span>
                                                    <span class="dial-code col-2 text-right">+590</span>
                                                    <span class="example-number sr-only">0690 30-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇭</span>
                                                    <span class="country-code sr-only">SH</span>
                                                    <span class="country-name truncate col-9">St. Helena</span>
                                                    <span class="dial-code col-2 text-right">+290</span>
                                                    <span class="example-number sr-only">2 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇰🇳</span>
                                                    <span class="country-code sr-only">KN</span>
                                                    <span class="country-name truncate col-9">St. Kitts &amp; Nevis</span>
                                                    <span class="dial-code col-2 text-right">+1869</span>
                                                    <span class="example-number sr-only">(869) 765-2917</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇱🇨</span>
                                                    <span class="country-code sr-only">LC</span>
                                                    <span class="country-name truncate col-9">St. Lucia</span>
                                                    <span class="dial-code col-2 text-right">+1758</span>
                                                    <span class="example-number sr-only">(758) 284-5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇲🇫</span>
                                                    <span class="country-code sr-only">MF</span>
                                                    <span class="country-name truncate col-9">St. Martin (Saint-Martin (partie française))</span>
                                                    <span class="dial-code col-2 text-right">+590</span>
                                                    <span class="example-number sr-only">0690 30-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇵🇲</span>
                                                    <span class="country-code sr-only">PM</span>
                                                    <span class="country-name truncate col-9">St. Pierre &amp; Miquelon (Saint-Pierre-et-Miquelon)</span>
                                                    <span class="dial-code col-2 text-right">+508</span>
                                                    <span class="example-number sr-only">055 12 34</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇻🇨</span>
                                                    <span class="country-code sr-only">VC</span>
                                                    <span class="country-name truncate col-9">St. Vincent &amp; the Grenadines</span>
                                                    <span class="dial-code col-2 text-right">+1784</span>
                                                    <span class="example-number sr-only">(784) 430-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇩</span>
                                                    <span class="country-code sr-only">SD</span>
                                                    <span class="country-name truncate col-9">Sudan (‫السودان‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+249</span>
                                                    <span class="example-number sr-only">091 123 1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇷</span>
                                                    <span class="country-code sr-only">SR</span>
                                                    <span class="country-name truncate col-9">Suriname</span>
                                                    <span class="dial-code col-2 text-right">+597</span>
                                                    <span class="example-number sr-only">741-2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇯</span>
                                                    <span class="country-code sr-only">SJ</span>
                                                    <span class="country-name truncate col-9">Svalbard &amp; Jan Mayen</span>
                                                    <span class="dial-code col-2 text-right">+47</span>
                                                    <span class="example-number sr-only">412 34 567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇿</span>
                                                    <span class="country-code sr-only">SZ</span>
                                                    <span class="country-name truncate col-9">Swaziland</span>
                                                    <span class="dial-code col-2 text-right">+268</span>
                                                    <span class="example-number sr-only">7612 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇪</span>
                                                    <span class="country-code sr-only">SE</span>
                                                    <span class="country-name truncate col-9">Sweden (Sverige)</span>
                                                    <span class="dial-code col-2 text-right">+46</span>
                                                    <span class="example-number sr-only">070-123 45 67</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇨🇭</span>
                                                    <span class="country-code sr-only">CH</span>
                                                    <span class="country-name truncate col-9">Switzerland (Schweiz)</span>
                                                    <span class="dial-code col-2 text-right">+41</span>
                                                    <span class="example-number sr-only">078 123 45 67</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇸🇾</span>
                                                    <span class="country-code sr-only">SY</span>
                                                    <span class="country-name truncate col-9">Syria (‫سوريا‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+963</span>
                                                    <span class="example-number sr-only">0944 567 890</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇼</span>
                                                    <span class="country-code sr-only">TW</span>
                                                    <span class="country-name truncate col-9">Taiwan (台灣)</span>
                                                    <span class="dial-code col-2 text-right">+886</span>
                                                    <span class="example-number sr-only">0912 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇯</span>
                                                    <span class="country-code sr-only">TJ</span>
                                                    <span class="country-name truncate col-9">Tajikistan</span>
                                                    <span class="dial-code col-2 text-right">+992</span>
                                                    <span class="example-number sr-only">(8) 917 12 3456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇿</span>
                                                    <span class="country-code sr-only">TZ</span>
                                                    <span class="country-name truncate col-9">Tanzania</span>
                                                    <span class="dial-code col-2 text-right">+255</span>
                                                    <span class="example-number sr-only">0621 234 567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇭</span>
                                                    <span class="country-code sr-only">TH</span>
                                                    <span class="country-name truncate col-9">Thailand (ไทย)</span>
                                                    <span class="dial-code col-2 text-right">+66</span>
                                                    <span class="example-number sr-only">081 234 5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇱</span>
                                                    <span class="country-code sr-only">TL</span>
                                                    <span class="country-name truncate col-9">Timor-Leste</span>
                                                    <span class="dial-code col-2 text-right">+670</span>
                                                    <span class="example-number sr-only">7721 2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇬</span>
                                                    <span class="country-code sr-only">TG</span>
                                                    <span class="country-name truncate col-9">Togo</span>
                                                    <span class="dial-code col-2 text-right">+228</span>
                                                    <span class="example-number sr-only">90 11 23 45</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇰</span>
                                                    <span class="country-code sr-only">TK</span>
                                                    <span class="country-name truncate col-9">Tokelau</span>
                                                    <span class="dial-code col-2 text-right">+690</span>
                                                    <span class="example-number sr-only">7290</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇴</span>
                                                    <span class="country-code sr-only">TO</span>
                                                    <span class="country-name truncate col-9">Tonga</span>
                                                    <span class="dial-code col-2 text-right">+676</span>
                                                    <span class="example-number sr-only">771 5123</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇹</span>
                                                    <span class="country-code sr-only">TT</span>
                                                    <span class="country-name truncate col-9">Trinidad &amp; Tobago</span>
                                                    <span class="dial-code col-2 text-right">+1868</span>
                                                    <span class="example-number sr-only">(868) 291-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇳</span>
                                                    <span class="country-code sr-only">TN</span>
                                                    <span class="country-name truncate col-9">Tunisia (‫تونس‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+216</span>
                                                    <span class="example-number sr-only">20 123 456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇷</span>
                                                    <span class="country-code sr-only">TR</span>
                                                    <span class="country-name truncate col-9">Turkey (Türkiye)</span>
                                                    <span class="dial-code col-2 text-right">+90</span>
                                                    <span class="example-number sr-only">0501 234 5678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇲</span>
                                                    <span class="country-code sr-only">TM</span>
                                                    <span class="country-name truncate col-9">Turkmenistan</span>
                                                    <span class="dial-code col-2 text-right">+993</span>
                                                    <span class="example-number sr-only">8 66 123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇨</span>
                                                    <span class="country-code sr-only">TC</span>
                                                    <span class="country-name truncate col-9">Turks &amp; Caicos Islands</span>
                                                    <span class="dial-code col-2 text-right">+1649</span>
                                                    <span class="example-number sr-only">(649) 213-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇹🇻</span>
                                                    <span class="country-code sr-only">TV</span>
                                                    <span class="country-name truncate col-9">Tuvalu</span>
                                                    <span class="dial-code col-2 text-right">+688</span>
                                                    <span class="example-number sr-only">901234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇺🇬</span>
                                                    <span class="country-code sr-only">UG</span>
                                                    <span class="country-name truncate col-9">Uganda</span>
                                                    <span class="dial-code col-2 text-right">+256</span>
                                                    <span class="example-number sr-only">0712 345678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇺🇦</span>
                                                    <span class="country-code sr-only">UA</span>
                                                    <span class="country-name truncate col-9">Ukraine (Україна)</span>
                                                    <span class="dial-code col-2 text-right">+380</span>
                                                    <span class="example-number sr-only">039 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇦🇪</span>
                                                    <span class="country-code sr-only">AE</span>
                                                    <span class="country-name truncate col-9">United Arab Emirates (‫الإمارات العربية المتحدة‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+971</span>
                                                    <span class="example-number sr-only">050 123 4567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇺🇾</span>
                                                    <span class="country-code sr-only">UY</span>
                                                    <span class="country-name truncate col-9">Uruguay</span>
                                                    <span class="dial-code col-2 text-right">+598</span>
                                                    <span class="example-number sr-only">094 231 234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇺🇲</span>
                                                    <span class="country-code sr-only">UM</span>
                                                    <span class="country-name truncate col-9">U.S. Outlying Islands</span>
                                                    <span class="dial-code col-2 text-right">+1</span>
                                                    <span class="example-number sr-only">(201) 555-0123</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇻🇮</span>
                                                    <span class="country-code sr-only">VI</span>
                                                    <span class="country-name truncate col-9">U.S. Virgin Islands</span>
                                                    <span class="dial-code col-2 text-right">+1340</span>
                                                    <span class="example-number sr-only">(340) 642-1234</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇺🇿</span>
                                                    <span class="country-code sr-only">UZ</span>
                                                    <span class="country-name truncate col-9">Uzbekistan (Oʻzbekiston)</span>
                                                    <span class="dial-code col-2 text-right">+998</span>
                                                    <span class="example-number sr-only">8 91 234 56 78</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇻🇺</span>
                                                    <span class="country-code sr-only">VU</span>
                                                    <span class="country-name truncate col-9">Vanuatu</span>
                                                    <span class="dial-code col-2 text-right">+678</span>
                                                    <span class="example-number sr-only">591 2345</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇻🇦</span>
                                                    <span class="country-code sr-only">VA</span>
                                                    <span class="country-name truncate col-9">Vatican City (Città del Vaticano)</span>
                                                    <span class="dial-code col-2 text-right">+39</span>
                                                    <span class="example-number sr-only">312 345 6789</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇻🇪</span>
                                                    <span class="country-code sr-only">VE</span>
                                                    <span class="country-name truncate col-9">Venezuela</span>
                                                    <span class="dial-code col-2 text-right">+58</span>
                                                    <span class="example-number sr-only">0412-1234567</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇻🇳</span>
                                                    <span class="country-code sr-only">VN</span>
                                                    <span class="country-name truncate col-9">Vietnam (Việt Nam)</span>
                                                    <span class="dial-code col-2 text-right">+84</span>
                                                    <span class="example-number sr-only">091 234 56 78</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇼🇫</span>
                                                    <span class="country-code sr-only">WF</span>
                                                    <span class="country-name truncate col-9">Wallis &amp; Futuna</span>
                                                    <span class="dial-code col-2 text-right">+681</span>
                                                    <span class="example-number sr-only">50 12 34</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇪🇭</span>
                                                    <span class="country-code sr-only">EH</span>
                                                    <span class="country-name truncate col-9">Western Sahara (‫الصحراء الغربية‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+212</span>
                                                    <span class="example-number sr-only">0650-123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇾🇪</span>
                                                    <span class="country-code sr-only">YE</span>
                                                    <span class="country-name truncate col-9">Yemen (‫اليمن‬‎)</span>
                                                    <span class="dial-code col-2 text-right">+967</span>
                                                    <span class="example-number sr-only">0712 345 678</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇿🇲</span>
                                                    <span class="country-code sr-only">ZM</span>
                                                    <span class="country-name truncate col-9">Zambia</span>
                                                    <span class="dial-code col-2 text-right">+260</span>
                                                    <span class="example-number sr-only">095 5123456</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item d-flex justify-content-between country-item">
                                                    <span class="flag-emoji col-1">🇿🇼</span>
                                                    <span class="country-code sr-only">ZW</span>
                                                    <span class="country-name truncate col-9">Zimbabwe</span>
                                                    <span class="dial-code col-2 text-right">+263</span>
                                                    <span class="example-number sr-only">071 123 4567</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <span class="input-group-addon">+1</span>
                                    <input type="text" class="form-control user-mobile" name="mobile" placeholder="(555) 123-4567">
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

                                    <div class="form-group">
                                        <label>Country *</label>
                                        <select id="country" name="country" class="form-control">
                                            <option value="">Select a country</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

                                    <div class="form-group">
                                        <label>State *</label>
                                        <select id="state" name="state" class="form-control">
                                            <option value="">Select a state</option>
                                        </select>
                                    </div>

                                </div>

                            </div>




                            <div class="form-group">
                                <label>Your Message *</label>
                                <textarea class="form-control" name="message" placeholder="Your Message" rows="4"></textarea>
                            </div>

                            <input type="hidden" name="country_code" id="country_code" value="<?= "+1"; ?>" readonly>
                            <button type="submit" class="btn btn-info btn-block btn-round process_button">Send request</button>
                        </form>

                        <div class="text-center text-muted delimiter"></div>
                        <div class="d-flex justify-content-center social-buttons">

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Contact Agent Modal End-->

    <!-- Business Modal Start-->
    <div class="modal" id="businessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Business Pricing Breakdown</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body price-modal-body">

                    <h6>Our pricing structure is simple and transparent, allowing you to choose the plan that best suits your team size. Take a look at our price ranges below:</h6>

                    <ol class="list price-list">
                        <li class="list-item">
                            <b>Basic Plan</b> - $49.99 per month ( up to 10 team members ):
                            Ideal for startups and small businesses, this plan offers all the essential features you need to collaborate effectively with your team.
                        </li>

                        <li class="list-item">
                            <b>Standard Plan</b> - $79.99 per month ( up to 50 team members ):
                            If your team is growing or already consists of a moderate number of members, this plan provides additional resources and functionalities to enhance your workflow.
                        </li>

                        <li class="list-item">
                            <b>Advanced Plan</b> - $149.99 per month ( up to 150 team members ):
                            Designed for larger organizations, this plan offers expanded capabilities, allowing you to manage a substantial team while maintaining efficiency and productivity.
                        </li>

                        <li class="list-item">
                            <b>Pro Plan</b> - $300.00 per month ( up to 500 team members ):
                            Tailored for businesses with a significant workforce, the pro plan accommodates larger teams while providing comprehensive features to facilitate collaboration and coordination.
                        </li>

                        <li class="list-item">
                            <b>Enterprise Plan</b> - Contact a Planiversity Sales Agent for specific pricing (more than 500 team members):
                            For enterprises with an extensive number of team members, our enterprise plan offers custom pricing to meet your unique needs. Get in touch with our dedicated sales team to discuss the most suitable plan for your organization.
                        </li>
                    </ol>

                    <h6>All our pricing plans also come with the option to subscribe annually, providing you with even more cost savings. By choosing the annual account, you will pay only for 11 months, granting you an additional month of service free of charge.</h6>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Business Modal End-->

    <?php
    include('dashboard/include/mobile-message.php');
    ?>

    <script src="<?php echo SITE; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/popper.min.js"></script>
    <script src="<?= SITE ?>dashboard/js/main.js"></script>

    <?php include('new_backend_script.php'); ?>
    <script src="https://js.stripe.com/v1/"></script>
    <script src="<?= SITE ?>assets/billing/js/country-states.js"></script>

    <script>
        let teamMembers = 0;
        let totalPrice = 0;
        let count = 0;
        let user_country_code = "US";
        let account_type = "<?= $account_type; ?>";

        function updatePriceCalculation(teamMembers) {

            switch (true) {
                case (teamMembers <= 10):
                    totalPrice = 49.99;
                    break;
                case (teamMembers <= 50):
                    totalPrice = 79.99;
                    break;
                case (teamMembers <= 150):
                    totalPrice = 149.99;
                    break;
                default:
                    totalPrice = 300.00;
            }

            return totalPrice;

        }

        // Function to update the position of the user icon
        function updateUserIconPosition() {
            var slider = $('#userSlider');
            var sliderWidth = slider.width();
            var sliderValue = slider.val();
            var min = slider.attr('min');
            var max = slider.attr('max');

            var iconWidth = parseInt($('#userIcon').css('width'));
            var newPosition = (sliderValue - min) / (max - min) * (sliderWidth - iconWidth);
            $('#userIcon').css('left', newPosition + 'px');

        }

        $(function() {
            updateUserIconPosition();
        });

        $('#contact_agent').click(function() {
            $('#contactAgentModal').modal('show');
        });

        <?php if ($account_type != 'individual') { ?>

            const country_array = country_and_states.country;
            const states_array = country_and_states.states;

            const id_state_option = document.getElementById("state");
            const id_country_option = document.getElementById("country");

            function updateTotalPrice() {

                teamMembers = parseInt($('#userSlider').val());

                let totalPriceValue = updatePriceCalculation(teamMembers);

                $('#member_count').text(teamMembers);

                $('#monthly-breakdown').text("$" + (totalPriceValue).toFixed(2));
                $('#annual-breakdown').text("$" + (totalPriceValue * 11).toFixed(2) + " ( Get 12 months but only pay for 11 )");

            }

            const createCountryNamesDropdown = (country_array) => {
                let option = '';
                option += '<option value="">select a country</option>';

                for (let country_code in country_array) {
                    // set selected option user country
                    let selected = (country_code == user_country_code) ? ' selected' : '';
                    option += '<option value="' + country_code + '"' + selected + '>' + country_array[country_code] + '</option>';
                }
                id_country_option.innerHTML = option;
            };
            const createStatesNamesDropdown = (states_array) => {
                let selected_country_code = id_country_option.value;

                console.log('selected_country_code', selected_country_code);

                // get state names
                let state_names = states_array[selected_country_code];

                console.log('selected_country_code', selected_country_code);

                // if invalid country code
                if (!state_names) {
                    id_state_option.innerHTML = '<option value="">select a state</option>';
                    return;
                }
                let option = '';
                option += '<select id="state">';
                option += '<option value="">select a state</option>';
                for (let i = 0; i < state_names.length; i++) {
                    option += '<option value="' + state_names[i].name + '">' + state_names[i].name + '</option>';
                }
                option += '</select>';
                id_state_option.innerHTML = option;
            };

            $(function() {
                updateTotalPrice();

                createCountryNamesDropdown(country_array);
                createStatesNamesDropdown(states_array);
            });

            id_country_option.addEventListener("change", function(event) {
                createStatesNamesDropdown(states_array);
            });

            $('#userSlider').on('input', function() {
                updateTotalPrice();
                updateUserIconPosition();
            });

            $('#learn_more').click(function() {
                $('#businessModal').modal('show');
            });

            $(".dropdown-menu li button").click(function(evt) {
                // Setup VARs
                var inputGroup = $('.input-group');
                var inputGroupBtn = inputGroup.find('.input-group-btn .btn');
                var inputGroupAddon = inputGroup.find('.input-group-addon');
                var inputGroupInput = inputGroup.find('.form-control');

                // Get info for the selected country
                var selectedCountry = $(evt.target).closest('li');
                var selectedEmoji = selectedCountry.find('.flag-emoji').html();
                var selectedExampleNumber = selectedCountry.find('.example-number').html();
                var selectedDialCode = selectedCountry.find('.dial-code').html();

                // Dynamically update the picker
                inputGroupBtn.find('.emoji').html(selectedEmoji);
                inputGroupAddon.html(selectedDialCode);
                $('#country_code').val(selectedDialCode);
                inputGroupInput.attr("placeholder", selectedExampleNumber);
            });

            $("#contactform").validate({

                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    mobile: {
                        maxlength: 20,
                    },
                    country: {
                        required: true,
                    },
                    state: {
                        required: true,
                    },
                    message: {
                        required: true,
                        minlength: 5,
                        maxlength: 150,
                    },
                },

                messages: {
                    name: {
                        required: "Please type your name",
                    },
                    email: {
                        required: "Please type your email",
                        email: "Please type valid email",
                    },
                    country: {
                        required: "Please select a country",
                    },
                    state: {
                        required: "Please select a state",
                    },
                    message: {
                        required: "Please type your message",
                        minlength: "Minimum length 5 characters",
                        maxlength: "Maximum length 150 characters",
                    },
                },

                submitHandler: function(form) {

                    $('.process_button').css('cursor', 'wait');
                    $('.process_button').attr('disabled', true);

                    $.ajax({
                        url: SITE + "root/price/process",
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(res) {

                            $('#contactAgentModal').modal('hide');
                            $(form).trigger("reset");

                            swal({
                                title: "Inquery Submitted",
                                text: "Our agent will contact you shortly.",
                                type: "success",
                                timer: 4000,
                                showConfirmButton: true,
                                customClass: 'swal-height'
                            });

                            $('.process_button').css('cursor', 'pointer');
                            $('.process_button').removeAttr('disabled');

                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            if (jqXHR.responseJSON.data.error) {
                                toastr.error(jqXHR.responseJSON.data.error);
                            }

                            $('.process_button').css('cursor', 'pointer');
                            $('.process_button').removeAttr('disabled');

                        }
                    });
                },
                //errorElement: "span",                                               // Do not change code below
                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                }

            });

        <?php } else { ?>

            $(function() {
                updateTotalPrice();
            });

            $('#userSlider').on('input', function() {
                updateTotalPrice();
                updateUserIconPosition();
            });

            function updateTotalPrice() {
                teamMembers = parseInt($('#userSlider').val());

                let totalPrice = updateIndividualPriceCalculation(teamMembers);

                $('#member_count').text(teamMembers);

                const couponApplied = $('#coupon_flag').val();
                const couponPercent = parseInt($('#coupon_percent').val());


                if (couponApplied !== '0' && couponPercent) {
                    let monthly_percent_process = percentCalculation(totalPrice.monthly, couponPercent);
                    discountFormate("monthly", totalPrice.monthly, monthly_percent_process);

                    let annualy_percent_process = percentCalculation(totalPrice.annual, couponPercent);
                    discountFormate("annual", totalPrice.annual, annualy_percent_process);

                    let one_time_percent_process = percentCalculation(totalPrice.oneTime, couponPercent);
                    discountFormate("one-time", totalPrice.oneTime, one_time_percent_process);

                    return;
                }

                $('#one-time-breakdown').text("$" + (totalPrice.oneTime).toFixed(2));
                $('#monthly-breakdown').text("$" + (totalPrice.monthly).toFixed(2));
                $('#annual-breakdown').text("$" + (totalPrice.annual).toFixed(2) + " ( Get 12 months but only pay for 11 )");

            }

            function updateIndividualPriceCalculation(teamMembers) {

                switch (true) {
                    case (teamMembers <= 10):
                        return {
                            oneTime: 4.99,
                                monthly: 10.99,
                                annual: 120.00
                        }
                    case (teamMembers <= 50):
                        return {
                            oneTime: 9.99,
                                monthly: 19.99,
                                annual: 219.00
                        }
                    default:
                        return {
                            oneTime: 19.99,
                                monthly: 34.99,
                                annual: 384.00
                        }
                }
            }
        <?php } ?>

        function delete_trip(id) {
            var valid = confirm('Are you sure? want to delete');
            if (valid) {
                $('<form action="" method="POST"></form>').append('<input name="group_del" value="' + id + '" />').appendTo('body').submit();
            } else {
                return false;
            }
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

            $('.uploaded_image').click(function() {
                $('#upload').click();
            })

            var $uploadCrop,
                tempFilename,
                rawImg,
                imageId;

            function readFile(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.upload-demo').addClass('ready');
                        //$('#btn-crop-image').click();
                        $('#cropImagePop').modal('show');
                        rawImg = e.target.result;

                        // $uploadCrop.croppie('bind', {
                        //     url: rawImg,
                        // }).then(function() {
                        //     console.log('jQuery bind complete');
                        //     //$('.cr-slider').attr({'min':0.1, 'max':0.1});
                        // });
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    alert("Sorry - you're browser doesn't support the FileReader API");
                }
            }

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'circle'
                },
                enableExif: true,
                showZoomer: true,
                enableResize: true,
                enableOrientation: true,
                mouseWheelZoom: 'ctrl',
            });

            $('#cropImagePop').on('shown.bs.modal', function() {
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            });

            $('#upload').on('change', function() {
                readFile(this);
            });

            $('#cropImageBtn').on('click', function(ev) {
                $uploadCrop.croppie('result', {
                    type: 'base64',
                    format: 'png'

                }).then(function(resp) {
                    $.ajax({
                        url: 'ajaxfiles/upload_profile.php',
                        method: 'POST',
                        data: {
                            image: resp,
                            useId: <?= $userdata['id'] ?>
                        },
                        success: function(data) {

                            $(".profile_picture_place").attr("src", data);
                            //$('#upload-demo').croppie('destroy');
                            $('#formId').trigger("reset");
                            $('#cropImagePop').modal('hide');
                            //$('.btn-close-modal').click();
                            toastr.success('Profile Picture Successfully Updated');
                        }
                    });
                });
            });
        });

        Stripe.setPublishableKey('<?= STRIPE_PUBLISHABLE_KEY; ?>');

        $(function() {
            var dataSet = 'request_id=' + "planiversity";
            initialEnableProcess(dataSet);
        });

        $('#upgrade-button').click(function() {
            $('#payment_fname').focus();
        });

        $('#coupon-reset').click(function() {

            var monthly_price_value;
            var annual_price_value;
            var one_time_price_value;
            var flag = $('#coupon_flag').val();


            if (flag == 1) {
                teamMembers = parseInt($('#userSlider').val());
                if (account_type == "individual") {
                    let totalValuePrice = updateIndividualPriceCalculation(teamMembers);

                    monthly_price_value = totalValuePrice.monthly;
                    annual_price_value = totalValuePrice.annual;
                    one_time_price_value = totalValuePrice.oneTime;

                } else {
                    let totalValuePrice = updatePriceCalculation(teamMembers);
                    monthly_price_value = totalValuePrice.toFixed(2);
                    annual_price_value = (totalValuePrice * 11).toFixed(2);
                }


                discountFormate("monthly", monthly_price_value, "");
                discountFormate("annual", annual_price_value, "");
                if (one_time_price_value) {
                    discountFormate("one-time", one_time_price_value, "");
                }

                $('#coupon_context').val(0);
                $('#coupon_plan_level').val(0);
                $('#coupon_breakdown').val(0);
                $('#coupon_flag').val(0);
                $('#coupon_id').val('');

                $(".page-alerts").css('display', 'none');
                $('#paypal-button-container').show();
                $('.payment_card').attr('disabled', false);

            }

        });

        let dynamic_plan_id = null;

        if (paypal.Buttons && typeof paypal.Buttons === 'function') {
            paypal.Buttons({
                env: 'production',
                locale: 'en_US',
                style: {
                    shape: 'rect',
                    color: 'blue',
                    size: "responsive",
                    label: 'subscribe',
                    tagline: false
                },
                createSubscription: function(data, actions) {
                    let dynamic_plan_id = 'P-0UN45499082368334L7LVQJA'; // Declare the variable outside the function

                    // Function to update the dynamic_plan_id based on the selected option
                    function updateDynamicPlanId() {
                        var teamMembers = parseInt(document.getElementById('userSlider').value);

                        console.log('teamMembers', teamMembers);

                        switch (true) {

                            case (teamMembers <= 10):
                                dynamic_plan_id = 'P-0UN45499082368334L7LVQJA';
                                break;
                            case (teamMembers <= 50):
                                dynamic_plan_id = 'P-8GW757883K8686000MSE677Q';
                                break;
                            case (teamMembers <= 150):
                                dynamic_plan_id = 'P-7C453104X8282612HMSE7BUY';
                                break;
                            default:
                                dynamic_plan_id = 'P-70L43251DL425864UMSE7CDI';

                        }

                        console.log('inside_dynamic_plan_id', dynamic_plan_id);
                    }

                    <?php if ($account_type != 'individual') { ?>

                        updateDynamicPlanId(); // Call the function initially to set the dynamic_plan_id

                        // Event listener for select box change event
                        document.getElementById('userSlider').addEventListener('change', function() {
                            updateDynamicPlanId();
                        });

                    <?php } ?>


                    return actions.subscription.create({
                        /* Creates the subscription */
                        plan_id: dynamic_plan_id,
                        custom_id: "<?php echo sha1($userdata['id']) ?>",
                    });
                },
                onApprove: function(data, actions) {
                    //alert(data.subscriptionID); // You can add optional success message for the subscriber here


                    if (data.subscriptionID) {

                        var payment_type = $('input[name="payment_type"]:checked').val();
                        var token_id = $('#token').val();
                        var teamMembers = parseInt(document.getElementById('userSlider').value);

                        var dataSet = 'subscription_id=' + data.subscriptionID + '&teamMembers=' + teamMembers + '&payment_type=' + payment_type + '&token_key=' + token_id + '&page_id=' + SITE;

                        $.ajax({
                            url: SITE + "ajaxfiles/payment/subscription_create.php",
                            type: "POST",
                            data: dataSet,
                            dataType: 'json',
                            success: function(response) {

                                $('.reset_value').val('');

                                swal({
                                    title: response.message,
                                    type: "success",
                                    timer: 2500,
                                    showConfirmButton: true,
                                    customClass: 'swal-height'

                                }, function() {
                                    window.open(SITE + "payment_confirmation?id=" + response.id_value + "&type=subscription", '_blank');
                                });
                                $('#coupon-reset').click();


                            },
                            error: function(jqXHR, textStatus, errorThrown) {

                                swal({

                                    title: "Payment Process Failed",
                                    type: "warning",
                                    timer: 2500,
                                    showConfirmButton: false,
                                    customClass: 'swal-height'

                                });

                            }


                        });




                    }



                },
                onCancel: function(data) {

                    console.log("onCancel");

                },
                onError: function(err) {
                    console.log("onError");
                    swal({

                        title: "Payment Process Failed",
                        type: "warning",
                        timer: 2500,
                        showConfirmButton: false,
                        customClass: 'swal-height'

                    });

                    $('#coupon-reset').click();

                }
            }).render('#paypal-button-container-subscribe'); // Renders the PayPal button
        }

        paypal.Button.render({
            env: 'production',
            locale: 'en_US',
            style: {
                shape: 'rect',
                color: 'black',
                size: "responsive",
                //layout: 'vertical',
                label: 'pay',
                //fundingicons: 'true',
                tagline: false
            },
            ui: {
                hasError: !1
            },

            // Or 'production'
            // Set up the payment:
            // 1. Add a payment callback
            payment: function(data, actions) {
                // 2. Make a request to your server

                var payment_type = $('input[name="payment_type"]:checked').val();
                var token_id = $('#token').val();
                var coupon_id = $('#coupon_id').val();
                var coupon_flag = $('#coupon_flag').val();
                var teamMembersCount = parseInt($('#userSlider').val());

                return actions.request.post(SITE + 'ajaxfiles/payment/instance_create.php', {
                        page_id: SITE,
                        payment_type: payment_type,
                        token_key: token_id,
                        coupon_id: coupon_id,
                        coupon_flag: coupon_flag,
                        teamMembers: teamMembersCount,
                    })

                    .then(function(res) {
                        // 3. Return res.id from the response
                        return res.paymentID;
                    });

            },
            // Execute the payment:
            // 1. Add an onAuthorize callback
            onAuthorize: function(data, actions) {
                // 2. Make a request to your server
                var payment_type = $('input[name="payment_type"]:checked').val();
                var token_id = $('#token').val();
                var coupon_id = $('#coupon_id').val();
                var coupon_flag = $('#coupon_flag').val();
                var coupon_context = $('#coupon_context').val();
                var teamMembersCount = parseInt($('#userSlider').val());

                return actions.request.post(SITE + 'ajaxfiles/payment/execute_payment.php', {
                        page_id: SITE,
                        payment_type: payment_type,
                        token_key: token_id,
                        coupon_id: coupon_id,
                        coupon_flag: coupon_flag,
                        teamMembers: teamMembersCount,
                        coupon_context: coupon_context,
                        paymentID: data.paymentID,
                        payerID: data.payerID
                    })
                    .then(function(res) {
                        // 3. Show the buyer a confirmation message.                        

                        if (res.message == 'successfully done') {


                            swal({
                                title: "Payment has been made successfully",
                                type: "success",
                                timer: 2500,
                                showConfirmButton: true,
                                customClass: 'swal-height'
                            }, function() {
                                if (ref) {
                                    window.open(SITE + "trip/name/" + ref, '_blank');
                                } else {
                                    window.open(SITE + "payment_confirmation?id=" + res.transition_id + "&type=" + res.type, '_blank');
                                }
                            });

                            toastr.success("Payment has been made successfully");

                            $('#coupon-reset').click();


                        } else {


                            swal({
                                title: "Payment Process Failed",
                                type: "warning",
                                timer: 2500,
                                showConfirmButton: false,
                                customClass: 'swal-height'
                            });

                            $('#coupon-reset').click();


                        }

                    });
            }
        }, '#paypal-button-container');


        $('.payment_type').change(function() {
            var value = $(this).val();
            let enable_payment_option = $('#enable_payment_option').val();
            $(' .plans_list li').removeClass("nav_active");
            $(this).closest('li').toggleClass("nav_active");
            if (value == "monthly" && enable_payment_option == 1) {
                $('#paypal-button-container').hide();
                $('#paypal-button-container-subscribe').show();
                $('#popupmess').html('I understand that Planiversity will charge me the monthly account price as advertised, and to cancel that option, I will have to manually deactivate the recurring option.');
            } else {
                $('#paypal-button-container').show();
                $('#paypal-button-container-subscribe').hide();
                $('#popupmess').html('I understand that this is a one-time-payment and that I will not be automatically charged again. If I wish to setup recurring monthly payments, I understand that I have to activate the recurring payment option');

            }

        });

        $("#payment_form").validate({
            //ignore: ':hidden:not(.validy)',
            rules: {

                payment_fname: {
                    required: true,
                },
                payment_lname: {
                    required: true,
                },
                payment_cardnumber: {
                    required: true,
                    minlength: 13,
                    maxlength: 19
                },
                payment_expmonth: {
                    required: true
                },
                payment_expyear: {
                    required: true
                },
                payment_cvc: {
                    required: true,
                    minlength: 2,
                    maxlength: 4
                }
            },
            messages: {

                payment_fname: {
                    required: 'Please type your first name'
                },
                payment_lname: {
                    required: 'Please type your last name'
                },
                payment_country: {
                    required: 'Please select country'
                },
                payment_address: {
                    required: 'Please type address'
                },
                payment_city: {
                    required: 'Please type city'
                },
                payment_state: {
                    required: 'Please type state'
                },
                payment_zipcode: {
                    required: 'Please type zipcode'
                },
                payment_cardnumber: {
                    required: 'Please type card number',
                    minlength: 'Invalid card number format',
                    maxlength: 'Invalid card number format',

                },
                payment_expmonth: {
                    required: 'Please type exp month'
                },
                payment_expyear: {
                    required: 'Please type exp year'
                },
                payment_cvc: {
                    required: 'Please type your cvc',
                    minlength: 'Invalid cvc format',
                    maxlength: 'Invalid cvc format',
                }
            },


            submitHandler: function(form) {

                var coupon_breakdown = $('#coupon_breakdown').val();

                if (coupon_breakdown == 1) {
                    couponBreakdownProcess();
                } else {
                    $('#payment_auth_confirm').modal('show');
                }

            }, // Do not change code below
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            }


        });


        function couponBreakdownProcess() {

            var form = document.getElementById('payment_form');

            $.ajax({
                url: SITE + "ajaxfiles/payment/breakdown_payment_process.php",
                type: "POST",
                data: $(form).serialize() + '&check=' + SITE + '&date=' + new Date(),
                dataType: 'json',
                success: function(response) {

                    $('#coupon-reset').click();
                    $('.reset_value').val('');
                    $('#payment_loading_screen').modal('hide');
                    $('#payment_process').css('cursor', 'pointer');
                    $('#payment_process').removeAttr('disabled');
                    $('#payment_auth_submit').css('cursor', 'pointer');
                    $('#payment_auth_submit').removeAttr('disabled');
                    $("#confirm").prop("checked", false);

                    swal({
                        title: response.message,
                        type: "success",
                        timer: 2500,
                        showConfirmButton: true,
                        customClass: 'swal-height'

                    }, function() {
                        if (ref) {
                            window.open(SITE + "trip/name/" + ref, '_blank');
                        } else {
                            window.open(SITE + "payment_confirmation?id=" + response.id + "&type=" + response.type, '_blank');
                        }

                    });


                    toastr.success(response.message);

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    var res = jqXHR.responseJSON;

                    $('#payment_loading_screen').modal('hide');

                    $('#payment_process').css('cursor', 'pointer');
                    $('#payment_process').removeAttr('disabled');

                    $('#payment_auth_submit').css('cursor', 'pointer');
                    $('#payment_auth_submit').removeAttr('disabled');

                    $("#confirm").prop("checked", false);

                    $('#coupon-reset').click();

                    toastr.error(res.message);


                }

            });
        }



        $("#payment_auth").validate({
            rules: {

                confirm: {
                    required: true,
                },

            },
            messages: {

                confirm: {
                    required: ''
                },

            },


            submitHandler: function(form) {


                $('#payment_process').css('cursor', 'wait');
                $('#payment_process').attr('disabled', true);

                $('#payment_auth_submit').css('cursor', 'wait');
                $('#payment_auth_submit').attr('disabled', true);

                $("#payment_auth_confirm").modal('hide');
                $('#payment_loading_screen').modal('show');

                Stripe.createToken({
                    number: $('#payment_cardnumber').val(),
                    cvc: $('#payment_cvc').val(),
                    exp_month: $('#payment_expmonth').val(),
                    exp_year: $('#payment_expyear').val()
                }, stripeResponseHandler);
                return false;


            }, // Do not change code below
            errorPlacement: function(error, element) {
                //error.insertAfter(element.parent());
            }


        });

        function stripeResponseHandler(status, response) {
            if (response.error) {
                //enable the submit button                

                $('#payment_process').css('cursor', 'pointer');
                $('#payment_process').removeAttr('disabled');

                $('#payment_auth_submit').css('cursor', 'pointer');
                $('#payment_auth_submit').removeAttr('disabled');

                $('#payment_loading_screen').modal('hide');

                $("#confirm").prop("checked", false);

                //display the errors on the form
                //$(".payment-errors").html(response.error.message);                

                swal({
                    title: response.error.message,
                    type: "warning",
                    //showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                });


            } else {
                stripeTokenHandler(response.id);
            }
        }


        function stripeTokenHandler(token) {

            var form = document.getElementById('payment_form');
            teamMembers = parseInt($('#userSlider').val());

            $.ajax({
                url: SITE + "ajaxfiles/payment/card_payment_process.php",
                type: "POST",
                data: $(form).serialize() + '&teamMembers=' + teamMembers + '&stripeToken=' + token + '&check=' + SITE + '&date=' + new Date(),
                dataType: 'json',
                success: function(response) {

                    $('#coupon-reset').click();
                    $('.reset_value').val('');
                    $('#payment_loading_screen').modal('hide');
                    $('#payment_process').css('cursor', 'pointer');
                    $('#payment_process').removeAttr('disabled');
                    $('#payment_auth_submit').css('cursor', 'pointer');
                    $('#payment_auth_submit').removeAttr('disabled');
                    $("#confirm").prop("checked", false);


                    swal({
                        title: response.message,
                        type: "success",
                        timer: 2500,
                        showConfirmButton: true,
                        customClass: 'swal-height'

                    }, function() {

                        if (ref) {
                            window.open(SITE + "trip/name/" + ref, '_blank');
                        } else {
                            if (response.type) {
                                window.open(SITE + "payment_confirmation?id=" + response.id_value + "&type=" + response.type, '_blank');
                            }
                        }
                    });


                    toastr.success(response.message);

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    var res = jqXHR.responseJSON;

                    $('#payment_loading_screen').modal('hide');

                    $('#payment_process').css('cursor', 'pointer');
                    $('#payment_process').removeAttr('disabled');

                    $('#payment_auth_submit').css('cursor', 'pointer');
                    $('#payment_auth_submit').removeAttr('disabled');

                    $("#confirm").prop("checked", false);

                    $('#coupon-reset').click();

                    toastr.error(res.message);


                }

            });



        }






        function notification(code, percent, text) {
            $('#notification').html('<strong>' + code + '</strong> ' + text + ' <strong>(' + percent + '% discount)</strong> ').slideDown().delay(2000).slideDown();
        }

        function percentCalculation(price, percent) {
            return (price - (price * percent / 100)).toFixed(2);
        }

        function discountFormate(action, old_price, discounted_price) {
            if (discounted_price == "") {
                var place = '$' + old_price;
            } else {
                var place = '<span class="old-price">$' + old_price + '</span><span class="new-price"> $' + discounted_price + '</span>';
            }
            $('#' + action + "-breakdown").html(place);
        }

        function couponCalculation(percent, plan, type) {
            let monthly_price;
            let annual_price;
            let one_time_price;
            let teamMembers = parseInt($('#userSlider').val());


            if (type == 'individual') {
                let totalValue = updateIndividualPriceCalculation(teamMembers);

                monthly_price = totalValue.monthly;
                annual_price = totalValue.annual;
                one_time_price = totalValue.oneTime;
            } else {
                let totalValuePrice = updatePriceCalculation(teamMembers);
                monthly_price = totalValuePrice.toFixed(2);
                annual_price = (totalValuePrice * 11).toFixed(2);
            }

            if (plan == 'monthly') {

                let monthly_percent_process = percentCalculation(monthly_price, percent);
                discountFormate("monthly", monthly_price, monthly_percent_process);

            } else if (plan == 'annual') {

                let annual_percent_process = percentCalculation(annual_price, percent);
                discountFormate("annual", annual_price, annual_percent_process);

            } else {

                let monthly_percent_process = percentCalculation(monthly_price, percent);
                let annual_percent_process = percentCalculation(annual_price, percent);
                discountFormate("monthly", monthly_price, monthly_percent_process);
                discountFormate("annual", annual_price, annual_percent_process);
                if (one_time_price) {
                    let one_time_price_process = percentCalculation(one_time_price, percent);
                    discountFormate("one-time", one_time_price, one_time_price_process);
                }
            }

        }


        $('#load_personal_data').change(function() {

            if (this.checked) {

                var dataSet = 'flag=' + 1;

                $.ajax({
                    url: SITE + "ajaxfiles/load_previous_data.php",
                    type: "POST",
                    data: dataSet,
                    dataType: "json",
                    success: function(response) {
                        setInputValue(response.data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error('No previously saved data found');
                    }

                });


            } else {
                $('.reset_value').val('');
            }
        });

        function setInputValue(obj) {

            if (obj) {
                $('#payment_fname').val(obj.fname);
                $('#payment_lname').val(obj.lname);
                $('#payment_country').val(obj.country);
                $('#payment_address').val(obj.address);
                $('#payment_city').val(obj.city);
                $('#payment_state').val(obj.state);
                $('#payment_zipcode').val(obj.zipcode);
            }

        }

        $('#coupon_submit').click(function() {

            var code = $('#coupon_code').val();
            var flag = $('#coupon_flag').val();

            if ((code == '') || (code == undefined)) {
                $('.coupon_response').html('Please type your coupon code');
                $("#coupon_code").focus();

            } else if ((code.length < 3)) {
                $('.coupon_response').html('The minimum coupon code length is three characters.');
                $("#coupon_code").focus();

            } else if ((flag == '1')) {

                $('.coupon_response').html('One coupon code is already applied');
                toastr.error("One coupon code is already applied");

            } else {
                $('.coupon_response').html('');

                $('#coupon_submit').css('cursor', 'wait');
                $('#coupon_submit').attr('disabled', true);

                $.ajax({
                    type: "POST",
                    url: SITE + "ajaxfiles/payment/coupon_process.php",
                    data: {
                        "code": code,
                        "check": SITE,
                        "date": new Date(),
                        "account_type": account_type
                    },
                    dataType: 'json',
                    success: function(response) {

                        $(".page-alerts").css('display', 'block');
                        $('.page-alert').slideDown();

                        notification(response.code, response.data.percent, "Coupon code applied");

                        if (response.data.breakdown == 1) {
                            $('.payment_card').attr('disabled', true);
                            $('#paypal-button-container').hide();
                        }

                        $('#coupon_code').val('');
                        $('#coupon_flag').val(1);
                        $('#coupon_percent').val(response.data.percent);
                        $('#coupon_context').val(response.data.context);
                        $('#coupon_plan_level').val(response.data.plan_level);
                        $('#coupon_breakdown').val(response.data.breakdown);
                        $('#coupon_id').val(response.data.id);
                        couponCalculation(response.data.percent, response.data.plan_level, account_type);
                        toastr.success("Coupon code applied ");


                        $('#coupon_submit').css('cursor', 'pointer');
                        $('#coupon_submit').removeAttr('disabled');

                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                        var res = jqXHR.responseJSON;

                        $('.coupon_response').html('<span class="code_place">' + res.code + '</span> ' + res.message);
                        toastr.error(res.message);

                        $('#coupon_submit').css('cursor', 'pointer');
                        $('#coupon_submit').removeAttr('disabled');

                    }

                });

            }
        });



        function paypalOptionProcess(enabled, account_type) {
            if ((enabled == 1) && (account_type != 'individual')) {

                $('#paypal-button-container').hide();
                $('#paypal-button-container-subscribe').show();
                $('#popupmess').html('I understand that Planiversity will charge me the monthly account price as advertised, and to cancel that option, I will have to manually deactivate the recurring option.');

            } else {

                $('#paypal-button-container').show();
                $('#paypal-button-container-subscribe').hide();
                $('#popupmess').html('I understand that this is a one-time-payment and that I will not be automatically charged again. If I wish to setup recurring monthly payments, I understand that I have to activate the recurring payment option');

            }
        }

        function initialEnableProcess(dataSet) {

            $('#enable_payment_option').css('cursor', 'wait');
            $('#enable_payment_option').attr('disabled', true);

            $.ajax({
                url: SITE + "ajaxfiles/load_automatic_payment.php",
                type: "GET",
                data: dataSet,
                dataType: "json",
                cache: false,
                success: function(response) {


                    //console.log('response', response);
                    if (response.data.recurring_payment == 1) {
                        $('#enable_payment_option').prop('checked', true);
                        $('#enable_payment_option').val('1');
                        paypalOptionProcess(1, account_type);
                    } else {
                        $('#enable_payment_option').val('0');
                        paypalOptionProcess(0, account_type);
                    }

                    $('#enable_payment_option').css('cursor', 'pointer');
                    $('#enable_payment_option').removeAttr('disabled');

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    // $(".loading_screen").hide();
                    // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");

                    $('#enable_payment_option').css('cursor', 'pointer');
                    $('#enable_payment_option').removeAttr('disabled');

                }

            });


        }


        function paypalOptionSelect(option_value) {
            var payment_type = $('input[name="payment_type"]:checked').val();

            console.log('payment_type', payment_type);
            console.log('option_value', option_value);

            if ((option_value == "1") && (payment_type == 'monthly')) {
                console.log('Inside Subscription');
                $('#paypal-button-container').hide();
                $('#paypal-button-container-subscribe').show();
                $('#popupmess').html('I understand that Planiversity will charge me the monthly account price as advertised, and to cancel that option, I will have to manually deactivate the recurring option.');
            } else {
                console.log('Inside Payment');
                $('#paypal-button-container').show();
                $('#paypal-button-container-subscribe').hide();
                $('#popupmess').html('I understand that this is a one-time-payment and that I will not be automatically charged again. If I wish to setup recurring monthly payments, I understand that I have to activate the recurring payment option');
            }
        }


        $('#enable_payment_option').change(function() {

            if ($(this).prop('checked')) {
                $(this).val('1');
            } else {
                $(this).val('0');
            }

            let hold = $(this).val();


            if (hold) {

                var dataSet = 'flag=' + hold;

                $(this).css('cursor', 'wait');
                $(this).attr('disabled', true);

                $.ajax({
                    url: SITE + "ajaxfiles/toggle_automatic_payment.php",
                    type: "POST",
                    data: dataSet,
                    dataType: "json",
                    success: function(response) {
                        //setInputValue(response.data);

                        paypalOptionSelect(hold);

                        $('#enable_payment_option').css('cursor', 'pointer');
                        $('#enable_payment_option').removeAttr('disabled');

                        toastr.success(response.message);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error('No previously saved data found');
                    }

                });


            } else {
                $('.reset_value').val('');
            }
        });
    </script>


</body>

</html>