<html>

<head>
    <title><?= $page_title; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <script>
        var SITE = '<?= SITE; ?>';
    </script>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= SITE; ?>images/favicon.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--calendar css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
    <link href="<?= SITE ?>assets/css/fullcalendar.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE ?>assets/css/app-style.css?v=2023" rel="stylesheet" type="text/css" />
    <link href="<?= SITE ?>assets/css/theme.css" rel="stylesheet" type="text/css" />
    <link href="<?= SITE ?>assets/css/people.css" rel="stylesheet" type="text/css" />
    <script src="<?= SITE ?>assets/js/modernizr.min.js"></script>
    <script src="<?= SITE ?>assets/js/jquery.min.js"></script>
    <script src="<?= SITE ?>assets/js/moment.min.js"></script>
    <script src='<?= SITE ?>assets/js/fullcalendar.min.js'></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

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