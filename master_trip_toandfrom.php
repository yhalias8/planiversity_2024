<?php
session_start();
include_once("config.ini.php");
include_once("config.ini.curl.php");
//include_once('config.php');

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/origin-destination/' . $_GET['transport'];
    header("Location:" . SITE . "login");
}

if (!isset($_GET["idtrip"]) && !isset($_GET['transport']))
    header("Location:" . SITE . "trip/how-are-you-traveling");

$transport = (isset($_GET['transport']) && !empty($_GET['transport'])) ? $_GET['transport'] : '';

$trip = '';
$travelmode = 'DRIVING';
$label = 'Flight';
$trip_label = 'flight';
$label_station = 'Airport';
$vihicle_place_holder = "";
$multiCityLabel = "Multi City";
$oneWayLabel = "One Way";
$extra_portion = "drive or train";
$distance_mode = 'Plane';

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
        $distance_mode = 'Driving';
        break;
    case 'train':
        $travelmode = 'TRANSIT';
        $label = 'Train';
        $trip_label = 'train';
        $label_station = 'Railway Station';
        $oneWayLabel = "One Way";
        $extra_portion = "drive or plane";
        $distance_mode = 'Train';
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
        var SITE = '<?php echo SITE; ?>';
    </script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?php echo SITE; ?>assets/css/multi-form.css?v=20230621" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/app-style.css?v=20230621" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/@duffel/components@latest/dist/CardPayment.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <script src="<?php echo SITE; ?>js/jquery.validate.min.js"></script>
    <script src="<?php echo SITE; ?>js/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/multi-form.js?v=2020231"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="<?= SITE; ?>js/global.js?v=20230518"></script>

    <style>
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
        .trip_info {
            display: block;
        }

        .trip_info p {
            margin: 0;
            font-size: 18px;
        }

        .trip_info p span {
            font-weight: bold;
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
    include_once('includes/top_bar.php');

    ?>
    </header>




    <div data-backdrop="false" id="schedule-modal" class="modal fade bs-example-modal-lg modal-to-and-from toandfrom pt-3 location_form_1 create_itinerary" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;top: 105px">

        <div class="modal-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-0">
            <div class="modal-content modal-content-white">
                <div class="modal-header pt-2 itinerary-header">
                    <button type="button" class="close" aria-hidden="true">-</button>

                    <div class="heading_section">
                        <p>PLANVERSITY</p>
                        <h4 class="modal-title" id="myLargeModalLabel">Build Your Itinerary</h4>
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
                            <div class="trip-item <?= $transport == 'vehicle' ? 'active' : null ?>">
                                <a href="<?= SITE ?>trip/origin-destination/vehicle">
                                    <div class="">
                                        <img src="<?= SITE ?>assets/images/by_vical_icon.png" class="">
                                    </div>
                                    <p class="text-center trip-mode-text">Vehicle</p>
                                </a>
                            </div>
                            <div class="trip-item <?= $transport == 'plane' ? 'active' : null ?>">
                                <a href="<?= SITE ?>trip/origin-destination/plane">
                                    <div class="">
                                        <img src="<?= SITE ?>assets/images/by_plan_icon.png" class="">
                                    </div>
                                    <p class="text-center trip-mode-text">Plane</p>
                                </a>
                            </div>
                            <div class="trip-item <?= $transport == 'train' ? 'active' : null ?>">
                                <a href="<?= SITE ?>trip/origin-destination/train">
                                    <div class="">
                                        <img src="<?= SITE ?>assets/images/by_train_icon.png" class="">
                                    </div>
                                    <p class="text-center trip-mode-text">Train</p>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-body connect-bg-ground" id="schedule-modal-body">


                    <fieldset id="build_place">
                        <form id="myForm" action="#" method="POST" class="main_form">

                            <!-- One "tab" for each step in the form: -->
                            
                            <div class="itinerary_tab">
                                <div class="itinerary_section">
                                    <h3>Type of Trip?</h3>
                                    <p>What type of trip are you taking?</p>
                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options">

                                                    <?php if ($transport == 'plane') { ?>
                                                        <div class="option-control">
                                                            <input type="checkbox" id="trip_mode_one" name="location_triptype" value="r" class="regular-checkbox big-checkbox" /><label for="trip_mode_one"></label>
                                                            <p>Round Trip</p>
                                                        </div>
                                                    <?php }  ?>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="trip_mode_two" name="location_triptype" value="o" class="regular-checkbox big-checkbox" /><label for="trip_mode_two"></label>
                                                        <p><?= $oneWayLabel; ?></p>
                                                    </div>

                                                    <div class="option-control">
                                                        <input type="checkbox" id="trip_mode_three" name="location_triptype" value="m" class="regular-checkbox big-checkbox" /><label for="trip_mode_three"></label>
                                                        <p><?= $multiCityLabel; ?></p>
                                                    </div>

                                                </div>

                                                <div class="error_option">
                                                    <label id="trip_mode_value-error" class="mvalidy error trip_mode_value_error" for="trip_mode_value"></label>
                                                </div>
                                                <input type="text" name="trip_mode_value" id="trip_mode_value" class='input_option_opacity input_reset' readonly>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>



                            <div class="itinerary_tab">
                                <div class="itinerary_section">
                                    <h3>Where will you start and end your journey?</h3>
                                    <p>Enter your start and end point desitnation?</p>
                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options option-padding-60 text-left">

                                                    <div class="row">

                                                        <div class="col-md-12 col-lg-6">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Starting <?php echo $label_station; ?></label>
                                                                <input type="text" name="location_from" id="location_from" class="dashboard-form-control form-control input-lg clearable" placeholder="Enter Starting <?php echo $label_station; ?>">
                                                                <button class="close-icon from-part" type="reset"></button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-lg-6">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Destination <?php echo $label_station; ?></label>
                                                                <input type="text" name="location_to" id="location_to" class="clearable dashboard-form-control input-lg" placeholder=" <?php echo $transport == 'vehicle' ? $vihicle_place_holder : 'Enter Destination ' . $label_station; ?>">
                                                                <button class="close-icon from-part" type="reset"></button>
                                                            </div>
                                                        </div>
                                                        <input name="location_from_latlng" id="location_from_latlng" class="inp1 input_reset" type="hidden" readonly>
                                                        <input name="location_to_latlng" id="location_to_latlng" class="inp1 input_reset" type="hidden" readonly>
                                                        <input name="location_multi_waypoint_latlng" id="location_multi_waypoint_latlng" class="inp1 input_reset" type="hidden" readonly>
                                                        <input name="via_waypoints" id="via_waypoints" class="input_reset" type="hidden" readonly>
                                                        
                                                        <div class="col-md-12 col-lg-12">
                                                            <div class="trip_info">
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
                                    <h3>What is the date that you are departing?</h3>
                                    <p>Enter your dates and times for your outbound trip?</p>

                                    <div class="itinerary_content">

                                        <div class="row">
                                            <div class="col-lg-12 col-xl-12">

                                                <div class="options option-padding-60 text-left">

                                                    <div class="row">
                                                        <div class="col-md-12 col-lg-3">
                                                            <div class="form-group frm-grp trip-grp">
                                                                <label class="mr-b-10">Date of Departure <span class="label_info">(optional)</span></label>
                                                                <div class="date_picker_item">
                                                                    <input type="text" class="dashboard-form-control start-date input-lg date_calculation" placeholder="Date of Departure" name="location_datel" autocomplete="off" id="location_datel" data-date-format="mm/dd/yyyy">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-lg-3">
                                                            <div class="form-group frm-grp trip-grp">
                                                                <label class="mr-b-10">Departure Time (HH:MM) <span class="label_info">(optional)</span></label>
                                                                <input type="time" class="dashboard-form-control input-lg" placeholder="Departure Time" name="location_datel_deptime" autocomplete="off" id="location_datel_deptime">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-lg-3">
                                                            <div class="form-group frm-grp trip-grp">
                                                                <label class="mr-b-10">Arrival Date <span class="label_info">(optional)</span></label>
                                                                <div class="date_picker_item">
                                                                    <input type="text" class="dashboard-form-control end-date input-lg date_calculation" placeholder="Arrival Date" name="location_datel_arr" value="" autocomplete="off" id="location_datel_arr" data-date-format="mm/dd/yyyy">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-lg-3">
                                                            <div class="form-group frm-grp trip-grp">
                                                                <label class="mr-b-10">Arrival Time (HH:MM) <span class="label_info">(optional)</span></label>
                                                                <input type="time" class="dashboard-form-control input-lg" placeholder="Arrival Time" name="location_datel_arrtime" autocomplete="off" id="location_datel_arrtime">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>



                                    <div class="return_trip_section pt-5" style="display:none;">

                                        <h3>And when do you plan to return?</h3>
                                        <p>Enter your dates and times for your return trip?</p>
                                        <div class="itinerary_content">

                                            <div class="row">
                                                <div class="col-lg-12 col-xl-12">

                                                    <div class="options option-padding-60 text-left">

                                                        <div class="row">
                                                            <div class="col-md-12 col-lg-3">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Date of Return <span class="label_info">(optional)</span></label>
                                                                    <div class="date_picker_item">
                                                                        <input type="text" class="dashboard-form-control return-start-date input-lg date_calculation" placeholder="Date of Return" name="location_dater" autocomplete="off" id="location_dater" data-date-format="mm/dd/yyyy">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-3">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Departure Time (HH:MM) <span class="label_info">(optional)</span></label>
                                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Departure Time" name="location_dater_deptime" autocomplete="off" id="location_dater_deptime">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-3">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Arrival Date <span class="label_info">(optional)</span></label>
                                                                    <div class="date_picker_item">
                                                                        <input type="text" class="dashboard-form-control return-end-date input-lg date_calculation" placeholder="Arrival Date" name="location_dater_arr" value="" autocomplete="off" id="location_dater_arr" data-date-format="mm/dd/yyyy">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 col-lg-3">
                                                                <div class="form-group frm-grp trip-grp">
                                                                    <label class="mr-b-10">Arrival Time (HH:MM) <span class="label_info">(optional)</span></label>
                                                                    <input type="time" class="dashboard-form-control input-lg" placeholder="Arrival Time" name="location_dater_arrtime" autocomplete="off" id="location_datel_arrtime">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="multi-path pt-3" style="display:none;">

                                        <h3>Where you would stop on your journey?</h3>
                                        <p>Enter your stops?</p>

                                        <div class="option-padding-60">
                                            <div class="add_stop_item">
                                                <a href="javascript:void(0);" class="add_stop_btn" id="addMoreStop"><i class="fa fa-plus"></i> Add Stop</a>
                                            </div>
                                        </div>


                                        <div class="option-padding-60 pt-2">
                                            <div id="append_more_stop" class="accordion_container_item"></div>
                                        </div>

                                    </div>




                                </div>

                            </div>





                            <div class="itinerary_tab">


                                <div class="itinerary_section">
                                    <h3>Do you want to add Hotel details?</h3>
                                    <p>Add hotel details for your trip.</p>
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
                                                                <input type="text" name="hotel_date_checkin" id="hotel_date_checkin" class="clearable dashboard-form-control input-lg date_calculation" data-date-format="yyyy-mm-dd">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 col-lg-4">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Check Out</label>
                                                                <input type="text" name="hotel_date_checkout" id="hotel_date_checkout" class="clearable dashboard-form-control input-lg date_calculation" data-date-format="yyyy-mm-dd">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-12 col-lg-12">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Hotel Address</label>
                                                                <input type="text" name="hotel_address" id="hotel_address" class="dashboard-form-control form-control input-lg clearable">
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
                                    <h3>Do you want to add car rental details?</h3>
                                    <p>Add car rental details for your trip</p>
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
                                                                <input type="text" name="rental_date_pick" id="rental_date_pick" class="clearable dashboard-form-control input-lg date_calculation" data-date-format="yyyy-mm-dd">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12 col-lg-4">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Date of drop off</label>
                                                                <input type="text" name="rental_date_drop" id="rental_date_drop" class="clearable dashboard-form-control input-lg date_calculation" data-date-format="yyyy-mm-dd">
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="col-md-12 col-lg-12">
                                                            <div class="form-group frm-grp">
                                                                <label class="mr-b-10">Rental Agency Address</label>
                                                                <input type="text" name="rental_agency_address" id="rental_agency_address" class="dashboard-form-control form-control input-lg clearable">
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
                                    <h3>Do you want to add a <?= $extra_portion; ?> portion?</h3>
                                    <p>This will take you from your final destination to another location.</p>
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


                                                    <?php if ($transport != 'vehicle') { ?>

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
                                                                            <input name="location_from_drivingportion" id="location_from_drivingportion" type="text" class="dashboard-form-control input-lg" placeholder="Driving Start">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input name="location_to_drivingportion" id="location_to_drivingportion" type="text" class="dashboard-form-control input-lg" placeholder="Driving Destination">
                                                                        </div>
                                                                        <input name="location_from_latlng_drivingportion" id="location_from_latlng_drivingportion" class="inp1 input_reset" type="hidden" readonly>
                                                                        <input name="location_to_latlng_drivingportion" id="location_to_latlng_drivingportion" class="inp1 input_reset" type="hidden" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    <?php } ?>

                                                    <?php if ($transport != 'plane') { ?>

                                                        <div class="row">

                                                            <div class="col-md-12">
                                                                <div class="form-group text-center">
                                                                    <label class="add_train_portion_heading mt-3 mb-3"><a href="#" onclick="toggle_visibility('flight');" class="outline-btn">
                                                                            <i class="fa fa-plus plus-icon color-black"></i>Add a Flight Portion</a></label>
                                                                    <div id="flight" style="display:none;">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input name="location_from_flightportion" id="location_from_flightportion" type="text" class="dashboard-form-control input-lg" placeholder="Takeoff Location">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input name="location_to_flightportion" id="location_to_flightportion" type="text" class="dashboard-form-control input-lg" placeholder="Destination Location">
                                                                            </div>
                                                                            <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1 input_reset" type="hidden" readonly>
                                                                            <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1 input_reset" type="hidden" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    <?php } ?>

                                                    <?php if ($transport != 'train') { ?>

                                                        <div class="row">

                                                            <div class="col-md-12">
                                                                <div class="form-group text-center">
                                                                    <label class="add_train_portion_heading mt-3 mb-3"><a href="#" onclick="toggle_visibility('train');" class="outline-btn">
                                                                            <i class="fa fa-plus plus-icon color-black"></i>Add a Train Portion</a></label>
                                                                    <div id="train" style="display:none;">
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <input name="location_from_trainportion" id="location_from_trainportion" type="text" class="dashboard-form-control input-lg" placeholder="Takeoff Location">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <input name="location_to_trainportion" id="location_to_trainportion" type="text" class="dashboard-form-control input-lg" placeholder="Destination Location">
                                                                            </div>
                                                                            <input name="location_from_latlng_trainportion" id="location_from_latlng_trainportion" class="inp1 input_reset" type="hidden" readonly>
                                                                            <input name="location_to_latlng_trainportion" id="location_to_latlng_trainportion" class="inp1 input_reset" type="hidden" readonly>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    <?php } ?>




                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="itinerary_tab">


                                <div class="itinerary_section">
                                    <h3>Your travel information is nearly complete.</h3>
                                    <h6>Minimize this slide to confirm your route. Drag your route if you want to change it</h6>
                                    <p>Ready to plan a trip?</p>
                                    <div class="itinerary_content">

                                    </div>
                                </div>



                                <input name="transport" value="<?= $transport; ?>" type="hidden" readonly>

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
                                        <button type="button" class="action_button submit">Create Trip</button>
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
        <button type="button" class="btn btn-info flyover_undo" id="reset_up" style="<?= $transport == 'vehicle' ? 'display:inline-block': 'display:none' ?>">Undo last change</button>
        <button type="button" class="btn btn-info flyover_save" id="flyover_save">Save</button>
    </div>    

    <script>
        var transportWay = '<?php echo $transport; ?>';
    </script>
    <script>
        var val = {
            // Specify validation rules
            ignore: ':hidden:not(.option_validy)',
            rules: {
                option_value: {
                    required: true,
                },
                trip_mode_value: {
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
                option_value: {
                    required: "Please select a option",
                },
                trip_mode_value: {
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
                    required: "Please type your starting location",
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

        $('input[type="checkbox"][name=initial_option]').on('change', function() {
            $('input[type="checkbox"][name=initial_option]').not(this).prop('checked', false);
        });

        $('input[type="checkbox"][name=location_triptype]').on('change', function() {
            $('input[type="checkbox"][name=location_triptype]').not(this).prop('checked', false);
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
        
        function path_calculation(mode, distance, duration) {
            return (" <p><span> " + mode + " distance </span> : " + distance + "</p><p><span> Duration </span> : " + duration + ".</p > ")
        }        
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
            if (transportWay === 'plane') {
                // drawing line
                new DrawPlaneDirectionsHandler(map);
            } else {
                // tracing the route
                new AutocompleteDirectionsHandler(map);
            }


            var hotel_address = document.getElementById('hotel_address');
            var hotelAddressPlaceAutocomplete = new google.maps.places.Autocomplete(hotel_address);

            var rental_agency_address = document.getElementById('rental_agency_address');
            var rentalAgencyAddressPlaceAutocomplete = new google.maps.places.Autocomplete(rental_agency_address);
            
            this.hotelPlaceChangedListenerPortion(hotelAddressPlaceAutocomplete, 'DRIVING');
        }


        function DrawPlaneDirectionsHandler(map) {
            this.map = map;
            this.originPlaceId = this.origin_dportionPlaceId = null;
            this.destinationPlaceId = this.destination_dportionPlaceId = null;
            this.originPlaceLocation = this.origin_dportionPlaceLocation = null;
            this.destinationPlaceLocation = this.destination_dportionPlaceLocation = null;
            this.wayptLocation = this.wayptPlaceId = null;

            var originInput = document.getElementById('location_from');
            var destinationInput = document.getElementById('location_to');
            var originPlaceAutocomplete = new google.maps.places.Autocomplete(originInput, {
                types: ['airport']
            });
            var destPlaceAutocomplete = new google.maps.places.Autocomplete(destinationInput, {
                types: ['airport']
            });

            this.directionsService = new google.maps.DirectionsService;
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

            this.setupAlgoliaPlaceChangedListener(originPlaceAutocomplete, 'ORIG');
            this.setupAlgoliaPlaceChangedListener(destPlaceAutocomplete, 'DEST');

            var originInput_dportion = document.getElementById('location_from_drivingportion');
            var destinationInput_dportion = document.getElementById('location_to_drivingportion');

            var origin_dportionAutocomplete = new google.maps.places.Autocomplete(originInput_dportion);

            var destination_dportionAutocomplete = new google.maps.places.Autocomplete(destinationInput_dportion);

            this.setupAlgoliaPlaceChangedListenerPortion(origin_dportionAutocomplete, 'ORIG', 'DRIVING');
            this.setupAlgoliaPlaceChangedListenerPortion(destination_dportionAutocomplete, 'DEST', 'DRIVING');

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
            /*************************************************/
        }

        /************************* NEW **********************************/
        DrawPlaneDirectionsHandler.prototype.setupPlaceChangedListenerPortion = function(autocomplete, mode, ptype) {
            var me = this;
            autocomplete.bindTo('bounds', this.map);
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();

                console.log('place', place);

                if (!place.place_id) {
                    window.alert("Please select an option from the dropdown list.");
                    return;
                }
                if (mode === 'ORIG') {
                    if (ptype == 'DRIVING') {
                        me.origin_dportionPlaceId = place.place_id;
                        me.origin_dportionPlaceLocation = place.geometry.location;
                    } else {
                        me.origin_tportionPlaceId = place.place_id;
                        me.origin_tportionPlaceLocation = place.geometry.location;
                    }
                } else {
                    if (ptype == 'DRIVING') {
                        me.destination_dportionPlaceId = place.place_id;
                        me.destination_dportionPlaceLocation = place.geometry.location;
                    } else {
                        me.destination_tportionPlaceId = place.place_id;
                        me.destination_tportionPlaceLocation = place.geometry.location;
                    }
                }

                me.portionroute(ptype);
            });
        };

        DrawPlaneDirectionsHandler.prototype.setupAlgoliaPlaceChangedListenerPortion = function(autocomplete, mode, ptype) {
            var me = this;

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();

                if (place) {



                    if (!place.place_id) {
                        window.alert("Please select an option from the dropdown list.");
                        return;
                    }
                    if (mode === 'ORIG') {
                        if (ptype == 'DRIVING') {
                            me.origin_dportionPlaceId = place.place_id;
                            me.origin_dportionPlaceLocation = place.geometry.location;
                        } else {
                            me.origin_tportionPlaceId = place.place_id;
                            me.origin_tportionPlaceLocation = place.geometry.location;
                        }
                    } else {
                        if (ptype == 'DRIVING') {
                            me.destination_dportionPlaceId = place.place_id;
                            me.destination_dportionPlaceLocation = place.geometry.location;
                        } else {
                            me.destination_tportionPlaceId = place.place_id;
                            me.destination_tportionPlaceLocation = place.geometry.location;
                        }
                    }
                    me.portionroute(ptype);


                } else {
                    window.alert('No results found in google map.');
                }


            });


        };

        DrawPlaneDirectionsHandler.prototype.portionroute = function(ptype) {
            if (ptype == 'DRIVING') {
                if (!this.origin_dportionPlaceId || !this.destination_dportionPlaceId) {
                    return;
                }
            } else {
                if (!this.origin_tportionPlaceId || !this.destination_tportionPlaceId) {
                    return;
                }
            }
            var me = this;

            if (ptype == 'DRIVING') {
                document.getElementById('location_from_latlng_drivingportion').value = me.origin_dportionPlaceLocation;
                document.getElementById('location_to_latlng_drivingportion').value = me.destination_dportionPlaceLocation;
                var bounds = new google.maps.LatLngBounds();

                var marker_icon = new google.maps.MarkerImage(
                    'https://planiversity.com/assets/images/icon.png',
                    null,
                    // The origin for my image is 0,0.
                    new google.maps.Point(0, 0),
                    // The center of the image is 50,50 (my image is a circle with 100,100)
                    new google.maps.Point(50, 50)
                );

                // var marker_destination = new google.maps.Marker({
                //     position: new google.maps.LatLng(me.destination_dportionPlaceLocation.lat(), me.destination_dportionPlaceLocation.lng()),
                //     // icon: 'https://planiversity.com/assets/images/icon.png',
                //     icon: marker_icon,
                //     label: {
                //         text: markerAlpaArr[mark_number + 1],
                //         color: "#ffffff",
                //     }
                // });

                // marker_destination.setMap(map);

                // bounds.extend(marker_destination.position);
                // map.fitBounds(bounds);
            } else {
                document.getElementById('location_from_latlng_trainportion').value = me.origin_tportionPlaceLocation;
                document.getElementById('location_to_latlng_trainportion').value = me.destination_tportionPlaceLocation;
                var bounds = new google.maps.LatLngBounds();

                var marker_icon = new google.maps.MarkerImage(
                    'https://planiversity.com/assets/images/icon.png',
                    null,
                    // The origin for my image is 0,0.
                    new google.maps.Point(0, 0),
                    // The center of the image is 50,50 (my image is a circle with 100,100)
                    new google.maps.Point(50, 50)
                );

                var marker_destination = new google.maps.Marker({
                    position: new google.maps.LatLng(me.destination_tportionPlaceLocation.lat(), me.destination_tportionPlaceLocation.lng()),
                    // icon: 'https://planiversity.com/assets/images/icon.png',
                    icon: marker_icon,
                    label: {
                        text: markerAlpaArr[mark_number + 1],
                        color: "#ffffff",
                    }
                });

                marker_destination.setMap(map);

                bounds.extend(marker_destination.position);
                map.fitBounds(bounds);

            }
            this.directionsService.route({
                origin: {
                    'placeId': (ptype == "DRIVING" ? this.origin_dportionPlaceId : this.origin_tportionPlaceId)
                },
                destination: {
                    'placeId': (ptype == "DRIVING" ? this.destination_dportionPlaceId : this.destination_tportionPlaceId)
                },
                travelMode: google.maps.TravelMode[ptype]
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

                    map.fitBounds(bounds);                    

                    //me.fitBounds(me.bounds.union(response.routes[0].bounds));
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        };

        /************************* NEW **********************************/
        DrawPlaneDirectionsHandler.prototype.setupPlaceChangedListener = function(autocomplete, mode) {
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
                } else {
                    me.destinationPlaceId = place.place_id;
                    me.destinationPlaceLocation = place.geometry.location;
                    // portion change
                    me.origin_dportionPlaceId = place.place_id;
                    me.origin_dportionPlaceLocation = place.geometry.location;
                    // me.origin_fportionPlaceId = place.place_id;
                    // me.origin_fportionPlaceLocation = place.geometry.location;
                    me.origin_tportionPlaceId = place.place_id;
                    me.origin_tportionPlaceLocation = place.geometry.location;
                }
                me.route();
            });
        };
        DrawPlaneDirectionsHandler.prototype.setupAlgoliaPlaceChangedListener = function(autocomplete, mode) {
            var me = this;

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                console.log('Typeping...', mode);

                console.log('place', place);

                if (place) {
                    if (mode === 'ORIG') {
                        me.originPlaceId = place.place_id;
                        me.originPlaceLocation = place.geometry.location;

                        console.warn(place.geometry.location);

                    } else {
                        me.destinationPlaceId = place.place_id;
                        me.destinationPlaceLocation = place.geometry.location;
                        // portion change
                        me.origin_dportionPlaceId = place.place_id;
                        me.origin_dportionPlaceLocation = place.geometry.location;
                        // me.origin_fportionPlaceId = place.place_id;
                        // me.origin_fportionPlaceLocation = place.geometry.location;
                        me.origin_tportionPlaceId = place.place_id;
                        me.origin_tportionPlaceLocation = place.geometry.location;
                    }
                    me.route();
                } else {
                    window.alert('No results found in google map.');
                }



            });


        };

        DrawPlaneDirectionsHandler.prototype.route = function() {
            if (!this.originPlaceId || !this.destinationPlaceId) {
                return;
            }
            var me = this;

            // clean map before start
            if (marker_origin != null) {
                marker_origin.setMap(null);
                marker_origin = null;
            };
            if (marker_destination != null) {
                marker_destination.setMap(null);
                marker_destination = null;
            };
            if (flightPath != null) {
                flightPath.setMap(null);
                flightPath = null;
            };
            var iconBase = 'https://planiversity.com/assets/images/';
            var marker_imageA = new google.maps.MarkerImage(
                'https://planiversity.com/assets/images/Selected_A.png',
                null,
                // The origin for my image is 0,0.
                new google.maps.Point(0, 0),
                // The center of the image is 50,50 (my image is a circle with 100,100)
                new google.maps.Point(50, 50)
            );
            var marker_imageB = new google.maps.MarkerImage(
                'https://planiversity.com/assets/images/Selected_B.png',
                null,
                // The origin for my image is 0,0.
                new google.maps.Point(0, 0),
                // The center of the image is 50,50 (my image is a circle with 100,100)
                new google.maps.Point(25, 50)
            );
            var bounds = new google.maps.LatLngBounds();
            marker_origin = new google.maps.Marker({
                position: this.originPlaceLocation,
                icon: marker_imageA
            });
            marker_destination = new google.maps.Marker({
                position: this.destinationPlaceLocation,
                icon: marker_imageB
            });
            marker_origin.setMap(this.map);
            marker_destination.setMap(this.map);
            bounds.extend(marker_origin.position);
            bounds.extend(marker_destination.position);

            document.getElementById('location_from_latlng').value = this.originPlaceLocation;
            document.getElementById('location_to_latlng').value = this.destinationPlaceLocation;
            //document.getElementById('location_from_drivingportion').value = document.getElementById('location_to').value;
            //document.getElementById('location_from_latlng_drivingportion').value = this.originPlaceLocation;

            var flightPlanCoordinates = [{
                    lat: this.originPlaceLocation.lat(),
                    lng: this.originPlaceLocation.lng()
                },
                {
                    lat: this.destinationPlaceLocation.lat(),
                    lng: this.destinationPlaceLocation.lng()
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
            mark_number = 1;
            // hide to and from content
            //show_win(2);
        };

        /**
         * @constructor
         */



        function AutocompleteDirectionsHandler(map) {
            this.map = map;
            this.originPlaceId = this.origin_dportionPlaceId = null;
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

            //alert(inputType);


            var originPlaceAutocomplete = new google.maps.places.Autocomplete(originInput, {
                types: [inputType]
            });

            var destPlaceAutocomplete = new google.maps.places.Autocomplete(destinationInput, {
                types: [inputType]
            });


            var me = this;

            document.getElementById("reset_up").addEventListener("click", function() {

                $('.flyover_undo').css('cursor', 'wait');
                $('.flyover_undo').attr('disabled', true);

                var via_waypoints_value = [];
                via_waypoints_value = document.getElementById('via_waypoints').value;

                if (via_waypoints_value == "") {
                    via_waypoints_value = [];
                } else {
                    via_waypoints_value = JSON.parse(via_waypoints_value);
                }

                if (Array.isArray(via_waypoints_value) && via_waypoints_value.length) {

                    if (via_waypoints_value.length == 1) {
                        me.routeSplit(via_waypoints_value);
                        me.route();
                    } else {
                        var returnValue = me.routeSplitValueReturn(via_waypoints_value);
                        me.route(returnValue);
                    }

                    $('.flyover_undo').css('cursor', 'pointer');
                    $('.flyover_undo').removeAttr('disabled');

                } else {

                    swal({
                        title: "There is nothing to undo",
                        type: "warning",
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok",
                        closeOnConfirm: true
                    });


                    $('.flyover_undo').css('cursor', 'pointer');
                    $('.flyover_undo').removeAttr('disabled');

                }

            });
            
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
                    
                    let distance_calculation = calcTotalDistanceText(result);
                    let duration_calculation = calcTotalDurationText(result);     
                    
                    $('.trip_info').html(path_calculation("<?= $distance_mode ?>", distance_calculation, duration_calculation));                    
                    
                    infowindow1 = new google.maps.InfoWindow();
                    infowindow1.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/car_icon.png'></div><div style='float:right; padding: 3px;'>" + distance_calculation + "<br>" + duration_calculation + "</div>");
                    infowindow1.setPosition(result.routes[0].overview_path[center_point | 0]);
                    infowindow1.open(map);

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

            autocomplete.addListener('change', function() {
                console.log('changing...');
            });



            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();


                if (place) {

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
                }

            });




        };

        AutocompleteDirectionsHandler.prototype.setupAlgoliaPlaceChangedListener = function(autocomplete, mode) {
            var me = this;
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                if (place) {

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
                                zoom: 5
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
                    'https://planiversity.com/assets/images/icon.png',
                    null,
                    // The origin for my image is 0,0.
                    new google.maps.Point(0, 0),
                    // The center of the image is 50,50 (my image is a circle with 100,100)
                    new google.maps.Point(50, 50)
                );

                marker_destination = new google.maps.Marker({
                    position: me.destination_dportionPlaceLocation,
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

            if (ptype == 'TRANSIT') {
                document.getElementById('location_from_latlng_trainportion').value = me.origin_tportionPlaceLocation;
                document.getElementById('location_to_latlng_trainportion').value = me.destination_tportionPlaceLocation;
                var bounds = new google.maps.LatLngBounds();

                var marker_icon = new google.maps.MarkerImage(
                    'https://planiversity.com/assets/images/icon.png',
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
                    travelMode: google.maps.TravelMode[ptype]
                }, function(response, status) {
                    if (status === 'OK') {
                        var line = new google.maps.Polyline({
                            path: response.routes[0].overview_path,
                            strokeColor: '#0688E9',
                            strokeOpacity: 1.0,
                            strokeWeight: 3
                        });
                        line.setMap(map);

                        me.fitBounds(me.bounds.union(response.routes[0].bounds));
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

                if (marker_destination) {
                    marker_destination.setMap(null);
                }

                var marker_icon = new google.maps.MarkerImage(
                    'https://planiversity.com/assets/images/icon.png',
                    null,
                    // The origin for my image is 0,0.
                    new google.maps.Point(0, 0),
                    // The center of the image is 50,50 (my image is a circle with 100,100)
                    new google.maps.Point(50, 50)
                );

                marker_destination = new google.maps.Marker({
                    position: me.destination_fportionPlaceLocation,
                    // icon: 'https://planiversity.com/assets/images/icon.png',
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
                if (flightPath) {
                    flightPath.setMap(null);
                }
                
                /* Custom Start*/

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

                /* Custom End*/

                flightPath.setMap(this.map);
                this.map.fitBounds(bounds);
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
            
            if (!this.originPlaceId || !this.destinationPlaceId) {
                return;
            }

            var me = this;
            document.getElementById('location_from_latlng').value = me.originPlaceLocation;
            document.getElementById('location_to_latlng').value = me.destinationPlaceLocation;
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
                        // me.directionsDisplay
                        directionsRenderer.setDirections(response);
                        var my_route = response.routes[0];
                        addMarker(my_route)
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
                        
                        let distance_calculation = calcTotalDistanceText(response);
                        let duration_calculation = calcTotalDurationText(response);

                        $('.trip_info').html(path_calculation("<?= $distance_mode ?>", distance_calculation, duration_calculation));                        

                        var center_point2 = response.routes[0].overview_path.length / 2;
                        var infowindow2 = new google.maps.InfoWindow();
                        infowindow2.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/train_icon2.png'></div><div style='float:right; padding: 3px;'>" + distance_calculation + "<br>" + duration_calculation + "</div>");
                        infowindow2.setPosition(response.routes[0].overview_path[center_point2 | 0]);
                        infowindow2.open(map);
                        addMarker(response.routes[0])
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
                // $('#rental_agency_address').prop('required',false);
            } else {
                $("#rental_agency_address").show();
                // $("#rental_agency_address").prop('required',true);
            }
        });

        $("#hotel_located").click(function() {
            if (this.checked == true) {
                $("#hotel_address").hide();
                // $('#hotel_address').prop('required',false);
            } else {
                $("#hotel_address").show();
                // $("#hotel_address").prop('required',true);
            }
        });

        $('input[type=radio][name=location_triptype]').change(function() {
            if (transportWay === 'train') { // plane or vehicle
                return;
            }
            if (this.value == 'o') {
                $("#return").hide();
            } else {
                $("#return").show();
            }
            if (this.value == 'm') {
                $("#waypts_div").show();
                if (transportWay === 'vehicle') {
                    $("#addmorebtnmaindiv").show();
                }
            } else {
                $("#waypts_div").val('');
                $("#waypts_div").hide();
            }
        });



        $('input[type=checkbox][name=location_triptype]').change(function() {

            console.log('Trip Mode', this.value);

            $('#trip_mode_value').val(this.value);
            $('#trip_mode_value-error').html('');

            if (this.value == 'o') {
                $(".return_trip_section").hide();
                $(".multi-path").hide();
            } else if (this.value == 'm') {
                $(".return_trip_section").show();
                $(".multi-path").show();
            } else {
                $(".multi-path").hide();
                $(".return_trip_section").show();
            }

        });


        $('input[type=checkbox][name=initial_option]').change(function() {

            console.log('Option Value Check', this.value);

            $('#option_value').val(this.value);
            $('#option_value-error').html('');

            if (this.value == 'booking') {
                $('.booking_section').delay(500).fadeIn();
            } else {
                $(".booking_section").hide();
            }
        });


        $('input[type=checkbox][name=hotel_option]').change(function() {

            console.log('Trip Mode', this.value);

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

            console.log('Trip Mode', this.value);

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

            console.log('Drive Option', this.value);

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

        $(".close").click(function() {
            if ($("#schedule-modal").hasClass('modaltrans')) {
                $("#schedule-modal").removeClass('modaltrans');
                $("#schedule-modal-body").removeClass('modaltrans-body');
                $("#schedule-modal-body").removeClass('modaltrans-body-mozila');
                $("#myLargeModalLabel").css({
                    fontSize: 21
                });
                // $('#schedule-modal').modal('show');
                $(this).html("-");
                $('.flyover').hide();
            } else {
                
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
                // $('#schedule-modal').collapse()
                // $('#schedule-modal').modal('show');
                $(this).html("+");
            }
        });

        var add_more_stop_count = 0
        var multi_arr_value = [];
        // var waypoints = [];
        var currentWriteIndex = null;
        var currentChangeIndex = null;
        var wayptAutocompleteVar = [];
        var is_update = null;
        var i = 0;
        var place = "";

        function addLocationToStop(index) {
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
                    var inputType = "trainStation";
                    var mapType = 'TRANSIT';
                    break;
                default:
                    var inputType = "";
                    var mapType = 'DRIVING';
                    break;
            }

            wayptAutocompleteVar[index] = new google.maps.places.Autocomplete(document.getElementById("multi_location_waypoint" + index), {
                types: [inputType]
            });

            // wayptAutocompleteVar[index].bindTo('bounds', map);

            wayptAutocompleteVar[index].addListener('place_changed', function() {
                var place = wayptAutocompleteVar[index].getPlace();

                map = new google.maps.Map(document.getElementById('map'), {
                    mapTypeControl: false,
                    center: {
                        lat: 40.730610,
                        lng: -73.968285
                    },
                    // mapTypeId: mapType,
                    zoom: 7
                });

                directionsRenderer.setMap(map);
                is_update = null;
                i = 0;
                currentWriteIndex = currentChangeIndex;
                //var geocoder = new google.maps.Geocoder;
                //var latlng = e.suggestion.latlng;


                if (place) {

                    if (multi_arr_value.length == 0) {
                        multi_arr_value.push({
                            id: currentWriteIndex,
                            location_multi_waypoint_latlng: place.geometry.location.lat() + ',' + place.geometry.location.lng()
                        })
                        waypoints.push({
                            location: place.geometry.location,
                            stopover: true
                        })
                    } else {
                        multi_arr_value.push({
                            id: currentWriteIndex,
                            location_multi_waypoint_latlng: place.geometry.location.lat() + ',' + place.geometry.location.lng()
                        })

                        waypoints.push({
                            location: place.geometry.location,
                            stopover: true
                        })

                        /*for (i = 0; i < multi_arr_value.length; i++) {
                            if (multi_arr_value[i].id == currentWriteIndex) {
                                is_update = i;
                                waypoints[i].location = place.geometry.location
                            }

                            if (multi_arr_value.length == i + 1) {
                                if (multi_arr_value[is_update]) {
                                    if (is_update != null) {
                                        multi_arr_value[is_update].location_multi_waypoint_latlng = place.geometry.location.lat() + ',' + place.geometry.location.lng()
                                    } else {
                                        multi_arr_value.push({
                                            id: currentWriteIndex,
                                            location_multi_waypoint_latlng: place.geometry.location.lat() + ',' + place.geometry.location.lng()
                                        })
                                        waypoints.push({
                                            location: place.geometry.location,
                                            stopover: true
                                        })
                                    }
                                } else {
                                    multi_arr_value.push({
                                        id: currentWriteIndex,
                                        location_multi_waypoint_latlng: place.geometry.location.lat() + ',' + place.geometry.location.lng()
                                    })
                                    waypoints.push({
                                        location: place.geometry.location,
                                        stopover: true
                                    })
                                }
                            }
                        }*/
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
                                    addMarker(my_route);
                                }
                            });

                            break;
                        case "plane":

                            // console.log('plane multiple');
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


                $('#append_more_stop').append(html);

                addLocationToStop(add_more_stop_count);
                addLocationToStop(add_more_stop_count + "_hotel_addr");
                addLocationToStop(add_more_stop_count + "_agency_addr");

                $('#multi_location_waypoint_date' + add_more_stop_count).datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true,
                })

                $('#multi_location_waypoint_dep_date' + add_more_stop_count).datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true,
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
                            addMarker(my_route)
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
                            addMarker(my_route)
                            directionsRenderer.setDirections(result);
                        }
                    });
                    document.getElementById('location_multi_waypoint_latlng').value = JSON.stringify(multi_arr_value)
                    break;
                }
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
                    var reAutocomplete = new google.maps.places.Autocomplete(document.getElementById("location_from_drivingportion"));

                } else {
                    $("#location_from_drivingportion").val("");
                }

                if (statusFlight === "block" && transportWay !== 'train') {
                    $("#location_from_flightportion").val(location_to);

                    var reAutocomplete = new google.maps.places.Autocomplete(document.getElementById("location_from_flightportion"));
                } else {
                    $("#location_from_flightportion").val("");
                }

                if (statusTrain === "block") {
                    $("#location_from_trainportion").val(location_to);

                    var reAutocomplete = new google.maps.places.Autocomplete(document.getElementById("location_from_trainportion"), {
                        types: ['train_station']
                    });

                } else {
                    $("#location_from_trainportion").val("");
                }
            }, 100);
        }


        
        var dateToday = new Date();

        var datePickerOptions = {
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        }


    var customClasses = [];

    var customPickerOptions = {
        
            templates: {
                leftArrow: '<i class="fa fa-chevron-left"></i>',
                rightArrow: '<i class="fa fa-chevron-right"></i>'
            },
            //format: 'mm/dd/yyyy',
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
                        tooltip: "Travel date",
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

            // From
            var startDate = $('#location_datel').datepicker('getDate');
            var start_date = moment(startDate).format("YYYY-MM-DD");

            var arrivalDate = $('#location_datel_arr').datepicker('getDate');
            var start_arrival_date = moment(arrivalDate).format("YYYY-MM-DD");

            // TO
            var returnDate = $('#location_dater').datepicker('getDate');
            var return_date = moment(returnDate).format("YYYY-MM-DD");

            var returnArrivalDate = $('#location_dater_arr').datepicker('getDate');
            var return_arrival_date = moment(returnArrivalDate).format("YYYY-MM-DD");

            if ((startDate) && (arrivalDate) && (!returnDate) && (!returnArrivalDate)) {

                console.log('First IF');
                customClasses = [];
                date_range_calculation(start_date, start_arrival_date, customClasses);

            } else if ((startDate) && (arrivalDate) && (returnDate) && (!returnArrivalDate)) {

                console.log('Second IF ELSE');
                customClasses = [];
                date_range_calculation(start_date, return_date, customClasses);

            } else if ((startDate) && (arrivalDate) && (returnDate) && (returnArrivalDate)) {

                customClasses = [];
                date_range_calculation(start_date, return_arrival_date, customClasses);

            } else if ((!startDate) && (!arrivalDate) && (returnDate) && (returnArrivalDate)) {

                customClasses = [];
                date_range_calculation(return_date, return_arrival_date, customClasses);

            } else if ((startDate) && (!arrivalDate) && (returnDate) && (returnArrivalDate)) {

                customClasses = [];
                date_range_calculation(start_date, return_arrival_date, customClasses);

            } else if (startDate) {
                console.log('First store');
                customClasses = [];
                customClasses.push(start_date);

            } else if (arrivalDate) {
                console.log('Second store');
                customClasses = [];
                customClasses.push(start_arrival_date);

            } else if (returnDate) {
                console.log('return_date');
                customClasses = [];
                customClasses.push(return_date);

            } else if (returnArrivalDate) {
                console.log('return_arrival_date');
                customClasses = [];
                customClasses.push(return_arrival_date);

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
            $('.return-start-date').datepicker('setStartDate', oneDayFromStartDate);
        });

        $('.end-date').datepicker().on("show", function() {
            var startDate = $('.start-date').datepicker('getDate');
            $('.day.disabled').filter(function(index) {
                return $(this).text() === moment(startDate).format('D');
            }).addClass('active');
        });

        /* return start Date */

        $('.return-start-date').datepicker().on("changeDate", function() {
            var startDate = $('.return-start-date').datepicker('getDate');
            var oneDayFromStartDate = moment(startDate).toDate();
            $('.return-end-date').datepicker('setStartDate', oneDayFromStartDate);
        });

        $('.return-end-date').datepicker().on("show", function() {
            var startDate = $('.return-start-date').datepicker('getDate');
            $('.day.disabled').filter(function(index) {
                return $(this).text() === moment(startDate).format('D');
            }).addClass('active');
        });


        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
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