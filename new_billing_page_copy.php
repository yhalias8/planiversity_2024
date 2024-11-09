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
        $monthly_price = 9.99;
        $annual_price = 109.99;
        $expire_label = "FREE";
    } else {
        $account_type = "business";
        $plan_type = "Business Account";
        $plan_id = PAYPAL_BUSINESS_PLAN_ID;
        $plan_hold = "your business billing plan";
        $user_payment_status = $plan->check_plan($userdata['id']);
        $credit = $plan->get_total_credit(40);
        $user_active_plan = $plan->get_current_plan($userdata['id']);
        $monthly_price = 39.99;
        $annual_price = 449.99;
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

        .saved {
            width: 20px;
            height: 20px;
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
                                                                                                    <h4>Payment  <?= $i ?> - <?= $plan_type ?></h4>
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



                                                                </div>



                                                            </div>

                                                            <div class="col-xl-7 col-12">
                                                                <div class="renew_select_your_personal_sec">

                                                                    <div class="billing_content_heading">
                                                                        <h6>&nbsp;</h6>

                                                                        <h2><span>Renew or Select</span> <?php echo $plan_hold ?> </h2>
                                                                    </div>




                                                                    <div class="monthly_plan_radio">
                                                                        <ul class="list-unstyled plans_list">
                                                                            
                                                                            <?php
                                                                            $checked_item = "checked=checked";
                                                                            if ($account_type == 'individual') {
                                                                                $checked_item = "null";
                                                                            ?>
                                                                                <li class="nav_active">

                                                                                    <label class="redio_button_desin"><span>One Time Use Only</span>
                                                                                        Only <p id="monthly-breakdown" class="price-breakdown"> $3.99 </p>
                                                                                        <input class="payment_type" type="radio" checked="checked" name="payment_type" value="one_time">
                                                                                        <span class="checkmark"></span>
                                                                                    </label>

                                                                                </li>

                                                                            <?php }

                                                                            ?>
                                                                            
                                                                            <li class="nav_active">

                                                                                <label class=" redio_button_desin"><span>30 Days Unlimited Use</span>
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
                                                                                            <div class="form-group radio-form-group">
                                                                                                <div class="checkbox form-check-inline">
                                                                                                    <input type="checkbox" id="saved" value="1" name="saved_agree" class="saved">
                                                                                                    <label for="saved"></label>
                                                                                                </div>
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
                            <label for="confirm">
                                <input id="confirm" type="checkbox" name="confirm">
                                <p id="popupmess" class="mod-p">
                                    I authorize Planiversity, LLC to charge me the agreed to amount for the plan I have selected. I understand that this charge will not automatically occur monthly, and that to extend my service, I will need to initiate the purchase again once my plan expires.
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
    
    <?php
    include('dashboard/include/mobile-message.php');
    ?>


    <script src="<?php echo SITE; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/popper.min.js"></script>
    <script src="<?= SITE ?>dashboard/js/main.js"></script>
    <!-- <script src="<?= SITE ?>dashboard/js/jquery-ui.js"></script> -->

    <?php include('new_backend_script.php'); ?>
    <script src="https://js.stripe.com/v1/"></script>


    <script>
        
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

        $('#upgrade-button').click(function() {
            $('#payment_fname').focus();
        });

        $('#coupon-reset').click(function() {

            var flag = $('#coupon_flag').val();

            if (flag == 1) {

                var monthly_price = <?= $monthly_price; ?>;
                var annual_price = <?= $annual_price; ?>;

                discountFormate("monthly", monthly_price, "");
                discountFormate("annual", annual_price, "");

                $('#coupon_context').val(0);
                $('#coupon_breakdown').val(0);
                $('#coupon_flag').val(0);
                $('#coupon_id').val('');

                $(".page-alerts").css('display', 'none');
                $('#paypal-button-container').show();
                $('.payment_card').attr('disabled', false);                

            }

        });


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

                return actions.request.post(SITE + 'ajaxfiles/payment/instance_create.php', {
                        page_id: SITE,
                        payment_type: payment_type,
                        token_key: token_id,
                        coupon_id: coupon_id,
                        coupon_flag: coupon_flag,
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

                return actions.request.post(SITE + 'ajaxfiles/payment/execute_payment.php', {
                        page_id: SITE,
                        payment_type: payment_type,
                        token_key: token_id,
                        coupon_id: coupon_id,
                        coupon_flag: coupon_flag,
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
            $(' .plans_list li').removeClass("nav_active");
            $(this).closest('li').toggleClass("nav_active");
            if (value == "monthly") {
                $('#popupmess').html('I authorize Planiversity, LLC to charge me the agreed to amount for the plan I have selected. I understand that this charge will not automatically occur monthly, and that to extend my service, I will need to initiate the purchase again once my plan expires.');
            } else {
                $('#popupmess').html('I authorize Planiversity, LLC to charge me the agreed to amount for the plan I have selected. I understand that this charge will not automatically occur monthly, and that to extend my service, I will need to initiate the purchase again once my plan expires.');
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
                // payment_country: {
                //     required: true,
                // },
                // payment_address: {
                //     required: true
                // },
                // payment_city: {
                //     required: true
                // },
                // payment_state: {
                //     required: true
                // },
                // payment_zipcode: {
                //     required: true
                // },
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
                    $("#payment_form").trigger("reset");
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

            $.ajax({
                url: SITE + "ajaxfiles/payment/card_payment_process.php",
                type: "POST",
                data: $(form).serialize() + '&stripeToken=' + token + '&check=' + SITE + '&date=' + new Date(),
                dataType: 'json',
                success: function(response) {

                    $('#coupon-reset').click();
                    $("#payment_form").trigger("reset");
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

        function couponCalculation(percent, plan) {
            var monthly_price = <?= $monthly_price; ?>;
            var annual_price = <?= $annual_price; ?>;

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
                        "account_type": "<?= $account_type ?>"
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
                        $('#coupon_context').val(response.data.context);
                        $('#coupon_breakdown').val(response.data.breakdown);
                        $('#coupon_id').val(response.data.id);
                        couponCalculation(response.data.percent, response.data.plan_level);
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
    </script>


</body>

</html>