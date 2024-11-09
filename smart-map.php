<?php
include_once("config.ini.php");

if ($auth->isLogged()) {
    header('location: ' . SITE . 'welcome');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planiversity</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?= SITE ?>affiliate/css/menuzord.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE ?>affiliate/css/mystyle.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="<?= SITE ?>affiliate/css/responsive.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Google Tag Manager -->

    <style>
        #smt-covwidget-widgetdisclaimer {
            padding: 10px !important;
        }

        .smt-covwidget-checker {
            flex-basis: 10% !important;
        }

        /* .smt-covwidget-disclaimer > p {
            display: none !important;
        } */
    </style>

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



    <noscript>

        <img height="1" width="1" src="https://www.facebook.com/tr?id=871547440200746&ev=PageView&noscript=1" />

    </noscript>

    <!--End Facebook Pixel Code-->
</head>

<body>
    <!-- Google Tag Manager (noscript) -->

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <!-- End Google Tag Manager (noscript) -->
    <section class="smart_map_header_sec">
        <header id="header">
            <div class="container">
                <div class="beauty">
                    <div id="menuzord" class="menuzord">
                        <a href="<?= SITE ?>" title="" class="menuzord-brand">
                            Planiversity
                        </a>
                        <!-- <a href="javascript:void(0)" title="" class="icon_baar">
                            <div></div>
                            <div></div>
                            <div></div>
                        </a> -->
                        <ul class="menuzord-menu menuzord-right">
                            <li class="active"><a href="<?= SITE ?>about-us">About Us</a></li>
                            <li><a href="<?= SITE ?>faq">FAQs</a></li>
                            <li><a href="<?= SITE ?>select-your-payment">What it Costs</a></li>
                            <li><a href="<?= SITE ?>smart-map">Smart Travel</a></li>
                            <li><a href="<?= SITE ?>contact-us">Contact Us</a></li>
                            <li class="sign_in_btn"><a href="<?= SITE ?>login">Sign In</a></li>
                        </ul>
                    </div>
                    <!--/#menuzord-->
                </div>
            </div>
        </header>
    </section>
    <section class="see_whats_open_travel">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="see_whats_open_travel_head">
                        <h3>Planiversity Smart Map</h3>
                        <h2>See what's open for travel</h2>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="see_whats_open_travel_text">
                        <p>Ready to travel but not sure where to go? Use the map below to quickly check where you can travel and
                            what you can do there for your next trip.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="map_tabpanel_sec">
        <div class="container">
            <div class="map_tabpanel_shedow">
                <smt-gcovwidget apikey='010e44f6-6d2b-40a9-92aa-582286f33308' lang='en'></smt-gcovwidget>
                <!--Load Smartvel libs-->
                <script src="https://cdn.smartvel.com/scripts/gcovwidget/boot.min.js"></script>
            </div>
        </div>
    </section>
    <section class="sed_ut_perspiciatis_sec">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="sed_ut_perspiciatis_heading">
                        <h3>Smart Map</h3>
                        <h2>Know before you go</h2>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="sed_ut_perspiciatis_left">
                        <p>In order to ensure customers remain up-to-date on the latest COVID related information during their travel, Planiversity teamed up with Smartvel, travel planning solutions provider.</p>
                        <p>Powered by AI driven software and updated from information tracked by the International Air Transport Association (IATA), we provide you to most updated information about your destination.</p>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-12">
                    <div class="sed_ut_perspiciatis_right">
                        <p>No longer will travelers have to shop around for the information they need before they go. Find out before you even leave the house what the COVID restrictions at your destination are, or the types of masks required, and so on.</p>
                        <p>Explore the map functions and note the restrictions specific to any travel destination in mind. Let Planiversity supply the details and critical information, so that you have less exhausting research to chase.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer_sec">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                    <div class="footer_item1">
                        <h2 class="mobile_heading">Planiversity</h2>
                        <p>A revolutionary travel logistics <span>service, dedicated to consolidating</span> so much of your travel information. </p>

                        <ul class="social_media_footer list-unstyled">
                            <li><a href="" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li><a href="" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                            <li><a href="" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
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
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
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
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                    <div class="footer_item3">
                        <h4>Contacts</h4>
                        <p>Have a question? Need to get <span>something off your chest?</span>
                            Email Us and we'll contact you...</p>
                        <address>
                            4023 Kennett Pike <span>Suite 690</span> Wilmington, DE 19807
                        </address>
                        <p><a href="mailto:plans@planiversity.com">plans@planiversity.com</a></p>
                    </div>
                </div>
            </div>
            <div class="copywrite_sec">
                <p>© Copyright. 2015 - 2021. Planiversity, LLC. All Rights Reserved.</p>
            </div>
        </div>
        <!--<div class="footer_curcle_bar">
        <img src="images/footer_curcle_bar.png">    
        </div>-->
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.minjs"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= SITE ?>affiliate/js/menuzord.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();
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
</body>

</html>