<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
include_once("config.ini.php");
$SITE = 'https://www.planiversity.com/';
if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/travel-documents/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}
$output = '';
include("class/class.TripPlan.php");
$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
if (empty($id_trip))
    header("Location:" . SITE . "trip/how-are-you-traveling");
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
    case 'vehicle' : $travelmode = 'DRIVING';
        break;
    case 'train' : $travelmode = 'TRANSIT';
        break;
}

if (isset($_POST['connect_submit'])) {
    //header("Location:".SITE."trip/add-employee-profile/".$id_trip);
    header("Location:" . SITE . "trip/add-employee-profile/" . $id_trip);
    /* $filter = $_POST['filter_option'];
      $embassis = $_POST['embassy_list'];
      // edit data trip in DB
      $trip->edit_data_filter($id_trip,$filter,$embassis);
      if (!$trip->error)
      header("Location:".SITE."trip/add-employee-profile/".$id_trip);
      else
      $output = 'A system error has been encountered. Please try again.'; */
}
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6"> <![endif]-->
<!--[if IE 7 ]> <html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8 ]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9 ]> <html lang="en" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--->
<html lang="en">
    <!--<![endif]-->
    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Planiversity | Consolidated Travel Information Management</title>
            <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
            <meta name="keywords" content="Consolidated Travel Information Management">
            <meta name="author" content="">
            <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>images/favicon.png">

