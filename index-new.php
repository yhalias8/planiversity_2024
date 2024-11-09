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
  <link href="<?php echo SITE; ?>home-page/css/menuzord.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo SITE; ?>home-page/css/mystyle.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link href="<?php echo SITE; ?>home-page/css/responsive.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="<?php echo SITE; ?>home-page/js/owl.carousel.js"></script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146873572-1"></script>
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

<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->

  <section class="header_slider_sec">
    <header id="header">
      <div class="container">
        <div class="beauty">
          <div id="menuzord" class="menuzord"> <a href="<?php echo SITE; ?>/staging" title="" class="menuzord-brand"> Planiversity </a> <a href="<?php echo SITE; ?>/staging" title="" class="icon_baar">
              <div></div>
              <div></div>
              <div></div>
            </a>
            <ul class="menuzord-menu menuzord-right">
              <li class=""> <a href="about-us">About Us</a></li>
              <li><a href="faq">FAQs</a></li>
              <li><a href="select-your-payment">What it Costs </a></li>
              <li><a href="data-security">Data Security </a></li>
              <li><a href="contact-us">Contact Us </a></li>
              <?php
              if ($auth->isLogged() && $userdata['customer_number'] == '62f6d52f7e') { ?>
                <li><a href="apanel/users">Admin</a></li>
                <li><a class="btn free-trial-button" href="logout">Log Out</a>
                <?php } else if ($auth->isLogged()) { ?>
                <li><a class="btn free-trial-button" href="logout">Log Out</a>
                <?php   } else { ?>
                <li class="sign_in_btn"><a href="" id="show_loginform">Sign In</a>
                <?php } ?>
                </li>
                <!--<li class="sign_in_btn"><a href="" id="show_loginform">Sign In</a></li>-->
            </ul>
          </div>
          <!--/#menuzord-->
        </div>
      </div>
    </header>
    <img src="<?php echo SITE; ?>assets/images/home-page/header_banner.png" class="header_banner">
    <div class="banner_header_heading">
      <h2 data-aos="zoom-in" data-aos-duration="2000">Consolidated Travel <span>ltinerary Management</span></h2>
      <div class="pragraph">
        <p data-aos="fade-up" data-aos-duration="3000"> A revolutionary travel logistics service, dedicated to consolidating so much of your <span>travel information. It's designed with efficiency, security and safety as a priority.</span></p>
      </div>
    </div>
  </section>
  <div class="video_header_sec"> <a href="" data-toggle="modal" data-target="#video_popup"> <img id="starter" class="hide" src="<?php echo SITE; ?>assets/images/home-page/video_bg.png" alt="">
      <div class="video_play_icon"> <i class="fa fa-play-circle" aria-hidden="true"></i> </div>
    </a> </div>
  <section class="how_planning_text">
    <div class="container">
      <h2 data-aos="fade-left">How Planning Works</h2>
      <div class="btn_button_sec"> <a href="" class="btn_button" data-aos="fade-right">Try Free Today!</a> </div>
    </div>
  </section>
  <section class="create_your_toute_sec">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-5 col-lg-5 col-md-6 col-sm-6">
          <div class="create_your_toute_left_text">
            <h2 class="common_heading" data-aos="fade-down" data-aos-duration="1500">1. Create Your <span>Route</span></h2>
            <p data-aos="fade-up" data-aos-duration="2000">Start the process by <span>selecting your method of</span> <span>travel and creating your route.</span></p>
          </div>
        </div>
        <div class="col-xl-7 col-lg-7 col-md-6 col-sm-6 padding_right">
          <div class="create_your_toute_right_map" data-aos="flip-left" data-aos-easing="ease-out-cubic" data-aos-duration="2000"> <img src="<?php echo SITE; ?>assets/images/home-page/map_banner_oval_img.png"> </div>
        </div>
      </div>
    </div>
  </section>
  <section class="create_a_schedule_sec">
    <div class="container">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 oorder_2">
          <div class="create_your_toute_right_map" data-aos="fade-right" data-aos-offset="300" data-aos-easing="ease-in-sine"> <img src="<?php echo SITE; ?>assets/images/home-page/create_a_schedule_img.png"> </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
          <div class="create_your_toute_left_text desktop_view_text" data-aos="fade-left" data-aos-offset="300" data-aos-easing="ease-in-sine">
            <h2 class="common_heading">02. Create a Schedule <span>and Add Trip Notes</span></h2>
            <p>Keep track of your time and trip details, not <span>leaving either to chance. Adding trip notes to your</span> plan is a simple way to keep yourself reminded of <span>the little things while on the go.</span></p>
          </div>
          <div class="mobile_view_text">
            <h2 class="common_heading">02. Create a <span>Scheduleand and</span> Add Trip Notes</h2>
            <p>Keep track of your time and <span>trip details, not leaving</span> <span>either to chance. Adding</span> <span>trip notes to your plan is a</span> simple way to keep yourself <span>reminded of the little things</span> while on the go.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="add_resource_filters_sec">
    <div class="curcle_img1 text-right"> <img src="<?php echo SITE; ?>assets/images/home-page/curcle_img1.png"> </div>
    <div class="container">
      <div class="add_resource_filters_content">
        <div class="add_resource_filters_heading desktop_view_text">
          <h2 class="common_heading" data-aos="zoom-in">03. Add Resource Filters</h2>
          <p data-aos="fade-left" data-aos-offset="300" data-aos-easing="ease-in-sine"> <span>In our filter section, choose what you deem critical. Resource</span> <span>information, such as weather, embassy location, hospitals, service</span> stations, parking garages, police stations, and more are selected here. </p>
        </div>
        <div class="mobile_view_text">
          <h2 class="common_heading" data-aos="zoom-in">03. Add <span>Resource Filters</span></h2>
          <p data-aos="fade-left" data-aos-offset="300" data-aos-easing="ease-in-sine"> <span> In our filter section, choose</span> what you deem critical. <span>Resource information, such</span> <span>as weather, embassy</span> <span>location, hospitals, service</span> <span>stations, parking garages,</span> <span>police stations, and more</span> are selected here. </p>
        </div>
        <img src="<?php echo SITE; ?>assets/images/home-page/add_resource_filters_img.png">
      </div>
    </div>
  </section>
  <section class="upload_travel_document_sec">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
          <div class="create_your_toute_left_text desktop_view_text">
            <h2 class="common_heading" data-aos="fade-right" data-aos-offset="300" data-aos-easing="ease-in-sine"> 04. Upload Travel <span>Documents</span></h2>
            <p data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">One of the best features of this service is being able to consolidate your trip documentation. Forget having to sift through emails; keep the important information here instead.</p>
          </div>
          <div class="mobile_view_text">
            <h2 class="common_heading" data-aos="fade-right" data-aos-offset="300" data-aos-easing="ease-in-sine"> 04. Upload <span>Travel</span> Documents</h2>
            <p data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500"> <span>One of the best features of</span> this service is being able to <span>consolidate your trip</span> documentation. Forget <span>having to sift through</span> <span>emails; keep the important</span> information here instead.</p>
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 padding_right">
          <div class="create_your_toute_right_map" data-aos="zoom-in"> <img src="<?php echo SITE; ?>assets/images/home-page/upload_travel_document_img.png" class="upload_travel_docume_desktop"> <img src="<?php echo SITE; ?>assets/images/home-page/upload_travel_document_img2.png" class="upload_travel_docume_mobile"> </div>
        </div>
      </div>
    </div>
  </section>
  <section class="export_your_packet_sec">
    <div class="container">
      <div class="export_your_packet_heading text-center">
        <h2 class="common_heading" data-aos="fade-right">05. Export your <span>Packet</span></h2>
        <p data-aos="fade-left">Export your packet for an <span>awesome travel experience.</span></p>
      </div>
      <div class="export_your_packet_item">
        <div class="export_your_packet1" data-aos="zoom-out-up"> <img src="<?php echo SITE; ?>assets/images/home-page/export_your_packet1.png" class="export_packet_img1"> <img src="<?php echo SITE; ?>assets/images/home-page/export_your_packet2.png" class="export_packet_img2"> </div>
        <div class="user_card2" data-aos="zoom-out-left"> <img src="<?php echo SITE; ?>assets/images/home-page/user-card.png"> </div>
      </div>
      <div class="btn_button_sec" data-aos="fade-up" data-aos-duration="2000"> <a href="" class="btn_button">Try Free Today!</a> </div>
    </div>
    <div class="curcle_img2 text-right"> <img src="<?php echo SITE; ?>assets/images/home-page/curcle_img2.png"> </div>
  </section>
  <section class="product_slider_mobile_sec">
    <div class="container">
      <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
          <div class="product_item_list">
            <div class="product_item_img"> <img src="<?php echo SITE; ?>assets/images/home-page/wake-up-and-stretch--scaled.png"> </div>
            <div class="sale_text"> <span>Sale</span> </div>
            <div class="product_item_content">
              <h3>Planiversity Clothing</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
              <div class="pro_cart_btn"> <a href="" class="view_more_btn">View More</a> </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
          <div class="product_item_list">
            <div class="product_item_img"> <img src="<?php echo SITE; ?>assets/images/home-page/planiversioty_footwear.png"> </div>
            <div class="product_item_content">
              <h3>Planiversity Footwear</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
              <div class="pro_cart_btn"> <a href="" class="view_more_btn">View More</a> </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
          <div class="product_item_list">
            <div class="product_item_img"> <img src="<?php echo SITE; ?>assets/images/home-page/planiversioty_gear.png"> </div>
            <div class="product_item_content">
              <h3>Planiversity Gearas</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
              <div class="pro_cart_btn"> <a href="" class="view_more_btn">View More</a> </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="demos" class="product_slider_sec">
    <div class="container">
      <div class="large-12 columns">
        <div class="owl-carousel owl-theme">
          <div class="item">
            <div class="product_item_list">
              <div class="product_item_img"> <img src="<?php echo SITE; ?>assets/images/home-page/wake-up-and-stretch--scaled.png"> </div>
              <div class="sale_text"> <span>Sale</span> </div>
              <div class="product_item_content">
                <h3>Planiversity Clothing</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
                <div class="pro_cart_btn"> <a href="" class="view_more_btn">View More</a> </div>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="product_item_list">
              <div class="product_item_img"> <img src="<?php echo SITE; ?>assets/images/home-page/planiversioty_footwear.png"> </div>
              <div class="product_item_content">
                <h3>Planiversity Footwear</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
                <div class="pro_cart_btn"> <a href="" class="view_more_btn">View More</a> </div>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="product_item_list">
              <div class="product_item_img"> <img src="<?php echo SITE; ?>assets/images/home-page/planiversioty_gear.png"> </div>
              <div class="product_item_content">
                <h3>Planiversity Gearas</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
                <div class="pro_cart_btn"> <a href="" class="view_more_btn">View More</a> </div>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="product_item_list">
              <div class="product_item_img"> <img src="<?php echo SITE; ?>assets/images/home-page/wake-up-and-stretch--scaled.png"> </div>
              <div class="sale_text"> <span>Sale</span> </div>
              <div class="product_item_content">
                <h3>Planiversity Clothing</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
                <div class="pro_cart_btn"> <a href="" class="view_more_btn">View More</a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="the_ultimate_travel">
    <div class="container">
      <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
          <div class="the_ultimate_travel_img"> <img src="<?php echo SITE; ?>assets/images/home-page/the_ultimate_travel_img.png">
            <div class="the_ultimate_travel_img_heading">
              <h3>The ultimate travel <span>marketing book</span> </h3>
            </div>
            <div class="the_ultimate_travel_img_btn"> <a href="<?php echo SITE; ?>assets/images/home-page/the_ultimate_travel_img.png" class="image_download" download>Download</a> </div>
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
          <div class="the_ultimate_travel_right">
            <div class="free_btn"> <a href="">Free</a> </div>
            <div class="text">
              <h3 data-aos="fade-up" data-aos-anchor-placement="top-center" class="ultimate_head1">The ultimate travel <span>marketing book</span></h3>
              <h3 data-aos="fade-up" data-aos-anchor-placement="top-center" class="ultimate_head2"> The ultimate <span>travel marketing</span> book </h3>
              <p data-aos="fade-right" data-aos-offset="300" data-aos-easing="ease-in-sine">One of the best features of this service is being able to consolidate your trip documentation. Forget having to sift through emails; keep the important information here instead.</p>
            </div>
            <form action="" method="post" id="enquiryvalidation">
              <div class="the_ultimate_travel_input">
                <div class="row">
                  <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12 padding_right">
                    <input type="text" class="form-control" name="email" placeholder="Email Address" required>
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12 padding_left">
                    <button type="submit" class="btn_submit">Download Now</button>
                  </div>
                </div>
              </div>
              <input type="checkbox" name="news_updates" value="">
              &nbsp;
              <label for="news_updates"> Opt in to recieve news and updates.</label>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <footer class="footer_sec">
    <div class="container">
      <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
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
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
          <div class="footer_item2">
            <h4>Site Map</h4>
            <ul class="list-unstyled">
              <li><a href="">Contact Us</a></li>
              <li><a href="">Blog</a></li>
              <li><a href="">What You'll Get</a></li>
              <li><a href="">Sitemap</a></li>
            </ul>
          </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
          <div class="footer_item3">
            <h4>Contacts</h4>
            <p>Have a question? Need to get <span>something off your chest?</span> Email Us and we'll contact you...</p>
            <address>
              4023 Kennett Pike <span>Suit 690</span> Wilmington, DE 19807
            </address>
            <p><a href="mailto:plans@planiversity.com">plans@planiversity.com</a></p>
          </div>
        </div>
      </div>
      <div class="copywrite_sec">
        <p>© Copyright. 2015 - 2021. Planiversity, LLC. All Rights Reserved.</p>
      </div>
    </div>
    <div class="footer_curcle_bar"> <img src="<?php echo SITE; ?>assets/images/home-page/footer_curcle_bar.png"> </div>
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
              <source src="<?php echo SITE; ?>assets/images/home-page/planiversity_video.mp4" type="video/mp4">
            </video>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.minjs"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo SITE; ?>home-page/js/menuzord.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="<?php echo SITE; ?>home-page/js/jquery.validate.min.js"></script>
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
    document.getElementById('query_page_url').value = window.location;
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