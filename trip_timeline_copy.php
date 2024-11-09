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

// echo '<pre/>';
// print_r($trip);

// $markerAlpaArr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB');

// echo "multiWay 1st ".$trip->location_multi_waypoint_latlng."<br/>";

// echo "multiWay 2nd ".json_decode($trip->location_multi_waypoint_latlng)."<br/>";

// echo "multiWay 3rd ".count(json_decode($trip->location_multi_waypoint_latlng))."<br/>";

//  if ($trip->trip_location_to_flightportion || $trip->trip_location_to_drivingportion || $trip->trip_location_to_trainportion) {
//                 $start_marker = $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 1];
//                 $end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 2];
//                 echo "First";
//             } else {
//                 $start_marker = $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng))];
//                 $end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 1];
//                 echo "Second";
//             }
            
// var_dump($start_marker);


// die();

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

    <link href="<?php echo SITE; ?>assets/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css">

    <link href="<?php echo SITE; ?>assets/css/app-style.css" rel="stylesheet" type="text/css" />

    <script src="<?php echo SITE; ?>assets/js/modernizr.min.js"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />

    <script>
        var SITE = '<?= 'https://' . $_SERVER['HTTP_HOST'] . '/staging/'; ?>'
    </script>
    <script src="<?= SITE; ?>assets/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="<?php echo SITE; ?>js/trip_timeline.js"></script>
    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    
    

    <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
    <script src="<?php echo SITE; ?>js/flexcroll.js"></script>

    <?php /*include('new_head_files.php');  */ ?>

    <style>
        /*        .modaltrans {
                        height: 66px;
                        width: 292px;
                        overflow: hidden !important;
                        -webkit-transition: all 2s ease;
                        -moz-transition: all 2s ease;
                        -o-transition: all 2s ease;
                        transition: all 2s ease;
                    }*/
        .modaltrans {
            /*height: 66px;*/
            width: 292px;
            overflow: hidden !important;
            /*                -webkit-transition: all 2s ease;
                                -moz-transition: all 2s ease;
                                -o-transition: all 2s ease;
                                transition: all 2s ease;*/
            padding-left: 0px !important;
            max-height: 300px;
            margin-left: 14px;
        }

        .modaltrans-body {
            /*zoom: 19%;*/
            /*                transform: scale(0.2) translate(-200%, -200%);
                                width: 500%;*/
            transform: scale(0.4) translate(-77%, -77%);
            width: 260%;
            /*transform: scale(1.5);*/
        }

        .time-text {
            color: #fff;
            font-size: 12px;
            margin: 4px 0;
        }
    </style>
</head>

