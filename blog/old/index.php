<?php
include_once("../config.ini.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | Blog </title>
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management.">
    <meta name="keywords" content="Consolidated Travel Itinerary Management Blog">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>favicon.png">
    <?php include_once("includes/include_head.php"); ?>

</head>

<body>

    <div id="main-wrapper">

        <?php
        include_once("includes/include_navbar.php");
        include_once("includes/include_home_banner.php");
        ?>

        <div class="blog-filter">

            <div class="container">
                <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
                    <div class="container">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fa fa-bars"></span> Filter
                        </button>
                        <div class="collapse navbar-collapse" id="ftco-nav">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a href="#" class="nav-link">Features</a></li>
                                <li class="nav-item"><a href="#" class="nav-link">Air Travel</a></li>
                                <li class="nav-item"><a href="#" class="nav-link">Travel Planning</a></li>
                                <li class="nav-item"><a href="#" class="nav-link">Podcast</a></li>
                                <li class="nav-item"><a href="#" class="nav-link">Event Planning </a></li>
                            </ul>

                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">

                                    <div class="row search-place">

                                        <div class="col-md-2 col-2 padding_right">
                                            <div class="search_btn">
                                                <button type="button" id="keyword-button" class="btn_btn"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>

                                        <div class="col-md-10 col-12">
                                            <input type="text" id="keyword-input" class="form-control" placeholder="Search by Keyword">
                                        </div>

                                    </div>


                                </li>

                            </ul>

                        </div>
                    </div>
                </nav>

            </div>
        </div>

        <div class="blog-section spacer pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">

                        <div class="blog-place">

                            <div class="loading_section" style="display: none;">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>

                            <div class="row blog-row">

                                <!-- <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 blog-single">
                                    <div class="blog-wrapper">
                                        <img src="<?php echo SITE; ?>blog/images/blog_image/blog_1.png" alt="" />
                                        <div class="blog-content">
                                            <div class="info-header">
                                                <img src="<?php echo SITE; ?>images/img1.png">
                                                <div class="info-text">
                                                    <h4>TRAVEL TIPS</h4>
                                                    <h5>Erich Allen <span>April 01, 2022</span></h5>
                                                </div>
                                            </div>
                                            <h3>Why Being Nice Makes Travel Easier</h3>

                                            <p><a href="">Read more</a></p>

                                        </div>
                                    </div>
                                </div> -->

                                <!-- <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 blog-single">
                                    <div class="blog-wrapper">
                                        <img src="<?php echo SITE; ?>blog/images/blog_image/blog_2.png" alt="" />
                                        <div class="blog-content">
                                            <div class="info-header">
                                                <img src="<?php echo SITE; ?>images/img1.png">
                                                <div class="info-text">
                                                    <h4>TRAVEL TIPS</h4>
                                                    <h5>Erich Allen <span>April 01, 2022</span></h5>
                                                </div>
                                            </div>
                                            <h3>What If My Hotel Room Doesn’t Look Like The Photos?</h3>

                                            <p><a href="">Read more</a></p>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 blog-single">
                                    <div class="blog-wrapper">
                                        <img src="<?php echo SITE; ?>blog/images/blog_image/blog_3.png" alt="" />
                                        <div class="blog-content">
                                            <div class="info-header">
                                                <img src="<?php echo SITE; ?>images/img1.png">
                                                <div class="info-text">
                                                    <h4>TRAVEL TIPS</h4>
                                                    <h5>Erich Allen <span>April 01, 2022</span></h5>
                                                </div>
                                            </div>
                                            <h3>5 Ways Travel Planning Is Beneficial</h3>

                                            <p><a href="">Read more</a></p>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 blog-single">
                                    <div class="blog-wrapper">
                                        <img src="<?php echo SITE; ?>blog/images/blog_image/blog_4.png" alt="" />
                                        <div class="blog-content">
                                            <div class="info-header">
                                                <img src="<?php echo SITE; ?>images/img1.png">
                                                <div class="info-text">
                                                    <h4>TRAVEL TIPS</h4>
                                                    <h5>Erich Allen <span>April 01, 2022</span></h5>
                                                </div>
                                            </div>
                                            <h3>How to Communicate When They Don’t Speak My Language?</h3>

                                            <p><a href="">Read more</a></p>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 blog-single">
                                    <div class="blog-wrapper">
                                        <img src="<?php echo SITE; ?>blog/images/blog_image/blog_5.png" alt="" />
                                        <div class="blog-content">
                                            <div class="info-header">
                                                <img src="<?php echo SITE; ?>images/img1.png">
                                                <div class="info-text">
                                                    <h4>TRAVEL TIPS</h4>
                                                    <h5>Erich Allen <span>April 01, 2022</span></h5>
                                                </div>
                                            </div>
                                            <h3>The Importance Of Doing Research</h3>

                                            <p><a href="">Read more</a></p>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 blog-single">
                                    <div class="blog-wrapper">
                                        <img src="<?php echo SITE; ?>blog/images/blog_image/blog_6.png" alt="" />
                                        <div class="blog-content">
                                            <div class="info-header">
                                                <img src="<?php echo SITE; ?>images/img1.png">
                                                <div class="info-text">
                                                    <h4>TRAVEL TIPS</h4>
                                                    <h5>Erich Allen <span>April 01, 2022</span></h5>
                                                </div>
                                            </div>
                                            <h3>5 Tips for Easier Holiday Travel</h3>

                                            <p><a href="">Read more</a></p>

                                        </div>
                                    </div>
                                </div> -->

                            </div>

                        </div>


                    </div>

                </div>
            </div>
        </div>

    </div>

    <?php
    include_once("includes/include_home_script.php");
    include_once("includes/include_footer.php");
    ?>


</body>

</html>