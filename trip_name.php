<?php

session_start();

include_once("config.ini.php");

include_once("config.ini.curl.php");

include("class/class.TripPlan.php");

include_once("class/class.Plan.php");

$plan = new Plan();



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



if (isset($_POST['name_submit'])) {



    $name = filter_var($_POST["name_title"], FILTER_SANITIZE_STRING);

    $trip->edit_data_name($id_trip, $name);

    if (!$trip->error) {

        header("Location:" . SITE . "trip/pdf/" . $id_trip);
    } else

        $output = 'A system error has been encountered. Please try again.';
}



if ($userdata['account_type'] == 'Individual') {

    $user_payment_status = $plan->individual_check_plan($userdata['id']);
} else {

    $user_payment_status = $plan->check_plan($userdata['id']);
}





if ($trip->itinerary_type == "event") {

    $type = "trip_pdf_code_curl_tc_event.php";
} else {

    $type = "trip_pdf_code_curl_tc_trip.php";
}



$cover_image_update =  $trip->cover_image ? 'updated' : '';

include('include_doctype.php');

?>

<html>



<head>

    <meta charset="utf-8">

    <title>PLANIVERSITY - GIVE YOUR PLAN TITLE</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">

    <meta name="keywords" content="Consolidated Travel Information Management">

    <meta name="author" content="">

    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">



    <link href="<?php echo SITE; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo SITE; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo SITE; ?>assets/css/app-style.css?v=2090920" rel="stylesheet" type="text/css" />



    <link href="<?= SITE; ?>style/style.css?v=2090920" rel="stylesheet" type="text/css" />

    <link href="<?= SITE ?>assets/css/dev-style.css" rel="stylesheet" type="text/css" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">



    <script src="<?= SITE ?>dashboard/js/jquery-3.6.0.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>



    <script src="<?= SITE; ?>js/js_map.js"></script>

    <script src="<?= SITE; ?>js/global.js"></script>



    <script>
        var SITE = "<?= SITE ?>";

        var itinerary_type_mode = "<?= $trip->itinerary_type; ?>";

        var process_id = <?= $_GET["idtrip"] ?>;

        var default_cover = "assets/images/export_preview.jpg";
    </script>



    <?php

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
            padding: 14px 25px;
        }



        .create-trip-btn,
        .create-trip-btn:focus {
            padding: 12px 25px !important;
        }


        a.review-edit-export-btn {
            padding: 12px 25px !important
        }

        table {
            width: 100%;
        }

        .img-box {
            width: 100%;
            text-align: center;
        }



        .back-button {
            position: relative;
            top: 12px;
            /* margin-right: 70px; */
        }



        .action-section {
            justify-content: space-between;
        }

        .right-action {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
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

        .your_plan_item_list .your_plan_item_edit {
            display: flex;
        }


        button.btn.action_button {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-block;
            margin-right: 2px;
            padding: 10px;
        }



        button.btn.action_button i {
            text-align: center;
            font-size: 12px;
            display: flex;
        }



        button.btn.edit.action_button {
            background: #058bef;
            color: #fff;
            border-color: #f1f6fa;
        }



        button.btn.delete.action_button {
            background: #ff0023;
            border-color: #f1f6fa;
        }

        button.btn.edit.action_button:disabled {
            background: #bdbdbd;
            opacity: .95;
        }


        .skip_item_section {
            background-color: #f5f9fc;
        }


        div#upgrade {
            z-index: 99999;
        }

        #editModal {
            overflow: scroll;
        }



        div#preview_packet,
        div#api_modal,
        div#update_note,
        div#update_schedule {
            z-index: 99990;
            top: 5px;
        }



        .modal-content {
            background: #fff;
        }

        .modal {
            margin-bottom: 0;
            /* top: 80px; */
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

        .export_preview {
            text-align: center;
            margin-bottom: 10px;
        }



        .export_preview .preview {
            width: 200px;
        }



        .export_preview p {
            color: #fff;
            font-size: 14px;
            margin-top: 10px;
            cursor: pointer;
        }



        .item_preview {
            width: 100%;
            min-height: 550px;
            height: 550px;
            background: #fff;
        }



        .modal-content.custom-content {
            background: transparent;
            border: none;
            box-shadow: none;
        }



        .modal .modal-dialog .c-close {
            color: #fff;
            font-size: 48px;
            height: 52px;
            width: 48px;
            right: 0px;
        }



        #myframe {
            width: 100%;
            height: 100%
        }


        .loading {
            left: 0;
            position: absolute;
            top: 0;
            height: 100%;
            width: 100%;
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .review-wrapper {
            display: flex;
            justify-content: space-between;
            background: #FFFFFF;
            padding: 20px;
            margin-bottom: 5px;
        }

        .review-content {
            display: inline-flex;
            position: relative;
            top: 10px;
        }

        .review-content i {
            font-size: 18px;
        }

        .review-content p {
            font-size: 14px;
            padding-left: 10px;
            margin: 0 !important;
            line-height: 17px;
        }

        .review-action {
            min-width: 100px;
        }

        button.btn.review-btn {
            padding: 6px 16px;
            border: navajowhite;
            border-radius: 50px;
        }

        button.btn.review-btn i {
            font-size: 14px;
        }

        .review-edit {
            background: #D2E3F1;
            color: #0C246B;
        }


        .review-delete {
            background: #FDECD6;
            color: #F3732A;
        }

        .export_action {
            text-align: center;
        }

        .export_action button {
            color: #0C266D;
        }

        div#review_list h2 {
            text-align: center;
            font-size: 22px;
            margin-top: 50px;
        }

        .loading_screen {
            position: absolute;
            text-align: center;
            left: 50%;
            top: 30%;
            z-index: 99;
        }

        .loading_screen i {
            font-size: 80px;
            color: #0886e3;
        }

        .fancylight.popup-btn img {
            min-height: 210px;
            max-height: 210px;
            width: 100%;
        }

        .background_search,
        .backgound_search:focus {

            padding: 6px 50px;
        }

        .background_search:hover {
            padding: 6px 50px;
        }

        .search_place {
            display: flex;
            width: 500px;
            margin-bottom: 20px;
        }

        .search_btn {
            position: relative;
            right: 10px;
        }

        input#keyword {
            outline: 0 !important;
            /* border: 0 !important; */
            box-shadow: 0 1px 1px 0 rgb(45 44 44 / 5%) !important;
            background: #ffffff !important;
            border: 1px solid #C8CCD5;
            border-radius: 5px;
            border-right: 0;
        }

        div#search_result {
            min-height: 400px;
        }

        nav.justify-content-center.load_more {
            margin-right: 0px;
            margin-bottom: 30px;
            margin-top: 15px;
        }

        button.page-link.no-border:disabled {
            color: #d1d1d1;
            opacity: 1;
            cursor: not-allowed;
        }

        .no-data {
            text-align: center;
            width: 100%;
            margin: 0 auto;
            font-size: 30px;
            font-weight: 300;
            margin-top: 100px;
        }

        button.close.x-close {
            color: #fff !important;
            font-size: 48px !important;
            height: 52px !important;
            width: 48px !important;
            right: -20px !important;
        }

        label.error {
            font-size: 12px;
            color: red !important;
        }

        img.bottom-image {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40%;
            display: none;
        }

        .content.updated .bottom-image {
            display: block;
        }

        img.top-image {
            position: absolute;
            top: 0;
            right: 0;
            width: 20%;
            display: none;
        }

        .content.updated .top-image {
            display: block;
        }

        a.fancylight.popup-btn:hover {
            opacity: 0.8;
        }

        #defaultCoverProcess {
            display: none;
        }

        .content.updated #defaultCoverProcess {
            display: block;
        }

        a.fancylight.popup-btn:active {
            opacity: 0.4;
        }

        p.overlaytext {
            position: absolute;
            left: 20px;
            font-size: 14px;
            top: 30px;
            padding: 5px 15px;
            border-radius: 5px;
            margin: 0;
            display: none;
        }

        .content.updated p.overlaytext {
            display: block;
        }

        p.overlayTitle {
            position: absolute;
            left: 20px;
            font-size: 24px;
            top: 60px;
            padding: 5px 15px;
            border-radius: 5px;
            margin: 0;
            display: none;
        }



        .content.updated p.overlayTitle {
            display: block;
        }

        button.reload-btn {
            font-size: 12px;
            color: #ffffff;
            cursor: pointer;
            background: transparent;
            box-shadow: none;
            border-radius: 4px;
            padding: 10px 15px;
            margin-top: 15px;
            border: 1px solid #ffffff;
        }

        #preview_form_upload {
            min-height: 400px;
        }

        button.upload_preview_image {
            background: linear-gradient(#fac85c, #f5ab3f);
            color: #000;
            border: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            padding: 13px 20px;
            border-radius: 6px;
            width: 200px;
            display: block;
            text-align: center;
        }

        #upload_preview_image {
            display: block;
            margin: 0 auto;
            width: 400px;
        }


        button.upload_preview_image {
            text-align: center;
            margin: 0 auto;
            margin-top: 30px;
        }

        .progress {
            width: 450px;
            margin: 0 auto;
            margin-top: 30px;
        }



        @media (max-width: 1399px) {
            .right-action {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
            }

        }

        @media only screen and (max-width: 467px) {
            .modal-preview p {
                font-size: 18px;
            }

            .promo-section {
                margin-top: 40px;
                margin-bottom: 50px;
            }

        }
    </style>

    <!-- Global site tag (gtag.js) - Google Analytics -->

    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146873572-1"></script> -->

    <!-- <script>

        window.dataLayer = window.dataLayer || [];



        function gtag() {

            dataLayer.push(arguments);

        }

        gtag('js', new Date());



        gtag('config', 'UA-146873572-1');

    </script> -->

    <!-- Google Tag Manager -->

    <!-- <script>

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

    </script> -->

    <!-- End Google Tag Manager -->

