<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
   $_SESSION['redirect'] = 'calendar';
   header("Location:".SITE."login");
}
if ($userdata['account_type']=='Individual')
    header("Location:".SITE."welcome");
	
include('include_doctype.php');	
?>
<html>
<head>
<meta charset="utf-8">
<title>PLANIVERSITY - TRIP CALENDAR</title>

<link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

<link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
<script src="<?php echo SITE; ?>js/flexcroll.js"></script>

<link href="<?php echo SITE; ?>js/calendar/fullcalendar.min.css" rel="stylesheet" />
<script src="<?php echo SITE; ?>js/calendar/moment.min.js"></script>
<script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
<script src="<?php echo SITE; ?>js/calendar/fullcalendar.min.js"></script>
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
        if ($tmp && $stmt->rowCount()>0)
           { $timelines = $stmt->fetchAll(PDO::FETCH_OBJ);
             foreach ($timelines as $timeline){
                 $aux .= "{ 
                           title: '" . $timeline->title."', 
                           start: '".date('Y-m-d',strtotime($timeline->date))."',
                           end: '".date('Y-m-d',strtotime($timeline->date)+3600)."',
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

</head>

<body class="inner_page inner_page2">

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