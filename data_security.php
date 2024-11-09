<?php
include_once("config.ini.php");
include('include_doctype.php');
?>
<html>
<head>
<meta charset="utf-8">
<title>PLANIVERSITY - ABOUT US</title>

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

    <section class="main_cont">       
        <div class="cont_in">
            <article class="cont_opc about_l dat_sec_left">
                <p>We know, we know, it’s 2018 and data security is hugely important to customers of online services. We at Planiversity have taken the necessary steps to ensure that not only are we doing our part to protect your data, but also that those we partner and do business with are well-established and credible as well. Here are a few areas where Planiversity has taken steps to proactively protect your information:</p>
                <h1>Server Security</h1>
                <p>Hosting of the Planiversity platform is done through Inmotion hosting; the carrier of a well-developed privacy policy, as well as specific level of data security. Our decision to use this company as a resource came down to three fundamental aspects, and those are: volume capacity, speed, and security.</p>
                 <h1>PCI Compliance</h1>
                <p>Because Planiversity deals with payment accounts and processing, in addition to maintaining and storing user information, it is important that we adhere to the common standards set forth by the PCI Security Standard Council. We are strongly vested in the personal security of every one of our customers, and to ensure that each is protected, we adhere to a strict level of compliance established by the council. Planiversity only does business with credible and well-known, high-resource agencies, in order to eliminate every potential for data breach.</p>
                 <!--<h1>SSL Certificates</h1>
                <p>Our certificates are purchased through GoDaddy.com, the company where we also acquired our domain and other vital site services. These certificates are not only a critical step to authenticating the security of our website, but they also serve to protect and encrypt user information.</p>-->
                 <h1>PC Protection and Password Change Policy</h1>
                <p>To maintain the best possible level of protection of Planiversity software, our developers use top-of-the-line anti-virus software and adhere to a 180-day password change policy on all of our systems.</p>
                 <h1>Payment Processing</h1>
                <p>At Planiversity, we use only those reliable services for processing user payments. The two services which will act as the intermediaries for your service payments are PayPal and Stripe, two well-established and well-protected agencies. These two companies provide dependable services, while offering a long history of well-established security of user data. To review their privacy policies, please visit their respective websites.</p>
            </article> 
            
            <article class="cont_opc about_r dat_sec">                 
                 <h2>Quote from PCI SSC</h2>
                 <p>"The breach or theft of cardholder data affects the entire payment card ecosystem. Customers suddenly lose trust in merchants or financial institutions; their credit can be negatively affected -- there is enormous personal fallout. Merchants and financial institutions lose credibility (and in turn, business), they are also subject to numerous financial liabilities."</p>
                 <h2 class="style12">~ PCI Security Standard Council</h2>
            </article>
            
            <article class="cont_opc about_r dat_sec dat_sec2">                 
                 <h2>SSL Certificates</h2>
                 <!--<p>Our certificates are purchased through InMotion; the domain and other vital site services through GoDaddy. Certificates are not only a critical step to authenticating the security of our website, but they also serve to protect and encrypt  the information of every Planiversity user.</p>-->
                 <p>Our certificates are purchased through GoDaddy.com, the company where we also acquired our domain and other vital site services. These certificates are not only a critical step to authenticating the security of our website, but they also serve to protect and encrypt user information.</p>
            </article>
        </div>
    </section>

</div>

   <footer class="footer"><?php include('include_footer.php'); ?></footer>

</body>
</html>