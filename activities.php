<?php
include_once("config.ini.php");

include("class/class.Plan.php");
include_once("class/class.ToolsWelcome.php");

$plan = new Plan();

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'welcome';
    header("Location:" . SITE . "login");
}

include_once('include_doctype.php');


?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="description"
          content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <title>Planiversity | Consolidated Travel Information Management</title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no"/>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= SITE; ?>images/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
    <?php include('dashboard/include/css.php'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="<?= SITE ?>dashboard/js/jquery-3.6.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
    <script>
        var SITE = '<?= SITE; ?>';
    </script>

    <!--Facebook Pixel Code-->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
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
        <img height="1" width="1" src="https://www.facebook.com/tr?id=871547440200746&ev=PageView&noscript=1"/>
    </noscript>
    <!--End Facebook Pixel Code-->

    <?php
    if (file_exists('include_google_analitics.php')) {
        include_once('include_google_analitics.php');
    }
    ?>


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
        (function (w, d, s, l, i) {
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
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<section class="dashboard_mail_sec">
    <div class="container-fluid app-container app-theme-white">
        <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
            <div class="app-header header-shadow">
                <div class="app-header__logo">
                    <div class="logo-src">
                        <h3><span class="only-mob hamburger hamburger--elastic mobile-toggle-nav"><img
                                        src="<?= SITE; ?>/dashboard/images/arrow-planhd.svg"></span> Planiversity</h3>
                    </div>
                    <div class="header__pane ml-auto">
                        <div class="show-mob">
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                                    data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="app-header__mobile-menu">
                    <div class=" btn-group">
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                            <span class="hamburger-inner"></span>
                        </a>
                        <div tabindex="-1" role="menu" aria-hidden="true"
                             class="dropdown-menu dropdown-menu-right onmobile-drop">
                            <ul class="list-unstyled">
                                <?php
                                if ($userdata['account_type'] == 'Admin') {
                                    ?>
                                    <li>
                                        <a class="dropdown-item drop-menu-item" href="<?= SITE; ?>apanel/users"
                                           target="_blank">
                                            Admin
                                        </a>
                                    </li>
                                <?php } ?>

                                <li>
                                    <a href="<?= SITE; ?>welcome" class="dropdown-item drop-menu-item" target="_blank">
                                        Home
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= SITE; ?>contact-us" class="dropdown-item drop-menu-item"
                                       target="_blank">
                                        Contact Us
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= SITE ?>blog" class="dropdown-item drop-menu-item" target="_blank">
                                        Blog
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= SITE; ?>leave" class="dropdown-item drop-menu-item" target="_blank">
                                        Delete Account
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= SITE; ?>logout" class="dropdown-item drop-menu-item">
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php
                ActivityLogger::markVisitOnActivityPage($userdata['id']);
                include_once("includes/top_right_navigation.php");
                ?>

            </div>
            <div class="app-main">
                <div class="row dashboard_main_row">
                    <div class="col-xl-2 left_menu_colom3">
                        <div class="navbar_left_sec">
                            <div class="app-sidebar sidebar-shadow left_menubar_sec">
                                <div class="app-header__logo">
                                    <div class="logo-src"></div>
                                    <div class="header__pane ml-auto">
                                        <div>
                                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                                                    data-class="closed-sidebar">
                                                    <span class="hamburger-box1">
                                                        <span class="hamburger-inner"></span>
                                                    </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>


                                <div class="scrollbar-sidebar">
                                    <?php
                                    include('dashboard/include/navigation.php');
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-10 right_content_colom9">
                        <div class="app-main__outer">
                            <div class="app-main__inner">
                                <div class="">
                                    <div class="launch_commision_item_sec">
                                        <div class="dashboard_user_sec">
                                            <div class="user_header">
                                                <div class="row align-self-center">
                                                    <div class="col-xl-6 col-9">
                                                        <div class="user_header_left_item">
                                                            <div class="user_header_list">

                                                                <div class="user_header_text">
                                                                    <h6 style="margin-bottom:0">
                                                                        Activities</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-3">

                                                    </div>
                                                </div>
                                            </div>

                                            <section class="upcoming_events_sec" style="margin-top:17px">
                                                <div class="row">
                                                    <div class="col-xl-10" style="min-height: 70vh;">

                                                        <?php

                                                        function activityPager($page, $totalActivities, $top = true)
                                                        { ?>
                                                            <div style="text-align: center; padding: 10px; padding-<?=($top)?"top":"bottom";?>:0px">
                                                                <?php
                                                                $numOfPages = ceil($totalActivities / ActivityLogger::PER_PAGE);

                                                                if ($page > 1) { ?>
                                                                    <a href="<?= SITE; ?>activities.php?page=<?= $page - 1; ?>">Previous</a>
                                                                <?php }
                                                                if ($page > 1 && $numOfPages > $page) {
                                                                    echo " | ";
                                                                }
                                                                if ($numOfPages > $page) { ?>
                                                                    <a href="<?= SITE; ?>activities.php?page=<?= $page + 1; ?>">Next</a>
                                                                <?php } ?>
                                                            </div>
                                                            <?php
                                                        }


                                                        $page = $_GET['page'] ?? 1;
                                                        $activityPage = ActivityLogger::getUserActivityPage($userdata['id'], $page);

                                                        if ($activityPage['total_activities'] > 0) {
                                                            activityPager($page, $activityPage['total_activities']);
                                                            foreach ($activityPage['activities'] as $activity) { ?>
                                                                <div style="padding: 5px; border-radius: 5px;border:solid 1px #eee; background-color:#fff; margin-bottom:6px">
                                                                    <img src="<?= SITE . ($activity['author_picture'] ? ("ajaxfiles/profile/" . $activity['author_picture']) : 'images/my_profile_icon.png'); ?>"
                                                                         class="rounded-circle float-left" width="45"
                                                                         height="45" style="margin-top:4px;margin-left:4px;margin-bottom:4px;">
                                                                    <div style="margin-top:14px">
                                                                        <div style="float:left;padding-left:7px;font-size:15px"><?= $activity['message']; ?></div>
                                                                        <div style="float:right;font-size:15px;color:#6f7375;padding-right:10px">
                                                                            <?php
                                                                            $dt = new \DateTime($activity['timestamp']);
                                                                            echo $dt->format("d F Y h:i A");

                                                                            ?>
                                                                        </div>
                                                                        <br style="clear: both">
                                                                    </div>
                                                                </div>
                                                            <?php  }
                                                            activityPager($page, $activityPage['total_activities'], false);
                                                        } else { ?>
                                                            <h2 style="text-align: center">There is no activities logged
                                                                yet.</h2>
                                                        <?php } ?>


                                                    </div>
                                                </div>
                                            </section>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard_footer">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="privacy_and_policy_list">
                                        <a href="">Privacy</a>
                                        <a href="">Terms of Service</a>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="copy_write">
                                        <p>&copy;Copyright. 2015 - <?= date('Y'); ?> Planiversity, LLC. All Rights
                                            Reserved.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</section>


<audio id="chatAudio">
    <source src="<?= SITE ?>assets/sound/notification.mp3" type="audio/mpeg">
</audio>


<script src="<?php echo SITE; ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo SITE; ?>assets/js/popper.min.js"></script>
<script type="text/javascript" src="<?= SITE ?>dashboard/js/main.js?v=202292"></script>
<script src="<?= SITE ?>dashboard/js/jquery-ui.js"></script>
<script src="<?= SITE ?>/js/dashboard_next.js?v=<?= time(); ?>"></script>


</body>

</html>