<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
  $_SESSION['redirect'] = 'calendar';
  header("Location:" . SITE . "login");
}
if ($userdata['account_type'] == 'Individual')
  header("Location:" . SITE . "welcome");

include('include_doctype.php');
?>
<html>

<head>
  <meta charset="utf-8">
  <title>PLANIVERSITY - TRIP CALENDAR</title>

  <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
  <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

  <link href="<?= SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
  <link href="<?= SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
  <link href="<?= SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
  <script src="<?= SITE; ?>js/responsive-nav.js"></script>
  <script src="<?= SITE; ?>js/flexcroll.js"></script>

  <link href="<?= SITE; ?>js/calendar/fullcalendar.min.css" rel="stylesheet" />
  <script src="<?= SITE; ?>js/calendar/moment.min.js"></script>
  <script src="<?= SITE; ?>js/jquery-1.11.3.js"></script>
  <script src="<?= SITE; ?>js/calendar/fullcalendar.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#calendar').fullCalendar({
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay,listMonth'
        },
        navLinks: true, // can click day/week names to navigate views
        selectable: true,
        selectHelper: true,
        //eventLimit: true, // allow "more" link when too many events
        events: [
          <?php
          $stmt = $dbh->prepare("SELECT tl.* FROM timeline as tl,trips as tp WHERE tl.id_trip=tp.id_trip and tp.id_user=?");
          $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
          $tmp = $stmt->execute();
          $aux = '';
          if ($tmp && $stmt->rowCount() > 0) {
            $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($timelines as $timeline) {
              $aux .= "{ 
                           title: '" . $timeline->title . "', 
                           start: '" . date('Y-m-d', strtotime($timeline->date)) . "',
                           end: '" . date('Y-m-d', strtotime($timeline->date) + 3600) . "',
                           color: '#F49F32'
                          },";
            }
            echo $aux;
          }
          ?>
        ]
      });
    });
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
</head>

<body class="inner_page inner_page2">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <div class="content">

    <?php include('include_header.php'); ?>

    <section class="cont_in marg_b2 calendar_cont2">
      <div id='calendar'></div>
      <br clear="all" />
    </section>

  </div>


  <footer class="footer"><?php include('include_footer.php'); ?></footer>

</body>

</html>