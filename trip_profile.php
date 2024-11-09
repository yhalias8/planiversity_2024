<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/add-employee-profile/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}
if ($userdata['account_type'] == 'Individual')
    header("Location:" . SITE . "trip/name/" . $_GET['idtrip']);

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
    case 'vehicle':
        $travelmode = 'DRIVING';
        break;
    case 'train':
        $travelmode = 'TRANSIT';
        break;
}

if (isset($_POST['profile_submit'])) { //$filter = $_POST['filter_option'];
    //$embassis = $_POST['embassy_list'];
    // edit data trip in DB
    //$trip->edit_data_filter($id_trip,$filter,$embassis);
    if (!empty($_POST['profile_employee'])) { // save employee data in DB 
        $trip->edit_data_employee($id_trip, $_POST['profile_employee']);
        if (!$trip->error)
            header("Location:" . SITE . "trip/name/" . $id_trip);
        else
            $output = 'A system error has been encountered. Please try again.';
    } else
        header("Location:" . SITE . "trip/name/" . $id_trip);
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
    <title>PLANIVERSITY - ADD AN EMPLOYEE PROFILE</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/app-style.css" rel="stylesheet" type="text/css" />
    <!--<link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITE; ?>js/responsive-nav.js"></script>-->

    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?php echo SITE; ?>'
    </script>
    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <script src="<?php echo SITE; ?>js/global.js"></script>
    <?php include('new_head_files.php'); ?>
    <style>
        /*    .modaltrans{
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

        .modaltrans-body {
            /*zoom: 19%;*/
            /*                transform: scale(0.2) translate(-200%, -200%);
                                width: 500%;*/
            transform: scale(0.4) translate(-77%, -77%);
            width: 260%;
            /*transform: scale(1.5);*/
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

<body class="custom_profile">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include('new_backend_header.php'); ?>

    <?php include_once('includes/top_bar_active.php'); ?>

    </header>

    <div id="export-modal" data-backdrop="false" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none">
        <div class="modal-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">-</button>
                    <h4 class="modal-title" id="myLargeModalLabel">Add an Employee Profile</h4>
                </div>
                <div class="modal-body" id="export-modal-body">
                    <form name="profile_form" method="post" class="routemap">
                        <?php //include('include_icondetails.php')  
                        ?>
                        <div class="error_style"><?php echo $output; ?></div>
                        <input name="location_from" id="location_from" class="inp1" value="<?php echo $trip->trip_location_from; ?>" type="hidden">
                        <input name="location_to" id="location_to" class="inp1" value="<?php echo $trip->trip_location_to; ?>" type="hidden">
                        <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
                        <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
                        <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
                        <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">
                        <input name="trip_location_from_trainportion" id="trip_location_from_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
                        <input name="trip_location_to_trainportion" id="trip_location_to_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">

                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select autofocus name="profile_employee" id="profile_employee" class="dashboard-form-control input-lg">
                                            <option value="">Select...</option>
                                            <?php
                                            $stmt = $dbh->prepare("SELECT * FROM employees WHERE id_user=? ORDER BY f_name");
                                            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                            $tmp = $stmt->execute();
                                            $aux = '';
                                            if ($tmp && $stmt->rowCount() > 0) {
                                                $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
                                                foreach ($employees as $employee) {
                                                    $aux .= '<option value="' . $employee->id_employee . '" ' . ($trip->trip_employee == $employee->id_employee ? 'selected="selected"' : '') . ' >'
                                                        . $employee->f_name . ' ' . $employee->l_name . '                                    
                                            </option>';
                                                }
                                                echo $aux;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <!--<input name="" type="button" class="button bt_blue" value="SKIP THIS STEP">-->
                                        <a href="<?php echo SITE; ?>trip/name/<?php echo $_GET['idtrip']; ?>" class="create-trip-btn create-trip-btn-more-padding">Skip This Section</a>
                                        <input name="profile_submit" id="profile_submit" type="submit" class="refresh-btn" value="Proceed">
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

        function initMap() {
            directionsService = new google.maps.DirectionsService();
            directionsDisplay = new google.maps.DirectionsRenderer({
                polylineOptions: {
                    strokeColor: "#F08A0D"
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
            <?php
            if ($trip->location_multi_waypoint_latlng) {
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
                new DrawPlaneRoutes(map, <?PHP echo $lat_from_plane; ?>, <?PHP echo $lng_from_plane; ?>, <?PHP echo $lat_to_plane; ?>, <?PHP echo $lng_to_plane; ?>, <?php echo $location_multi_waypoint_latlng; ?>, 'flight');
            <?php } ?>

            <?php
            if ($trip->trip_location_from_flightportion) {
                $lat_from_plane = $lat_from_flightportion;
                $lng_from_plane = $lng_from_flightportion;
                $lat_to_plane = $lat_to_flightportion;
                $lng_to_plane = $lng_to_flightportion;
            ?>
                new DrawPlaneRoutes(map, <?PHP echo $lat_from_plane; ?>, <?PHP echo $lng_from_plane; ?>, <?PHP echo $lat_to_plane; ?>, <?PHP echo $lng_to_plane; ?>, <?php echo $location_multi_waypoint_latlng; ?>, 'portion');
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
                new AutocompleteDirectionsHandler(map, 'driving', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, "A", "B");
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

                new AutocompleteDirectionsHandler(map, 'driving', <?PHP echo $lat_fromd; ?>, <?PHP echo $lng_fromd; ?>, <?PHP echo $lat_tod; ?>, <?PHP echo $lng_tod; ?>, "", "<?PHP echo $end_marker; ?>");
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
                new AutocompleteDirectionsHandler(map, 'train', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, "A", "B");
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
                new AutocompleteDirectionsHandler(map, 'train', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, "", "D");
            <?php } ?>

        }

        function AutocompleteDirectionsHandler(map, transport, lat_from, lng_from, lat_to, lng_to, marker_a, marker_b) {
            var waypoints = [];
            var location_multi_waypoint_latlng_string = '<?php echo $trip->location_multi_waypoint_latlng ?>';
            var via_waypoints = '<?php echo $trip->trip_via_waypoints ?>';
            via_waypoints = via_waypoints.length > 0 ? JSON.parse(via_waypoints) : [];
            // if (location_multi_waypoint_latlng_string) {
            var location_multi_waypoint_latlng = location_multi_waypoint_latlng_string ? JSON.parse(location_multi_waypoint_latlng_string) : [];
            for (var k = 0; k < location_multi_waypoint_latlng.length + 1; k++) {
                for (var kk = 0; kk < via_waypoints.length; kk++) {
                    if (k == via_waypoints[kk].index) {
                        var pt = new google.maps.LatLng(via_waypoints[kk].lat, via_waypoints[kk].lng);
                        waypoints.push({
                            location: pt,
                            stopover: false
                        });
                    }
                }
                if (k < location_multi_waypoint_latlng.length) {
                    var location = location_multi_waypoint_latlng[k].location_multi_waypoint_latlng;
                    waypoints.push({
                        location: location,
                        stopover: true
                    })
                }

            }
            // }
            var directionsDisplay1 = new google.maps.DirectionsRenderer({
                polylineOptions: {
                    strokeColor: "#F08A0D"
                }
            });
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
                directionsService.route(request, function(response, status) {
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

                        // }
                    }
                });
            }
            if (transport == 'train') {
                var request = {
                    origin: document.getElementById('<?PHP echo $train_location_from; ?>').value,
                    destination: document.getElementById('<?PHP echo $train_location_to; ?>').value,
                    travelMode: google.maps.DirectionsTravelMode.TRANSIT,
                    transitOptions: {
                        modes: [google.maps.TransitMode.TRAIN]
                    },
                    unitSystem: google.maps.UnitSystem.<?PHP echo $scale; ?>

                };
                directionsService.route(request, function(response, status) {
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
    </script>
    <script>
        $(window).on('load', function() {
            $('#export-modal').modal('show');
        });
        $(".close").click(function() {
            if ($("#export-modal").hasClass('modaltrans')) {
                $("#export-modal").removeClass('modaltrans');
                $("#export-modal-body").removeClass('modaltrans-body');
                $("#myLargeModalLabel").css({
                    fontSize: 21
                });
                $(this).html("-");
            } else {
                $("#export-modal").addClass('modaltrans');
                $("#export-modal-body").addClass('modaltrans-body');
                $("#myLargeModalLabel").css({
                    fontSize: 15
                });
                $(this).html("+");
            }
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

    <?php include('new_backend_footer.php'); ?>

</body>

</html>