<?php
include_once("config.ini.php");
include_once("class/class.TripPlan.php");
include("class/class.Plan.php");
$output   = '';
$trip     = new TripPlan();
$plan = new Plan();
$id_trip  = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
$trip->get_data($id_trip);


if ($userdata['account_type'] == 'Individual') {
    $user_payment_status = $plan->individual_check_plan($userdata['id']);
} else {
    $user_payment_status = $plan->check_plan($userdata['id']);
}


if ($user_payment_status == 0) {
    header("Location:" . SITE . "billing/" . $id_trip); //$output = 'redirect to billing'; 
}

if (empty($trip->trip_title)) { // test script    
    header("Location:" . SITE . "trip/name/" . $id_trip); //$output = 'The trip name is empty'; 
}

$trip->setProgressing($id_trip, 0);

include('include_doctype.php');
?>
<html>
<head>
<meta charset="utf-8">
<title>PLANIVERSITY - PDF GENERATION</title>

<link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <script src="<?php echo SITE; ?>js/jquery-1.12.4.js"></script>

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

<script>
$(document).ready(function() {
    function getLoadingFileName(percentage) {
      var fname = `<?php echo SITE; ?>images/loading/${percentage}.png`;
      return fname;
    }
    var post_data = "idtrip="+"<?php echo $_GET['idtrip']; ?>";
    $.ajax({
        url: '<?php echo SITE; ?>trip_pdf_code.php',
        type: 'POST',
        data: post_data,
        dataType: 'html',
        success: function(data) {            
            //location.href = data;
            var mtimer = setInterval(()=>{
              $.post("<?php echo SITE; ?>ajaxfiles/get_progress.php", {'MODE':'progress','id':<?php echo $_GET['idtrip']; ?>},
              function(result) {
                result = JSON.parse(result);
                //$("#loading_txt").text("Please wait, generating PDF..." + result.progressing + '%');
                //$("#loading_img").attr("src",getLoadingFileName(result.progressing));
                if(result.progressing == 100) {
                  setTimeout(function(){
                    $('#loading').fadeOut();
                    $('#loading_txt').fadeOut();
                    clearInterval(mtimer);
                    location.href = "<?php echo SITE . 'welcome'; ?>";
                  },1000);
                }
              }
              )
            },500);
        },
        error: function(data) {
            alert("Something went wrong!"+data);
        }
    });
  }); 
</script>

<script>
$(document).ready(function() {
 jQuery.fn.centerElement = function () {
   this.css ("position", "absolute");
   this.css ("top", ($ (window). height () - this.height ()) / 2 + $ (window). scrollTop ()-50+ "px");
   this.css ("left", ($ (window). width () - this.width ()) / 2 + $ (window). scrollLeft () + "px")
   return this;
   }  
center_cont();
});

function center_cont(){ 
    $('#center_loading').centerElement();
    setTimeout(function(){
           center_cont();
          }, 100);
}

</script>

<style>
        body {
        background: #f1f2f3;
        }
        .loader {
            width: 100%;
            height: 100%;
            margin: 0 auto 0 auto;
            text-align:center;
        }
        .fullscreen-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('../../assets/images/transparent.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 0;
        }
</style>

</head>

<body>
<div class="fullscreen-background"></div>
  <div id="center_loading">
  <div id="loading" class="loader">
    <img id="loading_img" style="width: 200px;height:200px;" src="<?= SITE; ?>images/export_loading.gif">
  </div>
  <h1 id="loading_txt" style="text-align:center;color:#1973B2; font-size:18px; font-family:Tahoma, Geneva, sans-serif;line-height:36px">
            Please wait, generating your itinerary.Do not close your browser.
            <span style="display:block;">You will be redirected back to the dashboard when completed.</span>
</h1>
</div>

</body>

</html>
