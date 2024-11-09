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
<html>
<head>
<meta charset="utf-8">
<title>PLANIVERSITY - DASHBOARD</title>

<link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

<link href="<?php echo SITE; ?>style/dashb_menu.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITE; ?>js/responsive-nav.js"></script>

</head>

<body class="inner_page dashboard_page">

    <?php include('include_header_dashboard.php'); ?>

    <section class="main_cont">
        <ul class="dashb_opt">
            <li><a href="#"><img src="<?php echo SITE; ?>images/icon_people_management.png"><br />People Management</a></li>
            <li><a href="#"><img src="<?php echo SITE; ?>images/icon_job_management.png"><br />Job Management</a></li>
            <li><a href="#"><img src="<?php echo SITE; ?>images/icon_trip_management.png"><br />Trip Management</a></li>
            <li><a href="#"><img src="<?php echo SITE; ?>images/icon_master_plan.png"><br />Admin Master Plan</a></li>
        </ul>
    </section>
    
    <?php include('include_footer.php'); ?>

</body>
</html>