<!--            <link href="<?php echo SITE; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo SITE; ?>assets/css/icons.css" rel="stylesheet" type="text/css"/>
            <link href="<?php echo SITE; ?>assets/css/app-style.css" rel="stylesheet" type="text/css"/>-->
            <title>PLANIVERSITY - ADD A TRIP NAME</title>

            <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
            <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

            <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo SITE ?>assets/css/dev-style.css" rel="stylesheet" type="text/css"/>
            <!--<link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
            <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>-->

            <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
            <script src="<?php echo SITE; ?>js/js_map.js"></script>
            <script src="<?php echo SITE; ?>js/global.js"></script>
            <script>SITE = 'https://www.planiversity.com/';</script>
            <?php include('new_head_files.php'); ?>

            <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
            <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
            <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
            <style>
                .source-wrapper p{
                    position: absolute;
                    top: 36px;
                    left: 88px;
                    color: rgba(0,0,0,0.4);
                    font-size: 13px;
                    font-weight: 900;
                    pointer-events: none;
                    -webkit-transform: rotate(-45deg);
                    -moz-transform: rotate(-45deg);
                }
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
                    width: 292px;
                    overflow: hidden !important;
                    padding-left: 0px !important;
                    max-height: 300px;
                    margin-left: 14px;
                }
                .modaltrans-body{
                    /*zoom: 19%;*/
                    /*                    transform: scale(0.2) translate(-200%, -200%);
                                        width: 500%;*/
                    transform: scale(0.4) translate(-77%, -77%);
                    width: 260%;
                    /*transform: scale(1.5);*/
                }
            </style>
        </head>
        <body>

            <?php include('new_backend_header.php'); ?>
            <div class="navbar-custom old-site-colors">
                <div class="container-fluid">
                    <div id="navigation">
                        <ul class="navigation-menu text-center plan-nav">
                            <!--<li class="box">-->
                            <!--    <a href="<?php echo SITE; ?>trip/create-timeline/<?php echo $_GET['idtrip']; ?>" class="left-nav-button top-progress">-->
                            <!--        <img src="<?php echo SITE; ?>assets/images/step_calendar.png" alt="Schedule">-->
                            <!--        <p>Schedule</p>-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--<li class="step-arrow">-->
                            <!--    <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>"-->
                            <!--       class="left-nav-button top-progress">-->
                            <!--        <img src="<?php echo SITE; ?>assets/images/step_notes.png" alt="Notes">-->
                            <!--        <p>Notes</p>-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--<li class="step-arrow">-->
                            <!--    <a href="<?php echo SITE; ?>trip/filters/<?php echo $_GET['idtrip']; ?>"-->
                            <!--       class="left-nav-button top-progress">-->
                            <!--        <img src="<?php echo SITE; ?>assets/images/step_filters.png" alt="Filters">-->
                            <!--        <p>Filters</p>-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--<li class="step-arrow">-->
                            <!--    <a href="<?php echo SITE; ?>trip/travel-documents/<?php echo $_GET['idtrip']; ?>"-->
                            <!--       class="left-nav-button top-progress">-->
                            <!--        <img src="<?php echo SITE; ?>assets/images/step_documents.png" alt="Documents">-->
                            <!--        <p>Documents</p>-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--<li class="box">-->
                            <!--    <a href="<?php echo SITE; ?>trip/connect/<?php echo $_GET['idtrip']; ?>"-->
                            <!--       class="left-nav-button top-progress active-step scale">-->
                            <!--        <img src="<?php echo SITE; ?>assets/images/step_connect_sources.gif" alt="Connect">-->
                            <!--        <p>Connect</p>-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--<li class="step-arrow">-->
                            <!--    <a href="<?php echo SITE; ?>trip/add-employee-profile/<?php echo $_GET['idtrip']; ?>"-->
                            <!--       class="left-nav-button top-progress">-->
                            <!--        <img src="<?php echo SITE; ?>assets/images/step_pdf.png" alt="Export">-->
                            <!--        <p>Export</p>-->
                            <!--    </a>-->
                            <!--</li>-->
                            <li>
                                <a href="<?php echo SITE; ?>trip/create-timeline/<?php echo $_GET['idtrip']; ?>" class="left-nav-button scale" data-toggle="modal"
                                   data-target="#schedule-modal">
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
                            <li>
                                <a href="<?php echo SITE; ?>trip/travel-documents/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                                    <!--<img src="<?php echo SITE; ?>assets/images/step_documents.png" alt="Documents">-->
                                    <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/folder_open.png" alt="Schedule">Documents</p>
                                </a>
                            </li>
                            <li class="selected">
                                <a href="<?php echo SITE; ?>trip/connect/<?php echo $_GET['idtrip']; ?>"
                                   class="left-nav-button">
                                    <!--<img src="<?php echo SITE; ?>assets/images/step_connect_sources.png" alt="Connect">-->
                                    <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/share_outline.png" alt="Schedule">Connect</p>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo SITE; ?>trip/add-employee-profile/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">
                                    <!--<img src="<?php echo SITE; ?>assets/images/step_pdf.png" alt="Export">-->
                                    <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/download.png" alt="Schedule">Export</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <!--        <div class="wrapper">
                    <div class="container-fluid">
                        <div class="row">
                            <br clear="all" />
                            <div id="map"></div>
                        </div>
                    </div> 
                </div>-->
        <div data-backdrop="false" id="document-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">

            <input name="location_from" id="location_from" class="inp1" value="<?php echo $trip->trip_location_from; ?>" type="hidden">
            <input name="location_to" id="location_to" class="inp1" value="<?php echo $trip->trip_location_to; ?>" type="hidden">

            <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
            <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
            <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
            <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">                    
            <input name="trip_location_from_trainportion" id="trip_location_from_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
            <input name="trip_location_to_trainportion" id="trip_location_to_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">


            <div class="modal-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--data-dismiss="modal"-->
                        <button type="button" class="close"  aria-hidden="true">-</button>
                        <h4 class="modal-title" id="myLargeModalLabel">Do you need to add one of these?</h4>
                    </div>
                    <div class="modal-body" id="document-modal-body">
                        <form method="post" class="routemap" >
                            <fieldset>
                                <div class = "row">
                                    <div class="col-md-4">
                                        <a href = "">
                                            <div class = "source-wrapper">
                                                <p>Coming Soon!</p>
                                                <img src = "/assets/images/airbnb.png" alt = "airbnb">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href = "">
                                            <div class = "source-wrapper">
                                                <p>Coming Soon!</p>
                                                <img src = "/assets/images/opentable.png" alt = "opentable">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href = "">
                                            <div class="source-wrapper">
                                                <p>Coming Soon!</p>
                                                <img src = "/assets/images/tripadviser.png" alt = "tripadvisor">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class = "row">
                                    <div class = "col-md-9">
                                        <div class="form-group">
                                            <input name="connect_submit" id="connect_submit" type="submit" class="create-trip-btn" value="Save and Next">
                                            <!--<button type="submit" class="create-trip-btn">Save and Next</button>-->
                                        </div>
                                    </div>
                                    <div class = "col-md-3">
                                        <a href="<?php echo SITE; ?>trip/add-employee-profile/<?php echo $_GET['idtrip']; ?>" class="skip-note-btn">Skip This Section</a>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br clear="all" />
        <div id="map"></div>
        <!--        <footer class="footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="footer-text">&copy; Copyright. 2015 - <script>document.write(new Date().getFullYear())</script> Planiversity, LLC. All Rights Reserved. </p>
                            </div>
                        </div>
                    </div>
                </footer>-->


        <!--<script src="/assets/js/jquery.min.js"></script>-->
<!--        <script src="/assets/js/moment.min.js"></script>
        <script src="/assets/js/popper.min.js"></script>-->
        <!--<script src="/assets/js/bootstrap.min.js"></script>-->
