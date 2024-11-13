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
    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <link href="<?= SITE; ?>home-page/css/home-style.css?v=20241001" rel="stylesheet">
    <link href="<?= SITE; ?>home-page/css/search.css?v=20230812" rel="stylesheet">
    <link href="<?= SITE; ?>home-page/css/custom.css?v=20230812" rel="stylesheet">
    <link href="<?= SITE; ?>home-page/css/home-responsive.css?v=20241001" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css">



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

    <!-- Reditus Tag Manager -->
    <script>
        (function(w, d, s, p, t) {
            w.gr = w.gr || function() {
                w.gr.q = w.gr.q || [];
                w.gr.q.push(arguments);
            };
            p = d.getElementsByTagName(s)[0];
            t = d.createElement(s);
            t.async = true;
            t.src = "https://app.getreditus.com/gr.js?_ce=60";
            p.parentNode.insertBefore(t, p);
        })
        (window, document, "script");
        gr("track", "pageview");
    </script>

    <script>
        (function(w) {
            var k = "nudgify",
                n = w[k] || (w[k] = {});
            n.uuid = "18a58770-0d86-497e-a136-5b439bf4ed8a";
            var d = document,
                s = d.createElement("script");
            s.src = "https://pixel.nudgify.com/pixel.js";
            s.async = 1;
            s.charset = "utf-8";
            d.getElementsByTagName("head")[0].appendChild(s)
        })(window)
    </script>

    <!-- Start TradeDoubler Landing Page Tag Insert on all landing pages to handle first party cookie-->
    <script language="JavaScript">
        (function(i, s, o, g, r, a, m) {
            i['TDConversionObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o), m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://svht.tradedoubler.com/tr_sdk.js?org=2307051&prog=324547&dr=true&rand=' + Math.random(), 'tdconv');
    </script>
    <!-- End TradeDoubler tag-->

    <!--Facebook Pixel Code-->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };

            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';

            n.queue = [];
            t = b.createElement(e);
            t.async = !0;

            t.src = v;
            s = b.getElementsByTagName(e)[0];

            s.parentNode.insertBefore(t, s)
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '871547440200746');

        fbq('track', 'PageView');
    </script>

    <script>
        var SITE = '<?php echo SITE; ?>';
        var FILE_PATH = '<?=ADMIN_URL_UPLOADS;?>';
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
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

    <section class="header_slider_sec">
        <header id="header">
            <div class="topbar fixed-header animated slideInDown">
                <div class="header1 po-relative">
                    <div class="container p-0">
                        <nav class="navbar navbar-expand-lg h1-nav">
                            <a class="navbar-brand" href="<?php echo SITE; ?>">
                            <img class="landing-logo" src="<?= SITE; ?>assets/images/home-page/quality_logo.png">
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
                                        <a class="nav-link" href="<?= SITE ?>marketplace">Marketplace</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= SITE ?>travel-booking">Travel Booking</a>
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



            <div class="banner_header_heading container-800">
            <div class="container">
                <div class="row">
                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12">

                        <div class="create_your_toute_left_text mockup_section">
                            <h2 class="common_heading"><span> Create and manage</span> every planning experience.</h2>
                            <div class="pragraph">
                                <p> Perfect for groups and individuals! </p>
                            </div>

                            <div class="top-action">
                                <a href="https://www.planiversity.com/registration" class="header_action_button">
                                    Start Planning Now
                                    <img class="arrow-icon" src="<?= SITE; ?>assets/images/home-page/arrow-icon.svg">
                                </a>
                            </div>

                            <div class="top-tag">

                            <div class="pragraph mt-3">
                                <p class="p-0"> Popular Features </p>
                            </div>

                                <ul class="tag_nav">
                                    <li class="category-link"><button class="tag-class active" value="0">
                                    <img class="check-icon" src="<?= SITE; ?>assets/images/home-page/check-icon.svg">
                                    Document Sharing</button></li>
                                    <li class="category-link"><button class="tag-class" value="1">
                                    <img class="check-icon" src="<?= SITE; ?>assets/images/home-page/check-icon.svg">
                                    Check-Ins</button></li>
                                </ul>

                                <ul class="tag_nav">
                                    <li class="category-link"><button class="tag-class" value="3">
                                    <img class="check-icon" src="<?= SITE; ?>assets/images/home-page/check-icon.svg">
                                    Group Communicating</button></li>
                                    <li class="category-link"><button class="tag-class" value="4">
                                    <img class="check-icon" src="<?= SITE; ?>assets/images/home-page/check-icon.svg">
                                    Event & Travel Supporting Services</button></li>
                                </ul>

                            </div>


                        </div>
                    </div>

                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 oorder_2">
                        <div class="trip-mockup"> <img class="header-image" src="<?= SITE; ?>assets/images/home-page/top_mobile_image.png?4"> </div>
                    </div>
                </div>
            </div>

        </div>


    </section>
