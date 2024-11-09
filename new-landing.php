<?php
include_once("config.ini.php");
if ($auth->isLogged()) {
    header('location: welcome');
}

include_once("include_login_php.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planiversity</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?= SITE; ?>home-page/css/menuzord.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>home-page/css/home-style.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="<?= SITE; ?>home-page/css/home-responsive.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?= SITE; ?>home-page/js/owl.carousel.js"></script>

    <script>
        $(document).ready(function() {
            $('.icon_baar').click(function() {
                $('.drop-box').toggle();
            })
            $('.icon_baar').mouseover(function() {
                $('.drop-box').show();
            })
            $('.drop-box').mouseleave(function() {
                $('.drop-box').hide();
            })
        })
    </script>
    <!-- End Google Tag Manager -->
    <style>
        .drop-box {
            top: 50px;
            left: 200px;
            z-index: 99;
            width: auto;
            height: auto;
            padding: 5px 10px;
            background: white;
            border-radius: 5px;
            position: absolute;
        }

        .drop-box>a {
            text-decoration: none;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>


</head>

<body>


    <section class="header_slider_sec">
        <header id="header">


            <div class="topbar fixed-header animated slideInDown">
                <div class="header1 po-relative">
                    <div class="container">
                        <nav class="navbar navbar-expand-lg h1-nav">
                            <a class="navbar-brand" href="<?php echo SITE; ?>">
                                Planiversity
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mheader1" aria-controls="mheader1" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="fa fa-bars"></span>
                            </button>

                            <div class="collapse navbar-collapse hover-dropdown" id="mheader1">
                                <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= SITE ?>about-us">About Us</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= SITE ?>blog">Blog</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= SITE ?>select-your-payment">What It Costs</a>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= SITE ?>smart-map">Smart Travel</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= SITE ?>travel-deals">Travel Booking</a>
                                    </li>

                                    <?php
                                    if ($auth->isLogged() && $userdata['customer_number'] == '62f6d52f7e') { ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="apanel/users">Admin</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="btn free-trial-button" href="logout">Log Out</a>
                                        <?php } else if ($auth->isLogged()) { ?>
                                        <li class="nav-item sign_in_btn">
                                            <a class="btn free-trial-button" href="logout">Log Out</a>
                                        <?php   } else { ?>
                                        <li class="sign_in_btn"><a href="<?= SITE ?>login" id="show_loginform">Sign In</a>
                                        <?php } ?>
                                        </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>


        <img src="<?= SITE; ?>assets/images/home-page/header_banner.png" class="header_banner">
        <div class="banner_header_heading">
            <h2>The easiest way to create and manage a<span>customized travel itinerary</span></h2>
            <div class="pragraph">
                <p> A revolutionary travel logistics service, dedicated to consolidating so much of your <span>travel information. It's designed with efficiency, security and safety as a priority.</span></p>
            </div>
        </div>

        <div class="video_header_sec">

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-3 tab-card">
                            <div class="card-header tab-card-header">
                                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link active" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two" aria-selected="false">
                                            <p>
                                                <img src="<?= SITE; ?>home-page/images/2-active.png" class="active_image">
                                                <img src="<?= SITE; ?>home-page/images/2.png" class="normal_image">
                                                <span>Flights</span>
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One" aria-selected="true">
                                            <p>
                                                <img src="<?= SITE; ?>home-page/images/1-active.png" class="active_image">
                                                <img src="<?= SITE; ?>home-page/images/1.png" class="normal_image">
                                                <span>Hotels</span>
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="three-tab" data-toggle="tab" href="#three" role="tab" aria-controls="Three" aria-selected="false">
                                            <p>
                                                <img src="<?= SITE; ?>home-page/images/3-active.png" class="active_image">
                                                <img src="<?= SITE; ?>home-page/images/3.png" class="normal_image">
                                                <span>Trains</span>
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="four-tab" data-toggle="tab" href="#four" role="tab" aria-controls="Three" aria-selected="false">
                                            <p>
                                                <img src="<?= SITE; ?>home-page/images/4-active.png" class="active_image">
                                                <img src="<?= SITE; ?>home-page/images/4.png" class="normal_image">
                                                <span>Cars</span>
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-content top-place" id="myTabContent">

                                <div class="tab-pane fade show active p-3" id="two" role="tabpanel" aria-labelledby="two-tab">



                                    <form id="flight_initial_form">




                                        <div class="row mt-1 mb-2">
                                            <div class="col-md-2 pe-0 col-sm-12">
                                                <label class="radio"> <input type="radio" value="one" name="trip_style" class="trip_style" checked> One way <span></span> </label>
                                            </div>

                                            <div class="col-md-2 pe-0 col-sm-12">
                                                <label class="radio"> <input type="radio" value="round" name="trip_style" class="trip_style"> Roundtrip <span></span> </label>
                                            </div>


                                        </div>





                                        <div class="row">

                                            <div class="col-sm-12">

                                            </div>

                                            <div class="col-sm-6">

                                                <label class="mr-b-10">Origin Airport </label>
                                                <div class="group">
                                                    <select class="form-control select" id="from_location"></select>
                                                    <input type="hidden" class="form-control input-field validy" name="from_location_code" id="from_location_code" readonly>
                                                </div>

                                            </div>

                                            <div class="col-sm-6">

                                                <label class="mr-b-10">Destination Airport</label>
                                                <div class="group">
                                                    <select class="form-control select" id="to_location"></select>
                                                    <input type="hidden" class="form-control input-field validy" name="to_location_code" id="to_location_code" readonly>
                                                </div>

                                            </div>

                                        </div>


                                        <div class="row mt-1">

                                            <div class="col-md-12 col-lg-3">
                                                <div class="form-group frm-grp">
                                                    <label class="mr-b-10">Travellers</label>
                                                    <div class="">
                                                        <div class="input-group-icon" id="js-select-special">
                                                            <input class="dashboard-form-control extra-option" type="text" name="passengers" value="1 Person" disabled="disabled" id="info">
                                                        </div>
                                                        <div class="dropdown-select">
                                                            <ul class="list-room">
                                                                <li class="list-room__item mt-4">
                                                                    <ul class="list-person">
                                                                        <li class="list-person__item">
                                                                            <span class="name">Adults</span>
                                                                            <div class="quantity quantity1">
                                                                                <span class="minus a__minus">-</span>
                                                                                <input class="inputQty" type="number" min="1" value="1" name="adults" readonly>
                                                                                <span class="plus a__plus">+</span>
                                                                            </div>
                                                                        </li>
                                                                        <li class="list-person__item">
                                                                            <span class="name">Children</span>
                                                                            <div class="quantity quantity2">
                                                                                <span class="minus c__minus">-</span>
                                                                                <input class="inputQty" type="number" min="0" value="0" name="childs" readonly>
                                                                                <span class="plus c__plus">+</span>
                                                                            </div>
                                                                        </li>

                                                                        <ul class="common-list child-list"></ul>

                                                                        <li class="list-person__item">
                                                                            <span class="name">Infants</span>
                                                                            <div class="quantity quantity3">
                                                                                <span class="minus i__minus">-</span>
                                                                                <input class="inputQty" type="number" min="0" value="0" name="infants" readonly>
                                                                                <span class="plus i__plus">+</span>
                                                                            </div>
                                                                        </li>
                                                                        <ul class="common-list infant-list"></ul>
                                                                    </ul>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-12 col-lg-3">
                                                <div class="form-group frm-grp">
                                                    <label class="mr-b-10">Date of Departure</label>
                                                    <div class="date_picker_item">
                                                        <input type="text" class="dashboard-form-control datepicker_one extra-option" placeholder="Date of Departure" name="departure_flight_date" value="" autocomplete="off" required="">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-lg-3" id="return_flight_section" style="display: none;">
                                                <div class="form-group frm-grp">
                                                    <label class="mr-b-10">Return Date</label>
                                                    <div class="date_picker_item">
                                                        <input type="text" class="dashboard-form-control datepicker_one extra-option" placeholder="Return Date" name="return_flight_date" value="" autocomplete="off" required="">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-lg-3">
                                                <div class="form-group frm-grp">
                                                    <label class="mr-b-10">Cabin Class</label>
                                                    <div class="date_picker_item">

                                                        <select class="form-control extra-option" name="class">
                                                            <option value="">Any</option>
                                                            <option value="economy">Economy</option>
                                                            <option value="business">Business</option>
                                                            <option value="premium_economy">Premium Economy</option>
                                                            <option value="first">First</option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row">

                                            <!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#master_modal">Launch Modal</button> -->



                                            <div class="col-sm-12">
                                                <input type="hidden" name="steps" id="steps" readonly value="0">
                                                <input type="hidden" name="sorts" id="sorts" readonly value="total_amount">

                                                <button type="submit" class="btn btn-primary float-right mt-1 finish-next-btn mb-2 flight_search">Find Flights</button>
                                            </div>

                                        </div>

                                    </form>






                                </div>
                                <div class="tab-pane fade p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
                                    <script src="//tp.media/content?promo_id=2693&shmarker=311162&campaign_id=84&trs=20140&locale=en&hotel_type=&border_radius=5&plain=false&powered_by=false" charset="utf-8"></script>
                                </div>
                                <div class="tab-pane fade p-3" id="three" role="tabpanel" aria-labelledby="three-tab">
                                    <!-- <h5 class="card-title">Tab Card Three</h5> -->
                                    <script src="https://c91.travelpayouts.com/content?currency=USD&promo_id=4770&shmarker=311162&trs=171390&locale=en&layout=fluid&mode=train&departure-date=&arrival-date=&theme=white" charset="utf-8"></script>
                                </div>
                                <div class="tab-pane fade p-3" id="four" role="tabpanel" aria-labelledby="four-tab">
                                    <script src="//tp.media/content?promo_id=4578&shmarker=311162&campaign_id=130&trs=171390&locale=en&powered_by=false&border_radius=5&plain=false&show_logo=false&color_background=%23f5d361&color_button=%235a9854" charset="utf-8"></script>
                                    <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                                </div>

                            </div>


                        </div>

                        <div class="quick-link">
                            <a href="">
                                Skip go direct to planning
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>



    </section>


    <section class="create_a_schedule_sec">
        <div class="container">
            <h3 class="feature_heading">How The Planning Works</h3>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                    <div class="create_your_toute_left_text">
                        <div class="feature_number">
                            <span>01</span>
                        </div>

                        <h4 class="common_heading">Build your travel itinerary <span>and add rental, hotel, and </span> flight information. Then <span>add notes, and create a</span> schedule.</h4>
                        <div class="action_section">
                            <a href="https://www.planiversity.com/registration" class="action_button">Try Free Today!</a>
                        </div>
                    </div>

                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 oorder_2">
                    <div class="create_your_toute_right_map"> <img src="<?= SITE; ?>assets/images/home-page/one.png"> </div>
                </div>
            </div>
        </div>
    </section>

    <section class="create_your_toute_sec">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-7 col-lg-7 col-md-6 col-sm-6 padding_left">
                    <div class="create_your_toute_right_map"> <img src="<?= SITE; ?>assets/images/home-page/plans.png"> </div>
                </div>

                <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6">
                    <div class="create_your_toute_right_text">
                        <div class="feature_number">
                            <span>02</span>
                        </div>
                        <h4 class="common_heading">Add trip filters by locating <span>key resources, create </span>destination plans, explore<span> and add things to do.</span></h4>

                        <div class="action_section">
                            <a href="https://www.planiversity.com/registration" class="action_button">Try Free Today!</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="add_resource_filters_sec">
        <div class="curcle_img1 text-right"> <img src="<?= SITE; ?>assets/images/home-page/curcle_img1.png"> </div>
        <div class="container itinerary_section">
            <div class="add_resource_filters_content">
                <div class="add_resource_filters_heading">
                    <div class="feature_number item-center">
                        <span>03</span>
                    </div>
                    <h4 class="common_heading mt-4">Add copies of all documents, name your <span> trip, and export it into an organized</span> master itinerary PDF file</h4>
                </div>
                <img src="<?= SITE; ?>assets/images/home-page/app.png">
            </div>
        </div>
    </section>

    <section class="path_section">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 250" class="svg_path">
            <path fill="#0C246B" fill-opacity="1" d="M0,32L120,42.7C240,53,480,75,720,106.7C960,139,1200,181,1320,202.7L1440,224L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"></path>
        </svg>
    </section>

    <section class="export_your_packet_sec">


        <div class="container">
            <div class="video_place">
                <div class="export_your_packet_heading text-center">
                    <h3 class="common_heading-2">See How it Works</h3>
                </div>
                <div class="export_your_packet_item">
                    <div class="export_your_packet1">
                        <div class="effect-image-1 opacity-effect">
                            <a href="" data-toggle="modal" data-target="#video_popup">
                                <img id="starter" class="hide" src="<?= SITE; ?>assets/images/home-page/video_placeholder.jpg" alt="">
                                <div class="video_play_icon"> <i class="fa fa-play-circle" aria-hidden="true"></i> </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="travel_magazine">

                <div class="magazine_label">
                    <p>NEW!</p>
                </div>

                <div class="export_your_packet_heading text-center">
                    <h3 class="common_heading-2">Planivers Travel Magazine</h3>
                </div>

                <div class="magazine_content">

                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="magazine_content_heading">
                                <h4 class="common_heading_two">Subscribe today to <span>Planivers Travel </span> Magazine </h4>
                                <p>
                                    Planiversity has the travel planning down to a science. View our guide to learn a few planning tips and ideas for making the most thorough and detailed travel experience ahead of you.
                                </p>
                            </div>

                            <div class="action_section">
                                <a href="https://www.planiversity.com/registration" class="action_button">Subscribe Now!</a>
                            </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="magazine_img"> <img src="<?= SITE; ?>assets/images/home-page/magazine.png">
                            </div>
                        </div>



                    </div>

                </div>

            </div>



        </div>
        <div class="curcle_img3 text-right"> <img src="<?= SITE; ?>assets/images/home-page/half_oval.png"> </div>
    </section>

    <section class="the_ultimate_travel" id="guide-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                    <div class="the_ultimate_travel_img"> <img src="<?= SITE; ?>assets/images/home-page/guide.jpg">
                        <div class="the_ultimate_travel_img_heading">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                    <div class="the_ultimate_travel_right">
                        <div class="free_btn"> <a href="javascript:void(0)">Free</a> </div>
                        <div class="text">
                            <h3 class="ultimate_head1">Planiversity Guide<span>to Travel Planning</span></h3>
                            <p>Planiversity has the travel planning down to a science. View our guide to learn a few planning tips and ideas for making the most thorough and detailed travel experience ahead of you.</p>
                        </div>
                        <form id="enquiryvalidation">
                            <div class="the_ultimate_travel_input">
                                <div class="row">
                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 padding_right custom-col">
                                        <input type="email" id="email-subscribe" class="form-control" name="email" placeholder="Email Address" required>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <span id="error-msg" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 padding_left custom-col">
                                        <button type="button" id="btn-subscribe" class="btn_submit">Download Now</button>
                                    </div>

                                </div>
                            </div>
                            <input type="checkbox" name="news_updates" value="">
                            &nbsp;
                            <label for="news_updates" id="news_updates"> Opt in to recieve news and updates.</label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer_sec">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 custom-col">
                    <div class="footer_item1">
                        <h2 class="mobile_heading">Planiversity</h2>
                        <p>A revolutionary travel logistics <span>service, dedicated to consolidating</span> so much of your travel information. </p>
                        <ul class="social_media_footer list-unstyled">
                            <li><a href="https://twitter.com/planiversity" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="https://www.facebook.com/Planiversity" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li><a href="https://www.instagram.com/planiversity" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            <li><a href="https://www.linkedin.com/company/planiversity" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 custom-col">
                    <div class="footer_item2">
                        <h4>Site Map</h4>
                        <ul class="list-unstyled">
                            <li><a href="<?= SITE ?>contact-us">Contact Us</a></li>
                            <li><a href="<?= SITE ?>blog">Blog</a></li>
                            <li><a href="<?= SITE ?>data-security">Data Security</a></li>
                            <li><a href="">What You'll Get</a></li>
                            <li><a href="<?= SITE ?>sitemap.xml">Sitemap</a></li>
                        </ul>
                    </div>

                    <div class="footer_item2">
                        <h4>Legal</h4>
                        <ul class="list-unstyled">
                            <li><a href="https://getterms.io/view/98sYm/privacy/en-us">Privacy Policy</a></li>
                            <li><a href="https://getterms.io/view/98sYm/tos/en-us">Terms of Service</a></li>
                            <li><a href="https://getterms.io/view/98sYm/cookie/en-us">Cookies</a></li>
                            <li><a href="https://getterms.io/view/98sYm/aup/en-us">AUP</a></li>

                        </ul>
                    </div>

                    <div class="footer_item2">
                        <h4>Quick Links</h4>
                        <ul class="list-unstyled">
                            <li><a href="<?= SITE ?>registration">Free Demo</a></li>
                            <li><a href="<?= SITE ?>affiliate">Become an Affiliate</a></li>
                            <li><a href="">Partners</a></li>
                            <li><a href="https://planivers.com" target="_blank">Planivers Magazine</a></li>
                        </ul>
                    </div>

                </div>
                <!-- <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">

                </div> -->
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 custom-col">
                    <div class="footer_item3">
                        <h4>Contact Us</h4>
                        <p>Have a question? Need to get <span>something off your chest?</span> Email Us and we'll contact you...</p>
                        <address>
                            4023 Kennett Pike <span>Suite 690</span> Wilmington, DE 19807
                        </address>
                        <p><a href="mailto:plans@planiversity.com">plans@planiversity.com</a></p>
                    </div>
                </div>
            </div>
            <div class="copywrite_sec">
                <p>© Copyright. 2015 - <?= date('Y'); ?>. Planiversity, LLC. All Rights Reserved.</p>
            </div>
        </div>
        <div class="footer_curcle_bar"> <img src="<?= SITE; ?>assets/images/home-page/footer_curcle_bar.png"> </div>
    </footer>
    <div class="video_popusec">
        <!-- The Modal -->
        <div class="modal fade" id="video_popup">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close" id="pause-button" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <video width="100%" height="auto" id="video" controls>
                            <source src="<?= SITE; ?>assets/images/home-page/planiversity_video.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= SITE; ?>home-page/js/menuzord.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?= SITE; ?>home-page/js/jquery.validate.min.js"></script>

    <script>
        $('.image_download').click(function() {
            location.reload();
        });
    </script>
    <script>
        $('#video_popup').on('shown.bs.modal', function() {})
        $('#video_popup').on('hidden.bs.modal', function() {
            $('#video')[0].pause();
        })
    </script>
    <script>
        //AOS.init();
    </script>
    <script>
        (function($) {
            "use strict";
            var main_wind = $(window);
            var wWidth = $(window).width();
            jQuery(document).ready(function($) {
                jQuery("#menuzord").menuzord({
                    trigger: "hover",
                    indicatorFirstLevel: '<i class="fa fa-angle-down"></i>',
                    indicatorSecondLevel: '<i class="fa fa-angle-right"></i>'
                });

            });

        }(jQuery));
    </script>
    <script>
        (function(d) {
            'use strict';

            var str = d.getElementById('starter'),
                pyr = d.getElementById('player');

            str.classList.remove('hide');

            str.addEventListener('click',
                function() {
                    pyr.play();
                    str.classList.add('hide');
                }, false);

            pyr.addEventListener('ended',
                function() {
                    pyr.load();
                    str.classList.remove('hide');
                }, false);

        }(document));
    </script>
    <script type="text/javascript">
        $.validator.setDefaults({
            submitHandler: function() {
                form.submit();
            }
        });

        $(document).ready(function() {
            jQuery.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[a-z\s]+$/i.test(value);
            }, "Only alphabetical characters");
            $("#enquiryvalidation").validate({

                rules: {
                    name: {
                        required: true,
                        lettersonly: true
                    },
                    phone: {
                        required: true,
                        number: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    subject: "required",
                    country: "required"
                },

                errorElement: "em",
                errorPlacement: function(error, element) {
                    // Add the `help-block` class to the error element
                    error.addClass("help-block");

                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
                }
            });

        });
        //document.getElementById('query_page_url').value = window.location;
    </script>
    <script>
        $(document).ready(function() {
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 10,
                responsiveClass: true,
                autoplay: true,
                autoplayTimeout: 1000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 2,
                        nav: true
                    },
                    600: {
                        items: 4,
                        nav: false
                    },
                    1000: {
                        items: 3,
                        nav: true,
                        loop: false,
                        margin: 20
                    }
                }
            })
        })
    </script>
    <!-- <script type="text/javascript">
        "use strict";

        ! function() {
            var t = window.driftt = window.drift = window.driftt || [];
            if (!t.init) {
                if (t.invoked) return void(window.console && console.error && console.error("Drift snippet included twice."));
                t.invoked = !0, t.methods = ["identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on"],
                    t.factory = function(e) {
                        return function() {
                            var n = Array.prototype.slice.call(arguments);
                            return n.unshift(e), t.push(n), t;
                        };
                    }, t.methods.forEach(function(e) {
                        t[e] = t.factory(e);
                    }), t.load = function(t) {
                        var e = 3e5,
                            n = Math.ceil(new Date() / e) * e,
                            o = document.createElement("script");
                        o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
                        var i = document.getElementsByTagName("script")[0];
                        i.parentNode.insertBefore(o, i);
                    };
            }
        }();
        drift.SNIPPET_VERSION = '0.3.1';
        drift.load('99c7am4huua5');
    </script> -->
    <script>
        var count = 0;
        setInterval(function() {
            if (count < 4) {
                // $('.gadget-reel').attr('src', '<?= SITE; ?>assets/images/home-page/gadgets' + count + '.jpg');
                count++;
            } else {
                count = 0;
            }
        }, 5000)
    </script>
    <script>
        $(document).ready(function() {
            $('#btn-subscribe').click(function() {
                $.ajax({
                    type: "POST",
                    data: $('#enquiryvalidation').serialize(),
                    url: "download_guide.php",
                    dataType: "JSON",
                    success: function(response) {
                        if (response.status == "subscribed") {
                            // Start file download.
                            download("2022-Guide.pdf");
                            $('#email-subscribe').val("");
                        } else {
                            $('#error-msg').html("<b>" + response.title + "</b>");
                        }
                    },
                    error: function() {
                        console.log('Something went wrong');
                    }
                })
            })
        })

        function download(filename) {
            var element = document.createElement('a');
            element.setAttribute('href', '<?= SITE ?>home-page/' + filename);
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
        }
    </script>

</body>

</html>