<!--        <script src="/assets/js/jquery.slimscroll.js"></script>
        <script src="/assets/js/jquery.scrollTo.min.js"></script>-->

        <!--<script src="/assets/js/gmaps.min.js"></script>-->

        <!--<script src="/assets/js/jquery.core.js"></script>-->
        <!--<script src="/assets/js/jquery.app.js"></script>-->

        <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&callback=myMap"></script>-->
        <?PHP
        $scale = 'METRIC';
        if ($userdata['scale'] == 'imperial') {
            $scale = 'IMPERIAL';
        }
        if (!empty($trip->trip_location_from_latlng_flightportion)) {
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

        <script type="text/javascript">

            var map = null;
            var bounds = null;
            var directionsService = null;
            var directionsDisplay = null;

            function initMap() {
                directionsService = new google.maps.DirectionsService();
                directionsDisplay = new google.maps.DirectionsRenderer({polylineOptions: {strokeColor: "#F08A0D"}});
                map = new google.maps.Map(document.getElementById('map'), {
                    mapTypeControl: false,
                    center: {lat: 40.730610, lng: -73.968285},
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    zoom: 7
                });
<?php
if($trip->location_multi_waypoint_latlng) {
    $location_multi_waypoint_latlng = $trip->location_multi_waypoint_latlng;
} else {
    $location_multi_waypoint_latlng = '[]';
}
if ($trip->trip_transport == 'plane') {
    $lat_from_plane = $lat_from;
    $lng_from_plane = $lng_from;
    $lat_to_plane = $lat_to;
    $lng_to_plane = $lng_to;
    ?>
                new DrawPlaneRoutes(map, <?PHP echo $lat_from_plane; ?>, <?PHP echo $lng_from_plane; ?>,<?PHP echo $lat_to_plane; ?>,<?PHP echo $lng_to_plane; ?>,<?php echo $location_multi_waypoint_latlng; ?>, 'flight');
<?php } ?>

<?php
if ($trip->trip_location_from_flightportion) {
    $lat_from_plane = $lat_from_flightportion;
    $lng_from_plane = $lng_from_flightportion;
    $lat_to_plane = $lat_to_flightportion;
    $lng_to_plane = $lng_to_flightportion;
    ?>
                new DrawPlaneRoutes(map, <?PHP echo $lat_from_plane; ?>, <?PHP echo $lng_from_plane; ?>,<?PHP echo $lat_to_plane; ?>,<?PHP echo $lng_to_plane; ?>,<?php echo $location_multi_waypoint_latlng; ?>, 'portion');
<?php } ?>

<?php
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
                    new AutocompleteDirectionsHandler(map, 'driving',<?PHP echo $lat_from; ?>,<?PHP echo $lng_from; ?>,<?PHP echo $lat_to; ?>,<?PHP echo $lng_to; ?>, "A", "B");
<?php } ?>

<?php
if ($trip->trip_location_from_drivingportion) {
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

    if ($trip->trip_transport == 'train')
        $end_marker = "D";
    else
        $end_marker = "C";
    ?>

                    new AutocompleteDirectionsHandler(map, 'driving',<?PHP echo $lat_fromd; ?>,<?PHP echo $lng_fromd; ?>,<?PHP echo $lat_tod; ?>,<?PHP echo $lng_tod; ?>, "", "<?PHP echo $end_marker; ?>");
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
                    new AutocompleteDirectionsHandler(map, 'train',<?PHP echo $lat_from; ?>,<?PHP echo $lng_from; ?>,<?PHP echo $lat_to; ?>,<?PHP echo $lng_to; ?>, "A", "B");
<?php } ?>
<?php
if ($trip->trip_location_from_trainportion) {
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
                    new AutocompleteDirectionsHandler(map, 'train',<?PHP echo $lat_from; ?>,<?PHP echo $lng_from; ?>,<?PHP echo $lat_to; ?>,<?PHP echo $lng_to; ?>, "", "D");
<?php } ?>

            }
            function AutocompleteDirectionsHandler(map, transport, lat_from, lng_from, lat_to, lng_to, marker_a, marker_b) {
                var waypoints = [];
                var location_multi_waypoint_latlng_string = '<?php echo $trip->location_multi_waypoint_latlng ?>';
                var via_waypoints = '<?php echo $trip->trip_via_waypoints ?>';
                via_waypoints = via_waypoints.length>0?JSON.parse(via_waypoints):[];
                // if (location_multi_waypoint_latlng_string) {
                    var location_multi_waypoint_latlng = location_multi_waypoint_latlng_string?JSON.parse(location_multi_waypoint_latlng_string):[];
                    for (var k = 0; k < location_multi_waypoint_latlng.length+1; k++) {
                        for(var kk=0;kk<via_waypoints.length;kk ++) {
                            if(k == via_waypoints[kk].index) {
                                var pt = new google.maps.LatLng(via_waypoints[kk].lat, via_waypoints[kk].lng);
                                waypoints.push({location:pt,stopover:false});
                            }
                        }
                        if(k<location_multi_waypoint_latlng.length) {
                            var location = location_multi_waypoint_latlng[k].location_multi_waypoint_latlng;
                            waypoints.push({location: location, stopover: true})
                        }

                    }
                // }
                var directionsDisplay1 = new google.maps.DirectionsRenderer({polylineOptions: {strokeColor: "#F08A0D"}});
                directionsDisplay.setMap(map);
                directionsDisplay1.setMap(map);
                directionsDisplay.setPanel(document.getElementById('panel'));
                if (transport == 'driving') {
                    var request = {
                        origin: document.getElementById('<?PHP echo $vehicle_location_from; ?>').value,
                        destination: document.getElementById('<?PHP echo $vehicle_location_to; ?>').value,
                        waypoints: waypoints,
                        optimizeWaypoints: true,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING,
                        unitSystem: google.maps.UnitSystem.<?PHP echo $scale; ?>
                    };
                    directionsService.route(request, function (response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            var line = new google.maps.Polyline({
                                path: response.routes[0].overview_path,
                                strokeColor: '#F08A0D',
                                strokeOpacity: 1.0,
                                strokeWeight: 3
                            });
                            directionsDisplay.setDirections(response);
                            var center_point = response.routes[0].overview_path.length / 2;
                            var infowindow = new google.maps.InfoWindow();
                            infowindow.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/car_icon.png'></div><div style='float:right; padding: 3px;'>" + calcTotalDistanceText(response) + "<br>" + calcTotalDurationText(response) + "</div>");
                            infowindow.setPosition(response.routes[0].overview_path[center_point | 0]);
                            infowindow.open(map);
                        }
                    });                    

                }
                if (transport == 'train') {
                    var request = {
                        origin: document.getElementById('<?PHP echo $train_location_from; ?>').value,
                        destination: document.getElementById('<?PHP echo $train_location_to; ?>').value,
                        travelMode: google.maps.DirectionsTravelMode.TRANSIT,
                        transitOptions: {modes: [google.maps.TransitMode.TRAIN]},
                        unitSystem: google.maps.UnitSystem.<?PHP echo $scale; ?>
                    };
                    directionsService.route(request, function (response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            var line2 = new google.maps.Polyline({
                                path: response.routes[0].overview_path,
                                strokeColor: '#F08A0D',
                                strokeOpacity: 1.0,
                                strokeWeight: 3
                            });
                            line2.setMap(map);
                            var center_point2 = response.routes[0].overview_path.length / 2;
                            var infowindow2 = new google.maps.InfoWindow();
                            infowindow2.setContent("<div style='float:left; padding: 3px;'><img width='40' src='" + SITE + "images/train_icon2.png'></div><div style='float:right; padding: 3px;'>" + response.routes[0].legs[0].distance.text + "<br>" + response.routes[0].legs[0].duration.text + "</div>");
                            infowindow2.setPosition(response.routes[0].overview_path[center_point2 | 0]);
                            infowindow2.open(map);
                            directionsDisplay.setDirections(response);
                        }
                    });
                }

            }

            $(".close").click(function () {
                if ($("#document-modal").hasClass('modaltrans')) {
                    $("#document-modal").removeClass('modaltrans');
                    $("#document-modal-body").removeClass('modaltrans-body');
                    $("#myLargeModalLabel").css({
                        fontSize: 21
                    });
                    $(this).html("-");
                } else {
                    $("#document-modal").addClass('modaltrans');
                    $("#document-modal-body").addClass('modaltrans-body');
                    $("#myLargeModalLabel").css({
                        fontSize: 15
                    });
                    $(this).html("+");
                }
            });
            $(window).on('load', function () {
                $('#document-modal').modal('show');
            });
            // The latitude and longitude of your business / place
            var position = [51.508742, -0.120850];

            function showGoogleMaps() {

                var latLng = new google.maps.LatLng(position[0], position[1]);

                var mapOptions = {
                    zoom: 3, // initialize zoom level - the max value is 3
                    streetViewControl: false, // hide the yellow Street View pegman
                    scaleControl: true, // allow users to zoom the Google Map
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: latLng
                };

                map = new google.maps.Map(document.getElementById('map'),
                        mapOptions);
            }
            function toggle_visibility(id) {
                var e = document.getElementById(id);
                if (e.style.display == 'block')
                    e.style.display = 'none';
                else
                    e.style.display = 'block';
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>
        <?php include('new_backend_footer.php'); ?>
    </body>
</html>