<!--
    <div id="discount-christmas" class="discount-christmas hide-for-mob">
        <div  class="discount-christmas__body">
          <img class="discount-christmas__body--img" src="<?= SITE; ?>assets/images/home-page/discount-christmas.png">
                <button class="discount-christmas__body--button" onclick="hideElement('discount-christmas')"
                 >
                 Close</button>
        </div>
    </div>

    <div id="discount-christmas" class="discount-christmas discount-christmas__mob hide-for-web">
        <div  class="discount-christmas__body">
          <img class="discount-christmas__body--img" src="<?= SITE; ?>assets/images/home-page/discount-christmas-mob.png">
                <button class="discount-christmas__mob--button" onclick="hideElement('discount-christmas')"
                 >
                 Close</button>
        </div>
    </div>
-->
    <section style="overflow-x: hidden" class="create_a_schedule_sec container-800">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 oorder_2 mobile_landing">
                    <img class="mobile_landing_app_img" src="<?= SITE; ?>assets/images/home-page/mobile_landing_app.png?3">
                </div>
                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12">

                    <div class="create_your_toute_left_text">
                        <img class="devices-icon" src="<?= SITE; ?>assets/images/home-page/devices-icon.svg">
                        <span>EASY PLANNING TOOL</span>

                        <h4 class="common_heading my-4">At the core of every successful endeavour lies meticulous planning.</h4>
                        <p>We specialize in crafting team or individual trips and events that transcend expectations. From the initial
                            concept to the finest details, we shape experiences that cater to your team's unique essence.</p>
                        <div class="action_section">
                            <a href="https://www.planiversity.com/registration" class="action_button">Start your plan Today!</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="create_your_toute_sec container-800">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12">
                    <div class="create_your_toute_right_text">
                        <img class="devices-icon" src="<?= SITE; ?>assets/images/home-page/devices-icon.svg">
                        <span class="">ALL IN ONE SOLUTION</span>
                        <h4 class="common_heading my-4">Easily share your plan updates instantly with friends.</span></h4>
                        <p>Whether you're orchestrating a project, coordinating an event, or simply sharing plan updates,
                            our companion app transforms communication into a dynamic, interactive experience. Elevate
                            your team's efficiency, boost productivity, stay on top of information management, and
                            cultivate a sense of unity.</p>

                        <div class="action_section">
                            <a href="https://www.planiversity.com/registration" class="action_button">Start your plan Today!</a>
                        </div>
                    </div>

                    <div class="gray-background"></div>
                </div>

                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 padding_left">
                    <div class="create_your_toute_right_map d-flex justify-content-end"> <img class="toite-img" src="<?= SITE; ?>assets/images/home-page/filters_landing-min.png?3"> </div>
                </div>

            </div>
        </div>
    </section>

    <section class="add_resource_filters_sec container-800">
        <div class="container my-5">
            <div class="row">

            <div class="col-xl-7 col-lg-7 col-md-6 col-sm-6 padding_left">
                    <div class="create_your_toute_right_map"> <img class="mb-4 app_landing" src="<?= SITE; ?>assets/images/home-page/app_landing.png?3"> </div>
                </div>

                <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6 d-flex align-items-center">
                    <div class="shareable_plans_text">
                        <img class="devices-icon" src="<?= SITE; ?>assets/images/home-page/devices-icon.svg">
                        <span>SHAREABLE PLANS</span>
                        <h4 class="common_heading my-4">The Plan doesn't end with execution.</span></h4>
                        <p>In fact, that's where it truly begins. We understand that communication is the thread that weaves every
                            experience together. That's why we've integrated innovative communication solutions that keep your team
                            connected and updated at all times.</p>

                        <div class="action_section">
                            <a href="https://www.planiversity.com/registration" class="action_button">Start your plan Today!</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="add_what_you_get">
        <div class="curcle_img5 text-left"> <img src="<?= SITE; ?>assets/images/home-page/left_oval.png"> </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="add_what_you_get_content">
                        <div class="add_what_you_heading">
                            <h3 class="what_you_heading mt-4">What You'll Get</h3>
                        </div>

                        <div class="content_inside">
                            <img src="<?= SITE; ?>assets/images/home-page/view_plan.png" class="file_download">
                        </div>

                        <div class="action_section">
                            <a href="https://www.planiversity.com/registration" class="action_button">Try Free Today!</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

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
                                <img id="starter" class="hide" src="<?= SITE; ?>assets/images/home-page/video_placeholder_main.jpg" alt="">
                                <div class="video_play_icon"> <i class="fa fa-play-circle" aria-hidden="true"></i> </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <section class="add_marketplace_sec">
        <div class="curcle_img1 text-right marketplace-circle"> <img src="<?= SITE; ?>assets/images/home-page/top_right.png"> </div>
        <div class="container marketplace_section">
            <div class="add_marketplace_content">
                <div class="add_marketplace_heading">
                    <h3 class="marketplace_heading">Planiversity Marketplace</h4>
                </div>
            </div>


            <div class="top_header_heading">

                <div class="container">

                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">

                            <div class="banner-heading">

                                <h2>Find the right deal to take you or your business to the next level.</h2>

                                <p>Find courses, books, and services at a fraction of the cost, and provided by <span>the best in the business.</span></p>

                            </div>

                            <div class="banner-search mb-5">
                                <div class="s002">

                                    <form id="search_form">

                                        <div class="inner-form">
                                            <div class="input-field first-wrap">
                                                <div class="icon-wrap">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                                <input id="search" name="search" type="text" placeholder="What are you looking for?" />
                                            </div>

                                            <div class="input-field fouth-wrap">

                                                <select id="category_field" name="category_field" class="form-control form_category">
                                                <option value="" selected>Choose Category</option>
                                                <option value="0">All</option>
                                                <option value="3"> Travel Services </option>
                                                <option value="4"> Event Services </option>
                                                <option value="10"> Adventure </option>
                                                <option value="11"> Concierge Services </option>
                                                </select>

                                            </div>

                                            <div class="input-field fifth-wrap">
                                                <button class="btn-search e_button" type="submit">Search</button>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                            <div class="banner-image">

                                <img src="<?= SITE; ?>marketplace/images/test.png" class="into-image">

                                <div class="top-banner-info">
                                    <div class="icon-info">
                                        <img src="<?= SITE; ?>marketplace/images/quality-icon.png">
                                    </div>

                                    <div class="title-info">
                                        <h4>Proof of quality</h4>
                                        <p>All sellers fully vetted</p>
                                    </div>

                                </div>

                                <div class="right-banner-info">
                                    <div class="icon-info">
                                        <img src="<?= SITE; ?>marketplace/images/safe-icon.png">
                                    </div>

                                    <div class="title-info">
                                        <h4>Safe and secure</h4>
                                        <p>Only secure payment options</p>
                                    </div>

                                </div>

                                <div class="bottom-banner-info">

                                    <div class="people-info">
                                        <h4>Services brought directly to you</h4>
                                    </div>

                                    <div class="icon-people">
                                        <img src="<?= SITE; ?>marketplace/images/people-icon.png">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>


    <section class="marketplace-content" id="marketplace-content">

        <div class="container">

            <div class="marketplace_item">

                <div class="marketplace_heading">

                    <h3>Browse Services</h3>

                    <div class="marketplace_subheading">
                        <div class="search_result">
                            <p><span class="service_count">0</span> Services Found</p>
                        </div>

                        <div class="marketplace-category-nav">
                            <ul class="category_nav">
                                <li class="category-link"><button class="button-class active" value="0">All</button></li>
                                <li class="category-link"><button class="button-class" value="3"> Travel Services </button></li>
                                <li class="category-link"><button class="button-class" value="4">Event Services </button></li>
                                <li class="category-link"><button class="button-class" value="10">Adventure </button></li>
                                <li class="category-link"><button class="button-class" value="11">Concierge Services </button></li>
                            </ul>
                        </div>

                    </div>

                </div>


                <div class="service_load">
                    <div class="loading_section" style="display: none;">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                    <div class="row" id="service_content"></div>

                </div>

            </div>

        </div>

    </section>

    <section class="export_your_packet_sec">
        <div class="container">

            <div class="travel_magazine">


                <div class="export_your_packet_heading text-center">
                    <h3 class="common_heading-2">Planivers Travel Magazine</h3>
                </div>

                <div class="magazine_content">

                    <div class="row">

                        <div class="col-xl-5 col-lg-6 col-md-6 col-sm-6 pl-5">
                            <div class="magazine_content_heading">
                                <h4 class="common_heading_two">Check it out <span>today</span> </h4>
                                <p>
                                    Planiversity has it's own travel magazine, and it's loaded with relevant information, entertainment, and inspiration; all for an affordable price.
                                </p>
                            </div>

                            <div class="action_section">
                                <a href="https://www.planiversity.com/registration" class="action_button">Learn More!</a>
                            </div>

                        </div>

                        <div class="col-xl-7 col-lg-6 col-md-6 col-sm-6">
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

            <div class="carousel-content">

                <div class="owl-carousel owl-theme pdf-content">

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="the_ultimate_travel_img"> <img src="<?= SITE; ?>assets/images/home-page/guide_magazine_2023.jpg">
                                <div class="the_ultimate_travel_img_heading">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="the_ultimate_travel_right">
                                <div class="free_btn"> <a href="javascript:void(0)">Free</a> </div>
                                <div class="text">
                                    <h3 class="ultimate_head1">Planiversity 2023 Guide<span>to Travel Planning</span></h3>
                                    <p>Planiversity has the travel planning down to a science. View our guide to learn a few planning tips and ideas for making the most thorough and detailed travel experience ahead of you.</p>
                                </div>
                                <form id="pdf_form_1">
                                    <div class="the_ultimate_travel_input">
                                        <div class="row">

                                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 padding_right custom-col">
                                                <input type="email" id="email-subscribe" class="form-control email" name="email" placeholder="Email Address" required>
                                            </div>

                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <span id="error-msg" class="text-danger"></span>
                                            </div>

                                            <div class="col-sm-12 mt-2">
                                                <div class="form-group custom-group">
                                                    <div class="g-recaptcha" data-sitekey="6LcIzPIUAAAAANWbMmJsjYbO6aE1R-nGsXD79AbD" data-callback="recaptchaCallbackOne" data-expired-callback="recaptchaExpiredOne"></div>
                                                    <input id="grecaptcha_one" name="grecaptcha" type="text" readonly style="opacity: 0; position: absolute; top: 0; left: 0; height: 1px; width: 1px;">
                                                    <!-- readonly style="opacity: 0; position: absolute; top: 0; left: 0; height: 1px; width: 1px;" -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 padding_left custom-col">
                                                <button type="submit" class="btn_submit pdf_submit" value="p1">Download Now</button>
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

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="the_ultimate_travel_img"> <img src="<?= SITE; ?>assets/images/home-page/guide_magazine.jpg">
                                <div class="the_ultimate_travel_img_heading">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                            <div class="the_ultimate_travel_right">
                                <div class="free_btn"> <a href="javascript:void(0)">Free</a> </div>
                                <div class="text">
                                    <h3 class="ultimate_head1">Planiversity 2022 Guide<span>to Travel Planning</span></h3>
                                    <p>Planiversity has the travel planning down to a science. View our guide to learn a few planning tips and ideas for making the most thorough and detailed travel experience ahead of you.</p>
                                </div>
                                <form class="pdf_form_2">
                                    <div class="the_ultimate_travel_input">
                                        <div class="row">
                                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 padding_right custom-col">
                                                <input type="email" id="email-subscribe" class="form-control email" name="email" placeholder="Email Address" required>
                                            </div>
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <span id="error-msg" class="text-danger"></span>
                                            </div>

                                            <div class="col-sm-12 mt-2">
                                                <div class="form-group custom-group">
                                                    <div class="g-recaptcha" data-sitekey="6LcIzPIUAAAAANWbMmJsjYbO6aE1R-nGsXD79AbD" data-callback="recaptchaCallbackTwo" data-expired-callback="recaptchaExpiredTwo"></div>
                                                    <input id="grecaptcha_two" name="grecaptcha" type="text" readonly style="opacity: 0; position: absolute; top: 0; left: 0; height: 1px; width: 1px;">
                                                    <!-- readonly style="opacity: 0; position: absolute; top: 0; left: 0; height: 1px; width: 1px;" -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 padding_left custom-col">
                                                <button type="submit" class="btn_submit pdf_submit" value="p2">Download Now</button>
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

                <div class="owl-theme">
                    <div class="owl-controls">
                        <div class="custom-nav owl-nav"></div>
                    </div>
                </div>

            </div>



        </div>
    </section>


    <div class="app-section">
        <div class="container">
            <div class="call-to-action">

                <h2>Keep Planiversity in your pocket!</h2>
                <p class="tagline">Install the Planiversity app to keep track of your most up-to-date plans and notifications.</p>

                <div class="my-4">
                    <a href="https://apps.apple.com/us/app/planiversity/id1641601686" target="_blank" class="app_link effect-class"><img src="<?= SITE; ?>assets/images/app_store.webp" alt="App Store"></a>
                    <a href="https://play.google.com/store/apps/details?id=com.planiversity" target="_blank" class="app_link effect-class"><img src="<?= SITE; ?>assets/images/google_play.webp" alt="Google play"></a>
                </div>

                <p class="text-primary"><small><i> OR </i></small></p>
                <div class="app_scan effect-class">
                    <img src="<?= SITE; ?>assets/images/app_scan.png">
                </div>

            </div>
        </div>
    </div>

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

    <div class="modal fade modal-blur" tabindex="-1" role="dialog" id="master_modal" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content custom-content">
                <!-- <div class="modal-header">
                    <h5 class="modal-title">Service Details</h5>
                </div> -->
                <div class="modal-body">

                    <div class="row">

                        <div class="col-lg-12">

                            <div class="service_head">
                                <div class="service_heading">
                                    <img src="https://via.placeholder.com/200/FFFFFF/000000/?text=loading" class="heading_img">
                                    <h3>I will do mobile app development for ios and android</h3>
                                    <h5>Courses & Learning</h5>
                                </div>

                                <div class="payment_info">

                                    <p>Regular Price : <span>$350</span></p>
                                    <p>Sale Price : <span>$250</span></p>
                                    <p>Member Price : <span>$230</span></p>

                                </div>

                            </div>

                            <div class="service-footer no-footer">
                                <div class="seller-info">
                                    <img src="https://www.planiversity.com/staging/ajaxfiles/profile/IMG_1466443467.png" class="author_image">
                                    <p id="author_name">Wanda Runo</p>
                                </div>
                                <div class="rating-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ratings">
                                            <i class="fa fa-star rating-color"></i>
                                            <p><span class="rating">0 </span> <span> | </span> <span class="review">0</span> <span>reviews</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=""></div>

                            <div class="seller_info">

                                <p>Loading...</p>

                            </div>

                            <div class="service_body">

                                <h3>About This Service</h3>

                                <div class="service_content"></div>

                            </div>

                        </div>



                    </div>


                </div>
                <div class=" modal-footer">
                    <button type="button" class="btn btn-secondary master_modal_click" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary buy-btn" value=""><i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy</button>
                </div>
            </div>
        </div>
    </div>

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
                            <source src="<?= SITE; ?>assets/file/planiversity_intro.mp4" type="video/mp4">
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
    <script src="<?php echo SITE; ?>js/jquery.validate.min.js"></script>
    <script src="<?php echo SITE; ?>js/additional-methods.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="<?= SITE; ?>assets/js/bootstrap-datepicker.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script type="text/javascript" src="<?= SITE; ?>marketplace/js/landing-page.js?v=20230501"></script>

    <script>

    function hideElement(id){
        document.querySelectorAll(`#${id}`).forEach(box => { box.style.display = "none" })
    }

    var owl = $('.owl-carousel');
        owl.owlCarousel({
            stagePadding: 10,
            mouseDrag: false,
            loop: false,
            margin: 10,
            nav: true,
            autoplay: false,
            lazyLoad: true,
            autoplayTimeout: 50000,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });

    $('.file_download').on('click', function() {

            var filePath = "Randy_s_Trip_to_Los_Angeles-1469-40.pdf";

            $.ajax({
                url: "<?= SITE . 'assets/file/' ?>" + filePath,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(data);
                    a.href = url;
                    a.download = filePath;
                    document.body.append(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                }
            });


        });
        $('.image_download').click(function() {
            location.reload();
        });
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

            // pyr.addEventListener('ended',
            //     function() {
            //         pyr.load();
            //         str.classList.remove('hide');
            //     }, false);

        }(document));
    </script>


