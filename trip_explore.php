<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/plan-notes/' . $_GET['idtrip'];
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

if (isset($_POST['notes_submit'])) {
    header("Location:" . SITE . "trip/resources/" . $id_trip);
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
    <title>PLANIVERSITY - ADDS NOTES TO YOUR TRIP PLAN</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo SITE; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo SITE; ?>assets/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css">

    <link href="<?php echo SITE; ?>assets/css/app-style.css?v=20230220" rel="stylesheet" type="text/css" />

    <script src="<?php echo SITE; ?>assets/js/modernizr.min.js"></script>


    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?= 'https://' . $_SERVER['HTTP_HOST'] . '/staging/'; ?>'
        var itinerary_type_mode = "<?= $trip->itinerary_type; ?>"
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
    
    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <script src="<?php echo SITE; ?>js/global.js?v=202230"></script>

    <style>
        .modaltrans {
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

        .master_modal {
            overflow: scroll;
        }

        .modal-backdrop {
            background-color: #000;
            z-index: 1111;
        }

        .modal-blur {
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
        }

        div#note-modal-body {
            background-size: 80%;
        }

        .note-navigation {
            display: block;
            width: 100%;
            height: 100%;
        }

        .map_help p {
            background: #048cf2;
            padding: 6px 10px;
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
            top: 80px;
        }

        .modal .modal-dialog .c-close {
            color: #fff;
            font-size: 48px;
            height: 52px;
            width: 48px;
        }

        .explore_section {
            min-height: 500px;
            padding-bottom: 120px;
        }

        .smt-component {
            display: contents;
            width: 100% !important;
            max-width: 100% !important;
            padding: 10px;
            background-color: inherit !important;
        }

        span.smt-card-detailsName.smt-truncateLines {
            font-size: 16px !important;
        }

        button.smt-button {
            cursor: pointer;
        }

        .ReactModal__Overlay.ReactModal__Overlay--after-open {
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
        }
    </style>
    
</head>

<body class="custom_notes">

        <div>

        <?php include('new_backend_header.php'); ?>

        <div class="navbar-custom old-site-colors">
            <div class="container-fluid">
                <?php
                $step_index = "explore";
                include('dashboard/include/itinerary-step.php');
                ?>                
            </div>
        </div>
        </header>
        
        
        <br clear="all" />
        <div id="map"></div>        

        <div id="note-modal" data-backdrop="false" class="modal fade bs-example-modal-lg master_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div id="modal-dialog1" class="modal-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
                <div class="modal-content connect-bg">
                    <div class="modal-header pl-0 px-4">
                        <!--data-dismiss="modal"-->
                        <button type="button" id="mclose" class="close" aria-hidden="true">-</button>
                        <div>
                            <p class="small-logo-title pt-4">PLANIVERSITY</p>
                            <h4 class="modal-title pl-0 pt-0 " id="myLargeModalLabel">Explore your Plan</h4>
                        </div>
                    </div>
                    <div class="modal-body connect-bg-ground " id="note-modal-body">

                        <div class="error_style"><?php echo $output; ?></div>


                        <div class="explore_section">

                            <div id="smartvel" class="smt-component"></div>
                            <SmartvelComponent data-apiKey='010e44f6-6d2b-40a9-92aa-582286f33308' data-lang='en' data-destination='9705' data-latitude='<?= $filter_lat_to; ?>' data-longitude='<?= $filter_lng_to; ?>' data-autofilterinitialization="true" data-ordergroups="top,places,restaurants,events">
                            </SmartvelComponent>
                            <!--Load Smartvel libs-->

                        </div>


                        <div class="note-navigation">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12 pt-3">
                                    <a href="<?php echo SITE; ?>trip/plans/<?php echo $_GET['idtrip']; ?>" class="skip-note-btn mb mobile_skip">Back</a>
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12 custom-full">
                                    <div class="row action-row">
                                        <div class="col-md-9 col-lg-9 pt-2 action-full left">
                                            <a href="<?php echo SITE; ?>trip/travel-documents/<?php echo $_GET['idtrip']; ?>" class="skip-note-btn mb mobile_skip">Skip This Section</a>
                                        </div>
                                        <div class="col-md-3 col-lg-3 mt-xs-0 action-full right">
                                            <div class="form-group">
                                                <a href="<?php echo SITE; ?>trip/travel-documents/<?php echo $_GET['idtrip']; ?>" id="notes_submit" class="refresh-btn mt-3 mt-xs-0 mt-sm-0">Finished, Next Step</a>
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
        
        <script src="https://cdn.smartvel.com/scripts/boot.min.js"></script>

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
                    $lat_from = $tmp[0];
                    $lng_from = $tmp[1];
                    $tmp = str_replace('(', '', $trip->trip_location_to_latlng_trainportion); // Ex: (25.7616798, -80.19179020000001)
                    $tmp = str_replace(')', '', $tmp);
                    $tmp = explode(',', $tmp);
                    $lat_to = $tmp[0];
                    $lng_to = $tmp[1];
                ?>
                    bounds2.extend(new google.maps.LatLng(<?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>));
                    new AutocompleteDirectionsHandler(map, 'train', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, <?php echo $location_multi_waypoint_latlng; ?>, <?php echo $trip_via_waypoints ?>, true, "<?PHP echo $end_marker; ?>");
                <?php } ?>

                map.fitBounds(bounds2);

            }
        </script>
        <script>
            $(window).on('load', function() {
                $('#note-modal').modal('show');
            });
            $("#mclose").click(function() {
                if ($("#note-modal").hasClass('modaltrans')) {
                    $("#note-modal").removeClass('modaltrans');
                    $("#note-modal-body").removeClass('modaltrans-body');
                    $("#myLargeModalLabel").css({
                        fontSize: 21
                    });
                    $("#modal-dialog1").css({
                        maxWidth: '70%'
                    });
                    $(this).html("-");
                } else {
                    $("#note-modal").addClass('modaltrans');
                    $("#note-modal-body").addClass('modaltrans-body');
                    $("#myLargeModalLabel").css({
                        fontSize: 15
                    });
                    $("#modal-dialog1").css({
                        maxWidth: '100%'
                    });
                    $(this).html("+");
                }
            });
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

        <?php include('new_backend_footer.php'); ?>

</body>

</html>
