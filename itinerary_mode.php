<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
  $_SESSION['redirect'] = 'trip/how-are-you-traveling';
  header("Location:" . SITE . "login");
}

include('include_doctype.php');
?>
<html>

<head>
  <meta charset="utf-8">
  <title>PLANIVERSITY - HOW ARE YOU TRAVELING?</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
  <meta name="keywords" content="Consolidated Travel Information Management">
  <meta name="author" content="">
  <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
  <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
  <script>
    var SITE = '<?php echo SITE; ?>'
  </script>

  <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
  <?php include('new_head_files.php'); ?>
  <style type="text/css">
    .footer {
      position: fixed !important;
    }
  </style>

</head>

<body class="custom_howtravelling">

  <?php include('new_backend_header.php'); ?>

  <?php include_once('includes/top_bar.php'); ?>
  </header>

  <div class="wrapper">
    <section class="map_with_content_Sec">
      <div id="map"></div>

      <div class="how-travel-wrapper how_travel_mobile_bg">
        <div class="row">
          <div class="col-mdgoogleapis-12 col-sm-12 text-center pt-5">
            <!--<img src = "<?php echo SITE; ?>assets/images/logo2x.png" alt = "logo icon">-->
            <h4 class="Planiversity_head">Planiversity</h4>
            <h3 class="where-to-hd">What Type of Plan Do You Need?</h3>
            <span class="where-select-method">Select your option</span>
          </div>
        </div>
        <div class="row justify-content-md-center mx-5" style="padding-left: 5rem; padding-right: 5rem">
          <div class="col-md-6">
          </div>
        </div>
        <div class="row justify-content-md-center mx-5 pb-5 map_vehicle_plane_icon">
          <div class="col-md-2 col-sm-12 col-4 border_line_right1">
            <a href="<?php echo SITE; ?>trip/event-itinerary">
              <div class="how-travel-ico-wrapper">
                <img src="<?php echo SITE; ?>assets/images/schedule.png" class="default_icon_des" width="30px" height="30px" alt="logo icon" style="vertical-align: -moz-middle-with-baseline;">
                <img src="<?php echo SITE; ?>assets/images/schedule_color.png" class="default_icon_mobile">
              </div>
              <p class="text-center where-text">Event</p>
            </a>
          </div>
          <div class="col-md-2 col-sm-12 col-4 border_line_right1">
            <a href="<?php echo SITE; ?>trip/how-are-you-traveling">
              <div class="how-travel-ico-wrapper">
                <img src="<?php echo SITE; ?>assets/images/travel.png" class="default_icon_des" width="30px" height="30px" alt="logo icon" style="vertical-align: -moz-middle-with-baseline;">
                <img src="<?php echo SITE; ?>assets/images/travel_color.png" class="default_icon_mobile">
              </div>
              <p class="text-center where-text">Trip</p>
            </a>
          </div>
          <div class="col-md-2 col-sm-12 col-4 border_line_right1">
            <a href="<?php echo SITE; ?>trip/job-itinerary">
              <div class="how-travel-ico-wrapper">
                <img src="<?php echo SITE; ?>assets/images/travel.png" class="default_icon_des" width="30px" height="30px" alt="logo icon" style="vertical-align: -moz-middle-with-baseline;">
                <img src="<?php echo SITE; ?>assets/images/travel_color.png" class="default_icon_mobile">
              </div>
              <p class="text-center where-text">Job</p>
            </a>
          </div>
          <div class="col-md-2 col-sm-12 col-4 border_line_right1">
            <a href="<?php echo SITE; ?>trip/appointment-itinerary">
              <div class="how-travel-ico-wrapper">
                <img src="<?php echo SITE; ?>assets/images/travel.png" class="default_icon_des" width="30px" height="30px" alt="logo icon" style="vertical-align: -moz-middle-with-baseline;">
                <img src="<?php echo SITE; ?>assets/images/travel_color.png" class="default_icon_mobile">
              </div>
              <p class="text-center where-text">Appointment</p>
            </a>
          </div>
        </div>
      </div>
    </section>
  </div>


  <script>
    var marker_origin = null;
    var marker_destination = null;
    var flightPath = null;

    var map = null;
    var bounds = null;

    function initMap() {
      map = new google.maps.Map(document.getElementById('map'), {
        mapTypeControl: false,
        center: {
          lat: 40.730610,
          lng: -73.968285
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        zoom: 7,
        disableDefaultUI: true,
      });


    }

    SITE = 'www.planiversity.com/';
  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>

  <?php include('new_backend_footer.php'); ?>

</body>

</html>