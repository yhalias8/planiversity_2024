<?php
session_start();
include_once("config.ini.php");
include_once("config.ini.curl.php");


if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/event-itinerary';
    header("Location:" . SITE . "login");
}

$transport = "vehicle";

$trip = '';
$travelmode = 'DRIVING';
$label = 'Flight';
$trip_label = 'flight';
$label_station = 'Airport';
$vihicle_place_holder = "";
$multiCityLabel = "Multi City";
$oneWayLabel = "One Way";
$extra_portion = "drive or train";

switch ($transport) {
    case 'vehicle':
        $travelmode = 'DRIVING';
        $label = 'Vehicle';
        $trip_label = 'car';
        $label_station = 'Point';
        $vihicle_place_holder = "Enter Final Destination";
        $multiCityLabel = "Multiple Stops (Road Trip)";
        $oneWayLabel = "From A to B";
        $extra_portion = "plane or train";
        break;
    case 'train':
        $travelmode = 'TRANSIT';
        $label = 'Train';
        $trip_label = 'train';
        $label_station = 'Railway Station';
        $oneWayLabel = "One Way";
        $extra_portion = "drive or plane";
        break;
}

$emp = $dbh->prepare("SELECT a.id_employee as option_id ,CONCAT(a.f_name,' ',a.l_name) as option_name  FROM employees as a, users as b WHERE a.employee_id = b.customer_number AND a.id_user = ? ORDER BY f_name");
$emp->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$emp_tmp = $emp->execute();
$employees = array(); // Initialize an array to hold the results

if ($emp_tmp && $emp->rowCount() > 0) {
    $employees = $emp->fetchAll(PDO::FETCH_OBJ);
}

// Convert the PHP array to JSON
$employeeJson = json_encode($employees);


$grp = $dbh->prepare("SELECT DISTINCT a.id as option_id ,a.group_name as option_name FROM travel_groups as a, employees as b WHERE a.id = b.travel_group AND a.user_id = ? ORDER BY id");
$grp->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$grp_tmp = $grp->execute();
$groups = array(); // Initialize an array to hold the results

if ($grp_tmp && $grp->rowCount() > 0) {
    $groups = $grp->fetchAll(PDO::FETCH_OBJ);
}

// Convert the PHP array to JSON
$groupsJson = json_encode($groups);

include('include_doctype.php');

?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <title>PLANIVERSITY - TO AND FROM</title>
    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>

    <script>
        var SITE = '<?php echo SITE; ?>'
    </script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?php echo SITE; ?>assets/css/multi-form.css?v=20230621" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/ui-kit.css?v=20230621" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/app-style.css?20230621" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/@duffel/components@latest/dist/CardPayment.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <script src="<?php echo SITE; ?>js/jquery.validate.min.js"></script>
    <script src="<?php echo SITE; ?>js/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/multi-form-event.js?v=20230218"></script>
    <script src="<?php echo SITE; ?>js/utils/map-marker.js?v=20230218"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="<?= SITE; ?>js/global.js?v=20230518"></script>

    <style>
        .menu_checkbox {
            justify-content: center;
        }

        .people_section {
            height: auto;
        }

        .modaltrans {
            width: 350px;
            overflow: hidden !important;
            max-height: 300px;
            padding-left: 0px !important;
            margin-left: 14px;
        }

        .datepicker td,
        .datepicker th {
            text-align: center;
        }

        .modaltrans-body {
            transform: scale(0.25) translate(-151%, -151%);
            width: 400%;
        }

        .modaltrans-body-mozila {
            transform: scale(0.2) translate(-200%, -200%);
            width: 500%;
        }

        .finish-next-btn:hover {
            color: #FFF !important;
        }

        .algolia-places .ap-input-icon {
            margin-top: 16px;
        }

        .list-inline {
            display: none;
        }

        .right-side-link {
            display: inherit;
        }

        .affiliate_link {
            margin-left: 50px;
            position: relative;
            top: 3px;
        }

        .affiliate_link p {
            font-size: 12px;
            color: #000;
        }

        .affiliate_link p span {
            color: #048cf2;
        }

        .affiliate_link p span:hover {
            color: #048cf9;
        }

        .close-icon {
            border: 1px solid transparent;
            background-color: transparent;
            display: inline-block;
            vertical-align: middle;
            outline: 0;
            cursor: pointer;
        }

        .close-icon:after {
            content: "X";
            display: none;
            width: 15px;
            height: 18px;
            position: absolute;
            color: #000000;
            z-index: 1;
            right: 25px;
            top: 0;
            bottom: 8px;
            margin: auto;
            text-align: center;
            font-weight: normal;
            font-size: 18px;
            cursor: pointer;
        }

        .clearable:valid~.close-icon::after {
            display: block !important;
        }

        .clearable:valid~.close-icon::after {
            display: block !important;
        }

        button.close-icon.all-portion.portion:after {
            bottom: 35px;
        }

        .action_option {
            width: 100%;
            text-align: center;
        }

        .action-section {
            display: inline-block;
            position: relative;
            padding-left: 35px;
            padding-right: 20px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 18px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default radio button */
        .action-section input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Create a custom radio button */
        .checkmark {
            position: absolute;
            top: 2;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 50%;
        }

        /* On mouse-over, add a grey background color */
        .action-section:hover input~.checkmark {
            background-color: #ccc;
        }

        /* When the radio button is checked, add a blue background */
        .action-section input:checked~.checkmark {
            background-color: #2196F3;
        }

        /* Create the indicator (the dot/circle - hidden when not checked) */
        .action-section:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the indicator (dot/circle) when checked */
        .action-section input:checked~.checkmark:after {
            display: block;
        }

        /* Style the indicator (dot/circle) */
        .action-section .checkmark:after {
            top: 5px;
            left: 5px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: white;
        }

        .extra_input {
            width: auto;
            font-size: 17px;
            border: 1px solid #aaaaaa;
        }

        span.label_info {
            font-weight: 300;
        }

        .flyover {
            position: absolute;
            z-index: 999999;
            bottom: 100px;
            right: 100px;
        }

        .flyover_undo {
            background: #233178;
            color: #fff;
            font-weight: 400;
            border: 3px solid #fff;
        }

        .flyover_save {
            background: #FFC90D;
            color: #493B4A;
            font-weight: 500;
            border: 3px solid #fff;
            width: 200px;
        }

        .special-date {
            color: #F0AD4E !important;
            border-radius: 0 !important;
            border-bottom: 5px solid #F0AD4E !important;
            text-decoration: line-through;
            background: unset !important;
            text-shadow: unset !important;
            font-size: 20px !important;
        }

        .special-date:hover {
            background: #d1d1d1 !important;
        }

        div#schedule-modal-body {
            background-size: 80% !important;
        }
    </style>
</head>

