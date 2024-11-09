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

if ($plan->check_plan($userdata['id'])) { // if you have a plan export PDF
    if (isset($_GET['idtrip']) && !empty($_GET['idtrip']))
        header("Location:" . SITE . "trip/pdf/" . $_GET['idtrip']);
}

if (!($_GET['id']) && !($_GET['type'])) {
    header("Location:" . SITE . "billing");
}

include('include_doctype.php');

$msg = '';


if (isset($_POST['group_del']) && !empty($_POST['group_del'])) {
    $query = "DELETE FROM `trips` WHERE `trips`.`id_trip` in (" . $_POST['group_del'] . ")";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
    $tmp = $stmt->execute();
}

$metric = '';
$imperial = '';
if (isset($_POST['scale'])) {
    $scale = $_POST['scale'];
    $query = "UPDATE `users` SET `scale` = '" . $scale . "' WHERE `users`.`id` = " . $userdata['id'] . ";";
    $stmtnew = $dbh->prepare($query);
    $stmtnew->execute();
    if ($_POST['scale'] == 'metric') $metric = 'checked="checked"';
    if ($_POST['scale'] == 'imperial') $imperial = 'checked="checked"';
} else {
    if ($userdata['scale'] == 'metric')
        $metric = 'checked="checked"';
    if ($userdata['scale'] == 'imperial')
        $imperial = 'checked="checked"';
}

// Sync google calendar
$gcalendar = '';
$calendar_check = 1;
if (isset($_POST['ggcaltmp'])) {
    $check = ($_POST['ggcal']) ? 1 : 0;
    $timezone_offset_minutes = $_POST['ggcaltimezone'];
    $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
    $query = "UPDATE `users` SET `sync_googlecalendar` = '" . $check . "', `timezone` = '" . $timezone_name . "'  WHERE `users`.`id` = " . $userdata['id'] . ";";
    $stmtnew = $dbh->prepare($query);
    $stmtnew->execute();
    if ($check) {
        $calendar_check = 2;
        $gcalendar = 'checked="checked"';
    }
} else {
    if ($userdata['sync_googlecalendar']) {
        $calendar_check = 2;
        $gcalendar = 'checked="checked"';
    }
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
    <title>Planiversity | Consolidated Travel Information Management</title>
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
    </script>


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

        .payment_info div {
            display: inline-block;
        }

        section.billing_page_content_sec.payment_content {
            min-height: calc(100vh - 70px);
        }

        .payment_info .content {
            margin-left: 15px;
        }

        .payment-images img {
            width: 110px;
            vertical-align: bottom;
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



                                                <section class="billing_page_content_sec payment_content">
                                                    <div class="row">

                                                        <div class="col-xl-12 col-12">

                                                            <div class="billing_content_left_item">

                                                                <div class="billing_content_heading">
                                                                    <h2>Payment Information</h2>
                                                                </div>

                                                                <div class="payment_info">
                                                                    <div class="payment-images">
                                                                        <img src="<?= SITE ?>assets/images/insurance.png" class="img-responsive">
                                                                    </div>
                                                                    <div class="content">
                                                                        <h2>Thank you for your <?= $_GET['type']; ?></h2>
                                                                        <h5>Transaction id : <?= $_GET['id']; ?></h5>
                                                                        <h6>Check the billing section for a record of your payment.</h6>
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
                                    I authorize Planiversity, LLC to bill this account monthly. I also understand that any requests for changes to billing must be submitted to Planiversity customer service.
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


    <!-- <script src="<?php echo SITE; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/popper.min.js"></script> -->
    <!-- <script type="text/javascript" src="<?= SITE ?>dashboard/js/main.js"></script> -->
    <!-- <script src="<?= SITE ?>dashboard/js/jquery-ui.js"></script> -->

    <?php include('new_backend_script.php'); ?>


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
                        url: SITE + 'ajaxfiles/upload_profile.php',
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

                            //console.log('data',data);

                            //location.reload();
                        }
                    });
                });
            });
        });
    </script>


</body>

</html>