<script type="text/javascript">
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
 </script>

    <script>


        function recaptchaCallbackOne() {
            var response = grecaptcha.getResponse();
            $("#grecaptcha_one").val(response);
        }

        function recaptchaExpiredOne() {
            $("#grecaptcha_one").val("");
        }

        function recaptchaCallbackTwo() {
            var response = grecaptcha.getResponse();
            $("#grecaptcha_two").val(response);
        }

        function recaptchaExpiredTwo() {
            $("#grecaptcha_two").val("");
        }

        $(document).on("click", ".pdf_submit", function(e) {

            var item = $(this).val();

            var form_select = $(this).closest("form");

            form_select.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    grecaptcha: {
                        required: true,
                    }
                },
                messages: {

                    email: {
                        required: 'Please type your email',
                        email: 'Please type valid email'
                    },
                    grecaptcha: {
                        required: 'Please check the recaptcha'
                    }
                },
                submitHandler: function(form) {


                    $.ajax({
                        type: "POST",
                        data: $(form).serialize() + '&item=' + item,
                        url: "download_guide.php",
                        dataType: "JSON",
                        success: function(response) {
                            console.log('response', response);
                            if (response.data.message == "success") {
                                // Start file download.
                                download(response.data.item);
                                $('.email').val("");
                                recaptchaExpiredOne();
                                recaptchaExpiredTwo();

                            } else {
                                $('#error-msg').html("<b>" + response.title + "</b>");
                            }
                        },
                        error: function() {
                            console.log('Something went wrong');
                        }
                    })


                }, // Do not change code below
                // errorPlacement: function(error, element) {
                //     error.insertAfter(element.parent());
                // }
            });

            //console.log('form', form);
        });

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
