<?php
session_start();
include_once("config.ini.php");
include_once('config.php');

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
$label_station = 'Airport';
$vihicle_place_holder = "";
$multiCityLabel = "Multi City";

switch ($transport) {
    case 'vehicle':
        $travelmode = 'DRIVING';
        $label = 'Vehicle';
        $label_station = 'Point';
        $vihicle_place_holder = "Enter Final Destination";
        $multiCityLabel = "Multiple Stops (Road Trip)";
        break;
    case 'train':
        $travelmode = 'TRANSIT';
        $label = 'Train';
        $label_station = 'Railway Station';
        break;
}

$output = '';
if (isset($_POST['location_submit'])) {
    //    print_r($_REQUEST);
    //    exit;
    $from = $_POST["location_from"];
    $to = $_POST["location_to"];
    $from_latlng = $_POST["location_from_latlng"];
    $to_latlng = $_POST["location_to_latlng"];
    $transport = filter_var($_GET["transport"], FILTER_SANITIZE_STRING);
    $filter = array();
    if (isset($_POST['filter_option'])) {
        $filter = $_POST['filter_option'];
    }
    $embassis = '';
    if (isset($_POST['embassy_list'])) {
        $embassis = $_POST['embassy_list'];
    }

    $location_triptype = $_POST['location_triptype'];
    $location_datel = $_POST['location_datel'];
    $location_datel_deptime = $_POST['location_datel_deptime'];
    $location_datel_arrtime = $_POST['location_datel_arrtime'];
    $dep_flight_no = "";
    if (isset($_POST['dep_flight_no'])) {
        $dep_flight_no = $_POST['dep_flight_no'];
    }
    $dep_seat_no = "";
    if (isset($_POST['dep_seat_no'])) {
        $dep_seat_no = $_POST['dep_seat_no'];
    }
    $location_dater = $_POST['location_dater'];
    $location_dater_deptime = $_POST['location_dater_deptime'];
    $location_dater_arrtime = $_POST['location_dater_arrtime'];
    $ret_flight_no = "";
    if (isset($_POST['ret_flight_no'])) {
        $ret_flight_no = $_POST['ret_flight_no'];
    }
    $ret_seat_no = "";
    if (isset($_POST['ret_seat_no'])) {
        $ret_seat_no = $_POST['ret_seat_no'];
    }
    $hotel_name = $_POST['hotel_name'];
    $hotel_date_checkin = $_POST['hotel_date_checkin'];
    $hotel_date_checkout = $_POST['hotel_date_checkout'];
    $rental_agency = $_POST['rental_agency'];
    $rental_date_pick = $_POST['rental_date_pick'];
    $rental_date_drop = $_POST['rental_date_drop'];
    $location_from_flightportion = '';
    if (isset($_POST['location_from_flightportion'])) {
        $location_from_flightportion = $_POST['location_from_flightportion'];
    }
    $location_to_flightportion = '';
    if (isset($_POST['location_to_flightportion'])) {
        $location_to_flightportion = $_POST['location_to_flightportion'];
    }
    $location_from_latlng_flightportion = '';
    if (isset($_POST['location_from_latlng_flightportion'])) {
        $location_from_latlng_flightportion = $_POST['location_from_latlng_flightportion'];
    }
    $location_to_latlng_flightportion = '';
    if (isset($_POST['location_to_latlng_flightportion'])) {
        $location_to_latlng_flightportion = $_POST['location_to_latlng_flightportion'];
    }
    $location_from_drivingportion = $_POST['location_from_drivingportion'];
    $location_to_drivingportion = $_POST['location_to_drivingportion'];
    $location_from_latlng_drivingportion = $_POST['location_from_latlng_drivingportion'];
    $location_to_latlng_drivingportion = $_POST['location_to_latlng_drivingportion'];
    $location_from_trainportion = $_POST['location_from_trainportion'];
    $location_to_trainportion = $_POST['location_to_trainportion'];
    $location_from_latlng_trainportion = $_POST['location_from_latlng_trainportion'];
    $location_to_latlng_trainportion = $_POST['location_to_latlng_trainportion'];
    $location_multi_waypoint = isset($_POST['multi_location_waypoint']) ? $_POST['multi_location_waypoint'] : "";
    $location_multi_waypoint_date = isset($_POST['multi_location_waypoint_date']) ? $_POST['multi_location_waypoint_date'] : "";
    $location_multi_waypoint_latlng = isset($_POST['location_multi_waypoint_latlng']) ? $_POST['location_multi_waypoint_latlng'] : "";
    $via_waypoints = isset($_POST['via_waypoints']) ? $_POST['via_waypoints'] : "";
    $hotel_address = "";
    if (isset($_POST['hotel_located']) && $_POST['hotel_located'] == 'on') {
        $hotel_address = 'Located at Airport';
    } else {
        $hotel_address = $_POST['hotel_address'];
    }
    $rental_agency_address = "";
    if (isset($_POST['rental_agency_located']) && $_POST['rental_agency_located'] == 'on') {
        $rental_agency_address = 'Located at Airport';
    } else {
        $rental_agency_address = $_POST['rental_agency_address'];
    }
    if (!empty($from) && !empty($to) && !empty($from_latlng) && !empty($to_latlng)) { // save data trip in DB
        include("class/class.TripPlan.php");
        $trip = new TripPlan();
        $trip->put_data($transport, $from, $to, $from_latlng, $to_latlng, $filter, $embassis, $location_triptype, $location_datel, $location_datel_deptime, $location_datel_arrtime, $dep_flight_no, $dep_seat_no, $location_dater, $location_dater_deptime, $location_dater_arrtime, $ret_flight_no, $ret_seat_no, $hotel_name, $hotel_date_checkin, $hotel_date_checkout, $rental_agency, $rental_date_pick, $rental_date_drop, $location_from_flightportion, $location_to_flightportion, $location_from_latlng_flightportion, $location_to_latlng_flightportion, $location_from_drivingportion, $location_to_drivingportion, $location_from_latlng_drivingportion, $location_to_latlng_drivingportion, $location_from_trainportion, $location_to_trainportion, $location_from_latlng_trainportion, $location_to_latlng_trainportion, "", "", $hotel_address, $rental_agency_address, json_encode($location_multi_waypoint), $location_multi_waypoint_latlng, $via_waypoints, $location_multi_waypoint_date);
        if (!$trip->error) { //header("Location:".SITE."trip/route/".$dbh->lastInsertId());
            header("Location:" . SITE . "trip/create-timeline/" . $dbh->lastInsertId());
        } else {
            $output = 'A system error has been encountered. Please try again.' . $trip->error;
            echo $output;
            exit();
        }
    } else
        $output = 'Please fill origin and destination place.';
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
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.19.0"></script>

    <script>
        var SITE = '<?php echo SITE; ?>'
    </script>
    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <script src="<?php echo SITE; ?>js/global.js"></script>
    <link href="<?php echo SITE; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo SITE; ?>assets/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css">
    <?php include('new_head_files.php'); ?>
    <style>
        .modaltrans {
            /*height: 66px;*/
            width: 292px;
            overflow: hidden !important;
            /*                -webkit-transition: all 2s ease;
                -moz-transition: all 2s ease;
                -o-transition: all 2s ease;*/
            /*transition: all 2s ease;*/
            max-height: 300px;
            padding-left: 0px !important;
            margin-left: 14px;
        }

        .datepicker td,
        .datepicker th {
            text-align: center;
        }

        .modaltrans-body {
            /*zoom: 19%;*/
            transform: scale(0.25) translate(-151%, -151%);
            width: 400%;
        }

        .modaltrans-body-mozila {
            /*                transform: scale(0.5) translate(-50%, -50%);
                                width: 191%;*/
            transform: scale(0.2) translate(-200%, -200%);
            width: 500%;
        }

        .finish-next-btn:hover {
            color: #FFF !important;
        }

        .algolia-places .ap-input-icon {
            margin-top: 16px;
        }
    </style>
    
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
</head>

<body class="custom_toandfrom">

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php
    //include('include_header.php')
    include('new_backend_header.php');
    ?>
    <?php include_once('includes/top_bar_active.php'); ?>
    </header>

    <div data-backdrop="false" id="schedule-modal" class="modal fade bs-example-modal-lg modal-to-and-from" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;top: 134px">
        <form name="location_form" method="post" class="toandfrom pt-3">
            <div class="modal-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
                <div class="modal-content modal-content-white">
                    <div class="modal-header bb-none pt-4">
                        <button type="button" class="close" aria-hidden="true">-</button>
                        <h4 class="modal-title" id="myLargeModalLabel">Build Your Itinerary</h4>
                    </div>
                    <div class="modal-body" id="schedule-modal-body">
                        <fieldset>
                            <div class="trip-type-wrap">
                                <div class="row">
                                    <!--<div class="col-md-12 col-lg-2">
                                        <div class="mgn-top">
                                            <div class="radio form-check-inline form-group">
                                                <input type="radio" id="location_triptype_r" value="r" name="location_triptype" >
                                                <label for="location_triptype_r" >Round Trip</label>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="col-md-12 col-lg-3">
                                        <!--mgn-top-20-->
                                        <div class="mgn-top">
                                            <div class="radio form-check-inline  form-group">
                                                <input type="radio" id="location_triptype_o" value="o" name="location_triptype" checked>
                                                <label for="location_triptype_o">From A to B</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-9">
                                        <div class="mgn-top">
                                            <div class="radio form-check-inline  form-group">
                                                <input type="radio" id="location_triptype_m" value="m" name="location_triptype">
                                                <label for="location_triptype_m"><?php echo $multiCityLabel; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
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
                            </script>
                            <div class="row">

                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Starting <?php echo $label_station; ?></label>
                                        <!--<input type="text" class="dashboard-form-control input-lg" onChange="updateSubwayMap()" placeholder="Enter Starting Airport" required="">-->
                                        <input name="location_from" id="location_from" value="<?php if ($trip) echo $trip->trip_location_from; ?>" type="text" class="dashboard-form-control form-control input-lg clearable" placeholder="Enter Starting <?php echo $label_station; ?>" required>

                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Destination <?php echo $label_station; ?></label>
                                        <input name="location_to" id="location_to" value="<?php if ($trip) echo $trip->trip_location_to; ?>" type="text" class="clearable dashboard-form-control input-lg" placeholder=" <?php echo $transport == 'vehicle' ? $vihicle_place_holder : 'Enter Destination ' . $label_station; ?>">
                                    </div>
                                </div>
                                <input name="location_from_latlng" id="location_from_latlng" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng; ?>">
                                <input name="location_to_latlng" id="location_to_latlng" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng; ?>">
                                <input name="location_multi_waypoint_latlng" id="location_multi_waypoint_latlng" class="inp1" type="hidden">
                                <input name="via_waypoints" id="via_waypoints" type="hidden">
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Date of Departure</label>
                                        <input type="text" class="dashboard-form-control datepicker input-lg" placeholder="Date of Departure" name="location_datel" value="<?php if ($trip) echo $trip->trip_location_datel; ?>" autocomplete="off" id="location_datel" required="">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Departure Time (HH:MM)</label>
                                        <input type="time" class="dashboard-form-control input-lg" placeholder="Departure Time" name="location_datel_deptime" value="<?php if ($trip) echo $trip->trip_location_datel_deptime; ?>" autocomplete="off" id="location_datel_deptime" required="">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Arrival Date</label>
                                        <input type="text" class="dashboard-form-control datepicker input-lg" placeholder="Arrival Date" name="location_datel_arr" value="" autocomplete="off" id="location_datel_arr" required="">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Arrival Time (HH:MM)</label>
                                        <input type="time" class="dashboard-form-control input-lg" placeholder="Arrival Time" name="location_datel_arrtime" value="<?php if ($trip) echo $trip->trip_location_datel_arrtime; ?>" autocomplete="off" id="location_datel_arrtime" required="">
                                    </div>
                                </div>
                            </div>
                            <?php if ($transport != 'vehicle') { ?>
                                <div class="row">
                                    <div class="col-md-12 col-lg-4">
                                        <div class="form-group frm-grp">
                                            <label class="mr-b-10"><?php echo $label; ?> Number</label>
                                            <input type="text" class="dashboard-form-control input-lg" placeholder="<?php echo $label; ?> Number" name="dep_flight_no" id="dep_flight_no" value="<?php if ($trip) echo $trip->trip_dep_flight_no; ?>" autocomplete="off" required="">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-4">
                                        <div class="form-group frm-grp">
                                            <label class="mr-b-10">Seat Number</label>
                                            <input type="text" class="dashboard-form-control input-lg" placeholder="Seat Number" name="dep_seat_no" id="dep_seat_no" value="<?php if ($trip) echo $trip->trip_dep_seat_no; ?>" autocomplete="off" required="">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div id="return">
                                <div class="row">
                                    <div class="col-md-12 col-lg-3">
                                        <div class="form-group frm-grp">
                                            <label class="mr-b-10">Date of Return</label>
                                            <input type="text" class="dashboard-form-control datepicker input-lg" placeholder="Date of Return" name="location_dater" autocomplete="off" value="<?php if ($trip) echo $trip->trip_location_dater; ?>" id="location_dater">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-3">
                                        <div class="form-group frm-grp">
                                            <label class="mr-b-10">Departure Time (HH:MM)</label>
                                            <input type="time" class="dashboard-form-control input-lg" placeholder="Departure Time" name="location_dater_deptime" value="<?php if ($trip) echo $trip->trip_location_dater_deptime; ?>" autocomplete="off" id="location_dater_deptime">
                                        </div>
                                    </div>

                                </div>

                                <?php if ($transport != 'vehicle') { ?>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-4">
                                            <div class="form-group frm-grp">
                                                <label class="mr-b-10"><?php echo $label; ?> Number</label>
                                                <input type="text" class="dashboard-form-control input-lg" placeholder="<?php echo $label; ?> Number" name="ret_flight_no" id="ret_flight_no" value="<?php if ($trip) echo $trip->trip_ret_flight_no; ?>" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-lg-4">
                                            <div class="form-group frm-grp">
                                                <label class="mr-b-10">Seat Number</label>
                                                <input type="text" class="dashboard-form-control input-lg" placeholder="Seat Number" name="ret_seat_no" id="ret_seat_no" value="<?php if ($trip) echo $trip->trip_ret_seat_no; ?>" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <div id="waypts_div" style="display:none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="mr-b-10">Stop</label>
                                    </div>
                                </div>
                                <div id="add_more_stop_input0" class="row">
                                    <div class="form-group col-md-6">
                                        <input name="multi_location_waypoint[]" autocomplete="off" index="0" onfocus="onWayPointKeyUp(this)" id="multi_location_waypoint0" value="" type="text" class="clearable dashboard-form-control input-lg" placeholder="First In-Between Stop">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-default btn-sm" id="addMoreStop" style="font-size: inherit !important;">
                                            <span class="fa fa-plus"></span>
                                        </button>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="dashboard-form-control datepicker input-lg" placeholder="Enter date" name="multi_location_waypoint_date[]" id="multi_location_waypoint_date0" value="" autocomplete="off">
                                    </div>
                                </div>
                                <div id="append_more_stop" class="row">
                                </div>
                            </div>
                            <div class="row pt-4">
                                <h5 class="add-hotel-text col-lg-6 col-md-12 main-color">Add a Hotel Stay</h5>
                                <div class="checkbox checkbox-primary col-lg-6 col-md-12">
                                    <input name="checkbox-hotel" id="checkbox-hotel" type="checkbox">
                                    <label for="checkbox-hotel">
                                        Use same dates as above
                                    </label>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Hotel Name</label>
                                        <input type="text" name="hotel_name" id="hotel_name" class="dashboard-form-control input-lg" placeholder="Hotel Name" value="<?php if ($trip) echo $trip->trip_hotel_name; ?>">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Date of Check in</label>
                                        <input type="text" name="hotel_date_checkin" id="hotel_date_checkin" autocomplete="off" value="<?php if ($trip) echo $trip->trip_hotel_date_checkin; ?>" class="dashboard-form-control datepicker input-lg" placeholder="Date of Check in">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Date of Check out</label>
                                        <input type="text" name="hotel_date_checkout" id="hotel_date_checkout" class="dashboard-form-control datepicker input-lg" placeholder="Date of Check out" value="<?php if ($trip) echo $trip->trip_hotel_date_checkout; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12" style="min-height: 46px;">
                                    <div class="checkbox checkbox-primary">
                                        <input name="hotel_located" id="hotel_located" type="checkbox">
                                        <label for="hotel_located">
                                            Located at Airport
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label class="mr-b-10">Hotel Address</label>
                                    <input class="dashboard-form-control input-lg" type="text" placeholder="Enter Hotel Address" name="hotel_address" id="hotel_address" value='<?php if ($trip) echo $trip->trip_rental_agency_address; ?>'>
                                </div>
                            </div>
                            <div class="row pt-4">
                                <h5 class="col-lg-6 col-md-12 add-hotel-text main-color">Add a Car Rental</h5>
                                <div class="col-lg-6 col-md-12 checkbox checkbox-primary">
                                    <input name="checkbox-car-rental" id="checkbox-car-rental" type="checkbox">
                                    <label for="checkbox-car-rental">
                                        Use same dates as above
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Rental Agency</label>
                                        <input type="text" class="dashboard-form-control input-lg" placeholder="Rental Agency" value="<?php if ($trip) echo $trip->trip_rental_agency; ?>" name="rental_agency" id="rental_agency">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group frm-grp">
                                        <label class="mr-b-10">Date of pick up</label>
                                        <input type="text" name="rental_date_pick" id="rental_date_pick" class="dashboard-form-control datepicker input-lg" placeholder="Date of pick up" value="<?php if ($trip) echo $trip->trip_rental_date_pick; ?>">
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-3">
                                    <div class="form-group">
                                        <label class="mr-b-10">Date of drop off</label>
                                        <input type="text" name="rental_date_drop" id="rental_date_drop" class="dashboard-form-control datepicker input-lg" placeholder="Date of drop off" value="<?php if ($trip) echo $trip->trip_rental_date_drop; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12" style="min-height: 46px;">
                                    <div class="checkbox checkbox-primary">
                                        <input name="rental_agency_located" id="rental_agency_located" type="checkbox">
                                        <label for="rental_agency_located">
                                            Located at Airport
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <label class="mr-b-10">Rental Agency Address</label>
                                    <input class="dashboard-form-control input-lg" placeholder="Rental Agency Address" name="rental_agency_address" id="rental_agency_address" value='<?php if ($trip) echo $trip->trip_rental_agency_address; ?>'>
                                </div>
                            </div>

                            <script src="<?php echo SITE; ?>js/node_modules/php-date-formatter/js/php-date-formatter.min.js"></script>
                            <script src="<?php echo SITE; ?>js/node_modules/jquery-mousewheel/jquery.mousewheel.js"></script>
                            <script src="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.js?v=3"></script>
                            <div class="row pt-4">
                                <?php if ($transport != 'vehicle') { ?>
                                    <div class="col-md-12">
                                        <div class="form-group mb-10">
                                            <label><a href="#" onclick="toggle_visibility('driving');" class="outline-btn">
                                                    <i class="mdi mdi-plus-circle-outline plus-icon color-black"></i>Add Driving Portion</a></label>
                                            <div id="driving" style="display:none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input name="location_from_drivingportion" id="location_from_drivingportion" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>" type="text" class="dashboard-form-control input-lg" placeholder="Driving Start">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input name="location_to_drivingportion" id="location_to_drivingportion" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>" type="text" class="dashboard-form-control input-lg" placeholder="Driving Destination">
                                                    </div>
                                                    <input name="location_from_latlng_drivingportion" id="location_from_latlng_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_drivingportion; ?>">
                                                    <input name="location_to_latlng_drivingportion" id="location_to_latlng_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_drivingportion; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($transport != 'plane') { ?>
                                    <div class="col-md-12">
                                        <div class="form-group mb-10">
                                            <label><a href="#" onclick="toggle_visibility('flight');" class="outline-btn">
                                                    <i class="mdi mdi-plus-circle-outline plus-icon color-black"></i>Add flight Portion</a></label>
                                            <div id="flight" style="display:none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input name="location_from_flightportion" id="location_from_flightportion" value="<?php if ($trip) echo $trip->trip_location_from_flightportion; ?>" type="text" class="clearable dashboard-form-control input-lg" placeholder="Takeoff Location">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input name="location_to_flightportion" id="location_to_flightportion" value="<?php if ($trip) echo $trip->trip_location_to_flightportion; ?>" type="text" class="dashboard-form-control input-lg" placeholder="Destination Location">
                                                    </div>
                                                    <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
                                                    <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($transport != 'train') { ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><a href="#" onclick="toggle_visibility('train');" class="outline-btn">
                                                    <i class="mdi mdi-plus-circle-outline plus-icon color-black"></i>Add a Train Portion</a></label>
                                            <div id="train" style="display:none;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input name="location_from_trainportion" id="location_from_trainportion" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>" type="text" class="dashboard-form-control input-lg" placeholder="Takeoff Location">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input name="location_to_trainportion" id="location_to_trainportion" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>" type="text" class="dashboard-form-control input-lg" placeholder="Destination Location">
                                                    </div>
                                                    <input name="location_from_latlng_trainportion" id="location_from_latlng_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_trainportion; ?>">
                                                    <input name="location_to_latlng_trainportion" id="location_to_latlng_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_trainportion; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pt-5px">
                                    <!--<div class="form-group frm-grp">-->


                                    <a href="<?php echo SITE; ?>trip/origin-destination/<?php echo $transport; ?>" class="finish-next-btn">Clear All</a>
                                    <!--</div>-->
                                </div>
                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 text-right pt-10px">
                                    <a href="" class="skip-note-btn float-none mobile_skip">Skip Section</a>
                                    <input name="location_submit" type="submit" id="location_submit" value="Create Trip" class="save-event-btn ml-3">
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <br clear="all" />
    <div id="map"></div>


    <script>
        var transportWay = '<?php echo $transport; ?>';
    </script>
    <script>
        $(window).on('load', function() {
            $('#schedule-modal').modal('show');

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
            var originPlaceAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: originInput,
                type: 'airport',
            });
            var destPlaceAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: destinationInput,
                type: 'airport',
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
            var origin_dportionAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: originInput_dportion,
                type: '',
            });
            var destination_dportionAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: destinationInput_dportion,
                type: '',
            });
            this.setupAlgoliaPlaceChangedListenerPortion(origin_dportionAutocomplete, 'ORIG', 'DRIVING');
            this.setupAlgoliaPlaceChangedListenerPortion(destination_dportionAutocomplete, 'DEST', 'DRIVING');
            var originInput_tportion = document.getElementById('location_from_trainportion');
            var destinationInput_tportion = document.getElementById('location_to_trainportion');
            var origin_tportionAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: originInput_tportion,
                type: 'trainStation',
            });
            var destination_tportionAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: destinationInput_tportion,
                type: 'trainStation',
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

                //alert(me.origin_dportionPlaceLocation)
                me.portionroute(ptype);
            });
        };
        DrawPlaneDirectionsHandler.prototype.setupAlgoliaPlaceChangedListenerPortion = function(autocomplete, mode, ptype) {
            var me = this;
            autocomplete.on('change', function(e) {
                var geocoder = new google.maps.Geocoder;
                var latlng = e.suggestion.latlng;
                geocoder.geocode({
                    'location': latlng
                }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var place = results[0];
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
                        }
                    }
                });
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
                // var marker_origin = new google.maps.Marker({
                //     position: new google.maps.LatLng(me.origin_dportionPlaceLocation.lat(), me.origin_dportionPlaceLocation.lng()),
                //     label: {
                //         text: "",
                //         color: "#ffffff",
                //     },
                //     icon:'https://planiversity.com/assets/images/icon.png'
                // });
                var marker_destination = new google.maps.Marker({
                    position: new google.maps.LatLng(me.destination_dportionPlaceLocation.lat(), me.destination_dportionPlaceLocation.lng()),
                    icon: 'https://planiversity.com/assets/images/icon.png',
                    label: {
                        text: markerAlpaArr[mark_number + 1],
                        color: "#ffffff",
                    }
                });
                //if(marker_a)
                //marker_origin.setMap(map);
                //if(marker_b)
                marker_destination.setMap(map);
                // bounds.extend(marker_origin.position);
                bounds.extend(marker_destination.position);
                map.fitBounds(bounds);
            } else {
                document.getElementById('location_from_latlng_trainportion').value = me.origin_tportionPlaceLocation;
                document.getElementById('location_to_latlng_trainportion').value = me.destination_tportionPlaceLocation;
                var bounds = new google.maps.LatLngBounds();
                // var marker_origin = new google.maps.Marker({
                //     position: new google.maps.LatLng(me.origin_tportionPlaceLocation.lat(), me.origin_tportionPlaceLocation.lng()),
                //     label: {
                //         text: "",
                //         color: "#ffffff",
                //     },
                //     icon:'https://planiversity.com/assets/images/icon.png'
                // });
                var marker_destination = new google.maps.Marker({
                    position: new google.maps.LatLng(me.destination_tportionPlaceLocation.lat(), me.destination_tportionPlaceLocation.lng()),
                    icon: 'https://planiversity.com/assets/images/icon.png',
                    label: {
                        text: markerAlpaArr[mark_number + 1],
                        color: "#ffffff",
                    }
                });
                //if(marker_a)
                //marker_origin.setMap(map);
                //if(marker_b)
                marker_destination.setMap(map);
                // bounds.extend(marker_origin.position);
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
                if (status === 'OK') { //alert('-origin:---'+this.origin_dportionPlaceId+'----'+ptype);
                    //if (ptype=='DRIVING') me.directionsDisplay1.setDirections(response); else me.directionsDisplay2.setDirections(response);
                    var line = new google.maps.Polyline({
                        path: response.routes[0].overview_path,
                        //geodesic: true,
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
            autocomplete.on('change', function(e) {
                var geocoder = new google.maps.Geocoder;
                var latlng = e.suggestion.latlng;
                geocoder.geocode({
                    'location': latlng
                }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var place = results[0];
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
                        } else {
                            window.alert('No results found in google map.');
                        }
                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }
                });
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
                    var inputType = "";
                    break;
                case "plane":
                    var inputType = "airport";
                    break;
                case "train":
                    var inputType = "trainStation";
                    break;
                default:
                    var inputType = "";
                    break;
            }
            var originPlaceAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: originInput,
                type: inputType,
            });
            var destPlaceAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: destinationInput,
                type: inputType,
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
                    // var waypoints = result.route[0].legs[j].via_waypoint;
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
                // console.log(points_arr);
                if (result.routes[0].legs.length > 0) {
                    // get miles
                    var center_point = result.routes[0].overview_path.length / 2;
                    if (infowindow1) {
                        infowindow1.close();
                    }
                    infowindow1 = new google.maps.InfoWindow();
                    infowindow1.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/car_icon.png'></div><div style='float:right; padding: 3px;'>" + calcTotalDistanceText(result) + "<br>" + calcTotalDurationText(result) + "</div>");
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
                var origin_dportionAutocomplete = places({
                    appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                    apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                    container: originInput_dportion,
                    type: '',
                });
                var destination_dportionAutocomplete = places({
                    appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                    apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                    container: destinationInput_dportion,
                    type: '',
                });
                this.setupAlgoliaPlaceChangedListenerPortion(origin_dportionAutocomplete, 'ORIG', 'DRIVING');
                this.setupAlgoliaPlaceChangedListenerPortion(destination_dportionAutocomplete, 'DEST', 'DRIVING');
            } else {
                var originInput_tportion = document.getElementById('location_from_trainportion');
                var destinationInput_tportion = document.getElementById('location_to_trainportion');
                var origin_tportionAutocomplete = places({
                    appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                    apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                    container: originInput_tportion,
                    type: 'trainStation',
                });
                var destination_tportionAutocomplete = places({
                    appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                    apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                    container: destinationInput_tportion,
                    type: 'trainStation',
                });
                this.setupAlgoliaPlaceChangedListenerPortion(origin_tportionAutocomplete, 'ORIG', 'TRANSIT');
                this.setupAlgoliaPlaceChangedListenerPortion(destination_tportionAutocomplete, 'DEST', 'TRANSIT');
            }
            var originInput_fportion = document.getElementById('location_from_flightportion');
            var destinationInput_fportion = document.getElementById('location_to_flightportion');
            var origin_fportionAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: originInput_fportion,
                type: 'airport',
            });
            var destination_fportionAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: destinationInput_fportion,
                type: 'airport',
            });
            this.setupAlgoliaPlaceChangedListenerPortion(origin_fportionAutocomplete, 'ORIG', 'PLANE');
            this.setupAlgoliaPlaceChangedListenerPortion(destination_fportionAutocomplete, 'DEST', 'PLANE');
        }

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
            autocomplete.on('change', function(e) {
                var geocoder = new google.maps.Geocoder;
                var latlng = e.suggestion.latlng;
                geocoder.geocode({
                    'location': latlng
                }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var place = results[0];

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
                    }
                });
            });
        };
        AutocompleteDirectionsHandler.prototype.setupAlgoliaPlaceChangedListener = function(autocomplete, mode) {
            var me = this;
            // autocomplete.bindTo('bounds', this.map);
            autocomplete.on('change', function(e) {
                var geocoder = new google.maps.Geocoder;
                var latlng = e.suggestion.latlng;
                geocoder.geocode({
                    'location': latlng
                }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var place = results[0];
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
                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }
                });
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
                // marker_origin = new google.maps.Marker({
                //     position: me.origin_dportionPlaceLocation,
                //     icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + markerAlpaArr[mark_number] + '|007acc|FFFFFF'
                // });
                marker_destination = new google.maps.Marker({
                    position: me.destination_dportionPlaceLocation,
                    icon: 'https://planiversity.com/assets/images/icon.png',
                    label: {
                        text: markerAlpaArr[mark_number + 1],
                        color: "#ffffff",
                    }
                });
                //marker_origin.setMap(this.map);
                marker_destination.setMap(this.map);
                // bounds.extend(marker_origin.position);
                bounds.extend(marker_destination.position);
            }
            if (ptype == 'TRANSIT') {
                document.getElementById('location_from_latlng_trainportion').value = me.origin_tportionPlaceLocation;
                document.getElementById('location_to_latlng_trainportion').value = me.destination_tportionPlaceLocation;
                var bounds = new google.maps.LatLngBounds();
                // marker_origin = new google.maps.Marker({
                //     position: me.origin_tportionPlaceLocation,
                //     icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + markerAlpaArr[mark_number] + '|007acc|FFFFFF'
                // });

                marker_destination = new google.maps.Marker({
                    position: me.destination_tportionPlaceLocation,
                    icon: 'https://planiversity.com/assets/images/icon.png',
                    label: {
                        text: markerAlpaArr[mark_number + 1],
                        color: "#ffffff",
                    }
                });
                //marker_origin.setMap(this.map);
                marker_destination.setMap(this.map);
                // bounds.extend(marker_origin.position);
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
                    //travelMode: this.travelMode,
                    travelMode: google.maps.TravelMode[ptype]
                }, function(response, status) {
                    if (status === 'OK') {
                        /*alert('-------'+ptype);  */
                        //if (ptype=='DRIVING') me.directionsDisplay1.setDirections(response); else me.directionsDisplay2.setDirections(response);

                        var line = new google.maps.Polyline({
                            path: response.routes[0].overview_path,
                            //geodesic: true,
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
                // marker_origin = new google.maps.Marker({
                //     position: me.origin_fportionPlaceLocation,
                //     icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + markerAlpaArr[mark_number] + '|007acc|FFFFFF'
                // });
                marker_destination = new google.maps.Marker({
                    position: me.destination_fportionPlaceLocation,
                    icon: 'https://planiversity.com/assets/images/icon.png',
                    label: {
                        text: markerAlpaArr[mark_number + 1],
                        color: "#ffffff",
                    }
                });
                //marker_origin.setMap(this.map);
                marker_destination.setMap(this.map);
                // bounds.extend(marker_origin.position);
                bounds.extend(marker_destination.position);

                //document.getElementById('location_from_latlng_flightportion').value = me.origin_fportionPlaceLocation;
                //document.getElementById('location_to_latlng_flightportion').value = me.destination_fportionPlaceLocation;

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
                flightPath = new google.maps.Polyline({
                    path: flightPlanCoordinates,
                    geodesic: true,
                    strokeColor: '#0688E9',
                    strokeOpacity: 1.0,
                    strokeWeight: 3
                });

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

        AutocompleteDirectionsHandler.prototype.route = function() {
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
                        //                me.directionsDisplay
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

                        var center_point2 = response.routes[0].overview_path.length / 2;
                        var infowindow2 = new google.maps.InfoWindow();
                        infowindow2.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/train_icon2.png'></div><div style='float:right; padding: 3px;'>" + response.routes[0].legs[0].distance.text + "<br>" + response.routes[0].legs[0].duration.text + "</div>");
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
                //                $('#rental_agency_address').prop('required',false);
            } else {
                $("#rental_agency_address").show();
                //                $("#rental_agency_address").prop('required',true);
            }
        });
        $("#hotel_located").click(function() {
            if (this.checked == true) {
                $("#hotel_address").hide();
                //                $('#hotel_address').prop('required',false);
            } else {
                $("#hotel_address").show();
                //                $("#hotel_address").prop('required',true);
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

        $(".close").click(function() {
            if ($("#schedule-modal").hasClass('modaltrans')) {
                $("#schedule-modal").removeClass('modaltrans');
                $("#schedule-modal-body").removeClass('modaltrans-body');
                $("#schedule-modal-body").removeClass('modaltrans-body-mozila');
                $("#myLargeModalLabel").css({
                    fontSize: 21
                });
                //                $('#schedule-modal').modal('show');
                $(this).html("-");
            } else {
                $("#schedule-modal").addClass('modaltrans');
                if (window.navigator.userAgent.indexOf("Chrome") > -1) {
                    $("#schedule-modal-body").addClass('modaltrans-body');
                } else {
                    $("#schedule-modal-body").addClass('modaltrans-body-mozila');
                }

                $("#myLargeModalLabel").css({
                    fontSize: 15
                });
                //                $('#schedule-modal').collapse()
                //                $('#schedule-modal').modal('show');
                $(this).html("+");
            }
        });
        var add_more_stop_count = 0
        var multi_arr_value = [];
        //        var waypoints = [];
        var currentWriteIndex = null;
        var currentChangeIndex = null;
        var wayptAutocompleteVar = [];
        var is_update = null;
        var i = 0;
        var place = "";

        function addLocationToStop(index) {
            switch (transportWay) {
                case "vehicle":
                    var inputType = "";
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
            // wayptAutocompleteVar[index] = new google.maps.places.Autocomplete(document.getElementById('multi_location_waypoint' + index), {types: ['establishment']});
            wayptAutocompleteVar[index] = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: document.getElementById("multi_location_waypoint" + index),
                type: inputType
            });

            // wayptAutocompleteVar[index].bindTo('bounds', map);
            wayptAutocompleteVar[index].on('change', function(e) {
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
                var geocoder = new google.maps.Geocoder;
                var latlng = e.suggestion.latlng;
                geocoder.geocode({
                    'location': latlng
                }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var place = results[0];
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
                                for (i = 0; i < multi_arr_value.length; i++) {
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
                                }
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
                    }
                });
            });

        }
        //        var add_more_map_lable = ['A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z']
        var addMoreLabel = ["First", "Second", "Third", "Fourth", "Fifth", "Sixth", "Seventh", "8th", "9th"];
        $('#addMoreStop').click(function() {
            if ($('#append_more_stop .dashboard-form-control').length <= 1 && transportWay == "vehicle") {
                add_more_stop_count++;
                $('#append_more_stop').append(`
                    <div id="more_block${add_more_stop_count}" class="row" style="width: 100%;margin-left:0;">
                        <div id="add_more_stop_input${add_more_stop_count}" class = "col-md-6">
                            <div class="form-group">
                                <input name="multi_location_waypoint[]" autocomplete = "off" index="${add_more_stop_count}" onfocus="onWayPointKeyUp(this)" id = "multi_location_waypoint${add_more_stop_count}" value = "" type = "text" class = "clearable dashboard-form-control input-lg" placeholder = "${add_more_stop_count < 8?addMoreLabel[add_more_stop_count]:((add_more_stop_count*1+1)+"th")} In-Between Stop">
                            </div>
                        </div>
                        <div class="col-md-2"  id="add_more_stop_btn${add_more_stop_count}">
                            <button type="button" class="btn btn-default btn-sm" onclick="removeStop(${add_more_stop_count})"  style="font-size: inherit !important;">
                                <span class="fa fa-minus"></span>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="dashboard-form-control datepicker input-lg" name="multi_location_waypoint_date[]"  id="multi_location_waypoint_date${add_more_stop_count}" value="" autocomplete="off" placeholder = "Enter date">
                        </div>
                    </div>
                `);
                addLocationToStop(add_more_stop_count);
                $(`#multi_location_waypoint_date${add_more_stop_count}`).datepicker({
                    format: "yyyy-mm-dd"
                }).on('change', function() {
                    $('.datepicker.datepicker-dropdown').hide();
                });
            }
        });

        function onWayPointKeyUp(e) {
            currentChangeIndex = e.getAttribute("index");
        }

        function removeStop(value) {
            $('#more_block' + value).remove()
            // $('#add_more_stop_btn' + value).remove()
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

        function setDrivingAndTrain() {
            setTimeout(function() {
                var location_to = document.getElementById("location_to");
                location_to = location_to.value;
                var statusDriving = $("#driving").css('display');
                var statusFlight = $("#flight").css('display');
                var statusTrain = $("#train").css('display');
                if (statusDriving === "block" && transportWay !== 'train') {
                    $("#location_from_drivingportion").val(location_to);
                    var reAutocomplete = places({
                        appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                        apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                        container: document.getElementById("location_from_drivingportion"),
                        type: '',
                    });
                } else {
                    $("#location_from_drivingportion").val("");
                }
                if (statusFlight === "block" && transportWay !== 'train') {
                    $("#location_from_flightportion").val(location_to);
                    var reAutocomplete = places({
                        appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                        apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                        container: document.getElementById("location_from_flightportion"),
                        type: '',
                    });
                } else {
                    $("#location_from_flightportion").val("");
                }
                if (statusTrain === "block") {
                    $("#location_from_trainportion").val(location_to);
                    var reAutocomplete = places({
                        appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                        apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                        container: document.getElementById("location_from_trainportion"),
                        type: '',
                    });
                } else {
                    $("#location_from_trainportion").val("");
                }
            }, 100);
        }


        // custom function for current page.
        $(document).ready(function() {
            $(document).on("change", "#location_to", function() {
                setDrivingAndTrain();
            });
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            })
            setTimeout(function() {
                addLocationToStop(0);
            }, 1000);
            var hotel_address = document.getElementById('hotel_address');
            var hotelAddressPlaceAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: hotel_address,
                type: 'address',
            });
            var rental_agency_address = document.getElementById('rental_agency_address');
            var rentalAgencyAddressPlaceAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: rental_agency_address,
                type: 'address',
            });

        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

    <?php include('new_backend_footer.php'); ?>


</body>

</html>