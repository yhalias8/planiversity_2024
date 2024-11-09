<?php
session_start();
include_once("config.ini.php");
include("class/class.TripPlan.php");
include("class/class.Plan.php");

$plan = new Plan();

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/travel-documents/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}

$output = '';

$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);

if (empty($id_trip)) header("Location:" . SITE . "trip/how-are-you-traveling");
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

if (isset($_POST['documents_submit'])) {
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
    <meta name="description"
        content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <title>PLANIVERSITY - ADD YOUR TRAVEL DOCUMENTS</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <!--<link href="<?php echo SITE; ?>assets/css/app-style.css?v=20307090" rel="stylesheet" type="text/css" />-->

    <link href="<?php echo SITE; ?>js/upload/uploadfile.css" rel="stylesheet">
    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
    var SITE = '<?php echo SITE; ?>';
    var itinerary_type_mode = "<?= $trip->itinerary_type; ?>";
    </script>
    <script src="<?php echo SITE; ?>js/trip_documents.js"></script>
    <script src="<?php echo SITE; ?>js/global.js?v=203040"></script>
    <script src="<?php echo SITE; ?>js/upload/jquery.uploadfile.js"></script>
    <?php include('new_head_files.php'); ?>
    <script>
    function savedoctrip(doc, trip) {
        setTimeout(function() {
            $('#errordocuse_' + doc).hide('fast');
            $.post(SITE + "ajaxfiles/add_documents.php", {
                    dt: doc,
                    tp: trip
                },
                function(data) {
                    if (data['error']) {
                        $('#errordocuse_' + doc).html(data['error']);
                        $('#errordocuse_' + doc).fadeIn(500);
                    } else {
                        $('#docuse_' + doc).fadeOut(1000);
                    }
                }, "json");
        }, 0);
    }
    </script>

    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.css">
    <style>
    h1 {
        font-size: 24px;
        color: #1f74b7;
    }

    h2 {
        font-size: 20px;
        color: #1f74b7;
    }

    h4 {
        font-size: 12px;
        color: #67758d;
        text-transform: uppercase;
    }

    .w-35 {
        width: 35%;
    }

    .upload-document-icon {
        width: 24px;
        fill: rgba(13, 37, 109, 1);
    }

    .document-icon {
        width: 24px;
        fill: white;
    }

    /* .document-block:nth-of-type(3n - 3) .document-icon,
    .document-block:nth-of-type(3n - 3) .document-text {
        fill: white;
    }

    .document-block:nth-of-type(3n - 2) .document-icon,
    .document-block:nth-of-type(3n-1) .document-icon {
        fill: rgba(46, 58, 89, 1);
    }

    .document-block:nth-of-type(3n - 2) .document-text,
    .document-block:nth-of-type(3n-1) .document-text {
        color: rgba(46, 56, 77, 1);
    } */

    .upload-text {
        font-size: 15px;
        color: rgba(46, 56, 77, 1);
    }

    .document-text {
        font-size: 15px;
        color: white;
        padding: 0.375rem 0.75rem;
    }

    .document-type-text {
        font-size: 14px;
        color: white;
        padding: 0.375rem 0.75rem;
    }

    .document-type-title {
        font-size: 14px;
        color: rgba(46, 56, 77, 1);
        padding: 0 0.75rem;
    }

    .upload-button {
        border: 0;
        border-radius: 5px;
        width: 123.09px;
        height: 50.2px;
        font-weight: bold;
        display: flex;
        justify-content: center;
        align-items: center;
        color: black !important;
        background: linear-gradient(0,
                rgba(243, 159, 50, 1) 0%,
                rgba(250, 205, 97, 1) 100%);
    }


    .upload-block {
        height: 75px;
        min-height: 75px !important;
        border: 1px solid rgba(139, 145, 154, 0.27);
        border-radius: 5px;
        background: white;
        padding: 1rem 0.6rem 1rem 2rem;
    }

    .document-block {
        height: 75px !important;
        min-height: 75px !important;
        background: #0c246b;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        padding: 1rem 0.6rem 1rem 2rem !important;
        color: white !important;
    }
/* 
    .document-block:nth-child(3n) {
        background-color: rgba(12, 36, 107, 1) !important;
    }

    .document-block:nth-child(3n+1) {
        background-color: rgba(5, 139, 239, 1) !important;
    }

    .document-block:nth-child(3n+2) {
        background-color: rgba(244, 160, 51, 1) !important;
    } */


    .gray-border-left {
        border-left: 1px solid #e9e9e9 !important;
    }

    .trash-button {
        width: 32px !important;
        height: 32px !important;
        border: none !important;
        border-radius: 10rem !important;
        display: flex !important;
        cursor: pointer;
        align-items: center !important;
        justify-content: center !important;
        background: rgb(255 251 245 / 20%) !important
    }

    .trash-button svg {
        fill: white !important
    }

    .dropzone .dz-preview {
        margin: 0;
    }

    .dropzone {
        border: none;
        background: transparent;
        padding: 0;
    }
/* 
    .document-block:nth-of-type(3n - 3) .trash-button svg {
        fill: rgba(244, 160, 51, 1) !important
    }

    .document-block:nth-of-type(3n - 2) .trash-button svg,
    .document-block:nth-of-type(3n-1) .trash-button svg {
        fill: rgba(12, 36, 107, 1) !important
    }

    .document-block:nth-of-type(3n - 3) .trash-button {
        background: rgba(244, 160, 51, .2) !important
    }

    .document-block:nth-of-type(3n - 2) .trash-button,
    .document-block:nth-of-type(3n-1) .trash-button {
        background: rgba(12, 36, 107, .2) !important
    } */

    .footer-block {
        height: 75px;
        padding: 1rem 0 1rem 0;
    }

    .footer-action-button {
        width: 258px;
        height: 44.15px;
        background: rgba(5, 139, 239, 1);
        cursor: pointer;
        color: white;
        border: none;
        border-radius: 5px;
        box-shadow: 0px 4px 10px 0px rgba(45, 123, 255, 0.28);
    }

    .footer-skip-button {
        color: rgba(46, 56, 77, 1);
        width: 208px;
        height: 44.15px;
        display: flex;
        align-items: center;
        background: transparent;
        border: none;
    }

    .city-image{
    position: absolute;
    right: 0;
    bottom: 0;
    width: 561px;
    z-index: 0;
    }

    .custom-body{
        padding: 43px 281px 234px 50px !important;
    }

    .custom-hr{
        margin-left: -50px;
    margin-right: -281px;
     height: 1px;
        background-color: rgba(12, 36, 107, 0.08);;
        border: none;
    }

    .modal-content{
        background: rgba(245, 250, 253, 1) !important;
    }

    .back-button{
        color: black;
    }
    </style>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146873572-1"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery-simple-upload@1.1.0/simpleUpload.min.js"></script> -->

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

<body class="custom_documents">
<div class="fullscreen-background"></div>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include('new_backend_header.php'); ?>

    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <?php
            $step_index = "documents";
            include('dashboard/include/itinerary-step.php');
            ?>
        </div>
    </div>
    </header>

    <div data-backdrop="false" id="document-modal" class="modal fade bs-example-modal-lg custom_prefix_modal"
        tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-custom-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
            <div class="modal-content connect-bg">
                <div class="modal-body custom-body p-0 position-relative" id="document-modal-body">
                    <div class="container p-0 position-relative" style="z-index: 1;">

                        <h4>planversity</h2>
                            <h1>Add Documents</h1>

                            <hr class="my-4 custom-hr" />
                            <form id="my-awesome-dropzone" style="min-height: 75px;" class="dropzone">
                                <div class="error_style"><?php echo $output; ?></div>
                                <input name="location_from" id="location_from" class="inp1"
                                    value="<?php echo $trip->trip_location_from; ?>" type="hidden">
                                <input name="location_to" id="location_to" class="inp1"
                                    value="<?php echo $trip->trip_location_to; ?>" type="hidden">
                                <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion"
                                    class="inp1" type="hidden"
                                    value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
                                <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion"
                                    class="inp1" type="hidden"
                                    value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
                                <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion"
                                    class="inp1" type="hidden"
                                    value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
                                <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion"
                                    class="inp1" type="hidden"
                                    value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">
                                <input name="trip_location_from_trainportion" id="trip_location_from_trainportion"
                                    class="inp1" type="hidden"
                                    value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
                                <input name="trip_location_to_trainportion" id="trip_location_to_trainportion"
                                    class="inp1" type="hidden"
                                    value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">
                                <input name="trip_generated" id="trip_generated" class="inp1" type="hidden"
                                    value="<?php if ($trip) echo $trip->trip_generated; ?>" readonly>
                                <input name="trip_u_id" id="trip_u_id" class="inp1" type="hidden"
                                    value="<?php if ($trip) echo $trip->user_id; ?>" readonly>
                                <input name="trip_title" id="trip_title" class="inp1" type="hidden"
                                    value="<?php if ($trip) echo $trip->trip_title; ?>" readonly>


                                    <div class="d-flex justify-content-between upload-block align-items-center">

                                        <div class="p-0 d-flex align-items-center">
                                        <svg class="upload-document-icon" width="24" height="24" viewBox="0 0 24 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <mask id="mask0_1_229" style="mask-type:luminance"
                                                maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                                                <rect width="24" height="24" fill="white" />
                                            </mask>
                                            <g mask="url(#mask0_1_229)">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M19.9276 8.65196C19.9307 8.66123 19.9338 8.67057 19.937 8.68C19.965 8.764 19.982 8.85 19.987 8.938C19.9879 8.94813 19.9907 8.95762 19.9935 8.96697C19.9968 8.97792 20 8.98867 20 9V20C20 21.103 19.103 22 18 22H6C4.897 22 4 21.103 4 20V4C4 2.897 4.897 2 6 2H13C13.0113 2 13.0221 2.0032 13.033 2.00647C13.0424 2.00925 13.0519 2.01208 13.062 2.013C13.151 2.018 13.237 2.036 13.321 2.064C13.331 2.06743 13.3409 2.07056 13.3509 2.07368C13.3727 2.08057 13.3943 2.08736 13.415 2.097C13.521 2.146 13.622 2.207 13.708 2.293L19.708 8.293C19.794 8.379 19.855 8.48 19.904 8.586C19.9132 8.60787 19.9204 8.62974 19.9276 8.65196C19.9307 8.66123 19.9276 8.65196 19.9276 8.65196ZM14 8H16.586L14 5.414V8ZM6 4V20H18.002L18 10H13C12.447 10 12 9.553 12 9V4H6ZM11 12V14H9V16H11V18H13V16H15V14H13V12H11Z" />
                                            </g>
                                        </svg>

                                        <p class="font-weight-bold m-0 upload-text ml-3">Drop your document
                                            here
                                            or upload</p>
                                        <span class="dz-message"></span>


                                        </div>
                                    <div
                                        class="d-flex justify-content-between p-0 align-items-center w-35 gray-border-left" style="display:none">

                                        <a class="upload-button ml-1 w-100" id="dropzone-click-target">Upload</a>

                                    </div>

                                </div>



                            </form>


                                <div class="d-flex justify-content-between">
                                    <h2 class="mt-5 mb-4">Uploaded Documents</h2>
                                    <!-- <div class="d-flex justify-content-between align-items-end mb-4 w-35">
                                        <p class="font-weight-bold m-0 document-type-title">Document Type</p>
                                    </div> -->
                                </div>
                            <div id="uploaded-docs">
                            </div>


                            <form name="documents_form" method="post"  class="footer-block d-flex justify-content-between align-items-center mt-4">

                                <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                                <div class="p-0 d-flex align-items-end h-100">
                                    <a class="m-0 ml-3 back-button" href="<?= SITE; ?>trip/plans/<?= $_GET['idtrip']; ?>">Back</a>
                                </div>

                                <div class="d-flex justify-content-between p-0 align-items-center w-35">
                                    <a class="footer-skip-button" href="<?php echo SITE; ?>trip/name/<?php echo $_GET['idtrip']; ?>">Skip section</a>
                                    <button class="footer-action-button" name="documents_submit" id="documents_submit" type="submit" type="submit">Finished, Next Step</button>
                                </div>
                                <?php } ?>
    </form>

                    </div>
                    <img class="city-image" src="<?php echo SITE; ?>assets/images/city.png">
                    <br clear="all" />
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="upgrade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">UPGRADE PLAN</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>The number of documents that can be added is limited with a free account</p>
                    <a href="<?= SITE ?>billing">
                        <button class="btn btn-info">UPGRADE NOW</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php 
    
    $maxFileCount = 100;
    
    ?>

    <script>
    Dropzone.autoDiscover = false;
    var currentType = 'additional';
    var xhrUpload;
    var types = {
        passport: 'Passport',
        driver: 'Driver`s License',
        hitinerary: 'Hotel Itinerary',
        additional: 'Additional Document',
        fitinerary: 'Travel Itinerary'
    }

    function setup(id) {
        let options = {
            thumbnailHeight: 210,
            thumbnailWidth: 140,
            url: SITE +
                "ajaxfiles/upd_documents.php?type=driver&tp=<?php echo $id_trip ?>&vt=<?php echo $userdata['id'] ?>" +
                "&trip_generated=" +
                trip_generated +
                "&trip_u_id=" +
                trip_u_id +
                "&trip_title=" +
                trip_title,
            maxFilesize: 99999999,
            maxFiles: 5,
            parallelUploads: 10000,
            clickable: '#dropzone-click-target',
            params: {
                typeEvent: 'test'
            },
            uploadMultiple: true,
            dictResponseError: "Server not Configured",
            dictFileTooBig: "File too big ({{filesize}}MB). Must be less than {{maxFilesize}}MB.",
            dictCancelUpload: "",
            autoProcessQueue: false,
            autoQueue: false,
            previewsContainer: '#uploaded-docs',
            acceptedFiles: ".png,.jpg,.jpeg",
            init: function() {
                var self = this;

                self.on("removedfile", function(file) {
                    var trip_generated = $("#trip_generated").val();
                    var trip_u_id = $("#trip_u_id").val();
                    var trip_title = $("#trip_title").val();
                    if (!file.upload?.isloaded && !file?.isloaded) {
                        xhrUpload?.abort()
                    } else {
                        $.post(SITE + "ajaxfiles/del_documents.php", {
                                op: "delete",
                                name: file.upload.filename || file.name,
                                type: "additional",
                                trip_generated: trip_generated,
                                trip_u_id: trip_u_id,
                                trip_title: trip_title,
                            },
                            function(resp, textStatus, jqXHR) {

                            });
                    }

                });
                self.on("addedfile", function(file, response) {
                    var trip_generated = $("#trip_generated").val();
                    var trip_u_id = $("#trip_u_id").val();
                    var trip_title = $("#trip_title").val();
                    file.upload = {
                        ...file.upload,
                        isloaded: false
                    }
                    setTimeout(function() {
                        var formData = new FormData();
                        formData.append('myfile', file);
                        if (!file?.eventtype) {
                            file.eventtype = currentType;
                        }
                        // file.previewElement.querySelector('#type').innerText = types[file
                        //     .eventtype];
                        xhrUpload = $.ajax({
                            cache: false,
                            url: SITE +
                                "ajaxfiles/upd_documents.php?type=additional&tp=<?php echo $id_trip ?>&vt=<?php echo $userdata['id'] ?>" +
                                "&trip_generated=" +
                                trip_generated +
                                "&trip_u_id=" +
                                trip_u_id +
                                "&trip_title=" +
                                trip_title,
                            dataType: "json",
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                file.upload.filename = data[0]
                                file.upload.isloaded = true
                            }
                        });
                    }, 100)
                });
            },
            accept: function(file, done) {
                const pattern = /\d{6}(\.)/;

                if (pattern.test(file.name)) {
                    done();
                } else {
                    done("File name not a valid admission number");
                    return false;
                }
            },
            previewTemplate: `

            <div style="display: flex !important" class="document-block background-item-blue dz-preview dz-file-preview d-flex justify-content-between align-items-center mb-3">
                                <div class="p-0 d-flex align-items-center">
                                    <svg class="document-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <mask id="mask0_1_229" style="mask-type:luminance" maskUnits="userSpaceOnUse"
                                            x="0" y="0" width="24" height="24">
                                            <rect width="24" height="24" fill="white" />
                                        </mask>
                                        <g mask="url(#mask0_1_229)">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M19.9276 8.65196C19.9307 8.66123 19.9338 8.67057 19.937 8.68C19.965 8.764 19.982 8.85 19.987 8.938C19.9879 8.94813 19.9907 8.95762 19.9935 8.96697C19.9968 8.97792 20 8.98867 20 9V20C20 21.103 19.103 22 18 22H6C4.897 22 4 21.103 4 20V4C4 2.897 4.897 2 6 2H13C13.0113 2 13.0221 2.0032 13.033 2.00647C13.0424 2.00925 13.0519 2.01208 13.062 2.013C13.151 2.018 13.237 2.036 13.321 2.064C13.331 2.06743 13.3409 2.07056 13.3509 2.07368C13.3727 2.08057 13.3943 2.08736 13.415 2.097C13.521 2.146 13.622 2.207 13.708 2.293L19.708 8.293C19.794 8.379 19.855 8.48 19.904 8.586C19.9132 8.60787 19.9204 8.62974 19.9276 8.65196C19.9307 8.66123 19.9276 8.65196 19.9276 8.65196ZM14 8H16.586L14 5.414V8ZM6 4V20H18.002L18 10H13C12.447 10 12 9.553 12 9V4H6ZM11 12V14H9V16H11V18H13V16H15V14H13V12H11Z" />
                                        </g>
                                    </svg>
                                    <p class="font-weight-bold m-0 document-text ml-3 dz-filename"><span data-dz-name></span></p>
                                </div>

                                <div class="d-flex justify-content-end p-0 align-items-center w-35">
                                    <!-- <p class="font-weight-bold m-0 document-type-text"><span id="type" data-dz-eventtype></span></p> !-->
                                    <button class=" dz-remove trash-button mr-5">
                                        <svg data-dz-remove="" width="16" height="15" viewBox="0 0 16 15" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M14.8499 2.67871C14.7437 2.57251 14.5893 2.51331 14.4394 2.51331H11.5586V1.61578C11.5586 1.00879 11.0654 0.515625 10.4584 0.515625H5.54078C4.93379 0.515625 4.44062 1.00879 4.44062 1.61578V2.51331H1.54511C1.23779 2.51331 0.986328 2.76294 0.986328 3.07208C0.986328 3.38123 1.23596 3.63086 1.54511 3.63086H2.03827V13.3553C2.03827 13.9623 2.53144 14.4554 3.13843 14.4554H13.0176C13.6246 14.4554 14.1178 13.9623 14.1178 13.3553V3.63086H14.4391C14.7464 3.63086 15.0135 3.38123 15.0135 3.07208C15.0138 2.92377 14.9561 2.78491 14.8499 2.67871ZM2.58001 14.1732C2.61628 14.2139 2.65456 14.2507 2.69334 14.2874L2.69333 14.2874C2.65455 14.2507 2.61577 14.2139 2.58001 14.1732ZM2.73145 14.2586C2.76904 14.2999 2.80731 14.3411 2.8449 14.3729C2.82139 14.353 2.7992 14.3295 2.77569 14.3045C2.7616 14.2895 2.74704 14.2741 2.73145 14.2586ZM10.4535 1.61914H5.53895V2.52277H10.4535V1.61914ZM13.0161 13.3538H3.13867V3.63086H4.56202H13.0161V13.3538ZM7.43066 6.06525C7.43066 5.75458 7.68183 5.50342 7.9925 5.50342C8.30317 5.50342 8.55433 5.75488 8.55433 6.06525V11.278C8.55433 11.5886 8.30317 11.8398 7.9925 11.8398C7.68183 11.8398 7.43066 11.5886 7.43066 11.278V6.06525ZM4.94947 5.50342C4.63883 5.50342 4.3877 5.75458 4.3877 6.06525V11.278C4.3877 11.5886 4.63883 11.8398 4.94947 11.8398C5.26011 11.8398 5.51124 11.5886 5.51124 11.278V6.06525C5.51124 5.75488 5.2598 5.50342 4.94947 5.50342ZM10.4425 6.06525C10.4425 5.75458 10.6936 5.50342 11.0043 5.50342C11.315 5.50342 11.5661 5.75488 11.5661 6.06525V11.278C11.5661 11.5886 11.315 11.8398 11.0043 11.8398C10.6936 11.8398 10.4425 11.5886 10.4425 11.278V6.06525Z" />
                                        </svg>
                                    </button>

                                </div>
                            </div>
                            <div class="dz-error-message"><i class="fa fa-warning">&nbsp;</i><span data-dz-errormessage></span></div>
`
        };

        var myDropzone = new Dropzone(`#${id}`, options);

        function loadAllFiles() {
            Object.keys(types).forEach(item => {
                $.ajax({
                    cache: false,
                    url: SITE +
                        `ajaxfiles/load_documents.php?type=${item}&tp=<?php echo $id_trip ?>&vt=<?php echo $userdata['id'] ?>`,
                    dataType: "json",
                    success: function(data) {
                        for (var i = 0; i < data.length; i++) {
                            data[i].eventtype = item
                            data[i] = {
                                ...data[i],
                                isloaded: true
                            }
                            myDropzone.emit('addedfile', data[i])
                        }
                    }
                });

            })
        }

        loadAllFiles();
    }


    setup("my-awesome-dropzone");


    function changeType(type) {
        currentType = type;
        document.querySelector('#dropdownMenuButton').innerText = types[type];
    }


    var maxFileCount = '<?= $maxFileCount ?>';

    function changeDocOrientaion(orientation, filename) {
        $.ajax({
            cache: false,
            url: SITE + "ajaxfiles/upd_documents.php?tp=<?php echo $id_trip ?>&filenm=" + filename + "&orn=" +
                orientation,
            success: function(data) {
                alert("Orientation saved successfully for " + filename);
            }
        });
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
    var map = null;
    var bounds = null;
    var directionsService = null;
    var directionsDisplay = null;


    </script>
    <script>
        $(window).on('load', function() {
            $('#document-modal').modal('show');
        });

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

    <?php include('new_backend_footer.php'); ?>
<?php if ($trip->getRole($id_trip) != TripPlan::ROLE_COLLABORATOR) { ?>
        <Script type="text/javascript">
            $(document).ready(function() {
                $(".upload-block").remove();
                function hideButtons() {
                    if ($(".trash-button").length ) {
                        $(".trash-button").remove();
                        clearInterval(checkInterval);
                    }
                }
                var checkInterval = setInterval(hideButtons, 500);
            });
        </Script>

<?php } ?>
</body>

</html>