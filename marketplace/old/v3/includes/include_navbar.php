<header id="header">
    <div class="topbar fixed-header animated slideInDown">
        <div class="header1 po-relative">
            <div class="container">
                <nav class="navbar navbar-expand-lg h1-nav">
                    <a class="navbar-brand" href="<?php echo SITE; ?>">
                        Planiversity
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mheader1" aria-controls="mheader1" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa fa-bars"></span>
                    </button>

                    <div class="collapse navbar-collapse hover-dropdown" id="mheader1">
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE ?>about-us">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE ?>blog">Blog</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE ?>select-your-payment">What It Costs</a>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE ?>smart-map">Smart Travel</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE ?>travel-booking">Travel Booking</a>
                            </li>


                            <?php
                            if ($auth->isLogged() && $userdata['customer_number'] == '62f6d52f7e') { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="apanel/users">Admin</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn free-trial-button" href="../logout">Log Out</a>
                                <?php } else if ($auth->isLogged()) { ?>
                                <li class="nav-item sign_in_btn">
                                    <a class="btn free-trial-button" href="..logout">Log Out</a>
                                <?php   } else { ?>
                                <li class="sign_in_btn"><a href="<?= SITE ?>login" id="show_loginform">Sign In</a>
                                <?php } ?>
                                </li>

                                <li class="nav-item">
                                    <button class="wishlist_section">
                                        <span><i class="fa fa-heart-o" aria-hidden="true"></i></span>
                                        <span class="badge" id="wishlist_count">0</span>
                                    </button>
                                </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>