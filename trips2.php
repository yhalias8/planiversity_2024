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
                                                <h6>james alford</h6>
                                            </div>
                                        </div>
                                        <div class="header_users widget-content-right header-user-info ml-3">
                                            <div class="heade_user_img">
                                                <img src="<?php echo SITE; ?>/assets/billing2/images/users.png">
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
                                    <!--<div class="app-header__menu">
                                 <span>
                                 <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                                 <span class="btn-icon-wrapper">
                                 <i class="fa fa-ellipsis-v fa-w-6"></i>
                                 </span>
                                 </button>
                                 </span>
                                 </div>-->
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
                                                                    <div class="user_header_img">
                                                                        <img src="<?php echo SITE; ?>/assets/billing2/images/users.png">
                                                                    </div>
                                                                    <div class="user_header_text">
                                                                        <h4>james alford <span class="business_user">business user</span></h4>
                                                                        <h6>Customer#: 140b8fa7f4</h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6">
                                                            <div class="start_a_plan_btn">
                                                                <a href=""><span>Start a new plan</span>&nbsp;<i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dashboard_trips_sec">
                                                    <div class="trips_tabpanel_head_search">
                                                        <div class="trips_tabpanel_head_item">
                                                            <ul class="nav">
                                                                <li>
                                                                    <h4 class="active">Search by Keyword</h4>
                                                                </li>
                                                                <li>
                                                                    <h4>Search by Name</h4>
                                                                </li>
                                                            </ul>
                                                            <div class="trips_search_item">
                                                                <form action="" method="post">
                                                                    <div class="row">
                                                                        <div class="col-xl-10 col-9">
                                                                            <input type="text" name="" class="form-control" placeholder="Search by Keyword">
                                                                        </div>
                                                                        <div class="col-xl-2 col-3 padding_right">
                                                                            <div class="search_btn">
                                                                                <button type="submit" class="btn_btn"><i class="bi bi-search"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <section class="trips_content_sec trips_content_desktop_vew">
                                                        <div class="row">
                                                            <div class="col-xl-6 col-lg-6 col-12">
                                                                <div class="trips_conten_left_items">
                                                                    <div class="trips_left_items_scrolling">
                                                                        <div class="tab">
                                                                            <ul class="list-unstyled">
                                                                                <li class="nav-item">
                                                                                    <div class="tablinks" onclick="openCity(event, 'meeting')" id="defaultOpen">
                                                                                        <div class="events_items_box_trips">
                                                                                            <div class="event_items_left">
                                                                                                <h6>SEP 06 11:00 AM - 11:30 AM</h6>
                                                                                                <h2>Meeting with a client</h2>
                                                                                                <p>Tell how to boost website traffic</p>
                                                                                                <span class="meet_btn yellow_color">MEETING</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="nav-item">
                                                                                    <div class="tablinks" onclick="openCity(event, 'trip')">
                                                                                        <div class="events_items_box_trips">
                                                                                            <div class="event_items_left">
                                                                                                <h6>SEP 10 - SEP 15</h6>
                                                                                                <h2>Bob Smith’s trip to London</h2>
                                                                                                <p>Business trip with sales team</p>
                                                                                                <span class="meet_btn light_bkue">TRIP</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="nav-item">
                                                                                    <div class="tablinks" onclick="openCity(event, 'event')">
                                                                                        <div class="events_items_box_trips">
                                                                                            <div class="event_items_left">
                                                                                                <h6>SEP 18 11:00 AM - 11:30 AM</h6>
                                                                                                <h2>Sales Event</h2>
                                                                                                <p>Tell how to boost website traffic</p>
                                                                                                <span class="meet_btn light_green">EVENT</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="nav-item">
                                                                                    <div class="tablinks" onclick="openCity(event, 'trip_2')">
                                                                                        <div class="events_items_box_trips">
                                                                                            <div class="event_items_left">
                                                                                                <h6>SEP 10 - SEP 15</h6>
                                                                                                <h2>Bob Smith’s trip to London</h2>
                                                                                                <p>Business trip with sales team</p>
                                                                                                <span class="meet_btn light_bkue">TRIP</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="nav-item">
                                                                                    <div class="tablinks" onclick="openCity(event, 'meeting_2')">
                                                                                        <div class="events_items_box_trips">
                                                                                            <div class="event_items_left">
                                                                                                <h6>SEP 06 11:00 AM - 11:30 AM</h6>
                                                                                                <h2>Meeting with a client</h2>
                                                                                                <p>Tell how to boost website traffic</p>
                                                                                                <span class="meet_btn pink_color">MEETING</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                                <li class="nav-item">
                                                                                    <div class="tablinks" onclick="openCity(event, 'trip_3')">
                                                                                        <div class="events_items_box_trips">
                                                                                            <div class="event_items_left">
                                                                                                <h6>SEP 10 - SEP 15</h6>
                                                                                                <h2>Bob Smith’s trip to London</h2>
                                                                                                <p>Business trip with sales team</p>
                                                                                                <span class="meet_btn light_bkue">TRIP</span>
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
                                                                <div class="trips_meeting_righ_box_sec">
                                                                    <div class="tab-content">
                                                                        <div id="meeting" class="tabcontent">
                                                                            <div class="trips_meeting_righ_box_item">
                                                                                <div class="trips_meeting_righ_box_item_text">
                                                                                    <div class="row">
                                                                                        <div class="col-xl-8 col-12">
                                                                                            <div class="time_head">
                                                                                                <h6>SEP 06 11:00 AM - 11:30 AM</h6>
                                                                                                <h2>Meeting with a client</h2>
                                                                                                <h3>Tell how to boost website traffic</h3>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-xl-4 col-12">
                                                                                            <div class="trips_meeting_righ_box_btn text-right">
                                                                                                <a href="javascript:avoid(0)" class="yellow_color">MEETING</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xl-12">
                                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                                                        <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="map_trips">
                                                                                    <img src="<?php echo SITE; ?>/assets/billing2/images/map_img.jpg" class="img-fluid">
                                                                                </div>
                                                                                <div class="trips_meeting_righ_box_item_text">
                                                                                    <div class="col-xl-12">
                                                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                                                        <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                                                        <div class="open_and_export_button">
                                                                                            <ul class="list-unstyled">
                                                                                                <li><a href="">Open&nbsp;&nbsp;<i class="bi bi-eye"></i></a></li>
                                                                                                <li><a href="">Open&nbsp;&nbsp;<i class="bi bi-box-arrow-in-right"></i></a></li>
                                                                                            </ul>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div id="trip" class="tabcontent">
                                                                            TRIP
                                                                        </div>
                                                                        <div id="event" class="tabcontent">
                                                                            EVENT
                                                                        </div>
                                                                        <div id="trip_2" class="tabcontent">
                                                                            TRIP
                                                                        </div>
                                                                        <div id="meeting_2" class="tabcontent">
                                                                            MEETING
                                                                        </div>
                                                                        <div id="trip_3" class="tabcontent">
                                                                            MEETING
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </section>
                                                    <div class="trips_mobile_view_content">
                                                        <div class="acc-container">
                                                            <div class="acc">
                                                                <div class="acc-head">
                                                                    <div class="event_items_left">
                                                                        <h6>SEP 06 11:00 AM - 11:30 AM</h6>
                                                                        <h2>Meeting with a client</h2>
                                                                        <p>Tell how to boost website traffic</p>
                                                                        <span class="meet_btn yellow_color">MEETING</span>
                                                                    </div>
                                                                </div>
                                                                <div class="acc-content">
                                                                    <div class="trips_meeting_righ_box_item">
                                                                        <div class="trips_meeting_righ_box_item_text">
                                                                            <div class="row">
                                                                                <div class="col-xl-12 col-12">
                                                                                    <div class="time_head">
                                                                                        <h6>SEP 06 11:00 AM - 11:30 AM</h6>
                                                                                        <h2>Meeting with a client</h2>
                                                                                        <h3>Tell how to boost website traffic</h3>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xl-12">
                                                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="map_trips">
                                                                            <img src="<?php echo SITE; ?>/assets/billing2/images/map_img.jpg" class="img-fluid">
                                                                        </div>
                                                                        <div class="trips_meeting_righ_box_item_text">
                                                                            <div class="col-xl-12">
                                                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                                                                                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                                                <div class="open_and_export_button">
                                                                                    <ul class="list-unstyled">
                                                                                        <li><a href="">Open&nbsp;&nbsp;<i class="bi bi-eye"></i></a></li>
                                                                                        <li><a href="">Open&nbsp;&nbsp;<i class="bi bi-box-arrow-in-right"></i></a></li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="acc">
                                                                <div class="acc-head">
                                                                    <div class="event_items_left">
                                                                        <h6>SEP 10 - SEP 15</h6>
                                                                        <h2>Bob Smith’s trip to London</h2>
                                                                        <p>Business trip with sales team</p>
                                                                        <span class="meet_btn light_bkue">TRIP</span>
                                                                    </div>
                                                                </div>
                                                                <div class="acc-content">
                                                                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolore magnam, nobis consequuntur nemo cupiditate vel sit ducimus quisquam quaerat sint officia ad voluptas beatae eveniet. Aliquam aspernatur nulla cupiditate reiciendis ut? Illum odio id odit? Tempore, ea itaque illum laborum quasi</p>
                                                                </div>
                                                            </div>
                                                            <div class="acc">
                                                                <div class="acc-head">
                                                                    <div class="event_items_left">
                                                                        <h6>SEP 18 11:00 AM - 11:30 AM</h6>
                                                                        <h2>Sales Event</h2>
                                                                        <p>Tell how to boost website traffic</p>
                                                                        <span class="meet_btn light_green">EVENT</span>
                                                                    </div>
                                                                </div>
                                                                <div class="acc-content">
                                                                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolore magnam, nobis consequuntur nemo cupiditate vel sit ducimus quisquam quaerat sint officia ad voluptas beatae eveniet. Aliquam aspernatur nulla cupiditate reiciendis ut? Illum odio id odit? Tempore, ea itaque illum laborum quasi</p>
                                                                </div>
                                                            </div>
                                                            <div class="acc">
                                                                <div class="acc-head">
                                                                    <div class="event_items_left">
                                                                        <h6>SEP 10 - SEP 15</h6>
                                                                        <h2>Bob Smith’s trip to London</h2>
                                                                        <p>Business trip with sales team</p>
                                                                        <span class="meet_btn light_bkue">TRIP</span>
                                                                    </div>
                                                                </div>
                                                                <div class="acc-content">
                                                                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolore magnam, nobis consequuntur nemo cupiditate vel sit ducimus quisquam quaerat sint officia ad voluptas beatae eveniet. Aliquam aspernatur nulla cupiditate reiciendis ut? Illum odio id odit? Tempore, ea itaque illum laborum quasi</p>
                                                                </div>
                                                            </div>
                                                            <div class="acc">
                                                                <div class="acc-head">
                                                                    <div class="event_items_left">
                                                                        <h6>SEP 06 11:00 AM - 11:30 AM</h6>
                                                                        <h2>Meeting with a client</h2>
                                                                        <p>Tell how to boost website traffic</p>
                                                                        <span class="meet_btn pink_color">MEETING</span>
                                                                    </div>
                                                                </div>
                                                                <div class="acc-content">
                                                                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolore magnam, nobis consequuntur nemo cupiditate vel sit ducimus quisquam quaerat sint officia ad voluptas beatae eveniet. Aliquam aspernatur nulla cupiditate reiciendis ut? Illum odio id odit? Tempore, ea itaque illum laborum quasi</p>
                                                                </div>
                                                            </div>
                                                            <div class="acc">
                                                                <div class="acc-head">
                                                                    <div class="event_items_left">
                                                                        <h6>SEP 10 - SEP 15</h6>
                                                                        <h2>Bob Smith’s trip to London</h2>
                                                                        <p>Business trip with sales team</p>
                                                                        <span class="meet_btn light_bkue">TRIP</span>
                                                                    </div>
                                                                </div>
                                                                <div class="acc-content">
                                                                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolore magnam, nobis consequuntur nemo cupiditate vel sit ducimus quisquam quaerat sint officia ad voluptas beatae eveniet. Aliquam aspernatur nulla cupiditate reiciendis ut? Illum odio id odit? Tempore, ea itaque illum laborum quasi</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <!-- <script type="text/javascript" src="js/main.js"></script>
    <script src="js/jquery-3.6.0.js"></script>
    <script src="js/jquery-ui.js"></script> -->
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