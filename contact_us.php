<?php
include_once("config.ini.php");
include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <title>PLANIVERSITY - CONTACT US</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>

</head>

<body class="inner_page">

    <div class="content">

        <?php include('include_header.php') ?>

        <section class="main_cont main_cont2">
            <div class="cont_in">
                <article class="cont_opc contact_cont">
                    <h1>Contact Us</h1>
                    <p class="contact_l">Have a question? Need to get something off your chest?<br />
                        Email Us and we'll contact you...<br /><br />
                        Planiversity, LLC<br />
                        P.O. Box 475<br />
                        Chester Heights, PA 19017-0475<br />
                        customerservice@planiversity.com</p>
                    <div class="contact_r">
                        <img src="<?php echo SITE; ?>images/img2.jpg" class="" alt="" />
                    </div>
                </article>
            </div>
        </section>

    </div>

    <footer class="footer"><?php include('include_footer.php'); ?></footer>

</body>

</html>