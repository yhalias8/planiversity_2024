<?php
include_once("config.ini.php");
include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <title>PLANIVERSITY</title>

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

        <div class="main_cont">

            <div class="cont_in bill_info">

                <article class="bill_info_top">
                    <h1 class="style1">Select your payment based on the type of account</h1>
                    <p>How you as the user take advantage of Planiversity’s services largely depends on your needs. While the bulk of planning and organization features are the same, there are additional perks that come with a business account. Take a look below to get a better idea of what separates the two packages and identify what is a more suitable course for you to take. Good planning begins with the right choices!</p>
                </article>

                <section class="cont_opc bill_l">
                    <div class="bill_cont_in">
                        <h2>For the Individual</h2>
                        <h1>Case by Case or Monthly</h1>
                        <ul class="list1">
                            <li>Keep your costs with us low if your needs are few and far between</li>
                            <li>Pay on a per case basis</li>
                            <li>Your use is never complicated, but the benefits of travel organization are the same as a business</li>
                            <li>You'll still have access to your past trip packets and order history</li>
                            <li>If your needs increase, just jump to a monthly payment and use the service as often as you like</li>
                            <li>You'll never be locked into a commitment, but we know you’ll want to stay!</li>
                        </ul>
                    </div>
                </section>
                <section class="cont_opc bill_r">
                    <div class="bill_cont_in">
                        <h2>For the Business</h2>
                        <h1>Monthly or Annually</h1>
                        <ul class="list1">
                            <li>Whether you choose to use a monthly or annual basis, we will only take a monthly payment, not pulling it all from you at once</li>
                            <li>Manage a calendar and keep track of which employees are currently on a trip, leaving soon, or have already returned</li>
                            <li>Create an employee database, managing all who are travel for work, or just to manage employee files</li>
                            <li>Create a job database, and assign your employees to each job</li>
                            <li>Link the employee and job information to your trip packet</li>
                            <li>Access the trip history of any employee and see your order history, all from your administrative panel</li>
                        </ul>
                    </div>
                </section>

            </div>

        </div>

    </div>

    <footer class="footer">
        <div class="style2">
            <p>Planiversitywas built to accommodate the individual and business alike, because each comes with their own basic needs. That is why we’ve kept the planning process similar, but added features for the business’s organization. We understand that the common traveler wants things simplified, organized, and affordable, while the businesses need services with a lower price tag and greater amount of utility; something that will add value to their operations and is responsive to their challenges. We offer a service unmatched by the competition, mainly because we have the experience and know the areas where improvement is needed.</p>
        </div>
        <?php include('include_footer.php'); ?>
    </footer>

</body>

</html>