<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'dashboard';
    header("Location:" . SITE . "login");
}
if ($userdata['account_type'] == 'Individual')
    header("Location:" . SITE . "welcome");

include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <title>PLANIVERSITY - DASHBOARD</title>

    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?= SITE; ?>style/dashb_menu.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
    <script src="<?= SITE; ?>js/responsive-nav.js"></script>
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

<body class="inner_page dashboard_page">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include('include_header_dashboard.php'); ?>

    <section class="main_cont">
        <ul class="dashb_opt">
            <li><a href="#"><img src="<?= SITE; ?>images/icon_people_management.png"><br />People Management</a></li>
            <li><a href="#"><img src="<?= SITE; ?>images/icon_job_management.png"><br />Job Management</a></li>
            <li><a href="#"><img src="<?= SITE; ?>images/icon_trip_management.png"><br />Trip Management</a></li>
            <li><a href="#"><img src="<?= SITE; ?>images/icon_master_plan.png"><br />Admin Master Plan</a></li>
        </ul>
    </section>

    <?php include('include_footer.php'); ?>

</body>

</html>