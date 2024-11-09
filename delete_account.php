<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = '/welcome';
    header("Location:" . SITE . "login");
}

include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | Consolidated Travel Information Management</title>
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">

    <!--calendar css-->
    <link href="/assets/css/fullcalendar.min.css" rel="stylesheet" />

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/app-style.css" rel="stylesheet" type="text/css" />
    <script src="/assets/js/modernizr.min.js"></script>

    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/moment.min.js"></script>
    <script src='/assets/js/fullcalendar.min.js'></script>

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
    <?php include('new_backend_header.php'); ?>
    </header>
    <div class="delete-account-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="delete-account-hd-message">Sorry to see you go</h3>
                </div>
            </div>
            <div class="row justify-content-md-center">
                <div class="col-lg-6">
                    <div class="card-box padding-30 text-center">
                        <p class="closing-message">Closing your account will wipe all travel packet history, and data will no longer be accessible.</p>
                        <a href="" class="close-account-btn" data-toggle="modal" data-target="#delete-account-message-modal">Close my account</a>
                        <a href="/welcome" class="cancel-acct-btn">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="delete-account-message-modal" data-backdrop="false" class="modal fade show modal-custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-content">
                <div class="modal-header custom-modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <p class="mod-p2 text-center">Confirm your choice to close your account.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="confirm-del" class="accept-btn">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <?php include('new_backend_footer.php'); ?>
    <script type="text/javascript">
        $('.modal-custom').on('show.bs.modal', function(e) {
            setTimeout(function() {
                $('.modal-backdrop').addClass('modal-backdrop-custom');
            });
        });
        $("#confirm-del").click(function() {
            $.post("/ajaxfiles/del_user.php",
                function(data) {
                    alert(data)
                    window.location = '/logout';
                });
        });
    </script>
</body>

</html>