</head>



<body>
<div class="fullscreen-background"></div>
    <!-- Google Tag Manager (noscript) -->

    <!-- <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> -->

    <!-- End Google Tag Manager (noscript) -->

    <?php include('new_backend_header.php'); ?>



    <div class="navbar-custom old-site-colors">

        <div class="container-fluid">

            <?php

            $step_index = "name";

            include('dashboard/include/itinerary-step.php');

            ?>

        </div>

    </div>

    </header>



    <div id="proceed-to-pdf-modal" data-backdrop="false" class="modal fade bs-example-modal-lg master_modal custom_prefix_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">

        <form id="name_form" class="routemap">

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



            <div class="modal-dialog modal-custom-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">

                <div class="modal-content connect-bg">

                    <div class="modal-header pl-0 px-4">

                        <div>

                            <p class="small-logo-title pt-4">PLANIVERSITY</p>

                            <h4 class="modal-title pl-0 pt-0" id="myLargeModalLabel">Give Your Plan a Title</h4>

                        </div>

                    </div>

                    <div class="modal-body  connect-bg-ground" id="proceed-to-pdf-modal-body">

                        <div class="error_style"><?= $output; ?></div>

                        <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                        <p class="event-title pb-0">Plan Title</p>
                        <?php } ?>

                        <fieldset>

                            <div class="row">

                                <div class="col-md-6 col-sm-12">
                                    <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>

                                    <div class="form-group">

                                        <input name="name_title" id="name_title" maxlength="90" type="text" value="<?= $trip->trip_title; ?>" class="dashboard-form-control input-lg" placeholder="" required>

                                    </div>
                                    <?php } ?>




                                    <div class="d-flex flex-row flex-wrap pt-4 action-section">


                                        <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>

                                        <div class="back-button">

                                            <a href="<?= SITE; ?>trip/connect/<?= $_GET['idtrip']; ?>" class="skip-note-btn mobile_skip">Back</a>

                                            <!-- <button type="button" class="btn btn-primary" id="preview_process">Process</button> -->

                                        </div>



                                        <div class="right-action">

                                            <a href="javascript:void(0)" class="review-edit-export-btn" style="color: #f2a034; background: #0d2972;" data-toggle="modal" data-target="#editModal" data-backdrop="false">Review, edit and personalize</a>


                                            <div>
                                                <button name="name_submit" id="name_submit" type="submit" style="color: #0d2972;" class="create-trip-btn" value="Proceed To PDF">Finished, Export</button>
                                            </div>

                                        </div>

                                        <?php } ?>



                                    </div>





                                </div>



                                <div class="col-md-6 col-sm-12 promo-section">



                                    <script src="https://c153.travelpayouts.com/content?promo_id=4652&shmarker=311162.12&trs=171390&category=1&viewport=2" charset="utf-8"></script>



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



    <div class="modal fade modal-blur" id="upgrade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">

        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">

                <div class="modal-body text-center">



                    <div class="modal-preview">



                        <img src="<?= SITE; ?>images/upgrade.png" class="img-responsive" />



                        <p>To export this packet, you will <span>have to upgrade your plan</span></p>



                        <a href="<?= SITE ?>billing/<?= $_GET['idtrip']; ?>" class="upgrade-now mb-4">

                            <button class="refresh-btn mt-3 mt-xs-0 mt-sm-0">Upgrade Now</button>

                        </a>



                        <!-- <a href="<?= SITE . 'trip/travel-documents/' . $_GET['idtrip'] ?>" class="skip-process">

                            Skip, Next Step

                        </a> -->



                    </div>



                </div>

            </div>

        </div>

    </div>





    <div class="modal fade modal-blur" data-backdrop="true" id="update_schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h4 class="modal-title" id="myModalLabel">Update Schedule</h4>

                </div>

                <form id="timeline_form_update">

                    <div class="modal-body">



                        <div class="row">

                            <div class="col-md-12 col-lg-12 col-sm-12">



                                <div class="form-group custom-group">

                                    <p class="event-title">Event Title</p>

                                    <input name="timeline_name" id="e_timeline_name" type="text" maxlength="150" class="timeline-input update_item" placeholder="Add New Event" class="dashboard-form-control input-lg">

                                </div>



                            </div>

                        </div>



                        <div class="row">

                            <div class="col-md-12 col-lg-12 col-sm-12">



                                <div class="form-group custom-group">

                                    <p class="event-title">Event Date</p>

                                    <input type="text" id="e_timeline_date" name="timeline_date" class="form-control datepicker">

                                </div>



                            </div>



                        </div>





                        <div class="row">

                            <div class="col-md-12 col-lg-12 col-sm-12">



                                <div class="form-group custom-group">

                                    <p class="event-title">Event Time</p>

                                    <div class="time-input">

                                        <input type="time" id="e_timeline_time" name="timeline_time" class="form-control timer-input update_item" tabindex="53" placeholder="1 : 30 AM">

                                    </div>

                                </div>



                            </div>



                        </div>



                        <input type="hidden" id="item_id" name="item_id" readonly>



                    </div>

                    <div class="modal-footer">

                        <button type="update" id="cropImageBtn" class="btn btn-primary update_submit_button">Update</button>

                        <button type="button" class="btn btn-danger btn-close-modal" data-dismiss="modal">Cancel</button>

                    </div>

                </form>



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



                        <input type="hidden" id="note_id" name="note_id" readonly>



                    </div>

                    <div class="modal-footer">

                        <button type="update" id="cropImageBtn" class="btn btn-primary update_submit_button">Update</button>

                        <button type="button" class="btn btn-danger btn-close-modal" data-dismiss="modal">Cancel</button>

                    </div>

                </form>



            </div>

        </div>

    </div>







    <div class="modal modal-blur" id="preview_packet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">

        <div class="modal-dialog modal-md" role="document">

            <div class="modal-content custom-content">



                <div class="modal-header custom-modal-header">

                    <button type="button" class="close c-close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>



                <div class="modal-body text-center">



                    <div class="modal-preview">



                        <div class="item_preview">



                            <div class="loading" id="loading">



                                <p><i class='fa fa-spinner fa-spin '></i>

                                    Loading

                                </p>

                            </div>

                            <iframe id="myframe" src="" style="display:none"></iframe>

                            <div class="preview-action">

                                <button class="reload-btn" id="reload-btn" style="display:none;">Reload Preview</button>

                            </div>

                        </div>



                    </div>



                </div>

            </div>

        </div>

    </div>





    <div class="modal modal-blur" id="api_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">

        <div class="modal-dialog review-modal-lg" role="document">

            <div class="modal-content">



                <div class="modal-header custom-modal-header">

                    <button type="button" class="close x-close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>



                <div class="modal-body text-center">





                    <div class="imageview_warp">





                        <div class="container">



                            <ul id="tabs" class="ml-0 nav nav-tabs">

                                <li class="nav-item"><a href="" data-target="#oneTab" data-toggle="tab" class="nav-link big text-uppercase active">Unsplash Image</a></li>

                                <li class="nav-item"><a href="" data-target="#twoTab" data-toggle="tab" class="nav-link big text-uppercase ">Upload Image</a></li>

                            </ul>





                            <div id="tabsContent" class="tab-content">



                                <div id="oneTab" class="tab-pane active">



                                    <div class="row mt-3">

                                        <div class="col-lg-12 text-center my-2">



                                            <div class="row">

                                                <div class="col-xl-12 col-12">

                                                    <div class="search_place">

                                                        <input type="text" id="keyword" class="form-control" placeholder="Search by Keyword">

                                                        <div class="search_btn">

                                                            <button type="button" id="keyword-button" class="btn btn-primary finish-next-btn background_search"><i class="fa fa-search"></i></button>

                                                        </div>

                                                    </div>

                                                </div>



                                            </div>

                                        </div>

                                    </div>





                                    <div class="loading_screen" style="display: none;">

                                        <i class="fa fa-spinner fa-spin"></i>

                                    </div>



                                    <div class="portfolio-item row" id="search_result">



                                    </div>



                                    <nav class="justify-content-center load_more" style="display: none;">

                                        <ul class="pagination pagination-base pagination-boxed pagination-square mb-0">

                                            <li class="page-item">

                                                <button class="page-link no-border target_action" value="null" id="previous_button" data-type="before" disabled="disabled">

                                                    <span aria-hidden="true">Previous</span>

                                                </button>

                                            </li>



                                            <li class="page-item">

                                                <button class="page-link no-border target_action" value="null" id="next_button" data-type="after">

                                                    <span aria-hidden="true">Next</span>

                                                </button>

                                            </li>

                                        </ul>

                                    </nav>



                                </div>



                                <div id="twoTab" class="tab-pane">



                                    <form id="preview_form_upload" method="post" enctype="multipart/form-data">



                                        <div class="row">

                                            <div class="col-md-12 col-lg-12 col-sm-12">



                                                <div class="form-group custom-group mt-5">

                                                    <p class="event-title"><b>Recommended image size : </b>Landscape (1080 x 1350 pixels)</p>

                                                    <input type="file" name="upload_preview_image" id="upload_preview_image" value="Choose a file" accept="image/png, image/gif, image/jpeg">

                                                </div>



                                            </div>

                                        </div>





                                        <div id="upload_own_image">

                                            <button class="upload_preview_image"> Upload Image </button>

                                        </div>



                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="progress" style="display:none;">

                                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;">

                                                    </div>

                                                </div>

                                            </div>

                                        </div>



                                    </form>







                                </div>

                            </div>





                        </div>









                    </div>



                    <input type="hidden" name="api_proceced" id="api_proceced" readonly value="0">

                    <input type="hidden" name="api_query" id="api_query" readonly value="">



                </div>

            </div>

        </div>

    </div>





    <div id="editModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

        <div class="modal-dialog review-modal-lg">

            <div class="modal-content custom-modal-content">

                <div class="modal-header custom-modal-header">

                    <div>

                        <p style="font-size: 12px;margin:0">PLANIVERSITY</p>

                        <p style="font-size: 24px;color: #1F74B7;margin:0;">Review</p>

                    </div>

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <div class="modal-body">



                    <div class="row align-self-center">

                        <div class="col-xl-8 col-10">



                            <div>

                                <p class="mod-p3">This will not be the final view of your export, You can edit or delete what information you choose to here..</p>

                            </div>



                            <div class="all-doc-wrap slimscroll-itineraries" id="review_list"></div>





                        </div>





                        <div class=" col-xl-4 col-10">



                            <div class="export_preview">

                                <div class="content <?= $cover_image_update; ?>">

                                    <a href="javascript:void(0)">

                                        <div class="content-overlay"></div>

                                        <img class="content-image" id="main_preview" src="<?php echo $trip->cover_image ? $trip->cover_image_url : SITE . "assets/images/export_preview.jpg" ?>">

                                        <p class="overlaytext">&nbsp;</p>

                                        <p class="overlayTitle"><?= $trip->trip_title; ?></p>

                                        <div class="content-details fadeIn-bottom">


                                            <p class="content-text" id="unsplashApiCall" data-toggle="modal" data-target="#api_modal"><i class="fa fa-picture-o"></i> Change Cover Image</p>

                                            <p class="content-text" id="defaultCoverProcess"><i class="fa fa-undo"></i> Default Cover Image</p>

                                        </div>

                                    </a>

                                </div>



                            </div>



                            <div class="export_action">

                                <button data-dismiss="modal" type="buttton" class="create-trip-btn">Save and Close, Proceed to Export</button>

                            </div>



                        </div>



                    </div>





                    <!-- process -->





                    <input type="hidden" name="preview_proceced" id="preview_proceced" readonly value="0">



                    <div class="d-flex justify-content-between">



                    </div>

                </div>

                <div class="modal-footer">

                    <!--<button type="button" data-dismiss="modal" class="dismiss-btn">Cancel</button>-->

                </div>

            </div>

        </div>

    </div>





    <?php include('new_backend_footer.php'); ?>



    <script>
        function frmSubmit() {

            $('#editModal').modal('hide');

            if ($("#name_title").val() != '') {

                $("form[name='name_form']").submit();

            } else {

                alert("The trip name is empty");

            }

        }
    </script>



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

    <script>
        var user_payment_status = <?= $user_payment_status ?>



        $("#name_form").validate({

            rules: {

                name_title: {

                    required: true,

                }

            },

            messages: {



                name_title: {

                    required: 'Please type your trip title'

                },

            },





            submitHandler: function(form) {





                $('#name_submit').css('cursor', 'wait');

                $('#name_submit').attr('disabled', true);



                $.ajax({

                    url: SITE + "ajaxfiles/build/update_trip_info.php",

                    type: "POST",

                    data: $(form).serialize() + '&id=' + process_id,

                    dataType: 'json',

                    success: function(response) {



                        window.location.replace(SITE + "trip/pdf/" + process_id);



                    },

                    error: function(jqXHR, textStatus, errorThrown) {



                        $('#upgrade').modal('show');

                        $('#name_submit').css('cursor', 'pointer');

                        $('#name_submit').removeAttr('disabled');



                    }





                });









            }, // Do not change code below

            errorPlacement: function(error, element) {

                error.insertAfter(element.parent());

            }





        });




        let returnValues = [];





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

        //$('.modal-backdrop').removeClass("modal-backdrop");









        $('#previewGenerate').click(function() {





            var preview_proceced = $('#preview_proceced').val();



            if (preview_proceced == 0) {

                console.log('Processing...');

                var process_no = process_id;

                var file_load = "<?= $type ?>";

                var access_id = <?= $userdata['id'] ?>;

                getPreviewProcess(process_no, file_load, access_id);

            }





        });





        $('#preview_process').click(function() {

            var process_no = process_id;

            getPreviewProcess(process_no);

        });





        $(function() {

            reviewListProcess();

        });



        $('#reload-btn').click(function() {

            var preview_proceced = $('#preview_proceced').val();



            if (preview_proceced == 2) {

                console.log('Processing reload inside ...');

                $('#loading').show();

                $('#myframe').hide();

                $(this).hide();

                $('#myframe')[0].contentWindow.location.reload(true);

                $('#preview_proceced').val(1);



            }

        });







        function getPreviewProcess(id, file_load, access_id) {



            $('#preview_proceced').val(1);

            var name_title_hole = $('#name_title').val();



            var fullpath = SITE + "preview/" + file_load + "?idtrip=" + id + "&uid=" + access_id + "&title=" + name_title_hole;

            $('#myframe').attr("src", fullpath + "#toolbar=0&zoom=FitH");



            const iframeEle = document.getElementById('myframe');

            const loadingEle = document.getElementById('loading');



            iframeEle.addEventListener('load', function() {

                // Hide the loading indicator

                iframeEle.style.display = 'block';

                loadingEle.style.display = 'none';

                // Bring the iframe back

                iframeEle.style.opacity = 1;

            });



        }



        window.frames["myframe"].contentDocument.oncontextmenu = function() {

            return false;

        };







        function reviewListProcess() {



            var process_no = process_id;

            var type = "list";





            if (process_no && type) {





                var dataSet = 'process_no=' + process_no + '&type=' + type;



                $.ajax({

                    url: SITE + "ajaxfiles/review/process.php",

                    type: "POST",

                    data: dataSet,

                    dataType: 'json',

                    success: function(response) {
                        $('#review_list').html(response.data.review_list);

                        notfound();
                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#review_list').html("<h2>A system error has been encountered. Please try again</h2>");
                    }



                });



            }





        }





        function notfound() {



            if (!$.trim($('#review_list').html()).length) {

                $('#review_list').html("<h2> <i class='fa fa-info-circle'></i> Nothing is found </h2>");

            }



        }





        function del_element(id, ref_type) {





            swal({

                title: "Are you sure?",

                type: "warning",

                showCancelButton: true,

                confirmButtonColor: "#DD6B55",

                confirmButtonText: "Yes, delete it!",

                closeOnConfirm: true

            }, function() {



                $('.review-btn').css('cursor', 'not-allowed');

                $('.review-btn').attr('disabled', true);



                var dataSet = 'id=' + id + '&ref_type=' + ref_type;



                $.ajax({

                    type: "POST",

                    url: SITE + "ajaxfiles/review/delete_process.php",

                    data: dataSet,

                    dataType: 'json',

                    success: function(response) {

                        toastr.success(response.message);

                        $("#" + ref_type + "_" + +id + "").remove();

                        $('.review-btn').css('cursor', 'pointer');

                        $('.review-btn').removeAttr('disabled');

                        notfound();



                    },

                    error: function(jqXHR, textStatus, errorThrown) {

                        toastr.error(jqXHR.responseJSON);

                        $('.review-btn').css('cursor', 'pointer');

                        $('.review-btn').removeAttr('disabled');

                    }



                });







            });







        }









        $('#unsplashApiCall').click(function() {



            var api_proceced = $('#api_proceced').val();



            if (api_proceced == 0) {

                unsplashInitialProcess();

            }



        });





        function unsplashInitialProcess() {

            var dataSet = 'request_id=' + "planiversity" + '&type=' + "travel" + '&keyword=' + "travel" + '&page=' + "1";

            upsplashAjaxProcess(dataSet);

        }



        $(document).on("click", ".target_action", function(event) {

            var type = $(this).data("type");

            var targetValue = $(this).val();

            var queryString = $("#api_query").val();

            var dataSet = 'request_id=' + "planiversity" + '&type=' + "travel" + '&keyword=' + queryString + '&page=' + targetValue;

            upsplashAjaxProcess(dataSet);

        });





        $(document).on("click", "#keyword-button", function(event) {

            var queryString = $('#keyword').val();

            var dataSet = 'request_id=' + "planiversity" + '&type=' + "travel" + '&keyword=' + queryString + '&page=' + "1";

            if (queryString != "") {

                upsplashAjaxProcess(dataSet);

            }

        });



        $("#keyword").keyup(function(event) {

            if (event.keyCode == 13) {

                $("#keyword-button").click();

            }

        });



        function upsplashAjaxProcess(dataSet) {

            $("#search_result").html("");

            $(".loading_screen").show();

            $(".load_more").hide();

            $(".target_action").attr("disabled", false);

            $(".background_search").attr("disabled", true);



            $.ajax({

                url: SITE + "process/unsplash/apiProcessing.php",

                type: "GET",

                data: dataSet,

                dataType: "json",

                success: function(response) {



                    $(".loading_screen").hide();

                    $("#search_result").html(response.data.responseList);

                    next_page = response.data.next_page;

                    previous_page = response.data.previous_page;

                    request_query = response.data.request_query;

                    returnValues = [...response.data.return_values];



                    $("#next_button").val(response.data.next_page);

                    $("#previous_button").val(response.data.previous_page);



                    if (response.data.responseList) {

                        $("#api_proceced").val(1);

                        $("#api_query").val(request_query);

                    }



                    if (next_page || previous_page) {

                        $(".load_more").show();

                    }



                    if (next_page == null) {

                        $("#next_button").attr("disabled", true);

                    }



                    if (previous_page == null) {

                        $("#previous_button").attr("disabled", true);

                    }



                    $(".background_search").attr("disabled", false);





                },

                error: function(jqXHR, textStatus, errorThrown) {
                    $(".loading_screen").hide();

                    $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");
                }



            });

        }





        function edit_note(id) {



            $('#update_note').modal('show');



            var dataSet = 'id=' + id;



            $.ajax({

                url: SITE + "ajaxfiles/note/get_note_single.php",

                type: "GET",

                data: dataSet,

                dataType: 'json',

                success: function(response) {



                    if (response) {

                        $("#e_notes_text").val(response['text']);

                        $("#note_id").val(response['id_note']);



                    }

                }





            });







        }





        $("#note_form_update").validate({



            rules: {



                notes_text: {

                    required: true,

                }

            },

            messages: {



                notes_text: {

                    required: 'Please type note text'

                }

            },





            submitHandler: function(form) {





                $('.update_submit_button').css('cursor', 'wait');

                $('.update_submit_button').attr('disabled', true);





                $.ajax({

                    url: SITE + "ajaxfiles/note/update_note.php",

                    type: "POST",

                    data: $(form).serialize(),

                    dataType: 'json',

                    success: function(response) {



                        $("#note_form_update").trigger("reset");

                        toastr.success('Successfully Note Updated');

                        reviewListProcess();

                        $('.update_submit_button').css('cursor', 'pointer');

                        $('.update_submit_button').removeAttr('disabled');

                        $('#update_note').modal('hide');





                    },

                    error: function(jqXHR, textStatus, errorThrown) {



                        toastr.error('A system error has been encountered. Please try again');



                        $('.update_submit_button').css('cursor', 'pointer');

                        $('.update_submit_button').removeAttr('disabled');

                        $('#update_note').modal('hide');



                    }









                });









            }, // Do not change code below

            errorPlacement: function(error, element) {

                error.insertAfter(element.parent());

            }





        });







        function edit_timelines(id) {



            $('#update_schedule').modal('show');



            var dataSet = 'id=' + id;



            $.ajax({

                url: SITE + "ajaxfiles/timeline/get_timeline_single.php",

                type: "GET",

                data: dataSet,

                dataType: 'json',

                success: function(response) {



                    if (response) {



                        var date = response['date'].substr(0, 10);

                        var time = response['date'].substr(11, 19);



                        $("#e_timeline_name").val(response['title']);

                        $("#e_timeline_date").val(date);

                        $("#e_timeline_time").val(time);

                        $("#item_id").val(response['id_timeline']);



                    }

                }





            });







        }









        $("#timeline_form_update").validate({

            ignore: ':hidden:not(.validy)',

            rules: {



                timeline_name: {

                    required: true,

                },

                timeline_time: {

                    required: true,

                },

                timeline_date: {

                    required: true,

                },

            },

            messages: {



                timeline_name: {

                    required: 'Please type event title'

                },

                timeline_time: {

                    required: 'Please select time'

                },

                timeline_date: {

                    required: 'Please select date'

                }

            },





            submitHandler: function(form) {





                $('.update_submit_button').css('cursor', 'wait');

                $('.update_submit_button').attr('disabled', true);





                $.ajax({

                    url: SITE + "ajaxfiles/timeline/update_timeline.php",

                    type: "POST",

                    data: $(form).serialize(),

                    dataType: 'json',

                    success: function(response) {



                        $("#timeline_form_update").trigger("reset");

                        toastr.success('Successfully Schedule Updated');

                        reviewListProcess();

                        $('.update_submit_button').css('cursor', 'pointer');

                        $('.update_submit_button').removeAttr('disabled');

                        $('#update_schedule').modal('hide');





                    },

                    error: function(jqXHR, textStatus, errorThrown) {



                        toastr.error('A system error has been encountered. Please try again');



                        $('.update_submit_button').css('cursor', 'pointer');

                        $('.update_submit_button').removeAttr('disabled');

                        $('#update_schedule').modal('hide');



                    }









                });









            }, // Do not change code below

            errorPlacement: function(error, element) {

                error.insertAfter(element.parent());

            }





        });





        function reloadPreviewAction() {

            $('#preview_proceced').val(2);

            $('#reload-btn').show();



        }



        function selectImageProcessing(id) {



            const matchValue = returnValues.findIndex((item) => item.id == id);



            ReturnData = returnValues[matchValue];



            $('#main_preview').attr("src", ReturnData.urls.regular);

            $('.content').addClass("updated");



            $('#api_modal').modal('hide');



            var dataSet = 'flag=' + 1 + '&image_url=' + ReturnData.urls.regular + '&id=' + process_id;

            CoverChangeAjaxProcess(dataSet);

            reloadPreviewAction();



        }





        $('#defaultCoverProcess').click(function() {



            var default_image = SITE + default_cover;

            $('#main_preview').attr("src", default_image);

            $('.content').removeClass("updated");



            var dataSet = 'flag=' + 0 + '&image_url=' + '' + '&id=' + process_id;

            CoverChangeAjaxProcess(dataSet);

            reloadPreviewAction();



        });





        function CoverChangeAjaxProcess(dataSet) {



            $.ajax({

                url: SITE + "ajaxfiles/review/update_cover_image.php",

                type: "POST",

                data: dataSet,

                dataType: "json",

                success: function(response) {

                    toastr.success('Successfully Cover Updated');

                },

                error: function(jqXHR, textStatus, errorThrown) {

                    toastr.error('A system error has been encountered. Please try again');

                }



            });

        }



        $('.review-edit-export-btn').click(function() {



            var name_title_hole = $('#name_title').val();

            $('.overlayTitle').html(name_title_hole);



        });





        $("#preview_form_upload").validate({



            rules: {



                upload_preview_image: {

                    required: true,

                }

            },

            messages: {



                upload_preview_image: {

                    required: 'Please select your image'

                }

            },





            submitHandler: function(form) {



                $('.upload_preview_image').css('cursor', 'wait');

                $('.upload_preview_image').attr('disabled', true);



                var formData = new FormData(form);

                formData.append('id', process_id);

                $('.progress').show();



                $.ajax({

                    xhr: function() {

                        var xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function(evt) {

                            if (evt.lengthComputable) {

                                var percentComplete = evt.loaded / evt.total;

                                percentComplete = parseInt(percentComplete * 100);

                                $('.progress-bar').css('width', percentComplete + "%");

                                $('.progress-bar').html(percentComplete + "%");

                                if (percentComplete === 100) {



                                }

                            }

                        }, false);

                        return xhr;

                    },

                    url: SITE + "ajaxfiles/review/cover_image_upload.php",

                    type: "POST",

                    data: formData,

                    dataType: 'json',

                    cache: false,

                    contentType: false,

                    processData: false,

                    success: function(response) {

                        $('#main_preview').attr("src", response.return_url);

                        $('.content').addClass("updated");



                        $("#preview_form_upload").trigger("reset");

                        $('.progress').hide();



                        $('#api_modal').modal('hide');

                        toastr.success('Successfully Cover Updated');

                        $('.upload_preview_image').css('cursor', 'pointer');

                        $('.upload_preview_image').removeAttr('disabled');

                        reloadPreviewAction();





                    },

                    error: function(jqXHR, textStatus, errorThrown) {



                        toastr.error('A system error has been encountered. Please try again');



                        $('.upload_preview_image').css('cursor', 'pointer');

                        $('.upload_preview_image').removeAttr('disabled');



                    }



                });





            }, // Do not change code below

            errorPlacement: function(error, element) {

                error.insertAfter(element.parent());

            }





        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>



</body>



</html>