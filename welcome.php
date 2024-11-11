<?php
include_once("config.ini.php");

include("class/class.Plan.php");
include_once("class/class.ToolsWelcome.php");
include_once ("class/class.TripPlan.php");

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

    if (!is_array($_POST['group_del'])) {
        $arr = [$_POST['group_del']];
    } else {
        $arr = $_POST['group_del'];
    }
    foreach ($arr as $tripId) {
        ActivityLogger::log($tripId, ActivityLogger::PLAN_DELETED);
    }

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

        function toggleAlertForm()
        {
            $(".alert-form").toggle();
            $("#for1").prop("checked",true);
            $("#update_status_text").val('');

            if ($(".alert-form").is(":visible")) {
                $(".attend-badge-div").show();
                $(".attend-checkbox").prop("checked", true);
                $(".update-status-person img").on('click', function () {
                    $("#attend-radio-selected").prop("checked", true);
                    $(this).parent().parent().find(".badge").toggle();
                    var that = $(this).parent().parent().find(".checkbox");
                    if (that.is(":checked")) {
                        that.prop("checked", false);
                    } else {
                        that.prop("checked", true);
                    }
                });
            } else {
                $(".update-status-person img").off('click');
                $(".attend-badge-div").hide();
                $(".attend-checkbox").prop("checked", false);
            }
        }

        $(document).ready(function () {
           $("#status-update-btn").on("click", function(e) {
               e.preventDefault();
               toggleAlertForm();
           })
        });
        var schoolNumber = 1;
        var event_list = Array(
            <?php
            $badges = [
                ['itineraryType' => 'trip', 'color' => '#046fcf', 'school' => 1],
                ['itineraryType' => 'event', 'color' => '#f7b94d', 'school' => 2],
                ['itineraryType' => 'job', 'color' => '#34c759', 'school' => 4],
                ['itineraryType' => 'appt', 'color' => '#a259ff', 'school' => 5],
            ];
            foreach ($badges as $b) {
                echo ToolsWelcome::generateTripEvents($dbh, $userdata, $b['itineraryType'], $b['color'], $b['school']);
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
                                    school: 3,
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
        .update-status-person {
            cursor: pointer;
        }

        .update-status-textarea, .update-status-textarea:focus {
            font-size: 12px;
            border-radius: 5px;
            box-shadow: none;
            color: #666;
            outline: none;
            width: 50%;
            font-weight: 400;
            -webkit-box-shadow: 0 1px 1px 0 rgba(45, 44, 44, 0.05) !important;
            background: #F9FAFF;
            border: 1px solid #C8CCD5;
            padding: 2px 10px;
            resize: none;
        }

        .update-status-btn, .update-status-btn:focus, .update-status-btn:hover {
            font-size: 17px;
            color: #fff;
            outline: none;
            font-size: 14px;
            font-weight: bold;
            border: 1px solid #F39F32;
            background: #f00;
            box-shadow: 0px 4px 10px rgba(255, 255, 45, 0.0001);
            border-radius: 4px;
            cursor: pointer;
            outline: none;
            text-indent: inherit;
        }

        .alert-form {
            display:none;
        }

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
        
        .trip_section {
            display: flex;
            justify-content: space-between;
        }

        .trip_text:nth-child(2) {
            margin-left: 10px;
        }

        .trip_text.file_download {
            background: linear-gradient(#fac85c, #f5ab3f);
            color: #000;
            border: 0;
            cursor: pointer;
        }

        .trip_text.open {
            background: linear-gradient(#3380d7, #387fbb);
            color: #fff !important;
        }

        .trip_text {
            width: 50% !important;
        }        
        
        .event_trip_meeting_all a.active {
            background: #065bb5;
            color: #fff;
            border-radius: 10px;
        }
        
        .events_items_box.personalized {
            background-color: white;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            box-shadow: inset 0 0 0 2000px rgb(8 76 160 / 20%);
        }

        .events_items_box.personalized h6 {
            color: #fff;
            filter: drop-shadow(2px 4px 6px black);
        }

        .events_items_box.personalized h2,
        .events_items_box.personalized h2 a {
            color: #fff;
            filter: drop-shadow(2px 4px 6px black);
            padding: 0;
            background-color: unset;
            font-size: 18px;
        }

        a.trip_name {
            background-color: #c5e3fb;
            color: #038df4;
            text-transform: capitalize;
            font-weight: 700;
            border-radius: 4px;
            width: 100%;
            display: block;
            text-align: left;
            padding: 10px 20px;
            font-size: 14px;
        }

        a.trip_name i {
            margin-right: 2px;
            background: #357fcb;
            padding: 5px;
            border-radius: 14px;
            color: #fff;
            width: 25px;
            height: 25px;
            text-align: center;
            font-size: 14px;
        }

        .event_items_left h2 {
            text-transform: capitalize;
        }  
        
        .segment-item {
            display: grid;
        }
        .card.trip_info {
            margin-bottom: 0px !important;
        }
        .card.other_info {
            margin-bottom: 0px !important;
        }  

        .gradient-border, .gradient-border2 {
            position: relative;
            width: 200px;
            height: 200px;
            border-radius: 50%;
        }

        .gradient-border::before, .gradient-border2::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            padding: 5px;
            -webkit-mask: linear-gradient(white, white) content-box, linear-gradient(white, white);
            mask: linear-gradient(white, white) content-box, linear-gradient(white, white);
            mask-composite: exclude;
        }

        .gradient-border::before {
            background: conic-gradient(from 0deg, rgba(0, 255, 0, 1) 0%, rgba(0, 255, 0, 0) 100%);

        }

        .gradient-border2::before {
            background: conic-gradient(from 0deg, rgba(255, 255, 0, 1) 0%, rgba(255, 255, 0, 0) 100%);

        }

        img.collaborators {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        
    </style>
</head>

<?php

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
(SELECT * FROM trips WHERE (DATE(NOW()) <= DATE(location_datel)) AND id_user=?)
UNION ALL
(SELECT * FROM trips WHERE (DATE(NOW()) <= DATE(location_datel)) AND id_trip IN( SELECT trip_id from migration_master where modifier_user_id=? AND status NOT IN ('pending','declined')))
union
(select t.* from trips t
inner join connect_master cm on t.id_trip=cm.id_trip 
inner join connect_details cd on cm.id=cd.connect_id
inner join employees e on e.id_employee=cd.people_id 
inner join users u on e.employee_id=u.customer_number
where u.id=? and (DATE(NOW()) <= DATE(location_datel)))
ORDER BY `location_datel` ASC
");
$stmt_all->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$stmt_all->bindValue(2, $userdata['id'], PDO::PARAM_INT);
$stmt_all->bindValue(3, $userdata['id'], PDO::PARAM_INT);
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

                    <?php  include_once("includes/top_right_navigation.php");  ?>

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
                                                            <div class="upcoming_sec" style="background-color:#0c246b;border-radius: 10px">
                                                                <h3 style="color:#fff"><i class="fa fa-calendar-check-o" aria-hidden="true"></i>&nbsp;Upcoming</h3>

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
                                                                            $expend_button = "";
                                                                            
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
                                                                                $titledata = '<a href="' . SITE . 'trip/connect/' . $tripl->id_trip . '" style="text-decoration: none" class="trip_name"><i class="fa fa-pencil"></i> ' . $titledata . ' </a>';
                                                                                // $aux .= '<button style="text-decoration: none" class="trip_text file_download" data-file-path="' . $file_path . '"> <i class="fa fa-download" aria-hidden="true"></i> Download </button>';
                                                                                
                                                                                $expend_button = '
                                                                                <button class="btn btn-info trip_action_button trip_expand" data-trip_ref="' . $tripl->id_trip . '">
                                                                                <i class="fa fa-expand" aria-hidden="true"></i></button>
                                                                                ';
                                                                            } else {
                                                                                $aux .= '<a href="' . SITE . 'trip/connect/' . $tripl->id_trip . '" style="text-decoration: none" class="trip_text open"> Open </a>';
                                                                            } 
                                                                            
                                                                            $style = "style='margin-bottom:30px;'";
                                                                            $personalized = null;

                                                                            if ($tripl->cover_image) {
                                                                                $url = $tripl->cover_image_url;
                                                                                $parmas = $tripl->cover_image_type ? null : "&q=80&w=400";
                                                                                $full_path = $url . $parmas;
                                                                                $style = "style='margin-bottom:30px;background-image:url(" . $full_path . ")'";
                                                                                $personalized = "personalized";
                                                                            }

                                                                            ?>
                                                                            
                                                                            <div class="events_items_box <?= $personalized ?>" <?= $style ?>>
                                                                                <div class="event_items_left">
                                                                                    <h6 class="d-flex justify-content-between"><span>Packet #: <b><?= $tripl->packet_number ?></b></span> </h6>
                                                                                    <h6 class="d-flex justify-content-between"> <span><?= date('M d h:i A', strtotime($tripl->location_datel . ' ' . $tripl->location_datel_deptime)) ?> - <?= date('h:i A', strtotime($tripl->location_datel_arrtime)) ?></span></h6>                                                                                    
                                                                                    <h2><?= $titledata; ?></h2>
                                                                                    <p></p>
                                                                                    <div class="trip_section">
                                                                                    <span class="trip_text"><?= $tripl->itinerary_type; ?></span>
                                                                                    <?= $aux ?>


                                                                                        <div style=" height:50px; display:block;position: absolute; bottom: -20px; right: 10px;">
                                                                                            <?php
                                                                                            $stmt = $dbh->prepare("SELECT a.id, c.role, a.people_id, c.f_name AS first_name, c.l_name AS last_name, c.photo, c.photo_connect, c.email,b.is_group,d.group_name
	FROM connect_details AS a
	INNER JOIN connect_master AS b ON a.connect_id = b.id
	INNER JOIN employees AS c ON a.people_id = c.id_employee
	LEFT JOIN travel_groups as d ON b.group_id = d.id
	WHERE b.id_trip = ? limit 2");
                                                                                            $stmt->bindValue(1, $tripl->id_trip, PDO::PARAM_INT);
                                                                                            $tmp = $stmt->execute();
                                                                                            $aux = '';
                                                                                            $peoples = [];
                                                                                            $first = true;
                                                                                            if ($tmp && $stmt->rowCount() > 0) {
                                                                                                $peoples = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                                                                foreach ($peoples as $people) {
                                                                                                    $photo = SITE . "assets/images/user_profile.png";
                                                                                                    $pathFolder = "people";

                                                                                                    $name = $people['first_name'] .' ' . $people['last_name'];
                                                                                                    if ($people['photo']) {
                                                                                                        $photo = SITE . 'ajaxfiles/' . $pathFolder . '/' . $people['photo'];
                                                                                                    }

                                                                                                    $borderColor = ($people['role']==TripPlan::ROLE_COLLABORATOR?'lime':'yellow');


                                                                                                    if ($first) {
                                                                                                        echo '<img src="' . $photo . '" class="rounded-circle" style="height:50px;margin-right:5px; " alt="' . $name . '">';
                                                                                                    } else {
                                                                                                        echo '<img src="' . $photo . '" class="rounded-circle" style="height:50px;margin-right:5px; margin-left:-20px;" alt="' . $name . '">';
                                                                                                    }
                                                                                                    $first = false;

                                                                                                }
                                                                                            }




                                                                                            ?>
                                                                                        </div>


                                                                                    </div>
                                                                                </div>
                                                                                <div class="event_items_right top_action">
                                                                                    
                                                                                    <?= $expend_button; ?>

                                                                                   <?php if($tripl->id_user == $userdata['id']) { ?>
                                                                                    <button class="btn btn-danger trip_action_button" onclick="delete_trip(<?= $tripl->id_trip ?>)">
                                                                                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                                                                                    </button>
                                                                                <?php } ?>
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

                                                                <?php
                                                                if ((!$tmp || $stmt_all->rowCount() == 0) &&
                                                                    (!$tmp_event || $stmt_event->rowCount() == 0) &&
                                                                    (!$tmp_meeting || $stmt_meeting->rowCount() == 0)) { ?>
                                                                    <br>
                                                                    <div class="upcoming_sec" style="border-radius:10px">
                                                                        <h1 style="text-align: center">
                                                                            <img src="<?=SITE;?>/images/upcoming-no-events.png" class="img-fluid"><br>
                                                                            You have nothing planned at this time.
                                                                        </h1>
                                                                    </div>
                                                                <?php } ?>

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
                                                                                <li><a href="javascript:void(0)" class="jobs">jobs</a></li>
                                                                                <li><a href="javascript:void(0)" class="appointments">Appointments</a></li>
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
    <?php  if ($userdata['account_type'] != 'Admin') { ?>
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
    
        <audio id="chatAudio">
        <source src="<?= SITE ?>assets/sound/notification.mp3" type="audio/mpeg">
        </audio>



    <div class="modal fade modal-blur" id="trip_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-trip-lg" role="document">
            <div class="modal-content trip-content">
                <div class="modal-header">
                    <h4 class="modal-title modal_trip_name" id="myModalLabel"> - </h4>
                    <button type="button" class="close trip_close" data-dismiss="modal">&times;</button>

                </div>

                <div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 10000">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Recipient:</label>
                                        <input type="text" class="form-control" id="recipient-name">
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Message:</label>
                                        <textarea class="form-control" id="message-text"></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Send message</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-body text-center trip-body">

                    <div class="row">

                        <div class="col-md-8 p-0 segment-item">

                            <div class="card trip_info">
                                <div class="trip_heading" style="border-bottom: 1px solid #c8ccd5;">
                                    <div class="row">
                                        <div class="col-8" style="padding-left:0;magin-left:0">
                                            <h5 style="border:0">Attendees <span id="attendee_count"></span></h5>
                                        </div>
                                        <div class="col-4">
                                            <div class="start_a_plan_btn" style="text-align: right; margin-top:10px;">

                                                <a href="javascript:void(0)" id="status-update-btn" style="background-color:#f00;color: #fff;border-radius: 5px; padding: 6px 10px; font-size: 12px;font-weight: 500;line-height: 5px; text-decoration: none; text-transform: capitalize; display: inline; text-align: center; margin-left: auto;">
                                                    <span>Alert</span>
                                                </a>

                                            </div>
                                        </div>
                                    </div>

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
                                    <h5 style="border-bottom:0">Updates</h5>
                                </div>
                                <div>
                                    <div class="loading_screen" style="display: none;" id="update_loading">
                                        <div class="spinner-border text-primary"></div>
                                    </div>

                                    <div class="update_details" id="update_details">
                                        <div class="message_form" style="display:none"></div>
                                        <div class="statuses"></div>
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

                            <div class="card other_info" style="background: none">
                                <img src="<?=SITE;?>/images/comming-2025.png" />
                            </div>

                            <div class="card mt-3 other_info">
                                <div class="trip_heading document">
                                    <h5 style="color:#4a6875;font-weight: normal">Your Private Plan Notes</h5>
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
                                                <div class="input-group comment-group" style="background: none; border:0">
                                                    <input type="text" class="form-control comment-field" style="border-radius: 5px;border:solid 1px #dddddd; color: #999;font-size:14px" name="commentfield" id="commentfield" placeholder="Enter Note">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default comment-action" style="background-color: #f8bb4f;padding:.375rem .75rem;margin-left:9px" type="submit"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
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
    <script type="text/javascript" src="<?= SITE ?>dashboard/js/main.js?v=202292"></script>
    <script src="<?= SITE ?>dashboard/js/jquery-ui.js"></script>
    <script src="<?= SITE ?>/js/dashboard_next.js?v=<?= time(); ?>"></script>
    

    <script>
        let initial_message_count = 0;
        
        $(function() {
            var dataSet = 'request_id=' + "planiversity";
            messageNotificationProcess(dataSet);
        });
        
        setInterval(function() {
            var dataSet = 'request_id=' + "planiversity";
            messageNotificationProcess(dataSet);
        }, 8000);    
        

        function notificationSoundProcess(recent_count) {
            if (initial_message_count < recent_count) {

                $('#chatAudio')[0].play().catch(function(error) {
                    console.log("Chrome cannot play sound without user interaction first")
                });

            }
            initial_message_count = recent_count;
            console.log('initial_message_count-2', initial_message_count);
        }        

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
                        notificationSoundProcess(response.data.results);
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
            const handleClick = (selector, buttonClass, schoolValue, modeValue) => {
                $(selector).click(function() {
                    $(buttonClass).click();
                    classToggle(this);

                    const valuePass = {
                        school: schoolValue,
                        mode: modeValue,
                    };
                    localStorageValue(valuePass);
                });
            };

            handleClick(".trips", ".fc-trips-button", 3, 'trips');
            handleClick(".events", ".fc-events-button", 2, 'events');
            handleClick(".meetings", ".fc-meetings-button", 3, 'meetings');
            handleClick(".jobs", ".fc-jobs-button", 4, 'jobs');
            handleClick(".appointments", ".fc-appointments-button", 5, 'appointments');
            handleClick(".all", ".fc-alls-button", 'all', 'all');

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
            
            $('#cropImagePop').on('shown.bs.modal', function () {
                $uploadCrop.croppie('bind', {
                    url: rawImg
                }).then(function () {
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
                            
                            $(".profile_picture_place").attr("src",data);
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