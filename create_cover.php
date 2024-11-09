<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'dashboard';
    header("Location:".SITE."login");
}
if ($userdata['account_type']=='Individual')
    header("Location:".SITE."welcome");

include('include_doctype.php');
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="ie6"> <![endif]-->
<!--[if IE 7 ]> <html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8 ]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9 ]> <html lang="en" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | Consolidated Travel Information Management</title>
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">

    <link href="assets_itinerary/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets_itinerary/css/icons.css" rel="stylesheet" type="text/css" />

    <link href="assets_itinerary/css/jquery.datetimepicker.css" rel="stylesheet" type="text/css">

    <link href="assets_itinerary/css/app-style.css" rel="stylesheet" type="text/css" />



    <script src="assets_itinerary/js/modernizr.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">
            <div class="logo">
                <a href="" class="logo">
                    <span class="logo-small"><img src = "assets_itinerary/images/logo-icon.png" alt = "logo icon"></span>
                    <span class="logo-large"><img src = "assets_itinerary/images/logo-icon.png" alt = "logo icon"><span>Planiversity</span></span>
                </a>
            </div>
            <div class="menu-extras topbar-custom">
                <ul class="list-inline float-right mb-0">
                    <li class="menu-item list-inline-item">
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                    </li>
                    <li class="menu-item list-inline-item">
                        <a class="nav-link" href="" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            Admin
                        </a>
                    </li>
                    <li class="list-inline-item dropdown more-nav-list">
                        <a class="nav-link dropdown-toggle arrow-none link-drop" data-toggle="dropdown" href="" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            Menu
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg" aria-labelledby="Preview">
                            <a href="" class="dropdown-item drop-menu-item">
                                About Us
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                What It Costs
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                Make a Payment
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                FAQs
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                Data Security
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                Contact Us
                            </a>
                            <a href="" class="dropdown-item drop-menu-item">
                                Blog
                            </a>
                        </div>
                    </li>
                    <li class="list-inline-item dropdown more-nav-list">
                        <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src="assets_itinerary/images/avatar-1.jpg" alt="user" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                            <div class="dropdown-item noti-title">
                                <h5 class="text-overflow">
                                    <small class="text-white">Eric</small>
                                </h5>
                            </div>
                            <a href="" class="dropdown-item drop-menu-item">
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu text-center">
                    <li class="box">
                        <a href="" class="left-nav-button top-progress active-step scale" data-toggle="modal" data-target="#schedule-modal2" data-backdrop="false">
                            <img src = "assets_itinerary/images/step_calendar.png" alt = "Schedule">
                            <p>Schedule</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress" data-toggle="modal" data-target="#note-modal">
                            <img src = "assets_itinerary/images/step_notes.png" alt = "Notes">
                            <p>Notes</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress">
                            <img src = "assets_itinerary/images/step_filters.png" alt = "Filters">
                            <p>Filters</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress">
                            <img src = "assets_itinerary/images/step_documents.png" alt = "Documents">
                            <p>Documents</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress" data-toggle="modal" data-target="#connect-modal">
                            <img src = "assets_itinerary/images/step_connect_sources.png" alt = "Documents">
                            <p>Connect</p>
                        </a>
                    </li>
                    <li class="step-arrow">
                        <a href="" class="left-nav-button top-progress">
                            <img src = "assets_itinerary/images/step_pdf.png" alt = "Export">
                            <p>Export</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<div class="wrapper solid-background">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="event-config-wrapper">
                    <h4 class="cover-hd">Create Cover Sheet</h4>
                    <form>
                        <fieldset>
                            <div class = "row">
                                <div class="col-md-12 col-lg-6">
                                    <div class = "card-inner-wrapper">
                                        <div class="form-group">
                                            <input type="text" class="line-input form-control input-lg" required="" placeholder="Event Title">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="line-input form-control input-lg" required="" placeholder="Party Time">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="line-input form-control input-lg" required="" placeholder="Time and Date">
                                        </div>
                                        <a href = "" class = "edit-text-action-btn" data-toggle="modal" data-target="#add-comment-modal">Edit text and background</a>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6">
                                    <div class = "event-card-wrapper">

                                        <h4 class = "text-center event-card-preview">Preview</h4>
                                        <div class = "event-preview-card-wrapper">

                                            <div class = "event-card-inner">
                                                <div class = "card-photo">
                                                    <img src = "assets_itinerary/images/pattern.png" alt = "card photo">
                                                </div>
                                                <h3 class = "event-card-info">Celebrate Retirement</h3>
                                                <h3 class = "event-card-info">Robert Anderson</h3>
                                                <h3 class = "event-card-info">May 1, 2020 at 1:00pm</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "col-md-12 col-lg-10">
                                    <div>
                                        <button type="submit" class="refresh-btn">Finished, Next Step</button>
                                    </div>
                                </div>
                                <div class = "col-md-12 col-lg-2">
                                    <a href = "" class = "skip-note-btn">Skip this Section</a>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add-comment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content wht-bg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit text and background</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action = "" class = "event-editor-form">
                    <div class = "row">
                        <div class = "col-sm-8">
                            <div class = "form-grop">
                                <input type="file" name="file" id="file" class="inputfile" />
                                <label for="file">Upload Image</label>

                                <!-- <div class="upload-btn-wrapper">
                                  <label class="btn">Upload a file</label>
                                  <input type="file" name="myfile" />
                                </div> -->
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <div class="select-style">
                                    <select class="input-lg inp1">
                                        <option value="Arial">Arial</option>
                                        <option value="helvetica">Helvetica</option>
                                        <option value="century gothic">Century Gothic</option>
                                        <option value="open sans">Open Sans</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="select-style">
                                    <select class="input-lg inp1">
                                        <option value="">Font Size</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <p class="footer-text">&copy; Copyright. 2015 - <script>document.write(new Date().getFullYear())</script> Planiversity, LLC. All Rights Reserved. </p>
            </div>
        </div>
    </div>
</footer>
<script src="assets_itinerary/js/jquery.min.js"></script>
<script src="assets_itinerary/js/jquery.datetimepicker.full.min.js"></script>

<script src="assets_itinerary/js/moment.min.js"></script>
<script src="assets_itinerary/js/popper.min.js"></script>
<script src="assets_itinerary/js/bootstrap.min.js"></script>
<script src="assets_itinerary/js/jquery.slimscroll.js"></script>

<script src="assets_itinerary/js/jquery.core.js"></script>
<script src="assets_itinerary/js/jquery.app.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&callback=myMap"></script>

<script type="text/javascript">
    var position = [51.508742, -0.120850];
    function showGoogleMaps() {
        var latLng = new google.maps.LatLng(position[0], position[1]);
        var mapOptions = {
            zoom: 3, // initialize zoom level - the max value is 3
            streetViewControl: false, // hide the yellow Street View pegman
            scaleControl: true, // allow users to zoom the Google Map
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: latLng
        };
        map = new google.maps.Map(document.getElementById('googlemaps'),
            mapOptions);
    }
    google.maps.event.addDomListener(window, 'load', showGoogleMaps);

    function toggle_edit_form(id) {
        var e = document.getElementById(id);
        if(e.style.display == 'block')
            e.style.display = 'none';
        else
            e.style.display = 'block';
    }

    $('#datetimepicker').datetimepicker({
        inline:true
    });
</script>
</body>
</html>