<body class="custom_toandfrom">

    <!--<div class="content">-->

    <?php
    //include('include_header.php')
    include('new_backend_header.php');
    include('new_backend_footer.php');
    ?>
    <?php include_once('includes/top_bar.php'); ?>
    </header>




    <div data-backdrop="false" id="schedule-modal" class="modal fade bs-example-modal-lg modal-to-and-from toandfrom pt-3 location_form_1 create_itinerary" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;top: 105px">

        <div class="modal-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-0">
            <div class="modal-content modal-content-white">
                <div class="modal-header pt-2 itinerary-header">
                    <button type="button" class="close" aria-hidden="true">-</button>

                    <div class="heading_section">
                        <p>PLANVERSITY</p>
                        <h4 class="modal-title" id="myLargeModalLabel">Build Your Event Itinerary</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <div class="trip-navigation">

                    <div class="col-md-12 text-center">

                        <div class="row">
                            <div class="trip-item active">
                                <a href="<?= SITE ?>trip/itinerary-option">
                                    <div class="">
                                        <img src="<?= SITE ?>assets/images/schedule_color.png" class="">
                                    </div>
                                    <p class="text-center trip-mode-text">Event</p>
                                </a>
                            </div>
                            <div class="trip-item">
                                <a href="<?= SITE ?>trip/how-are-you-traveling">
                                    <div class="">
                                        <img src="<?= SITE ?>assets/images/travel_color.png" class="">
                                    </div>
                                    <p class="text-center trip-mode-text">Trip</p>
                                </a>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="modal-body connect-bg-ground" id="schedule-modal-body">



                    <fieldset id="build_place">
                        <form id="myForm" action="#" method="POST" class="main_form">

                            <div class="itinerary_tab">
                                <div class="itinerary_section">
                                    <h3>Connect feature</h3>
                                    <p>Select the group or the individuals who will be attached to temployeeshis plan. <br> Don`t worry, you can always add more later.</p>
                                    <div class="itinerary_content">
                                        <input name="people_mode" id="people_mode" value="People" type="hidden">
                                        <div id="added_people">

                                        </div>


                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">

                                                    <div class="row">

                                                        <div class="col-12 mb-6">

                                                            <div class="mx-auto menu_checkbox">
                                                                <span>Group</span>
                                                                <input type="checkbox" name="switch" id="switch" checked value="1">
                                                                <span>People</span>
                                                            </div>

                                                        </div>


                                                        <div class="col-12 col-lg-6 mx-auto text-left">


                                                            <p class="event-title pb-0">Add profile </p>

                                                            <div class="people_place d-flex g-2">

                                                                <div class="form-group people-field">
                                                                    <select autofocus name="profile_employee" id="profile_employee" class="dashboard-form-control input-lg">
                                                                    </select>

                                                                    <label id="profile_employee-error" class="error profile_employee_error" for="profile_employee" style="display: none;">
                                                                        Please select people profile
                                                                    </label>
                                                                </div>

                                                                <div class="form-group ml-2">
                                                                    <button type="submit" class="btn btn-secondary people-action">
                                                                        Add
                                                                    </button>
                                                                </div>

                                                            </div>


                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 col-lg-6 mx-auto">
                                                            <div class="people_section">


                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Will the event be at more the one location?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="location_count_one" name="location_count_option" value="yes" class="regular-checkbox big-checkbox" /><label for="location_count_one"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="location_count_multiple" name="location_count_option" value="no" class="regular-checkbox big-checkbox" /><label for="location_count_multiple"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="location_count_step-error" class="mvalidy error location_count_step_error" for="location_count_step"></label>
                                                </div>
                                                <input type="text" name="location_count_step" id="location_count_step" class='input_option_opacity input_reset' readonly>

                                            </div>
                                        </div>
                                    </div>



                                    <div class="location_multiple_section mt-5 pb-3" style="display:none;">
                                        <h3>How many different location will there be?</h3>

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-12 col-lg-6 mx-auto">
                                                    <div>
                                                        <input type="number" min="1" max="5" name="location_count" id="location_count" class="dashboard-form-control w-100 form-control input-lg clearable" placeholder="Enter Location Count">
                                                    </div>
                                                    <div class="location_inputs text-left mt-4">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="location_one_section mt-5 pb-3" style="display:none;">

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-12 col-lg-6 mx-auto">
                                                    <div>
                                                        <label class="mt-2 mr-b-10">Enter your event location</label>
                                                        <input type="text" class="dashboard-form-control one-location-input form-control input-lg clearable" data-event-location="1" placeholder="Enter Location">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location Count Inputs -->
                                    <div>
                                        <input type="text" class="input_option_opacity" value="" name="event_location_1" id="event_location_1">
                                        <input type="hidden" value="" name="event_location_2" id="event_location_2">
                                        <input type="hidden" value="" name="event_location_3" id="event_location_3">
                                        <input type="hidden" value="" name="event_location_4" id="event_location_4">
                                        <input type="hidden" value="" name="event_location_5" id="event_location_5">
                                    </div>

                                </div>
                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">

                                    <h3>How long is your event?</h3>
                                    <p>Add details for your event schedule.</p>
                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="event_option_one" name="event_option" value="one" class="regular-checkbox big-checkbox" /><label for="event_option_one"></label>
                                                        <p>One Day</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="event_option_two" name="event_option" value="multiple" class="regular-checkbox big-checkbox" /><label for="event_option_two"></label>
                                                        <p>Multiple Days</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="event_option_value-error" class="mvalidy error event_option_value_error" for="event_option_value"></label>
                                                </div>
                                                <input type="text" name="event_option_value" id="event_option_value" class='input_option_opacity input_reset' readonly>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="single_event_section pt-5" style="display:none;">
                                        <h3>What is the date of your event?</h3>
                                        <p>Enter your date and time for your upcomming event?</p>

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">

                                                    <div class="options option-padding-60 text-left">

                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Date of event <span class="label_info">(optional)</span></label>
                                                                    <div class="date_picker_item">
                                                                        <input type="text" class="dashboard-form-control input-lg date_calculation" placeholder="Date of event" name="location_datel" autocomplete="off" id="location_datel" data-date-format="mm/dd/yyyy">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Starting time (HH:MM) <span class="label_info">(optional)</span></label>
                                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Starting time" name="location_datel_deptime" autocomplete="off" id="location_datel_deptime">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 col-lg-4">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Ending time (HH:MM) <span class="label_info">(optional)</span></label>
                                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Ending time" name="location_dater_deptime" autocomplete="off" id="location_dater_deptime">
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="multiple_event_section pt-5" style="display:none;">
                                        <h3>What is the dates of your event?</h3>
                                        <p>Enter your dates and times for your upcomming event?</p>

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">

                                                    <div class="options option-padding-60 text-left">

                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-3">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Starting date <span class="label_info">(optional)</span></label>
                                                                    <div class="date_picker_item">
                                                                        <input type="text" class="dashboard-form-control start-date input-lg date_calculation" placeholder="Starting date" name="location_datel_m" autocomplete="off" id="location_datel_m" data-date-format="mm/dd/yyyy">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-3">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Starting time (HH:MM) <span class="label_info">(optional)</span></label>
                                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Starting time" name="location_datel_deptime_m" autocomplete="off" id="location_datel_deptime_m">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 col-lg-3">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Ending Date <span class="label_info">(optional)</span></label>
                                                                    <div class="date_picker_item">
                                                                        <input type="text" class="dashboard-form-control end-date input-lg date_calculation" placeholder="Ending Date" name="location_dater" value="" autocomplete="off" id="location_dater" data-date-format="mm/dd/yyyy">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-3">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Ending time (HH:MM) <span class="label_info">(optional)</span></label>
                                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Ending time" name="location_dater_deptime_r" autocomplete="off" id="location_dater_deptime_r">
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

                            <div class="itinerary_tab">
                                <div class="itinerary_section">
                                    <h3>What is the location of your event?</h3>
                                    <p>Enter your location address point?</p>
                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options option-padding-160 text-left">

                                                    <div class="row">

                                                        <div class="col-md-12 col-lg-12">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Event <?php echo $label_station; ?></label>
                                                                <input type="text" name="location_from" id="location_from" class="dashboard-form-control form-control input-lg clearable" placeholder="Enter Event <?php echo $label_station; ?>">
                                                                <button class="close-icon from-part" type="button"></button>
                                                            </div>
                                                        </div>

                                                        <input name="location_to" id="location_to" class="inp1 input_reset" type="hidden" readonly>
                                                        <input name="location_from_latlng" id="location_from_latlng" class="inp1 input_reset" type="hidden" readonly>
                                                        <input name="location_to_latlng" id="location_to_latlng" class="inp1 input_reset" type="hidden" readonly>
                                                        <input name="location_multi_waypoint_latlng" id="location_multi_waypoint_latlng" class="inp1 input_reset" type="hidden" readonly>
                                                        <input name="via_waypoints" id="via_waypoints" class="input_reset" type="hidden">
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Do you want to add Hotel details?</h3>
                                    <p>Add hotel details for your event.</p>
                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="hotel_option_one" name="hotel_option" value="yes" class="regular-checkbox big-checkbox" /><label for="hotel_option_one"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="hotel_option_two" name="hotel_option" value="no" class="regular-checkbox big-checkbox" /><label for="hotel_option_two"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="hotel_option_value-error" class="mvalidy error hotel_option_value_error" for="hotel_option_value"></label>
                                                </div>
                                                <input type="text" name="hotel_option_value" id="hotel_option_value" class='input_option_opacity input_reset' readonly>

                                            </div>
                                        </div>
                                    </div>



                                    <div class="hotel_room_book_section mt-5 pb-3" style="display:none;">
                                        <h3>Do you need to book a hotel room?</h3>
                                        <p>You can book a hotel directly on Planiversity..</p>
                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">

                                                    <div class="options">
                                                        <div class="option-control pr-5">
                                                            <input type="checkbox" id="hotel_room_one" name="hotel_room_option" value="yes" class="regular-checkbox big-checkbox" /><label for="hotel_room_one"></label>
                                                            <p>Yes</p>
                                                        </div>

                                                        <div class="option-control">
                                                            <input type="checkbox" id="hotel_room_two" name="hotel_room_option" value="no" class="regular-checkbox big-checkbox" /><label for="hotel_room_two"></label>
                                                            <p>No</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="hotel_booking_section mt-1 mb-2" style="display:none;">

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">

                                                    <script src="//tp.media/content?promo_id=2693&shmarker=311162&campaign_id=84&trs=20140&locale=en&hotel_type=&border_radius=5&plain=false&powered_by=false" charset="utf-8"></script>

                                                </div>
                                            </div>


                                        </div>

                                    </div>


                                    <div class="hotel_entry_section mt-5" style="display:none;">
                                        <h3>Please add your hotel details</h3>
                                        <p>Enter your hotel details</p>
                                        <div class="itinerary_content">

                                            <div class="row text-left">
                                                <div class="col-lg-12 col-xl-12">

                                                    <div class="row">

                                                        <div class="col-md-12 col-lg-4">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Hotel Name</label>
                                                                <input type="text" name="hotel_name" id="hotel_name" class="dashboard-form-control form-control input-lg clearable">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-lg-4">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Date of Check In</label>
                                                                <input type="text" name="hotel_date_checkin" id="hotel_date_checkin" class="clearable dashboard-form-control input-lg date_calculation" data-date-format="mm/dd/yyyy">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 col-lg-4">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Check Out</label>
                                                                <input type="text" name="hotel_date_checkout" id="hotel_date_checkout" class="clearable dashboard-form-control input-lg date_calculation" data-date-format="mm/dd/yyyy">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-12 col-lg-12">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Hotel Address</label>
                                                                <input type="text" name="hotel_address" id="hotel_address" class="dashboard-form-control form-control input-lg clearable">
                                                                <button class="close-icon all-portion" type="button" data-target="hotel_address"></button>
                                                            </div>
                                                        </div>

                                                        <input name="location_portion_to_latlng" id="location_portion_to_latlng" class="inp1 input_reset" type="hidden" readonly>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Will the event be at more the one location?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="location_count_one" name="location_count_option" value="yes" class="regular-checkbox big-checkbox" /><label for="location_count_one"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="location_count_multiple" name="location_count_option" value="no" class="regular-checkbox big-checkbox" /><label for="location_count_multiple"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="location_count_step-error" class="mvalidy error location_count_step_error" for="location_count_step"></label>
                                                </div>
                                                <input type="text" name="location_count_step" id="location_count_step" class='input_option_opacity input_reset' readonly>

                                            </div>
                                        </div>
                                    </div>



                                    <div class="location_multiple_section mt-5 pb-3" style="display:none;">
                                        <h3>How many different location will there be?</h3>

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-12 col-lg-6 mx-auto">
                                                    <div>
                                                        <input type="number" min="1" max="5" name="location_count" id="location_count" class="dashboard-form-control w-100 form-control input-lg clearable" placeholder="Enter Location Count">
                                                    </div>
                                                    <div class="location_inputs text-left mt-4">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="location_one_section mt-5 pb-3" style="display:none;">

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-12 col-lg-6 mx-auto">
                                                    <div>
                                                        <label class="mt-2 mr-b-10">Enter your event location</label>
                                                        <input type="text" class="dashboard-form-control one-location-input form-control input-lg clearable" data-event-location="1" placeholder="Enter Location">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location Count Inputs -->
                                    <div>
                                        <input type="text" class="input_option_opacity" value="" name="event_location_1" id="event_location_1">
                                        <input type="hidden" value="" name="event_location_2" id="event_location_2">
                                        <input type="hidden" value="" name="event_location_3" id="event_location_3">
                                        <input type="hidden" value="" name="event_location_4" id="event_location_4">
                                        <input type="hidden" value="" name="event_location_5" id="event_location_5">
                                    </div>

                                </div>
                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Do you want to add car rental details?</h3>
                                    <p>Add car rental details for your event</p>
                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="car_option_one" name="car_option" value="yes" class="regular-checkbox big-checkbox" /><label for="car_option_one"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="car_option_two" name="car_option" value="no" class="regular-checkbox big-checkbox" /><label for="car_option_two"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="car_option_value-error" class="mvalidy error car_option_value_error" for="car_option_value"></label>
                                                </div>
                                                <input type="text" name="car_option_value" id="car_option_value" class='input_option_opacity input_reset' readonly>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="car_rental_book_section mt-5 pb-5" style="display:none;">
                                        <h3>Do you need to book car rental?</h3>
                                        <p>You can book car rentall directly on Planiversity.</p>
                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">
                                                    <div class="options">
                                                        <div class="option-control pr-5">
                                                            <input type="checkbox" id="car_rental_option_one" name="car_rental_option" value="yes" class="regular-checkbox big-checkbox" /><label for="car_rental_option_one"></label>
                                                            <p>Yes</p>
                                                        </div>

                                                        <div class="option-control">
                                                            <input type="checkbox" id="car_rental_option_two" name="car_rental_option" value="no" class="regular-checkbox big-checkbox" /><label for="car_rental_option_two"></label>
                                                            <p>No</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="car_booking_section mt-1 mb-2" style="display:none;">

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">

                                                    <script src="//tp.media/content?promo_id=4578&shmarker=311162&campaign_id=130&trs=171390&locale=en&powered_by=false&border_radius=5&plain=false&show_logo=false&color_background=%23f5d361&color_button=%235a9854" charset="utf-8"></script>

                                                </div>
                                            </div>


                                        </div>

                                    </div>


                                    <div class="car_entry_section mt-5" style="display:none;">
                                        <h3>Please add your car rental details</h3>
                                        <p>Enter your car rental details</p>
                                        <div class="itinerary_content">

                                            <div class="row text-left">
                                                <div class="col-lg-12 col-xl-12">

                                                    <div class="row">

                                                        <div class="col-md-12 col-lg-4">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Rental Agency</label>
                                                                <input type="text" name="rental_agency" id="rental_agency" class="dashboard-form-control form-control input-lg clearable">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-lg-4">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Date of pick up</label>
                                                                <input type="text" name="rental_date_pick" id="rental_date_pick" class="clearable dashboard-form-control input-lg date_calculation" data-date-format="mm/dd/yyyy">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 col-lg-4">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Date of drop off</label>
                                                                <input type="text" name="rental_date_drop" id="rental_date_drop" class="clearable dashboard-form-control input-lg date_calculation" data-date-format="mm/dd/yyyy">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-12 col-lg-12">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Rental Agency Address</label>
                                                                <input type="text" name="rental_agency_address" id="rental_agency_address" class="dashboard-form-control form-control input-lg clearable">
                                                                <button class="close-icon all-portion" type="button" data-target="rental_agency_address"></button>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Do you want to add a driving, <?= $extra_portion; ?> portion?</h3>
                                    <p>This will take you from your event location to another location.</p>
                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="drive_option_one" name="drive_option" value="yes" class="regular-checkbox big-checkbox" /><label for="drive_option_one"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="drive_option_two" name="drive_option" value="no" class="regular-checkbox big-checkbox" /><label for="drive_option_two"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="drive_option_value-error" class="mvalidy error drive_option_value_error" for="drive_option_value"></label>
                                                </div>
                                                <input type="text" name="drive_option_value" id="drive_option_value" class='input_option_opacity input_reset' readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="drive_entry_section mt-5" style="display:none;">
                                        <h3>Please add your final destination details</h3>
                                        <p>Enter your details</p>
                                        <div class="itinerary_content">

                                            <div class="row text-left">
                                                <div class="col-lg-12 col-xl-12">



                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="form-group text-center mb-10">
                                                                <label><a href="#" onclick="toggle_visibility('driving');" class="outline-btn">
                                                                        <i class="fa fa-plus plus-icon color-black"></i>Add Driving Portion</a>
                                                                </label>
                                                            </div>

                                                            <div id="driving" style="display:none;">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group frm-grp">
                                                                            <input name="location_from_drivingportion" id="location_from_drivingportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Driving Start">
                                                                            <button class="close-icon all-portion portion" type="button" data-target="location_from_drivingportion"></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group frm-grp">
                                                                            <input name="location_to_drivingportion" id="location_to_drivingportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Driving Destination">
                                                                            <button class="close-icon all-portion portion" type="button" data-target="location_to_drivingportion"></button>
                                                                        </div>
                                                                    </div>
                                                                    <input name="location_from_latlng_drivingportion" id="location_from_latlng_drivingportion" class="inp1 input_reset" type="hidden" readonly>
                                                                    <input name="location_to_latlng_drivingportion" id="location_to_latlng_drivingportion" class="inp1 input_reset" type="hidden" readonly>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="form-group text-center mb-10">
                                                                <label class="add_train_portion_heading mt-3"><a href="#" onclick="toggle_visibility('flight');" class="outline-btn">
                                                                        <i class="fa fa-plus plus-icon color-black"></i>Add a Flight Portion</a></label>
                                                                <div id="flight" style="display:none;">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group frm-grp">
                                                                                <input name="location_from_flightportion" id="location_from_flightportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Takeoff Location">
                                                                                <button class="close-icon all-portion portion" type="button" data-target="location_from_flightportion"></button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group frm-grp">
                                                                                <input name="location_to_flightportion" id="location_to_flightportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Destination Location">
                                                                                <button class="close-icon all-portion portion" type="button" data-target="location_to_flightportion"></button>
                                                                            </div>
                                                                        </div>
                                                                        <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1 input_reset" type="hidden" readonly>
                                                                        <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1 input_reset" type="hidden" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="form-group text-center">
                                                                <label class="add_train_portion_heading mt-3 mb-3"><a href="#" onclick="toggle_visibility('train');" class="outline-btn">
                                                                        <i class="fa fa-plus plus-icon color-black"></i>Add a Train Portion</a></label>
                                                                <div id="train" style="display:none;">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group frm-grp">
                                                                                <input name="location_from_trainportion" id="location_from_trainportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Takeoff Location">
                                                                                <button class="close-icon all-portion portion" type="button" data-target="location_from_trainportion"></button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group frm-grp">
                                                                                <input name="location_to_trainportion" id="location_to_trainportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Destination Location">
                                                                                <button class="close-icon all-portion portion" type="button" data-target="location_to_trainportion"></button>
                                                                            </div>
                                                                        </div>
                                                                        <input name="location_from_latlng_trainportion" id="location_from_latlng_trainportion" class="inp1 input_reset" type="hidden" readonly>
                                                                        <input name="location_to_latlng_trainportion" id="location_to_latlng_trainportion" class="inp1 input_reset" type="hidden" readonly>
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
                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Your event information is nearly complete.</h3>
                                    <h6>Minimize this slide to confirm your location</h6>
                                    <p>Ready to plan a event?</p>
                                    <div class="itinerary_content">

                                    </div>
                                </div>

                                <input name="transport" value="<?= $transport; ?>" type="hidden" readonly>
                                <input name="location_triptype" value="<?= 'o' ?>" type="hidden" readonly>

                            </div>

                            <!-- Circles which indicates the steps of the form: -->
                            <div class="step_section">
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                                <span class="step"></span>
                            </div>

                            <div style="overflow:auto;">
                                <div class="action_section">
                                    <div class="left-side">
                                        <button type="button" class="action_button previous">Back</button>
                                        <button type="button" class="action_button start_over">Start Over</button>
                                    </div>

                                    <div class="right-side">
                                        <button type="button" class="action_button next">Next</button>
                                        <button type="button" class="action_button submit">Create Event</button>
                                    </div>
                                </div>
                            </div>


                        </form>

                    </fieldset>

                </div>


            </div>
        </div>

    </div>
    <br clear="all" />
    <div id="map"></div>


    <div class="flyover" style="display:none">
        <button type="button" class="btn btn-info flyover_save" id="flyover_save">Save</button>
    </div>

    <script>
        var transportWay = '<?php echo $transport; ?>';
        var employees = <?php echo $employeeJson; ?>;
        var groups = <?php echo $groupsJson; ?>;
        var userId = <?php echo  $userdata['id']; ?>;
    </script>
    <script>
        var val = {
            // Specify validation rules
            ignore: ':hidden:not(.option_validy)',
            rules: {
                location_count_step: {
                    required: true
                },
                event_location_1: {
                    required: true
                },
                event_option_value: {
                    required: true,
                },
                hotel_option_value: {
                    required: true,
                },
                car_option_value: {
                    required: true,
                },
                drive_option_value: {
                    required: true,
                },
                location_from: {
                    required: true,
                },
                location_to: {
                    required: true,
                },

            },
            // Specify validation error messages
            messages: {
                location_count_step: {
                    required: "Please select a option",
                },
                event_location_1: {
                    required: "Please fill required fields",
                },
                event_option_value: {
                    required: "Please select a option",
                },
                hotel_option_value: {
                    required: "Please select a option",
                },
                car_option_value: {
                    required: "Please select a option",
                },
                drive_option_value: {
                    required: "Please select a option",
                },
                location_from: {
                    required: "Please type your event location",
                },
                location_to: {
                    required: "Please type your destination location",
                }
            }
        }
        $("#myForm").multiStepForm({
            // defaultStep:0,
            beforeSubmit: function(form, submit) {
                console.log("called before submiting the form");
                console.log(form);
                console.log(submit);
            },
            validations: val,
        }).navigateTo(0);


        $('input[type="checkbox"][name=location_count_option]').on('change', function() {
            $('input[type="checkbox"][name=location_count_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=event_option]').on('change', function() {
            $('input[type="checkbox"][name=event_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=hotel_option]').on('change', function() {
            $('input[type="checkbox"][name=hotel_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=car_option]').on('change', function() {
            $('input[type="checkbox"][name=car_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=hotel_room_option]').on('change', function() {
            $('input[type="checkbox"][name=hotel_room_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=car_option]').on('change', function() {
            $('input[type="checkbox"][name=car_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=car_rental_option]').on('change', function() {
            $('input[type="checkbox"][name=car_rental_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=drive_option]').on('change', function() {
            $('input[type="checkbox"][name=drive_option]').not(this).prop('checked', false);
        });
    </script>

    <script>
        var infowindow1 = null;
        var marker_origin = null;
        var marker_destination = null;
        var flightPath = null;

        var map = null;
        var bounds = null;
        var directionsService = null;
        var directionsRenderer = null;
        var waypoints = [];
        mark_number = 0;

        function initMap() {
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                draggable: true,
                polylineOptions: {
                    strokeColor: "#0688E9"
                },
                suppressMarkers: true
            });
            map = new google.maps.Map(document.getElementById('map'), {
                mapTypeControl: false,
                center: {
                    lat: 40.730610,
                    lng: -73.968285
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: 7
            });
            directionsRenderer.setMap(map);

            new AutocompleteDirectionsHandler(map);
            // setupLocationAutocomplete($('.one-location-input'));


            var hotel_address = document.getElementById('hotel_address');
            var hotelAddressPlaceAutocomplete = new google.maps.places.Autocomplete(hotel_address);

            var rental_agency_address = document.getElementById('rental_agency_address');
            var rentalAgencyAddressPlaceAutocomplete = new google.maps.places.Autocomplete(rental_agency_address);

            this.hotelPlaceChangedListenerPortion(hotelAddressPlaceAutocomplete, 'DRIVING');


            $('.from-part').click(function() {
                initMap();
                $('#location_from').val('');
                $('#location_to').val('');
                $('#location_from_latlng').val('');
                $('#location_to_latlng').val('');
            });


        }


        function AutocompleteDirectionsHandler(map) {
            this.map = map;
            this.originPlaceId = this.origin_dportionPlaceId = null;
            this.originAddress = null;
            this.destinationPlaceId = this.destination_dportionPlaceId = null;
            this.originPlaceLocation = this.origin_dportionPlaceLocation = null;
            this.destinationPlaceLocation = this.destination_dportionPlaceLocation = null;
            this.wayptPlaceLocation = null;
            this.travelMode = '<?php echo $travelmode; ?>';
            var originInput = document.getElementById('location_from');
            var destinationInput = document.getElementById('location_to');

            switch (transportWay) {
                case "vehicle":
                    var inputType = null;
                    break;
                case "plane":
                    var inputType = "airport";
                    break;
                case "train":
                    var inputType = "train_station";
                    break;
                default:
                    var inputType = "";
                    break;
            }

            var originPlaceAutocomplete = new google.maps.places.Autocomplete(originInput, {
                types: [inputType]
            });

            var destPlaceAutocomplete = new google.maps.places.Autocomplete(destinationInput, {
                types: [inputType]
            });

            var me = this;


            this.directionsService = new google.maps.DirectionsService;
            this.directionsDisplay = new google.maps.DirectionsRenderer({
                draggable: true,
                polylineOptions: {
                    strokeColor: "#0688E9"
                },
                suppressMarkers: true
            });
            this.directionsDisplay.setMap(map);

            this.directionsDisplay1 = new google.maps.DirectionsRenderer({
                draggable: true,
                polylineOptions: {
                    strokeColor: "#0688E9"
                },
                suppressMarkers: true
            });
            this.directionsDisplay2 = new google.maps.DirectionsRenderer({
                draggable: true,
                polylineOptions: {
                    strokeColor: "#0688E9"
                },
                suppressMarkers: true
            });
            this.directionsDisplay1.setMap(map);
            this.directionsDisplay2.setMap(map);

            directionsRenderer.addListener("directions_changed", () => {
                var result = directionsRenderer.getDirections();
                var points_arr = [];
                var via_waypoints = [];
                for (var j = 0; j < result.routes[0].legs.length; j++) {
                    var way_point = result.routes[0].legs[j];

                    for (var k = 0; k < way_point.via_waypoint.length; k++) {
                        var vlat = way_point.via_waypoint[k].location.lat();
                        var vlng = way_point.via_waypoint[k].location.lng();
                        via_waypoints.push({
                            lat: vlat,
                            lng: vlng,
                            index: j
                        });
                    }
                    for (var i = 0; i < way_point.steps.length; i++) {
                        var lat = way_point.steps[i].end_point.lat();
                        var lng = way_point.steps[i].end_point.lng();
                        var lat_lng = {
                            'lat': lat,
                            'lng': lng
                        };
                        points_arr.push(lat_lng);
                    }
                }

                if (result.routes[0].legs.length > 0) {
                    // get miles
                    var center_point = result.routes[0].overview_path.length / 2;


                    if (infowindow1) {
                        infowindow1.close();
                    }


                    // infowindow1 = new google.maps.InfoWindow();
                    // infowindow1.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/car_icon.png'></div><div style='float:right; padding: 3px;'>" + calcTotalDistanceText(result) + "<br>" + calcTotalDurationText(result) + "</div>");
                    // infowindow1.setPosition(result.routes[0].overview_path[center_point | 0]);
                    // infowindow1.open(map);

                }
                via_waypoints = JSON.stringify(via_waypoints);
                $("#via_waypoints").val(via_waypoints);
            });

            this.setupAlgoliaPlaceChangedListener(originPlaceAutocomplete, 'ORIG');
            this.setupAlgoliaPlaceChangedListener(destPlaceAutocomplete, 'DEST');

            if (transportWay === 'train') {
                var originInput_dportion = document.getElementById('location_from_drivingportion');
                var destinationInput_dportion = document.getElementById('location_to_drivingportion');

                var origin_dportionAutocomplete = new google.maps.places.Autocomplete(originInput_dportion);

                var destination_dportionAutocomplete = new google.maps.places.Autocomplete(destinationInput_dportion);

                this.setupAlgoliaPlaceChangedListenerPortion(origin_dportionAutocomplete, 'ORIG', 'DRIVING');
                this.setupAlgoliaPlaceChangedListenerPortion(destination_dportionAutocomplete, 'DEST', 'DRIVING');
            } else {
                var originInput_tportion = document.getElementById('location_from_trainportion');
                var destinationInput_tportion = document.getElementById('location_to_trainportion');

                var origin_tportionAutocomplete = new google.maps.places.Autocomplete(originInput_tportion, {
                    types: ['train_station']
                });

                var destination_tportionAutocomplete = new google.maps.places.Autocomplete(destinationInput_tportion, {
                    types: ['train_station']
                });

                this.setupAlgoliaPlaceChangedListenerPortion(origin_tportionAutocomplete, 'ORIG', 'TRANSIT');
                this.setupAlgoliaPlaceChangedListenerPortion(destination_tportionAutocomplete, 'DEST', 'TRANSIT');
            }




            var originInput_dportion = document.getElementById('location_from_drivingportion');
            var destinationInput_dportion = document.getElementById('location_to_drivingportion');

            var origin_dportionAutocomplete = new google.maps.places.Autocomplete(originInput_dportion);

            var destination_dportionAutocomplete = new google.maps.places.Autocomplete(destinationInput_dportion);

            this.setupAlgoliaPlaceChangedListenerPortion(origin_dportionAutocomplete, 'ORIG', 'DRIVING');
            this.setupAlgoliaPlaceChangedListenerPortion(destination_dportionAutocomplete, 'DEST', 'DRIVING');


            var originInput_fportion = document.getElementById('location_from_flightportion');
            var destinationInput_fportion = document.getElementById('location_to_flightportion');


            var origin_fportionAutocomplete = new google.maps.places.Autocomplete(originInput_fportion, {
                types: ['airport']
            });

            var destination_fportionAutocomplete = new google.maps.places.Autocomplete(destinationInput_fportion, {
                types: ['airport']
            });


            this.setupAlgoliaPlaceChangedListenerPortion(origin_fportionAutocomplete, 'ORIG', 'PLANE');
            this.setupAlgoliaPlaceChangedListenerPortion(destination_fportionAutocomplete, 'DEST', 'PLANE');
        }


        AutocompleteDirectionsHandler.prototype.routeSplit = function(waypoints_value) {
            var waypoints_value = waypoints_value.splice(1);
            document.getElementById('via_waypoints').value = waypoints_value;
        }
        AutocompleteDirectionsHandler.prototype.routeSplitValueReturn = function(waypoints_value) {
            var waypoints_value_output = waypoints_value.splice(1);
            document.getElementById('via_waypoints').value = waypoints_value_output;
            var newArray = [];

            waypoints_value_output.forEach((element) => {
                newArray.push({
                    location: new google.maps.LatLng(element.lat, element.lng),
                    stopover: false
                });

            });
            return newArray;

        }

        function hotelPlaceChangedListenerPortion(autocomplete, ptype) {
            var me = this;

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();

                console.log('Place Changed ... ');

                if (place) {

                    document.getElementById('location_portion_to_latlng').value = place.geometry.location;

                }

            });

        };

        AutocompleteDirectionsHandler.prototype.setupPlaceChangedListenerPortion = function(autocomplete, mode, ptype) {
            var me = this;
            autocomplete.bindTo('bounds', this.map);
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.place_id) {
                    window.alert("Please select an option from the dropdown list.");
                    return;
                }

                if (mode === 'ORIG') {
                    if (ptype == 'DRIVING') {
                        me.origin_dportionPlaceId = place.place_id;
                        me.origin_dportionPlaceLocation = place.geometry.location;
                    }
                    if (ptype == 'TRANSIT') {
                        me.origin_tportionPlaceId = place.place_id;
                        me.origin_tportionPlaceLocation = place.geometry.location;
                    }
                    if (ptype == 'PLANE') {
                        me.origin_fportionPlaceId = place.place_id;
                        me.origin_fportionPlaceLocation = place.geometry.location;
                    }
                } else {
                    if (ptype == 'DRIVING') {
                        me.destination_dportionPlaceId = place.place_id;
                        me.destination_dportionPlaceLocation = place.geometry.location;
                    }
                    if (ptype == 'TRANSIT') {
                        me.destination_tportionPlaceId = place.place_id;
                        me.destination_tportionPlaceLocation = place.geometry.location;
                    }
                    if (ptype == 'PLANE') {
                        me.destination_fportionPlaceId = place.place_id;
                        me.destination_fportionPlaceLocation = place.geometry.location;
                    }
                }

                me.portionroute(ptype);
            });
        };

        AutocompleteDirectionsHandler.prototype.setupAlgoliaPlaceChangedListenerPortion = function(autocomplete, mode, ptype) {
            var me = this;


            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();


                if (place) {
                    if (mode === 'ORIG') {
                        if (ptype === 'DRIVING') {
                            me.origin_dportionPlaceId = place.place_id;
                            me.origin_dportionPlaceLocation = place.geometry.location;
                        }
                        if (ptype === 'TRANSIT') {
                            me.origin_tportionPlaceId = place.place_id;
                            me.origin_tportionPlaceLocation = place.geometry.location;
                        }
                        if (ptype === 'PLANE') {
                            me.origin_fportionPlaceId = place.place_id;
                            me.origin_fportionPlaceLocation = place.geometry.location;

                            console.log('me.origin_fportionPlaceId', me.origin_fportionPlaceId);
                            console.log('me.origin_fportionPlaceLocation', me.origin_fportionPlaceLocation);
                        }
                    } else {
                        if (ptype === 'DRIVING') {
                            me.destination_dportionPlaceId = place.place_id;
                            me.destination_dportionPlaceLocation = place.geometry.location;
                        }
                        if (ptype === 'TRANSIT') {
                            me.destination_tportionPlaceId = place.place_id;
                            me.destination_tportionPlaceLocation = place.geometry.location;
                        }
                        if (ptype === 'PLANE') {
                            me.destination_fportionPlaceId = place.place_id;
                            me.destination_fportionPlaceLocation = place.geometry.location;
                        }
                    }
                    me.portionroute(ptype);
                }

            });




        };

        AutocompleteDirectionsHandler.prototype.setupAlgoliaPlaceChangedListener = function(autocomplete, mode) {

            console.log('Car Route');

            var me = this;
            autocomplete.addListener('place_changed', function() {
                console.log('Car Route change');
                var place = autocomplete.getPlace();
                if (place) {

                    if (mode === 'ORIG') {
                        me.originPlaceId = place.place_id;
                        me.originPlaceLocation = place.geometry.location;
                        me.originAddress = place.formatted_address;

                        me.destinationPlaceId = place.place_id;
                        me.destinationPlaceLocation = place.geometry.location;
                        me.origin_dportionPlaceId = place.place_id;
                        me.origin_dportionPlaceLocation = place.geometry.location;
                        me.origin_fportionPlaceId = place.place_id;
                        me.origin_fportionPlaceLocation = place.geometry.location;
                        me.origin_tportionPlaceId = place.place_id;
                        me.origin_tportionPlaceLocation = place.geometry.location;

                    } else if (mode === 'WAYPT') {
                        if (transportWay === 'vehicle') {
                            map = new google.maps.Map(document.getElementById('map'), {
                                mapTypeControl: false,
                                center: {
                                    lat: 40.730610,
                                    lng: -73.968285
                                },
                                mapTypeId: google.maps.MapTypeId.ROADMAP,
                                zoom: 5
                            });
                            directionsRenderer.setMap(map);
                        }
                        me.wayptPlaceId = place.place_id;
                        me.wayptPlaceLocation = place.geometry.location;
                    } else {
                        // me.destinationPlaceId = place.place_id;
                        // me.destinationPlaceLocation = place.geometry.location;
                        // me.origin_dportionPlaceId = place.place_id;
                        // me.origin_dportionPlaceLocation = place.geometry.location;
                        // me.origin_fportionPlaceId = place.place_id;
                        // me.origin_fportionPlaceLocation = place.geometry.location;
                        // me.origin_tportionPlaceId = place.place_id;
                        // me.origin_tportionPlaceLocation = place.geometry.location;
                    }
                    me.route();

                } else {
                    window.alert('No results found in google map.');
                }


            });

        };

        AutocompleteDirectionsHandler.prototype.portionroute = function(ptype) {


            if (ptype == 'DRIVING') {
                if (!this.origin_dportionPlaceId || !this.destination_dportionPlaceId) {
                    return;
                }
            }
            if (ptype == 'TRANSIT') {
                if (!this.origin_tportionPlaceId || !this.destination_tportionPlaceId) {
                    return;
                }
            }
            if (ptype == 'PLANE') {
                if (!this.origin_fportionPlaceId || !this.destination_fportionPlaceId) {
                    return;
                }
            }
            var me = this;

            if (ptype == 'DRIVING') {
                document.getElementById('location_from_latlng_drivingportion').value = me.origin_dportionPlaceLocation;
                document.getElementById('location_to_latlng_drivingportion').value = me.destination_dportionPlaceLocation;
                var bounds = new google.maps.LatLngBounds();

                var marker_icon = new google.maps.MarkerImage(
                    //'https://planiversity.com/assets/images/icon.png',
                    'https://planiversity.com/assets/images/Selected_A.png',
                    null,
                    // The origin for my image is 0,0.
                    new google.maps.Point(0, 0),
                    // The center of the image is 50,50 (my image is a circle with 100,100)
                    new google.maps.Point(50, 50)
                );

                // mark_number = 1

                // marker_destination = new google.maps.Marker({
                //     position: me.destination_dportionPlaceLocation,
                //     // icon: 'https://planiversity.com/assets/images/icon.png',
                //     icon: {
                //         url: "https://planiversity.com/assets/images/Selected_A.png",
                //         size: new google.maps.Size(100, 100),
                //         anchor: new google.maps.Point(40, 40),
                //     },
                //     label: {
                //         text: markerAlpaArr[mark_number],
                //         color: "#ffffff",
                //     }
                // });

                // marker_destination.setMap(this.map);

                // bounds.extend(marker_destination.position);
            }

            if (ptype == 'TRANSIT') {
                document.getElementById('location_from_latlng_trainportion').value = me.origin_tportionPlaceLocation;
                document.getElementById('location_to_latlng_trainportion').value = me.destination_tportionPlaceLocation;
                var bounds = new google.maps.LatLngBounds();

                var marker_icon = new google.maps.MarkerImage(
                    //'https://planiversity.com/assets/images/icon.png',
                    'https://planiversity.com/assets/images/Selected_A.png',
                    null,
                    // The origin for my image is 0,0.
                    new google.maps.Point(0, 0),
                    // The center of the image is 50,50 (my image is a circle with 100,100)
                    new google.maps.Point(50, 50)
                );

                marker_destination = new google.maps.Marker({
                    position: me.destination_tportionPlaceLocation,
                    // icon: 'https://planiversity.com/assets/images/icon.png',
                    icon: marker_icon,
                    label: {
                        text: markerAlpaArr[mark_number + 1],
                        color: "#ffffff",
                    }
                });
                marker_destination.setMap(this.map);
                bounds.extend(marker_destination.position);
            }

            if (ptype == 'PLANE') {
                document.getElementById('location_from_latlng_flightportion').value = me.origin_fportionPlaceLocation;
                document.getElementById('location_to_latlng_flightportion').value = me.destination_fportionPlaceLocation;
            }

            if (ptype != 'PLANE') {

                this.directionsService.route({
                    origin: {
                        'placeId': (ptype == "DRIVING" ? this.origin_dportionPlaceId : this.origin_tportionPlaceId)
                    },
                    destination: {
                        'placeId': (ptype == "DRIVING" ? this.destination_dportionPlaceId : this.destination_tportionPlaceId)
                    },
                    travelMode: google.maps.TravelMode[ptype],
                    optimizeWaypoints: true,
                }, function(response, status) {
                    if (status === 'OK') {
                        var line = new google.maps.Polyline({
                            path: response.routes[0].overview_path,
                            strokeColor: '#0688E9',
                            strokeOpacity: 1.0,
                            strokeWeight: 3
                        });
                        line.setMap(map);

                        var my_route = response.routes[0];

                        marker_destination = new google.maps.Marker({
                            position: my_route.legs[0].end_location,
                            icon: {
                                url: "https://planiversity.com/assets/images/Selected_A.png",
                                size: new google.maps.Size(100, 100),
                                anchor: new google.maps.Point(40, 40),
                            },
                            label: {
                                text: markerAlpaArr[mark_number],
                                color: "#ffffff",
                            }
                        });

                        marker_destination.setMap(this.map);

                        bounds.extend(marker_destination.position);

                        //me.fitBounds(me.bounds.union(response.routes[0].bounds));
                    } else {
                        window.alert('Directions request failed due to ' + status);
                    }
                });

            } else {

                this.map = map;
                var bounds = new google.maps.LatLngBounds();
                if (marker_origin) {
                    marker_origin.setMap(null);
                }



                var marker_icon = new google.maps.MarkerImage(
                    'https://planiversity.com/assets/images/icon.png',
                    null,
                    // The origin for my image is 0,0.
                    new google.maps.Point(0, 0),
                    // The center of the image is 50,50 (my image is a circle with 100,100)
                    new google.maps.Point(50, 50)
                );


                marker_origin = new google.maps.Marker({
                    position: me.origin_fportionPlaceLocation,
                    icon: {
                        url: "https://planiversity.com/assets/images/Selected_A.png",
                        size: new google.maps.Size(100, 100),
                        anchor: new google.maps.Point(40, 40),
                    },
                    map: map,
                });
                marker_origin.setMap(this.map);
                bounds.extend(marker_origin.position);

                marker_destination = new google.maps.Marker({
                    position: me.destination_fportionPlaceLocation,
                    icon: {
                        url: "https://planiversity.com/assets/images/Selected_A.png",
                        size: new google.maps.Size(100, 100),
                        anchor: new google.maps.Point(40, 40),
                    },
                    label: {
                        text: markerAlpaArr[mark_number + 1],
                        color: "#ffffff",
                    }
                });
                marker_destination.setMap(this.map);
                bounds.extend(marker_destination.position);


                var flightPlanCoordinates = [{
                        lat: me.origin_fportionPlaceLocation.lat(),
                        lng: me.origin_fportionPlaceLocation.lng()
                    },
                    {
                        lat: me.destination_fportionPlaceLocation.lat(),
                        lng: me.destination_fportionPlaceLocation.lng()
                    }
                ];
                const lineSymbol = {
                    path: "M 0,0 0,1",
                    strokeOpacity: 1,
                    scale: 3,
                };
                const lineSymbol1 = {
                    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                    strokeOpacity: 1,
                };
                flightPath = new google.maps.Polyline({
                    path: flightPlanCoordinates,
                    geodesic: true,
                    icons: [{
                            icon: lineSymbol,
                            offset: "0",
                            repeat: "20px",
                        },
                        {
                            icon: lineSymbol1,
                            offset: "100%",
                        },
                    ],
                    strokeColor: '#0688E9',
                    strokeOpacity: 0,
                    strokeWeight: 3
                });

                flightPath.setMap(this.map);
                this.map.fitBounds(bounds);
                mark_number = 2;

            }

        };

        AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
            var me = this;
            autocomplete.bindTo('bounds', this.map);
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.place_id) {
                    window.alert("Please select an option from the dropdown list.");
                    return;
                }
                if (mode === 'ORIG') {
                    me.originPlaceId = place.place_id;
                    me.originPlaceLocation = place.geometry.location;
                } else if (mode === 'WAYPT') {
                    if (transportWay === 'vehicle') {
                        map = new google.maps.Map(document.getElementById('map'), {
                            mapTypeControl: false,
                            center: {
                                lat: 40.730610,
                                lng: -73.968285
                            },
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                            zoom: 7
                        });
                        directionsRenderer.setMap(map);
                    }
                    me.wayptPlaceId = place.place_id;
                    me.wayptPlaceLocation = place.geometry.location;
                } else {
                    me.destinationPlaceId = place.place_id;
                    me.destinationPlaceLocation = place.geometry.location;
                    me.origin_dportionPlaceId = place.place_id;
                    me.origin_dportionPlaceLocation = place.geometry.location;
                    me.origin_fportionPlaceId = place.place_id;
                    me.origin_fportionPlaceLocation = place.geometry.location;
                    me.origin_tportionPlaceId = place.place_id;
                    me.origin_tportionPlaceLocation = place.geometry.location;

                }
                me.route();
            });
        };

        AutocompleteDirectionsHandler.prototype.route = function(wayptsArray = null) {

            console.log('This is this');

            if (!this.originPlaceId || !this.destinationPlaceId) {
                return;
            }
            var me = this;


            document.getElementById('location_from_latlng').value = me.originPlaceLocation;
            document.getElementById('location_to_latlng').value = me.destinationPlaceLocation;
            document.getElementById('location_to').value = me.originAddress;

            var waypts = [];
            var destPID = this.destinationPlaceId;
            if (transportWay === 'vehicle') {
                if (me.wayptPlaceLocation != null) {
                    waypts.push({
                        location: me.wayptPlaceLocation,
                        stopover: true
                    });
                    destPID = me.wayptPlaceId;
                }

                if (waypoints.length > 0) {
                    waypts = waypts.concat(waypoints)
                }

                if (wayptsArray) {
                    waypts = wayptsArray;
                }


                directionsService.route({
                    origin: {
                        'placeId': this.originPlaceId
                    },
                    destination: {
                        'placeId': this.destinationPlaceId
                    },
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    travelMode: this.travelMode,
                }, function(response, status) {
                    if (status === 'OK') {

                        var my_route = response.routes[0];
                        addMarker(my_route, 'event')
                        map.setZoom(14);
                        map.panTo(my_route.overview_path[0]);
                    } else {
                        window.alert('Directions ** request failed due to ' + status);
                    }
                });
            } else {
                if (me.wayptPlaceLocation != null) {
                    waypts.push({
                        location: me.destinationPlaceLocation,
                        stopover: true
                    });
                    destPID = me.wayptPlaceId;
                }
                this.directionsService.route({
                    origin: {
                        'placeId': this.originPlaceId
                    },
                    destination: {
                        'placeId': destPID
                    },
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    travelMode: this.travelMode,
                    transitOptions: {
                        modes: ['TRAIN'],
                        routingPreference: 'FEWER_TRANSFERS'
                    },

                }, function(response, status) {
                    if (status === 'OK') {
                        var line2 = new google.maps.Polyline({
                            path: response.routes[0].overview_path,
                            strokeColor: '#0688E9',
                            strokeOpacity: 1.0,
                            strokeWeight: 3
                        });
                        line2.setMap(map);

                        addMarker(response.routes[0], 'event')
                        me.directionsDisplay.setDirections(response);
                    } else {
                        window.alert('Directions ** request failed due to ' + status);
                    }
                });
            }
        };


        function tog(v) {
            return v ? 'addClass' : 'removeClass';
        }
        $(document).on('input', '.clearable', function() {
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function(e) {
            $(this)[tog(this.offsetWidth - 18 < e.clientX - this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function(ev) {
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
        });

        $("#checkbox-hotel").click(function() {
            var dep = $("#location_datel").val();
            var arr = $("#location_dater").val();
            if (this.checked == true)
                $("#hotel_date_checkin").val(dep);
            $("#hotel_date_checkout").val(arr);
        });

        $("#checkbox-car-rental").click(function() {
            var dep = $("#location_datel").val();
            var arr = $("#location_dater").val();
            if (this.checked == true)
                $("#rental_date_pick").val(dep);
            $("#rental_date_drop").val(arr);
        });

        $("#rental_agency_located").click(function() {
            if (this.checked == true) {
                $("#rental_agency_address").hide();
            } else {
                $("#rental_agency_address").show();
            }
        });

        $("#hotel_located").click(function() {
            if (this.checked == true) {
                $("#hotel_address").hide();
            } else {
                $("#hotel_address").show();
            }
        });

        const populateSelectBox = (data, type) => {
            $('#people_mode').val(type);
            var items = `<option value="">Select ${type === 'Group' ? 'Group' : 'Profile'}</option>`;

            $.each(data, function(index, item) {
                items += "<option id='" + item.option_id + "' value='" + item.option_id + "' >" + item.option_name + "</option>";
            });

            $("#profile_employee").html(items);
        }

        $('#switch').change(function() {
            if (this.checked) {
                populateSelectBox(employees, "People")
            } else {
                populateSelectBox(groups, "Group")
            }
        });

        $('.people-action').click(function(event) {
            event.preventDefault();
            const peopleValue = $('#profile_employee').val();

            if (!peopleValue) {
                $('#profile_employee-error').fadeIn();
            } else {
                $('#profile_employee-error').fadeOut();
            }

            $(".people-button").css("cursor", "not-allowed");
            $(".people-button").attr("disabled", 1);

            connectPeople($('#people_mode').val(), peopleValue);
        });

        $(document).on("click", "button.delete_people_action", function(event) {
            event.preventDefault();
            let id = $(this).val();

            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function() {
                const elsToRemove = $(`[data-people-id="${id}"]`);
                if (elsToRemove) {
                    $(elsToRemove).remove();
                }
            });

        });

        function connectPeople(mode, id) {
            const params = {
                user_id: userId,
                [mode === 'People' ? 'people_id' : 'group_id']: id
            }

            let items = [];

            $.getJSON(SITE + "ajaxfiles/connect/get_people.php", params, function(data) {
                $.each(data, function(index, item) {
                    if ($(".people_section").find(`#people_${item.id}`).length > 0) {
                        return;
                    }

                    const addedPeopleDiv = document.getElementById('added_people');
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = 'people[]';
                    newInput.value = item.id;
                    newInput.setAttribute('data-people-id', item.id);

                    addedPeopleDiv.appendChild(newInput);

                    let photo = SITE + "assets/images/user_profile.png";
                    let path_folder = "people";

                    let name = item.first_name + " " + item.last_name;

                    if (item.photo_connect == "1") {
                        path_folder = "profile";
                    }

                    if (item.photo) {
                        photo = SITE + `ajaxfiles/${path_folder}/${item.photo}`;
                    }

                    items += `
                    <div class="people_row" id="people_${item.id}" data-people-id="${item.id}">
                    <div class="people_left_side"><div class="people_img">
                    <img src="${photo}"></div><div class="people_info">
                    <h4>${item.name}</h4><p>${item.email}</p></div></div>
                    <div class="people_right_side">
                    <button id="delete" class="btn btn-mini btn-danger delete_people_action" title="Delete User" value="${item.id}"><i class='fa fa-trash'></i> </button>
                    </div></div>
                    `;

                });

                $(".people-button").css("cursor", "pointer");
                $(".people-button").removeAttr("disabled");

                $(".people_section").append(items);
            });
        }

        $(function() {
            populateSelectBox(employees, "People");
        });

        $('input[type=checkbox][name=location_count_option]').change(function() {

            $('#location_count_step').val(this.value);
            $('#location_count_step-error').html('');

            if (this.value == 'yes') {
                $('.location_multiple_section').fadeIn();
                $('.location_one_section').hide();
            } else {
                $('.location_multiple_section').hide();
                $('.location_one_section').fadeIn();
            }
        });


        $('input[type=checkbox][name=event_option]').change(function() {

            $('#event_option_value').val(this.value);
            $('#event_option_value-error').html('');

            if (this.value == 'one') {
                $('#location_datel_m').val('');
                $('#location_dater').val('');
                $('.single_event_section').delay(500).fadeIn();
                $('.multiple_event_section').hide();
            } else {
                $('#location_datel').val('');
                $('.multiple_event_section').delay(500).fadeIn();
                $(".single_event_section").hide();
            }

        });



        $('input[type=checkbox][name=hotel_option]').change(function() {

            $('#hotel_option_value').val(this.value);
            $('#hotel_option_value-error').html('');

            if (this.value == 'yes') {
                $('.hotel_room_book_section').delay(500).fadeIn();
                $('.hotel_entry_section').delay(500).fadeIn();
            } else {
                $(".hotel_room_book_section").hide();
                $(".hotel_entry_section").hide();
                $('.next').click();
            }

        });


        $('input[type=checkbox][name=car_option]').change(function() {

            $('#car_option_value').val(this.value);
            $('#hotel_option_value-error').html('');

            if (this.value == 'yes') {
                $('.car_rental_book_section').delay(500).fadeIn();
                $('.car_entry_section').delay(500).fadeIn();
            } else {
                $(".car_rental_book_section").hide();
                $(".car_entry_section").hide();
                $('.next').click();
            }

        });


        $('input[type=checkbox][name=drive_option]').change(function() {

            $('#drive_option_value').val(this.value);
            $('#drive_option_value-error').html('');

            if (this.value == 'yes') {
                $('.drive_entry_section').delay(500).fadeIn();
            } else {
                $(".drive_entry_section").hide();
                $('.next').click();
            }

        });


        $('input[type=checkbox][name=hotel_room_option]').change(function() {

            if (this.value == 'yes') {
                $('.hotel_booking_section').delay(500).fadeIn();
            } else {
                $(".hotel_booking_section").hide();
            }

        });


        $('input[type=checkbox][name=car_rental_option]').change(function() {

            if (this.value == 'yes') {
                $('.car_booking_section').delay(500).fadeIn();
            } else {
                $(".car_booking_section").hide();
            }

        });

        function toggle_visibility(id) {
            var e = document.getElementById(id);
            if (e.style.display == 'block')
                e.style.display = 'none';
            else
                e.style.display = 'block';
            setDrivingAndTrain();
        }

        $(window).on('load', function() {
            $('#schedule-modal').modal('show');
            $("#return").hide();
        });

        $('#flyover_save').click(function() {
            $(".close").click();
        });

        const inputMap = new Map();
        const markersMap = new Map();


        // $('#location_count').change(function() {
        //     const currentVal = $(this).val();
        //     let items = [];

        //     if (currentVal <= 0 || currentVal > 5) {
        //         return;
        //     }

        //     for (let i = 1; i <= currentVal; i++) {
        //         items += `<label class="mt-2 mr-b-10">Location #${i}</label>
        //             <input type="text" data-event-location="${i}" class="dashboard-form-control multiple-location-input form-control input-lg clearable" placeholder="Enter Location">
        //             </div>`
        //     }

        //     $('.location_inputs').html(items);

        //     let inputs = $('.multiple-location-input');

        //     // setupLocationAutocomplete(inputs);
        // });



        // const setupLocationAutocomplete = (inputs) => {
        //     if ($(inputs).length === 0) {
        //         return;
        //     }

        //     $(inputs).each(input => {
        //         let locationNum = $(inputs[input]).attr('data-event-location');

        //         inputMap.set(locationNum, new google.maps.places.Autocomplete(inputs[input]));

        //         inputMap.get(locationNum).addListener('place_changed', function() {
        //             var place = this.getPlace();

        //             var location_lat = place.geometry['location'].lat();
        //             var location_lng = place.geometry['location'].lng();

        //             var marker = markersMap.get(locationNum);
        //             // Remove old marker if it exists
        //             if (marker) {
        //                 marker.setMap(null);
        //             }

        //             $('event_location_1-error').hide();
        //             $(`#event_location_${locationNum}`).val(`${location_lat} ${location_lng}`);

        //             // Create and store new marker
        //             var newMarker = createNewMarker(map, location_lat, location_lng);
        //             markersMap.set(locationNum, newMarker);
        //         });
        //     });
        // }


        $(".close").click(function() {
            if ($("#schedule-modal").hasClass('modaltrans')) {
                console.log('going big');

                $("#schedule-modal").removeClass('modaltrans');
                $("#schedule-modal-body").removeClass('modaltrans-body');
                $("#schedule-modal-body").removeClass('modaltrans-body-mozila');
                $("#myLargeModalLabel").css({
                    fontSize: 21
                });

                $(this).html("-");
                $('.flyover').hide();
            } else {

                console.log('going small');
                $('.flyover').show();

                $("#schedule-modal").addClass('modaltrans');
                if (window.navigator.userAgent.indexOf("Chrome") > -1) {
                    $("#schedule-modal-body").addClass('modaltrans-body');
                } else {
                    $("#schedule-modal-body").addClass('modaltrans-body-mozila');
                }

                $("#myLargeModalLabel").css({
                    fontSize: 15
                });

                $(this).html("+");
            }
        });

        var add_more_stop_count = 0
        var multi_arr_value = [];
        var currentWriteIndex = null;
        var currentChangeIndex = null;
        var wayptAutocompleteVar = [];
        var wayHotelMultiple = [];
        var is_update = null;
        var i = 0;
        var place = "";

        function addLocationToStop(index, type) {
            switch (transportWay) {
                case "vehicle":
                    var inputType = null;
                    var mapType = 'DRIVING';
                    break;
                case "plane":
                    var inputType = "airport";
                    var mapType = 'TRANSIT';
                    break;
                case "train":
                    var inputType = "train_station";
                    var mapType = 'TRANSIT';
                    break;
                default:
                    var inputType = "";
                    var mapType = 'DRIVING';
                    break;
            }

            if (type == 'hotel') {
                new google.maps.places.Autocomplete(document.getElementById("multi_location_waypoint" + index), {
                    types: [null]
                });

            }

            if (type == 'car') {

                new google.maps.places.Autocomplete(document.getElementById("multi_location_waypoint" + index), {
                    types: [null]
                });

            }


            wayptAutocompleteVar[index] = new google.maps.places.Autocomplete(document.getElementById("multi_location_waypoint" + index), {
                types: [inputType]
            });

            wayptAutocompleteVar[index].addListener('place_changed', function() {
                var place = wayptAutocompleteVar[index].getPlace();

                map = new google.maps.Map(document.getElementById('map'), {
                    mapTypeControl: false,
                    center: {
                        lat: 40.730610,
                        lng: -73.968285
                    },
                    zoom: 7
                });

                directionsRenderer.setMap(map);
                is_update = null;
                i = 0;
                currentWriteIndex = currentChangeIndex;


                if (place) {

                    if (multi_arr_value.length == 0) {
                        multi_arr_value.push({
                            id: currentWriteIndex,
                            address: place.formatted_address,
                            location_multi_waypoint_latlng: place.geometry.location.lat() + ',' + place.geometry.location.lng()
                        })
                        waypoints.push({
                            location: place.geometry.location,
                            stopover: true
                        })
                    } else {
                        multi_arr_value.push({
                            id: currentWriteIndex,
                            address: place.formatted_address,
                            location_multi_waypoint_latlng: place.geometry.location.lat() + ',' + place.geometry.location.lng()
                        })

                        waypoints.push({
                            location: place.geometry.location,
                            stopover: true
                        })

                    }

                    document.getElementById('location_multi_waypoint_latlng').value = JSON.stringify(multi_arr_value)
                    var way_ponts = waypoints
                    switch (transportWay) {
                        case "vehicle":
                            var request = {
                                origin: document.getElementById('location_from').value,
                                destination: document.getElementById('location_to').value,
                                waypoints: way_ponts,
                                optimizeWaypoints: true,
                                travelMode: mapType,
                            };
                            directionsService.route(request, function(result, status) {
                                if (status == 'OK') {
                                    directionsRenderer.setDirections(result);
                                    var my_route = result.routes[0];
                                    addMarker(my_route, 'event');
                                }
                            });

                            var location_from_value = document.getElementById('location_from').value;
                            var location_to_value = document.getElementById('location_to').value;

                            var location_from_latlng_value = document.getElementById('location_from_latlng').value;
                            var location_to_latlng_value = document.getElementById('location_to_latlng').value;

                            location_from_latlng_value = convertLatLngStrToArray(location_from_latlng_value);
                            location_to_latlng_value = convertLatLngStrToArray(location_to_latlng_value);


                            var infowindowfrom = new google.maps.InfoWindow();
                            infowindowfrom.setContent("<div style='float:left; padding: 3px;'></div><div style='float:right; padding: 3px;'> " + location_from_value + " </div>");
                            infowindowfrom.setPosition({
                                lat: location_from_latlng_value[0] * 1,
                                lng: location_from_latlng_value[1] * 1
                            });
                            infowindowfrom.open(map);

                            var infowindowto = new google.maps.InfoWindow();
                            infowindowto.setContent("<div style='float:left; padding: 3px;'></div><div style='float:right; padding: 3px;'> " + location_to_value + " </div>");
                            infowindowto.setPosition({
                                lat: location_to_latlng_value[0] * 1,
                                lng: location_to_latlng_value[1] * 1
                            });
                            infowindowto.open(map);

                            var via_multipoints_value = [];

                            via_multipoints_value = JSON.stringify(multi_arr_value);

                            if (Array.isArray(multi_arr_value) && multi_arr_value.length) {

                                for (var i = 0; i < multi_arr_value.length; i++) {
                                    var element = multi_arr_value[i];
                                    const mylocation = element.location_multi_waypoint_latlng.split(",");
                                    var infowindowmulti = new google.maps.InfoWindow();
                                    infowindowmulti.setContent("<div style='float:left; padding: 3px;'></div><div style='float:right; padding: 3px;'> " + element.address + " </div>");
                                    infowindowmulti.setPosition({
                                        lat: mylocation[0] * 1,
                                        lng: mylocation[1] * 1
                                    });
                                    infowindowmulti.open(map);

                                }


                            }


                            break;
                        case "plane":

                            var originPlaceLocation = document.getElementById('location_from_latlng').value;
                            var destinationPlaceLocation = document.getElementById('location_to_latlng').value;
                            originPlaceLocation = convertLatLngStrToArray(originPlaceLocation);
                            destinationPlaceLocation = convertLatLngStrToArray(destinationPlaceLocation);
                            var bounds = new google.maps.LatLngBounds();
                            var marker_imageA = new google.maps.MarkerImage(
                                'https://planiversity.com/assets/images/Selected_A.png',
                                null,
                                // The origin for my image is 0,0.
                                new google.maps.Point(0, 0),
                                // The center of the image is 50,50 (my image is a circle with 100,100)
                                new google.maps.Point(50, 50)
                            );

                            var mk = new google.maps.Marker({
                                position: new google.maps.LatLng(originPlaceLocation[0] * 1, originPlaceLocation[1] * 1),
                                icon: marker_imageA,
                                label: {
                                    text: markerAlpaArr[0],
                                    color: "#ffffff",
                                }
                            });
                            mk.setMap(map);
                            bounds.extend(mk.position);

                            const lineSymbol = {
                                path: "M 0,0 0,1",
                                strokeOpacity: 1,
                                scale: 3,
                            };
                            const lineSymbol1 = {
                                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                                strokeOpacity: 1,
                            };

                            var flightPlanCoordinates = [{
                                lat: originPlaceLocation[0] * 1,
                                lng: originPlaceLocation[1] * 1
                            }];
                            for (var i = 0; i < waypoints.length; i++) {
                                flightPlanCoordinates.push({
                                    lat: waypoints[i].location.lat(),
                                    lng: waypoints[i].location.lng()
                                });
                                mk = new google.maps.Marker({
                                    position: new google.maps.LatLng(waypoints[i].location.lat(), waypoints[i].location.lng()),
                                    icon: marker_imageA,
                                    label: {
                                        text: markerAlpaArr[i + 1],
                                        color: "#ffffff",
                                    }
                                });
                                mk.setMap(map);
                                bounds.extend(mk.position);
                            }
                            flightPlanCoordinates.push({
                                lat: destinationPlaceLocation[0] * 1,
                                lng: destinationPlaceLocation[1] * 1
                            })
                            flightPath = new google.maps.Polyline({
                                path: flightPlanCoordinates,
                                geodesic: true,
                                icons: [{
                                        icon: lineSymbol,
                                        offset: "0",
                                        repeat: "20px",
                                    },
                                    {
                                        icon: lineSymbol1,
                                        offset: "100%",
                                    },
                                ],
                                strokeColor: '#0688E9',
                                strokeOpacity: 0,
                                strokeWeight: 3
                            });
                            flightPath.setMap(map);
                            var mindex = waypoints.length + 1;
                            mark_number = mindex;
                            mk = new google.maps.Marker({
                                position: new google.maps.LatLng(destinationPlaceLocation[0] * 1, destinationPlaceLocation[1] * 1),
                                icon: marker_imageA,
                                label: {
                                    text: markerAlpaArr[mindex],
                                    color: "#ffffff",
                                }
                            });
                            mk.setMap(map);
                            bounds.extend(mk.position);
                            map.fitBounds(bounds);
                            break;
                        case "train":
                            break;
                    }
                }


            });


        }

        var addMoreLabel = ["First", "Second", "Third", "Fourth", "Fifth", "Sixth", "Seventh", "8th", "9th"];

        $('#addMoreStop').click(function() {
            if ($('#append_more_stop').length) {
                var html = `<div class="set set${add_more_stop_count}">
                                <div class="close_item" onclick="removeMultiStop(${add_more_stop_count})">
                                    <i class="fa fa-times" aria-hidden="true" onclick="removeMultiStop(${add_more_stop_count})"></i>
                                </div>
                                <a href="javascript:void(0)" onclick="collapse(this, ${add_more_stop_count})">
                                    Stop 0${add_more_stop_count + 1}
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <div class="content content${add_more_stop_count}">
                                    <div class="accordion_container_item_first">
                                        <div class="row">
                                            <div class="col-xl-12 col-lg-12">
                                                <div class="form-group">
                                                    <label>Destination</label>
                                                    <input type="text" name="multi_location_waypoint[]" onfocus="onWayPointKeyUp(this)" id="multi_location_waypoint${add_more_stop_count}" class="form-control" placeholder="Destination">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Arrival Date</label>
                                                    <div class="date_picker_item">
                                                        <input type="text" name="multi_location_waypoint_date[]" id="multi_location_waypoint_date${add_more_stop_count}" class="form-control datepicker" placeholder="Arrival Date" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>Date of Departure</label>
                                                    <div class="date_picker_item">
                                                        <input type="text" name="multi_location_waypoint_dep_date[]" id="multi_location_waypoint_dep_date${add_more_stop_count}" class="form-control datepicker" placeholder="Date of Departure" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                <?php if ($transport != 'vehicle') { ?>
                    html += `<div class="add_stop_flight_de">
                                        <div class="add_stop_flight_de_item">
                                            <div class="row">
                                                <div class="col-xl-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label><?php echo $label; ?> Number</label>
                                                        <div class="date_picker_item">
                                                            <input type="text" name="multi_waypoint_flight_no[]" class="form-control" placeholder="<?php echo $label; ?> Number" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>Seat Number</label>
                                                        <input type="text" name="multi_waypoint_seat_no[]" class="form-control" placeholder="Seat Number" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                <?php } ?>
                html += `<div class="add_hotel_details_list">
                                        <div class="add_hotel_details_items">
                                            <div class="row">
                                                <div class="col-xl-12 text-left">
                                                    <label for="add_hotel_detail">
                                                        <input type="checkbox" onclick="add_hotel_detail(this, ${add_more_stop_count})" id="add_hotel_detail${add_more_stop_count}" class="extra_input"/>
                                                        Add Hotel Details
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="add_hotel_detail_value${add_more_stop_count}" style="display: none">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group">
                                                            <label>Hotel Name</label>
                                                            <input type="text" name="stop_hotelname[]" class="form-control" placeholder="Hotel Name" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group">
                                                            <label>Hotel Address</label>
                                                            <input type="text" name="stop_hoteladdress[]" onfocus="onWayPointKeyUp(this)" class="form-control" id="multi_location_waypoint${add_more_stop_count}_hotel_addr" placeholder="Hotel Address Custom"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add_rental_car_detai_sec">
                                            <div class="row">
                                                <div class="col-xl-12 text-left">
                                                    <label for="add_rental_deta_check">
                                                        <input type="checkbox" onclick="add_rental_detail(this, ${add_more_stop_count})" id="add_rental_deta_check${add_more_stop_count}" class="extra_input"/>
                                                        Add Rental Car Details
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="add_rental_deta_check_value${add_more_stop_count}" style="display: none">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group">
                                                            <label>Rental Agency</label>
                                                            <input type="text" name="stop_rentalagency[]" class="form-control" placeholder="Rental Agency" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="form-group">
                                                            <label>Rental Agency Address</label>
                                                            <input type="text" name="stop_rentaladdress[]" onfocus="onWayPointKeyUp(this)" id="multi_location_waypoint${add_more_stop_count}_agency_addr" class="form-control" placeholder="Rental Agency Address 2"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                $('#append_more_stop').append(html);

                addLocationToStop(add_more_stop_count, "multi");
                addLocationToStop(add_more_stop_count + "_hotel_addr", "hotel");
                addLocationToStop(add_more_stop_count + "_agency_addr", "car");

                $('#multi_location_waypoint_date' + add_more_stop_count).datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                })

                $('#multi_location_waypoint_dep_date' + add_more_stop_count).datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                })

                add_more_stop_count++;
            }
        });

        function removeMultiStop(stop_id) {
            $(".set" + stop_id).remove();
            for (var i = 0; i < multi_arr_value.length; i++) {
                if (multi_arr_value[i].id == stop_id) {
                    multi_arr_value.splice(i, 1)
                    waypoints.splice(i, 1);
                    var way_ponts = waypoints
                    var request = {
                        origin: document.getElementById('location_from').value,
                        destination: document.getElementById('location_to').value,
                        waypoints: way_ponts,
                        optimizeWaypoints: true,
                        travelMode: 'DRIVING'
                    };
                    directionsService.route(request, function(result, status) {
                        if (status == 'OK') {
                            var my_route = result.routes[0];
                            addMarker(my_route, 'event')
                            directionsRenderer.setDirections(result);
                        }
                    });
                    document.getElementById('location_multi_waypoint_latlng').value = JSON.stringify(multi_arr_value)
                    break;
                }
            }
        }

        function onWayPointKeyUp(e) {
            currentChangeIndex = e.getAttribute("index");
        }

        function removeStop(value) {
            $('#more_block' + value).remove()
            for (var i = 0; i < multi_arr_value.length; i++) {
                if (multi_arr_value[i].id == value) {
                    multi_arr_value.splice(i, 1)
                    waypoints.splice(i, 1);
                    var way_ponts = waypoints
                    var request = {
                        origin: document.getElementById('location_from').value,
                        destination: document.getElementById('location_to').value,
                        waypoints: way_ponts,
                        optimizeWaypoints: true,
                        travelMode: 'DRIVING'
                    };
                    directionsService.route(request, function(result, status) {
                        if (status == 'OK') {
                            var my_route = result.routes[0];
                            addMarker(my_route, 'event')
                            directionsRenderer.setDirections(result);
                        }
                    });
                    document.getElementById('location_multi_waypoint_latlng').value = JSON.stringify(multi_arr_value)
                    break;
                }
            }
        }

        function add_rental_detail(e, id) {
            if ($(e).is(":checked")) {
                $("#add_rental_deta_check_value" + id).show();
            } else {
                $("#add_rental_deta_check_value" + id).hide();
            }
        }

        function add_hotel_detail(e, id) {
            if ($(e).is(":checked")) {
                $("#add_hotel_detail_value" + id).show();
            } else {
                $("#add_hotel_detail_value" + id).hide();
            }
        }

        function collapse(e, id) {
            if ($(e).hasClass("active")) {
                $(e).removeClass("active");
                $(e).siblings(".content" + id + "").slideUp(200);
                $(".set" + id + " > a i").removeClass("fa-angle-up").addClass("fa-angle-down");
            } else {
                $(".set" + id + " > a i").removeClass("fa-angle-up").addClass("fa-angle-down");
                $(e).find("i").removeClass("fa-angle-down").addClass("fa-angle-up");
                $(".set" + id + " > a").removeClass("active");
                $(e).addClass("active");
                $(".content" + id + "").slideUp(200);
                $(e).siblings(".content" + id + "").slideDown(200);
            }
        }

        function setDrivingAndTrain() {
            setTimeout(function() {
                var location_to = document.getElementById("location_to");
                location_to = location_to.value;
                var statusDriving = $("#driving").css('display');
                var statusFlight = $("#flight").css('display');
                var statusTrain = $("#train").css('display');

                if (statusDriving === "block" && transportWay !== 'train') {
                    $("#location_from_drivingportion").val(location_to);

                } else {
                    $("#location_from_drivingportion").val("");
                }

                if (statusFlight === "block" && transportWay !== 'train') {
                    $("#location_from_flightportion").val(location_to);

                } else {
                    $("#location_from_flightportion").val("");
                }

                if (statusTrain === "block") {
                    $("#location_from_trainportion").val(location_to);

                } else {
                    $("#location_from_trainportion").val("");
                }
            }, 100);
        }


        $('.all-portion').click(function() {
            var target = $(this).attr("data-target");
            console.log('target', target);
            $('#' + target).val('');
        });


        var dateToday = new Date();

        var datePickerOptions = {
            format: 'mm/dd/yyyy',
            autoclose: true,
        }


        var customClasses = [];

        var customPickerOptions = {
            templates: {
                leftArrow: '<i class="fa fa-chevron-left"></i>',
                rightArrow: '<i class="fa fa-chevron-right"></i>'
            },
            keyboardNavigation: false,
            autoclose: true,
            todayHighlight: true,
            disableTouchKeyboard: true,
            orientation: "auto",
            startDate: new Date(),
            beforeShowDay: function(date) {
                var year = date.getFullYear();
                var month = ('0' + (date.getMonth() + 1)).slice(-2);
                var day = ('0' + date.getDate()).slice(-2);
                var isoDate = year + '-' + month + '-' + day;

                // Check if the date has a custom class
                if (customClasses.indexOf(isoDate) !== -1) {
                    // The date has a custom class, so return it
                    return {
                        tooltip: "Event date",
                        classes: "special-date"
                    };
                } else {
                    // The date does not have a custom class, so return nothing
                    return {

                    };

                }

            }


        };

        var date_calculation = $('.date_calculation').datepicker(customPickerOptions);

        function date_range_calculation(start_date, end_date, customClasses) {

            console.log('start_date', start_date);
            console.log('end_date', end_date);


            // define start and end dates
            var startDate = new Date(start_date);
            var endDate = new Date(end_date);

            // calculate number of days between dates
            var timeDiff = Math.abs(endDate.getTime() - startDate.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            // generate list of dates between start and end dates
            var dateList = [];
            for (var i = 0; i <= diffDays; i++) {
                var date = new Date(startDate.getTime() + (i * 24 * 60 * 60 * 1000));
                customClasses.push(date.toISOString().substr(0, 10));
            }

        }

        $('.date_calculation').datepicker().on("changeDate", function() {


            let startDate = $('#location_datel').datepicker('getDate');
            let start_date = moment(startDate).format("YYYY-MM-DD");

            let m_startDate = $('#location_datel_m').datepicker('getDate');
            let m_start_date = moment(m_startDate).format("YYYY-MM-DD");


            var returnDate = $('#location_dater').datepicker('getDate');
            var return_date = moment(returnDate).format("YYYY-MM-DD");

            if ((m_startDate) && (returnDate)) {

                console.log('Both multiple');
                customClasses = [];
                date_range_calculation(m_start_date, return_date, customClasses);

            } else if (m_startDate) {
                console.log('First store');
                customClasses = [];
                customClasses.push(m_start_date);

            } else if (returnDate) {
                console.log('return_date');
                customClasses = [];
                customClasses.push(return_date);

            } else if (startDate) {
                console.log('One way');
                customClasses = [];
                customClasses.push(start_date);

            } else {
                console.log('Both gone');
                customClasses = [];
            }

            date_calculation.datepicker("update");

        });

        $('.start-date').datepicker().on("changeDate", function() {
            var startDate = $('.start-date').datepicker('getDate');
            var oneDayFromStartDate = moment(startDate).toDate();
            $('.end-date').datepicker('setStartDate', oneDayFromStartDate);
        });

        $('.end-date').datepicker().on("show", function() {
            var startDate = $('.start-date').datepicker('getDate');
            $('.day.disabled').filter(function(index) {
                return $(this).text() === moment(startDate).format('D');
            }).addClass('active');
        });


        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            todayHighlight: true,
        });

        $('.datepicker_one').datepicker(datePickerOptions);
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript" src="https://unpkg.com/@duffel/components@latest/dist/CardPayment.umd.min.js"></script>
    <script type="text/javascript" src="<?= SITE; ?>js/home-flight.js"></script>

</body>

</html>