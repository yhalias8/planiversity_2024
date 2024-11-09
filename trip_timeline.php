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
$markerAlpaArr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB');

$travelmode = 'DRIVING';
switch ($transport) {
    case 'vehicle':
        $travelmode = 'DRIVING';
        break;
    case 'train':
        $travelmode = 'TRANSIT';
        break;
}

if (isset($_POST['timeline_submit'])) {
    header("Location:" . SITE . "trip/plan-notes/" . $id_trip);
}

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
    <title>PLANIVERSITY - TIMELINE</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">

    <link href="<?php echo SITE; ?>assets/css/app-style.css?v=20230801513" rel="stylesheet" type="text/css" />

    <script src="<?php echo SITE; ?>assets/js/modernizr.min.js"></script>

    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.5/dist/fullcalendar.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />

    <script src="<?= SITE; ?>js/jquery-1.11.3.js"></script>

    <script>
        var SITE = '<?php echo SITE; ?>';
        var itinerary_type_mode = "<?= $trip->itinerary_type; ?>";
    </script>
    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <script src="<?= SITE; ?>js/global.js?v=20230222"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>


    <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
    <script src="<?php echo SITE; ?>js/flexcroll.js"></script>

    <style>
        .modal-step-invalid {
            color: red !important
        }

        .modal-step-error {
            margin-top: 24px;
            color: red;
            font-weight: 600;
            display: flex;
            gap: 5px;
            justify-content: center;
            align-items: center;
        }

        textarea.modal-step-invalid,
        input.modal-step-invalid {
            border: red 2px solid
        }

        .advanced_footer {
            display: flex;
            justify-content: space-between;
            padding: 12px;
        }

        .advanced_footer button:nth-child(2) {
            margin-left: auto;
        }

        .upload-button {
            border: 0;
            cursor: pointer;
            border-radius: 5px;
            height: 50.2px;
            max-width: 300px;
            min-width: 120px;
            margin: 32px auto 0;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
            background: linear-gradient(0,
                    rgba(243, 159, 50, 1) 0%,
                    rgba(250, 205, 97, 1) 100%);
        }

        .edit-timeline-button {
            margin-top: 7px;
            border-radius: 5px;
            padding: 4px 6px;
            font-size: 14px;
        }

        .advanced_nav {
            text-align: left;
        }

        .advanced_nav span {
            cursor: pointer;
            text-align: left;
            text-decoration: underline;
        }

        .modaltrans {
            /*height: 66px;*/
            width: 292px;
            overflow: hidden !important;
            padding-left: 0px !important;
            max-height: 300px;
            margin-left: 14px;
        }

        .modaltrans-body {
            transform: scale(0.4) translate(-77%, -77%);
            width: 260%;
        }

        .time-text {
            color: #fff;
            font-size: 12px;
            margin: 4px 0;
        }

        .custom-group {
            margin-bottom: 5px;
        }

        label.error {
            font-size: 12px;
            color: red !important;
        }

        .button-place {
            margin-top: 20px;
        }

        span.load_item {
            margin-left: 20px;
        }

        #update_schedule,
        div#advanced_popup {
            z-index: 9999;
        }

        .master_modal {
            overflow-x: hidden !important;
            overflow-y: scroll !important;
        }

        .modal-backdrop {
            background-color: #000;
            z-index: 1111;
        }

        .modal-blur {
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
        }

        h4.calendar-title {
            font-size: 20px;
            color: #1F74B7;
            margin-top: 10px;
        }

        div#schedule-modal-body {
            background-size: 80%;
        }

        .schedule-navigation {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .map_help p {
            background: #048cf2;
            padding: 7px 10px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        .map_help p:hover {
            background: #4d90c3;
        }

        div#video_popup {
            z-index: 9999999999;
        }

        #video_popup .modal-content {
            background: transparent;
            border: none;
            box-shadow: none;
        }

        #video_popup .modal-content .modal-header {
            border-bottom: 0;
        }

        .cmodal {
            margin-bottom: 0 !important;
            top: 0px;
        }

        .modal .modal-dialog .c-close {
            color: #fff;
            font-size: 48px;
            height: 52px;
            width: 48px;
        }

        .timeline-box {
            height: calc(600px - 10px);
            background-color: rgba(255, 255, 255, 0.5);
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        button.btn.timeline-action-btn {
            background: #cde8fc;
            color: #1191f0;
            font-size: 13px;
            font-weight: 500;
            border: none;
            padding: 10px 20px;
        }

        button.btn.timeline-action-btn.active {
            background: #058bef;
            color: #fff;
        }

        .event-preview-section {
            min-height: 450px;
            height: 500px;
            overflow-y: scroll;
        }

        .event-preview-section::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #e0eef9;
        }

        .event-preview-section::-webkit-scrollbar {
            width: 8px;
            background-color: #e0eef9;
        }

        .event-preview-section::-webkit-scrollbar-thumb {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #a1d1f5;
        }

        .preview-group {
            padding: 10px;
        }

        .preview-group h3.group-heading {
            font-size: 16px;
            font-weight: 600;
            color: #0C246B;
        }

        .preview-single-box {
            background-color: rgba(215, 237, 255, 0.8);
            padding: 15px 60px 5px 60px;
            border-radius: 15px;
            margin-bottom: 10px;
        }

        .preview-single-box h3.box-title {
            font-size: 16px;
            font-weight: bold;
            color: #0C246B;
        }

        .preview-single-box p {
            font-size: 13px;
            color: #058BEF;
        }

        .box-timestamp p {
            color: #0C246B;
            font-weight: 600;
        }

        .box-timestamp p span {
            margin-left: 20px;
        }

        h3.no-found {
            font-size: 17px;
            font-weight: 400;
            text-align: center;
            margin-top: 50px;
        }

        .loading_section {
            position: absolute;
            text-align: center;
            z-index: 99;
            width: 100%;
            height: 100%;
        }

        .loading_section i {
            font-size: 80px;
            color: #0886e3;
            position: relative;
            top: 120px;
        }

        .pac-container {
            z-index: 90000;
            /* Adjust the value as needed */
        }

        #calendar {
            max-width: 100%;
        }

        .fc-row.fc-week.table-bordered.fc-rigid {
            min-height: 68px !important;
        }

        .fc-basic-view .fc-body .fc-row {
            min-height: 68px !important;
        }

        div#advanced_popup,
        div#edit_timeline_popup {
            z-index: 10000;
            top: 0;
            margin-bottom: 0 !important;
        }

        .connect-bg-ground {
            min-height: 400px;
            background-size: contain !important;
        }

        .modal-content {
            background: #fff;
            border-radius: 8px;
        }

        .advanced_section {
            padding: 50px;
        }

        .advanced_section h3 {
            font-size: 26px;
            font-weight: 400;
        }

        .advanced_section p {
            margin: 0;
        }

        .advanced_body {
            margin-top: 10px;
        }

        .custom-control.custom-checkbox.option-control {
            display: inline-flex;
            margin-right: 20px;
        }

        .custom-control-label::before,
        .custom-control-label::after {
            width: 30px;
            height: 30px;
        }

        .custom-control-label p {
            display: block;
            position: relative;
            left: 10px;
            top: 5px;
            font-size: 16px;
            font-weight: 400;
        }

        .input_option_opacity {
            display: none;
            width: 1px;
            height: 1px;
            opacity: 0;
            -webkit-box-shadow: none;
            border: none;
            line-height: 1px;
            font-size: 1px;
            padding: 0;
        }

        label#advance_check-error {
            display: none !important;
        }

        label#option_requirement-error {
            display: block;
            margin-top: 20px;
        }

        button#mclose:focus {
            background: unset;
        }

        .advanced-plan-body {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
        }

        @media (max-width: 1150px) {
            .timeline-modal-lg {
                width: 90%;
                max-width: 100%;
            }

            .segment-item {
                flex: auto;
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .timeline-header {
                display: block;
            }

            .preview-single-box {
                padding: 10px;
            }

            #calendar {
                max-width: 100%;
            }
        }
    </style>
