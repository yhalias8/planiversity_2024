<?PHP
if (strstr($_SERVER['PHP_SELF'], 'index.php')) {
   $title = "Planiversity | Consolidated Travel Information Management";
   $metakey = "Consolidated Travel Information Management";
   $metadesc = "Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management.";
   $h1 = "A revolutionary, unlimited travel logistics service dedicated to consolidating so much travel information.";
}
if (strstr($_SERVER['PHP_SELF'], 'about_us_new.php')) {
   $title = "Planiversity | About Planiversity";
   $metakey = "About Planiversity and Erich Allen";
   $metadesc = "Using skills developed as both a military pilot and Corporate Director of Safety, Erich Allen founded Planiversity to turn the average traveler into an organized and informed traveler.";
   $h1 = "Have your complete organized travel plan before you leave - including the locations of embassies, hospitals, subway stations, police stations, or parking garages - all in one securely managed packet.";
}
if (strstr($_SERVER['PHP_SELF'], 'contact_us_new.php')) {
   $title = "Planiversity | Contact";
   $metakey = "Contact Planiversity";
   $metadesc = "Have a question about Planiversity or need to get something off your chest? Drop us a line. We look forward to helping you!";
   $h1 = "Contact Us";
}
if (strstr($_SERVER['PHP_SELF'], 'billing_page_new.php')) {
   $title = "Planiversity | Manage Your Trip";
   $metakey = "Travel Information Management";
   $metadesc = "Planiversity accommodates both individual and business travel planning and management, with software options built to ensure your security and awareness.";
   $h1 = "Convenient and secure payment options based on how often you travel";
}
if (strstr($_SERVER['PHP_SELF'], 'faq_new.php')) {
   $title = "Planiversity | FAQs";
   $metakey = "Frequently Asked Questions";
   $metadesc = "Why organize your travel with Planiversity? What experience can we offer you? How can having more than just an itinerary benefit you? We've prepared our top questions with answers to keep you in the know.";
   $h1 = "Frequently Asked Questions, Consistently Prepared Answers";
}
if (strstr($_SERVER['PHP_SELF'], 'data_security_new.php')) {
   $title = "Planiversity | Security";
   $metakey = "Complete Information Security with Planiversity";
   $metadesc = "Planiversity takes privacy and security seriously, and we've taken measures to ensure every protection against a data breach. Using encryption, adhering to PCI compliance, and working only with partners who prioritize safety, we are diligent about data protection.";
   $h1 = "Information security protection is a standard.";
}
if (strstr($_SERVER['PHP_SELF'], 'registration_new.php')) {
   $title = "Planiversity | Sign Up";
   $metakey = "Sign Up; Register to use";
   $metadesc = "Why consider Planiversity? We are the only travel management company to combine so much of your trip information in one document. When you work with Planiversity, you become the true smart traveler.";
   $h1 = "Experience travel the way a seasoned traveler would.";
}

?>
<meta charset="utf-8">
<title><?PHP echo $title; ?></title>
<meta name="description" content="<?PHP echo $metadesc; ?>">
<meta name="keywords" content="<?PHP echo $metakey; ?>">

<link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
<link rel="canonical" href="<?php echo SITE . $canonical; ?>">
<link href="<?php echo SITE; ?>stylenew/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE; ?>stylenew/responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SITE; ?>stylenew/slider.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITE; ?>stylenew/responsive-nav.js"></script>
