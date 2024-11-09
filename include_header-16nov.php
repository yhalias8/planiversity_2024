<header class="header">

    <div class="cont_in">

        <script src="<?php echo SITE; ?>js/index.js"></script>
        <?php if (!strstr($_SERVER['SCRIPT_FILENAME'], 'index.php')) { ?>
            <a href="<?php echo SITE; ?>welcome"><img src="<?php echo SITE; ?>images/inner_logo.png" alt="" /></a>
        <?php }  ?>


        <nav class="nav-collapse">
            <ul>
                <?php if ($auth->isLogged()) { ?>
                    <li><a href="<?php echo SITE; ?>welcome">Home</a></li>
                    <?php if ($userdata['account_type'] == 'Business' || $userdata['account_type'] == 'Admin') { ?>
                        <!--<li><a href="<?php echo SITE; ?>dashboard">Dashboard</a></li>-->
                        <li><a href="<?php echo SITE; ?>calendar">Trip Calendar</a></li>
                    <?php } ?>
                    <!--<li><a href="<?php echo SITE; ?>trip/how-are-you-traveling">Start a Plan Trip</a></li>-->
                <?php } else { ?>
                    <li><a href="<?php echo SITE; ?>">Home</a></li>
                    <li><a href="<?php echo SITE; ?>login">Login</a></li>
                <?php } ?>
                <li><a href="<?php echo SITE; ?>about-us">About Us</a></li>
                <li><a href="<?php echo SITE; ?>select-your-payment">Billing</a></li>
                <li><a href="<?php echo SITE; ?>faq">Faq</a></li>
                <li><a href="<?php echo SITE; ?>contact-us">Contact Us</a></li>
                <li><a href="<?php echo SITE; ?>data-security">Data Security</a></li>
                <?php if ($auth->isLogged()) { ?>
                    <li><a href="<?php echo SITE; ?>logout">Log Out</a></li>
                <?php } ?>
            </ul>
        </nav>

        <?php if ($auth->isLogged()) { ?>
            <nav class="menu_opt">
                <ul>
                    <?php
                    $img = 'images/img3.png';
                    if ($userdata['picture']) $img = $_SERVER['HTTP_HOST'].'/staging/ajaxfiles/profile/' . $userdata['picture'];
                    ?>
                    <li class="user"><a href="<?php echo SITE; ?>my-profile"><span><img src="<?php echo SITE; ?>/<?php echo $img; ?>" alt="" /></span><br clear="all" /><?php echo $userdata['name']; ?></a></li>
                    <?php if ($userdata['account_type'] == 'Admin') { ?>
                        <li class="admin"><a href="<?php echo SITE; ?>apanel/users"><span>Admin Panel</span></a></li>
                    <?php } else { ?>
                        <!--<li class="admin inactive"><a>Admin Panel</a></li>-->
                    <?php } ?>
                    <!--<li class="logout"><a href="<?php echo SITE; ?>logout">Log Out</a></li>-->
                </ul>
            </nav>
        <?php } ?>


    </div>

</header>
<input name="doc_height" id="doc_height" value="" type="hidden">

<script>
    var navigation = responsiveNav(".nav-collapse");
</script>