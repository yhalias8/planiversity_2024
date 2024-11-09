<?php
include_once("config.ini.php");
include_once("config.ini.curl.php");
include("class/class.TripPlan.php");
include("class/class.Plan.php");

$plan = new Plan();

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/resources/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}

$output = '';

$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);


if (empty($id_trip)) {
    $_SESSION['redirect'] = 'trip/resources/' . $_GET['idtrip'];
}

$trip->get_data($id_trip);

if ($trip->error) {
    if ($trip->error == 'error_access') {
        header("Location:" . SITE . "trip/how-are-you-traveling");
        $output = 'You do not have access to this trip';
    } else
        $output = 'A system error has been encountered. Please try again.';
}

$trip_location_from_latlng = $trip->trip_location_from_latlng;
$trip_location_to_latlng = $trip->trip_location_to_latlng;
$trip_has_train = false;


if ($trip->itinerary_type == "event") {
    $trip_location_from_latlng = $trip->trip_location_from_latlng;
    $trip_location_to_latlng = $trip->trip_location_to_latlng;
}

if ($trip->location_portion_to_latlng) {
    $trip_location_to_latlng = $trip->location_portion_to_latlng;
}

if ($trip->trip_transport == 'plane') {
    if ($trip->trip_location_to_latlng_drivingportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_drivingportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_drivingportion;
    }

    if ($trip->trip_location_to_latlng_trainportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_trainportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_trainportion;
        $trip_has_train = true;
    }
}


if ($trip->trip_transport == 'vehicle') {
    if ($trip->trip_location_to_latlng_flightportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_flightportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_flightportion;
    }
    if ($trip->trip_location_to_latlng_trainportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_trainportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_trainportion;
        $trip_has_train = true;
    }
    if ($trip->trip_location_to_latlng_drivingportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_drivingportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_drivingportion;
    }
}

//echo $trip_location_to_latlng;

if ($trip->trip_transport == 'train') {
    if ($trip->trip_location_to_latlng_flightportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_flightportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_flightportion;
    }
    if ($trip->trip_location_to_latlng_drivingportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_drivingportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_drivingportion;
    }
    $trip_has_train = true;
}


