<?php
include_once("config.ini.php");

include("class/class.Plan.php");
$plan = new Plan();

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'welcome';
    header("Location:" . SITE . "login");
}

include('include_doctype.php');

$msg = '';
if (isset($_FILES['fileUp'])) {
    $_tmp = 'ajaxfiles/profile/';
    if (empty($_FILES['fileUp']['name'])) {
        $msg = 'Please choose a file!';
    } else {
        $allowed = array('jpg', 'jpeg', 'gif', 'png');
        $file_name = $_FILES['fileUp']['name'];
        $file_extn = strtolower(end(explode('.', $file_name)));
        $file_temp = $_FILES['fileUp']['tmp_name'];

        if (in_array($file_extn, $allowed)) {
            if (move_uploaded_file($file_temp, $_tmp . $file_name)) { // save in DB
                $query = "UPDATE users SET picture = ? WHERE id = ?";
                $stmtnew = $dbh->prepare($query);
                $stmtnew->bindValue(1, $file_name, PDO::PARAM_STR);
                $stmtnew->bindValue(2, $userdata['id'], PDO::PARAM_INT);
                $stmtnew->execute();
                $userdata['picture'] = $file_name;
            } else
                $msg = 'A system error has been encountered. Please try again.';
        } else {
            $msg = 'Incorrect file type. Allowed: ' . implode(', ', $allowed);
        }
    }
}

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

