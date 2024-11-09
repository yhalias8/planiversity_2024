<?php
session_start();
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/create-timeline/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}

$output = '';
include("class/class.TripPlan.php");
$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
if (empty($id_trip)) header("Location:" . SITE . "trip/how-are-you-traveling");
$trip->get_data($id_trip);
if ($trip->error) {
    if ($trip->error == 'error_access') { // popup and
        header("Location:" . SITE . "trip/how-are-you-traveling");
        //$output = 'You do not have access to this trip';
    } else
        $output = 'A system error has been encountered. Please try again.';
}
$transport = (isset($trip->trip_transport) && !empty($trip->trip_transport)) ? $trip->trip_transport : '';
$tmp = str_replace('(', '', $trip->trip_location_from_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp = str_replace(')', '', $tmp);
$tmp = explode(',', $tmp);
$lat_from = $tmp[0];
$lng_from = $tmp[1];
$tmp = str_replace('(', '', $trip->trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp = str_replace(')', '', $tmp);
$tmp = explode(',', $tmp);
$lat_to = $tmp[0];
$lng_to = $tmp[1];

$travelmode = 'DRIVING';
switch ($transport) {
    case 'vehicle' :
        $travelmode = 'DRIVING';
        break;
    case 'train'   :
        $travelmode = 'TRANSIT';
        break;
}

if (isset($_POST['timeline_submit'])) {
    header("Location:" . SITE . "trip/plan-notes/" . $id_trip);
    /*//$filter = $_POST['filter_option'];
    $embassis = $_POST['embassy_list'];
    // edit data trip in DB
    $trip->edit_data_filter($id_trip,$filter,$embassis);
    if (!$trip->error)
       header("Location:".SITE."trip/plan-notes/".$id_trip);
    else
       $output = $trip->error;
       //$output = 'A system error has been encountered. Please try again.'; */
}

include('include_doctype.php');
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]>
<html lang="en" class="ie6"> <![endif]-->
<!--[if IE 7 ]>
<html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8 ]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9 ]>
<html lang="en" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PLANIVERSITY - TIMELINE</title>
    <meta name="description"
          content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">

    <link href="<?php echo SITE; ?>assets_itinerary/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo SITE; ?>assets_itinerary/css/icons.css" rel="stylesheet" type="text/css"/>

    <link href="<?php echo SITE; ?>assets_itinerary/css/jquery.datetimepicker.css" rel="stylesheet"
          type="text/css">

    <link href="<?php echo SITE; ?>assets_itinerary/css/app-style.css" rel="stylesheet"
          type="text/css"/>


    <script src="<?php echo SITE; ?>assets_itinerary/js/modernizr.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">
            <div class="logo">
                <a href="" class="logo">
                    <span class="logo-small"><img
                                src="<?php echo SITE; ?>assets_itinerary/images/logo-icon.png"
                                alt="logo icon"></span>
                    <span class="logo-large"><img
                                src="<?php echo SITE; ?>assets_itinerary/images/logo-icon.png"
                                alt="logo icon"><span>Planiversity</span></span>
                </a>
            </div>
            <div class="menu-extras topbar-custom">
                <ul class="list-inline float-right mb-0">
                    <li class="menu-item list-inline-item">
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                    </li>
                    <li class="menu-item list-inline-item">
                        <a class="nav-link" href="" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            Admin
                        </a>
                    </li>
                    <li class="list-inline-item dropdown more-nav-list">
                        <a class="nav-link dropdown-toggle arrow-none link-drop" data-toggle="dropdown" href=""
                           role="button"
                           aria-haspopup="false" aria-expanded="false">
                            Menu
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg"
                             aria-labelledby="Preview">
                            <a href="" class="dropdown-item drop-menu-item">
                                About Us
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                What It Costs
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                Make a Payment
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                FAQs
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                Data Security
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                Contact Us
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                Blog
                            </a>
                        </div>
                    </li>
                    <li class="list-inline-item dropdown more-nav-list">
                        <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src="<?php echo SITE; ?>assets_itinerary/images/avatar-1.jpg"
                                 alt="user" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                            <div class="dropdown-item noti-title">
                                <h5 class="text-overflow">
                                    <small class="text-white">Eric</small>
                                </h5>
                            </div>
                            <a href="" class="dropdown-item drop-menu-item">
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu text-center">
                    <li class="box">
                        <a href="" class="left-nav-button top-progress active-step scale" data-toggle="modal"
                           data-target="#schedule-modal2" data-backdrop="false">
                            <img src="<?php echo SITE; ?>assets_itinerary/images/step_calendar.png"
                                 alt="Schedule">
                            <p>Schedule</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress" data-toggle="modal" data-target="#note-modal">
                            <img src="<?php echo SITE; ?>assets_itinerary/images/step_notes.png"
                                 alt="Notes">
                            <p>Notes</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress">
                            <img src="<?php echo SITE; ?>assets_itinerary/images/step_filters.png"
                                 alt="Filters">
                            <p>Filters</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress">
                            <img src="<?php echo SITE; ?>assets_itinerary/images/step_documents.png"
                                 alt="Documents">
                            <p>Documents</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress" data-toggle="modal"
                           data-target="#connect-modal">
                            <img src="<?php echo SITE; ?>assets_itinerary/images/step_connect_sources.png"
                                 alt="Documents">
                            <p>Connect</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress">
                            <img src="<?php echo SITE; ?>assets_itinerary/images/step_pdf.png"
                                 alt="Export">
                            <p>Export</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<div class="wrapper solid-background">
    <div class="container-fluid">

    </div>
