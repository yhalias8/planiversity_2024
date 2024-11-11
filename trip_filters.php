<?php
include_once("config.ini.php");
include("class/class.TripPlan.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/resources/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}

$output = '';
$scale = 'Km';
$factor = 1000;

if ($userdata['scale'] == 'imperial') {
    $factor = 1609;
    $scale = 'Miles';
}

$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);

if (empty($id_trip)) {
    header("Location:" . SITE . "trip/how-are-you-traveling");
}

if (isset($_POST['filter_submit'])) {

    $filter = $_POST['filter_option'];
    $embassis = $_POST['embassy_list'];
    $directions_text = $_POST['directions_text'];
    // Edit data trip in DB
    if (!empty($_POST['radius']))
        $filter[50] = $_POST['lat_click'] . '::' . $_POST['lng_click'] . '::' . $_POST['radius'];
    else
        $filter[50] = '0';

    $trip->edit_data_filter($id_trip, $filter, $embassis, $directions_text);
    if (!$trip->error)
        header("Location:" . SITE . "trip/travel-documents/" . $id_trip);
    else
        $output = 'A system error has been encountered. Please try again.';
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

$radius = 1;
$zoom = 11;
$showclear = 0;


if ($trip->trip_option_weather || $trip->trip_option_hotels || $trip->trip_option_police || $trip->trip_option_university || $trip->trip_option_atm || $trip->trip_option_library || $trip->trip_option_pharmacy || $trip->trip_option_metro || $trip->trip_option_subway_station || $trip->trip_option_playground || $trip->trip_option_performing_arts_car_rental || $trip->trip_option_embassy || $trip->trip_option_museum || $trip->trip_option_church || $trip->trip_option_hospitals || $trip->trip_option_gas || $trip->trip_option_embassis || $trip->trip_option_taxi || $trip->trip_option_airfields || $trip->trip_option_directions || $trip->trip_option_busmap || $trip->trip_option_parking)
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

switch ($radius) {
    case ($radius >= 6):
        ($scale == 'Km') ? $zoom = 11 : $zoom = 11;
        break;
    case ($radius >= 5):
        ($scale == 'Km') ? $zoom = 11 : $zoom = 11;
        break;
    case ($radius >= 4):
        ($scale == 'Km') ? $zoom = 12 : $zoom = 11;
        break;
    case ($radius >= 3):
        ($scale == 'Km') ? $zoom = 12 : $zoom = 12;
        break;
    case ($radius >= 2):
        ($scale == 'Km') ? $zoom = 13 : $zoom = 12;
        break;
    case ($radius >= 1):
        ($scale == 'Km') ? $zoom = 14 : $zoom = 13;
        break;
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

if (isset($_POST['filter_clear'])) {
    $filter[50] = '0';
    $trip->edit_data_filter($id_trip, $filter, $embassis);
    if (!$trip->error)
        header("Location:" . SITE . "trip/resources/" . $id_trip);
}

$dtd = $dbh->prepare("SELECT id,title,address,lat,lng,type from tripit_resources WHERE trip_id=?");
$dtd->bindValue(1, $trip->trip_id, PDO::PARAM_INT);
$tmp = $dtd->execute();
$resources = [];
if ($tmp && $dtd->rowCount() > 0) {
    $resources = $dtd->fetchAll(PDO::FETCH_OBJ);
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
    <title>PLANIVERSITY - RESOURCES</title>

    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?= SITE; ?>style/style.css?v=20230627" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>assets/css/app-style.css?v=20230621" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>assets/css/dev-style.css?v=20222" rel="stylesheet" type="text/css" />

    <script src="<?= SITE; ?>js/jquery-1.11.3.js"></script>

    <script>
        var SITE = '<?php echo SITE; ?>';
        var itinerary_type_mode = "<?= $trip->itinerary_type; ?>";
    </script>
    <script src="<?= SITE; ?>js/trip_timeline.js"></script>
    <script src="<?= SITE; ?>js/js_map.js"></script>
    <script src="<?= SITE; ?>js/global.js?v=203040"></script>

    <link href="<?= SITE; ?>js/node_modules/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
    <script src="<?= SITE; ?>js/popup/jquery.js"></script>
    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link href="<?= SITE; ?>js/popup/jquery.css" rel="stylesheet" type="text/css" />

    <?php include('new_head_files.php') ?>

    <style>
        .pac-container {
            z-index: 1900;
        }

        .btn-resource-add {
            margin: 12px auto 0;
            background: linear-gradient(180deg, #faca5c 0, #f4a333 100%);
            border-radius: 6px;
            border: none;
            width: 120px;
            color: #000;
            font-size: 15px;
        }

        .btn-resource-add_sm {
            font-size: 10px;
            width: 60px;
            margin: 12px 0 0;
        }


        #resource-popup {
            z-index: 9999;
        }

        .resource-popup__steps {
            margin-top: 64px;
        }

        .modal-btn-next {
            display: block;
            margin: 12px 0 0 auto;
        }

        .modal-step-error {
            margin: 20px 0;
        }

        select.modal-step-invalid {
            border: 2px solid red;
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

        .tab-action {
            display: flex;
            justify-content: space-around;
            list-style: none;
            margin: 0;
        }


        .tab-action li {
            width: 100%;
            background: #068bee;
            cursor: pointer;
            color: #ffffff !important;
            box-shadow: 0 1px 1px 0 rgb(0 0 0 / 20%);
        }

        .tab-action.process .active {
            background-color: transparent;
            color: #048df4 !important;
        }

        .tab-action li.rounded-plus-left {
            border-radius: 0.85rem;
        }

        .tab-action.process li.rounded-plus-left {
            border-radius: 0.85rem 0 0 0.85rem;
        }

        .rounded-plus-right {
            border-radius: 0 0.85rem 0.85rem 0;
        }

        .tab-details>div {
            display: none;
        }

        .tab-details>div.active {
            display: block;
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
            padding: 12px;
            border-radius: 8px;
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
            cursor: pointer;
            color: #fff;
        }

        .map_help p {
            background: #058aee;
            padding: 6px;
            border-radius: 5px;
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

        .invalid-resource {
            border: 1px solid red;
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

        ul.tab-action.process {
            list-style: none;
            margin: 0 auto !important;
            padding: 0;
            width: 98%;
        }

        ul.tab-action.process {
            padding-left: 20px;
            z-index: 5;
        }

        ul.tab-action.process li {
            color: grey;
            background: transparent;
            padding: 14px 24px 10px;
            margin: 0px -6px 0 10px;
            position: relative;
            float: left;
            text-align: center;
            z-index: 1;
        }

        ul.tab-action.process li::before {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 70%;
            height: 100%;
            border-style: solid;
            border-color: #eee;
            border-width: 2px 0 2px 2px;
            border-radius: 8px 0 0 0;
            -webkit-transform: skewX(-20deg);
            -moz-transform: skewX(-20deg);
            -o-transform: skewX(-20deg);
            transform: skewX(-20deg);
            background-color: #058cf2;
            z-index: -1;
        }

        ul.tab-action.process li::after {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            right: 0;
            width: 70%;
            height: 100%;
            border-style: solid;
            border-color: #eee;
            border-width: 2px 2px 2px 0;
            border-radius: 0 8px 0 0;
            -webkit-transform: skewX(20deg);
            -moz-transform: skewX(20deg);
            -o-transform: skewX(20deg);
            transform: skewX(20deg);
            background-color: #058cf2;
            z-index: -1;
        }

        ul.tab-action.process li.active {
            color: orange;
            z-index: 10;
        }

        ul.tab-action.process li.active::before,
        ul.tab-action.process li.active::after {
            background-color: #fff;
            border-bottom-color: #fff;
        }


        /* Weather app */

        .wh-info {
            color: #1C2331 !important;
        }

        .wh-top h6 {
            color: #9a9b9f;
        }

        .wh-short {
            font-size: 12px;
        }

        .filter-bottom-wrapper {
            position: absolute;
            margin: 0;
            margin-top: 7px;
            left: 32px;
        }

        .custom-info-window {
            min-width: 200px;
            /* Set the minimum width value you desire */
        }

        p.main_body {
            margin: 0;
            margin-top: 8px;
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
            overflow-x: hidden;
            min-width: 200px;
            min-height: 80px;
            max-width: 300px;
        }

        div#spinner-container {
            padding-top: 20px;
        }

        div#info-content p {
            margin: 0;
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

        .gm-style-iw-chr {
            position: absolute;
            display: flex;
            top: 0;
            right: 0;
        }

        .gm-style-iw.gm-style-iw-c {
            padding-top: 35px !important;
        }

        .photo-container {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            padding: 10px;
            /* border: 1px solid #ddd; */
        }

        .photo-container ul {
            display: flex;
            flex-wrap: nowrap;
            margin: 0px !important;
            margin-left: -20px !important;
            padding: 0;
        }

        .photo-container li {
            margin: 10px;
            display: inline-block;
        }

        .photo-container img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .modal-backdrop {
            display: none;
        }

        #enlarged-image {
            width: 350px; /* contoh ukuran lebar */
            height: 350px; /* contoh ukuran tinggi */
            margin: 0 auto;
            display: block;
        }
    </style>
</head>

<body class="custom_filters">
    <div class="fullscreen-background"></div>
    <!--<div class="content"> -->
    <?php include('new_backend_header.php') ?>

    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <?php
            $step_index = "resources";
            include('dashboard/include/itinerary-step.php');
            ?>
        </div>
    </div>
    </header>

    <div data-backdrop="false" id="filter1-modal" class="modal fade master_modal connect-bg custom_prefix_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none">
        <div class="modal-dialog filter-modal-lg modal-custom-dialog mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3" style="background-color: rgba(245, 250, 253, 0.83);">
            <div class="modal-content connect-bg">
                <div class="modal-header rounded-0">
                    <div class="heading_section">
                        <p>PLANVERSITY</p>
                        <h4 class="modal-title" id="myLargeModalLabel">Resources</h4>
                    </div>
                </div>
                <div class="modal-body background-transparent px-4" id="filter1-modal-body">
                    <form id="trip_filters" name="timeline_form" method="post" class="routemap">
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12 col-lg-4">
                                    <?PHP
                                    $destination = $lat_to . ',' . $lng_to;
                                    $destination_weather = $lat_to2 . ',' . $lng_to2;
                                    $key = 'AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y';

                                    $xml = TripPlan::getXmlFromUrl("https://maps.googleapis.com/maps/api/geocode/xml?latlng=" . $destination_weather . "&sensor=false&key=" . $key);

                                    if ($xml->status == 'OK') {
                                        foreach ($xml->result[1]->address_component as $value) {
                                            if ($value->type == 'locality') {
                                                $locality_long_name = trim($value->long_name);
                                                $locality_short_name = $value->short_name;
                                            } elseif ($value->type == 'administrative_area_level_2') {
                                                $locality_long_name = trim($value->long_name);
                                                $locality_short_name = $value->short_name;
                                            }

                                            if ($value->type == 'political') {
                                                $political_long_name = trim($value->long_name);
                                                $political_short_name = $value->short_name;
                                            }
                                            if ($value->type == 'country') {
                                                $country_long_name = trim($value->long_name);
                                                $country_short_name = $value->short_name;
                                            }
                                        }
                                    }

                                    $dir = "busmap/";
                                    $indir = 0;
                                    if (is_dir($dir)) {
                                        if ($dh = opendir($dir)) {
                                            while (($file = readdir($dh)) !== false) {
                                                if (filetype($dir . $file) == 'file') {
                                                    $filename = substr($file, 0, -4);
                                                    $filename = str_replace('-', ' ', $filename);
                                                    $filename = str_replace(',', ' ', $filename);
                                                    if (!empty($political_long_name)) {
                                                        if (stristr($filename, $political_long_name)) {
                                                            $busmapimg = '<img src="' . SITE . $dir . $file . '" width="100%" />';
                                                            $indir = 1;
                                                            break;
                                                        }
                                                    } else {
                                                        if (stristr($filename, $locality_long_name)) {
                                                            $busmapimg = '<img src="' . SITE . $dir . $file . '" width="100%" />';
                                                            $indir = 1;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }

                                            closedir($dh);
                                        }
                                    }

                                    if (!$indir)
                                        $busmapimg = '<img src="' . SITE . $dir . 'noimage.png"/>';
                                    ?>

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

                                    <?php include('include_filter.php');
                                    ?>
                                </div>

                                <div class="col-md-12 col-lg-8">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-8">


                                            <!--Facility Radius<br> -->
                                            <ul class="rounded p-0 mb-3 tab-action">
                                                <li class="modal-sub-title text-white rounded-plus-left tab-1 active" onclick="tabProcess.call(this,event,'tab-1')">
                                                    <img class="modal-sub-title-img active_image" src="<?= SITE; ?>images/flag_outline.png"></img>
                                                    <img class="modal-sub-title-img normal_image" src="<?= SITE; ?>images/flag_color.png"></img>
                                                    Facility Radius
                                                </li>
                                                <li class="modal-sub-title text-white rounded-plus-right tab-2" id="direction_section" onclick="tabProcess.call(this,event,'tab-2')" style="display: none;">
                                                    <img class="modal-sub-title-img active_image" src="<?= SITE; ?>images/directions_white.png"></img>
                                                    <img class="modal-sub-title-img normal_image" src="<?= SITE; ?>images/directions_color.png"></img>
                                                    Directions
                                                </li>
                                            </ul>

                                            <div class="tab-details">
                                                <div id="tab-1" class="active">

                                                    <div class="flexible-container rounded-bottom border-grey" id="filter_map"></div>


                                                    <!--<label for="radius">Scale:<input name="radius" id="radius" class="inp1" value="<?= $radius; ?>" type="text"><?= $scale; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add a number value, then click the map to set location.</label>-->
                                                    <div class="filter-bottom-wrapper rounded p-2 border-grey background-white">
                                                        <div class="flex-div">
                                                            <p>Scale:</p>
                                                        </div>
                                                        <div class="flex-div">
                                                            <input name="radius" id="radius" value="<?= $radius; ?>" type="text" class="map-radius-value-input"></input>
                                                        </div>
                                                        <div class="flex-div">
                                                            <p><?= $scale; ?></p>
                                                        </div>
                                                        <div class="flex-div">
                                                            <input type="range" class="range-slider" min="0" max="20" value="<?= $radius; ?>" step="1" style="box-shadow: none; padding: 5px 0px 5px 0px; width: 330px">
                                                        </div>
                                                        <div class="flex-div">
                                                            <!-- <p>Add a number value, then click the map to set location.</p> -->
                                                        </div>

                                                    </div>

                                                </div>
                                                <div id="tab-2">

                                                    <div class="flexible-container rounded-bottom border-grey" id="directions_map"></div>
                                                    <div style="direction: ltr;" id="right-panel"></div>

                                                </div>
                                            </div>



                                            <div class="border-grey rounded background-white">

                                                <div class="d-flex pb-3 px-2 filter_button justify-content-between align-items-end">
                                                    <div>
                                                        <div class="map_help" data-toggle="modal" data-target="#video_popup">
                                                            <p>How to use map</p>
                                                        </div>

                                                        <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>" class="skip-note-btn mb mobile_skip">Back</a>

                                                    </div>

                                                    <div>
                                                        <!-- <a href="<?= SITE; ?>trip/travel-documents/<?= $_GET['idtrip']; ?>" class="skip-note-btn mobile_skip">Skip This Section</a> -->

                                                        <?php if ($showclear) { ?>
                                                            <input name="filter_clear" id="filter_clear" type="submit" class="refresh-btn text-dark mr-2" value="Clear Filter">
                                                        <?php } ?>

                                                        <input name="filter_submit" id="filter_submit" type="submit" class="refresh-btn mt-3 mt-xs-0 mt-sm-0" value="Finished, Next Step">

                                                    </div>

                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-md-12 col-lg-4">
                                            <div class="background-main rounded h-100 rounded-plus">
                                                <h6 class="modal-sub-title text-white"><img class="modal-sub-title-img" src="<?php echo SITE; ?>/images/map.png"></img> Facilities </h6>
                                                <div class="direction-info-wrapper rounded border-grey filter-modal-left-content p-0 filter-content rounded-plus">

                                                    <div class="facility_section checkbox_section">

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_hotels" type="checkbox" <?php if ($trip && $trip->trip_option_hotels) echo 'checked="checked"'; ?> value="hotels" onchange="NearbyPlacesHandler('lodging',this.checked,'Hotel')">
                                                                    <label for="filter_hotels" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/lodging.png">
                                                                        </span>
                                                                        <p>Hotels/Motels</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_police" type="checkbox" <?php if ($trip && $trip->trip_option_police) echo 'checked="checked"'; ?> value="police" onchange="NearbyPlacesHandler('police',this.checked,'Police Station')">
                                                                    <label for="filter_police" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/police.png">
                                                                        </span>
                                                                        <p>Police Stations</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_hospitals" type="checkbox" <?php if ($trip && $trip->trip_option_hospitals) echo 'checked="checked"'; ?> value="hospitals" onchange="NearbyPlacesHandler('hospital',this.checked,'Hospital')">
                                                                    <label for="filter_hospitals" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/hospital.png">
                                                                        </span>
                                                                        <p>Hospitals</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_airport" type="checkbox" <?php if ($trip && $trip->trip_option_airfields) echo 'checked="checked"'; ?> value="airports" onchange="NearbyPlacesHandler('airport',this.checked,'Airport')">
                                                                    <label for="filter_airport" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/airport.png">
                                                                        </span>
                                                                        <p>Airports/Heliports</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_parking" type="checkbox" <?php if ($trip && $trip->trip_option_parking) echo 'checked="checked"'; ?> value="parking" onchange="NearbyPlacesHandler('parking',this.checked,'Parking')">
                                                                    <label for="filter_parking" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/parking.png">
                                                                        </span>
                                                                        <p>Parking</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_subway_station" type="checkbox" <?php if ($trip && $trip->trip_option_subway_station) echo 'checked="checked"'; ?> value="subway_station" onchange="NearbyPlacesHandler('subway_station',this.checked,'Subway Station')">
                                                                    <label for="filter_subway_station" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/train_station.png">
                                                                        </span>
                                                                        <p>Subway Stations</p>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_gas" type="checkbox" <?php if ($trip && $trip->trip_option_gas) echo 'checked="checked"'; ?> value="gas" onchange="NearbyPlacesHandler('gas_station',this.checked,'Gas Station')">
                                                                    <label for="filter_gas" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/gas_station.png">
                                                                        </span>
                                                                        <p>Service Station </p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_taxi" type="checkbox" <?php if ($trip && $trip->trip_option_taxi) echo 'checked="checked"'; ?> value="taxi" onchange="NearbyPlacesHandler('taxi_stand',this.checked,'Texi Stand')">
                                                                    <label for="filter_taxi" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/taxi_stand.png">
                                                                        </span>
                                                                        <p>Taxi Services</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_university" type="checkbox" <?php if ($trip && $trip->trip_option_university) echo 'checked="checked"'; ?> value="university" onchange="NearbyPlacesHandler('university',this.checked,'University')">
                                                                    <label for="filter_university" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/university.png">
                                                                        </span>
                                                                        <p>Universities</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_atm" type="checkbox" <?php if ($trip && $trip->trip_option_atm) echo 'checked="checked"'; ?> value="atm" onchange="NearbyPlacesHandler('atm',this.checked,'Atm')">
                                                                    <label for="filter_atm" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/atm.png">
                                                                        </span>
                                                                        <p>ATM</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_library" type="checkbox" <?php if ($trip && $trip->trip_option_library) echo 'checked="checked"'; ?> value="library" onchange="NearbyPlacesHandler('library',this.checked,'Library')">
                                                                    <label for="filter_library" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/library.png">
                                                                        </span>
                                                                        <p>Libraries</p>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_museum" type="checkbox" <?php if ($trip && $trip->trip_option_museum) echo 'checked="checked"'; ?> value="museum" onchange="NearbyPlacesHandler('museum',this.checked,'Museum')">
                                                                    <label for="filter_museum" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/museum.png">
                                                                        </span>
                                                                        <p>Museums</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_church" type="checkbox" <?php if ($trip && $trip->trip_option_church) echo 'checked="checked"'; ?> value="church" onchange="NearbyPlacesHandler('church',this.checked,'Church')">
                                                                    <label for="filter_church" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/church.png">
                                                                        </span>
                                                                        <p>Religious Institutions</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_metro" type="checkbox" <?php if ($trip && $trip->trip_option_metro) echo 'checked="checked"'; ?> value="metro" onchange="NearbyPlacesHandler('train_station',this.checked,'Train Station')">
                                                                    <label for="filter_metro" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/train_station.png">
                                                                        </span>
                                                                        <p>Metro Stations</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_playground" type="checkbox" <?php if ($trip && $trip->trip_option_playground) echo 'checked="checked"'; ?> value="playground" onchange="NearbyPlacesHandler('park',this.checked,'Park')">
                                                                    <label for="filter_playground" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/park.png">
                                                                        </span>
                                                                        <p>Parks</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_pharmacy" type="checkbox" <?php if ($trip && $trip->trip_option_pharmacy) echo 'checked="checked"'; ?> value="pharmacy" onchange="NearbyPlacesHandler('pharmacy',this.checked,'Pharmacy')">
                                                                    <label for="filter_pharmacy" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/hospital.png">
                                                                        </span>
                                                                        <p>Pharmacy</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_covid" type="checkbox" <?php if ($trip && $trip->trip_option_covid) echo 'checked="checked"'; ?> value="covid" onchange="NearbyPlacesHandler('covid_testing_center',this.checked,'Covid Testing Center')">
                                                                    <label for="filter_covid" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/covid.png">
                                                                        </span>
                                                                        <p>Covid Testing</p>
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_electric_car" type="checkbox" <?php if ($trip && $trip->trip_option_electric_car) echo 'checked="checked"'; ?> value="electric_car" onchange="NearbyPlacesHandler('ev_charging_station',this.checked,'Electric Car Charging Station')">
                                                                    <label for="filter_electric_car" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/gas_station.png">
                                                                        </span>
                                                                        <p>Electric Car Charging</p>
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_shopping" type="checkbox" <?php if ($trip && $trip->trip_option_shopping_mall) echo 'checked="checked"'; ?> value="shopping_mall" onchange="NearbyPlacesHandler('shopping_mall',this.checked,'Shopping Mall')">
                                                                    <label for="filter_shopping" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/shopping_mall.png">
                                                                        </span>
                                                                        <p>Shopping</p>
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_golf_course" type="checkbox" <?php if ($trip && $trip->trip_option_golf_course) echo 'checked="checked"'; ?> value="golf_course" onchange="NearbyPlacesHandler('golf_course',this.checked,'Golf Course')">
                                                                    <label for="filter_golf_course" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/golf_course.png">
                                                                        </span>
                                                                        <p>Golf Course</p>
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_restaurant" type="checkbox" <?php if ($trip && $trip->trip_option_restaurant) echo 'checked="checked"'; ?> value="restaurant" onchange="NearbyPlacesHandler('restaurant',this.checked,'Restaurant')">
                                                                    <label for="filter_restaurant" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/restaurant.png">
                                                                        </span>
                                                                        <p>Restaurant</p>
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_cafe" type="checkbox" <?php if ($trip && $trip->trip_option_cafe) echo 'checked="checked"'; ?> value="cafe" onchange="NearbyPlacesHandler('cafe',this.checked,'Cafe')">
                                                                    <label for="filter_cafe" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/cafe.png">
                                                                        </span>
                                                                        <p>Cafe</p>
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_historical" type="checkbox" <?php if ($trip && $trip->trip_option_historical) echo 'checked="checked"'; ?> value="historical site" onchange="NearbyPlacesHandler('historical site',this.checked,'Historical Site')">
                                                                    <label for="filter_historical" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/historical.png">
                                                                        </span>
                                                                        <p>Historical Sites</p>
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_gym" type="checkbox" <?php if ($trip && $trip->trip_option_gym) echo 'checked="checked"'; ?> value="gym" onchange="NearbyPlacesHandler('gym',this.checked,'Gym')">
                                                                    <label for="filter_gym" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/gym.png">
                                                                        </span>
                                                                        <p>Gyms</p>
                                                                    </label>
                                                                </div>

                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_embassy" type="checkbox" <?php if ($trip && $trip->trip_option_embassy) echo 'checked="checked"'; ?> value="embassy" onchange="NearbyPlacesHandler('embassy',this.checked,'Embassy')">
                                                                    <label for="filter_embassy" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/embassy.png">
                                                                        </span>
                                                                        <p>Embassy</p>
                                                                    </label>
                                                                </div>
                                                                
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_car_rental" type="checkbox" <?php if ($trip && $trip->trip_option_car_rental) echo 'checked="checked"'; ?> value="car_rental" onchange="NearbyPlacesHandler('car_rental',this.checked,'Car Rental')">
                                                                    <label for="filter_car_rental" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/car_rental.png">
                                                                        </span>
                                                                        <p>Car Rental</p>
                                                                    </label>
                                                                </div>
                                                                
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_movie_theater" type="checkbox" <?php if ($trip && $trip->trip_option_movie_theater) echo 'checked="checked"'; ?> value="movie_theater" onchange="NearbyPlacesHandler('movie_theater',this.checked,'Cinema')">
                                                                    <label for="filter_movie_theater" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/movie_theater.png">
                                                                        </span>
                                                                        <p>Cinema</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_post_office" type="checkbox" <?php if ($trip && $trip->trip_option_post_office) echo 'checked="checked"'; ?> value="post_office" onchange="NearbyPlacesHandler('post_office',this.checked,'Post office')">
                                                                    <label for="filter_post_office" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/post_office.png">
                                                                        </span>
                                                                        <p>Post Office</p>
                                                                    </label>
                                                                </div>
                                                                <div class="checkbox checkbox-primary ml-4">
                                                                    <input name="filter_option[]" id="filter_bus_station" type="checkbox" <?php if ($trip && $trip->trip_option_bus_station) echo 'checked="checked"'; ?> value="bus_station" onchange="NearbyPlacesHandler('bus_station',this.checked,'Bus Station')">
                                                                    <label for="filter_bus_station" class="black-checkbox-label">
                                                                        <span class="map-icons">
                                                                            <img src="<?php echo SITE; ?>images/map-icons/bus_station.png">
                                                                        </span>
                                                                        <p>Bus Station</p>
                                                                    </label>
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
                        </fieldset>
                        <br clear="all" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="push"></div>
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
                            <source src="<?= SITE; ?>assets/video/resource_explainer_new.mp4" type="video/mp4">
                        </video>


                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="map" style="display:none"></div>
    <br clear="all" />


    <div id="info-content">
        <div id="spinner-container">
            <div class="spinner"></div>
        </div>
    </div>

    <div class="modal fade" id="resource-popup" tabindex="-1" role="dialog" aria-labelledby="Resource" aria-hidden="false" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-plan-lg" role="document">

            <form class="resource-popup__form" id="resource-form">
                <div class="modal-content">

                    <div class="modal-body connect-bg-ground text-center resource-popup__content">
                        <button type="button" class="close trip_close" data-dismiss="resource-popup" onclick="$('#resource-popup').modal('hide')">×</button>

                        <div class="resource-popup__steps">
                            <div class="resource-popup__step" data-step="1">

                                <h3>Add more information about point</h3>

                                <div style="display: none;" class="modal-step-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 16H12.01M12 8V12M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#FF0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span>Please, fill required fields</span>
                                </div>


                                <div class="resource-popup__body">
                                    <label for="resource_type">Select type of point</label>
                                    <select class="form-control mb-2 resource-depends modal-step-required" name="resource_type" id="resource_type">
                                        <option value="">Select a option</option>
                                        <option value="lodging">Hotel/Motel</option>
                                        <option value="police">Police</option>
                                        <option value="hospital">Hospital</option>
                                        <option value="airport">Airport</option>
                                        <option value="parking">Parking</option>
                                        <option value="subway_station">Subway Station</option>
                                        <option value="gas_station">Gas Station</option>
                                        <option value="taxi_stand">Taxi Stand</option>
                                        <option value="university">University</option>
                                        <option value="atm">Atm</option>
                                        <option value="library">Library</option>
                                        <option value="museum">Museum</option>
                                        <option value="church">Church</option>
                                        <option value="train_station">Metro Station</option>
                                        <option value="park">Park</option>
                                        <option value="pharmacy">Pharmacy</option>
                                        <option value="covid_testing_center">Covid Testing Center</option>
                                        <option value="ev_charging_station">Electric Car Charging</option>
                                        <option value="shopping_mall">Shopping Mall</option>
                                        <option value="golf_course">Golf Course</option>
                                        <option value="restaurant">Restaurant</option>
                                        <option value="cafe">Cafe</option>
                                        <option value="historical site">Historical Site</option>

                                        <option value="gym">Gym</option>
                                        <option value="embassy">Embassy</option>
                                        <option value="car_rental">Car Rental</option>
                                        <option value="movie_theater">Cinema</option>
                                        <option value="movie_theater">Post Office</option>
                                        <option value="movie_theater">Bus Station</option>


                                    </select>

                                    <label for="resource_title">Title of point</label>
                                    <input type="text" id="resource_title" name="resource_title" maxlength="30" class="dashboard-form-control resource-depends modal-step-required mb-2 form-control input-lg clearable" required placeholder="Give your point a name" />

                                    <input type="hidden" id="location_to_lat" name="resource_lat" value="">
                                    <input type="hidden" id="location_to_lng" name="resource_lng" value="">
                                    <input type="hidden" id="resource_custom" name="resource_custom" value="1">
                                    <input type="hidden" id="resource_id" name="resource_id" value="">
                                    <input type="hidden" id="resource_trip_id" name="resource_trip_id" value="<?= $trip->trip_id; ?>">
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="resource-popup__footer">
                        <button type="submit" class="btn btn-danger modal-btn-next">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="modal fade" id="image-modal" tabindex="-1" role="dialog" aria-labelledby="image-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="enlarged-image" src="" alt="Enlarged image">
            </div>
            </div>
        </div>
    </div>


    <?PHP
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
        var myLocation = null;
        var map = null;
        var geocoder = null;
        var map_filters = null;
        var infowindow = null;
        var bounds = null;
        var directionsService = null;
        var directionsDisplay = null;
        var action_marker = null;
        var autocomplete = null
        var icon_path = 'https://planiversity.com/assets/images/icon-pack/';
        let main_logo = "<?= SITE; ?>assets/images/new-logo-small.png";
        let custom_marker = false;
        const displayedMarkers = [];
        const TRIP_ID = <?= $trip->trip_id; ?>;
        const markers_list = <?= json_encode($resources) ?>;
        const METRIC = "<?= $scale ?>";


        const desiredWidth = 38; // Set the desired width here
        const desiredHeight = 38; // Set the desired height here

        $(document).ready(function() {
            // Get the search field and result container
            var $searchField = $('#embassy_search_field');
            var $resultContainer = $('#embassy_result');

            // Listen for keyup event on search field
            $searchField.on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();

                // Filter embassy list based on search term
                $resultContainer.find('a').each(function() {
                    var embassyName = $(this).text().toLowerCase();
                    if (embassyName.includes(searchTerm)) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            });
        });

        function enlargeImage(img) {
            // Set the source of the enlarged image
            document.getElementById('enlarged-image').src = img.src;

            // Show the modal
            $('#image-modal').modal('show');
        }

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

        $tmp_index = count(json_decode($trip->location_multi_waypoint_latlng) ?? []);

        if ($trip->trip_location_to_flightportion || $trip->trip_location_to_drivingportion || $trip->trip_location_to_trainportion) {
            $start_marker = $markerAlpaArr[$tmp_index + 1];
            $end_marker =  $markerAlpaArr[$tmp_index + 2];
            $filter_end_marker =  $markerAlpaArr[$tmp_index + 2];
        } else {
            $start_marker = $markerAlpaArr[$tmp_index];
            $end_marker =  $markerAlpaArr[$tmp_index + 1];
            $filter_end_marker =  $markerAlpaArr[$tmp_index + 1];
        }
        ?>
        var location_multi_waypoint = <?= $location_multi_waypoint_latlng; ?>;

        function calcDistanceBetween({
            lat1,
            lng1,
            lat2,
            lng2,
            unit
        }) {
            let position1 = new google.maps.LatLng(lat1, lng1);
            let position2 = new google.maps.LatLng(lat2, lng2);

            const distance = google.maps.geometry.spherical.computeDistanceBetween(position1, position2);
            let result;
          
            if (unit === 'Miles') {
                // Convert meters to miles
                result = (distance * 0.000621371192).toFixed(2) + ' miles';
            } else {
                // Default to metric (meters to kilometers)
                result = (distance / 1000.0).toFixed(2) + ' km';
            }

            return result;
        }

        function getResourceIcon(name) {
            switch (name) {
                case 'atm':
                    return 'atm.png';
                case 'church':
                    return 'church.png';
                case 'library':
                    return 'library.png';
                case 'covid_testing_center':
                    return 'covid.png';
                case 'ev_charging_station':
                    return 'gas_station.png';
                case 'gas_station':
                    return 'gas_station.png';
                case 'golf_course':
                    return 'golf_course.png';
                case 'historical site':
                    return 'historical.png';
                case 'cafe':
                    return 'cafe.png';
                case 'lodging':
                    return 'lodging.png';
                case 'police':
                    return 'police.png';
                case 'hospital':
                    return 'hospital.png';
                case 'airport':
                    return 'airport.png';
                case 'parking':
                    return 'parking.png';
                case 'subway_station':
                    return 'train_station.png';
                case 'taxi_stand':
                    return 'taxi_stand.png';
                case 'university':
                    return 'university.png';
                case 'museum':
                    return 'museum.png';
                case 'train_station':
                    return 'train_station.png';
                case 'park':
                    return 'park.png';
                case 'pharmacy':
                    return 'hospital.png';
                case 'shopping_mall':
                    return 'shopping_mall.png';
                case 'restaurant':
                    return 'restaurant.png';
                case 'gym':
                    return  'gym.png';
                case 'post_office':
                    return  'post_office.png';
                case 'bus_station':
                    return  'bus_station.png';
                case 'embassy':
                    return 'embassy.png';
                case 'car_rental':
                    return 'car_rental.png';
                case 'movie_theater':
                    return 'movie_theater.png';
                default:
                    return 'no_set.png';
            }
        }

        function changeMarkerPosition(lat, lng, icon, animate, sourcePath = '', needOffsetCenter = true) {
            if (!sourcePath) sourcePath = icon_path;
            let processIcon = createMapIcon(icon, sourcePath);

            var latlng = new google.maps.LatLng(lat, lng);
            action_marker.setVisible(true);
            action_marker.setAnimation(animate === 'drop' ? google.maps.Animation.DROP : google.maps.Animation.BOUNCE)
            action_marker.setPosition(latlng);
            action_marker.setIcon(processIcon);
            map_filters.setZoom(15);

            if (needOffsetCenter) {
                offsetCenter(action_marker.getPosition(), -300, 400);
            }
        }

        function deleteMarker(id) {
            //Find and remove the marker from the Array
            for (var i = 0; i < displayedMarkers.length; i++) {
                if (displayedMarkers[i].id == id) {
                    //Remove the marker from Map
                    displayedMarkers[i].setMap(null);

                    //Remove the marker from array.
                    displayedMarkers.splice(i, 1);
                    return;
                }
            }
        }

        function getPlaceFromGeocoder(marker) {
            return new Promise((resolve, reject) => {
                var latlng = marker.getPosition();
                var geocoder = new google.maps.Geocoder();

                geocoder.geocode({
                    'location': latlng
                }, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            resolve(results[0]);
                        } else {
                            reject(new Error('No results found'));
                        }
                    } else {
                        reject(new Error('Geocoder failed due to: ' + status));
                    }
                });
            });
        }

        function addMarkerProcess(id, lat, lng, icon, title) {
            var myLatlng = new google.maps.LatLng(lat, lng);
            const iconSourcePath = '<?= SITE; ?>images/map-icons/';

            let processIcon = createMapIcon(icon, iconSourcePath);

            infowindow = new google.maps.InfoWindow()


            var marker_pupulated = new google.maps.Marker({
                position: myLatlng,
                icon: processIcon,
                map: map_filters,
                title: title,
                draggable: false,
                animation: null
            });


            marker_pupulated.id = id;
            displayedMarkers.push(marker_pupulated);


            marker_pupulated.addListener('click', async function() {
                let service = new google.maps.places.PlacesService(map_filters);
                let place = null;

                const distance = calcDistanceBetween({
                    lat1: myLocation.lat,
                    lng1: myLocation.lng,
                    lat2: lat,
                    lng2: lng,
                    unit: METRIC
                });

                try {
                    place = await getPlaceFromGeocoder(marker_pupulated);
                } catch (e) {
                    console.log(e);
                }

                if (!place) return

                var content = '<div id="info-content">';
                content += '<div id="spinner-container"><div class="spinner"></div></div>';
                content += '</div>';

                infowindow.setContent(content);
                // infowindow.setHeaderContent(title);
                infowindow.open(map_filters, this);
                // infowindow.setHeaderDisabled(true)

                var infoContent = document.getElementById('info-content');
                var spinner = infoContent ? infoContent.querySelector('.spinner') : null;

                if (spinner) {
                    spinner.style.display = 'block';

                    service.getDetails({
                        placeId: place.place_id,
                        fields: ['name', 'rating', 'formatted_phone_number', 'opening_hours', 'opening_hours.weekday_text', 'website', 'photos']
                    }, function(placeDetails, status) {

                        spinner.style.display = 'none';

                        if (status === google.maps.places.PlacesServiceStatus.OK) {

                            let detailsContent = `
                                <strong class="main_head">
                                <img src="${main_logo}" width="26px"><p>${title}</p> </strong>
                                <p class="main_body"> ${place.name ?? ''} <span> ${place.vicinity ?? ''} </span></p>
                                `;
                            if (placeDetails.formatted_phone_number) {
                                detailsContent += '<p><b>Phone: </b> ' + placeDetails.formatted_phone_number + '</p>';
                            }

                            if (placeDetails.opening_hours) {
                                var openingHours = placeDetails.opening_hours;

                                if (openingHours.weekday_text) {
                                    detailsContent += '<p><b>Opening Hours:</b></p><ul>';
                                    for (var i = 0; i < openingHours.weekday_text.length; i++) {
                                        detailsContent += '<li>' + openingHours.weekday_text[i] + '</li>';
                                    }
                                    detailsContent += '</ul>';
                                }
                            } else {
                                detailsContent += '<p><b>Status: </b> Closed</p>';
                            }

                            if (placeDetails.website) {
                                detailsContent += '<p><b>Website: </b> <a href="' + placeDetails.website + '" target="_blank">' + placeDetails.website + '</a></p>';
                            }

                            if (distance) {
                                detailsContent += `<p><b>${distance} from your destination</b></p>`;
                            }

                            if (placeDetails.photos) {
                                // detailsContent += '<p><b>Photos:</b></p>';
                                detailsContent += '<div class="photo-container" style="width: 100%; overflow-x: auto; white-space: nowrap;margin-top:10px;">';
                                detailsContent += '<ul style="display: flex; flex-wrap: nowrap;">';
                                for (var i = 0; i < placeDetails.photos.length; i++) {
                                    detailsContent += '<li style="margin: 10px;"><img src="' + placeDetails.photos[i].getUrl() + '" width="100px" class="enlargeable" onclick="enlargeImage(this)"></li>';
                                }
                                detailsContent += '</ul>';
                                detailsContent += '</div>';
                            }


                            // Update the content of the info window with the details
                            document.getElementById('info-content').innerHTML = detailsContent;
                            const importNode = $('#info-content').find('.btn-import-resource');
                            if (importNode) {
                                setOnImportClick(importNode);
                            }
                        }

                    });

                }
            });
        }
        // Add a click event listener to the images
        // $('.enlargeable').on('click', function() {
        //     console.log('image clicked')
        //     // Get the source of the clicked image
        //     var src = $(this).attr('src');
            
        //     // Create a modal or lightbox to display the enlarged image
        //     var modal = '<div class="modal"><img src="' + src + '" style="width: 50%; height: auto;"></div>';
            
        //     // Add the modal to the page and show it
        //     $('body').append(modal);
        //     $('.modal').show();
            
        //     // Add a click event listener to the modal to close it when clicked
        //     $('.modal').on('click', function() {
        //         $(this).remove();
        //     });
        // });

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

        function createMapIcon(icon, sourcePath) {
            const customIcon = {
                url: sourcePath + icon, // Set the path to the icon image
                scaledSize: new google.maps.Size(desiredWidth, desiredHeight),
            };

            return customIcon;
        }

        function loadResourceMarkers() {
            if (!Array.isArray(markers_list) || markers_list.length < 1) {
                return;
            }

            for (let i = 0; i < markers_list.length; i++) {
                let data = markers_list[i]
                let icon_image = getResourceIcon(data.type);
                addMarkerProcess(data.id, data.lat, data.lng, icon_image, data.title);

                // (function(populated_marker, data) {
                //     google.maps.event.addListener(populated_marker, "click", function(e) {
                //
                //         console.log('data', data);
                //
                //         let contentData = contentProcess(data.title, data.type, data.address, data.plan_checked_in, data.plan_date, data.schedule_linked);
                //         infoWindow.setContent(contentData);
                //         infoWindow.open(map, populated_marker);
                //     });
                //     // google.maps.event.addListener(populated_marker, "dragend", function(e) {
                //     //     var lat, lng, address;
                //     //     geocoder.geocode({
                //     //         'latLng': populated_marker.getPosition()
                //     //     }, function(results, status) {
                //     //         if (status == google.maps.GeocoderStatus.OK) {
                //     //             lat = populated_marker.getPosition().lat();
                //     //             lng = populated_marker.getPosition().lng();
                //     //             address = results[0].formatted_address;
                //     //             alert("Latitude: " + lat + "\nLongitude: " + lng + "\nAddress: " + address);
                //     //         }
                //     //     });
                //     // });
                // })(populated_marker, data);
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

        setTimeout(() => {
            loadResourceMarkers();
        }, 500)

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

            map_filters = new google.maps.Map(document.getElementById('filter_map'), {
                mapTypeControl: false,
                center: {
                    lat: <?= $filter_lat_to; ?>,
                    lng: <?= $filter_lng_to; ?>
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: <?= $zoom; ?>
            });

            geocoder = new google.maps.Geocoder();

            action_marker = new google.maps.Marker({
                map: map_filters,
                draggable: true,
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

            map_filters.addListener('click', function(event) {
                const latlng = {
                    lat: parseFloat(event.latLng.lat()),
                    lng: parseFloat(event.latLng.lng()),
                };

                $('#location_to_lat').val(latlng.lat);
                $('#location_to_lng').val(latlng.lng);

                var icon = getResourceIcon($('#resource_type').val());
                const iconSourcePath = '<?= SITE; ?>images/map-icons/';

                changeMarkerPosition($('#location_to_lat').val(), $('#location_to_lng').val(), icon, 'bounce', iconSourcePath, false);
                golocation(latlng);
            });

            function golocation(latlng) {
                geocoder
                    .geocode({
                        location: latlng
                    })
                    .then((response) => {
                        if (response.results[0]) {

                            document.querySelector('#resource_address').value = response.results[0].formatted_address || '';
                            //console.log('Draged-address', response.results[0]);
                            //console.log('Draged-address', response.results[0].formatted_address);

                        } else {
                            window.alert("No results found");
                        }
                    })
                    .catch((e) => window.alert("Geocoder failed due to: " + e))
            }

            var addressInput = document.getElementById('resource_address');
            autocomplete = new google.maps.places.Autocomplete(addressInput);

            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();

                var location_lat = place.geometry['location'].lat();
                var location_lng = place.geometry['location'].lng();

                $('#location_to_lat').val(location_lat);
                $('#location_to_lng').val(location_lng);

                var flag_type = $('#plan_type').val();
                var icon = getResourceIcon($('#resource_type').val());
                const iconSourcePath = '<?= SITE; ?>images/map-icons/';

                changeMarkerPosition(location_lat, location_lng, icon, 'drop', iconSourcePath);
            });

            const resourceTypeSelect = $('#resource_type');

            $(resourceTypeSelect).on('change', event => {
                const val = $(event.currentTarget).val();

                // var place = autocomplete.getPlace();

                // var location_lat = place.geometry['location'].lat();
                // var location_lng = place.geometry['location'].lng();
                // $('#location_to_lat').val(location_lat);
                // $('#location_to_lng').val(location_lng);


                var icon = getResourceIcon(val);
                const iconSourcePath = '<?= SITE; ?>images/map-icons/';

                changeMarkerPosition($('#location_to_lat').val(), $('#location_to_lng').val(), icon, 'drop', iconSourcePath);
            })


            var bounds2 = new google.maps.LatLngBounds();

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

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(<?= $filter_lat_to; ?>, <?= $filter_lng_to ?>),
                icon: {
                    url: itinerary_type_mode == "event" ?
                        "https://planiversity.com/assets/images/Selected_B.png" : "https://planiversity.com/assets/images/Selected_A.png",
                    size: new google.maps.Size(100, 100),
                    anchor: new google.maps.Point(40, 40),
                },
                label: labelProp,
                map: map_filters,
                draggable: true,
                animation: google.maps.Animation.DROP,
            });

            google.maps.event.addListener(marker, 'dragend', function(event) {
                $("#lat_click").val(event.latLng.lat());
                $("#lng_click").val(event.latLng.lng());
                $("#trip_filters").submit();
            });

            <?php
            if ($radius) {
                $radius = $radius * $factor; ?>

                // Add circle overlay and bind to marker
                var circle = new google.maps.Circle({
                    map: map_filters,
                    center: {
                        lat: <?= $filter_lat_to; ?>,
                        lng: <?= $filter_lng_to; ?>
                    },
                    radius: <?= $radius; ?>, // 10 miles in metres
                    //fillColor: '#AA0000',
                    fillColor: "#FF0000",
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.4,
                    strokeWeight: 2,
                    fillOpacity: 0.20,
                    clickable: false
                });
            <?PHP } ?>
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
        <?php if ($trip->trip_transport == 'vehicle' || $trip->trip_location_to_drivingportion) { ?>

            function show_directions(trMode) {
                var directionsService = new google.maps.DirectionsService();
                var directionsDisplay = new google.maps.DirectionsRenderer();

                map_directions = new google.maps.Map(document.getElementById('directions_map'), {
                    mapTypeControl: false,
                    center: {
                        lat: <?= $lat_to; ?>,
                        lng: <?= $lng_to; ?>
                    },
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    zoom: <?= $zoom; ?>
                });

                var bounds3 = new google.maps.LatLngBounds();
                bounds3.extend(new google.maps.LatLng(<?= $lat_from; ?>, <?= $lng_from; ?>));
                bounds3.extend(new google.maps.LatLng(<?= $lat_to; ?>, <?= $lng_to; ?>));

                directionsDisplay.setMap(map_directions);
                map_directions.fitBounds(bounds3);
                directionsDisplay.setPanel(document.getElementById('right-panel'));
                calculateAndDisplayRoute(directionsService, directionsDisplay, map_directions, trMode);

            }
        <?PHP } ?>
        <?php if ($trip_has_train) { ?>

            function show_directions_train() {
                var directionsService = new google.maps.DirectionsService();
                var directionsDisplay = new google.maps.DirectionsRenderer();
                map_directions = new google.maps.Map(document.getElementById('directions_map'), {
                    mapTypeControl: false,
                    center: {
                        lat: <?= $lat_to; ?>,
                        lng: <?= $lng_to; ?>
                    },
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    zoom: <?= $zoom; ?>
                });

                var bounds3t = new google.maps.LatLngBounds();


                bounds3t.extend(new google.maps.LatLng(<?= $lat_from; ?>, <?= $lng_from; ?>));
                bounds3t.extend(new google.maps.LatLng(<?= $lat_to; ?>, <?= $lng_to; ?>));

                directionsDisplay.setMap(map_directions);
                map_directions.fitBounds(bounds3t);
                directionsDisplay.setPanel(document.getElementById('right-panel'));
                calculateAndDisplayRouteTrain(directionsService, directionsDisplay, map_directions);
            }
        <?PHP } ?>


        /************ filters ******************/

        $(document).ready(function() {
            <?php if ($trip->trip_option_weather) { ?>
                setTimeout(function() {
                    // getWeather_(1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_hotels) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('lodging', 1, "Hotel");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_police) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('police', 1, "Police Station");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_hospitals) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('hospital', 1, "Hospital");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_gas) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('gas_station', 1, "Gas Station");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_embassis) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('embassy', 1, "Embassy");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_taxi) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('taxi_stand', 1, "Texi Stand");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_airfields) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('airport', 1, "Airport");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_university) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('university', 1, "University");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_atm) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('atm', 1, "Atm");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_museum) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('museum', 1, "Museum");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_gym) { ?>
            setTimeout(function() {
                NearbyPlacesHandler('gym', 1, "Gym");
            }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_post_office) { ?>
            setTimeout(function() {
                NearbyPlacesHandler('post_office', 1, "Post Office");
            }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_bus_station) { ?>
            setTimeout(function() {
                NearbyPlacesHandler('bus_station', 1, "Bus Station");
            }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_movie_theater) { ?>
            setTimeout(function() {
                NearbyPlacesHandler('movie_theater', 1, "Cinema");
            }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_embassy) { ?>
            setTimeout(function() {
                NearbyPlacesHandler('embassy', 1, "Embassy");
            }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_car_rental) { ?>
            setTimeout(function() {
                NearbyPlacesHandler('car_rental', 1, "Car Rental");
            }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_church) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('church', 1, "Church");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_metro) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('train_station', 1, "Train Station");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_subway_station) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('subway_station', 1, "Subway Station");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_playground) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('park', 1, "Park");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_metro) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('library', 1, "Library");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_pharmacy) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('pharmacy', 1, "Pharmacy");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_covid) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('covid_testing_center', 1, "Covid Testing Center");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_electric_car) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('ev_charging_station', 1, "Electric Car Charging Station");
                }, 1000);
            <?php } ?>

            <?php if ($trip->trip_option_shopping_mall) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('shopping_mall', 1, "Shopping Mall");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_golf_course) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('golf_course', 1, "Golf Course");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_restaurant) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('restaurant', 1, "Restaurant");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_cafe) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('cafe', 1, "Cafe");
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_historical) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('historical site', 1, "Historical Site");
                }, 1000);
            <?php } ?>

            <?php if ($trip->trip_option_directions) { ?>
                setTimeout(function() {
                    $('.tab-action').addClass("process");
                    $('#direction_section').show();
                    tabProcess(event, 'tab-2');
                    $('#right-panel').show();
                    show_directions();
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_busmap) { ?>setTimeout(function() {
                //NearbyPlacesHandler('bus_station',1);
                $('#busmap_result').toggle('slow');

            }, 1000);
        <?php } ?>
        <?php if ($trip->trip_option_parking) { ?>setTimeout(function() {
            NearbyPlacesHandler('parking', 1);
        }, 1000);
        <?php } ?>

        $('#filter_directions').click(function() {
            if ($("#filter_directions").is(':checked')) {
                $("#filter_submit").attr("disabled", "disabled");
                $('.tab-action').addClass("process");
                $('#direction_section').show();
                tabProcess(event, 'tab-2');
                $('#right-panel').show();
                show_directions();
            } else {
                // unchecked
                $('#direction_section').hide();
                $('.tab-action').removeClass("process");
                tabProcess(event, 'tab-1');
                $('#right-panel').hide();
            }
        });

        $('#filter_directions_train').click(function() {
            if ($("#filter_directions_train").is(':checked')) { // checked
                $("#filter_submit").attr("disabled", "disabled");
                $('#direction_cont').show();
                $('#right-panel').show();
                show_directions_train('TRANSIT');
            } else {
                // unchecked
                $('#direction_cont').hide();
                $('#right-panel').hide();
            }

        });


        });


        <?php if (!$radius) $radius = $factor * 10; ?>
        var marker_hotels = [];
        var marker_police = [];
        var marker_hospital = [];
        var marker_gasstation = [];
        var marker_embassy = [];
        var marker_airfields = [];
        var marker_taxi = [];
        var marker_bus_station = [];
        var marker_parking = [];
        var marker_university = [];
        var marker_atm = [];
        var marker_museum = [];
        var marker_gym = [];
        var marker_post_office = [];
        var marker_bus_station = [];
        var marker_car_rental = [];
        var marker_movie_theater = [];
        var marker_church = [];
        var marker_metro = [];
        var marker_train_station = [];
        var marker_subway_station = [];
        var marker_park = [];
        var marker_library = [];
        var marker_pharmacy = [];
        var marker_covid = [];
        var marker_electric_car = [];
        var marker_shopping_mall = [];
        var marker_golf_course = [];
        var marker_restaurant = [];
        var marker_cafe = [];
        var marker_historical = [];
        var filter_type = '';

        function NearbyPlacesHandler(filter, status, name = null) {
            filter_type = filter;
            document.getElementById('filter_click').value = filter;

            if (!status) {
                // erase all markers
                if (filter == 'lodging' && (marker_hotels && marker_hotels.length)) {
                    for (i = 0; i < marker_hotels.length; i++) {
                        marker_hotels[i].setMap(null);
                    }
                    marker_hotels.length = 0;
                }
                if (filter == 'police' && (marker_police && marker_police.length)) {
                    for (i = 0; i < marker_police.length; i++) {
                        marker_police[i].setMap(null);
                    }
                    marker_police.length = 0;
                }
                if (filter == 'hospital' && (marker_hospital && marker_hospital.length)) {
                    for (i = 0; i < marker_hospital.length; i++) {
                        marker_hospital[i].setMap(null);
                    }
                    marker_hospital.length = 0;
                }
                if (filter == 'gas_station' && (marker_gasstation && marker_gasstation.length)) {
                    for (i = 0; i < marker_gasstation.length; i++) {
                        marker_gasstation[i].setMap(null);
                    }
                    marker_gasstation.length = 0;
                }
                if (filter == 'airport' && (marker_airfields && marker_airfields.length)) {
                    for (i = 0; i < marker_airfields.length; i++) {
                        marker_airfields[i].setMap(null);
                    }
                    marker_airfields.length = 0;
                }
                if (filter == 'taxi_stand' && (marker_taxi && marker_taxi.length)) {
                    for (i = 0; i < marker_taxi.length; i++) {
                        marker_taxi[i].setMap(null);
                    }
                    marker_taxi.length = 0;
                }
                if (filter == 'bus_station' && (marker_bus_station && marker_bus_station.length)) {
                    for (i = 0; i < marker_bus_station.length; i++) {
                        marker_bus_station[i].setMap(null);
                    }
                    marker_bus_station.length = 0;
                }
                if (filter == 'parking' && (marker_parking && marker_parking.length)) {
                    for (i = 0; i < marker_parking.length; i++) {
                        marker_parking[i].setMap(null);
                    }
                    marker_parking.length = 0;
                }
                if (filter == 'university' && (marker_university && marker_university.length)) {
                    for (i = 0; i < marker_university.length; i++) {
                        marker_university[i].setMap(null);
                    }
                    marker_university.length = 0;
                }
                if (filter == 'atm' && (marker_atm && marker_atm.length)) {
                    for (i = 0; i < marker_atm.length; i++) {
                        marker_atm[i].setMap(null);
                    }
                    marker_atm.length = 0;
                }
                if (filter == 'museum' && (marker_museum && marker_museum.length)) {
                    for (i = 0; i < marker_museum.length; i++) {
                        marker_museum[i].setMap(null);
                    }
                    marker_museum.length = 0;
                }

                if (filter == 'bus_station' && (marker_bus_station && marker_bus_station.length)) {
                    for (i = 0; i < marker_bus_station.length; i++) {
                        marker_bus_station[i].setMap(null);
                    }
                    marker_bus_station.length = 0;
                }

                if (filter == 'post_office' && (marker_post_office && marker_post_office.length)) {
                    for (i = 0; i < marker_post_office.length; i++) {
                        marker_post_office[i].setMap(null);
                    }
                    marker_post_office.length = 0;
                }

                if (filter == 'gym' && (marker_gym && marker_gym.length)) {
                    for (i = 0; i < marker_gym.length; i++) {
                        marker_gym[i].setMap(null);
                    }
                    marker_gym.length = 0;
                }

                if (filter == 'movie_theater' && (marker_movie_theater && marker_movie_theater.length)) {
                    for (i = 0; i < marker_movie_theater.length; i++) {
                        marker_movie_theater[i].setMap(null);
                    }
                    marker_movie_theater.length = 0;
                }

                if (filter == 'car_rental' && (marker_car_rental && marker_car_rental.length)) {
                    for (i = 0; i < marker_car_rental.length; i++) {
                        marker_car_rental[i].setMap(null);
                    }
                    marker_car_rental.length = 0;
                }

                if (filter == 'embassy' && (marker_embassy && marker_embassy.length)) {
                    for (i = 0; i < marker_embassy.length; i++) {
                        marker_embassy[i].setMap(null);
                    }
                    marker_embassy.length = 0;
                }

                if (filter == 'church' && (marker_church && marker_church.length)) {
                    for (i = 0; i < marker_church.length; i++) {
                        marker_church[i].setMap(null);
                    }
                    marker_church.length = 0;
                }
                if (filter == 'embassy' && (marker_embassy && marker_embassy.length)) {
                    for (i = 0; i < marker_embassy.length; i++) {
                        marker_embassy[i].setMap(null);
                    }
                    $('#embassy_result').slideUp('slow');
                    document.getElementById("embassy_result").innerHTML = '';
                    $('#embassy_search').hide();
                    marker_embassy.length = 0;
                }
                if (filter == 'metro' && (marker_metro && marker_metro.length)) {
                    for (i = 0; i < marker_metro.length; i++) {
                        marker_metro[i].setMap(null);
                    }
                    marker_metro.length = 0;
                }
                if (filter == 'train_station' && (marker_train_station && marker_train_station.length)) {
                    for (i = 0; i < marker_train_station.length; i++) {
                        marker_train_station[i].setMap(null);
                    }
                    marker_train_station.length = 0;
                }
                if (filter == 'park' && (marker_park && marker_park.length)) {
                    for (i = 0; i < marker_park.length; i++) {
                        marker_park[i].setMap(null);
                    }
                    marker_park.length = 0;
                }
                if (filter == 'subway_station' && (marker_subway_station && marker_subway_station.length)) {
                    for (i = 0; i < marker_subway_station.length; i++) {
                        marker_subway_station[i].setMap(null);
                    }
                    marker_subway_station.length = 0;
                }
                if (filter == 'library' && (marker_library && marker_library.length)) {
                    for (i = 0; i < marker_library.length; i++) {
                        marker_library[i].setMap(null);
                    }
                    marker_library.length = 0;
                }
                if (filter == 'pharmacy' && (marker_pharmacy && marker_pharmacy.length)) {
                    for (i = 0; i < marker_pharmacy.length; i++) {
                        marker_pharmacy[i].setMap(null);
                    }
                    marker_pharmacy.length = 0;
                }
                if (filter == 'covid_testing_center' && (marker_covid && marker_covid.length)) {
                    for (i = 0; i < marker_covid.length; i++) {
                        marker_covid[i].setMap(null);
                    }
                    marker_covid.length = 0;
                }
                if (filter == 'ev_charging_station' && (marker_electric_car && marker_electric_car.length)) {
                    for (i = 0; i < marker_electric_car.length; i++) {
                        marker_electric_car[i].setMap(null);
                    }
                    marker_electric_car.length = 0;
                }
                if (filter == 'shopping_mall' && (marker_shopping_mall && marker_shopping_mall.length)) {
                    for (i = 0; i < marker_shopping_mall.length; i++) {
                        marker_shopping_mall[i].setMap(null);
                    }
                    marker_shopping_mall.length = 0;
                }
                if (filter == 'golf_course' && (marker_golf_course && marker_golf_course.length)) {
                    for (i = 0; i < marker_golf_course.length; i++) {
                        marker_golf_course[i].setMap(null);
                    }
                    marker_golf_course.length = 0;
                    console.log('golf_course inside');
                }

                if (filter == 'restaurant' && (marker_restaurant && marker_restaurant.length)) {
                    for (i = 0; i < marker_restaurant.length; i++) {
                        marker_restaurant[i].setMap(null);
                    }
                    marker_restaurant.length = 0;
                }
                if (filter == 'cafe' && (marker_cafe && marker_cafe.length)) {
                    for (i = 0; i < marker_cafe.length; i++) {
                        marker_cafe[i].setMap(null);
                    }
                    marker_cafe.length = 0;
                }
                if (filter == 'historical site' && (marker_historical && marker_historical.length)) {
                    for (i = 0; i < marker_historical.length; i++) {
                        marker_historical[i].setMap(null);
                    }
                    marker_restaurant.length = 0;
                }

            } else {
                bounds = new google.maps.LatLngBounds();

                if (filter == 'embassy' || filter == 'bus_station') {
                    var request = {
                        location: myLocation,
                        radius: <?= $radius; ?>, // 10 miles in meters
                        types: [filter],
                    };

                    infowindow = new google.maps.InfoWindow();
                    infowindow.setOptions({
                        pixelOffset: new google.maps.Size(0, -30),
                        boxClass: 'custom-info-window' // Add your custom class name here
                    });
                    var service = new google.maps.places.PlacesService(map_filters);
                    service.nearbySearch(request, callback);
                } else {
                    if (filter == 'covid_testing_center' || filter == 'ev_charging_station' || filter == 'golf_course' || filter == 'historical site' || filter == 'cafe') {
                        var request = {
                            location: myLocation,
                            radius: <?= $radius; ?>, // 10 miles in meters
                            keyword: filter,
                        };
                    } else {
                        console.log(filter);
                        var request = {
                            location: myLocation,
                            radius: <?= $radius; ?>, // 10 miles in meters
                            types: [filter],
                        };
                    }

                    infowindow = new google.maps.InfoWindow();
                    var service = new google.maps.places.PlacesService(map_filters);
                    service.nearbySearch(request, function(results, status) {
                        callback(results, status, name, service);
                    });
                }
            }
        }

        function callback(results, status, name, service) {
            console.log(results)
            "use strict";
            if (status == google.maps.places.PlacesServiceStatus.OK) {
                for (var i = 0; i < results.length; i++) {
                    createMarker(results[i], name, service);
                }
                if (document.getElementById('filter_click').value == 'embassy')
                    $('#embassy_result').slideDown('slow');
            }
        }

        //var tmpi = 0;
        function createMarker(place, name, service) {
            "use strict";
            var place_icon;
            place_icon = "<?= SITE; ?>images/map-icons/" + place.types['0'] + ".png";

            if (document.getElementById('filter_atm').checked && !document.getElementById('filter_gas').checked && place.types['0'] == 'gas_station')
                place_icon = "<?= SITE; ?>images/map-icons/atm.png";

            if (document.getElementById('filter_church').checked && !document.getElementById('filter_university').checked && place.types['0'] == 'university')
                place_icon = "<?= SITE; ?>images/map-icons/church.png";

            if (filter_type == 'covid_testing_center')
                place_icon = "<?= SITE; ?>images/map-icons/covid.png";

            if (filter_type == 'ev_charging_station')
                place_icon = "<?= SITE; ?>images/map-icons/gas_station.png";

            if (filter_type == 'golf_course')
                place_icon = "<?= SITE; ?>images/map-icons/golf_course.png";

            if (filter_type == 'historical site')
                place_icon = "<?= SITE; ?>images/map-icons/historical.png";

            if (filter_type == 'cafe')
                place_icon = "<?= SITE; ?>images/map-icons/cafe.png";

            let main_logo = "<?= SITE; ?>assets/images/new-logo-small.png";

            var placeLoc = place.geometry.location;
            var marker = new google.maps.Marker({
                map: map_filters,
                position: place.geometry.location,
                icon: {
                    url: place_icon
                },
                animation: google.maps.Animation.DROP
            });

            var datainfo;
            if (document.getElementById('filter_click').value == 'lodging')
                marker.type = 'lodging';
            marker_hotels.push(marker);
            if (document.getElementById('filter_click').value == 'police')
                marker.type = 'police';
            marker_police.push(marker);
            if (document.getElementById('filter_click').value == 'hospital')
                marker.type = 'hospital';
            marker_hospital.push(marker);
            if (document.getElementById('filter_click').value == 'gas_station')
                marker.type = 'gas_station';
            marker_gasstation.push(marker);

            if (document.getElementById('filter_click').value == 'airport')
                marker.type = 'airport';
            marker_airfields.push(marker);
            if (document.getElementById('filter_click').value == 'taxi_stand')
                marker.type = 'taxi_stand';
            marker_taxi.push(marker);
            if (document.getElementById('filter_click').value == 'bus_station')
                marker.type = 'bus_station';
            marker_bus_station.push(marker);
            if (document.getElementById('filter_click').value == 'parking')
                marker.type = 'parking';
            marker_parking.push(marker);
            if (document.getElementById('filter_click').value == 'university')
                marker.type = 'university';
            marker_university.push(marker);
            if (document.getElementById('filter_click').value == 'atm')
                marker.type = 'atm';
            marker_atm.push(marker);
            if (document.getElementById('filter_click').value == 'museum')
                marker.type = 'museum';
            marker_museum.push(marker);
            if (document.getElementById('filter_click').value == 'gym')
                marker.type = 'gym';
            marker_gym.push(marker);
            if (document.getElementById('filter_click').value == 'post_office')
                marker.type = 'post_office';
            marker_post_office.push(marker);
            if (document.getElementById('filter_click').value == 'bus_station')
                marker.type = 'bus_station';
            marker_bus_station.push(marker);
            if (document.getElementById('filter_click').value == 'movie_theater')
                marker.type = 'movie_theater';
            marker_movie_theater.push(marker);
            if (document.getElementById('filter_click').value == 'car_rental')
                marker.type = 'car_rental';
            marker_car_rental.push(marker);

            if (document.getElementById('filter_click').value == 'embassy')
                marker.type = 'embassy';
            marker_embassy.push(marker);


            if (document.getElementById('filter_click').value == 'church')
                marker.type = 'church';
            marker_church.push(marker);
            if (document.getElementById('filter_click').value == 'train_station')
                marker.type = 'train_station';
            marker_train_station.push(marker);
            if (document.getElementById('filter_click').value == 'subway_station')
                marker.type = 'subway_station';
            marker_subway_station.push(marker);
            if (document.getElementById('filter_click').value == 'park')
                marker.type = 'park';
            marker_park.push(marker);
            if (document.getElementById('filter_click').value == 'library')
                marker.type = 'library';
            marker_library.push(marker);
            if (document.getElementById('filter_click').value == 'pharmacy')
                marker.type = 'pharmacy';
            marker_pharmacy.push(marker);
            if (document.getElementById('filter_click').value == 'covid_testing_center')
                marker.type = 'covid_testing_center';
            marker_covid.push(marker);
            if (document.getElementById('filter_click').value == 'ev_charging_station')
                marker.type = 'ev_charging_station';
            marker_electric_car.push(marker);
            if (document.getElementById('filter_click').value == 'shopping_mall')
                marker.type = 'shopping_mall';
            marker_shopping_mall.push(marker);
            if (document.getElementById('filter_click').value == 'golf_course')
                marker.type = 'golf_course';
            marker_golf_course.push(marker);
            if (document.getElementById('filter_click').value == 'restaurant')
                marker.type = 'restaurant';
            marker_restaurant.push(marker);
            if (document.getElementById('filter_click').value == 'cafe')
                marker.type = 'cafe';
            marker_cafe.push(marker);
            if (document.getElementById('filter_click').value == 'historical site')
                marker.type = 'historical site';
            marker_historical.push(marker);

            /*
            if (place.types[0] == 'embassy') { //datainfo = '<b><a onclick="google.maps.event.trigger(marker_embassy['+tmpi+'], \'click\')">'+place.name+'</a></b>'+'<br />'+place.vicinity+'<br /> Phone: '+place.formatted_phone_number+'<br />';
                //datainfo = '<a target="_blank" href="<?= SITE; ?>trip/embassy/'+place.place_id+'" >'+place.name+'</a>'+'<br />';
                //datainfo = '<a>'+place.name+'</a>'+'<br />';
                var listembassis = String('<?= $trip->trip_list_embassis; ?>');
                var check = '';
                if (listembassis.includes(place.place_id))
                    check = 'checked="checked"';
                datainfo = '<a><input type="checkbox" name="embassy_list[]" value="' + place.place_id + '" ' + check + '/>' + place.name + '</a>';
                $('#embassy_search').show();
                document.getElementById("embassy_result").innerHTML = document.getElementById("embassy_result").innerHTML + datainfo;
            }
            */

            google.maps.event.addListener(marker, 'click', function() {
                console.log('m', marker);

                var content = '<div id="info-content">';
                content += '<div id="spinner-container"><div class="spinner"></div></div>';
                content += '</div>';

                infowindow.setContent(content);
                infowindow.open(map_filters, this);

                var markerLatLng = marker.getPosition();
                var lat = markerLatLng.lat();
                var lng = markerLatLng.lng();

                let myLocation = {
                    lat: <?= $filter_lat_to; ?>,
                    lng: <?= $filter_lng_to; ?>
                };

                const distance = calcDistanceBetween({
                    lat1: myLocation.lat,
                    lng1: myLocation.lng,
                    lat2: lat,
                    lng2: lng,
                    unit: METRIC
                });


                var infoContent = document.getElementById('info-content');
                var spinner = infoContent ? infoContent.querySelector('.spinner') : null;
                let itemAddress = place?.plus_code?.compound_code ?? 'No set';
                const itemTitle = place?.name ?? '';


                if (spinner) {

                    spinner.style.display = 'block';

                    service.getDetails({
                        placeId: place.place_id,
                        fields: ['name', 'rating', 'formatted_address', 'formatted_phone_number', 'opening_hours', 'opening_hours.weekday_text', 'website', 'photos']
                    }, function(placeDetails, status) {
                        spinner.style.display = 'none';

                        if (status === google.maps.places.PlacesServiceStatus.OK) {

                            if (placeDetails.formatted_address) {
                                itemAddress = placeDetails.formatted_address;
                            }

                            let detailsContent = `
                                <strong class="main_head">
                                <img src="${main_logo}" width="26px"><p>${name}</p> </strong>
                                <p class="main_body"> ${place.name} <span> ${place.vicinity} </span></p>
                                `;
                            if (placeDetails.formatted_phone_number) {
                                detailsContent += '<p><b>Phone: </b> ' + placeDetails.formatted_phone_number + '</p>';
                            }

                            if (placeDetails.opening_hours) {
                                var openingHours = placeDetails.opening_hours;

                                if (openingHours.weekday_text) {
                                    detailsContent += '<p><b>Opening Hours:</b></p><ul>';
                                    for (var i = 0; i < openingHours.weekday_text.length; i++) {
                                        detailsContent += '<li>' + openingHours.weekday_text[i] + '</li>';
                                    }
                                    detailsContent += '</ul>';
                                }
                            } else {
                                detailsContent += '<p><b>Status: </b> Closed</p>';
                            }

                            if (placeDetails.website) {
                                detailsContent += '<p><b>Website: </b> <a href="' + placeDetails.website + '" target="_blank">' + placeDetails.website + '</a></p>';
                            }

                            if (distance) {
                                detailsContent += `<p><b>${distance} from your destination</b></p>`;
                            }

                            if (placeDetails.photos) {
                                // detailsContent += '<p><b>Photos:</b></p>';
                                detailsContent += '<div class="photo-container" style="width: 100%; overflow-x: auto; white-space: nowrap;margin-top:10px;">';
                                detailsContent += '<ul style="display: flex; flex-wrap: nowrap;">';
                                for (var i = 0; i < placeDetails.photos.length; i++) {
                                    detailsContent += '<li style="margin: 10px;"><img src="' + placeDetails.photos[i].getUrl() + '" width="100px" class="enlargeable" onclick="enlargeImage(this)"></li>';
                                }
                                detailsContent += '</ul>';
                                detailsContent += '</div>';
                            }

                            detailsContent += `<button type="button" class='btn btn-resource-add btn-resource-add_sm btn-import-resource' data-title="${itemTitle}" data-type="${marker.type ?? ''}" data-address="${itemAddress}" data-lat="${lat}" data-lng="${lng}">Add</button>`

                            // Update the content of the info window with the details
                            document.getElementById('info-content').innerHTML = detailsContent;
                            const importNode = $('#info-content').find('.btn-import-resource');
                            if (importNode) {
                                setOnImportClick(importNode);
                            }
                        }

                    });

                }


                // infowindow.setContent(`
                // <strong class="main_head">
                // <img src="${main_logo}" width="26px"><p>${name}</p> </strong>
                // <p class="main_body"> ${place.name} <span> ${place.vicinity} </span></p>
                // ${content}
                // `);
                // infowindow.open(map, this);
            });

            bounds.extend(marker.position);

            //now fit the map to the newly inclusive bounds
            map.fitBounds(bounds);
        }

        /******************************/
        function calculateAndDisplayRouteTrain(directionsService, directionsDisplay, map) {
            var start = document.getElementById('location_from').value;
            var end = document.getElementById('location_to').value;
            if (!start) {
                start = document.getElementById('trip_location_from_trainportion').value;
                end = document.getElementById('trip_location_to_trainportion').value;

            }

            //alert(end);
            directionsService.route({
                origin: start,
                destination: end,
                provideRouteAlternatives: true,
                travelMode: 'TRANSIT',
                transitOptions: {
                    modes: [google.maps.TransitMode.TRAIN]
                }
            }, function(response, status) {
                if (status === 'OK') {
                    directionsDisplay.setDirections(response);
                    setTimeout(function() {
                        var directions_text = '';
                        document.getElementById('directions_text').innerHTML = document.getElementById('right-panel').innerHTML;
                        document.getElementById('directions_text').innerHTML = document.getElementsByClassName('adp')[0].innerHTML.replace(/^<div[^>]*? class\s*=\s*["']?adp-agencies[^>]+>([\S\s]*)<\/div>$/g, '\1');
                        directions_text = document.getElementById('directions_text').innerHTML;
                        if (directions_text)
                            $("#filter_submit").removeAttr("disabled");
                    }, 1000);


                    var j = 0;
                    for (var i = response.routes.length; j <= (i - 1); j++) {
                        if (j)
                            var color = '#999999';
                        else
                            var color = '#0000ff';

                        var line = new google.maps.Polyline({
                            path: response.routes[j].overview_path,
                            //geodesic: true,
                            strokeColor: color,
                            strokeOpacity: 1.0,
                            strokeWeight: 3
                        });
                        line.setMap(map);

                        if (j == 0)
                            var center_point = response.routes[j].overview_path.length / 2;
                        if (j == 1)
                            var center_point = response.routes[j].overview_path.length / 2 + 30;
                        if (j == 2)
                            var center_point = response.routes[j].overview_path.length / 2 - 30;
                        if (j == 3)
                            var center_point = response.routes[j].overview_path.length / 2 - 30;

                        var info0 = new google.maps.InfoWindow();
                    }
                } else {
                    window.alert('Directions request failed due to ' + status);
                    $("#filter_submit").removeAttr("disabled");
                }
            });
        }

        function calculateAndDisplayRoute(directionsService, directionsDisplay, map, trmode) {

            if (trmode == undefined) {
                trmode = 'DRIVING';
            } else {
                trmode = trmode;
            }
            <?php if ($trip->trip_transport == 'vehicle') { ?>
                var start = document.getElementById('location_from').value;
                var end = document.getElementById('location_to').value;

            <?PHP } ?>

            <?php if ($trip->trip_location_from_drivingportion && $trip->trip_location_to_drivingportion) { ?>
                var start = document.getElementById('<?= $vehicle_location_from; ?>').value;
                var end = document.getElementById('<?= $vehicle_location_to; ?>').value;

            <?PHP } ?>

            //alert(end);
            directionsService.route({
                origin: start,
                destination: end,
                provideRouteAlternatives: true,
                travelMode: trmode
            }, function(response, status) {
                if (status === 'OK') {
                    directionsDisplay.setDirections(response);
                    setTimeout(function() {
                        var directions_text = '';
                        document.getElementById('directions_text').innerHTML = document.getElementById('right-panel').innerHTML;
                        document.getElementById('directions_text').innerHTML = document.getElementsByClassName('adp')[0].innerHTML.replace(/^<div[^>]*? class\s*=\s*["']?adp-agencies[^>]+>([\S\s]*)<\/div>$/g, '\1');
                        directions_text = document.getElementById('directions_text').innerHTML;
                        if (directions_text)
                            $("#filter_submit").removeAttr("disabled");
                    }, 1000);


                    var j = 0;
                    for (var i = response.routes.length; j <= (i - 1); j++) {
                        if (j)
                            var color = '#999999';
                        else
                            var color = '#0000ff';

                        var line = new google.maps.Polyline({
                            path: response.routes[j].overview_path,
                            //geodesic: true,
                            strokeColor: color,
                            strokeOpacity: 1.0,
                            strokeWeight: 3
                        });
                        line.setMap(map);

                        if (j == 0)
                            var center_point = response.routes[j].overview_path.length / 2;
                        if (j == 1)
                            var center_point = response.routes[j].overview_path.length / 2 + 30;
                        if (j == 2)
                            var center_point = response.routes[j].overview_path.length / 2 - 30;
                        if (j == 3)
                            var center_point = response.routes[j].overview_path.length / 2 - 30;

                        var info0 = new google.maps.InfoWindow();
                        info0.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/car_icon.png'></div><div style='float:right; padding: 3px;'>distance: " + response.routes[j].legs[0].distance.text + "<br>time: " + response.routes[j].legs[0].duration.text + "</div>");
                        info0.setPosition(response.routes[j].overview_path[center_point | 0]);
                        info0.open(map);

                    }
                    //alert(response.routes.length);
                } else {
                    window.alert('Directions request failed due to ' + status);
                    $("#filter_submit").removeAttr("disabled");
                }
            });

        }

        function toggle_visibility(id) {
            var e = document.getElementById(id);
            if (e.style.display == 'block')
                e.style.display = 'none';
            else
                e.style.display = 'block';
        }

        $("#radius").change(function() {
            $("#trip_filters").submit()
        })

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
    <script>
        $(window).on('load', function() {
            $('#filter1-modal').modal('show');
        });
    </script>
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&departure_time=now&libraries=places&callback=initMap" async defer></script>-->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap"></script>

    <script src="<?php echo SITE; ?>js/utils/modal-stepper.js?v=20230815"></script>
    <script src="<?php echo SITE; ?>js/trip_resource.js?v=20230815"></script>

    <?php include('new_backend_footer.php'); ?>

    <script>
        function tabProcess(e, tab) {

            $('.tab-action li').removeClass("active");
            $('.' + tab).addClass("active");

            $('.tab-details div').removeClass("active");
            $('#' + tab).addClass("active");

        }

        var $sliderValue = $(".range-slider[type=range]").val(),
            $rangeSlider = $(".range-slider");

        // update value on scrub
        $rangeSlider.on("input", function() {
            $sliderValue = $(this).val();
            $('#radius').val($sliderValue)
            setTimeout(function() {
                $("#trip_filters").submit()
            }, 1000)
        });
    </script>

    <?php if ($trip->getRole($id_trip) != TripPlan::ROLE_COLLABORATOR) { ?>
        <script type="text/javascript">
            $(document).ready(function () {
                function hideButtons() {
                    if ($(".itinerary-field__button").length ) {
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