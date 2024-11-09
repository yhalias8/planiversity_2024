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

    <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo SITE; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo SITE; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo SITE; ?>assets/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css">

    <link href="<?php echo SITE; ?>assets/css/app-style.css?v=20230621" rel="stylesheet" type="text/css" />

    <script src="<?php echo SITE; ?>assets/js/modernizr.min.js"></script>


    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?php echo SITE; ?>';
        var itinerary_type_mode = "<?= $trip->itinerary_type; ?>";
    </script>

    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <script src="<?php echo SITE; ?>js/global.js?v=203040"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>



    <?php //include('new_head_files.php'); 
    ?>
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

        .note-result-wrap p,
        .doc-item-wrapper div {
            margin-bottom: inherit;
            font-size: 14px;
            color: #058BEF;
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

        #update_note {
            z-index: 9999;
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
            display: flex;
            justify-content: space-around;
            align-items: end;
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
<div class="fullscreen-background"></div>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div>

        <?php include('new_backend_header.php'); ?>

        <div class="navbar-custom old-site-colors">
            <div class="container-fluid">
                <?php
                $step_index = "note";
                include('dashboard/include/itinerary-step.php');
                ?>
            </div>
        </div>
        </header>

        <div class="modal cmodal fade modal-blur" id="video_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header custom-modal-header">
                        <button type="button" class="close c-close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>

                    <div class="modal-body text-center">

                        <div class="modal-preview">

                            <video width="90%" height="auto" id="video" controls>
                                <source src="<?= SITE; ?>assets/images/home-page/How_to_Use_Notes.mp4" type="video/mp4">
                            </video>


                        </div>

                    </div>
                </div>
            </div>
        </div>



        <div id="note-modal" data-backdrop="false" class="modal fade bs-example-modal-lg master_modal custom_prefix_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div id="modal-dialog1" class="modal-dialog modal-custom-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
                <div class="modal-content connect-bg">
                    <div class="modal-header pl-0 px-4">
                        <!--data-dismiss="modal"-->
                        <div>
                            <p class="small-logo-title pt-0">PLANIVERSITY</p>
                            <h4 class="modal-title pl-0 pt-0 " id="myLargeModalLabel">Add Notes to your Trip Packet</h4>
                        </div>
                    </div>
                    <div class="modal-body connect-bg connect-bg-ground" id="note-modal-body">

                        <form id="notes_form" class="routemap">
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
                            <input name="trip_generated" id="trip_generated" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_generated; ?>" readonly>
                            <input name="trip_u_id" id="trip_u_id" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->user_id; ?>" readonly>
                            <input name="trip_title" id="trip_title" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_title; ?>" readonly>

                            <fieldset>
                                <div class="row">


                                    <div class="col-md-8">
                                        <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                                        <div class="form-group">
                                            <textarea style="padding:15px;" autofocus name="notes_text" id="notes_text" maxlength="500" cols="" class="dashboard-form-textarea-control input-lg" rows="6" placeholder="Add Note"></textarea>
                                        </div>
                                        <?php } ?>

                                        <div class="d-flex justify-content-between">
                                            <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                                            <div class="form-group py-4">
                                                <button type="submit" class="btn btn-primary create-trip-btn" id="notes_add"> Save Note</button>
                                            </div>

                                            <div class="form-group py-4">
                                                <div class="map_help" data-toggle="modal" data-target="#video_popup">
                                                    <p><i class="fa fa-info-circle" aria-hidden="true"></i> How to use notes</p>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>

                                        <div id="data_list"></div>
                                    </div>
                                    <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                                    <div class="col-md-4 col-lg-4 col-sm-12 action-nav">

                                        <div class="note-navigation">
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-sm-12 pt-3">
                                                    <a href="<?php echo SITE; ?>trip/plans/<?php echo $_GET['idtrip']; ?>" class="skip-note-btn mb mobile_skip">Back</a>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-sm-12 custom-full">
                                                    <div class="row action-row">
                                                        <div class="col-md-5 col-lg-5 pt-2 action-full left">
                                                            <a href="<?php echo SITE; ?>trip/resources/<?php echo $_GET['idtrip']; ?>" class="skip-note-btn mb mobile_skip">Skip This Section</a>
                                                        </div>
                                                        <div class="col-md-7 col-lg-7 mt-xs-0 action-full right">
                                                            <div class="form-group">
                                                                <a href="<?php echo SITE; ?>trip/resources/<?php echo $_GET['idtrip']; ?>" id="notes_submit" class="refresh-btn mt-3 mt-xs-0 mt-sm-0">Finished, Next Step</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <?php } ?>
                                </div>

                            </fieldset>
                            <br clear="all" />
                        </form>

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade modal-blur" data-backdrop="true" id="update_note" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Update Note</h4>
                    </div>
                    <form id="note_form_update">
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">

                                    <div class="form-group custom-group">
                                        <p class="event-title">Note Text</p>
                                        <textarea style="padding:15px;" autofocus name="notes_text" id="e_notes_text" maxlength="500" cols="" class="dashboard-form-textarea-control input-lg" rows="6" placeholder="Add Note"></textarea>

                                    </div>

                                </div>
                            </div>


                            <input type="hidden" id="item_id" name="note_id" readonly>

                        </div>
                        <div class="modal-footer">
                            <button type="update" id="cropImageBtn" class="btn btn-primary update_submit_button">Update</button>
                            <button type="button" class="btn btn-danger btn-close-modal" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <br clear="all" />


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

        <?php include('new_backend_footer.php'); ?>

        <script src="<?php echo SITE; ?>js/trip_notes_next.js?v=20230801"></script>

        <script>
            $(window).on('load', function() {
                $('#note-modal').modal('show');
            });

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>


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