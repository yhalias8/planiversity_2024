<?php
session_start();
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
    <title>PLANIVERSITY - ADDS NOTES TO YOUR TRIP PLAN</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/app-style.css" rel="stylesheet" type="text/css" />

    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?php echo SITE; ?>'
    </script>
    <script src="<?php echo SITE; ?>js/trip_notes.js"></script>

    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <script src="<?php echo SITE; ?>js/global.js"></script>

    <?php include('new_head_files.php'); ?>
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

<body class="custom_notes">

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div>

        <?php include('new_backend_header.php'); ?>
        <?php include_once('includes/top_bar_active.php'); ?>

        </header>

        <div id="note-modal" data-backdrop="false" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div id="modal-dialog1" class="modal-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
                <div class="modal-content modal-content-white px-4">
                    <div class="modal-header pl-0">
                        <!--data-dismiss="modal"-->
                        <button type="button" class="close" aria-hidden="true">-</button>
                        <div>
                            <p class="small-logo-title pt-4">PLANIVERSITY</p>
                            <h4 class="modal-title pl-0 pt-0 " id="myLargeModalLabel">Add Notes to your Trip Packet</h4>
                        </div>
                    </div>
                    <div class="modal-body px-0" id="note-modal-body">
                        <form name="notes_form" method="post" class="routemap">
                            <div class="error_style"><?php echo $output; ?></div>
                            <input name="location_from" id="location_from" class="inp1" value="<?php echo $trip->trip_location_from; ?>" type="hidden">
                            <input name="location_to" id="location_to" class="inp1" value="<?php echo $trip->trip_location_to; ?>" type="hidden">
                            <input name="notes_idtrip" id="notes_idtrip" class="inp1" value="<?php echo $trip->trip_id; ?>" type="hidden">
                            <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
                            <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
                            <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
                            <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">
                            <input name="trip_location_from_trainportion" id="trip_location_from_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
                            <input name="trip_location_to_trainportion" id="trip_location_to_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <textarea style="padding:15px;" autofocus name="notes_text" id="notes_text" maxlength="500" cols="" class="dashboard-form-textarea-control input-lg" rows="6" placeholder="Add Note"></textarea>
                                        </div>
                                        <div class="form-group py-4">
                                            <input name="notes_add" id="notes_add" type="button" class="create-trip-btn" value="Save Note">
                                        </div>
                                        <div id="data_list">
                                            <?php
                                            $stmt = $dbh->prepare("SELECT * FROM notes WHERE id_trip=?");
                                            $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                                            $tmp = $stmt->execute();
                                            $aux = '';
                                            if ($tmp && $stmt->rowCount() > 0) {
                                                $notes = $stmt->fetchAll(PDO::FETCH_OBJ);
                                                foreach ($notes as $note) {
                                                    $aux .= '<div class = "note-result-wrap" id="note_' . $note->id_note . '">
                                        <p>' . $note->text .
                                                        '<a onclick="del_element(\'' . $note->id_note . '\')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class = "fa fa-times-circle edit-icon"  style="color:#058BEF;"></i></a>
                                        </p>
                                    </div>';
                                                }
                                                echo $aux;
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <div class="note-info-wrapper note-info-wrapper-white px-0 py-0">
                                            <h3 class="note-helper p-4 mb-0">Why Create Trip Notes</h3>
                                            <div class="note-body">
                                                <p class="p-4"><?php echo $config->Why_Create_Trip_Notes ?></p>
                                            </div>
                                        </div>

                                        <div class="pt-5">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 col-lg-8 col-sm-12 pt-3"></div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-5 col-lg-5  pt-2">
                                                <a href="<?php echo SITE; ?>trip/filters/<?php echo $_GET['idtrip']; ?>" class="skip-note-btn mb mobile_skip">Skip This Section</a>
                                            </div>
                                            <div class="col-md-7 col-lg-7  text-right mt-xs-0">
                                                <div class="form-group">
                                                    <input name="notes_submit" id="notes_submit" type="submit" class="refresh-btn mt-3 mt-xs-0 mt-sm-0" value="Finished, Next Step">
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
                                </div>
                            </fieldset>
                            <br clear="all" />
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <br clear="all" />
        <div id="map"></div>

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
            $(".close").click(function() {
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