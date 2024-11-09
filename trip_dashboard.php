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

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <script src="<?= SITE ?>dashboard/js/jquery-3.6.0.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

    <script>
        var SITE = '<?= 'https://' . $_SERVER['HTTP_HOST'] . '/'; ?>'
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


    <script>
        var schoolNumber = 1;
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

        #upload-demo {
            margin: auto;
        }

        .search_btn i {
            position: relative;
            bottom: 8px;
        }
        a.file_download {
            cursor: pointer;
            padding: 15px 10px !important;
            width: 120px !important;
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

$stmt = $dbh->prepare("SELECT * FROM trips WHERE id_user=? AND DATE(NOW()) >= DATE(location_datel) ORDER BY `id_trip` DESC");
$stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$tmp = $stmt->execute();

$stmt_event = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='event' AND DATE(NOW()) >= DATE(event_date_to)");
$stmt_event->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$tmp_event = $stmt_event->execute();

$stmt_meeting = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='meeting' AND DATE(NOW()) >= DATE(event_date_to)");
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
                                        <?php
                                        $page_index = "trip-dashboard";
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



                                                <div class="dashboard_trips_sec">
                                                    <div class="trips_tabpanel_head_search">
                                                        <div class="trips_tabpanel_head_item">
                                                            <ul class="nav">
                                                                <li class="tab-search">
                                                                    <h4 class="active" onclick="tabProcess.call(this,event,'tab-1')">Search by Keyword</h4>
                                                                </li>
                                                                <li class="tab-search">
                                                                    <h4 onclick="tabProcess.call(this,event,'tab-2')">Search by Name</h4>
                                                                </li>
                                                            </ul>
                                                            <div class="trips_search_item">
                                                                <form>
                                                                    <div class="row tab-place active" id="tab-1">
                                                                        <div class="col-xl-11 col-10">
                                                                            <input type="text" id="keyword-input" class="form-control" placeholder="Search by Keyword">
                                                                        </div>
                                                                        <div class="col-xl-1 col-2 padding_right">
                                                                            <div class="search_btn">
                                                                                <button type="button" id="keyword-button" class="btn_btn"><i class="bi bi-search"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row tab-place" id="tab-2">
                                                                        <div class="col-xl-11 col-10">
                                                                            <input type="text" id="name-input" class="form-control" placeholder="Search by Name">
                                                                        </div>
                                                                        <div class="col-xl-1 col-2 padding_right">
                                                                            <div class="search_btn">
                                                                                <button type="button" id="name-button" class="btn_btn"><i class="bi bi-search"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if ($userdata['account_type'] == 'Individual') {
                                                        $trip_reason = "Personal";
                                                    } else {
                                                        $trip_reason = "Business";
                                                    }
                                                    ?>


                                                    <section class="trips_content_sec trips_content_desktop_vew">
                                                        <div class="row">
                                                            <div class="col-xl-6 col-lg-6 col-12">


                                                                <div class="trips_conten_left_items">
                                                                    <div class="trips_left_items_scrolling">
                                                                        <div class="tab">
                                                                            <ul class="list-unstyled trip_event">



                                                                                <?php if ($tmp_meeting && $stmt_meeting->rowCount() > 0) {
                                                                                    $meetings = $stmt_meeting->fetchAll(PDO::FETCH_OBJ);

                                                                                    foreach ($meetings as $meetingl) {
                                                                                        $eventtitle = trim($meetingl->event_title);

                                                                                        if (strlen($eventtitle) >= 80)
                                                                                            $eventtitle = substr($eventtitle, 0, 80) . '...';



                                                                                        $date = explode('-', $meetingl->event_date);
                                                                                        $date = $date[2] . '-' . $date[0] . '-' . $date[1] . ' ' . $meetingl->event_time_from;


                                                                                ?>


                                                                                        <li class="nav-item">
                                                                                            <div class="tablinks" onclick="eventProcess.call(this,event,'meeting',<?= $meetingl->id ?>)" data-type="meeting" data-id="<?= $meetingl->id ?>">
                                                                                                <div class="events_items_box_trips">
                                                                                                    <div class="event_items_left">
                                                                                                        <h6><?= date('M d h:i A', strtotime($date)) ?> - <?= date('h:i A', strtotime($meetingl->event_time_to)) ?></h6>
                                                                                                        <h2><?= $eventtitle ?></h2>
                                                                                                        <p><?= $trip_reason ?> meeting</p>
                                                                                                        <span class="meet_btn yellow_color">MEETING</span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>



                                                                                <?php }
                                                                                } ?>



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
                                                                                        $from_address = explode(" ", $tripl->location_from);
                                                                                        $to_address = explode(" ", $tripl->location_to);

                                                                                        //var_dump($from_address[0]);



                                                                                        if ($tripl->pdf_generated) {
                                                                                            $titledata = $titledata2 = $tripl->title . ' ' . $employee_;
                                                                                        } else {
                                                                                            $titledata = $titledata2 = $from_address[0] . ' to ' . $to_address[0] . ' (incomplete)';
                                                                                        }
                                                                                        if (strlen($titledata) >= 80)
                                                                                            $titledata = substr($titledata, 0, 80) . '...';

                                                                                        if ($tripl->pdf_generated) {
                                                                                            $aux .= '<a target="_blank" href="' . SITE . 'pdf/' . $pdfname . '.pdf" style="text-decoration: none" class="text-dark">' . ucwords($titledata) . '</a>';
                                                                                        } else {
                                                                                            $aux .= '<a href="' . SITE . 'trip/create-timeline/' . $tripl->id_trip . '" style="text-decoration: none" class="text-dark">' . ucwords($titledata) . '</a>';
                                                                                        } ?>


                                                                                        <li class="nav-item">
                                                                                            <div class="tablinks" onclick="eventProcess.call(this,event,'trip',<?= $tripl->id_trip ?>)" data-type="trip" data-id="<?= $tripl->id_trip ?>">
                                                                                                <div class="events_items_box_trips">
                                                                                                    <div class="event_items_left">
                                                                                                        <h6><?= date('M d h:i A', strtotime($tripl->location_datel . ' ' . $tripl->location_datel_deptime)) ?> - <?= date('h:i A', strtotime($tripl->location_datel_arrtime)) ?></h6>
                                                                                                        <h2><?= $titledata ?></h2>
                                                                                                        <p><?= $trip_reason ?> trip</p>
                                                                                                        <span class="meet_btn light_bkue">TRIP</span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>

                                                                                <?php }
                                                                                } ?>


                                                                                <?php if ($tmp_event && $stmt_event->rowCount() > 0) {
                                                                                    $events = $stmt_event->fetchAll(PDO::FETCH_OBJ);

                                                                                    foreach ($events as $eventl) {
                                                                                        $eventtitle = trim($eventl->event_title);

                                                                                        if (strlen($eventtitle) >= 80)
                                                                                            $eventtitle = substr($eventtitle, 0, 80) . '...';

                                                                                        $date = explode('-', $eventl->event_date);
                                                                                        $date = $date[2] . '-' . $date[0] . '-' . $date[1] . ' ' . $eventl->event_time_from;

                                                                                ?>



                                                                                        <li class="nav-item">
                                                                                            <div class="tablinks" onclick="eventProcess.call(this,event,'event',<?= $eventl->id ?>)" data-type="event" data-id="<?= $eventl->id ?>">
                                                                                                <div class="events_items_box_trips">
                                                                                                    <div class="event_items_left">
                                                                                                        <h6><?= date('M d h:i A', strtotime($date)) ?> - <?= date('h:i A', strtotime($eventl->event_time_to)) ?></h6>
                                                                                                        <h2><?= $eventtitle ?></h2>
                                                                                                        <p><?= $trip_reason ?> event</p>
                                                                                                        <span class="meet_btn light_green">EVENT</span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </li>


                                                                                <?php }
                                                                                } ?>


                                                                                <li id="not-found" style="display: none;">
                                                                                    <div class="tablinks not-found-body">
                                                                                        <div class="events_items_box_trips ">
                                                                                            <div class="event_items_left">
                                                                                                <h2 align="center"> Nothing is found <img src="<?= SITE ?>images/pdf_icon1.png"></h2>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>



                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="col-xl-6 col-lg-6 col-12">
                                                                <div class="trips_meeting_righ_box_sec" id="event_placeholder">
                                                                    <div class="tab-content">

                                                                        <div id="event_content">

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
                    <button type="button" id="cropImageBtn" class="btn btn-primary crop_submit_button">Save Photo</button>
                    <button type="button" class="btn btn-danger btn-close-modal" data-dismiss="modal">Cancel</button>
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
    <script src="<?= SITE ?>dashboard/js/jquery-ui.js"></script>
    <script src="<?php echo SITE; ?>js/trip_dashboard_next.js?v=212"></script>



    <script>
    
        $(document).on('click', '.file_download', function(event) {

            var filePath = $(this).data("file-path");

            $.ajax({
                url: "<?= SITE . 'pdf/' ?>" + filePath,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(data);
                    a.href = url;
                    a.download = filePath;
                    document.body.append(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                }
            });


        });        

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


                    $('.crop_submit_button').css('cursor', 'wait');
                    $('.crop_submit_button').attr('disabled', true);


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

                            $('.crop_submit_button').css('cursor', 'pointer');
                            $('.crop_submit_button').removeAttr('disabled');

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