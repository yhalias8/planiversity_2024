<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Planiversity | Consolidated Travel Itinerary Management</title>
  <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management.">
  <meta name="keywords" content="Consolidated Travel Itinerary Management">
  <meta name="author" content="">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>favicon.png">
  <link href="<?php echo SITE; ?>newpage/Planiversity/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo SITE; ?>newpage/Planiversity/css/bootstrap.min.css" rel="stylesheet">

  <link href="<?php echo SITE; ?>newpage/Planiversity/css/style.css?2022" rel="stylesheet">
  <script src="<?php echo SITE; ?>newpage/Planiversity/js/jquery.min.js"></script>
  <script src="<?php echo SITE; ?>assets/js/popper.min.js"></script>
  <script src="<?php echo SITE; ?>newpage/Planiversity/js/bootstrap.min.js"></script>
  <script src="<?php echo SITE; ?>newpage/Planiversity/js/custom.js"></script>
  
  <script>
    var SITE = '<?php echo SITE; ?>';
  </script>  

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
  
  <?php include_once("includes/reditus_tracking_script.php"); ?>
  
  <script type="text/javascript">
  
    function show_win(id) { //$('#link_'+id).toggle();
      $('#win_' + id).toggle('slow');
    }
    $(document).ready(function() {
      $('#show_loginform').click(function() {
        $('.login_cont').show('fast');
      });
      $('#login_cont_close').click(function() {
        $('.login_cont').hide('fast');
      });
      $('#showhidepassword').click(function() {
        var x = document.getElementById("login_password");
        if (x.type === "password") {
          x.type = "text";
          document.getElementById("showhidepassword").src = "images/if_misc-_eye_vision_1276868.png";
        } else {
          x.type = "password";
          document.getElementById("showhidepassword").src = "images/if_misc-_eye_vision_1276868.png";
        }
      });
    });

    (function() {
      var po = document.createElement('script');
      po.type = 'text/javascript';
      po.async = true;
      //   po.src = 'https://apis.google.com/js/client.js?onload=onLoadCallback';
      po.src = 'https://apis.google.com/js/platform.js?onload=onLoadCallback';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(po, s);
    })();

    function onLoadCallback() {
      gapi.load('auth2', function() {
        auth2 = gapi.auth2.init({
          client_id: '397379138067-hvfs5h1f6jh19ladncpbm9mfkoddk9b4.apps.googleusercontent.com',
          scope: 'profile email'
        });
      });
    }

    function login() {
      /* var myParams = {
         'clientid' : '397379138067-hvfs5h1f6jh19ladncpbm9mfkoddk9b4.apps.googleusercontent.com', //You need to set client id
         'cookiepolicy' : 'single_host_origin',
         'callback' : 'onSignIn', //callback function
         'approvalprompt':'force',
         'scope' : 'profile email'
       };*/
      //gapi.auth2.signIn(myParams);
      auth2.signIn().then(function() {
        console.log(auth2.currentUser.get().getId());
        onSignIn(auth2.currentUser.get());
      });
    }

    function renderButton() {
      gapi.signin2.render('my-signin2', {
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'cookiepolicy': 'single_host_origin',
      });
    }

    function onSignIn(googleUser) {
      console.log('on sign in');
      prof = googleUser.getBasicProfile();
      //$("#login_form").append('<input type="hidden" id="X-xhgshdg-jhknh" name="X-xhgshdg-jhknh" value="true" />');
      //$("#login_username").val(prof.getEmail());
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '<?php echo SITE; ?>/ajaxfiles/g_usr_lgn.php');
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          //this.responseText;
          theResp = JSON.parse(this.responseText);
          document.getElementById('google_login_model').style.display = 'block';
          if (theResp[0] == 'true') {
            //alert(theResp[1]);
            document.getElementById('google_login_message_body').innerHTML = theResp[1];
            //window.location.replace('<?php echo SITE; ?>');
            //console.log(this.responseText+"---");
          } else {
            //alert(theResp[1]);
            document.getElementById('google_login_message_body').innerHTML = theResp[1];
          }
        }
      };
      var id_token = googleUser.getAuthResponse().id_token;
      xhr.send('idtoken=' + id_token + '&email=' + prof.getEmail() + '&name=' + prof.getName());
      // xhr.send('idtoken=' + id_token +'&email=' +prof.getEmail()+'&name='+prof.getName());
    }

    function onGoogleLoginSucess() {
      if (theResp[0] == 'true') {
        window.location.replace('<?php echo SITE; ?>');
      } else {
        document.getElementById('google_login_model').style.display = 'none';
      }
    }

    function onFailure(error) {
      alert(error);
    }

    function signOut() {
      var auth2 = gapi.auth2.getAuthInstance();
      auth2.signOut().then(function() {
        console.log('User signed out.');
      });
    }
  </script>
  <style>
    .google-button {
      height: 40px;
      border-width: 0;
      background: white;
      color: #737373;
      border-radius: 5px;
      white-space: nowrap;
      box-shadow: 1px 1px 0px 1px rgba(0, 0, 0, 0.05);
      transition-property: background-color, box-shadow;
      transition-duration: 150ms;
      transition-timing-function: ease-in-out;
      padding: 0;

      &:focus,
      &:hover {
        box-shadow: 1px 4px 5px 1px rgba(0, 0, 0, 0.1);
      }

      &:active {
        background-color: #e5e5e5;
        box-shadow: none;
        transition-duration: 10ms;
      }
    }

    .google-button__icon {
      display: inline-block;
      vertical-align: middle;
      margin: 8px 0 8px 8px;
      width: 18px;
      height: 18px;
      box-sizing: border-box;
    }

    .google-button__icon--plus {
      width: 27px;
    }

    .google-button__text {
      display: inline-block;
      vertical-align: middle;
      padding: 0 24px;
      font-size: 14px;
      font-weight: bold;
      font-family: 'Roboto', arial, sans-serif;
    }

    .newLoginButton {
      padding: 10px 17px;
      font-size: 15px;
      border-radius: 50px;
      color: #fff;
      margin: 15px 5px;
      border: 2px solid #f7973d;
      color: #fff !important;
      background: #f46b45;
      background: -webkit-linear-gradient(to right, #eea849, #f46b45);
      background: linear-gradient(to right, #eea849, #f46b45);
    }

    .form-control {
      border-radius: 5px;
      background-color: rgba(224, 231, 255, 0.2);
    }

    .get-started-btn,
    .get-started-btn:hover,
    .get-started-btn:focus {
      background-image: linear-gradient(180deg, #FACD61 0%, #F39F32 100%) !important;
      border-radius: 5.4px !important;
      color: #333333 !important;
      border: none !important;
      display: inline-block !important;
      font-family: 'Roboto', arial, sans-serif !important;
      font-weight: bold;
      font-size: 14px !important;
    }

    .pointer {
      cursor: pointer;
    }

    .topbar {
      background: linear-gradient(135deg, #0191FD 0.69%, #0D256E 99.13%), #F7F8FA !important;
      box-shadow: none !important;
    }

    .topbar a {
      color: white !important;
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 25px;
    }
    
    .sign_in_btn a {
      background-image: linear-gradient(#fac85c, #f5ab3f);
      border: none;
      color: #000 !important;
      border-radius: 5px;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      text-transform: capitalize;
      padding: 12px 30px;
      margin: 15px 5px;

    }

    .sign_in_btn a:hover {
      background-image: linear-gradient(#fac85c, #f5ab3f);
    }    
  </style>
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
  <div class="login_cont">
    <div class="login_cont_in">
      <a id="login_cont_close" class="close">&times;</a>
      <form name="login_form" style="border-radius:3px" method="POST">
        <h2 style="color:#1f74b7;font-size:24px;text-align:center">SIGN IN</h2>
        <!--<div class="error_style" id="loginerror"><?php echo $output; ?></div>-->
        <div class="<?php echo (!empty($result['message'])) ? "alert alert-success" : "" ?> <?php echo (!empty($result['error'])) ? "alert alert-danger" : "" ?> error_style"><?php echo $output; ?></div>
        <input class="form-control" name="login_username" id="login_username" type="text" value="" maxlength="100" placeholder="Email or Customer ID">
        <input class="form-control" name="login_password" id="login_password" type="password" maxlength="50" value="" placeholder="Password">
        <div class="showhidepassword showhidepassword2"><img title="toggle password visibility" id="showhidepassword" src="<?php echo SITE; ?>images/if_misc-_eye_vision_1276868.png" alt="showorhidepassword" /></div>
        <input style="padding: 10px 17px;
                           font-size: 15px;
                           margin: 20px 0px;
                           border: 2px solid #f7973d;
                           background: #f46b45;" class="newLoginButton get-started-btn" name="login_submit" id="login_submit" type="submit" value="Login" />
        <p style="margin:5px;text-align:center;">OR</p>
        <!--<div data-width="200" data-longtitle="true" id="my-signin2" class="g-signin2" data-onsuccess="onSignIn"></div>-->
        <div style="cursor:pointer;" onclick="login()"><button style="padding-top: 6px;padding-bottom: 43px;width:100%;background-image: linear-gradient(135deg, #0C81D7 0.69%, #0191FD 100%), linear-gradient(
180deg, #FACD61 0%, #F39F32 100%)!important;border-radius: 5.4px!important;color:white;" type="button" class="google-button">
            <span class="google-button__icon">
              <svg viewBox="0 0 366 372" xmlns="http://www.w3.org/2000/svg">
                <path d="M125.9 10.2c40.2-13.9 85.3-13.6 125.3 1.1 22.2 8.2 42.5 21 59.9 37.1-5.8 6.3-12.1 12.2-18.1 18.3l-34.2 34.2c-11.3-10.8-25.1-19-40.1-23.6-17.6-5.3-36.6-6.1-54.6-2.2-21 4.5-40.5 15.5-55.6 30.9-12.2 12.3-21.4 27.5-27 43.9-20.3-15.8-40.6-31.5-61-47.3 21.5-43 60.1-76.9 105.4-92.4z" id="Shape" fill="#EA4335" />
                <path d="M20.6 102.4c20.3 15.8 40.6 31.5 61 47.3-8 23.3-8 49.2 0 72.4-20.3 15.8-40.6 31.6-60.9 47.3C1.9 232.7-3.8 189.6 4.4 149.2c3.3-16.2 8.7-32 16.2-46.8z" id="Shape" fill="#FBBC05" />
                <path d="M361.7 151.1c5.8 32.7 4.5 66.8-4.7 98.8-8.5 29.3-24.6 56.5-47.1 77.2l-59.1-45.9c19.5-13.1 33.3-34.3 37.2-57.5H186.6c.1-24.2.1-48.4.1-72.6h175z" id="Shape" fill="#4285F4" />
                <path d="M81.4 222.2c7.8 22.9 22.8 43.2 42.6 57.1 12.4 8.7 26.6 14.9 41.4 17.9 14.6 3 29.7 2.6 44.4.1 14.6-2.6 28.7-7.9 41-16.2l59.1 45.9c-21.3 19.7-48 33.1-76.2 39.6-31.2 7.1-64.2 7.3-95.2-1-24.6-6.5-47.7-18.2-67.6-34.1-20.9-16.6-38.3-38-50.4-62 20.3-15.7 40.6-31.5 60.9-47.3z" fill="#34A853" />
              </svg>
            </span>
            <span class="google-button__text">Sign in with Google</span>
          </button></div>
        <label><span class="rem_me"><input name="login_remember" id="login_remember" type="checkbox" value="1">Remember Me</span></label>
        <a href="<?php echo SITE; ?>registration" class="forg_pass">No account yet?</a><br clear="all" />
        <a href="<?php echo SITE; ?>password-restore" class="forg_pass">Forgot Password?</a> <br clear="all" />
      </form>
    </div>
  </div>

  <div class="modal" id="google_login_model" tabindex="-1" role="dialog" style="background: #000000cf;display: none;z-index: 100000;">
    <div class="modal-dialog" role="document" style="max-width: 550px;">
      <div class="modal-content">
        <form method="POST" name="otp_form" action="submit_otp.php" class="form-horizontal" id="otp_form">
          <div class="modal-header">
            <h5 class="modal-title">Message</h5>
          </div>
          <div class="modal-body" id="google_login_message_body">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onClick="onGoogleLoginSucess()">OK</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="main-wrapper">
    <div class="topbar fixed-header animated slideInDown">
      <div class="header1 po-relative">
        <div class="container">
          <nav class="navbar navbar-expand-lg h1-nav">
            <a class="navbar-brand" href="<?php echo SITE; ?>">
              <!--<img src="<?php echo SITE; ?>newpage/Planiversity/images/logo.png" alt="logo" />-->
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
                  <a class="nav-link" href="<?= SITE ?>data-security">Data Security</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?= SITE ?>marketplace">Marketplace</a>
                </li>                
                <li class="nav-item">
                  <a class="nav-link" href="<?= SITE ?>travel-booking">Travel Booking</a>
                </li>
                <li class="sign_in_btn"><a class="btn" href="<?= SITE ?>login">Sign In</a></li>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </div>