$transport = (isset($trip->trip_transport) && !empty($trip->trip_transport)) ? $trip->trip_transport : '';
$tmp = str_replace('(', '', $trip->trip_location_from_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp = str_replace(')', '', $tmp);
$tmp = explode(',', $tmp);

$lat_from = $lat_from2 = trim($tmp[0]);
$lng_from = $lng_from2 = trim($tmp[1]);

if ($trip->trip_location_waypoint_latlng != '') {
    $tmp = str_replace('(', '', $trip->trip_location_waypoint_latlng); // Ex: (25.7616798, -80.19179020000001)
    $tmp = str_replace(')', '', $tmp);
    $tmp = explode(',', $tmp);
    $lat_to = $lat_to2 = trim($tmp[0]);
    $lng_to = $lng_to2 = trim($tmp[1]);
} else {
    $tmp = str_replace('(', '', $trip->trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
    $tmp = str_replace(')', '', $tmp);
    $tmp = explode(',', $tmp);
    $lat_to = $lat_to2 = trim($tmp[0]);
    $lng_to = $lng_to2 = trim($tmp[1]);
}

//echo ;
$tmp1 = str_replace('(', '', $trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp1 = str_replace(')', '', $tmp1);
$tmp1 = explode(',', $tmp1);

$filter_lat_to = $filter_lat_to2 = trim($tmp1[0]);
$filter_lng_to = $filter_lng_to2 = trim($tmp1[1]);

$markerAlpaArr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB');

$radius = 0;
$zoom = 11;
$showclear = 0;

if ($trip->trip_option_weather || $trip->trip_option_hotels || $trip->trip_option_police || $trip->trip_option_university || $trip->trip_option_atm || $trip->trip_option_library || $trip->trip_option_pharmacy || $trip->trip_option_metro || $trip->trip_option_subway_station || $trip->trip_option_playground || $trip->trip_option_museum || $trip->trip_option_church || $trip->trip_option_hospitals || $trip->trip_option_gas || $trip->trip_option_embassis || $trip->trip_option_taxi || $trip->trip_option_airfields || $trip->trip_option_directions || $trip->trip_option_busmap || $trip->trip_option_parking)
    $showclear = 1;

if (isset($_POST['lat_to']) && isset($_POST['lng_to']) && isset($_POST['radius'])) {
    $lat_to = $_POST['lat_to'];
    $lng_to = $_POST['lng_to'];
    $radius = $_POST['radius'];
    $showclear = 1;
} else if (!empty($trip->trip_option_circle)) {
    $circle_data = explode('::', $trip->trip_option_circle);
    $lat_to = $circle_data[0];
    $lng_to = $circle_data[1];
    $radius = $circle_data[2];
    $showclear = 1;
}


$travelmode = 'DRIVING';
switch ($transport) {
    case 'vehicle':
        $travelmode = 'DRIVING';
        break;
    case 'train':
        $travelmode = 'TRANSIT';
        break;
}

$dtd = $dbh->prepare("SELECT id_plan as id,
    plan_name as title,
    plan_lat as lat,
    plan_lng as lng,
    plan_type as type,
    plan_address as address,
    plan_checked_in,
    plan_date,
    schedule_linked
    FROM tripit_plans
    WHERE trip_id=? AND schedule_linked = 0;
");

$dtd->bindValue(1, $id_trip, PDO::PARAM_INT);
$tmp = $dtd->execute();
$aux = '';
$plans = [];
if ($tmp && $dtd->rowCount() > 0) {
    $plans = $dtd->fetchAll(PDO::FETCH_OBJ);
}

if ($userdata['account_type'] == 'Individual') {
    $maxFileCount = $plan->individual_check_plan($userdata['id']) ? 100 : 1;
} else {
    $maxFileCount = $plan->check_plan($userdata['id']) ? 100 : 1;
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
    <title>PLANIVERSITY - PLAN</title>

    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>style/style.css?v=20230815" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>assets/css/app-style.css?v=20230815" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="<?php echo SITE ?>assets/js/jquery.min.js"></script>
    <script src="<?= SITE; ?>js/calendar/moment.min.js"></script>


    <script>
        var SITE = '<?= SITE; ?>';
        var itinerary_type_mode = "<?= $trip->itinerary_type; ?>";
    </script>

    <script src="<?= SITE; ?>js/js_map.js"></script>
    <script src="<?= SITE; ?>js/global.js?v=202030"></script>

    <link href="<?= SITE; ?>js/node_modules/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>

    <style>
        .advanced_footer {
            display: flex;
            justify-content: space-between;
            padding: 12px;
        }

        .advanced_footer button:nth-child(2) {
            margin-left: auto;
        }


        .advanced_nav {
            text-align: left;
        }

        .map_icon i {
            margin-right: 3px;
            color: #0688e9;
        }

        .advanced_nav span {
            cursor: pointer;
            text-align: left;
            text-decoration: underline;
        }

        .modaltrans {
            width: 292px;
            overflow: hidden !important;
            padding-left: 0px !important;
            max-height: 300px;
            margin-left: 14px;
        }

        .modaltrans-body {
            transform: scale(0.2) translate(-200%, -200%);
            width: 500%;
        }

        .map-radius-value-input {
            text-indent: inherit;
            padding: 2px;
            border: 1px solid;
        }

        .master_modal {
            overflow: scroll;
        }

        .custom-group {
            margin-bottom: 2px;
        }

        .form-group.custom-group label {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .form-group.custom-group input {
            height: 38px;
        }

        .rounded-plus-right {
            border-radius: 0 0.85rem 0.85rem 0;
        }

        .pac-container {
            z-index: 1900;
            /* Adjust this value as needed */
        }

        div#right-panel {
            background: transparent !important;
        }

        .modal-backdrop {
            background-color: #000;
            z-index: 1111;
        }

        .modal-blur {
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
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

        .modal-content {
            background: #fff;
            border-radius: 8px;
        }

        .your_plan_item_list {
            overflow-y: scroll;
            margin-right: 1px;
            height: calc(370px - 70px);
        }

        .cmodal {
            margin-bottom: 0 !important;
            top: 80px;
        }

        .modal-preview img {
            min-width: 200px;
            width: 200px;
            transition: 0.3s;
        }

        .modal-preview p {
            margin-top: 15px;
            font-size: 30px;
            font-weight: 700;
        }

        .modal-preview p span {
            display: block;
        }

        .modal-preview a.upgrade-now button {
            font-size: 24px;
            padding: 10px 25px;
            border-radius: 30px;
            background: #357FA6;
            box-shadow: 5px 8px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }

        .modal-preview a.upgrade-now button:hover {
            opacity: 0.7;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .modal-preview a.skip-process {
            display: block;
            margin-top: 10px;
            font-size: 18px;
            color: #202020
        }

        .map_help {
            position: relative;
            top: 1px;
        }

        .map_help p {
            background: #048cf2;
            padding: 7px 10px;
            margin: 0;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        .map_help p:hover {
            background: #4d90c3;
        }

        .modal .modal-dialog .c-close {
            color: #fff;
            font-size: 48px;
            height: 52px;
            width: 48px;
        }

        .active_image {
            display: initial;
        }

        .normal_image {
            display: none;
        }

        .process li.active img.active_image {
            display: none;
        }

        .process li.active img.normal_image {
            display: initial;
        }

        p.main_body {
            margin: 0;
            margin-top: 8px !important;
        }

        p.main_body span {
            display: block;
        }

        strong.main_head {
            display: flex;
            align-items: center;
        }

        strong.main_head img {
            height: auto;
        }

        strong.main_head p {
            margin: 0;
            margin-left: 5px;
            font-weight: bold;
        }

        #loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        div#info-content {
            min-width: 200px;
            min-height: 80px;
            max-width: 300px;
        }

        div#spinner-container {
            padding-top: 20px;
        }

        div#info-content p {
            margin: 0;
            line-height: 21px;
        }

        div#info-content p b {
            font-weight: 600;
        }

        div#info-content ul {
            margin-top: 3px;
            margin-bottom: 3px;
            margin-left: 15px;
        }

        div#info-content ul li {
            font-size: 12px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        .hidden {
            display: none !important;
        }

        label.error {
            font-size: 12px;
            color: red !important;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #e9ecef !important;
            opacity: 1;
        }

        .your_plan_item_list .your_plan_item_edit {
            display: flex;
        }

        button.btn.action_button {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 2px;
            padding: 10px;
        }

        button.btn.action_button i {
            text-align: center;
            font-size: 12px;
            display: flex;
            justify-content: center;
        }

        button.btn.edit.action_button {
            background: #c8dbeb;
            color: #fff;
            border-color: #c8dbeb;
        }

        button.btn.edit.action_button i {
            color: #0C246B;
        }

        button.btn.delete.action_button {
            background: #e7dfd5;
            border-color: #e7dfd5;
        }

        button.btn.delete.action_button i {
            color: #e38409;
        }

        button.btn.action_button:disabled {
            opacity: .35;
        }

        #filter_map {
            height: calc(600px - 10px);
        }

        i.fa.fa-map-marker {
            color: #0688E9;
            font-size: 15px;
        }

        div#upgrade,
        div#advanced_popup {
            z-index: 99999;
        }

        .modal-preview img {
            min-width: 60%;
            width: 50%;
            box-shadow: 3px 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }

        .modal-preview p {
            margin-top: 15px;
            font-size: 30px;
            font-weight: 700;
        }

        .modal-preview p span {
            display: block;
        }

        .modal {
            margin-bottom: 0;
            top: 0px;
        }

        a.upgrade-now button {
            margin: 0 auto;
        }

        .modal-preview a.upgrade-now button {
            font-size: 24px;
            padding: 10px 25px;
            border-radius: 30px;
            background: #357FA6;
            box-shadow: 5px 8px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }

        .modal-preview a.upgrade-now button:hover {
            opacity: 0.7;
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .advanced_section {
            padding: 50px;
        }

        .advanced_section h3 {
            font-size: 26px;
        }

        .advanced_section p {
            margin: 0;
        }

        .advanced_body {
            margin-top: 10px;
        }

        .modal-preview a.skip-process {
            display: block;
            margin-top: 10px;
            font-size: 18px;
            color: #202020
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

        th.datepicker-switch {
            text-align: center;
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

        label#option_date_time-error,
        label#option_schedule-error {
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

        span.schedule_linked i {
            font-size: 12px;
            background: #c8dbeb;
            color: #10286e;
            padding: 4px;
            border-radius: 50%;
        }

        span.schedule_linked {
            margin-right: 3px;
        }

        p.schedule_content_linked i {
            color: #0688E9;
            font-size: 13px;
        }

        a#notes_submit {
            min-width: 250px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 1399px) {
            .right-action {
                display: block;
                margin-top: 20px;
            }

        }

        @media only screen and (max-width: 467px) {
            .modal-preview p {
                font-size: 18px;
            }

        }
    </style>
</head>

<body class="custom_filters">
<div class="fullscreen-background" style="z-index: 1;background-color:white"></div>
<div id="map"></div>
    <!--<div class="content"> -->
    <?php include('new_backend_header.php') ?>

    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <?php
            $step_index = "plan";
            include('dashboard/include/itinerary-step.php');
            ?>
        </div>
    </div>
    </header>

    <div data-backdrop="false" id="filter1-modal" class="modal fade connect-bg master_modal custom_prefix_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: block;padding-left: 17px;">
        <div class="modal-dialog modal-lg modal-custom-dialog mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3 " style="background-color: rgba(245, 250, 253, 0.83);">
            <div class="modal-content connect-bg">
                <div class="modal-header rounded-0">

                    <div class="heading_section">
                        <p>PLANVERSITY</p>
                        <h4 class="modal-title" id="myLargeModalLabel">Your Plans</h4>
                    </div>
                </div>
                <div class="modal-body background-transparent px-4" id="filter1-modal-body">

                    <fieldset>
                        <div class="row">
                            <div class="col-md-12 col-lg-4">

                                <div class="your_plan_tab_menu">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#your_plan">
                                                <i class="fa fa-calendar-check-o" aria-hidden="true"></i>&nbsp;Your Plans
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="type_of_activity_sec">


                                    <form id="form-plan" novalidate="novalidate">

                                        <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>



                                        <div class="form-group custom-group">
                                            <label>Plan Title</label>
                                            <input type="text" id="plan_title" name="plan_title" class="form-control" placeholder="Plan Title">
                                        </div>


                                        <div class="form-group custom-group">
                                            <label>Type of activity</label>
                                            <input type="hidden" id="plan_id" name="plan_id" readonly="">
                                            <select class="form-control" name="plan_type" id="plan_type">
                                                <option value="">Select a option</option>
                                                <option value="Place to eat">Place to eat</option>
                                                <option value="Things to do">Things to do</option>
                                                <option value="People to see">People to see</option>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group custom-group frm-grp">
                                            <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                                            <label>Address</label>
                                            <input type="text" id="plan_address" name="plan_address" class="dashboard-form-control form-control input-lg clearable" required placeholder="Address" disabled>
                                            <?php } ?>
                                            <input name="location_to_lat" id="location_to_lat" type="hidden" class="inp1 coordinate" value="<?= $filter_lat_to; ?>" readonly>
                                            <input name="location_to_lng" id="location_to_lng" type="hidden" class="inp1 coordinate" value="<?= $filter_lng_to; ?>" readonly>
                                            <input name="plans_idtrip" id="plans_idtrip" type="hidden" class="inp1" value="<?php echo $trip->trip_id; ?>" readonly>
                                            <input name="advance_check" class="input_option_opacity" id="advance_check" type="hidden" class="inp1" value="1" readonly>
                                            <input name="schedule_flag" class="input_option_opacity" id="schedule_flag" type="hidden" class="inp1" value="0" readonly>

                                        </div>

                                        <div class="row">
                                            <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                                            <div class="col-sm-6">
                                                <div class="type_of_activity_submit">
                                                    <button type="submit" id="btn-plan" class="btn">Add</button>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="map_help" data-toggle="modal" data-target="#video_popup">
                                                    <p><i class="fa fa-info-circle" aria-hidden="true"></i> How to use</p>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>


                                    </form>

                                </div>


                                <div class="your_plan_item_list">

                                    <ul id="plan_list">

                                    </ul>

                                </div>

                                <div class="error_style"><?= $output; ?></div>
                                <input name="location_from" id="location_from" class="inp1" value="<?= $trip->trip_location_from; ?>" type="hidden">
                                <input name="location_to" id="location_to" class="inp1" value="<?= $trip->trip_location_to; ?>" type="hidden">
                                <input name="timeline_idtrip" id="timeline_idtrip" class="inp1" value="<?= $trip->trip_id; ?>" type="hidden">
                                <input name="lat_to" id="lat_to" class="inp1" value="<?= $lat_to; ?>" type="hidden">
                                <input name="lng_to" id="lng_to" class="inp1" value="<?= $lng_to; ?>" type="hidden">
                                <input name="locality_long_name" id="locality_long_name" class="inp1" value="<?= $locality_long_name; ?>" type="hidden">
                                <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
                                <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
                                <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
                                <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">
                                <input name="trip_location_from_trainportion" id="trip_location_from_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
                                <input name="trip_location_to_trainportion" id="trip_location_to_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">
                                <textarea name="directions_text" id="directions_text" hidden="hidden"><?php if ($trip) echo $trip->trip_directions_text; ?></textarea>

                            </div>

                            <div class="col-md-12 col-lg-8">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="flexible-container rounded-bottom border-grey" id="filter_map"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </fieldset>

                    <div class="border-none pt-2 pb-3">

                        <div class="skip_item_section no-background p-0">
                            <ul class="list-unstyled justify-content-between">
                                <li>
                                    <a href="<?= SITE; ?>trip/create-timeline/<?= $_GET['idtrip']; ?>" class="skipt_value">Back</a>
                                </li>
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>" class="skipt_value">Skip Section</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>" id="notes_submit" class="refresh-btn mt-3 mt-xs-0 mt-sm-0">Finished, Next Step</a>
                                    </li>
                                </ul>
                            </ul>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="push"></div>
    </div>


    <div class="modal fade" id="advanced_popup" tabindex="-1" role="dialog" aria-labelledby="Plans Additional" aria-hidden="false" data-keyboard="false" data-backdrop="static">
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

                                <h3>Do you want to add a date / time to the plan?</h3>
                                <p>Select a option for your plan.</p>

                                <div class="advanced_body">

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-sub custom-control-input option-checkbox" id="plan_date_yes" value="yes" name="plan_date_yes">
                                        <label class="custom-control-label" for="plan_date_yes">
                                            <p>Yes</p>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="plan_date_no" value="no" name="plan_date_no">
                                        <label class="custom-control-label" for="plan_date_no">
                                            <p>No</p>
                                        </label>
                                    </div>


                                    <div class="mt-5 modal-step-sub" style="display: none;">
                                        <div class="row">

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="emp-form-label">Event Date</label>
                                                    <input type="text" name="plan_event_date" id="event_date" class="modal-step-required account-form-control form-control input-lg event_date event-field" placeholder="Date">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="emp-form-label">Event Time</label>
                                                    <input type="time" name="plan_event_time" id="event_time" class="account-form-control form-control input-lg event-field" placeholder="Time" aria-invalid="false">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="advanced_section advanced_step" data-step="2">

                                <h3>Would you like to add a check-in requirement for this plan?</h3>
                                <p>Select a option for your event.</p>

                                <div class="advanced_body">

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="plan_checkin_yes" value="yes" name="plan_checkin_yes">
                                        <label class="custom-control-label" for="plan_checkin_yes">
                                            <p>Yes</p>
                                        </label>
                                    </div>

                                    <div class="custom-control custom-checkbox option-control">
                                        <input type="checkbox" class="modal-btn-next custom-control-input option-checkbox" id="plan_checkin_no" value="no" name="plan_checkin_no">
                                        <label class="custom-control-label" for="plan_checkin_no">
                                            <p>No</p>
                                        </label>
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

    <div class="modal" id="upgrade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">

                    <div class="modal-preview">

                        <img src="<?= SITE; ?>/images/plans_preview.jpg" class="img-responsive" />

                        <p>To use this function, you will <span>have to upgrade your plan</span></p>

                        <a href="<?= SITE ?>billing" class="upgrade-now">
                            <button class="refresh-btn mt-3 mt-xs-0 mt-sm-0">Upgrade Now</button>
                        </a>

                        <a href="<?= SITE . 'trip/travel-documents/' . $_GET['idtrip'] ?>" class="skip-process">
                            Skip, Next Step
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal cmodal fade modal-blur" id="video_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header custom-modal-header">
                    <button type="button" class="close c-close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>

                <div class="modal-body text-center">
                    <div class="modal-preview">
                        <video width="90%" height="auto" id="video" controls>
                            <source src="<?= SITE; ?>assets/video/how_to_plans.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <br clear="all" />


    <?php

    $user_scale = 'METRIC';

    if ($userdata['scale'] == 'imperial') {
        $user_scale = 'IMPERIAL';
    }

    if (!empty($trip->trip_location_to_latlng_flightportion)) {

        $tmp = str_replace('(', '', $trip->trip_location_from_latlng_flightportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_from_flightportion = $tmp[0];
        $lat_fromd = $tmp[0];
        $lng_from_flightportion = $tmp[1];
        $lng_fromd = $tmp[1];
        $tmp = str_replace('(', '', $trip->trip_location_to_latlng_flightportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_to_flightportion = $tmp[0];
        $lng_to_flightportion = $tmp[1];
        $lat_tod = $tmp[0];
        $lng_tod = $tmp[1];
    }
    ?>

    <script>
        var maxFileCount = <?= $maxFileCount ?>

        var myLocation = null;
        var map = null;
        var map_filters = null;
        var bounds = null;
        var directionsService = null;
        var directionsDisplay = null;
        var infoWindow = null;

        var destination_marker = null;
        var populated_marker = null;
        var action_marker = null;
        let markers = [];
        var geocoder = null;
        var location_lat = $("#location_to_lat").val();
        var location_lng = $("#location_to_lng").val();

        const desiredWidth = 38; // Set the desired width here
        const desiredHeight = 38; // Set the desired height here

        var bounds2 = null;

        var icon_path = 'https://planiversity.com/assets/images/icon-pack/';

        let main_logo = "<?= SITE; ?>assets/images/new-logo-small.png";

        function iconSelect(value) {
            var hold;
            switch (value) {
                case "Place to eat":
                    hold = "restaurant_new.png";
                    break;
                case "Things to do":
                    hold = "place_new.png";
                    break;
                case "People to see":
                    hold = "people_new.png";
                    break;
                default:
                    hold = "restaurant_new.png";
            }

            return hold;
        }

        var markers_list = <?php echo json_encode(array_filter($plans, function ($el) {
                                return $el->schedule_linked != '1';
                            })) ?>

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
        if ($trip->trip_location_to_flightportion || $trip->trip_location_to_drivingportion || $trip->trip_location_to_trainportion) {
            $start_marker = $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 1];
            $end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 2];
            $filter_end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 2];
        } else {
            $start_marker = $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng))];
            $end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 1];
            $filter_end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 1];
        }

        ?>

        var location_multi_waypoint = <?= $location_multi_waypoint_latlng; ?>;

        function initMap() {
            directionsService = new google.maps.DirectionsService();
            directionsDisplay = new google.maps.DirectionsRenderer({
                polylineOptions: {
                    strokeColor: "#0688E9"
                }
            });

            myLocation = {
                lat: <?= $filter_lat_to; ?>,
                lng: <?= $filter_lng_to; ?>
            };

            geocoder = new google.maps.Geocoder();

            map_filters = new google.maps.Map(document.getElementById('filter_map'), {
                mapTypeControl: false,
                center: {
                    lat: <?= $filter_lat_to; ?>,
                    lng: <?= $filter_lng_to; ?>
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: <?= $zoom; ?>
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

            var bounds2 = new google.maps.LatLngBounds();

            action_marker = new google.maps.Marker({
                map: map_filters,
                draggable: true,
            });

            var input = document.getElementById('plan_address');
            var autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                // place variable will have all the information you are looking for.

                var location_lat = place.geometry['location'].lat();
                var location_lng = place.geometry['location'].lng();

                $('#location_to_lat').val(location_lat);
                $('#location_to_lng').val(location_lng);

                var flag_type = $('#plan_type').val();
                var icon = iconSelect(flag_type);
                changeMarkerPosition(location_lat, location_lng, icon, 'bounce');

            });

            google.maps.event.addListener(action_marker, 'dragend', function(event) {

                $("#location_to_lat").val(event.latLng.lat());
                $("#location_to_lng").val(event.latLng.lng());
                //$("#trip_filters").submit();
                //map.setZoom(10);
                //map.setCenter(marker.getPosition());
                action_marker.setAnimation(google.maps.Animation.BOUNCE);


                // const input = document.getElementById("latlng").value;
                // const latlngStr = input.split(",", 2);
                const latlngd = {
                    lat: parseFloat(event.latLng.lat()),
                    lng: parseFloat(event.latLng.lng()),
                };

                golocation(latlngd);

            });

            google.maps.event.addListener(map_filters, 'click', function(event) {

                var flag_type = $('#plan_type').val();

                if (flag_type == "") {

                    swal({
                        title: "Please select activity type",
                        type: "warning",
                        //showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok",
                        closeOnConfirm: true
                    });

                } else {

                    var icon = iconSelect(flag_type);
                    var latitude = event.latLng.lat();
                    var longitude = event.latLng.lng();

                    $("#location_to_lat").val(event.latLng.lat());
                    $("#location_to_lng").val(event.latLng.lng());

                    const latlng = {
                        lat: parseFloat(event.latLng.lat()),
                        lng: parseFloat(event.latLng.lng()),
                    };

                    golocation(latlng);
                    changeMarkerPosition(latitude, longitude, icon, 'bounce');

                }

            });

            // Add multiple markers to map
            // infoWindow = new google.maps.InfoWindow(),
            //     populated_marker, i;


            // google.maps.event.addListener(map_filters, 'click', function(event) {
            //     var latitude = event.latLng.lat();
            //     var longitude = event.latLng.lng();
            //     $("#lat_click").val(event.latLng.lat());
            //     $("#lng_click").val(event.latLng.lng());
            //     $("#trip_filters").submit();
            // });

            if (itinerary_type_mode == "event") {
                var labelProp = null;
            } else {
                var labelProp = {
                    text: '<?= $filter_end_marker; ?>',
                    color: "#ffffff",
                };
            }

            <?php
            if ($trip->trip_transport == 'plane') {
                $lat_from_plane = $lat_from;
                $lng_from_plane = $lng_from;
                $lat_to_plane = $lat_to;
                $lng_to_plane = $lng_to;
            ?>
                bounds2.extend(new google.maps.LatLng(<?= $lat_from; ?>, <?= $lng_from; ?>));
                bounds2.extend(new google.maps.LatLng(<?= $lat_to; ?>, <?= $lng_to; ?>));
                new DrawPlaneRoutes(map, <?= $lat_from_plane; ?>, <?= $lng_from_plane; ?>, <?= $lat_to_plane; ?>, <?= $lng_to_plane; ?>, <?= $location_multi_waypoint_latlng; ?>, 'flight');
            <?php } ?>

            <?php
            if ($trip->trip_location_to_flightportion) {
                $lat_fromd = $lat_from_flightportion;
                $lng_fromd = $lng_from_flightportion;
                $lat_tod = $lat_to_flightportion;
                $lng_tod = $lng_to_flightportion;

            ?>
                bounds2.extend(new google.maps.LatLng(<?= $lat_to; ?>, <?= $lng_to; ?>));
                new DrawPlaneRoutes(map, <?= $lat_fromd; ?>, <?= $lng_fromd; ?>, <?= $lat_tod; ?>, <?= $lng_tod; ?>, <?= $location_multi_waypoint_latlng; ?>, 'portion');
            <?php }

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
                bounds2.extend(new google.maps.LatLng(<?= $lat_from; ?>, <?= $lng_from; ?>));
                bounds2.extend(new google.maps.LatLng(<?= $lat_to; ?>, <?= $lng_to; ?>));
                new AutocompleteDirectionsHandler(map, 'driving', <?= $lat_from; ?>, <?= $lng_from; ?>, <?= $lat_to; ?>, <?= $lng_to; ?>, <?= $location_multi_waypoint_latlng; ?>, <?= $trip_via_waypoints ?>, false);
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
                bounds2.extend(new google.maps.LatLng(<?= $lat_tod; ?>, <?= $lng_tod; ?>));
                new AutocompleteDirectionsHandler(map, 'driving', <?= $lat_fromd; ?>, <?= $lng_fromd; ?>, <?= $lat_tod; ?>, <?= $lng_tod; ?>, [], [], true, "<?= $marker_set; ?>", "<?PHP echo $driving_start_indicate; ?>");

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
                bounds2.extend(new google.maps.LatLng(<?= $lat_from; ?>, <?= $lng_from; ?>));
                bounds2.extend(new google.maps.LatLng(<?= $lat_to; ?>, <?= $lng_to; ?>));
                new AutocompleteDirectionsHandler(map, 'train', <?= $lat_from; ?>, <?= $lng_from; ?>, <?= $lat_to; ?>, <?= $lng_to; ?>, <?= $location_multi_waypoint_latlng; ?>, <?= $trip_via_waypoints ?>, false);
            <?php } ?>
            <?php
            if ($trip->trip_location_to_trainportion) {
                $train_location_from = 'trip_location_from_trainportion';
                $train_location_to = 'trip_location_to_trainportion';
                $tmp = str_replace('(', '', $trip->trip_location_from_latlng_trainportion); // Ex: (25.7616798, -80.19179020000001)
                $tmp = str_replace(')', '', $tmp);
                $tmp = explode(',', $tmp);
                $lat_from = $tmp[0];
                $lng_from = $tmp[1];
                $tmp = str_replace('(', '', $trip->trip_location_to_latlng_trainportion); // Ex: (25.7616798, -80.19179020000001)
                $tmp = str_replace(')', '', $tmp);
                $tmp = explode(',', $tmp);
                $lat_to = $tmp[0];
                $lng_to = $tmp[1];
            ?>
                bounds2.extend(new google.maps.LatLng(<?= $lat_to; ?>, <?= $lng_to; ?>));
                new AutocompleteDirectionsHandler(map, 'train', <?= $lat_from; ?>, <?= $lng_from; ?>, <?= $lat_to; ?>, <?= $lng_to; ?>, <?= $location_multi_waypoint_latlng; ?>, <?= $trip_via_waypoints ?>, true, "<?= $end_marker; ?>");

            <?php } ?>

            map.fitBounds(bounds2);
        }


        /************ Start Plan Custom Function ******************/

        function offsetCenter(latlng, offsetx, offsety) {

            // latlng is the apparent centre-point
            // offsetx is the distance you want that point to move to the right, in pixels
            // offsety is the distance you want that point to move upwards, in pixels
            // offset can be negative

            var scale = Math.pow(3, map_filters.getZoom());
            var nw = new google.maps.LatLng(
                map.getBounds().getNorthEast().lat(),
                map.getBounds().getSouthWest().lng()
            );

            var worldCoordinateCenter = map_filters.getProjection().fromLatLngToPoint(latlng);
            var pixelOffset = new google.maps.Point((offsetx / scale) || 0, (offsety / scale) || 0)

            var worldCoordinateNewCenter = new google.maps.Point(
                worldCoordinateCenter.x - pixelOffset.x,
                worldCoordinateCenter.y + pixelOffset.y
            );

            var newCenter = map_filters.getProjection().fromPointToLatLng(worldCoordinateNewCenter);

            map_filters.setCenter(newCenter);
            //map.setZoom(map.getZoom() - 2);

        }

        function changeMarkerPosition(lat, lng, icon, animate) {

            let processIcon = createMapIcon(icon);

            var latlng = new google.maps.LatLng(lat, lng);
            action_marker.setVisible(true);
            action_marker.setPosition(latlng);
            action_marker.setIcon(processIcon);
            action_marker.setAnimation(animate == 'bounce' ? google.maps.Animation.BOUNCE : google.maps.Animation.DROP)
            map_filters.setZoom(12);

            offsetCenter(action_marker.getPosition(), -300, 400);
            //map.setCenter(action_marker.getPosition());
        }

        function createMapIcon(icon) {
            const customIcon = {
                url: icon_path + icon, // Set the path to the icon image
                scaledSize: new google.maps.Size(desiredWidth, desiredHeight),
            };

            return customIcon;
        }

        function golocation(latlng) {
            geocoder
                .geocode({
                    location: latlng
                })
                .then((response) => {
                    if (response.results[0]) {

                        document.querySelector('#plan_address').value = response.results[0].formatted_address || '';
                        //console.log('Draged-address', response.results[0]);
                        //console.log('Draged-address', response.results[0].formatted_address);

                    } else {
                        window.alert("No results found");
                    }
                })
                .catch((e) => window.alert("Geocoder failed due to: " + e))
        }

        setTimeout(function() {
            var icon = {
                url: 'https://planiversity.com/assets/images/Selected_B.png',
                size: new google.maps.Size(100, 100),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(51, 59)
            };

            destination_marker = new google.maps.Marker({
                position: new google.maps.LatLng(location_lat, location_lng),
                icon: icon,
                title: "Trip Destination",
                map: map_filters,
                draggable: false,
                animation: google.maps.Animation.DROP,
            });
            load_marker(1, 1);
        }, 3000);

        function load_marker(flag, marker_push) {

            for (var i = 0; i < markers_list.length; i++) {
                var data = markers_list[i]
                var icon_image = iconSelect(data.type);
                addMarkerProcess(data.id, data.lat, data.lng, icon_image, data.title, flag, marker_push);

                (function(populated_marker, data) {
                    google.maps.event.addListener(populated_marker, "click", function(e) {

                        console.log('data', data);

                        let contentData = contentProcess(data.title, data.type, data.address, data.plan_checked_in, data.plan_date, data.schedule_linked);
                        infoWindow.setContent(contentData);
                        infoWindow.open(map, populated_marker);
                    });
                    // google.maps.event.addListener(populated_marker, "dragend", function(e) {
                    //     var lat, lng, address;
                    //     geocoder.geocode({
                    //         'latLng': populated_marker.getPosition()
                    //     }, function(results, status) {
                    //         if (status == google.maps.GeocoderStatus.OK) {
                    //             lat = populated_marker.getPosition().lat();
                    //             lng = populated_marker.getPosition().lng();
                    //             address = results[0].formatted_address;
                    //             alert("Latitude: " + lat + "\nLongitude: " + lng + "\nAddress: " + address);
                    //         }
                    //     });
                    // });
                })(populated_marker, data);
                //bounds2.extend(populated_marker.position);
                //var bounds = new google.maps.LatLngBounds();
                // bounds2.extend(populated_marker.position);
                // map.fitBounds(bounds2);

                // var hold = {
                //     lat: data.lat,
                //     lng: data.lng,
                // }

                // bounds2.extend(hold);


            }

            //map.fitBounds(bounds2);

        }

        function contentProcess(title, type, address, checkin, plan_date, schedule_linked = 0) {
            let content = '<div id="info-content">';
            let schedule_content_linked = "";
            let check_in_content = ''
            let date_content = ''
            //content += '<div id="spinner-container"><div class="spinner"></div></div>';
            if (schedule_linked == 1) {
                schedule_content_linked = `<p class="schedule_content_linked"><i class="fa fa-calendar-check-o" aria-hidden="true" title="Schedule Linked"></i> Schedule Linked</p>`;
            }

            if (checkin == 1) {
                check_in_content = `<p class="map_icon">
                <span><i class="fa fa-check" aria-hidden="true" title="Date Linked"></i></span>
                Checked In
                </p>`
            }

            if (plan_date) {
                date_content = `<p class="map_icon">
                <span><i class="fa fa-calendar-o" aria-hidden="true" title="Date Linked"></i></span>
                ${moment(plan_date).format('LLL')}
                </p>`
            }
            content += `
                                <strong class="main_head">
                                <img src="${main_logo}" width="26px"><p>${type}</p> </strong>
                                <p class="main_body"> ${title} <span> <i class="fa fa-map-marker" aria-hidden="true"></i> ${address} </span></p>
                                <div>
                                ${date_content}
                                ${check_in_content}
                                </div>
                                ${schedule_content_linked}
                                `;
            content += '</div>';

            return content;
        }

        function addMarkerProcess(id, lat, lng, icon, title, flag, marker_push) {

            var myLatlng = new google.maps.LatLng(lat, lng);

            let processIcon = createMapIcon(icon);


            // var infoWindow = new google.maps.InfoWindow(),
            //     marker, i;
            // const infoWindowContent = '<div>This is the InfoWindow content</div>';

            // infoWindow = new google.maps.InfoWindow({
            //     content: infoWindowContent,
            // });

            infoWindow = new google.maps.InfoWindow(),
                populated_marker, id;

            // bounds2.extend(myLatlng);

            populated_marker = new google.maps.Marker({
                position: myLatlng,
                icon: processIcon,
                map: map_filters,
                title: title,
                draggable: false,
                animation: flag == 1 ? google.maps.Animation.DROP : null
            });



            if (marker_push == 1) {
                populated_marker.id = id;
                markers.push(populated_marker);
            }


            if (flag == 0) {
                populated_marker.addListener('click', function() {

                    const matchValue = markers_list.findIndex(
                        (item) => item.id == id
                    );

                    const itemData = markers_list[matchValue];

                    let contentData = contentProcess(itemData.title, itemData.type, itemData.address, itemData.plan_checked_in, itemData.plan_date, itemData.schedule_linked);

                    console.log('Action Marker Clicked', itemData, markers_list);
                    // Show the InfoWindow when the marker is clicked
                    infoWindow.setContent(contentData);
                    infoWindow.open(map_filters, populated_marker);
                });
            }

            // Add info window to marker    
            // google.maps.event.addListener(populated_marker, 'click', (function(marker, id) {
            //     return function() {
            //         infoWindow.setContent("Hola");
            //         infoWindow.open(map_filters, populated_marker);
            //     }
            // })(populated_marker, id));
        }

        function DeleteMarker(id) {
            //Find and remove the marker from the Array
            for (var i = 0; i < markers.length; i++) {
                if (markers[i].id == id) {
                    //Remove the marker from Map                  
                    markers[i].setMap(null);

                    //Remove the marker from array.
                    markers.splice(i, 1);
                    return;
                }
            }
        };

        function HideMarker(id) {
            //Find and remove the marker from the Array
            for (var i = 0; i < markers.length; i++) {
                if (markers[i].id == id) {
                    //Remove the marker from Map                  
                    markers[i].setVisible(false);
                    return;
                }
            }
        };

        /************ End Plan Custom Function ******************/

        /************ filters ******************/

        $(document).ready(function() {

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

            $("#checking").click(function() {
                $('#advanced_popup').modal('show');
            });

            $('input[name="option_date_time"]').click(function() {
                if ($(this).val() === 'yes' && $(this).is(':checked')) {
                    $('input[name="option_date_time"]').not(this).prop('checked', false);
                    $('#date-content').show();
                    $("#schedule_flag").val(1);
                } else {
                    $('input[name="option_date_time"]').not(this).prop('checked', false);
                    $('#date-content').hide();
                    $('#advance_check').val(0);
                    $("#schedule_flag").val(0);
                }
            });

            $('input[name="option_schedule"]').click(function() {
                if ($(this).val() === 'yes' && $(this).is(':checked')) {
                    $('input[name="option_schedule"]').not(this).prop('checked', false);
                    $('#schedule-content').show();
                    $("#schedule_flag").val(1);
                } else {
                    $('input[name="option_schedule"]').not(this).prop('checked', false);
                    $('#schedule-content').hide();
                    $('.event-field').val('');
                    $('#advance_check').val(0);
                    $("#schedule_flag").val(0);
                }
            });

            $('#plan_type').change(function() {

                var parent_value = $(this).val();

                if (parent_value == "") {
                    $("#plan_address").prop('disabled', true);
                    action_marker.setVisible(false);
                    $("#plan_address").val('');
                } else {
                    $("#plan_address").prop('disabled', false);

                }


            });


        });

        function toggle_visibility(id) {
            var e = document.getElementById(id);
            if (e.style.display == 'block')
                e.style.display = 'none';
            else
                e.style.display = 'block';
        }

        $('.flexible-container')
            .click(function() {
                $(this).find('iframe').addClass('clicked')
            })
            .mouseleave(function() {
                $(this).find('iframe').removeClass('clicked')
            });

        $(window).on('load', function() {
            $('#filter1-modal').modal('show');
        });



        $("#tgl-fascilities").click(function() {
            $("#expanded-facilities-modal").show();
            //$('#filter1-modal').modal('show');
        });
        $("#facility-cross").click(function() {
            $("#expanded-facilities-modal").hide();
            //$('#filter1-modal').modal('show');
        });
    </script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

    <script src="<?php echo SITE; ?>js/utils/modal-stepper.js?v=20230815"></script>
    <script src="<?php echo SITE; ?>js/trip_plan_next_update_plus.js?v=2230815aa"></script>

    <?php include('new_backend_footer.php'); ?>


<?php if ($trip->getRole($id_trip) != TripPlan::ROLE_COLLABORATOR) { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            function hideButtons() {
                if ($(".itinerary-field__button").length) {
                    $(".itinerary-field__button").hide();
                     clearInterval(checkInterval);
                }
            }
            var checkInterval = setInterval(hideButtons, 500);
        });
    </script>
<?php } ?>

</body>

</html>