// sync google calendar
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">

    <?php include('dashboard/include/css.php'); ?>

    <script src="<?= SITE ?>dashboard/js/jquery-3.6.0.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

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

    <script>
        var schoolNumber = 1;
        var event_list = Array(
            <?php
            // Trip
            $stmt = $dbh->prepare("SELECT tp.* FROM trips as tp WHERE pdf_generated=1 and tp.id_user=?");
            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
            $tmp = $stmt->execute();
            $aux = '';
            if ($tmp && $stmt->rowCount() > 0) {
                $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($timelines as $timeline) {
                    $employee_ = '';
                    if (!empty($timeline->id_employee)) {
                        $employee_ = '\n' . get_employee($timeline->id_employee);
                    }

                    $triptitle = trim($timeline->title);
                    $triptitle = str_replace('&#39;', '_', $triptitle);
                    $triptitle = str_replace(' ', '_', $triptitle);
                    $pdfname = $triptitle . '-' . $timeline->id_trip . '-' . $userdata['id'];
                    if ($timeline->location_datel && $timeline->location_datel != '0000-00-00')
                        $date_start = $timeline->location_datel;
                    else
                        $date_start = $timeline->date_created;

                    if ($timeline->location_dater && $timeline->location_dater != '0000-00-00')
                        $date_end = $timeline->location_dater . ' 20:25:17';
                    else
                        $date_end = $timeline->date_created;

                    $aux .= "{ 
                                title: '" . $triptitle . "', 
                                start: '" . date('Y-m-d', strtotime($date_start)) . "',
                                end: '" . date('Y-m-d', strtotime($date_end) + 3600) . "',
                                url: '" . SITE . "pdf/" . $pdfname . ".pdf',
                                school: 1,
                                color: '#c5e3fb'
                            },";
                }
                echo $aux;
            }
            // Events
            $stmt = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='event'");
            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
            $tmp = $stmt->execute();
            $aux = '';
            if ($tmp && $stmt->rowCount() > 0) {
                $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($timelines as $timeline) {
                    $from = $timeline->event_date_from ? DateTime::createFromFormat('m-d-Y', $timeline->event_date_from) : "";
                    $to = $timeline->event_date_to ? DateTime::createFromFormat('m-d-Y', $timeline->event_date_to) : "";
                    if ($from && $to) {
                        $aux .= "{ 
                                    title: '" . $timeline->event_title . "',
                                    start: '" . $from->format("Y-m-d") . "',
                                    end: '" . date('Y-m-d', strtotime($to->format("Y-m-d")) + 3600 * 24) . "',
                                    color: '#E7912A',
                                    school: 2,
                                },";
                    }
                }
                echo $aux;
            }
            // Meetings
            $stmt = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='meeting'");
            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
            $tmp = $stmt->execute();
            $aux = '';
            if ($tmp && $stmt->rowCount() > 0) {
                $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
                foreach ($timelines as $timeline) {
                    $from = $timeline->event_date_from ? DateTime::createFromFormat('m-d-Y', $timeline->event_date_from) : "";
                    $to = $timeline->event_date_to ? DateTime::createFromFormat('m-d-Y', $timeline->event_date_to) : "";
                    if ($from && $to) {
                        $aux .= "{ 
                                    title: '" . $timeline->event_title . "',
                                    start: '" . $from->format("Y-m-d") . "',
                                    end: '" . date('Y-m-d', strtotime($to->format("Y-m-d")) + 3600 * 24) . "',
                                    color: '#f6e7d4',
                                    school: 3,
                                },";
                    }
                }
                echo $aux;
            }

            ?>
        );
    </script>

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
                                <ul class="list-unstyled mobile-small">
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
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right ipad-show">
                                                    <ul class="list-unstyled ipad-ul">
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
                                            <div class="heade_user_img" id="uploaded_image">
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
                                                <div class="user_header">
                                                    <div class="row align-self-center">
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-10">
                                                            <div class="user_header_left_item">
                                                                <div class="user_header_list">
                                                                    <div class="user_header_img only-mob">
                                                                        <img src="<?= $img ?>">
                                                                    </div>
                                                                    <div class="user_header_text">
                                                                        <h4><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?> <span class="business_user"><?= $userdata['account_type']; ?> USER</span></h4>
                                                                        <h6>Customer#: <?= strtoupper($userdata['customer_number']) ?></h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-2">
                                                            <div class="start_a_plan_btn">
                                                                <a href="<?= SITE; ?>trip/how-are-you-traveling"><span>Start a new plan</span>
                                                                    <div class="plus-icon"><img src="<?= SITE; ?>/dashboard/images/plus-circle.svg"></div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <section class="upcoming_events_sec">
                                                    <div class="row">
                                                        <div class="col-xl-4">
                                                            <div class="upcoming_sec">
                                                                <h3><i class="fa fa-calendar-check-o" aria-hidden="true"></i>&nbsp;Upcoming Events</h3>

                                                                <div class="event_items_list">
                                                                    <?php if ($tmp && $stmt->rowCount() > 0) {
                                                                        $trips = $stmt->fetchAll(PDO::FETCH_OBJ);

                                                                        foreach ($trips as $tripl) {
                                                                            $aux = '';
                                                                            $employee_ = "";
                                                                            if (!empty($tripl->id_employee)) {
                                                                                $employee_ = ' ' . get_employee($tripl->id_employee);
                                                                            }

                                                                            $triptitle = trim($tripl->title);
                                                                            $triptitle = str_replace('&#39;', '_', $triptitle);
                                                                            $triptitle = str_replace(' ', '_', $triptitle);
                                                                            $pdfname = $triptitle . '-' . $tripl->id_trip . '-' . $userdata['id'];

                                                                            if ($tripl->pdf_generated) {
                                                                                $titledata = $titledata2 = $tripl->title . ' ' . $employee_;
                                                                            } else {
                                                                                $titledata = $titledata2 = '** incomplete trip plan **';
                                                                            }
                                                                            if (strlen($titledata) >= 80)
                                                                                $titledata = substr($titledata, 0, 80) . '...';

                                                                            if ($tripl->pdf_generated) {
                                                                                $aux .= '<a target="_blank" href="' . SITE . 'pdf/' . $pdfname . '.pdf" style="text-decoration: none" class="text-dark">' . ucwords($titledata) . '</a>';
                                                                            } else {
                                                                                $aux .= '<a href="' . SITE . 'trip/create-timeline/' . $tripl->id_trip . '" style="text-decoration: none" class="text-dark">' . ucwords($titledata) . '</a>';
                                                                            } ?>
                                                                            <div class="events_items_box">
                                                                                <div class="event_items_left">
                                                                                    <h6><?= date('M d h:i A', strtotime($tripl->location_datel . ' ' . $tripl->location_datel_deptime)) ?> - <?= date('h:i A', strtotime($tripl->location_datel_arrtime)) ?></h6>
                                                                                    <h2><?= $aux ?></h2>
                                                                                    <p></p>
                                                                                    <span class="trip_text">Trip</span>
                                                                                </div>
                                                                                <div class="event_items_right">
                                                                                    <a href="javascript:avoid(0)" onclick="delete_trip(<?= $tripl->id_trip ?>)">
                                                                                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                    <?php }
                                                                    } ?>
                                                                    <?php if ($tmp_event && $stmt_event->rowCount() > 0) {
                                                                        $events = $stmt_event->fetchAll(PDO::FETCH_OBJ);

                                                                        foreach ($events as $eventl) {
                                                                            $eventtitle = trim($eventl->event_title);

                                                                            if (strlen($eventtitle) >= 80)
                                                                                $eventtitle = substr($eventtitle, 0, 80) . '...';
                                                                    ?>
                                                                            <div class="events_items_box">
                                                                                <div class="event_items_left">
                                                                                    <h6>
                                                                                        <?php
                                                                                        $date = explode('-', $eventl->event_date);
                                                                                        $date = $date[2] . '-' . $date[0] . '-' . $date[1] . ' ' . $eventl->event_time_from;
                                                                                        ?>
                                                                                        <?= date('M d h:i A', strtotime($date)) ?> - <?= date('h:i A', strtotime($eventl->event_time_to)) ?>
                                                                                    </h6>
                                                                                    <h2><?= $eventtitle ?></h2>
                                                                                    <p></p>
                                                                                    <span class="trip_events">Event</span>
                                                                                </div>
                                                                                <div class="event_items_right">
                                                                                    <a href="javascript:avoid(0)" onclick="delete_trip(<?= $eventl->id ?>)">
                                                                                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                    <?php }
                                                                    } ?>
                                                                    <?php if ($tmp_meeting && $stmt_meeting->rowCount() > 0) {
                                                                        $meetings = $stmt_meeting->fetchAll(PDO::FETCH_OBJ);

                                                                        foreach ($meetings as $meetingl) {
                                                                            $eventtitle = trim($meetingl->event_title);

                                                                            if (strlen($eventtitle) >= 80)
                                                                                $eventtitle = substr($eventtitle, 0, 80) . '...';
                                                                    ?>
                                                                            <div class="events_items_box">
                                                                                <div class="event_items_left">
                                                                                    <h6>
                                                                                        <?php
                                                                                        $date = explode('-', $meetingl->event_date);
                                                                                        $date = $date[2] . '-' . $date[0] . '-' . $date[1] . ' ' . $meetingl->event_time_from;
                                                                                        ?>
                                                                                        <?= date('M d h:i A', strtotime($date)) ?> - <?= date('h:i A', strtotime($meetingl->event_time_to)) ?>
                                                                                    </h6>
                                                                                    <h2><?= $eventtitle ?></h2>
                                                                                    <p></p>
                                                                                    <span class="meeting_text">Meeting</span>
                                                                                </div>
                                                                                <div class="event_items_right">
                                                                                    <a href="javascript:avoid(0)" onclick="delete_trip(<?= $meetingl->id ?>)">
                                                                                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                    <?php }
                                                                    } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <div class="calendar_item_sec">
                                                                <div class="row">
                                                                    <div class="col-xl-6">
                                                                        <div class="calendar_heading">
                                                                            <h2><i class="fa fa-calendar-o" aria-hidden="true"></i>&nbsp;Calendar</h2>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-6">
                                                                        <div class="event_trip_meeting_all">
                                                                            <ul class="list-unstyled">
                                                                                <li><a href="javascript:void(0)" class="events">events</a></li>
                                                                                <li><a href="javascript:void(0)" class="trips">trips</a></li>
                                                                                <?php if ($userdata['account_type'] != 'Individual') { ?>
                                                                                    <li><a href="javascript:void(0)" class="meetings">meetings</a></li>
                                                                                <?php } ?>
                                                                                <li><a href="javascript:void(0)" class="all">all</a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="calendar_month_items">
                                                                    <div class="row">
                                                                        <div class="col-xl-12">
                                                                            <div id="calendar"></div>
                                                                        </div>
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
                            <!-- <div class="currnet-planboxt show-mob">
                                <div class="current-plan">
                                    <div class="form-row justify-content-center pad-20">
                                        <div class="col-lg-8 col-7">
                                            <h4> Current Plan </h4>
                                            <h6>Expires 25/08/2022</h6>
                                        </div>
                                        <div class="col-lg-4 col-5 align-self-center">
                                            <div class="personal-userbtn"> Personal User </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- <div class="mobile-fixed-footer show-mob">
        <div class="form-row">
            <div class="col-lg-3 col-3">
                <div class="footer-box active">
                    <a href="#">
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

    <form id="formId" name="formId" action="" method="post" enctype="multipart/form-data" class="d-none">
        <input type="file" id="upload" value="Choose a file" accept="image/*" style="display: none;">
    </form>

    <button type="button" id="btn-crop-model" data-toggle="modal" data-target="#cropImagePopl">
        Launch demo modal
    </button>

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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

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

            $('#uploaded_image').click(function() {
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
                        $('#btn-crop-model').click();
                        rawImg = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    swal("Sorry - you're browser doesn't support the FileReader API");
                }
            }

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'circle'
                },
                enforceBoundary: false,
                enableExif: true
            });

            $('#cropImagePop').on('shown.bs.modal', function() {
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            });

            $('#upload').on('change', function() {
                console.log("change")
                readFile(this);
            });

            $('#cropImageBtn').on('click', function(ev) {
                $uploadCrop.croppie('result', {
                    type: 'base64',
                    format: 'jpeg'

                }).then(function(resp) {
                    $.ajax({
                        url: SITE + 'ajaxfiles/upload_profile.php',
                        method: 'POST',
                        data: {
                            image: resp,
                            useId: <?= $userdata['id'] ?>
                        },
                        success: function(data) {
                            $('#cropImagePop').modal('hide');
                            location.reload()
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>