<body class="custom_notes">

    <?php include('new_backend_header.php'); ?>
    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu text-center plan-nav">
                    <li class="selected">
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
                    <li>
                        <a href="<?php echo SITE; ?>trip/filters/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?php echo SITE; ?>assets/images/step_filters.png" alt="Filters">-->
                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/slider_02.png" alt="Schedule">Filters</p>
                        </a>
                    </li>
                   <!-- <li>-->
                   <!-- 	<a href="<?php echo SITE; ?>trip/plans/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">-->
                        	<!--<img src="<?php echo SITE; ?>assets/images/step_filters.png" alt="Filters">-->
                   <!--      	<p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/plans.png" alt="Schedule">Plans</p>-->
                   <!--   	</a>-->
                  	<!--</li>-->
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
    <!--<div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div id="map"></div>
            </div>
        </div>
    </div>-->
    <div id="schedule-modal2" data-backdrop="false" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div id="modal-dialog1" class="modal-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
            <div class="modal-content modal-content-white px-4">
                <div class="modal-header pl-0">
                    <!--data-dismiss="modal"-->
                    <button type="button" class="close" aria-hidden="true">-</button>
                    <div>
                        <p class="small-logo-title pt-4">PLANIVERSITY</p>
                        <h4 class="modal-title pl-0 pt-0    " id="myLargeModalLabel">Create Schedule</h4>
                    </div>
                </div>
                <div class="modal-body px-0" id="schedule-modal2-body">
                    <form name="timeline_form" method="post" novalidate>
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
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-12">
                                    <div class="form-group">
                                        <p class="event-title">Add New Event</p>
                                        <input name="timeline_name" id="timeline_name" type="text" maxlength="150" class="timeline-input" value="" placeholder="Add New Event" class="dashboard-form-control input-lg">
                                        <div>
                                            <p class="event-title mb-0 pt-3">Time</p>
                                            <div class="col-md-12 col-lg-7 px-0">
                                                <div class="time-input">
                                                    <input type="time" id="timeline_time" name="timeline_time" class="form-control timer-input" tabindex="53" placeholder="1 : 30 AM">
                                                    <p class="time-text">Time format: HH:MM</p>
                                                    <input name="timeline_date" id="timeline_date" type="hidden" value="" />
                                                    <input name="timeline_id" id="timeline_id" type="hidden" value="" />
                                                </div>
                                            </div>
                                            <div>
                                                <input name="timeline_add" id="timeline_add" type="button" class="create-trip-btn" value="Save Event">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12">
                                    <div style="max-width: 360px; margin: auto;" class="calender-wrapper">
                                        <div class="row">
                                            <div class="col-md-8 col-lg-8">
                                                <div id="calendar"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12">
                                    <div class="note-info-wrapper note-info-wrapper-white px-0 py-0">
                                        <h3 class="note-helper p-4 mb-0">Why Create a Timeline?</h3>
                                        <div class="note-body">
                                            <p class="p-4">Just as you would use the calendar feature on your phone or computer, keeping
                                                yourself reminded of meetings, scheduled events, or just to remember when
                                                you want to be somewhere at a specific time is key. The best part about the
                                                timeline is that once you do create it, you will have it kept in the same
                                                location as the rest of your trip information. And if you happen to share
                                                your plan with others, you will all be on the same page. Sounds like you're
                                                a real pro!
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-sm-12 pt-3">
                                    <div id="data_list">
                                        <?php
                                        $stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=?");
                                        $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                                        $tmp = $stmt->execute();
                                        $aux = '';
                                        if ($tmp && $stmt->rowCount() > 0) {
                                            $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($timelines as $timeline) {
                                                $aux .= '<div class = "note-result-wrap" id="timeline_' . $timeline->id_timeline . '">
                                        <p>' . date('M d, h:i', strtotime($timeline->date)) . ' &nbsp;&nbsp;&nbsp; <span style="color:#78859A;">' . $timeline->title . '</span>
                                          <a href = "#" onclick="del_element(' . $timeline->id_timeline . ')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete">
                                            <i class = "fa fa-times-circle edit-icon"  style="color:#058BEF;"></i>
                                          </a>
                                          <a href = "#" onclick="toggle_edit_form(\'' . $timeline->id_timeline . '\');" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                                <i class = "fa fa-pencil (alias) edit-icon" style="color:#058BEF;"></i>
                                          </a>
                                        </p>
                                        <div id="' . $timeline->id_timeline . '" style="display:none;">
                                            <div class = "row">
                                               <div class="col-md-10">
                                                  <input type="text" name="nm_' . $timeline->id_timeline . '" id="name_' . $timeline->id_timeline . '" class="edit-form-control input-lg" placeholder="' . date('M d, Y h:i a', strtotime($timeline->date)) . ' --- ' . $timeline->title . '" required="">
                                               </div>
                                               <div class="col-md-2">
                                                  <a onClick="timeline_edit(\'' . $timeline->id_timeline . '\', \'name_' . $timeline->id_timeline . '\', \'' . $timeline->date . '\')" class="save-edit-btn">Save</a>
                                               </div>
                                             </div>
                                        </div>
                                    </div>';
                                            }
                                            echo $aux;
                                        }
                                        ?>
                                    </div>
                                    <br clear="all">
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-5 col-lg-5 mt-5 pt-2">
                                            <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>" class="skip-note-btn mobile_skip">Skip This Section</a>
                                        </div>
                                        <div class="col-md-7 col-lg-7 mt-5 text-right mt-xs-0">
                                            <div class="form-group">
                                                <button name="timeline_submit" id="timeline_submit" type="submit" class="refresh-btn">Finished, Next Step
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-lg-8 col-sm-12">
                                    <div class="error_cont">
                                        <div id="error_list" class="show_error"></div>
                                        <div id="loading_list"><img src="<?php echo SITE; ?>images/loading.gif" /></div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-12">

                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
        <div class="modal fade" id="update_schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Update Schedule</h4>
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

    <!--<div class="push"></div>-->

    <!--<br clear="all"/>-->
    <div id="map"></div>

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
    <!--<script src="<? /*= SITE; */ ?>js/node_modules/php-date-formatter/js/php-date-formatter.min.js"></script>
