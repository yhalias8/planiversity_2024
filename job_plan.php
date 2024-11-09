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
        .itinerary_section {
            margin-top: 24px;
        }

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

        .frm-grp {
            position: relative;
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

    <?php
    //include('include_header.php')
    include('new_backend_header.php');
    include('new_backend_footer.php');
    include_once('includes/top_bar.php');
    ?>
    </header>




    <div data-backdrop="false" id="schedule-modal" class="modal fade bs-example-modal-lg modal-to-and-from toandfrom pt-3 location_form_1 create_itinerary" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;top: 105px">

        <div class="modal-dialog modal-rounded-sm modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-0">
            <div class="modal-content modal-rounded-sm modal-content-white">
                <div class="modal-header pt-2 itinerary-header">
                    <button type="button" class="close" aria-hidden="true">-</button>

                    <div class="heading_section">
                        <p>PLANVERSITY</p>
                        <h4 class="modal-title" id="myLargeModalLabel">Build Your Job Itinerary</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-body connect-bg-ground" id="schedule-modal-body">

                    <fieldset id="build_place">
                        <form id="myForm" action="#" method="POST" class="main_form">
                            <input type="hidden" name="itinerary_type" value="job">

                            <div class="itinerary_tab">

                                <div class="itinerary_section">

                                    <h3>Do you want to include a short job summary?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="document_option_yes" name="document_option" value="yes" class="regular-checkbox big-checkbox" /><label for="document_option_yes"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="document_option_no" name="document_option" value="no" class="regular-checkbox big-checkbox" /><label for="document_option_no"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="document_option-error" class="mvalidy error" for="document_option_value"></label>
                                                </div>
                                                <input type="text" name="document_option_value" id="document_option_value" class='input_option_opacity input_reset' readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="document_section" style="display:none;">

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-8 mx-auto">
                                                    <div class="form-group frm-grp">
                                                        <textarea style="padding:15px;" autofocus="" name="location_note" id="location_note" maxlength="500" cols="" class="dashboard-form-textarea-control input-lg" rows="6" placeholder="Add Job summary" spellcheck="false"></textarea>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="itinerary_tab">
                                <div class="itinerary_section">
                                    <h3>What is the location of the Job?</h3>
                                    <p>Enter your job address point</p>
                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options text-left">

                                                    <div class="row">

                                                        <div class="col-12 col-lg-6 mx-auto">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Job location</label>
                                                                <input type="text" name="location_to" id="location_to" class="dashboard-form-control form-control input-lg clearable" placeholder="Enter location">
                                                                <button class="close-icon from-part" type="button"></button>
                                                            </div>
                                                        </div>

                                                        <input name="location_from" id="location_from" class="inp1 input_reset" type="hidden" readonly>
                                                        <input name="location_from_latlng" id="location_from_latlng" class="inp1 input_reset" type="hidden" readonly>
                                                        <input name="location_to_latlng" id="location_to_latlng" class="inp1 input_reset" type="hidden" readonly>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">

                                    <h3>Is this job a residence or business?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="location_category_residence" name="location_category" value="residence" class="regular-checkbox big-checkbox" /><label for="location_category_residence"></label>
                                                        <p>Residence</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="location_category_business" name="location_category" value="business" class="regular-checkbox big-checkbox" /><label for="location_category_business"></label>
                                                        <p>Business</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="location_category_value-error" class="mvalidy error event_option_value_error" for="location_category_value"></label>
                                                </div>
                                                <input type="text" name="location_category_value" id="location_category_value" class='input_option_opacity input_reset' readonly>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>


                            <div class="itinerary_tab">

                                <div class="itinerary_section">

                                    <h3>How long will this job take?</h3>
                                    <p>Add details for your job schedule.</p>
                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="event_option_one" name="event_option" value="one" class="regular-checkbox big-checkbox" /><label for="event_option_one"></label>
                                                        <p>One day</p>
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
                                        <h3>What is the date of your job?</h3>
                                        <p>Enter your date</p>

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">

                                                    <div class="options option-padding-60 text-left">

                                                        <div class="row justify-content-center form-group frm-grp trip-grp">
                                                            <div class="col-12 col-lg-6 location-date_start">
                                                                <label class="mr-b-10">Starting date <span class="label_info">(optional)</span></label>
                                                                <div class="date_picker_item">
                                                                    <input type="text" class="dashboard-form-control input-lg date_calculation" placeholder="Date of job" name="location_date_start" autocomplete="off" id="location_date_start" data-date-format="mm/dd/yyyy">
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-lg-6 location-date_end">
                                                                <label class="mr-b-10">Ending date <span class="label_info">(optional)</span></label>
                                                                <div class="date_picker_item">
                                                                    <input type="text" class="dashboard-form-control input-lg date_calculation" placeholder="End date of job" name="location_date_end" autocomplete="off" id="location_date_end" data-date-format="mm/dd/yyyy">
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
                                    <h3>Do you want to add start and stop time?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="start-stop-yes" name="start-stop" value="yes" class="regular-checkbox big-checkbox" /><label for="start-stop-yes"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="start-stop-no" name="start-stop" value="no" class="regular-checkbox big-checkbox" /><label for="start-stop-no"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="start_stop_value-error" class="mvalidy error start-stop_value_error" for="start_stop_value"></label>
                                                </div>
                                                <input type="text" name="start_stop_value" id="start_stop_value" class='input_option_opacity input_reset' readonly>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="row justify-content-center start-stop-section" style="display: none;">
                                        <div class="col-md-12 col-lg-4">
                                            <div class="form-group frm-grp trip-grp">
                                                <label class="mr-b-10">Starting time (HH:MM) <span class="label_info">(optional)</span></label>
                                                <input type="time" class="dashboard-form-control input-lg" placeholder="Starting time" name="location_time_start" autocomplete="off" id="location_time_start">
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-lg-4">
                                            <div class="form-group frm-grp trip-grp">
                                                <label class="mr-b-10">Ending time (HH:MM) <span class="label_info">(optional)</span></label>
                                                <input type="time" class="dashboard-form-control input-lg" placeholder="Ending time" name="location_time_end" autocomplete="off" id="location_time_end">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <!-- <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Do you need to add car rental info to this job?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="car_rental_yes" name="car_rental" value="yes" class="regular-checkbox big-checkbox" /><label for="car_rental_yes"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="car_rental_no" name="car_rental" value="no" class="regular-checkbox big-checkbox" /><label for="car_rental_no"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="car_rental_value-error" class="mvalidy error car_rental_value_error" for="car_rental_value"></label>
                                                </div>
                                                <input type="text" name="car_rental_value" id="car_rental_value" class='input_option_opacity input_reset' readonly>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="car-rental-section" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="mr-b-10">Rental location <span class="label_info">(optional)</span></label>

                                                <div class="form-group frm-grp">
                                                    <input name="location_from_drivingportion" id="location_from_drivingportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Rental location">
                                                    <button class="close-icon all-portion portion" type="button" data-target="location_from_drivingportion"></button>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group frm-grp">
                                                    <label class="mr-b-10">Confirmation number <span class="label_info">(optional)</span></label>
                                                    <input name="car_rent_number" id="car_rent_number" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Confirmation Number">
                                                </div>
                                            </div>

                                            <input name="location_from_latlng_drivingportion" id="location_from_latlng_drivingportion" class="inp1 input_reset" type="hidden" readonly>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="mr-b-10">Renting from <span class="label_info">(optional)</span></label>
                                                <div class="date_picker_item">
                                                    <input type="text" class="dashboard-form-control input-lg date_calculation" placeholder="Renting from" name="car_rent_date_start" autocomplete="off" id="car_rent_date_start" data-date-format="mm/dd/yyyy">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="mr-b-10">Renting to <span class="label_info">(optional)</span></label>
                                                <div class="date_picker_item">
                                                    <input type="text" class="dashboard-form-control input-lg date_calculation" placeholder="Renting to" name="car_rent_date_end" autocomplete="off" id="car_rent_date_end" data-date-format="mm/dd/yyyy">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Do you need to add flight info to this job?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="flight_portion_yes" name="flight_portion" value="yes" class="regular-checkbox big-checkbox" /><label for="flight_portion_yes"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="flight_portion_no" name="flight_portion" value="no" class="regular-checkbox big-checkbox" /><label for="flight_portion_no"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="flight_portion_value-error" class="mvalidy error flight_portion_value_error" for="flight_portion_value"></label>
                                                </div>
                                                <input type="text" name="flight_portion_value" id="flight_portion_value" class='input_option_opacity input_reset' readonly>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="flight-portion-section" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="mr-b-10">Takeoff location <span class="label_info">(optional)</span></label>

                                                <div class="form-group frm-grp">

                                                    <input name="location_from_flightportion" id="location_from_flightportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Takeoff Location">
                                                    <button class="close-icon all-portion portion" type="button" data-target="location_from_flightportion"></button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="mr-b-10">Destination location <span class="label_info">(optional)</span></label>

                                                <div class="form-group frm-grp">

                                                    <input name="location_to_flightportion" id="location_to_flightportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Destination Location">
                                                    <button class="close-icon all-portion portion" type="button" data-target="location_to_flightportion"></button>
                                                </div>
                                            </div>
                                            <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1 input_reset" type="hidden" readonly>
                                            <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1 input_reset" type="hidden" readonly>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group frm-grp">
                                                    <label class="mr-b-10">Flight number <span class="label_info">(optional)</span></label>
                                                    <input name="flight_number" id="flight_number" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Flight Number">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group frm-grp">
                                                    <label class="mr-b-10">Confirmation number <span class="label_info">(optional)</span></label>
                                                    <input name="flight_confirmation_number" id="flight_confirmation_number" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Confirmation Number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group frm-grp trip-grp">
                                                    <label class="mr-b-10">Takeoff time (HH:MM) <span class="label_info">(optional)</span></label>
                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Takeoff time" name="flight_time_start" autocomplete="off" id="flight_time_start">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group frm-grp trip-grp">
                                                    <label class="mr-b-10">Landing time (HH:MM) <span class="label_info">(optional)</span></label>
                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Landing time" name="flight_time_end" autocomplete="off" id="flight_time_end">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Do you need to add train info to this job?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="train_portion_yes" name="train_portion" value="yes" class="regular-checkbox big-checkbox" /><label for="train_portion_yes"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="train_portion_no" name="train_portion" value="no" class="regular-checkbox big-checkbox" /><label for="train_portion_no"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="train_portion_value-error" class="mvalidy error train_portion_value_error" for="train_portion_value"></label>
                                                </div>
                                                <input type="text" name="train_portion_value" id="train_portion_value" class='input_option_opacity input_reset' readonly>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="train-portion-section" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="mr-b-10">From location <span class="label_info">(optional)</span></label>

                                                <div class="form-group frm-grp">
                                                    <input name="location_from_trainportion" id="location_from_trainportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Takeoff Location">
                                                    <button class="close-icon all-portion portion" type="button" data-target="location_from_trainportion"></button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="mr-b-10">To location <span class="label_info">(optional)</span></label>

                                                <div class="form-group frm-grp">
                                                    <input name="location_to_trainportion" id="location_to_trainportion" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Destination Location">
                                                    <button class="close-icon all-portion portion" type="button" data-target="location_to_trainportion"></button>
                                                </div>
                                            </div>
                                            <input name="location_from_latlng_trainportion" id="location_from_latlng_trainportion" class="inp1 input_reset" type="hidden" readonly>
                                            <input name="location_to_latlng_trainportion" id="location_to_latlng_trainportion" class="inp1 input_reset" type="hidden" readonly>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group frm-grp trip-grp">
                                                    <label class="mr-b-10">Departure time (HH:MM) <span class="label_info">(optional)</span></label>
                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Departure time" name="train_time_start" autocomplete="off" id="train_time_start">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group frm-grp trip-grp">
                                                    <label class="mr-b-10">Arrival time (HH:MM) <span class="label_info">(optional)</span></label>
                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Arrival time" name="train_time_end" autocomplete="off" id="train_time_end">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group frm-grp">
                                                    <label class="mr-b-10">Confirmation number <span class="label_info">(optional)</span></label>
                                                    <input name="train_confirmation_number" id="train_confirmation_number" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Confirmation Number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div> -->


                            <div class="itinerary_tab">

                                <div class="itinerary_section">

                                    <h3>Do you want to include written driving directions?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="directions_option_yes" name="directions_option" value="yes" class="regular-checkbox big-checkbox" /><label for="directions_option_yes"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="directions_option_no" name="directions_option" value="no" class="regular-checkbox big-checkbox" /><label for="directions_option_no"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="directions_option_value-error" class="mvalidy error event_option_value_error" for="directions_option_value"></label>
                                                </div>
                                                <input type="text" name="directions_option_value" id="directions_option_value" class='input_option_opacity input_reset' readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="directions_section" style="display:none;">

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="mr-b-10"> Where from <span class="label_info">(optional)</span></label>

                                                    <div class="form-group frm-grp">
                                                        <input name="location_where_from" id="location_where_from" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Where from">
                                                        <button class="close-icon all-portion portion" type="button" data-target="location_where_from"></button>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <label class="mr-b-10"> Where to <span class="label_info">(optional)</span></label>

                                                    <div class="form-group frm-grp">
                                                        <input name="location_where_to" id="location_where_to" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Where from">
                                                        <button class="close-icon all-portion portion" type="button" data-target="location_where_to"></button>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span id="distance_calculation"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="itinerary_tab">

                                <div class="itinerary_section">

                                    <h3>Do you need to add Point of contact details ?</h3>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">
                                                    <div class="option-control pr-5">
                                                        <input type="checkbox" id="contact_option_yes" name="contact_option" value="yes" class="regular-checkbox big-checkbox" /><label for="contact_option_yes"></label>
                                                        <p>Yes</p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="contact_option_no" name="contact_option" value="no" class="regular-checkbox big-checkbox" /><label for="contact_option_no"></label>
                                                        <p>No</p>
                                                    </div>
                                                </div>

                                                <div class="error_option">
                                                    <label id="contact_option_value-error" class="mvalidy error" for="contact_option_value"></label>
                                                </div>
                                                <input type="text" name="contact_option_value" id="contact_option_value" class='input_option_opacity input_reset' readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="contact_section" style="display:none;">

                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="mr-b-10"> Name <span class="label_info">(optional)</span></label>

                                                    <div class="form-group frm-grp">
                                                        <input name="location_contact_name" id="location_contact_name" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Name">
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <label class="mr-b-10"> Phone number <span class="label_info">(optional)</span></label>

                                                    <div class="form-group frm-grp">
                                                        <input name="location_contact_phone" id="location_contact_phone" type="number" class="dashboard-form-control form-control input-lg clearable" placeholder="Phone number">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="mr-b-10"> Email <span class="label_info">(optional)</span></label>

                                                    <div class="form-group frm-grp">
                                                        <input name="location_contact_email" id="location_contact_email" type="email" class="dashboard-form-control form-control input-lg clearable" placeholder="Email">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="itinerary_tab">

                                <div class="itinerary_section">
                                    <h3>Your plan information is nearly complete.</h3>
                                    <h6>Minimize this slide to confirm your location</h6>
                                    <p>Ready to plan?</p>
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
                                <span class="step"></span>
                                <span class="step"></span>
                            </div>

                            <!-- Multiform Navigation -->
                            <div style="overflow:auto;">
                                <div class="action_section">
                                    <div class="left-side">
                                        <button type="button" class="action_button previous">Back</button>
                                        <button type="button" class="action_button start_over">Start Over</button>
                                    </div>

                                    <div class="right-side">
                                        <button type="button" class="action_button next">Next</button>
                                        <button type="button" class="action_button submit">Create Plan</button>
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
        var userId = <?php echo  $userdata['id']; ?>;

        var val = {
            // Specify validation rules
            ignore: ':hidden:not(.option_validy)',
            rules: {
                location_from: {
                    required: true,
                },

                location_category_value: {
                    required: true,
                },

                event_option_value: {
                    required: true,
                },

                drive_option_value: {
                    required: true,
                },

                start_stop_value: {
                    required: true,
                },

                // car_rental_value: {
                //     required: true
                // },

                // flight_portion_value: {
                //     required: true
                // },

                // train_portion_value: {
                //     required: true
                // },

                directions_option_value: {
                    required: true,
                },

                contact_option_value: {
                    required: true
                }
            },
            // Specify validation error messages
            messages: {
                location_from: {
                    required: "Please type your event location",
                },

                location_category_value: {
                    required: "Please select a option",
                },

                event_option_value: {
                    required: "Please select a option",
                },

                start_stop_value: {
                    required: "Please select a option",
                },

                // car_rental_value: {
                //     required: "Please select a option",
                // },

                // flight_portion_value: {
                //     required: "Please select a option",
                // },

                // train_portion_value: {
                //     required: "Please select a option",
                // },

                directions_option_value: {
                    required: "Please select a option",
                },

                contact_option_value: {
                    required: "Please select a option",
                },
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


        /* Step check box toggle - START */
        $('input[type="checkbox"][name=event_option]').on('change', function() {
            $('input[type="checkbox"][name=event_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=start-stop]').on('change', function() {
            $('input[type="checkbox"][name=start-stop]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=drive_option]').on('change', function() {
            $('input[type="checkbox"][name=drive_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=car_rental]').on('change', function() {
            $('input[type="checkbox"][name=car_rental]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=flight_portion]').on('change', function() {
            $('input[type="checkbox"][name=flight_portion]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=train_portion]').on('change', function() {
            $('input[type="checkbox"][name=train_portion]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=directions_option]').on('change', function() {
            $('input[type="checkbox"][name=directions_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=location_category]').on('change', function() {
            $('input[type="checkbox"][name=location_category]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=contact_option]').on('change', function() {
            $('input[type="checkbox"][name=contact_option]').not(this).prop('checked', false);
        });
        /* Step check box toggle - END */


        /* MultiForm Handler - START */
        $('input[type=checkbox][name=hotel_room_option]').change(function() {
            if (this.value == 'yes') {
                $('.hotel_booking_section').delay(500).fadeIn();
            } else {
                $(".hotel_booking_section").hide();
            }

        });

        $('input[type=checkbox][name=event_option]').change(function() {

            $('#event_option_value').val(this.value);
            $('#event_option_value-error').html('');

            $('.single_event_section').fadeIn()

            if (this.value == 'one') {
                $('.location-date_end').hide();
            } else {
                $('.location-date_end').fadeIn();
            }
        });

        $('input[type=checkbox][name=start-stop]').change(function() {
            $('#start_stop_value').val(this.value);
            $('#start_stop_value-error').html('');

            if (this.value == 'yes') {
                $('.start-stop-section').fadeIn();
            } else {
                $('.start-stop-section').fadeOut();
                $('#location_time_start').val('');
                $('#location_time_end').val('');
                $('.next').click();
            }
        });

        $('input[type=checkbox][name=car_rental]').change(function() {

            $('#car_rental_value').val(this.value);
            $('#car_rental_value-error').html('');

            if (this.value == 'yes') {
                $('.car-rental-section').fadeIn();
            } else {
                $(".car-rental-section").hide();
                $('.next').click();
            }

        });


        $('input[type=checkbox][name=flight_portion]').change(function() {

            $('#flight_portion_value').val(this.value);
            $('#flight_portion_value-error').html('');

            if (this.value == 'yes') {
                $('.flight-portion-section').fadeIn();
            } else {
                $(".flight-portion-section").hide();
                $('.next').click();
            }

        });

        $('input[type=checkbox][name=train_portion]').change(function() {

            $('#train_portion_value').val(this.value);
            $('#train_portion_value-error').html('');

            if (this.value == 'yes') {
                $('.train-portion-section').fadeIn();
            } else {
                $(".train-portion-section").hide();
                $('.next').click();
            }

        });


        $('input[type=checkbox][name=drive_option]').change(function() {

            $('#drive_option_value').val(this.value);
            $('#drive_option_value-error').html('');

            if (this.value == 'yes') {
                $('.drive_entry_section').fadeIn();
            } else {
                $(".drive_entry_section").hide();
                $('.next').click();
            }

        });

        $('input[type=checkbox][name=directions_option]').change(function() {

            $('#directions_option_value').val(this.value);
            $('#directions_option_value-error').html('');

            if (this.value == 'yes') {
                $('.directions_section').fadeIn();
            } else {
                $(".directions_section").hide();
                $('.next').click();
            }

        });

        $('input[type=checkbox][name=location_category]').change(function() {

            $('#location_category_value').val(this.value);
            $('#location_category_value-error').html('');

            $('.next').click();
        });

        $('input[type=checkbox][name=contact_option]').change(function() {

            $('#contact_option_value').val(this.value);
            $('contact_option_value-error').html('');

            if (this.value == 'yes') {
                $('.contact_section').fadeIn();
            } else {
                $(".contact_section").hide();
                $('.next').click();
            }

        });
        /* MultiForm Handler - END */
    </script>

    <script>
        $(document).ready(function () {
            $('input[type=checkbox][name=document_option]').change(function() {
                $('input[type="checkbox"][name=document_option]').not(this).prop('checked', false);
                $('#document_option_value').val(this.value);
                $('#document_option-error').html('');

                if (this.value == 'yes') {
                    $('.document_section').fadeIn();
                } else {
                    $(".document_section").hide();
                    $('.next').click();
                }

            });
        });
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

        document.addEventListener('DOMContentLoaded', function () {
            const locationWhereFrom = document.getElementById('location_where_from');
            const locationWhereTo = document.getElementById('location_where_to');
            const calculateButton = document.getElementById('calculate_distance');

            // Initialize Google Places Autocomplete
            const autocompleteFrom = new google.maps.places.Autocomplete(locationWhereFrom);
            const autocompleteTo = new google.maps.places.Autocomplete(locationWhereTo);

            // Set up event listeners
            autocompleteFrom.addListener('place_changed', calculateDistance);
            autocompleteTo.addListener('place_changed', calculateDistance);
            calculateButton.addEventListener('click', calculateDistance);

            function calculateDistance() {
                const placeFrom = autocompleteFrom.getPlace();
                const placeTo = autocompleteTo.getPlace();
                const locationFromValue = locationWhereFrom.value;
                const locationToValue = locationWhereTo.value;

                if (!locationFromValue || !locationToValue) {
                    console.log('One or both of the locations are not selected or invalid.');
                    return;
                }

                const geocoder = new google.maps.Geocoder();

                // Geocode the from location if necessary
                const geocodeFrom = placeFrom ? Promise.resolve({ results: [{ geometry: placeFrom.geometry }] }) : geocodeAddress(locationFromValue);
                // Geocode the to location if necessary
                const geocodeTo = placeTo ? Promise.resolve({ results: [{ geometry: placeTo.geometry }] }) : geocodeAddress(locationToValue);

                Promise.all([geocodeFrom, geocodeTo]).then(results => {
                    const origin = results[0].results[0].geometry.location;
                    const destination = results[1].results[0].geometry.location;

                    const service = new google.maps.DistanceMatrixService();
                    service.getDistanceMatrix(
                        {
                            origins: [origin],
                            destinations: [destination],
                            travelMode: google.maps.TravelMode.DRIVING,
                        },
                        (response, status) => {
                            if (status !== 'OK') {
                                console.error('Error with distance matrix service:', status);
                                return;
                            }

                            const result = response.rows[0].elements[0];
                            if (result.status === 'OK') {
                                const distanceInMiles = result.distance.value * 0.000621371;
                                const duration = result.duration.value;
                                const durationFormatted = formatDuration(duration);
                                $("#distance_calculation").html(" <p style='text-align:left'><span style='font-weight:bold'>Driving distance  </span> : " + distanceInMiles.toFixed(2) + " mi</p><p style='text-align:left'><span style='font-weight:bold'> Duration </span> : " + durationFormatted + ".</p > ");

                            } else {
                               // console.log('Distance calculation failed:', result.status);
                            }
                        }
                    );
                }).catch(error => {
                   // console.log('Geocoding error:', error);
                });
            }

            function geocodeAddress(address) {
                const geocoder = new google.maps.Geocoder();
                return new Promise((resolve, reject) => {
                    geocoder.geocode({ address }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            resolve({ results });
                        } else {
                            reject(status);
                        }
                    });
                });
            }

            function formatDuration(seconds) {
                const days = Math.floor(seconds / 86400);
                seconds %= 86400;
                const hours = Math.floor(seconds / 3600);
                seconds %= 3600;
                const minutes = Math.floor(seconds / 60);

                let durationString = "";
                if (days > 0) {
                    durationString += `${days} days `;
                }
                if (hours > 0) {
                    durationString += `${hours} hours `;
                }
                if (minutes > 0) {
                    durationString += `${minutes} minutes`;
                }
                return durationString.trim();
            }
        });


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

            var inputLocationWhere = document.getElementById('location_where_from');
            var inputLocationTo = document.getElementById('location_where_to');

            var inputLocationWhereAutocomplete = new google.maps.places.Autocomplete(inputLocationWhere);

            var inputLocationToAutocomplete = new google.maps.places.Autocomplete(inputLocationTo);

            this.setupAlgoliaPlaceChangedListenerPortion(inputLocationWhereAutocomplete, 'ORIG', 'DRIVING');
            this.setupAlgoliaPlaceChangedListenerPortion(inputLocationToAutocomplete, 'DEST', 'DRIVING');
            

            if (transportWay === 'train') {
                // var originInput_dportion = document.getElementById('location_from_drivingportion');
                // var destinationInput_dportion = document.getElementById('location_to_drivingportion');

                // var origin_dportionAutocomplete = new google.maps.places.Autocomplete(originInput_dportion);

                // var destination_dportionAutocomplete = new google.maps.places.Autocomplete(destinationInput_dportion);

                // this.setupAlgoliaPlaceChangedListenerPortion(origin_dportionAutocomplete, 'ORIG', 'DRIVING');
                // this.setupAlgoliaPlaceChangedListenerPortion(destination_dportionAutocomplete, 'DEST', 'DRIVING');
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
                        document.getElementById('location_from_latlng').value = place.geometry.location;
                        document.getElementById('location_from').value = place.formatted_address;

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

                    if (mode === 'DEST') {
                        me.originPlaceId = place.place_id;
                        me.originPlaceLocation = place.geometry.location;
                        me.destinationAddress = place.formatted_address;

                        me.destinationPlaceId = place.place_id;
                        me.destinationPlaceLocation = place.geometry.location;
                        me.destination_dportionPlaceId = place.place_id;
                        me.destination_dportionPlaceLocation = place.geometry.location;

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
                document.getElementById('location_from_latlng').value = me.origin_dportionPlaceLocation;
                document.getElementById('location_to_latlng').value = me.destination_dportionPlaceLocation;
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

            document.getElementById('location_from').value = me.destinationAddress;
            document.getElementById('location_from_latlng').value = me.originPlaceLocation;
            document.getElementById('location_to_latlng').value = me.destinationPlaceLocation;
            document.getElementById('location_to').value = me.destinationAddress;
            document.getElementById('location_where_to').value = me.destinationAddress;
            //document.getElementById('location_where_from').value = me.destinationAddress;

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

        function toggleTransportVisibility(id) {
            var e = document.getElementById(id);
            if (e.style.display == 'block') {
                e.style.display = 'none';
            } else {
                e.style.display = 'block';
                setDrivingAndTrain();
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



        $(document).on('input', '.clearable', function() {
            $(this)[tog(this.value)]('x');
        }).on('mousemove', '.x', function(e) {
            $(this)[tog(this.offsetWidth - 18 < e.clientX - this.getBoundingClientRect().left)]('onX');
        }).on('touchstart click', '.onX', function(ev) {
            ev.preventDefault();
            $(this).removeClass('x onX').val('').change();
        });

        $(window).on('load', function() {
            $('#schedule-modal').modal('show');
            $("#return").hide();
        });

        $('#flyover_save').click(function() {
            $(".close").click();
        });

        const inputMap = new Map();
        const markersMap = new Map();


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



        /* Date Picker start */

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


            let startDate = $('#location_date_start').datepicker('getDate');
            let start_date = moment(startDate).format("YYYY-MM-DD");

            var returnDate = $('#location_date_end').datepicker('getDate');
            var return_date = moment(returnDate).format("YYYY-MM-DD");

            if ((startDate) && (returnDate)) {
                customClasses = [];
                date_range_calculation(start_date, return_date, customClasses);

            } else if (startDate) {

                customClasses = [];
                customClasses.push(start_date);

            } else if (returnDate) {

                customClasses = [];
                customClasses.push(return_date);

            } else if (startDate) {
                customClasses = [];
                customClasses.push(start_date);

            } else {

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
        /* Date Picker end */
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript" src="https://unpkg.com/@duffel/components@latest/dist/CardPayment.umd.min.js"></script>
    <script type="text/javascript" src="<?= SITE; ?>js/home-flight.js"></script>

</body>

</html>