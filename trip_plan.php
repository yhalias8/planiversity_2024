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

    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?= 'https://' . $_SERVER['HTTP_HOST'] . '/staging/'; ?>'
    </script>
    <script src="<?php echo SITE; ?>js/trip_notes.js"></script>

    <script src="<?php echo SITE; ?>js/js_map.js"></script>
    <script src="<?php echo SITE; ?>js/global.js"></script>

    <?php include('new_head_files.php'); ?>

    <style>
        html {
            scroll-behavior: smooth;
        }
        .footer{position: relative;}
    </style>
</head>

<body class="custom_notes">

    <?php include('new_backend_header.php'); ?>

    <?php include_once('includes/top_bar_active.php'); ?>

    </header>


    <br clear="all" />
    <div id="map" class="plans_map"></div>
    <div class="your_plan_tab_sec">
        <div class="your_plan_tab_menu">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#your_plan">
                        <i class="fa fa-calendar-check-o" aria-hidden="true"></i>&nbsp;Your Plan</a></li>
                <li><a data-toggle="tab" href="#explore_tab">Explore</a></li>
            </ul>
        </div>

        <div class="your_plan_tab_items">
            <div class="tab-content">
                <div id="your_plan" class="tab-pane fade in active">
                    <div class="type_of_activity_sec">
                        <form id="form-plan" method="post">
                            <div class="form-group">
                                <label>Type of activity</label>
                                <input type="hidden" id="plan_id" />
                                <input type="text" id="plan_name" name="plan_name" class="form-control" required placeholder="Place to eat">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" id="plan_address" name="plan_address" class="form-control" required placeholder="Address">
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
                                <!-- Plan List -->
                                <?php
                                $query = "SELECT * FROM tripit_plans WHERE trip_id = ?";
                                $stmt = $dbh->prepare($query);
                                $stmt->bindValue(1, $id_trip, PDO::PARAM_STR);
                                $stmt->execute();

                                if ($stmt->rowCount() > 0) {
                                    $count = 0;
                                    $plans_ = $stmt->fetchAll(PDO::FETCH_OBJ);
                                    foreach ($plans_ as $plan_row) {
                                        $count++;
                                ?>
                                        <li id="plan_<?= $count ?>">
                                            <input type="hidden" name="plan_name[]" id="name_<?= $count ?>" value="<?= $plan_row->plan_name ?>" />
                                            <input type="hidden" name="plan_address[]" id="address_<?= $count ?>" value="<?= $plan_row->plan_address ?>" />
                                            <input type="hidden" name="plan_lat_long[]" id="lat_long_<?= $count ?>" value="<?= $plan_row->plan_lat_long ?>" />
                                            <div class="your_plan_item_list_text">
                                                <h4 id="plan_name_<?= $count ?>"><?= $plan_row->plan_name ?></h4>
                                                <p>At vero eos et accusamus et iusto odio. At vero eos et accusamus.</p>
                                                <p>At vero eos et accusamus et iusto odio. At vero eos et accusamus.</p>
                                                <h6 id="plan_address_<?= $count ?>"><?= $plan_row->plan_address ?></h6>
                                            </div>
                                            <div class="your_plan_item_edit">
                                                <a href="javascript:avoid(0)" onclick="edit_plan(<?= $count ?>)" class="pencil_edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                <a href="javascript:avoid(0)" onclick="remove_plan(<?= $count ?>)" class="delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                            </div>
                                        </li>
                                <?php }
                                } ?>
                            </ul>
                        </form>
                    </div>
                </div>
                <div id="explore_tab" class="tab-pane fade">
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
                </div>
            </div>
            <div class="skip_item_section">
                <ul class="list-unstyled">
                    <li>
                        <a href="<?= SITE . 'trip/travel-documents/' . $_GET['idtrip'] ?>" class="skipt_value">Skip Section</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" id="btn-plan-submit" class="save_next_value">Save and Next</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="plans_popup_item_sec1">
        <div class="plans_popup_item_1">
            <div class="popup_item_small">
                <div class="popup_item_small_img">
                    <img src="<?php echo SITE; ?>images/popup_item_img1.jpg">
                </div>
            </div>
            <div class="popup_item_small_text">
                <h5><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;321, Miami St. Fl 33129</h5>
                <h6>Vizcaya Museum & Gardens</h6>
            </div>
            <div class="popup_close">
                <a href="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="vizcaya_museum_gargens">
        <div class="vizcaya_museum_item">
            <img src="<?php echo SITE; ?>images/popup_item_img2.jpg">

            <div class="vizcaya_museum_gargen_content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="vizcaya_museum_gargens_left">
                            <h2>Vizcaya Museum & Gardens</h2>
                            <p>Historic estate with formal gardens & sculptures,<br> grottos & circa-1914
                                mansion turned museum.</p>
                            <h3>3251 S Miami Ave, Miami,FL 33129, United States</h3>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="vizcaya_museum_gargens_right">
                            <ul class="list-unstyled">
                                <li>
                                    <div class="vizcaya_museum_gargen_info">
                                        <div class="info_icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                            </svg>
                                        </div>
                                        <div class="info_information">
                                            <h3>Closed <span>Opens at 09:30</span></h3>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="vizcaya_museum_gargen_info">
                                        <div class="info_icon">
                                            <i class="fa fa-desktop" aria-hidden="true"></i>
                                        </div>
                                        <div class="info_information">
                                            <h3>vizcaya.org</h3>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="vizcaya_museum_gargen_info">
                                        <div class="info_icon">
                                            <i class="fa fa-phone" aria-hidden="true"></i>
                                        </div>
                                        <div class="info_information">
                                            <h3>+1305-250-9133</h3>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="add_to_list_btn">
                                        <a href="" class="btn_btn">Add to List&nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                            </svg> </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="popup_close">
            <a href="javascript:avoid(0)">
                <i class="fa fa-times" aria-hidden="true"></i>
            </a>
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
            if ($trip->trip_transport == 'vehicle') {

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
                new AutocompleteDirectionsHandler(map, 'driving', <?PHP echo $lat_from; ?>, <?PHP echo $lng_from; ?>, <?PHP echo $lat_to; ?>, <?PHP echo $lng_to; ?>, <?php echo $location_multi_waypoint_latlng; ?>, <?php echo $trip_via_waypoints ?>, false);
            <?php } ?>

            <?php
            if ($trip->trip_transport == 'train') {

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

            map.fitBounds(bounds2);

        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

    <?php include('new_backend_footer.php'); ?>

    <script>
        $(document).ready(function() {
            $('.your_plan_tab_menu li').click(function() {
                $('li').removeClass('active');
                $(this).addClass('active');
            });

        });

        var count = '<?= $count ?>';
        $("#form-plan").submit(function(e) {
            count++;
            e.preventDefault();
            var plan_id = $('#plan_id').val();
            var plan_name = $('#plan_name').val();
            var plan_address = $('#plan_address').val();
            var plan_lat_long = "";

            if (plan_name.length > 0 && plan_address.length > 0) {
                if (plan_id.length > 0) {
                    $('#name_' + plan_id).html(plan_name);
                    $('#address_' + plan_id).html(plan_address);
                    $('#plan_name_' + plan_id).html(plan_name);
                    $('#plan_address_' + plan_id).html(plan_address);
                } else {
                    var html = `<li id="plan_` + count + `">
                                <input type="hidden" name="plan_name[]" id="name_` + count + `" value="` + plan_name + `" />
                                <input type="hidden" name="plan_address[]" id="address_` + count + `" value="` + plan_address + `" />
                                <input type="hidden" name="plan_lat_long[]" id="lat_long_` + count + `" value="` + plan_lat_long + `" />
                                <div class="your_plan_item_list_text">
                                    <h4 id="plan_name_` + count + `">` + plan_name + `</h4>
                                    <p>At vero eos et accusamus et iusto odio. At vero eos et accusamus.</p>
                                    <p>At vero eos et accusamus et iusto odio. At vero eos et accusamus.</p>
                                    <h6 id="plan_address_` + count + `">` + plan_address + `</h6>
                                </div>
                                <div class="your_plan_item_edit">
                                    <a href="javascript:avoid(0)" onclick="edit_plan(` + count + `)" class="pencil_edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a href="javascript:avoid(0)" onclick="remove_plan(` + count + `)" class="delete"><i class="fa fa-times" aria-hidden="true"></i></a>
                                </div>
                            </li>`;

                    $('#plan_list').append(html);
                }

                $('#plan_id').val("");
                $('#plan_name').val("");
                $('#plan_address').val("");
                $('#btn-plan').html('Add');
            }
        });

        $('#btn-plan-submit').click(function() {
            $('#plan-form-submit').submit();
        })

        function remove_plan(id) {
            $('#plan_' + id).remove();
        }

        function edit_plan(id) {
            $('#plan_id').val(id);
            $('body').scrollTop(0);
            $('#btn-plan').html('Update');
            $('#plan_name').val($('#name_' + id).val());
            $('#plan_address').val($('#address_' + id).val());
        }
    </script>

</body>

</html>