<script src="<? /*= SITE; */ ?>js/node_modules/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="<? /*= SITE; */ ?>js/node_modules/jquery.datetimepicker.js?v=3"></script>-->

    <script src="<?= SITE; ?>assets/js/moment.min.js"></script>
    <script src="<?= SITE; ?>assets/js/bootstrap-datepicker.js"></script>

    <script src="<?= SITE; ?>assets/js/popper.min.js"></script>
    <script src="<?= SITE; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= SITE; ?>assets/js/jquery.slimscroll.js"></script>

    <script src="<?= SITE; ?>assets/js/jquery.core.js"></script>
    <script src="<?= SITE; ?>assets/js/jquery.app.js"></script>
    <script src="<?= SITE; ?>js/global.js"></script>

    <script>
        $(window).on('load', function() {
            $('#schedule-modal2').modal('show');
            $('#loading_list').hide();
        });

        $('#calendar').datepicker({
            inline: true,
            format: 'Y-m-d H:i:s'
        }).on('changeDate', function(e) {
            var ts = new Date(e.date);
            var date = ts.toISOString().slice(0, 10);
            $('#timeline_date').val(date);
        });

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
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: 7
            });
            var bounds2 = new google.maps.LatLngBounds();
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
            if ($trip->trip_location_to_flightportion || $trip->trip_location_to_drivingportion || $trip->trip_location_to_trainportion) {
                $start_marker = $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 1];
                $end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 2];
            } else {
                $start_marker = $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng))];
                $end_marker =  $markerAlpaArr[count(json_decode($trip->location_multi_waypoint_latlng)) + 1];
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


            ?>
                bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_tod; ?>, <?PHP echo $lng_tod; ?>));
                new AutocompleteDirectionsHandler(map, 'driving', <?PHP echo $lat_fromd; ?>, <?PHP echo $lng_fromd; ?>, <?PHP echo $lat_tod; ?>, <?PHP echo $lng_tod; ?>, [], [], true, "<?PHP echo $end_marker; ?>");
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

        function toggle_edit_form(id) {
            var e = document.getElementById(id);
            if (e.style.display == 'block')
                e.style.display = 'none';
            else
                e.style.display = 'block';
        }

        $(".close").click(function() {
            if ($("#schedule-modal2").hasClass('modaltrans')) {
                $("#schedule-modal2").removeClass('modaltrans');
                $("#schedule-modal2-body").removeClass('modaltrans-body');
                $("#myLargeModalLabel").css({
                    fontSize: 21
                });
                $("#modal-dialog1").css({
                    maxWidth: '70%'
                });
                $(".calender-wrapper").css({
                    visibility: 'visible'
                })
                $(this).html("-");
            } else {
                $("#schedule-modal2").addClass('modaltrans');
                $("#schedule-modal2-body").addClass('modaltrans-body');
                $("#myLargeModalLabel").css({
                    fontSize: 15
                });
                $("#modal-dialog1").css({
                    maxWidth: '100%'
                });
                $(".calender-wrapper").css({
                    visibility: 'hidden'
                })
                $(this).html("+");
            }
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

    <?php include('new_backend_footer.php'); ?>
</body>

</html>
