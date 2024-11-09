<section class="header_slider_sec">
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
                                        <a class="btn free-trial-button" href="logout">Log Out</a>
                                    <?php } else if ($auth->isLogged()) { ?>
                                    <li class="nav-item sign_in_btn">
                                        <a class="btn free-trial-button" href="logout">Log Out</a>
                                    <?php   } else { ?>
                                    <li class="sign_in_btn"><a href="<?= SITE ?>login" id="show_loginform">Sign In</a>
                                    <?php } ?>
                                    </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <img src="<?= SITE; ?>/marketplace/images/background.png" class="header_banner">

    <div class="top_header_heading">

        <div class="container">

            <div class="row">

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                    <div class="banner-heading">

                        <h2>Find the right deal to take you or your business to the next level.</h2>

                        <p>Find courses, books, and services at a fraction of the cost, and provided by <span>the best in the business.</span></p>

                    </div>

                    <div class="banner-search">
                        <div class="s002">
                            <form id="search_form">

                                <div class="inner-form">
                                    <div class="input-field first-wrap">
                                        <div class="icon-wrap">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <input id="search" name="search" type="text" placeholder="What are you looking for?" />
                                    </div>

                                    <div class="input-field fouth-wrap">

                                        <select id="category_field" name="category_field" class="form-control form_category">
                                            <option value="" selected>Choose Category</option>
                                            <option value="0">All</option>
                                            <option value="1">Courses & Learning</option>
                                            <option value="2"> E-books </option>
                                            <option value="3"> Travel Services </option>
                                            <option value="4"> Event Services </option>
                                        </select>
                                    </div>
                                    <div class="input-field fifth-wrap">
                                        <button class="btn-search e_button" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                    <div class="banner-image">
                        <div class="top-banner-info">
                            <div class="icon-info">
                                <img src="<?= SITE; ?>marketplace/images/quality-icon.png">
                            </div>

                            <div class="title-info">
                                <h4>Proof of quality</h4>
                                <p>Lorem Ipsum Dolar Amet</p>
                            </div>

                        </div>
                        <img src="<?= SITE; ?>marketplace/images/test.png" class="into-image">
                        <div class="right-banner-info">
                            <div class="icon-info">
                                <img src="<?= SITE; ?>marketplace/images/safe-icon.png">
                            </div>

                            <div class="title-info">
                                <h4>Safe and secure</h4>
                                <p>Lorem Ipsum Dolar Amet</p>
                            </div>

                        </div>


                        <div class="bottom-banner-info">

                            <div class="people-info">
                                <h4>300+ Courses online</h4>
                            </div>

                            <div class="icon-people">
                                <img src="<?= SITE; ?>marketplace/images/people-icon.png">
                            </div>
                        </div>
                    </div>

                </div>



            </div>

        </div>

    </div>




</section>