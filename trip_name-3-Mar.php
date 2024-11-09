<?php
session_start();
include_once("config.ini.php");
include("class/class.TripPlan.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/name/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}

$output = '';
$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);

if (empty($id_trip))
    header("Location:" . SITE . "trip/how-are-you-traveling");
$trip->get_data($id_trip);

if ($trip->error) {
    if ($trip->error == 'error_access') {
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

if ($_GET['error'] && $_GET['error'] == 1)
    $output = 'A system error has been encountered. Please try again.';
if ($_GET['error'] && $_GET['error'] == 2)
    $output = 'The trip name is empty';

if (isset($_POST['name_submit'])) {
    $filter = $_POST['filter_option'];
    $embassis = $_POST['embassy_list'];
    // Edit data trip in DB
    $trip->edit_data_filter($id_trip, $filter, $embassis);
    $name = filter_var($_POST["name_title"], FILTER_SANITIZE_STRING);
    $trip->edit_data_name($id_trip, $name);
    if (!$trip->error) {
        include("class/class.Plan.php");
        $plan = new Plan();
        if ($plan->check_plan($userdata['id'])) { // If you have a plan export PDF
            header("Location:" . SITE . "trip/pdf/" . $id_trip);
        } else {
            header("Location:" . SITE . "billing/" . $id_trip);
        }
    } else
        $output = 'A system error has been encountered. Please try again.';
}

include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <title>PLANIVERSITY - ADD A TRIP NAME</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?= SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE ?>assets/css/dev-style.css" rel="stylesheet" type="text/css" />

    <script src="<?= SITE; ?>js/jquery-1.11.3.js"></script>
    <script src="<?= SITE; ?>js/js_map.js"></script>
    <script src="<?= SITE; ?>js/global.js"></script>
    <script>
        SITE = 'https://www.planiversity.com/';
    </script>
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

        .review-edit-export-btn,
        .review-edit-export-btn:focus {
            font-size: 14px;
            color: #084fa5;
            outline: none;
            background: #1c73b8;
            cursor: pointer;
            background: transparent;
            box-shadow: none;
            border-radius: 4px;
            padding: 12px 25px;
        }

        .create-trip-btn,
        .create-trip-btn:focus {
            padding: 12px 25px !important;
        }

        a.review-edit-export-btn {
            padding: 14px 20px;
            position: relative;
            top: 5px;
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

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include('new_backend_header.php'); ?>

    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu text-center plan-nav">
                    <li>
                        <a href="<?= SITE; ?>trip/create-timeline/<?= $_GET['idtrip']; ?>" class="left-nav-button scale" data-toggle="modal" data-target="#schedule-modal">
                            <!--<img src="<?= SITE; ?>assets/images/step_calendar.png" alt="Schedule">-->
                            <p class="main-color"><img class="mr-2" src="<?= SITE; ?>images/calendar_check.png" alt="Schedule">Schedule</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= SITE; ?>trip/plan-notes/<?= $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?= SITE; ?>assets/images/step_notes.png" alt="Notes">-->
                            <p class="main-color"><img class="mr-2" src="<?= SITE; ?>images/file_blank.png" alt="Schedule">Notes</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= SITE; ?>trip/filters/<?= $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?= SITE; ?>assets/images/step_filters.png" alt="Filters">-->
                            <p class="main-color"><img class="mr-2" src="<?= SITE; ?>images/slider_02.png" alt="Schedule">Filters</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= SITE; ?>trip/travel-documents/<?= $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?= SITE; ?>assets/images/step_documents.png" alt="Documents">-->
                            <p class="main-color"><img class="mr-2" src="<?= SITE; ?>images/folder_open.png" alt="Schedule">Documents</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?= SITE; ?>trip/connect/<?= $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?= SITE; ?>assets/images/step_connect_sources.png" alt="Connect">-->
                            <p class="main-color"><img class="mr-2" src="<?= SITE; ?>images/share_outline.png" alt="Schedule">Connect</p>
                        </a>
                    </li>
                    <li class="selected">
                        <a href="<?= SITE; ?>trip/name/<?= $_GET['idtrip']; ?>" class="left-nav-button">
                            <!--<img src="<?= SITE; ?>assets/images/step_pdf.png" alt="Export">-->
                            <p class="main-color"><img class="mr-2" src="<?= SITE; ?>images/download.png" alt="Schedule">Export</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    </header>


    <div id="proceed-to-pdf-modal" data-backdrop="false" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <form name="name_form" method="post" class="routemap" action="<?= SITE . "trip/pdf/" . $id_trip ?>">
            <!--<div class="error_style"><?= $output; ?></div>-->
            <input name="location_from" id="location_from" class="inp1" value="<?= $trip->trip_location_from; ?>" type="hidden">
            <input name="location_to" id="location_to" class="inp1" value="<?= $trip->trip_location_to; ?>" type="hidden">
            <input name="localhost_full_path" id="localhost_full_path" class="inp1" type="hidden" value="">
            <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
            <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
            <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
            <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">
            <input name="trip_location_from_trainportion" id="trip_location_from_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
            <input name="trip_location_to_trainportion" id="trip_location_to_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">

            <!--<input name="name_title" id="name_title" maxlength="90" type="text" value="<?= $trip->trip_title; ?>" placeholder="Trip Name" class="inp1">-->
            <!--<input style="background: #ec8728;border: 2px solid #ec8728;padding: 9px 38px;font-size: 17px;color: #ffffff;" name="name_submit" id="name_submit" type="submit" class="button align_r" value="PROCEED TO PDF">-->

            <div class="modal-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
                <div class="modal-content connect-bg">
                    <div class="modal-header pl-0 px-4">
                        <div>
                            <p class="small-logo-title pt-4">PLANIVERSITY</p>
                            <h4 class="modal-title pl-0 pt-0" id="myLargeModalLabel">Add a Trip Name</h4>
                        </div>
                    </div>
                    <div class="modal-body  connect-bg-ground" id="proceed-to-pdf-modal-body">
                        <div class="error_style"><?= $output; ?></div>
                        <p class="event-title pb-0">Trip Name</p>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <input name="name_title" id="name_title" maxlength="90" type="text" value="<?= $trip->trip_title; ?>" class="dashboard-form-control input-lg" placeholder="" required>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row flex-wrap pt-4">
                                <div class="form-group mr-3">
                                    <button type="button" class="review-edit-export-btn cancel-finished">Proceed</button>
                                    <input name="name_submit" id="name_submit" type="submit" style="color: #0d2972;" class="create-trip-btn" value="Proceed To PDF">

                                    <!-- <?php
                                            if ($userdata['account_type'] == 'Business') {
                                                include_once("class/class.Plan.php");
                                                $plan = new Plan();
                                                if ($plan->check_plan($userdata['id'])) {
                                            ?>
                                            <button type="button" class="review-edit-export-btn cancel-finished">Proceed</button>

                                            <input name="name_submit" id="name_submit" type="submit" style="color: #0d2972;" class="create-trip-btn" value="Proceed To PDF">
                                        <?php } else { ?>
                                            <button name="payment_submit" id="payment_submit" type="button" style="color: #0d2972;" class="create-trip-btn" data-toggle="modal" data-target="#myModal">Proceed To PDF</button>
                                        <?php
                                                }
                                            } else if ($userdata['account_type'] == 'Individual') {
                                                include_once("class/class.Plan.php");
                                                $plan = new Plan();
                                                if ($plan->individual_check_plan($userdata['id'])) {
                                        ?>
                                            <button type="button" class="review-edit-export-btn cancel-finished">Proceed</button>

                                            <input name="name_submit" id="name_submit" type="submit" style="color: #0d2972;" class="create-trip-btn" value="Proceed To PDF">
                                        <?php } else { ?>
                                            <button name="payment_submit" id="payment_submit" type="button" style="color: #0d2972;" class="create-trip-btn" data-toggle="modal" data-target="#myModal">Proceed To PDF</button>
                                        <?php
                                                }
                                            } else {
                                        ?>
                                        <input name="name_submit" id="name_submit" type="submit" class="create-trip-btn" value="Proceed To PDF">
                                    <?php } ?> -->
                                </div>
                                <div class="mt-2">
                                    <a href="javascript:void(0)" class="review-edit-export-btn" style="color: #f2a034; background: #0d2972;" data-toggle="modal" data-target="#editModal" data-backdrop="false">Review and Edit Export</a>
                                </div>
                            </div>
                            <?php
                            $xml = simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?latlng=" . $lat_to . "," . $lng_to . "&sensor=false&key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y") or die("Error: Cannot create object xml");
                            if ($xml->status == 'OK') {
                                foreach ($xml->result->address_component as $value) {
                                    if ($value->type == 'country') {
                                        $country_long_name = $value->long_name;
                                        $country_short_name = $value->short_name;
                                    }
                                }

                                $xml_Advisories = simplexml_load_file("https://travel.state.gov/_res/rss/TAsTWs.xml") or die("Error: Cannot create object xml");
                                if ($xml_Advisories) {
                                    echo $country_long_name . '(' . $country_short_name . ')<br>';
                                    $country_long_name = trim($country_long_name);
                                    foreach ($xml_Advisories->channel->item as $val) {
                                        $currtitle = $val->title;
                                        if (strstr($val->title, $country_long_name)) {
                                            $data_advisor = $val->title . '<br><br>';
                                            $data_advisor .= $val->pubDate . '<br><br>';
                                            $data_advisor .= '<a href="' . $val->link . '">' . $val->link . '</a><br><br>';
                                            $data_advisor .= $val->description . '<br>';
                                            echo $data_advisor;
                                        }
                                    }
                                }
                            }
                            ?>
                        </fieldset>
                    </div>
                </div>
            </div>

            <br clear="all" />
        </form>
    </div>

    <div id="myModal" class="modal fade show modal-custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-content">
                <div class="modal-header custom-modal-header">
                    <button id="modalclose" type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="checkbox checkbox-primary">
                        <p id="popupmess" class="mod-p">It looks like you don't have enough credit. Please submit a payment to continue the travel packet.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?= SITE; ?>billing/<?= $_GET['idtrip']; ?>" class="make-payment-btn">Make a Payment</a>
                </div>
            </div>
        </div>
    </div>


    <div id="editModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content custom-modal-content">
                <div class="modal-header custom-modal-header">
                    <div>
                        <p style="font-size: 12px;margin:0">PLANIVERSITY</p>
                        <p style="font-size: 24px;color: #1F74B7;margin:0;">Review</p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="padding-right: 20%;">
                    <div>
                        <p class="mod-p3">This will not be the final view of your export, but you can remove what information you don't need.</p>
                    </div>
                    <div class="all-doc-wrap slimscroll-itineraries" style="min-width: 540px">

                        <?php
                        // Passport
                        $stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
                        $stmt->bindValue(1, 'passport', PDO::PARAM_STR);
                        $stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
                        $tmp = $stmt->execute();
                        if ($tmp && $stmt->rowCount() > 0) { // add new page
                            $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $j = 0;
                            foreach ($documents as $document) { ?>
                                <div class="doc-item-wrapper doc-<?= $document->id_document; ?>">
                                    <!-- <div class="row"> -->
                                    <div class="col-md-1">
                                        <i class="fa fa-file fa-2x"></i>
                                    </div>
                                    <div class="col-md-7">
                                        <p class="p-mg-b p-mg-b-color"><?= $document->name; ?></p>
                                        <hidden type="hidden" value="<?= $document->name; ?>" />
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= SITE . 'trip/travel-documents/' . $_GET['idtrip'] ?>">
                                            <p class="p-mg-b">Destination</p>
                                        </a>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <button class="remove-item-btn" onclick="remDoc(<?= $document->id_document; ?>)"><i class="fa fa-trash"></i></button>
                                    </div>
                                    <!-- </div> -->
                                </div>

                            <?php }
                        }

                        // Driver's License
                        $stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND type=? AND td.id_trip=?");
                        $stmt->bindValue(1, 'driver', PDO::PARAM_STR);
                        $stmt->bindValue(2, $id_trip, PDO::PARAM_INT);
                        $tmp = $stmt->execute();
                        if ($tmp && $stmt->rowCount() > 0) { // add new page
                            $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $j = 0;
                            foreach ($documents as $document) { ?>
                                <div class="doc-item-wrapper doc-<?= $document->id_document; ?>">
                                    <!-- <div class="row"> -->
                                    <div class="col-md-1">
                                        <i class="fa fa-file fa-2x"></i>
                                    </div>
                                    <div class="col-md-7">
                                        <p class="p-mg-b p-mg-b-color"><?= $document->name; ?></p>
                                        <hidden type="hidden" value="<?= $document->name; ?>" />
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= SITE . 'trip/travel-documents/' . $_GET['idtrip'] ?>">
                                            <p class="p-mg-b">Destination</p>
                                        </a>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <button class="remove-item-btn" onclick="remDoc(<?= $document->id_document; ?>)"><i class="fa fa-trash"></i></button>
                                    </div>
                                    <!-- </div> -->
                                </div>

                        <?php }
                        }
                        ?>
                        <script>
                            function remDoc(id) {
                                if (confirm("Are you sure you want to delete this document?")) {
                                    $.ajax({
                                        cache: false,
                                        url: SITE + "ajaxfiles/del_doc_edit.php?table=documents&id=" + id,
                                        success: function(data) {
                                            alert(data);
                                            $(".doc-" + id).remove()
                                        }
                                    });
                                }
                            }
                        </script>
                        <?php
                        // Additional documents .... the additional condition was changed to all documents late!
                        $stmt = $dbh->prepare("SELECT * FROM documents as dc, `trips-docs` as td WHERE dc.id_document=td.id_document AND td.id_trip=?");
                        $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                        $tmp = $stmt->execute();
                        if ($tmp && $stmt->rowCount() > 0) { // add new page
                            $documents = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $j = 0;
                            foreach ($documents as $document) {
                        ?>
                                <div class="doc-item-wrapper doc-<?= $document->id_document; ?>" id="doc-<?= $document->id_document; ?>">
                                    <div class="col-md-1">
                                        <i class="fa fa-file-o fa-2x"></i>
                                    </div>
                                    <div class="col-md-7">
                                        <p class="p-mg-b p-mg-b-color"><?= $document->name; ?></p>
                                        <hidden type="hidden" value="<?= $document->name; ?>" />
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= SITE . 'trip/travel-documents/' . $_GET['idtrip'] ?>">
                                            <p class="p-mg-b">Destination</p>
                                        </a>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <button onclick="remDoc(<?= $document->id_document; ?>)" class="remove-item-btn"><i class="fa fa-times-circle"></i></button>
                                    </div>
                                </div>

                        <?php
                            }
                        }
                        ?>
                        <script>
                            function remDel(id) {
                                if (confirm("Are you sure you want to delete this Reminder?")) {
                                    $.ajax({
                                        cache: false,
                                        url: SITE + "ajaxfiles/del_doc_edit.php?table=timeline&id=" + id,
                                        success: function(data) {
                                            alert(data);
                                            $("#tm-" + id).remove()
                                        }
                                    });
                                }
                            }
                        </script>

                        <?php
                        $html = '';
                        $stmt = $dbh->prepare("SELECT * FROM timeline WHERE id_trip=? ORDER BY date");
                        $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                        $tmp = $stmt->execute();
                        if ($tmp && $stmt->rowCount() > 0) {
                            $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
                            foreach ($timelines as $timeline) {
                        ?>
                                <div class="doc-item-wrapper" id="tm-<?= $timeline->id_timeline; ?>">
                                    <div class="col-md-1">
                                        <i class="fa fa-calendar-o fa-2x"></i>
                                    </div>
                                    <div class="col-md-7">
                                        <p class="p-mg-b p-mg-b-color">Reminder - (<?= $timeline->title ?>) (<?= date('d F Y h:i a', strtotime($timeline->date)) ?>)</p>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= SITE . 'trip/create-timeline/' . $_GET['idtrip'] ?>">
                                            <p class="p-mg-b">Destination</p>
                                        </a>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <button onclick="remDel(<?= $timeline->id_timeline; ?>)" class="remove-item-btn"><i class="fa fa-times-circle"></i></button>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <script>
                            function noteDel(id) {
                                if (confirm("Are you sure you want to delete this note?")) {
                                    $.ajax({
                                        cache: false,
                                        url: SITE + "ajaxfiles/del_doc_edit.php?table=notes&id=" + id,
                                        success: function(data) {
                                            alert(data);
                                            $("#nt-" + id).remove()
                                        }
                                    });
                                }
                            }
                        </script>
                        <?php
                        // Notes 
                        $html = '';
                        $stmt = $dbh->prepare("SELECT * FROM notes WHERE id_trip=? ORDER BY date");
                        $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
                        $tmp = $stmt->execute();
                        if ($tmp && $stmt->rowCount() > 0) {
                            //$html = '';
                            //$html .= '<p align="center" style="color:#F1A033; font-size: 18px; font-family: OpenSansBold; letter-spacing:1px"><b>REMINDERS</b></p>';
                            $notes = $stmt->fetchAll(PDO::FETCH_OBJ);
                            foreach ($notes as $note) {
                        ?>

                                <div class="doc-item-wrapper" id="nt-<?= $note->id_note; ?>">
                                    <div class="col-md-1">
                                        <i class="fa fa-sticky-note-o fa-2x"></i>
                                    </div>
                                    <div class="col-md-7">
                                        <p class="p-mg-b p-mg-b-color">Note - <?= $note->text; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="<?= SITE . 'trip/plan-notes/' . $_GET['idtrip'] ?>">
                                            <p class="p-mg-b">Destination</p>
                                        </a>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <button onclick="noteDel(<?= $note->id_note; ?>)" class="remove-item-btn"><i class="fa fa-times-circle"></i></button>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>

                    </div>
                    <div class="text-left" style="float: left;">
                        <button data-dismiss="modal" class="review-edit-export-btn cancel-finished">Cancel</button>
                    </div>
                    <div class="text-right">
                        <button onclick="frmSubmit()" type="submit" class="review-edit-export-btn cancel-finished">Proceed</button>
                        <button onclick="frmSubmit()" type="submit" style="color: #f2a034; background: #0d2972;" class="create-trip-btn finished-export">Proceed to export</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function frmSubmit() {
            $('#editModal').modal('toggle');
            if ($("#name_title").val() != '') {
                $("form[name='name_form']").submit();
            } else {
                alert("The trip name is empty");
            }
        }
    </script>

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


            ?>
                bounds2.extend(new google.maps.LatLng(<?= $lat_tod; ?>, <?= $lng_tod; ?>));
                new AutocompleteDirectionsHandler(map, 'driving', <?= $lat_fromd; ?>, <?= $lng_fromd; ?>, <?= $lat_tod; ?>, <?= $lng_tod; ?>, <?= $location_multi_waypoint_latlng; ?>, <?= $trip_via_waypoints ?>, true, "<?= $end_marker; ?>");
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
        $(window).on('load', function() {
            $('#proceed-to-pdf-modal').modal('show');
        });
        $("#modalmin").click(function() {
            if ($("#proceed-to-pdf-modal").hasClass('modaltrans')) {
                $("#proceed-to-pdf-modal").removeClass('modaltrans');
                $("#proceed-to-pdf-modal-body").removeClass('modaltrans-body');
                $("#myLargeModalLabel").css({
                    fontSize: 21
                });
                $(this).html("-");
            } else {
                $("#proceed-to-pdf-modal").addClass('modaltrans');
                $("#proceed-to-pdf-modal-body").addClass('modaltrans-body');
                $("#myLargeModalLabel").css({
                    fontSize: 15
                });
                $(this).html("+");
            }
        });
        $("#modalclose,.dismiss-btn").click(function() {
            $("#myModal").modal('hide');
        });
        $('.modal-backdrop').removeClass("modal-backdrop");
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>
    <?php include('new_backend_footer.php'); ?>
</body>

</html>