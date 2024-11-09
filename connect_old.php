<?php
session_start();
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
    <title>PLANIVERSITY - ADD AN PEOPLE PROFILE</title>

    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?= SITE; ?>style/style.css?v=20230621" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>assets/css/app-style.css?v=20230621" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://localhost/master/stag/style/sweetalert.css" rel="stylesheet">

    <script src="<?= SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?php echo SITE; ?>';
        var itinerary_type_mode = "<?= $trip->itinerary_type; ?>";
        var idtrip = '<?php echo $_GET['idtrip']; ?>';
    </script>
    <script src="<?= SITE; ?>js/js_map.js"></script>
    <script src="<?= SITE; ?>js/global.js?v=203040"></script>
    <?php include('new_head_files.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>          
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

    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <?php
            $step_index = "connect";
            include('dashboard/include/itinerary-step.php');
            ?>
        </div>
    </div>
    </header>

    <div id="export-modal" data-backdrop="false" class="modal fade bs-example-modal-lg custom_prefix_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none">
        <div class="modal-dialog modal-custom-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
            <div class="modal-content connect-bg">
                <div class="modal-header pl-0 px-4">
                    <button type="button" class="close" aria-hidden="true">-</button>
                    <div>
                        <p class="small-logo-title pt-4">PLANIVERSITY</p>
                        <h4 class="modal-title pl-0 pt-0" id="myLargeModalLabel">Connect <span class="event-title pb-0"> ( Connect one or more profiles to your trip )</span></h4>
                    </div>
                </div>
                <div class="modal-body connect-bg-ground" id="export-modal-body">
                    <form id="profile_form" class="routemap">
                        <?php //include('include_icondetails.php')  
                        ?>
                        <div class="error_style"><?= $output; ?></div>
                        <input name="location_from" id="location_from" class="inp1" value="<?= $trip->trip_location_from; ?>" type="hidden">
                        <input name="idtrip" id="idtrip" class="inp1" value="<?php echo $_GET['idtrip']; ?>" type="hidden">
                        <input name="location_to" id="location_to" class="inp1" value="<?= $trip->trip_location_to; ?>" type="hidden">
                        <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
                        <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
                        <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
                        <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">
                        <input name="trip_location_from_trainportion" id="trip_location_from_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
                        <input name="trip_location_to_trainportion" id="trip_location_to_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">

                        <p class="event-title pb-0">People Profile </p>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="people_place">
                                        <div class="form-group people-field">
                                            <select autofocus name="profile_employee" id="profile_employee" class="dashboard-form-control input-lg">
                                                <option value="">Select People Profile</option>
                                                <?php
                                                $stmt = $dbh->prepare("SELECT * FROM employees as a,users as b WHERE a.employee_id=b.customer_number AND a.id_user=? ORDER BY f_name");
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

                                        <div class="form-group people-action">

                                            <button type="submit" class="btn btn-primary people-button">
                                                Go
                                            </button>

                                        </div>

                                    </div>

                                    <div class="border-none">

                                        <div class="skip_item_section no-background">
                                            <ul class="list-unstyled justify-content-between">

                                                <li>
                                                    <a href="<?= SITE; ?>trip/travel-documents/<?= $_GET['idtrip']; ?>" class="skipt_value">Back</a>
                                                </li>
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <a href="<?php echo SITE; ?>trip/name/<?php echo $_GET['idtrip']; ?>" class="skipt_value">Skip Section</a>
                                                    </li>
                                                    <li>
                                                        <!-- <a href="javascript:void(0)" id="btn-plan-submit" class="save_next_value">Save and Next</a> -->
                                                        <a href="<?php echo SITE; ?>trip/name/<?php echo $_GET['idtrip']; ?>" id="notes_submit" class="refresh-btn mt-3 mt-xs-0 mt-sm-0">Finished, Next Step</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </div>
                                    </div>


                                </div>

                                <div class="col-md-6">

                                    <div class="people_section">



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
    if (!empty($trip->trip_location_to_latlng_flightportion)) {
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
    
    
     $(function() {
            getPeopleList();
        });

        function getPeopleList() {


            var items = "";
            $.getJSON(SITE + "ajaxfiles/connect/list_processing.php", {
                id_trip: idtrip
            }, function(data) {
                $.each(data, function(index, item) {
                    let photo = SITE + "assets/images/user_profile.png";

                    if (item.photo) {
                        photo = SITE + "ajaxfiles/people/" + item.photo;
                    }

                    items += "<div class='people_row' id='people_" + item.id + "'><div class='people_left_side'><div class='people_img'><img src='" + photo + "'></div><div class='people_info'><h4>" + item.last_name + "</h4><p>Main Customer Profile</p></div></div><div class='people_right_side'><button id='delete' class='btn btn-mini btn-danger delete_action' title='Delete User' value='" + item.id + "'><i class='fa fa-trash'></i> </button></div></div>";
                });

                $(".people_section").html(items);

            });


        }


        $("#profile_form").validate({
            rules: {
                profile_employee: {
                    required: true,
                },
            },
            messages: {
                profile_employee: {
                    required: "Please select people profile",
                },
            },

            submitHandler: function(form) {
                $(".people-button").css("cursor", "wait");
                $(".people-button").attr("disabled", true);

                $.ajax({
                    url: SITE + "ajaxfiles/connect/data_processing.php",
                    type: "POST",
                    data: $(form).serialize(),
                    dataType: "json",
                    success: function(response) {
                        $(form).trigger("reset");

                        toastr.success('Successfully People Connected');

                        getPeopleList();

                        $(".people-button").css("cursor", "pointer");
                        $(".people-button").removeAttr("disabled");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                        toastr.error(jqXHR.responseJSON.message);
                        $(".people-button").css("cursor", "pointer");
                        $(".people-button").removeAttr("disabled");
                    },
                });
            },
        });

        $(document).on("click", "button#delete", function(event) {

            $(".delete_action").css("cursor", "wait");
            $(".delete_action").attr("disabled", true);

            var id = $(this).val();

            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: true
            }, function() {

                $.ajax({
                    type: "POST",
                    url: SITE + "ajaxfiles/connect/delete_connect.php",
                    data: {
                        "id": id,
                    },
                    dataType: 'json',
                    success: function(response) {

                        toastr.success(response.message);
                        $("#people_" + id).slideUp(150, function() {
                            $("#people_" + id).remove();
                        });
                        //$("#people_" + id).remove();

                        $(".delete_action").css("cursor", "pointer");
                        $(".delete_action").removeAttr("disabled");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error(jqXHR.responseJSON);
                        $(".delete_action").css("cursor", "pointer");
                        $(".delete_action").removeAttr("disabled");
                    }

                });



            });



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
        var location_multi_waypoint = <?= $location_multi_waypoint_latlng; ?>;

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
                bounds2.extend(new google.maps.LatLng(<?= $lat_from; ?>, <?= $lng_from; ?>));
                bounds2.extend(new google.maps.LatLng(<?= $lat_to; ?>, <?= $lng_to; ?>));
                new DrawPlaneRoutes(map, <?= $lat_from_plane; ?>, <?= $lng_from_plane; ?>, <?= $lat_to_plane; ?>, <?= $lng_to_plane; ?>, <?= $location_multi_waypoint_latlng; ?>, 'flight');
            <?php } ?>

            <?php
            if ($trip->trip_location_to_flightportion) {
                $lat_from_plane = $lat_from_flightportion;
                $lng_from_plane = $lng_from_flightportion;
                $lat_to_plane = $lat_to_flightportion;
                $lng_to_plane = $lng_to_flightportion;

            ?>
                bounds2.extend(new google.maps.LatLng(<?= $lat_to; ?>, <?= $lng_to; ?>));
                new DrawPlaneRoutes(map, <?= $lat_from_plane; ?>, <?= $lng_from_plane; ?>, <?= $lat_to_plane; ?>, <?= $lng_to_plane; ?>, <?= $location_multi_waypoint_latlng; ?>, 'portion');
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