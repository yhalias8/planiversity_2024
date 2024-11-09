<?php
include_once("config.ini.php");
include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <title>PLANIVERSITY - FAQ</title>

    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?= SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
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

<body class="inner_page">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="content">

        <?php include('include_header.php') ?>

        <section class="main_cont">
            <div class="cont_in">
                <article class="cont_opc about_l faq_cont">
                    <h1>Frequently Asked Questions</h1>
                    <p><strong>Is there a service comparable to Planiversity?</strong><br />
                        The answer is simply a no. Planiversity is a unique software, in the sense that it turns you, the traveler, into a well prepared and confident traveler. Using the experience of our Chief Executive Officer, the idea behind the service is to make traveling more efficient by compiling necessary information. Locations of essential facilities will make you more prepared to respond to any unforeseen emergencies while traveling. While there are other companies that can compile your itineraries, none of them allow you the possibility to compile weather, fuel stops, locations of embassies, etc. At Planiversity we are in a league of our own.</p>
                    <p><strong>Why would I need to have digital copies of my license or passport compiled in the exported document?</strong><br />
                        The idea behind including digital copies is to allow travelers to keep their important documents safely stored in a safe location while touring the town. Travelers will not have to worry about keeping difficult to replace documents on them, nor will they have to confront the possibility of having their important documents confiscated from their person. If you need to verify your identification during a spot check, use the digital image. Do not burden yourself with potential corruption or worry that your documents may fall into the wrong hands, delaying your return home.</p>
                    <p><strong>Is Planiversity planning to improve or make the service more intuitive to users?</strong><br />
                        An intuitive process is important these days. Software users want to see the systems they are using become more responsive, as well as simpler to use. However, with Planiversity, our intent is to balance that level of intuitiveness and user responsibility. The key to the service is to be the planner and have control over your travel packet; something which the service will not do for you. The functionality of certain aspects will be improved as time goes on and as user feedback continues to accumulate.</p>
                    <p><strong>Why use Planiversity? How does it benefit me?</strong><br />
                        Most people have had, or will have the chance to travel. The many items and pieces of information that there are to remember to bring, in addition to wanting to utilize while traveling, are numerous and difficult to keep track of. We at Planiversity understand the necessity for positive information consolidation and have created a site where users can combine all of those pieces of essential information into one single source. As time goes on Planiversity will work to improve the filter process, making your travel packet better than what it is now.</p>
                </article>
            </div>
        </section>

    </div>

    <footer class="footer"><?php include('include_footer.php'); ?></footer>

</body>

</html>