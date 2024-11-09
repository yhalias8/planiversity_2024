<?php
include_once("../config.ini.php");
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

    <link href="css/menuzord.css" rel="stylesheet" type="text/css" />

    <link href="css/mystyle.css?v=100" rel="stylesheet">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link href="css/responsive.css?v=100" rel="stylesheet">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }
        
        .become_an_affiliate_for_submt img {
        width: 40px;
        margin-right: 5px;
        }
        .action_button {
            padding: 8px 30px !important;
            border-radius: 5px !important;
        }        
        .action_button:hover {
            opacity: 0.8;
        }        
    </style>



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
    
    <?php include_once("../includes/reditus_tracking_script.php"); ?>
    

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

    <section class="Affiliates_header_banner_sec">

        <header id="header">

            <div class="container">

                <div class="beauty">

                    <div id="menuzord" class="menuzord">

                        <a href="<?= SITE ?>" title="" class="menuzord-brand">

                            Planiversity

                        </a>

                        <a href="javascript:void(0)" title="" class="icon_baar">

                            <div></div>

                            <div></div>

                            <div></div>

                        </a>

                        <ul class="menuzord-menu menuzord-right">

                            <li class="active">

                                <a href="">Process</a>

                            </li>

                            <li><a href="">Commision Rates</a></li>

                            <!--<li class="sign_in_btn"><a href="#contact">Try Now</a></li>-->

                        </ul>

                    </div>

                    <!--/#menuzord-->

                </div>

            </div>

        </header>

        <div class="container">

            <div class="Affiliates_header_heading_sec">

                <div class="row">

                    <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12">

                        <div class="Affiliates_header_heading_left">

                            <h2>Become a <span>Planiversity</span> <span>Affiliate</span></h2>

                            <p style="font-size: 18px;">We at Planiversity know that the success of many often depends on a solid team, and therefore we decided to strengthen ours through the involvement of people like you! We want the travelers, the adventurers, the solo-wanderers, and the influencers of this field to join us in giving Planiversity to the world. We are a travel software team who is Dedicated—with a capitol D—to making travel more enjoyable by improving the planning process, thus making the experience more informed, organized, and as stress-free as possible. Become part of something on its way to becoming the biggest thing in future travel!</p>

                            

                        </div>

                    </div>



                    <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">

                        <div class="Affiliates_header_heading_right_img">

                            <img src="images/mobile_mockup_img.png">



                            <div class="contact_us_now_btn mobile_contact_btn">

                                <a href="#contact">Contact Us Now!</a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section id="contact" class="sed_ut_perspiciatis_sec">

        <div class="container">

            <div class="row">

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 order_2">

                    <div class="become_an_affiliate_form">

                        <form id="form-contact" method="post">

                            <div class="become_an_affiliate_form_heading">

                                <h2>Become an Affiliate</h2>

                                <p>If you want to become an affiliate, please click the button below and sign up.</p>

                            </div>

                            <span id="error-msg" class="text-danger"></span>

                            

                            <div class="row">



                                <div class="col-xl-12 col-lg-12 col-md-12">

                                    <div class="become_an_affiliate_for_submt">

                             <a href="https://app.getreditus.com/affiliate/sign_up/planiversity" target="_blank">
                             <button type="button" id="btn-contact" class="btn_submit action_button"><img src="<?=SITE?>/images/handshake_signup.png">SIGNUP NOW</button>
                             </a>

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">

                    <div class="become_an_affiliate_text">

                        <div class="become_an_affiliate_text_head">

                            <h3>Affiliate</h3>

                            <!-- <h2>Sed ut <span>perspiciatis</span></h2> -->

                            <p>Looking for reasons why you should get involved in Planiversity’s Affiliate program, consider these points:</p>

                        </div>

                        <div class="become_an_affiliate_text_item">

                            <ul class="list-unstyled">

                                <li>Free use of the service for affiliates</li>

                                <li>Top end commission rates back</li>

                                <li>Bonuses for Top Affiliates </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- <section class="sed_ut_perspiciatis_sec">

        <div class="container">

            <div class="row">

                <div class="col-xl-12">

                    <div class="sed_ut_perspiciatis_heading">

                        <h3>Affiliate</h3>

                        <h2>Sed ut <span>perspiciatis</span></h2>

                    </div>

                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-12">

                    <div class="sed_ut_perspiciatis_left">

                        <p>Sed ut perspiciatis unde omnis iste natus error sit

                            voluptatem accusantium doloremque laudantium,

                            rem aperiam, eaque ipsa quae ab illo inventore veritatis

                            et quasi architecto beatae vitae dicta sunt explicabo.</p>

                    </div>

                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-12">

                    <div class="sed_ut_perspiciatis_right">

                        <p>Sed ut perspiciatis unde omnis iste natus error sit

                            voluptatem accusantium doloremque laudantium,

                            rem aperiam, eaque ipsa quae ab illo inventore veritatis

                            et quasi architecto beatae vitae dicta sunt explicabo.</p>

                    </div>

                </div>

            </div>

        </div>

    </section> -->

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

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">

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

                <p>© Copyright. 2015 - <?= date('Y'); ?>. Planiversity, LLC. All Rights Reserved.</p>

            </div>

        </div>

        <!--<div class="footer_curcle_bar">

        <img src="images/footer_curcle_bar.png">    

        </div>-->

    </footer>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.minjs"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="js/menuzord.js"></script>

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



        $(document).ready(function() {

            $('#btn-contact').click(function() {

                $.ajax({

                    type: "POST",

                    data: $('#form-contact').serialize(),

                    url: "contact.php",

                    dataType: "JSON",

                    success: function(response) {

                        if (response.status == true) {

                            Swal.fire({

                                title: 'Thank You!',

                                text: "We will get to you soon!",

                                icon: 'success',

                                showCancelButton: false,

                                confirmButtonColor: '#3085d6',

                                cancelButtonColor: '#d33',

                                confirmButtonText: 'Okay'

                            }).then((result) => {

                                if (result.isConfirmed) {

                                    window.location.reload();

                                }

                            })

                        } else {

                            $('#error-msg').html("<b>" + response.message + "</b>");

                        }

                    },

                    error: function() {

                        console.log('Something went wrong');

                    }

                })

            })

        })
    </script>




</body>

</html>