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
    <?php
    if (file_exists('include_google_analitics.php')) {
        include('include_google_analitics.php');
    }
    ?>




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

    <!-- End Google Tag Manager -->



    <script>
        var schoolNumber = 1;

        var event_list = Array(

            <?php

            // Trip

            $stmt = $dbh->prepare("

            (SELECT * FROM trips WHERE pdf_generated=1 and itinerary_type='trip' and (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_user=?)

            UNION ALL

            (SELECT * FROM trips WHERE pdf_generated=1 and itinerary_type='trip' and (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_trip IN( SELECT trip_id from migration_master where modifier_user_id=? AND status NOT IN ('pending','declined')))

            ORDER BY `location_datel` ASC

            ");

            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);

            $stmt->bindValue(2, $userdata['id'], PDO::PARAM_INT);

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

                    $pdfname = $triptitle . '-' . $timeline->id_trip;

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

                                end: '" . date('Y-m-d', strtotime($date_end) + 3600) . "T23:59:00" . "',

                                url: '" . SITE . "pdf/" . $pdfname . ".pdf',

                                school: 1,

                                color: '#046fcf'

                            },";
                }

                echo $aux;
            }



            // Event Trip

            $stmt_event_list = $dbh->prepare("

            (SELECT * FROM trips WHERE pdf_generated=1 and itinerary_type='event' and (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_user=?)

            UNION ALL

            (SELECT * FROM trips WHERE pdf_generated=1 and itinerary_type='event' and (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_trip IN( SELECT trip_id from migration_master where modifier_user_id=? AND status NOT IN ('pending','declined')))

            ORDER BY `location_datel` ASC

            ");

            $stmt_event_list->bindValue(1, $userdata['id'], PDO::PARAM_INT);

            $stmt_event_list->bindValue(2, $userdata['id'], PDO::PARAM_INT);

            $tmp_event_list = $stmt_event_list->execute();

            $aux = '';

            if ($tmp_event_list && $stmt_event_list->rowCount() > 0) {

                $timelines = $stmt_event_list->fetchAll(PDO::FETCH_OBJ);

                foreach ($timelines as $timeline) {

                    $employee_ = '';

                    if (!empty($timeline->id_employee)) {

                        $employee_ = '\n' . get_employee($timeline->id_employee);
                    }



                    $triptitle = trim($timeline->title);

                    $triptitle = str_replace('&#39;', '_', $triptitle);

                    $triptitle = str_replace(' ', '_', $triptitle);

                    $pdfname = $triptitle . '-' . $timeline->id_trip;

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

                    end: '" . date('Y-m-d', strtotime($date_end) + 3600) . "T23:59:00" . "',

                    url: '" . SITE . "pdf/" . $pdfname . ".pdf',

                    school: 2,

                    color: '#f7b94d'

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

                    $from = $timeline->event_date_from ? date('Y-m-d', strtotime($timeline->event_date_from))  : "";

                    $to = $timeline->event_date_to ? date('Y-m-d', strtotime($timeline->event_date_to))  : "";

                    $to = $to . "T23:59:00";



                    if ($from && $to) {



                        $aux .= '{ 

                                    title: "' . $timeline->event_title . '",

                                    start:  "' .  $from . '",

                                    end: "' . $to . '",

                                    color: "#f7b94d",

                                    school: 2,

                                },';
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

                    $from = $timeline->event_date_from ? date('Y-m-d', strtotime($timeline->event_date_from))  : "";

                    $to = $timeline->event_date_to ? date('Y-m-d', strtotime($timeline->event_date_to))  : "";

                    $to = $to . "T23:59:00";



                    if ($from && $to) {

                        $aux .= '{ 

                                    title: "' . $timeline->event_title . '",

                                    start:  "' .  $from . '",

                                    end: "' . $to . '",

                                    color: "#ed272f",

                                    school: 3,

                                },';
                    }
                }

                echo $aux;
            }



            ?>

        );
    </script>




    <style>
        .trip_text {
            width: 50% !important;
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



$stmt_all = $dbh->prepare("

(SELECT * FROM trips WHERE (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_user=?)

UNION ALL

(SELECT * FROM trips WHERE (DATE(NOW()) <= DATE(location_datel) OR DATE(NOW()) <= DATE(location_datel_arr)) AND id_trip IN( SELECT trip_id from migration_master where modifier_user_id=? AND status NOT IN ('pending','declined')))

ORDER BY `location_datel` ASC

");



$stmt_all->bindValue(1, $userdata['id'], PDO::PARAM_INT);

$stmt_all->bindValue(2, $userdata['id'], PDO::PARAM_INT);

$tmp = $stmt_all->execute();



$stmt_event = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='event' AND DATE(NOW()) <= DATE(event_date_to)");

$stmt_event->bindValue(1, $userdata['id'], PDO::PARAM_INT);

$tmp_event = $stmt_event->execute();



$stmt_meeting = $dbh->prepare("SELECT * FROM events WHERE user_id=? AND event_type='meeting' AND DATE(NOW()) <= DATE(event_date_to)");

$stmt_meeting->bindValue(1, $userdata['id'], PDO::PARAM_INT);

$tmp_meeting = $stmt_meeting->execute();



?>



<body>



    <!-- Google Tag Manager (noscript) -->

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <!-- End Google Tag Manager (noscript) -->



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

                                        <a href="<?= SITE; ?>welcome" class="dropdown-item drop-menu-item" target="_blank">

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





                            <ul class="header-menu nav notification-nav">

                                <li class="nav-item">

                                    <a href="<?= SITE ?>message" class="nav-link notification-link">

                                        <span><i class="fa fa-envelope"> </i></span>

                                        <span class="badge" id="message_count"></span>

                                    </a>

                                </li>



                            </ul>





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

                                                            <a href="<?= SITE; ?>welcome" class="dropdown-item drop-menu-item" target="_blank">

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

                                        $page_index = "home";

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

                                                                <a href="<?= SITE; ?>trip/itinerary-option"><span>Start a new plan</span>

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

                                                                <h3><i class="fa fa-calendar-check-o" aria-hidden="true"></i>&nbsp;Upcoming</h3>



                                                                <div class="event_items_list">

                                                                    <?php if ($tmp && $stmt_all->rowCount() > 0) {

                                                                        $trips = $stmt_all->fetchAll(PDO::FETCH_OBJ);



                                                                        foreach ($trips as $tripl) {

                                                                            $aux = '';

                                                                            $employee_ = "";

                                                                            if (!empty($tripl->id_employee)) {

                                                                                $employee_ = ' ' . get_employee($tripl->id_employee);
                                                                            }



                                                                            $triptitle = trim($tripl->title);

                                                                            $triptitle = str_replace('&#39;', '_', $triptitle);

                                                                            $triptitle = str_replace(' ', '_', $triptitle);

                                                                            $pdfname = $triptitle . '-' . $tripl->id_trip;

                                                                            $raw_text = "** incomplete {$tripl->itinerary_type} plan **";

                                                                            if ($tripl->itinerary_type == "appoin") {
                                                                                $raw_text = "** incomplete appointment plan **";
                                                                            }


                                                                            if ($tripl->pdf_generated) {

                                                                                $titledata = $titledata2 = $tripl->title . ' ' . $employee_;
                                                                            } else {

                                                                                $titledata = $titledata2 = $raw_text;
                                                                            }

                                                                            if (strlen($titledata) >= 80)

                                                                                $titledata = substr($titledata, 0, 80) . '...';



                                                                            if ($tripl->pdf_generated) {

                                                                                $file_path = $pdfname . '.pdf';

                                                                                $titledata = '<a href="' . SITE . 'trip/create-timeline/' . $tripl->id_trip . '" style="text-decoration: none" class="trip_name"><i class="fa fa-pencil"></i> ' . $titledata . ' </a>';

                                                                                // $aux .= '<button style="text-decoration: none" class="trip_text file_download" data-file-path="' . $file_path . '"> <i class="fa fa-download" aria-hidden="true"></i> Download </button>';

                                                                            } else {

                                                                                $aux .= '<a href="' . SITE . 'trip/create-timeline/' . $tripl->id_trip . '" style="text-decoration: none" class="trip_text open"> Open </a>';
                                                                            }



                                                                            $style = null;

                                                                            $personalized = null;



                                                                            if ($tripl->cover_image) {

                                                                                $url = $tripl->cover_image_url;

                                                                                $parmas = $tripl->cover_image_type ? null : "&q=80&w=400";

                                                                                $full_path = $url . $parmas;

                                                                                $style = "style='background-image:url(" . $full_path . ")'";

                                                                                $personalized = "personalized";
                                                                            }



                                                                    ?>



                                                                            <div class="events_items_box <?= $personalized ?>" <?= $style ?>>

                                                                                <div class="event_items_left">

                                                                                    <h6 class="d-flex justify-content-between"><span>Packet #: <b><?= $tripl->packet_number ?></b></span> </h6>

                                                                                    <h6><span><?= date('M d h:i A', strtotime($tripl->location_datel . ' ' . $tripl->location_datel_deptime)) ?> - <?= date('h:i A', strtotime($tripl->location_datel_arrtime)) ?></span></h6>

                                                                                    <h2><?= $titledata; ?></h2>

                                                                                    <p></p>

                                                                                    <div class="trip_section">

                                                                                        <span class="trip_text"><?= $tripl->itinerary_type; ?></span>

                                                                                        <?= $aux ?>

                                                                                    </div>

                                                                                </div>

                                                                                <div class="event_items_right top_action">

                                                                                    <!--<a href="javascript:avoid(0)" onclick="delete_trip(<?= $tripl->id_trip ?>)">-->

                                                                                    <!--    <i class="fa fa-times-circle-o" aria-hidden="true"></i>-->

                                                                                    <!--</a>-->





                                                                                    <button class="btn btn-info trip_action_button trip_expand" data-trip_ref="<?= $tripl->id_trip ?>">

                                                                                        <i class="fa fa-expand" aria-hidden="true"></i>

                                                                                    </button>



                                                                                    <button class="btn btn-danger trip_action_button" onclick="delete_trip(<?= $tripl->id_trip ?>)">

                                                                                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>

                                                                                    </button>



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

                                                                                        $event_date_from = $eventl->event_date_from . " " . $eventl->event_time_from;

                                                                                        $event_date_from = date('M d H:i A', strtotime($event_date_from));



                                                                                        $event_date_to = $eventl->event_date_to . " " . $eventl->event_time_to;

                                                                                        $event_date_to = date('M d H:i A', strtotime($event_date_to));

                                                                                        ?>

                                                                                        <?= $event_date_from ?> - <?= $event_date_to; ?>

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

                                                                                        $meeting_date_from = $meetingl->event_date_from . " " . $meetingl->event_time_from;

                                                                                        $meeting_date_from = date('M d H:i A', strtotime($meeting_date_from));



                                                                                        $meeting_date_to = $meetingl->event_date_to . " " . $meetingl->event_time_to;

                                                                                        $meeting_date_to = date('M d H:i A', strtotime($meeting_date_to));

                                                                                        ?>

                                                                                        <?= $meeting_date_from ?> - <?= $meeting_date_to; ?>

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

                                                                                <li><a href="javascript:void(0)" class="trips active">trips</a></li>

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

        <input type="file" id="upload" value="Choose a file" accept="image/png, image/gif, image/jpeg" style="display: none;">

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





    <div class="modal fade modal-blur" id="trip_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-keyboard="false" data-backdrop="static">

        <div class="modal-dialog modal-trip-lg" role="document">

            <div class="modal-content trip-content">

                <div class="modal-header">

                    <h4 class="modal-title modal_trip_name" id="myModalLabel">.</h4>

                    <button type="button" class="close trip_close" data-dismiss="modal">&times;</button>



                </div>



                <div class="modal-body text-center trip-body">



                    <div class="row">



                        <div class="col-md-8 p-0 segment-item">



                            <div class="card trip_info">

                                <div class="trip_heading">

                                    <h5>Attendees</h5>

                                </div>



                                <div>

                                    <div class="loading_screen" style="display: none;" id="attendee_loading">

                                        <div class="spinner-border text-primary"></div>

                                    </div>

                                    <div class="attendee_details" id="attendee_details">

                                        <!-- <div class="row">

                                        <div class="col-md-3">

                                            <div class="people_left_side">

                                                <div class="people_img"><img src="https://localhost/master/stag/ajaxfiles/people/63ee82324eacf-1676575282.png"></div>

                                                <div class="people_info">

                                                    <h4>David</h4>

                                                    <p>Customer Name</p>

                                                </div>

                                            </div>

                                        </div>

                                                                                

                                        <div class="col-md-3">

                                            <div class="people_left_side">

                                                <div class="people_img"><img src="https://localhost/master/stag/ajaxfiles/people/63ee82324eacf-1676575282.png"></div>

                                                <div class="people_info">

                                                    <h4>Robert</h4>

                                                    <p>Customer Name</p>

                                                </div>

                                            </div>

                                        </div>

                                    </div> -->

                                    </div>

                                </div>



                                <div class="trip_heading top-border">

                                    <h5>Updates</h5>

                                </div>

                                <div>

                                    <div class="loading_screen" style="display: none;" id="update_loading">

                                        <div class="spinner-border text-primary"></div>

                                    </div>



                                    <div class="update_details" id="update_details">

                                        <!-- <ul class="list-group striped-list">

                                        <li class="list-group-item update-item">



                                            <div class="people_left_side update">

                                                <div class="people_img"><img src="https://localhost/master/stag/ajaxfiles/people/63ee82324eacf-1676575282.png"></div>

                                                <div class="people_info">

                                                    <h4>David</h4>

                                                </div>

                                            </div>



                                            <div class="update-info">

                                                <h6> Checked-in to his flight at PHL</h6>

                                                <p><i class="fa fa-calendar-o" aria-hidden="true"></i> June 2nd, 2023 1:17pm</p>

                                            </div>

                                        </li>                                                                               

                                        <li class="list-group-item update-item">



                                            <div class="people_left_side update">

                                                <div class="people_img"><img src="https://localhost/master/stag/ajaxfiles/people/63ee82324eacf-1676575282.png"></div>

                                                <div class="people_info">

                                                    <h4>Jhon</h4>

                                                </div>

                                            </div>



                                            <div class="update-info">

                                                <h6>Checked-in to his flight at PHL</h6>

                                                <p><i class="fa fa-calendar-o" aria-hidden="true"></i> June 23rd, 2023 10:17am</p>

                                            </div>

                                        </li>

                                    </ul> -->

                                    </div>

                                </div>

                            </div>



                        </div>



                        <div class="col-md-4 segment-item">



                            <div class="card other_info">

                                <div class="trip_heading document">

                                    <h5>Documents</h5>

                                </div>



                                <div class="document_details">



                                    <div class="document_action">

                                        <form>



                                            <div class="file-upload-container" onclick="document.getElementById('fileInput').click();">

                                                <label for="fileInput"><i class="fa fa-cloud-upload" aria-hidden="true"></i></label>

                                                <div class="instructions">

                                                    <span>Click to upload</span> or drag and drop<br>

                                                    PDF, DOC, DOCX (max 2MB)

                                                </div>

                                            </div>

                                            <input type="file" id="fileInput" accept=".png, .jpg, .pdf, .doc, .docx" style="display: none;" multiple>

                                        </form>



                                    </div>



                                    <div>

                                        <div class="loading_screen" style="display: none;" id="document_loading">

                                            <div class="spinner-border text-primary"></div>

                                        </div>



                                        <div class="document_list" id="document_list">



                                            <!-- <ul class="list-group">

                                            <li class="list-group-item document-item">

                                                <div class="document-body">

                                                    <div class="document-info">

                                                        <p>

                                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>

                                                            <span>101_passport_Emojis(2).jpg</span>

                                                        </p>

                                                    </div>

                                                </div>

                                            </li>                                           



                                            <li class="list-group-item document-item">

                                                <div class="document-body">

                                                    <div class="document-info">

                                                        <p>

                                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>

                                                            <span>101_passport_Emojis(2).jpg</span>

                                                        </p>

                                                    </div>

                                                </div>

                                            </li>



                                        </ul> -->



                                        </div>

                                    </div>



                                </div>

                            </div>



                            <div class="card other_info">

                                <div class="trip_heading document">

                                    <h5>Your Comments</h5>

                                </div>



                                <div class="comment_details">



                                    <div class="loading_screen" style="display: none;" id="comment_loading">

                                        <div class="spinner-border text-primary"></div>

                                    </div>



                                    <div class="comment_list" id="comment_list">



                                        <!-- <ul class="list-group">



                                            <li class="list-group-item comment-item">

                                                <div class="comment-body">

                                                    <p>Team received updated copy of business agreement.</p>

                                                    <div class="comment-info">

                                                        <p>by <span>David</span> at <span>June 23rd, 2023 10:17am</span> </p>

                                                    </div>

                                                </div>

                                            </li>                                            



                                            <li class="list-group-item comment-item">

                                                <div class="comment-body">

                                                    <p>Team received updated copy of business agreement.</p>

                                                    <div class="comment-info">

                                                        <p>by <span>David</span> at <span>June 23rd, 2023 10:17am</span> </p>

                                                    </div>

                                                </div>

                                            </li>



                                        </ul> -->



                                    </div>



                                    <div class="comment_entry">



                                        <form id="commentForm">

                                            <div class="comment_input">

                                                <div class="input-group comment-group">

                                                    <input type="text" class="form-control comment-field" name="commentfield" id="commentfield" placeholder="Add a new comment">

                                                    <span class="input-group-btn">

                                                        <button class="btn btn-default comment-action" type="submit">Submit</button>

                                                    </span>

                                                </div>

                                            </div>

                                        </form>



                                    </div>







                                </div>

                            </div>

                        </div>



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

    <script type="text/javascript" src="<?= SITE ?>dashboard/js/main.js?v=202290"></script>

    <script src="<?= SITE ?>dashboard/js/jquery-ui.js"></script>

    <script src="<?= SITE ?>/js/dashboard_next.js?v=<?= time(); ?>"></script>





    <script>
        $(function() {

            var dataSet = 'request_id=' + "planiversity";

            messageNotificationProcess(dataSet);

        });



        function messageNotificationProcess(dataSet) {



            $.ajax({

                url: SITE + "root/message/notification",

                type: "GET",

                data: dataSet,

                dataType: "json",

                cache: false,

                success: function(response) {



                    if (response.data.results > 0) {

                        $('#message_count').html(response.data.results);

                    } else {

                        $('#message_count').html('');

                    }



                },

                error: function(jqXHR, textStatus, errorThrown) {



                    // $(".loading_screen").hide();

                    // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");



                }



            });





        }



        var timezone_offset_minutes = new Date().getTimezoneOffset();

        timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;



        $('.file_download').on('click', function() {



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

                classToggle(this);



                var valuePass = {

                    school: 1,

                    mode: 'trips',

                };

                localStorageValue(valuePass);



            });

            $(".events").click(function() {

                $(".fc-events-button").click();

                classToggle(this);



                var valuePass = {

                    school: 2,

                    mode: 'events',

                };

                localStorageValue(valuePass);

            });

            $(".meetings").click(function() {

                $(".fc-meetings-button").click();

                classToggle(this);



                var valuePass = {

                    school: 3,

                    mode: 'meetings',

                };

                localStorageValue(valuePass);



            });

            $(".all").click(function() {

                $(".fc-alls-button").click();

                classToggle(this);



                var valuePass = {

                    school: 'all',

                    mode: 'all',

                };

                localStorageValue(valuePass);

            });





            const classToggle = (e) => {

                $('.event_trip_meeting_all a').removeClass("active");

                $(e).addClass("active");

            }





            const localStorageValue = (holding) => {

                localStorage.setItem('calender_mode', JSON.stringify(holding));

            }



            if (localStorage.getItem('calender_mode') !== null) {



                var savedValue = JSON.parse(localStorage.getItem('calender_mode'));

                $("." + savedValue.mode).click();

                schoolNumber = savedValue.school;

            }





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

                        $('#cropImagePop').modal('show');

                        rawImg = e.target.result;



                        // $uploadCrop.croppie('bind', {

                        //     url: rawImg

                        // }).then(function() {

                        //     console.log('jQuery bind complete');

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

                mouseWheelZoom: 'ctrl'

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

                    format: 'png',

                    circle: true



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

                            $('#formId').trigger("reset");

                            $('#cropImagePop').modal('hide');

                            toastr.success('Profile Picture Successfully Updated');



                            $('.crop_submit_button').css('cursor', 'pointer');

                            $('.crop_submit_button').removeAttr('disabled');



                            // $('.btn-close-modal').click();

                            // location.reload()

                        }

                    });

                });

            });

        });
    </script>

</body>



</html>