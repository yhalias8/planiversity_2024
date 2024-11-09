<?php
global $flag;
?>

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
                                <a class="nav-link" href="<?= SITE ?>marketplace">Marketplace</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="<?= SITE ?>travel-booking">Travel Booking</a>
                            </li>

                            <?php if (!$auth->isLogged()) { ?>

                                <li class="sign_in_btn"><a href="<?= SITE ?>login" id="show_loginform">Sign In</a>

                                <?php } else { ?>

                                <li class="nav-link user">

                                    <div class="dropdown">
                                        <a class="btn btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?= $userdata['name']; ?>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="<?= SITE ?>welcome"><i class="fa fa-home" aria-hidden="true"></i> Dashboard</a>
                                            <a class="dropdown-item" href="<?= SITE ?>profile"><i class="fa fa-user-circle" aria-hidden="true"></i> Profile</a>
                                            <a class="dropdown-item" href="<?= SITE ?>/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
                                        </div>
                                    </div>

                                </li>

                            <?php } ?>




                            <li class="nav-item">
                                <button class="wishlist_section <?= $flag ?> ">
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