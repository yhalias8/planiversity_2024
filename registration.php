<?php
include_once("config.ini.php");

if ($auth->isLogged()) {
    header("Location:" . SITE . "welcome"); // header("Location:".SITE."dashboard");
}

include('include_doctype.php');
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <meta charset="utf-8">
    <title>PLANIVERSITY - REGISTRATION</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?php echo SITE; ?>style/menu.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>

    <script src='https://www.google.com/recaptcha/api.js'></script>
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

        <?php include('include_header.php') ?>

        <h2 class="style3">Welcome to Planiversity</h2>

        <?php
        $output = '';
        $name = '';
        $email = '';

        function generate_customerid()
        {
            for ($i = 0; $i < 10; $i++) {
                $decstr = substr(md5(uniqid(rand(), true)), 10, 10);
                return $decstr;
            }
        }

        if (isset($_POST['register_submit'])) {
            $name = filter_var($_POST["register_name"], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST["register_email"], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST["register_password"], FILTER_SANITIZE_STRING);
            $passwordconform = filter_var($_POST["register_password2"], FILTER_SANITIZE_STRING);
            $clientID = generate_customerid();

            $params = array("name" => $name, "account_type" => $_POST["register_accounttype"], "customer_number" => $clientID);
            //$result = $auth->register($email, $password, $passwordconform, $params, null, $sendmail = false);
            $result = $auth->register($email, $password, $passwordconform, $params, $_POST['g-recaptcha-response'], $sendmail = true);
            if ($result['error']) {
                $output = $result['message'];
            } else {
                // send clientID number by email
                $mail = new PHPMailer;
                $mail->CharSet = 'UTF-8';
                $mail->From = $auth->config->site_email;
                $mail->FromName = $auth->config->site_name;
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Planiversity.com - Account created';
                $mail->Body = 'Hello,<br/><br/> Your clientID is <b>' . $clientID . '</b>, you can use this number to login here <a href="' . SITE . '">Planiversity.com</a>';
                $mail->send();
                // send clientID number by email
                $output = $result['message'];
                $output .= ' Please check your email to activate your account.';
                $name = '';
                $email = '';
            }
        }
        ?>

        <section class="main_cont">

            <div class="cont_in">

                <article class="cont_opc reg_cont">
                    <form name="register_form" method="POST">
                        <h1>Registration</h1>
                        <div class="error_style"><?php echo $output; ?></div>
                        <input id="Individual" type="radio" checked name="register_accounttype" value="Individual">
                        <label for="Individual">Individual</label>
                        <input id="Business" type="radio" name="register_accounttype" value="Business">
                        <label for="Business">Business</label><br />
                        <input name="register_name" id="register_name" maxlength="50" type="text" value="<?= $name ?>" class="inp1" placeholder="Name"><br />
                        <input name="register_email" id="register_email" maxlength="100" type="text" value="<?= $email ?>" class="inp1" placeholder="Email"><br />
                        <input name="register_password" id="register_password" maxlength="50" type="password" value="" class="inp1" placeholder="Password"><br />
                        <input name="register_password2" id="register_password2" maxlength="50" type="password" value="" class="inp1" placeholder="Password Confirmation">
                        <div class="g-recaptcha" data-sitekey="6LdIdUQUAAAAAP-mqo-k4yKwOJBA3dPt8CxWdVZP"></div>
                        <input name="register_submit" id="register_submit" type="submit" class="button button2 marg_t" value="GET STARTED">
                    </form>
                </article>

                <article class="reg_cont_r">
                    <h1>Why Consider Us</h1>
                    <ul>
                        <li>We are the only service to consolidate so much of your travel information into one location</li>
                        <li>Our focus is on two key elements of travel: organization and situational awareness</li>
                        <li>Your get to compile itineraries, documents, maps, key destination locations, weather, notes, embassy info, and your schedule together</li>
                        <li>Security while traveling abroad is important, and that is why we focus on emergency services locations and electronic copies of your documents; so that you can respond, and not have to worry about losing or having your passportconfiscated</li>
                        <li>We are only beginning and have a ton of plans for expansion. Imagine how far our service will advance in the months and years to come</li>
                    </ul>
                </article>
            </div>

        </section>
    </div>

    <footer class="footer"><?php include('include_footer.php'); ?></footer>

</body>

</html>