</head>

<body class="custom_notes">
<div class="fullscreen-background" style="z-index: 1;background-color:white"></div>
<div id="map"></div>
    <?php include('new_backend_header.php'); ?>

    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <?php
            $step_index = "schedule";
            include('dashboard/include/itinerary-step.php');
            ?>
        </div>
    </div>
    </header>



    <div class="modal cmodal fade modal-blur" id="video_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header custom-modal-header">
                    <button type="button" class="close c-close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body text-center">

                    <div class="modal-preview">

                        <video width="90%" height="auto" id="video" controls>
                            <source src="<?= SITE; ?>assets/images/home-page/How_to_Use_Schedule.mp4" type="video/mp4">
                        </video>


                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="schedule-modal2" data-backdrop="false" class="modal fade bs-example-modal-lg master_modal custom_prefix_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div id="modal-dialog1" class="modal-dialog timeline-modal-lg modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
            <div class="modal-content connect-bg px-4">
                <div class="modal-header pl-0">
                    <!--data-dismiss="modal"-->

                    <div>
                        <p class="small-logo-title">PLANIVERSITY</p>
                        <h4 class="modal-title pl-0 pt-0" id="myLargeModalLabel">Create Schedule <span class="small-logo-title"></span></h4>
                    </div>
                </div>
                <div class="modal-body connect-bg-ground px-0" id="schedule-modal-body">

                    <form id="timeline_form">
                        <fieldset>
                            <div class="error_style"><?php echo $output; ?></div>
                            <input name="location_from" id="location_from" class="inp1" value="<?php echo $trip->trip_location_from; ?>" type="hidden">
                            <input name="location_to" id="location_to" class="inp1" value="<?php echo $trip->trip_location_to; ?>" type="hidden">
                            <input name="timeline_idtrip" id="timeline_idtrip" class="inp1" value="<?php echo $trip->trip_id; ?>" type="hidden">
                            <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
                            <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
                            <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
                            <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">
                            <input name="trip_location_from_trainportion" id="trip_location_from_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
                            <input name="trip_location_to_trainportion" id="trip_location_to_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">

                            <input name="lat_to" id="lat_to" class="inp1" type="hidden" readonly>
                            <input name="lng_to" id="lng_to" class="inp1" type="hidden" readonly>
                            <input name="plan_linked" id="plan_linked" class="inp1" type="hidden" value="0" readonly>


                            <input id="advance_check" type="hidden" class="inp1" value="1" readonly>
                            <input name="checkin_flag" id="checkin_flag" class="inp1" type="hidden" value="0" readonly>
                            <input name="trip_note" id="advance_note" type="hidden" value="" readonly>


                            <input name="trip_generated" id="trip_generated" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_generated; ?>" readonly>
                            <input name="trip_u_id" id="trip_u_id" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->user_id; ?>" readonly>
                            <input name="trip_title" id="trip_title" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_title; ?>" readonly>
                            <div class="row">

                                <div class="col-md-4 col-sm-12 col-lg-4 segment-item">

                                    <div class="new-event-section card-opacity">
                                        <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                                        <div class="row">

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="emp-form-label">Add New Event</label>
                                                    <input name="event_name" id="event_name" type="text" class="account-form-control form-control input-lg inp1" maxlength="50" placeholder="Add New Event Name">
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="emp-form-label">Add Event Address <small>(Optional)</small></label>
                                                    <input name="event_address" id="event_address" type="text" class="account-form-control form-control input-lg inp1" placeholder="Add Event Address">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="emp-form-label">Event Date</label>
                                                    <input type="text" name="event_date" id="event_date" class="account-form-control form-control input-lg event_date" placeholder="Date">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="emp-form-label">Event Time</label>
                                                    <input type="time" name="event_time" id="event_time" class="account-form-control form-control input-lg" placeholder="Time">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <button type="submit" class="save-changes-btn submit_action_button employee-save-btn event-process-btn" id="save_event"> Save Event</button>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="map_help" data-toggle="modal" data-target="#video_popup">
                                                    <p><i class="fa fa-info-circle" aria-hidden="true"></i> How to use</p>
                                                </div>
                                            </div>

                                        </div>
                                        <?php } ?>

                                    </div>

                                    <div class="card-box event-list-section card-opacity">

                                        <div class="row">

                                            <div class="col-sm-12">
                                                <h5>Your Events</h5>
                                            </div>

                                            <div class="col-sm-12">

                                                <div class="event_list"></div>

                                            </div>


                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-8 col-sm-12 col-lg-8 schedule-navigation segment-item">

                                    <div class="card-box card-opacity timeline-box">
                                        <div class="timeline-header">
                                            <h4 class="calendar-title">Your Calendar</h4>
                                            <div class="timeline-action">
                                                <button type="button" class="btn timeline-action-btn active" data-mode="calender">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i> Calender View
                                                </button>
                                                <button type="button" class="btn timeline-action-btn" data-mode="preview">
                                                    <i class="fa fa-eye" aria-hidden="true"></i> Preview View
                                                </button>
                                            </div>
                                        </div>

                                        <div id="calendar"></div>
                                        <div>
                                            <div class="loading_section" style="display: none;">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                            <div class="event-preview-section" id="preview-section" style="display: none;">

                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                            <div class="border-none pt-1 pb-2">

                                <div class="skip_item_section no-background p-0">
                                    <ul class="list-unstyled justify-content-between">
                                        <li>
                                            <a href="<?= SITE; ?>welcome" class="skipt_value">Back</a>
                                        </li>
                                        <ul class="list-unstyled">
                                            <li>
                                                <a href="<?php echo SITE; ?>trip/plans/<?php echo $_GET['idtrip']; ?>" class="skipt_value">Skip Section</a>
                                            </li>
                                            <li>
                                                <a href="<?php echo SITE; ?>trip/plans/<?php echo $_GET['idtrip']; ?>" id="notes_submit" class="refresh-btn mt-3 mt-xs-0 mt-sm-0">Finished, Next Step</a>
                                            </li>
                                        </ul>
                                    </ul>
                                </div>

                            </div>
                            <?php } ?>


                        </fieldset>
                    </form>


                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="advanced_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-plan-lg" role="document">

            <form id="advanced_form">
                <div class="modal-content">
                    <div class="modal-body connect-bg-ground text-center advanced-plan-body">

                        <div class="advanced_steps">
                            <div style="display: none;" class="modal-step-error">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 16H12.01M12 8V12M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#FF0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>Please, fill required fields</span>
                            </div>

                            <div class="advanced_section advanced_step" data-step="1">

                                <h3>Would you like to add a check-in requirement for this event?</h3>
                                <p>Select a option for your event.</p>

                                <div class="advanced_body">

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="option_date_one" value="yes" name="option_requirement">
                                        <label class="custom-control-label" for="option_date_one">
                                            <p>Yes</p>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="option_date_two" value="no" name="option_requirement">
                                        <label class="custom-control-label" for="option_date_two">
                                            <p>No</p>
                                        </label>
                                    </div>

                                    <!-- <label id="option_requirement-error" class="mvalidy error option_requirement_error" for="option_requirement"></label> -->

                                </div>


                            </div>
                            <div class="advanced_section advanced_step" data-step="2">

                                <h3>Do you want to add special instructions?</h3>

                                <div class="advanced_body">

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-sub custom-control-input option-checkbox" id="trip_additional_note_yes" value="yes" name="trip_additional_note">
                                        <label class="custom-control-label" for="trip_additional_note_yes">
                                            <p>Yes</p>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="trip_additional_note_no" value="no" name="trip_additional_note">
                                        <label class="custom-control-label" for="trip_additional_note_no">
                                            <p>No</p>
                                        </label>
                                    </div>

                                    <div class="mt-5 modal-step-sub" style="display: none;">
                                        <textarea style="padding:15px;" autofocus="" name="trip_additional_note_text" id="trip_additional_note_text" maxlength="500" cols="" class="modal-step-required dashboard-form-textarea-control input-lg" rows="6" placeholder="Add Note" spellcheck="false"></textarea>
                                    </div>
                                </div>


                            </div>
                            <div class="advanced_section advanced_step" data-step="3">

                                <h3>Do you want to attach a document?</h3>

                                <div class="advanced_body">

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-sub custom-control-input option-checkbox" id="trip_additional_docs_yes" value="yes" name="trip_additional_docs">
                                        <label class="custom-control-label" for="trip_additional_docs_yes">
                                            <p>Yes</p>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="trip_additional_docs_no" value="no" name="trip_additional_docs">
                                        <label class="custom-control-label" for="trip_additional_docs_no">
                                            <p>No</p>
                                        </label>
                                    </div>

                                    <div class="mt-5 modal-step-sub" style="display: none;">
                                        <p>Please, upload your document</p>

                                        <div class="d-flex justify-content-between p-0 align-items-center w-35 gray-border-left">
                                            <input type="file" class="modal-step-required timeline_document" id="trip_additional_docs_file" name="trip_additional_docs_file" accept="application/msword, application/vnd.ms-powerpoint,text/plain, application/pdf, image/*">

                                            <label class="upload-button" for="trip_additional_docs_file">
                                                <span>Upload file</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                    <div class="advanced_footer">
                        <button style="display: none;" class="btn btn-info modal-btn-back">Back</button>
                        <button type="submit" class="btn btn-danger modal-btn-next">Next</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="modal fade" id="edit_timeline_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-plan-lg" role="document">

            <form id="edit_timeline_form">
                <div class="modal-content">
                    <div class="modal-body connect-bg-ground text-center advanced-plan-body">

                        <div class="advanced_steps">
                            <div style="display: none;" class="modal-step-error">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 16H12.01M12 8V12M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#FF0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>Please, fill required fields</span>
                            </div>

                            <div class="advanced_section advanced_step" data-step="1">

                                <h3>Would you like to add a check-in requirement for this event?</h3>
                                <p>Select a option for your event.</p>

                                <div class="advanced_body">

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="edit_timeline_checkin_yes" value="yes" name="edit_timeline_checkin">
                                        <label class="custom-control-label" for="edit_timeline_checkin_yes">
                                            <p>Yes</p>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="edit_timeline_checkin_no" value="no" name="edit_timeline_checkin">
                                        <label class="custom-control-label" for="edit_timeline_checkin_no">
                                            <p>No</p>
                                        </label>
                                    </div>
                                </div>


                            </div>
                            <div class="advanced_section advanced_step" data-step="2">

                                <h3>Do you want to add special instructions?</h3>

                                <div class="advanced_body">

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-sub custom-control-input option-checkbox" id="edit_timeline_note_yes" value="yes" name="edit_timeline_note">
                                        <label class="custom-control-label" for="edit_timeline_note_yes">
                                            <p>Yes</p>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="edit_timeline_note_no" value="no" name="edit_timeline_note">
                                        <label class="custom-control-label" for="edit_timeline_note_no">
                                            <p>No</p>
                                        </label>
                                    </div>

                                    <div class="mt-5 modal-step-sub" style="display: none;">
                                        <textarea style="padding:15px;" autofocus="" name="edit_timeline_note_text" id="edit_timeline_note_text" maxlength="500" cols="" class="modal-step-required dashboard-form-textarea-control input-lg" rows="6" placeholder="Add Note" spellcheck="false"></textarea>
                                    </div>
                                </div>


                            </div>
                            <div class="advanced_section advanced_step" data-step="3">

                                <h3>Do you want to attach a document?</h3>

                                <div class="advanced_body">

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-sub custom-control-input option-checkbox" id="edit_timeline_document_yes" value="yes" name="edit_timeline_document">
                                        <label class="custom-control-label" for="edit_timeline_document_yes">
                                            <p>Yes</p>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="edit_timeline_document_no" value="no" name="edit_timeline_document">
                                        <label class="custom-control-label" for="edit_timeline_document_no">
                                            <p>No</p>
                                        </label>
                                    </div>

                                    <div class="mt-5 modal-step-sub" style="display: none;">
                                        <p>Please, upload your document</p>

                                        <div class="d-flex justify-content-between p-0 align-items-center w-35 gray-border-left">
                                            <input type="file" class="modal-step-required timeline_document" id="edit_timeline_document_file" name="edit_timeline_document_file" accept="application/msword, application/vnd.ms-powerpoint,text/plain, application/pdf, image/*">

                                            <label class="upload-button" for="edit_timeline_document_file">
                                                <span>Upload file</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                    <div class="advanced_footer">
                        <button style="display: none;" class="btn btn-info modal-btn-back">Back</button>
                        <button type="submit" class="btn btn-danger modal-btn-next">Next</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="modal fade cmodal modal-blur" data-backdrop="true" id="update_schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Edit Schedule</h4>
                </div>
                <form id="timeline_form_update">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">

                                <div class="form-group custom-group">
                                    <p class="event-title">Event Title</p>
                                    <input name="timeline_name" id="e_timeline_name" type="text" maxlength="150" class="timeline-input update_item" placeholder="Add New Event" class="dashboard-form-control input-lg">

                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">

                                <div class="form-group custom-group">
                                    <p class="event-title">Event Date</p>
                                    <input type="text" id="e_timeline_date" name="timeline_date" class="form-control event_date">

                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">

                                <div class="form-group custom-group">
                                    <p class="event-title">Event Time</p>
                                    <div class="time-input">
                                        <input type="time" id="e_timeline_time" name="timeline_time" class="form-control timer-input update_item" tabindex="53" placeholder="1 : 30 AM">
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">

                                <div class="form-group custom-group">
                                    <button type="button" class="btn btn-info edit-timeline-button">Update note and/or document</button>
                                </div>

                            </div>

                        </div>

                        <input type="hidden" id="item_id" name="item_id" readonly>
                        <input type="hidden" id="plan_linked_flag" name="plan_linked_flag" readonly>


                    </div>
                    <div class="modal-footer">
                        <button type="update" id="cropImageBtn" class="btn btn-primary update_submit_button">Update</button>
                        <button type="button" id="close_edit_timeline" class="btn btn-danger btn-close-modal" data-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>




    <?PHP
    $scale = 'METRIC';
    if ($userdata['scale'] == 'imperial') {
        $scale = 'IMPERIAL';
    }
    if (!empty($trip->trip_location_from_latlng_flightportion)) {
        //        print_r($trip);
        $tmp = str_replace('(', '', $trip->trip_location_from_latlng_flightportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_from_flightportion = $tmp[0];
        $lng_from_flightportion = $tmp[1];
        $tmp = str_replace('(', '', $trip->trip_location_to_latlng_flightportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_to_flightportion = $tmp[0];
        $lng_to_flightportion = $tmp[1];
    }
    ?>

    <script src="<?= SITE; ?>assets/js/moment.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.5/dist/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <script src="https://momentjs.com/downloads/moment.js"></script>

    <?php include('new_backend_footer.php'); ?>

    <script src="<?php echo SITE; ?>js/utils/modal-stepper.js?v=20230814721"></script>
    <script src="<?php echo SITE; ?>js/trip_timeline_next.js?v=2023081723bbggg"></script>

    <script>

        var map = null;
        var bounds = null;
        var directionsService = null;
        var directionsDisplay = null;
        <?php
        if ($trip->location_multi_waypoint_latlng) {
            $location_multi_waypoint_latlng = $trip->location_multi_waypoint_latlng;
        } else {
            $location_multi_waypoint_latlng = '[]';
        }
        if ($trip->trip_via_waypoints) {
            $trip_via_waypoints = $trip->trip_via_waypoints;
        } else {
            $trip_via_waypoints = '[]';
        }
        ?>
        var location_multi_waypoint = <?php echo $location_multi_waypoint_latlng; ?>;

        function initMap() {
            directionsService = new google.maps.DirectionsService();
            directionsDisplay = new google.maps.DirectionsRenderer({
                polylineOptions: {
                    strokeColor: "#0688E9"
                }
            });
            map = new google.maps.Map(document.getElementById('map'), {
                mapTypeControl: false,
                center: {
                    lat: 40.730610,
                    lng: -73.968285
                },
                gestureHandling: "cooperative",
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: 7
            });
            var bounds2 = new google.maps.LatLngBounds();

            var input = document.getElementById('event_address');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                // place variable will have all the information you are looking for.

                var location_lat = place.geometry['location'].lat();
                var location_lng = place.geometry['location'].lng();

                $('#lat_to').val(location_lat);
                $('#lng_to').val(location_lng);

                $('#plan_linked').val(1);
            });


            <?php
            if ($trip->trip_transport == 'plane') {
            $lat_from_plane = $lat_from;
            $lng_from_plane = $lng_from;
            $lat_to_plane = $lat_to;
            $lng_to_plane = $lng_to;
            ?>
            bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>));
            bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>));
            new DrawPlaneRoutes(map, <?PHP echo $lat_from_plane; ?>, <?PHP echo $lng_from_plane; ?>, <?PHP echo $lat_to_plane; ?>, <?PHP echo $lng_to_plane; ?>, <?php echo $location_multi_waypoint_latlng; ?>, 'flight');
            <?php } ?>

            <?php
            if ($trip->trip_location_to_flightportion) {
            $lat_from_plane = $lat_from_flightportion;
            $lng_from_plane = $lng_from_flightportion;
            $lat_to_plane = $lat_to_flightportion;
            $lng_to_plane = $lng_to_flightportion;

            ?>
            bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>));
            new DrawPlaneRoutes(map, <?PHP echo $lat_from_plane; ?>, <?PHP echo $lng_from_plane; ?>, <?PHP echo $lat_to_plane; ?>, <?PHP echo $lng_to_plane; ?>, <?php echo $location_multi_waypoint_latlng; ?>, 'portion');
            <?php } ?>

            <?php
            $tmp_index = count(json_decode($trip->location_multi_waypoint_latlng) ?? []);
            if ($trip->trip_location_to_flightportion || $trip->trip_location_to_drivingportion || $trip->trip_location_to_trainportion) {
                $start_marker = $markerAlpaArr[$tmp_index + 1];
                $end_marker =  $markerAlpaArr[$tmp_index + 2];
            } else {
                $start_marker = $markerAlpaArr[$tmp_index];
                $end_marker =  $markerAlpaArr[$tmp_index + 1];
            }

            //var_dump($start_marker);

            if ($trip->trip_transport == 'vehicle') {
            $vehicle_location_from = 'location_from';
            $vehicle_location_to = 'location_to';
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
            ?>
            console.log('vehicle')
            bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>));
            bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>));
            new AutocompleteDirectionsHandler(map, 'driving', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, <?php echo $location_multi_waypoint_latlng; ?>, <?php echo $trip_via_waypoints ?>, false);
            <?php } ?>

            <?php
            if ($trip->trip_location_to_drivingportion) {
            $vehicle_location_from = 'trip_location_from_drivingportion';
            $vehicle_location_to = 'trip_location_to_drivingportion';
            $tmp = str_replace('(', '', $trip->trip_location_from_latlng_drivingportion); // Ex: (25.7616798, -80.19179020000001)
            $tmp = str_replace(')', '', $tmp);
            $tmp = explode(',', $tmp);
            $lat_fromd = $tmp[0];
            $lng_fromd = $tmp[1];
            $tmp = str_replace('(', '', $trip->trip_location_to_latlng_drivingportion); // Ex: (25.7616798, -80.19179020000001)
            $tmp = str_replace(')', '', $tmp);
            $tmp = explode(',', $tmp);
            $lat_tod = $tmp[0];
            $lng_tod = $tmp[1];

            $driving_start_indicate = "on";

            if (($lat_to == $lat_fromd) &&  ($lng_to == $lng_fromd)) {
                $driving_start_indicate = "off";
            }
            $marker_set = $end_marker;
            if ($trip->itinerary_type == "event") {
                $marker_set = $markerAlpaArr[1];
            }


            ?>
            bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_tod; ?>, <?PHP echo $lng_tod; ?>));
            new AutocompleteDirectionsHandler(map, 'driving', <?PHP echo $lat_fromd; ?>, <?PHP echo $lng_fromd; ?>, <?PHP echo $lat_tod; ?>, <?PHP echo $lng_tod; ?>, [], [], true, "<?PHP echo $marker_set; ?>", "<?PHP echo $driving_start_indicate; ?>");
            <?php } ?>

            <?php
            if ($trip->trip_transport == 'train') {
            $train_location_from = 'location_from';
            $train_location_to = 'location_to';
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
            ?>
            bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>));
            bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>));
            new AutocompleteDirectionsHandler(map, 'train', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, <?php echo $location_multi_waypoint_latlng; ?>, <?php echo $trip_via_waypoints ?>, false);
            <?php } ?>
            <?php
            if ($trip->trip_location_to_trainportion) {
            $train_location_from = 'trip_location_from_trainportion';
            $train_location_to = 'trip_location_to_trainportion';
            $tmp = str_replace('(', '', $trip->trip_location_from_latlng_trainportion); // Ex: (25.7616798, -80.19179020000001)
            $tmp = str_replace(')', '', $tmp);
            $tmp = explode(',', $tmp);
            $lat_fromd = $tmp[0];
            $lng_fromd = $tmp[1];
            $tmp = str_replace('(', '', $trip->trip_location_to_latlng_trainportion); // Ex: (25.7616798, -80.19179020000001)
            $tmp = str_replace(')', '', $tmp);
            $tmp = explode(',', $tmp);
            $lat_tod = $tmp[0];
            $lng_tod = $tmp[1];

            ?>
            bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>));
            new AutocompleteDirectionsHandler(map, 'train', <?PHP echo $lat_fromd; ?>, <?PHP echo $lng_fromd; ?>, <?PHP echo $lat_tod; ?>, <?PHP echo $lng_tod; ?>, <?php echo $location_multi_waypoint_latlng; ?>, <?php echo $trip_via_waypoints ?>, true, "<?PHP echo $end_marker; ?>");
            <?php } ?>

            map.fitBounds(bounds2);

        }


        $(window).on('load', function() {
            $('#schedule-modal2').modal('show');
            $('#loading_list').hide();
        });

        $('.event_date').datepicker({
            templates: {
                leftArrow: '<i class="fa fa-chevron-left"></i>',
                rightArrow: '<i class="fa fa-chevron-right"></i>'
            },
            format: 'mm/dd/yyyy',
            keyboardNavigation: false,
            autoclose: true,
            todayHighlight: true,
            disableTouchKeyboard: true,
            orientation: "auto"
        });

        function toggle_edit_form(id) {
            var e = document.getElementById(id);
            if (e.style.display == 'block')
                e.style.display = 'none';
            else
                e.style.display = 'block';
        }

        $('input[name="option_requirement"]').click(function() {
            if ($(this).val() === 'yes' && $(this).is(':checked')) {
                $('input[name="option_requirement"]').not(this).prop('checked', false);
                $("#checkin_flag").val(1);
            } else {
                $('input[name="option_requirement"]').not(this).prop('checked', false);
                $('#advance_check').val(0);
                $("#checkin_flag").val(0);
            }
        });


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

<?php if ($trip->getRole($id_trip) != TripPlan::ROLE_COLLABORATOR) { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            function hideButtons() {
                if ($(".event_edit_button").length || $(".event_action_button").length) {
                    $(".event_edit_button").hide();
                    $(".event_action_button").hide();
                    clearInterval(checkInterval);
                }
            }
            var checkInterval = setInterval(hideButtons, 500);
        });
    </script>
<?php } ?>
</body>

</html>