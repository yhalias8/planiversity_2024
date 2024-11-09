<?php
include_once("config.ini.php");
include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <title>PLANIVERSITY - ABOUT US</title>

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

                <article class="cont_opc about_l">
                    <h1>About Us</h1>
                    <p>Planiversity is a travel planning software, which is unique to users because it consolidates travel information, while also providing route and destination details. As the user, you’ll create a route, upload itineraries, add filters—such as embassy locations, weather, metro maps, and so on—to your personalized trip packet. You will also have the option to upload copies of yours, and your guest’s passports and driver’s license, allowing you to leave your critical documents secured, but still have your identification on you; it’s all about being prepared when traveling overseas. All of this information, and more, will be consolidated into a single document and formatted, so that when it is printed it can be organized into a travel booklet. Or it can simply be emailed as a pdf fileand kept on your smart device for easy access and reference, even in offline mode. Be more than ready for the next adventure, and travel like a pro!</p>
                    <p>Combining the skills developed as both a military pilot and Corporate Director of Safety, founder Erich Allen understands the importance of combining organization, information, safety, and preparedness into planning. These key elements are the foundation of Planiversity, a service he designed to turn the average person into the most organized and aware type of traveler possible. Compilingcritical information and grouping it together with basic needs, be itfor business or leisure travel, users of the service would be able to go anywhere with a sense of security, knowing that valuable resource information is not only available to them, but it is kept in the same location as their trip documentation.</p>
                </article>

                <article class="cont_opc about_r">
                    <img src="<?= SITE; ?>images/img1.png" alt="" />
                    <h2>Erich Allen<br /> <strong>Planiversity Founder and CEO</strong></h2>
                    <p>"Life is one thing that I cannot imagine evolving without the introduction of unfamiliar things.So, have the intention to see the world,we’ll take care of getting you organized and prepared for anything".</p>
                </article>

            </div>

        </section>

    </div>

    <footer class="footer"><?php include('include_footer.php'); ?></footer>

</body>

</html>