</div>
<div id="schedule-modal2" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-white">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myLargeModalLabel">Create Schedule</h4>
            </div>
            <div class="modal-body">
                <fieldset>
                    <div class="row">
                        <div class="col-md-12 col-lg-8">
                            <div class="form-group">
                                <input type="text" class="dashboard-form-control input-lg" placeholder="Add New Event"
                                       required="">
                            </div>
                            <div class="calender-wrapper">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4">
                                        <div class="cal-time-wrap">
                                            <h5>2019</h5>
                                            <h2>Sun, Dec 1</h2>

                                            <div class="time-input">
                                                <input type="time" class="form-control timer-input" tabindex="53"
                                                       placeholder="1 : 30 AM">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-8">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-4">
                            <div class="note-info-wrapper note-info-wrapper-white">
                                <h3>Why Create a Timeline</h3>
                                <p>Just as you would use the calendar feature on your phone or computer, keeping
                                    yourself reminded of meetings, scheduled events, or just to remember when you want
                                    to be somewhere at a specific time is key. The best part about the timeline is that
                                    once you do create it, you will have it kept in the same location as the rest of
                                    your trip information. And if you happen to share your plan with others, you will
                                    all be on the same page. Sounds like you're a real pro!</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-10">
                            <div class="form-group">
                                <button type="submit" class="refresh-btn">Finished, Next Step</button>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-2">
                            <a href="" class="skip-note-btn">Skip Section</a>
                        </div>
                    </div>
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <p class="footer-text">&copy; Copyright. 2015 -
                    <script>document.write(new Date().getFullYear())</script>
                    Planiversity, LLC. All Rights Reserved.
                </p>
            </div>
        </div>
    </div>
</footer>
<script src="<?php echo SITE; ?>assets_itinerary/js/jquery.min.js"></script>

<!-- <script src="<?php echo SITE; ?>assets_itinerary/js/jquery-3.2.1.slim.min.js"></script> -->

<!-- <script src="<?php echo SITE; ?>assets_itinerary/js/jquery.datetimepicker.full.min.js"></script> -->

<script src="<?php echo SITE; ?>assets_itinerary/js/moment.min.js"></script>

<script src="<?php echo SITE; ?>assets_itinerary/js/bootstrap-datepicker.js"></script>


<script src="<?php echo SITE; ?>assets_itinerary/js/popper.min.js"></script>
<script src="<?php echo SITE; ?>assets_itinerary/js/bootstrap.min.js"></script>
<script src="<?php echo SITE; ?>assets_itinerary/js/jquery.slimscroll.js"></script>

<script src="<?php echo SITE; ?>assets_itinerary/js/jquery.core.js"></script>
<script src="<?php echo SITE; ?>assets_itinerary/js/jquery.app.js"></script>


<script type="text/javascript">
    function toggle_edit_form(id) {
        var e = document.getElementById(id);
        if (e.style.display == 'block')
            e.style.display = 'none';
        else
            e.style.display = 'block';
    }

    $('#calendar').datepicker({inline: true});
</script>
</body>
</html>