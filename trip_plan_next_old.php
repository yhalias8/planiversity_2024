<?php
include_once("config.ini.php");
include("class/class.TripPlan.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/plans/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}

$output = '';
$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = 'DELETE FROM `tripit_plans` WHERE `trip_id` = ?';
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $id_trip, PDO::PARAM_INT);
    $stmt->execute();

    if (isset($_POST['plan_name']) && isset($_POST['plan_address'])) {
        $plan_name  = $_POST['plan_name'];
        $address    = $_POST['plan_address'];
        $lat_long   = $_POST['plan_lat_long'];

        foreach ($plan_name as $key => $name) {
            $query = "INSERT INTO `tripit_plans` (trip_id, plan_name, plan_address, plan_lat_long, created_at) values (?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $id_trip, PDO::PARAM_STR);
            $stmt->bindValue(2, $name, PDO::PARAM_STR);
            $stmt->bindValue(3, $address[$key], PDO::PARAM_STR);
            $stmt->bindValue(4, $lat_long[$key], PDO::PARAM_STR);
            $stmt->bindValue(5, date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    header("Location:" . SITE . "trip/travel-documents/" . $_GET['idtrip']);
}

if (empty($id_trip)) {
    header("Location:" . SITE . "trip/how-are-you-traveling");
}

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




$trip_location_from_latlng = $trip->trip_location_from_latlng;
$trip_location_to_latlng = $trip->trip_location_to_latlng;
$trip_has_train = false;

if ($trip->trip_transport == 'plane') {
    if ($trip->trip_location_to_latlng_drivingportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_drivingportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_drivingportion;
    }

    if ($trip->trip_location_to_latlng_trainportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_trainportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_trainportion;
        $trip_has_train = true;
    }
}


if ($trip->trip_transport == 'vehicle') {
    if ($trip->trip_location_to_latlng_flightportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_flightportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_flightportion;
    }
    if ($trip->trip_location_to_latlng_trainportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_trainportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_trainportion;
        $trip_has_train = true;
    }
}

//echo $trip_location_to_latlng;

if ($trip->trip_transport == 'train') {
    if ($trip->trip_location_to_latlng_flightportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_flightportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_flightportion;
    }
    if ($trip->trip_location_to_latlng_drivingportion) {
        $trip_location_from_latlng = $trip->trip_location_from_latlng_drivingportion;
        $trip_location_to_latlng = $trip->trip_location_to_latlng_drivingportion;
    }
    $trip_has_train = true;
}

$transport = (isset($trip->trip_transport) && !empty($trip->trip_transport)) ? $trip->trip_transport : '';
$tmp = str_replace('(', '', $trip->trip_location_from_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp = str_replace(')', '', $tmp);
$tmp = explode(',', $tmp);

$lat_from = $lat_from2 = trim($tmp[0]);
$lng_from = $lng_from2 = trim($tmp[1]);

if ($trip->trip_location_waypoint_latlng != '') {
    $tmp = str_replace('(', '', $trip->trip_location_waypoint_latlng); // Ex: (25.7616798, -80.19179020000001)
    $tmp = str_replace(')', '', $tmp);
    $tmp = explode(',', $tmp);
    $lat_to = $lat_to2 = trim($tmp[0]);
    $lng_to = $lng_to2 = trim($tmp[1]);
} else {
    $tmp = str_replace('(', '', $trip->trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
    $tmp = str_replace(')', '', $tmp);
    $tmp = explode(',', $tmp);
    $lat_to = $lat_to2 = trim($tmp[0]);
    $lng_to = $lng_to2 = trim($tmp[1]);
}

//echo ;


$tmp1 = str_replace('(', '', $trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp1 = str_replace(')', '', $tmp1);
$tmp1 = explode(',', $tmp1);

$filter_lat_to = $filter_lat_to2 = trim($tmp1[0]);
$filter_lng_to = $filter_lng_to2 = trim($tmp1[1]);

// echo $filter_lat_to;
// echo "<br/>";
// echo $filter_lng_to;
// echo "<br/>";
// die();


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
    <title>PLANIVERSITY - ADD YOUR TRIP PLAN</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/app-style.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.19.0"></script>

    <script>
        //var SITE = '<?= 'https://' . $_SERVER['HTTP_HOST'] . '/staging/'; ?>'
        //var SITE = '<?= 'http://' . $_SERVER['HTTP_HOST'] . '/master/stag/'; ?>'
        var SITE = '<?= 'https://' . $_SERVER['HTTP_HOST'] . '/staging/'; ?>'
    </script>
    <script src="<?php echo SITE; ?>js/trip_notes.js"></script>

    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <script src="<?php echo SITE; ?>js/global.js"></script>

    <?php include('new_head_files.php'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        .footer {
            position: relative;
        }

        div#map {
            margin-left: 536px;
        }

        .your_plan_item_list {
            overflow-y: scroll;
            margin-right: 15px;
            height: calc(800px - 50px);
        }

        ul#plan_list {
            padding-bottom: 50px;
        }

        .custom-group {
            margin-bottom: 10px;
        }

        button.ap-input-icon {
            top: 16px;
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
    </style>
</head>

<body class="custom_notes">

    <?php include('new_backend_header.php'); ?>

    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu text-center plan-nav">
                    <li>
                        <a href="<?php echo SITE; ?>trip/create-timeline/<?php echo $_GET['idtrip']; ?>" class="left-nav-button scale" data-toggle="modal" data-target="#schedule-modal">

                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/calendar_check.png" alt="Schedule">Schedule</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>trip/plan-notes/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">

                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/file_blank.png" alt="Schedule">Notes</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>trip/filters/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">

                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/slider_02.png" alt="Schedule">Filters</p>
                        </a>
                    </li>
                    <li class="selected">
                        <a href="javascript:void(0)" class="left-nav-button">

                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/plans.png" alt="Schedule">Plans</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>trip/travel-documents/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">

                            <p class="main-color"><img class="mr-2" src="<?php echo SITE; ?>images/folder_open.png" alt="Schedule">Documents</p>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>trip/connect/<?php echo $_GET['idtrip']; ?>" class="left-nav-button">

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


    <br clear="all" />
    <div id="map" class="plans_map"></div>
    <div class="your_plan_tab_sec">
        <div class="your_plan_tab_menu">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#your_plan">
                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>&nbsp;Your Plan</a></li>
                <!-- <li><a data-toggle="tab" href="#explore_tab">Explore</a></li> -->
            </ul>
        </div>

        <div class="your_plan_tab_items">
            <div class="tab-content">
                <div id="your_plan" class="tab-pane fade in active">
                    <div class="type_of_activity_sec">
                        <form id="form-plan">
                            <div class="form-group custom-group">
                                <label>Plan Title</label>
                                <input type="text" id="plan_title" name="plan_title" class="form-control" placeholder="Plan Title">
                            </div>

                            <div class="form-group custom-group">
                                <label>Type of activity</label>
                                <input type="hidden" id="plan_id" name="plan_id" readonly />
                                <!-- <input type="text" id="plan_name" name="plan_name" class="form-control" required placeholder="Place to eat"> -->
                                <select class="form-control" name="plan_type" id="plan_type">
                                    <option value="">Select a option</option>
                                    <option value="Place to eat">Place to eat</option>
                                    <option value="Things to do">Things to do</option>
                                    <option value="People to see">People to see</option>
                                </select>
                            </div>
                            <div class="form-group custom-group frm-grp">
                                <label>Address</label>
                                <input type="text" id="plan_address" name="plan_address" class="dashboard-form-control form-control input-lg clearable" required placeholder="Address">

                                <input name="location_to_lat" id="location_to_lat" type="hidden" class="inp1" value="<?= $filter_lat_to; ?>" readonly>
                                <input name="location_to_lng" id="location_to_lng" type="hidden" class="inp1" value="<?= $filter_lng_to; ?>" readonly>


                                <input name="plans_idtrip" id="plans_idtrip" type="hidden" class="inp1" value="<?php echo $trip->trip_id; ?>" readonly>

                            </div>
                            <div class="type_of_activity_submit">
                                <button type="submit" id="btn-plan" class="btn">Add</button>
                            </div>
                        </form>


                    </div>
                    <div class="border_top_bottom"></div>
                    <div class="your_plan_item_list">
                        <form action="" id="plan-form-submit" method="post">
                            <ul id="plan_list">

                            </ul>
                        </form>
                    </div>
                </div>

                <!-- <div id="explore_tab" class="tab-pane fade">
                    <div class="explore_tab_item_list_sec">
                        <div class="explore_tab_items">
                            <img src="https://www.planiversity.com/staging/images/popup_item_img2.jpg">
                            <div class="explore_tab_items_text">
                                <h2>Vizcaya Museum &amp; Gardens</h2>
                                <p>Historic estate with formal gardens &amp; sculptures,<br> grottos &amp; circa-1914
                                    mansion turned museum.</p>
                                <h3>3251 S Miami Ave, Miami,FL 33129, <br>United States</h3>
                                <div class="row explore_tab_items_row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <ul class="list-unstyled">
                                            <li>
                                                <div class="vizcaya_museum_gargen_info">
                                                    <div class="info_icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"></path>
                                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="info_information">
                                                        <h5>Closed <span>Opens at 09:30</span></h5>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <ul class="list-unstyled">

                                            <li>
                                                <div class="vizcaya_museum_gargen_info">
                                                    <div class="info_icon">
                                                        <i class="fa fa-desktop" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="info_information">
                                                        <h5>vizcaya.org</h5>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="vizcaya_museum_gargen_info">
                                                    <div class="info_icon">
                                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="info_information">
                                                        <h5>+1305-250-9133</h5>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="add_to_list_btn">
                                    <a href="" class="btn_btn">Add to List&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"></path>
                                        </svg> </a>
                                </div>
                            </div>
                            <div class="explore_tab_items_no">
                                <a href="">01</a>
                            </div>
                        </div>
                        <div class="explore_tab_items">
                            <img src="https://www.planiversity.com/staging/images/popup_item_img2.jpg">
                            <div class="explore_tab_items_text">
                                <h2>Vizcaya Museum &amp; Gardens</h2>
                                <p>Historic estate with formal gardens &amp; sculptures,<br> grottos &amp; circa-1914
                                    mansion turned museum.</p>
                                <h3>3251 S Miami Ave, Miami,FL 33129, <br>United States</h3>
                                <div class="row explore_tab_items_row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <ul class="list-unstyled">
                                            <li>
                                                <div class="vizcaya_museum_gargen_info">
                                                    <div class="info_icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"></path>
                                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="info_information">
                                                        <h5>Closed <span>Opens at 09:30</span></h5>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <ul class="list-unstyled">

                                            <li>
                                                <div class="vizcaya_museum_gargen_info">
                                                    <div class="info_icon">
                                                        <i class="fa fa-desktop" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="info_information">
                                                        <h5>vizcaya.org</h5>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="vizcaya_museum_gargen_info">
                                                    <div class="info_icon">
                                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="info_information">
                                                        <h5>+1305-250-9133</h5>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="add_to_list_btn">
                                    <a href="" class="btn_btn">Add to List&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"></path>
                                        </svg> </a>
                                </div>
                            </div>
                            <div class="explore_tab_items_no">
                                <a href="">02</a>
                            </div>
                        </div>
                        <div class="explore_tab_items">
                            <img src="https://www.planiversity.com/staging/images/popup_item_img2.jpg">
                            <div class="explore_tab_items_text">
                                <h2>Vizcaya Museum &amp; Gardens</h2>
                                <p>Historic estate with formal gardens &amp; sculptures,<br> grottos &amp; circa-1914
                                    mansion turned museum.</p>
                                <h3>3251 S Miami Ave, Miami,FL 33129, <br>United States</h3>
                                <div class="row explore_tab_items_row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <ul class="list-unstyled">
                                            <li>
                                                <div class="vizcaya_museum_gargen_info">
                                                    <div class="info_icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"></path>
                                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="info_information">
                                                        <h5>Closed <span>Opens at 09:30</span></h5>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <ul class="list-unstyled">

                                            <li>
                                                <div class="vizcaya_museum_gargen_info">
                                                    <div class="info_icon">
                                                        <i class="fa fa-desktop" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="info_information">
                                                        <h5>vizcaya.org</h5>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="vizcaya_museum_gargen_info">
                                                    <div class="info_icon">
                                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                                    </div>
                                                    <div class="info_information">
                                                        <h5>+1305-250-9133</h5>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="add_to_list_btn">
                                    <a href="" class="btn_btn">Add to List&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"></path>
                                        </svg> </a>
                                </div>
                            </div>
                            <div class="explore_tab_items_no">
                                <a href="">03</a>
                            </div>
                        </div>
                    </div>
                </div> -->

            </div>
            <div class="skip_item_section">
                <ul class="list-unstyled">
                    <li>
                        <a href="<?= SITE . 'trip/travel-documents/' . $_GET['idtrip'] ?>" class="skipt_value">Skip Section</a>
                    </li>
                    <li>
                        <!-- <a href="javascript:void(0)" id="btn-plan-submit" class="save_next_value">Save and Next</a> -->
                        <a href="<?php echo SITE; ?>trip/travel-documents/<?php echo $_GET['idtrip']; ?>" id="notes_submit" class="refresh-btn mt-3 mt-xs-0 mt-sm-0">Finished, Next Step</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>


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
        var myLocation = null;
        var map = null;
        var marker = null;
        var bounds = null;
        var geocoder = null;
        var directionsService = null;
        var directionsDisplay = null;
        var location_lat = $("#location_to_lat").val();
        var location_lng = $("#location_to_lng").val();

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

            myLocation = {
                lat: <?= $filter_lat_to; ?>,
                lng: <?= $filter_lng_to; ?>
            };

            geocoder = new google.maps.Geocoder();
            map = new google.maps.Map(document.getElementById('map'), {
                mapTypeControl: false,
                center: {
                    lat: <?= $filter_lat_to; ?>,
                    lng: <?= $filter_lng_to; ?>
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: 12
            });
            var bounds2 = new google.maps.LatLngBounds();
            //map.setZoom(map.getZoom() - 4);

            google.maps.event.addListener(map, 'click', function(event) {
                var latitude = event.latLng.lat();
                var longitude = event.latLng.lng();
                $("#location_to_lat").val(event.latLng.lat());
                $("#location_to_lng").val(event.latLng.lng());
                //$("#trip_filters").submit();
            });

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(location_lat, location_lng),
                icon: 'https://planiversity.com/assets/images/Selected_B.png',
                //icon: 'https://img.icons8.com/fluent/48/000000/marker.png',
                title: "Plan Destination",
                // label: {
                //     text: 'You desctination',
                //     color: "#ffffff",
                // },
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
            });

            google.maps.event.addListener(marker, 'dragend', function(event) {
                $("#location_to_lat").val(event.latLng.lat());
                $("#location_to_lng").val(event.latLng.lng());
                //$("#trip_filters").submit();
                //map.setZoom(10);
                //map.setCenter(marker.getPosition());
                marker.setAnimation(google.maps.Animation.BOUNCE);


                // const input = document.getElementById("latlng").value;
                // const latlngStr = input.split(",", 2);
                const latlng = {
                    lat: parseFloat(event.latLng.lat()),
                    lng: parseFloat(event.latLng.lng()),
                };

                geocoder
                    .geocode({
                        location: latlng
                    })
                    .then((response) => {
                        if (response.results[0]) {

                            document.querySelector('#plan_address').value = response.results[0].formatted_address || '';
                            //console.log('Draged-address', response.results[0]);
                            //console.log('Draged-address', response.results[0].formatted_address);

                        } else {
                            window.alert("No results found");
                        }
                    })
                    .catch((e) => window.alert("Geocoder failed due to: " + e))


                console.log(marker.getPosition());
            });

            //map.fitBounds(bounds2);

            //offsetCenter(marker.getPosition(), -300, 400);
            console.log('Outside-Marker', marker.getPosition());
            //offsetCenter(map.getCenter(), -300, 400);
        }

        function SetMarker(lat, lng) {
            //Remove previous Marker.
            if (marker != null) {
                marker.setMap(null);
            }

            //Set Marker on Map.
            //var data = markers[position];
            //var myLatlng = new google.maps.LatLng(lat, lng);
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                icon: 'https://planiversity.com/assets/images/Selected_B.png',
                //icon: 'https://img.icons8.com/fluent/48/000000/marker.png',
                title: "Plan Desctination",
                // label: {
                //     text: 'You desctination',
                //     color: "#ffffff",
                // },
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
            });

            //Create and open InfoWindow.
            // var infoWindow = new google.maps.InfoWindow();
            // infoWindow.setContent("<div style = 'width:200px;min-height:40px'>" + data.description + "</div>");
            // infoWindow.open(map, marker);
        };


        function offsetCenter(latlng, offsetx, offsety) {

            // latlng is the apparent centre-point
            // offsetx is the distance you want that point to move to the right, in pixels
            // offsety is the distance you want that point to move upwards, in pixels
            // offset can be negative

            var scale = Math.pow(2, map.getZoom());
            var nw = new google.maps.LatLng(
                map.getBounds().getNorthEast().lat(),
                map.getBounds().getSouthWest().lng()
            );

            var worldCoordinateCenter = map.getProjection().fromLatLngToPoint(latlng);
            var pixelOffset = new google.maps.Point((offsetx / scale) || 0, (offsety / scale) || 0)

            var worldCoordinateNewCenter = new google.maps.Point(
                worldCoordinateCenter.x - pixelOffset.x,
                worldCoordinateCenter.y + pixelOffset.y
            );

            var newCenter = map.getProjection().fromPointToLatLng(worldCoordinateNewCenter);

            map.setCenter(newCenter);

        }

        function changeMarkerPosition(lat, lng, animate) {
            // var location_lat = $("#location_to_lat").val();
            // var lcoation_lng = $("#location_to_lng").val();

            var latlng = new google.maps.LatLng(lat, lng);
            marker.setPosition(latlng);
            marker.setAnimation(animate == 'bounce' ? google.maps.Animation.BOUNCE : google.maps.Animation.DROP)
            console.log('marker', marker);
            console.log('updated-lat', lat);
            console.log('updated-lng', lng);
            console.log('position', marker.getPosition());
            map.setZoom(14);
            offsetCenter(marker.getPosition(), -300, 400);
            //map.setCenter(marker.getPosition());


        }

        setTimeout(function() {
            offsetCenter(myLocation, -300, 400);
        }, 2000);
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

    <script src="<?php echo SITE; ?>js/trip_plan_next.js"></script>

    <?php include('new_backend_footer.php'); ?>

    <script>
        $(document).ready(function() {
            $('.your_plan_tab_menu li').click(function() {
                $('li').removeClass('active');
                $(this).addClass('active');
            });

            // var plan_address = document.getElementById('plan_address');
            // var hotelAddressPlaceAutocomplete = places({
            //     appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
            //     apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
            //     container: plan_address,
            //     type: 'address',
            // });

        });


        (function() {


            var placesAutocomplete = places({
                appId: '<?php echo ALGOLIA_PLACES_APP_ID; ?>',
                apiKey: '<?php echo ALGOLIA_PLACES_API_KEY; ?>',
                container: document.querySelector('#plan_address'),
                templates: {
                    value: function(suggestion) {
                        return suggestion.name;
                    }
                }
            }).configure({
                type: 'address'
            });
            placesAutocomplete.on('change', function resultSelected(e) {
                var latlng = e.suggestion.latlng;


                document.querySelector('#location_to_lat').value = latlng.lat || '';
                document.querySelector('#location_to_lng').value = latlng.lng || '';
                //document.querySelector('#zip').value = e.suggestion.postcode || '';

                console.log('Result-E', e);
                //SetMarker(latlng.lat, latlng.lng);
                //marker.setPosition(latlng.lat, latlng.lng);

                changeMarkerPosition(latlng.lat, latlng.lng, 'bounce');




            });
        })();





        var count = 0;


        // $("#form-plan").submit(function(e) {
        //     count++;
        //     e.preventDefault();
        //     var plan_id = $('#plan_id').val();
        //     var plan_name = $('#plan_name').val();
        //     var plan_address = $('#plan_address').val();
        //     var plan_lat_long = "";

        //     if (plan_name.length > 0 && plan_address.length > 0) {
        //         if (plan_id.length > 0) {
        //             $('#name_' + plan_id).html(plan_name);
        //             $('#address_' + plan_id).html(plan_address);
        //             $('#plan_name_' + plan_id).html(plan_name);
        //             $('#plan_address_' + plan_id).html(plan_address);
        //         } else {
        //             var html = `<li id="plan_` + count + `">
        //                         <input type="hidden" name="plan_name[]" id="name_` + count + `" value="` + plan_name + `" />
        //                         <input type="hidden" name="plan_address[]" id="address_` + count + `" value="` + plan_address + `" />
        //                         <input type="hidden" name="plan_lat_long[]" id="lat_long_` + count + `" value="` + plan_lat_long + `" />
        //                         <div class="your_plan_item_list_text">
        //                             <h4 id="plan_name_` + count + `">` + plan_name + `</h4>
        //                             <p>At vero eos et accusamus et iusto odio. At vero eos et accusamus.</p>
        //                             <p>At vero eos et accusamus et iusto odio. At vero eos et accusamus.</p>
        //                             <h6 id="plan_address_` + count + `">` + plan_address + `</h6>
        //                         </div>
        //                         <div class="your_plan_item_edit">
        //                             <a href="javascript:avoid(0)" onclick="edit_plan(` + count + `)" class="pencil_edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
        //                             <a href="javascript:avoid(0)" onclick="remove_plan(` + count + `)" class="delete"><i class="fa fa-times" aria-hidden="true"></i></a>
        //                         </div>
        //                     </li>`;

        //             $('#plan_list').append(html);
        //         }

        //         $('#plan_id').val("");
        //         $('#plan_name').val("");
        //         $('#plan_address').val("");
        //         $('#btn-plan').html('Add');
        //     }
        // });

        // $('#btn-plan-submit').click(function() {
        //     $('#plan-form-submit').submit();
        // })

        // function remove_plan(id) {
        //     $('#plan_' + id).remove();
        // }

        // function edit_plan(id) {
        //     $('#plan_id').val(id);
        //     $('body').scrollTop(0);
        //     $('#btn-plan').html('Update');
        //     $('#plan_name').val($('#name_' + id).val());
        //     $('#plan_address').val($('#address_' + id).val());
        // }
    </script>

</body>

</html>