<?php
include_once("config.ini.php");
include("class/class.TripPlan.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/filters/' . $_GET['idtrip'];
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
        $filter[50] = $_POST['lat_to'] . '::' . $_POST['lng_to'] . '::' . $_POST['radius'];
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

$markerAlpaArr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB');

$zoom = 11;
$radius = 0;
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
        header("Location:" . SITE . "trip/filters/" . $id_trip);
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
    <title>PLANIVERSITY - FILTERS</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/app-style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/dev-style.css" rel="stylesheet" type="text/css" />

    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?php echo SITE; ?>'
    </script>
    <script src="<?php echo SITE; ?>js/trip_timeline.js"></script>
    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <script src="<?php echo SITE; ?>js/global.js"></script>

    <link href="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />

    <script src="<?php echo SITE; ?>js/popup/jquery.js"></script>
    <link href="<?php echo SITE; ?>js/popup/jquery.css" rel="stylesheet" type="text/css" />


    <?php include('new_head_files.php') ?>

    <style>
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

<body class="custom_filters">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!--<div class="content"> -->
    <?php include('new_backend_header.php') ?>

    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu text-center plan-nav">
                    <li>
                        <a href="<?php echo SITE; ?>trip/create-timeline/<?php echo $_GET['idtrip']; ?>" class="left-nav-button scale" data-toggle="modal" data-target="#schedule-modal">
                            <!--<img src="<?php echo SITE; ?>assets/images/step_calendar.png" alt="Schedule">-->
                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/calendar_check.png" alt="Schedule">Schedule</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?php echo SITE; ?>assets/images/step_notes.png" alt="Notes">-->
                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/file_blank.png" alt="Schedule">Notes</p>
                        </a>
                    </li>
                    <li class="selected">
                        <a href="<?php echo SITE; ?>trip/filters/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?php echo SITE; ?>assets/images/step_filters.png" alt="Filters">-->
                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/slider_02.png" alt="Schedule">Filters</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>trip/travel-documents/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?php echo SITE; ?>assets/images/step_documents.png" alt="Documents">-->
                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/folder_open.png" alt="Schedule">Documents</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>trip/connect/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?php echo SITE; ?>assets/images/step_connect_sources.png" alt="Connect">-->
                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/share_outline.png" alt="Schedule">Connect</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>trip/name/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?php echo SITE; ?>assets/images/step_pdf.png" alt="Export">-->
                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/download.png" alt="Schedule">Export</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </header>

    <div data-backdrop="false" id="filter1-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none">
        <div class="modal-dialog filter-modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3" style="background-color: rgba(245, 250, 253, 0.83);">
            <div class="modal-content background-transparent shadow-none">
                <div class="modal-header rounded-0">
                    <button type="button" id="mclose" class="close" aria-hidden="true">-</button>
                    <h4 class="modal-title" id="myLargeModalLabel">Trip Filters</h4>
                </div>
                <div class="modal-body background-transparent px-4" id="filter1-modal-body">
                    <form id="trip_filters" name="timeline_form" method="post" class="routemap">
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12 col-lg-3">
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
                                    <?php include('include_filter.php'); ?>
                                </div>

                                <div class="col-md-12 col-lg-9">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-8">
                                            <div class="filter-wrapper rounded p-0 background-main" id="direction_cont" style="display: none;">
                                                <h6 class="modal-sub-title text-white">Directions</h6>
                                                <!--Directions<br>-->
                                                <div class="flexible-container rounded-bottom border-grey" id="directions_map"></div>
                                            </div>
                                            <!--Facility Radius<br> -->
                                            <div class="filter-wrapper rounded p-0 background-main">
                                                <h6 class="modal-sub-title text-white"><img class="modal-sub-title-img" src="<?= SITE; ?>/images/flag_outline.png"></img>Facility Radius</h6>
                                                <div class="flexible-container rounded-bottom border-grey" id="filter_map"></div>
                                            </div>
                                            <!--<label for="radius">Scale:<input name="radius" id="radius" class="inp1" value="<?= $radius; ?>" type="text"><?= $scale; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add a number value, then click the map to set location.</label>-->
                                            <div class="filter-bottom-wrapper rounded p-2 border-grey">
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
                                                    <input type="range" class="range-slider" min="0" max="20" value="<?= $radius; ?>" step="1" style="box-shadow: none; padding: 5px 0px 5px 0px; width: 300px">
                                                </div>
                                                <div class="flex-div">
                                                    <!-- <p>Add a number value, then click the map to set location.</p> -->
                                                </div>

                                            </div>
                                            <div class="border-grey rounded">
                                                <div class="d-flex py-3 px-2 flex-row-reverse filter_button">
                                                    <input name="filter_submit" id="filter_submit" type="submit" class="create-trip-btn text-dark" value="Save and Next">
                                                    <?php if ($showclear) { ?>
                                                        <input name="filter_clear" id="filter_clear" type="submit" class="refresh-btn text-dark mr-2" value="Clear Filter">
                                                    <?php } ?>
                                                    <a href="<?= SITE; ?>trip/travel-documents/<?= $_GET['idtrip']; ?>" class="skip-note-btn mobile_skip">Skip This Section</a>
                                                </div>
                                            </div>
                                        </div>

                                        <?PHP
                                        $stmt = $dbh->prepare("SELECT * FROM config WHERE setting='Why_Add_Filters'");
                                        $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                                        $tmp = $stmt->execute();
                                        if ($tmp && $stmt->rowCount() > 0) {
                                            $text1 = $stmt->fetchAll(PDO::FETCH_OBJ);
                                            $text1 = $text1[0]->value;
                                        }
                                        ?>
                                        <?PHP
                                        $stmt = $dbh->prepare("SELECT * FROM config WHERE setting='How_to_use_the_map'");
                                        $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                                        $tmp = $stmt->execute();
                                        if ($tmp && $stmt->rowCount() > 0) {
                                            $text2 = $stmt->fetchAll(PDO::FETCH_OBJ);
                                            $text2 = $text2[0]->value;
                                        }
                                        ?>

                                        <div class="col-md-12 col-lg-4">
                                            <div class="background-main rounded h-100">
                                                <h6 class="modal-sub-title text-white">Facility Radius</h6>
                                                <div class="direction-info-wrapper rounded border-grey filter-modal-left-content p-0">
                                                    <div class="">
                                                        <div style="display: none;" id="right-panel"></div>
                                                        <h3 class="main-color font-weight-normal p-10px pb-0">How to use the map</h3>
                                                        <p class="p-10px pt-0"><?= $text2; ?></p><br>
                                                    </div>
                                                    <div class="p-10px pb-0">
                                                        <div class="filter-modal-left-devider"></div>
                                                    </div>
                                                    <div class="p-10px background-secondary pt-0">
                                                        <br />
                                                        <h3 class="main-color font-weight-normal">Why Add Filters</h3>
                                                        <p><?= $text1; ?></p>
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

    <br clear="all" />
    <div id="map"></div>
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
        var map_filters = null;
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

        if ($trip->trip_location_to_flightportion || $trip->trip_location_to_drivingportion || $trip->trip_location_to_trainportion) {
            $start_marker = $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 1];
            $end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 2];
        } else {
            $start_marker = $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng))];
            $end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 1];
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
                lat: <?= $lat_to; ?>,
                lng: <?= $lng_to; ?>
            };

            map_filters = new google.maps.Map(document.getElementById('filter_map'), {
                mapTypeControl: false,
                center: {
                    lat: <?= $lat_to; ?>,
                    lng: <?= $lng_to; ?>
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

            google.maps.event.addListener(map_filters, 'click', function(event) {
                var latitude = event.latLng.lat();
                var longitude = event.latLng.lng();
                $("#lat_to").val(event.latLng.lat());
                $("#lng_to").val(event.latLng.lng());
                $("#trip_filters").submit();
            });

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(<?= $lat_tod ? $lat_tod : $lat_to; ?>, <?= $lng_tod ? $lng_tod : $lng_to; ?>),
                icon: 'https://planiversity.com/assets/images/icon.png',
                label: {
                    text: '<?= $end_marker; ?>',
                    color: "#ffffff",
                },
                map: map_filters,
                draggable: true,
                animation: google.maps.Animation.DROP,
            });

            google.maps.event.addListener(marker, 'dragend', function(event) {
                $("#lat_to").val(event.latLng.lat());
                $("#lng_to").val(event.latLng.lng());
                $("#trip_filters").submit();
            });

            <?php
            if ($radius) {
                $radius = $radius * $factor; ?>

                // Add circle overlay and bind to marker
                var circle = new google.maps.Circle({
                    map: map_filters,
                    center: {
                        lat: <?= $lat_to; ?>,
                        lng: <?= $lng_to; ?>
                    },
                    radius: <?= $radius; ?>, // 10 miles in metres
                    fillColor: "#FF0000",
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.4,
                    strokeWeight: 2,
                    fillOpacity: 0.20
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


            ?>
                bounds2.extend(new google.maps.LatLng(<?= $lat_tod; ?>, <?= $lng_tod; ?>));
                new AutocompleteDirectionsHandler(map, 'driving', <?= $lat_fromd; ?>, <?= $lng_fromd; ?>, <?= $lat_tod; ?>, <?= $lng_tod; ?>, [], [], true, "<?= $end_marker; ?>");

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
                    getWeather_(1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_hotels) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('lodging', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_police) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('police', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_hospitals) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('hospital', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_gas) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('gas_station', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_embassis) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('embassy', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_taxi) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('taxi_stand', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_airfields) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('airport', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_university) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('university', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_atm) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('atm', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_museum) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('museum', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_church) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('church', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_metro) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('train_station', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_subway_station) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('subway_station', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_playground) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('park', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_metro) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('library', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_pharmacy) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('pharmacy', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_covid) { ?>
                setTimeout(function() {
                    NearbyPlacesHandler('covid_testing_center', 1);
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_directions) { ?>
                setTimeout(function() {
                    $('#direction_cont').show();
                    $('#right-panel').show();
                    show_directions();
                }, 1000);
            <?php } ?>
            <?php if ($trip->trip_option_busmap) { ?>setTimeout(function() {
                $('#busmap_result').toggle('slow');

            }, 1000);
        <?php } ?>
        <?php if ($trip->trip_option_parking) { ?>setTimeout(function() {
            NearbyPlacesHandler('parking', 1);
        }, 1000);
        <?php } ?>

        $('#filter_directions').click(function() {
            if ($("#filter_directions").is(':checked')) { // checked
                $("#filter_submit").attr("disabled", "disabled");
                $('#direction_cont').show();
                $('#right-panel').show();
                show_directions();
            } else {
                // unchecked
                $('#direction_cont').hide();
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
        var marker_church = [];
        var marker_metro = [];
        var marker_train_station = [];
        var marker_subway_station = [];
        var marker_park = [];
        var marker_library = [];
        var marker_pharmacy = [];
        var marker_covid = [];
        var filter_type = '';

        function NearbyPlacesHandler(filter, status) {
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
            } else {
                bounds = new google.maps.LatLngBounds();

                if (filter == 'embassy' || filter == 'bus_station') {
                    var request = {
                        location: myLocation,
                        radius: <?= $radius; ?>, // 10 miles in meters
                        types: [filter],
                    };

                    infowindow = new google.maps.InfoWindow();
                    var service = new google.maps.places.PlacesService(map_filters);
                    service.nearbySearch(request, callback);
                } else {
                    if (filter == 'covid_testing_center') {
                        var request = {
                            location: myLocation,
                            radius: <?= $radius; ?>, // 10 miles in meters
                            keyword: filter,
                        };
                    } else {
                        var request = {
                            location: myLocation,
                            radius: <?= $radius; ?>, // 10 miles in meters
                            types: [filter],
                        };
                    }

                    infowindow = new google.maps.InfoWindow();
                    var service = new google.maps.places.PlacesService(map_filters);
                    service.nearbySearch(request, callback);
                }
            }
        }

        function callback(results, status) {
            console.log(results)
            "use strict";
            if (status == google.maps.places.PlacesServiceStatus.OK) {
                for (var i = 0; i < results.length; i++) {
                    createMarker(results[i]);
                }
                if (document.getElementById('filter_click').value == 'embassy')
                    $('#embassy_result').slideDown('slow');
            }
        }

        function createMarker(place) {
            "use strict";
            var place_icon;
            place_icon = "<?= SITE; ?>images/map-icons/" + place.types['0'] + ".png";

            if (document.getElementById('filter_atm').checked && !document.getElementById('filter_gas').checked && place.types['0'] == 'gas_station')
                place_icon = "<?= SITE; ?>images/map-icons/atm.png";

            if (document.getElementById('filter_church').checked && !document.getElementById('filter_university').checked && place.types['0'] == 'university')
                place_icon = "<?= SITE; ?>images/map-icons/church.png";

            if (filter_type == 'covid_testing_center')
                place_icon = "<?= SITE; ?>images/map-icons/covid.png";

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
                marker_hotels.push(marker);
            if (document.getElementById('filter_click').value == 'police')
                marker_police.push(marker);
            if (document.getElementById('filter_click').value == 'hospital')
                marker_hospital.push(marker);
            if (document.getElementById('filter_click').value == 'gas_station')
                marker_gasstation.push(marker);
            if (document.getElementById('filter_click').value == 'embassy')
                marker_embassy.push(marker);
            if (document.getElementById('filter_click').value == 'airport')
                marker_airfields.push(marker);
            if (document.getElementById('filter_click').value == 'taxi_stand')
                marker_taxi.push(marker);
            if (document.getElementById('filter_click').value == 'bus_station')
                marker_bus_station.push(marker);
            if (document.getElementById('filter_click').value == 'parking')
                marker_parking.push(marker);
            if (document.getElementById('filter_click').value == 'university')
                marker_university.push(marker);
            if (document.getElementById('filter_click').value == 'atm')
                marker_atm.push(marker);
            if (document.getElementById('filter_click').value == 'museum')
                marker_museum.push(marker);
            if (document.getElementById('filter_click').value == 'church')
                marker_church.push(marker);
            if (document.getElementById('filter_click').value == 'train_station')
                marker_train_station.push(marker);
            if (document.getElementById('filter_click').value == 'subway_station')
                marker_subway_station.push(marker);
            if (document.getElementById('filter_click').value == 'park')
                marker_park.push(marker);
            if (document.getElementById('filter_click').value == 'library')
                marker_library.push(marker);
            if (document.getElementById('filter_click').value == 'pharmacy')
                marker_pharmacy.push(marker);
            if (document.getElementById('filter_click').value == 'covid_testing_center')
                marker_covid.push(marker);
            if (place.types[0] == 'embassy') {
                var listembassis = String('<?= $trip->trip_list_embassis; ?>');
                var check = '';
                if (listembassis.includes(place.place_id))
                    check = 'checked="checked"';
                datainfo = '<a><input type="checkbox" name="embassy_list[]" value="' + place.place_id + '" ' + check + '/>' + place.name + '</a>' + '<br />';

                document.getElementById("embassy_result").innerHTML = document.getElementById("embassy_result").innerHTML + datainfo;
            }

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(place.name + '<br>' + place.vicinity);
                infowindow.open(map, this);
            });

            bounds.extend(marker.position);

            // Now fit the map to the newly inclusive bounds
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
            $("form").submit()
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

        $("#mclose").click(function() {
            if ($("#filter1-modal").hasClass('modaltrans')) {
                $("#filter1-modal").removeClass('modaltrans');
                $("#filter1-modal-body").removeClass('modaltrans-body');
                $("#myLargeModalLabel").css({
                    fontSize: 21
                });
                $(this).html("-");
            } else {
                $("#filter1-modal").addClass('modaltrans');
                $("#filter1-modal-body").addClass('modaltrans-body');
                $("#myLargeModalLabel").css({
                    fontSize: 15
                });
                $(this).html("+");
            }
        });
        $("#tgl-fascilities").click(function() {
            $("#expanded-facilities-modal").show();
        });
        $("#facility-cross").click(function() {
            $("#expanded-facilities-modal").hide();
        });
    </script>
    <script>
        $(window).on('load', function() {
            $('#filter1-modal').modal('show');
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

    <?php include('new_backend_footer.php'); ?>

    <script>
        var $sliderValue = $(".range-slider[type=range]").val(),
            $rangeSlider = $(".range-slider");

        // update value on scrub
        $rangeSlider.on("input", function() {
            $sliderValue = $(this).val();
            $('#radius').val($sliderValue)
            setTimeout(function() {
                $("form").submit()
            }, 1000)
        });
    </script>

</body>

</html>