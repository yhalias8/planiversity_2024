<?php
include_once("../config.ini.php");
// if ($auth->isLogged()) {
//     header('location: welcome');
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Planiversity</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include_once("includes/include_head.php"); ?>
</head>

<body>
    <?php include_once("includes/include_header.php"); ?>

    <section class="marketplace-content">

        <div class="container">

            <div class="marketplace_item">

                <div class="marketplace_heading">

                    <h3>Browse Courses & Learning</h3>

                    <div class="marketplace_subheading">
                        <div class="search_result">
                            <p>245 Services Found</p>
                        </div>

                        <div class="marketplace-category-nav">
                            <ul>
                                <li class="active">Courses & Learning</li>
                                <li>E-books</li>
                                <li>Travel Services</li>
                                <li>Event Services</li>
                            </ul>

                        </div>

                    </div>

                </div>



                <div class="row">

                    <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3 service-item">
                        <div class="service-wrapper">
                            <img src="<?= SITE; ?>marketplace/images/service/01.jpg" alt="" class="service_image">
                            <div class="service-collect">
                                <p><i class="fa fa-heart-o" aria-hidden="true"></i></p>
                            </div>
                            <div class="service-content">
                                <div class="service-header">
                                    <p>Web & App Design</p>
                                    <h4>I will design modern websites in figma or adobe xd</h4>
                                </div>

                                <div class="rating-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ratings">
                                            <i class="fa fa-star rating-color"></i>
                                            <p>4.7 <span> 684 reviews </span></p>
                                        </div>
                                        <!-- <h5 class="review-count">12 Reviews</h5> -->
                                    </div>
                                </div>

                                <div class="service-footer">
                                    <div class="seller-info">
                                        <img src="<?= SITE; ?>ajaxfiles/profile/IMG_1466443467.png">
                                        <p>Wanda Runo</p>
                                    </div>
                                    <div class="service-price">
                                        <p>Starting at <span>$983</span></p>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3 service-item">
                        <div class="service-wrapper">
                            <img src="<?= SITE; ?>marketplace/images/service/02.jpg" alt="" class="service_image">
                            <div class="service-collect">
                                <p><i class="fa fa-heart-o" aria-hidden="true"></i></p>
                            </div>
                            <div class="service-content">
                                <div class="service-header">
                                    <p>Web & App Design</p>
                                    <h4>I will design modern websites in figma or adobe xd</h4>
                                </div>

                                <div class="rating-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ratings">
                                            <i class="fa fa-star rating-color"></i>
                                            <p>4.7 <span> 684 reviews </span></p>
                                        </div>
                                        <!-- <h5 class="review-count">12 Reviews</h5> -->
                                    </div>
                                </div>

                                <div class="service-footer">
                                    <div class="seller-info">
                                        <img src="<?= SITE; ?>ajaxfiles/profile/IMG_1466443467.png">
                                        <p>Wanda Runo</p>
                                    </div>
                                    <div class="service-price">
                                        <p>Starting at <span>$983</span></p>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3 service-item">
                        <div class="service-wrapper">
                            <img src="<?= SITE; ?>marketplace/images/service/01.jpg" alt="" class="service_image">
                            <div class="service-collect">
                                <p><i class="fa fa-heart-o" aria-hidden="true"></i></p>
                            </div>
                            <div class="service-content">
                                <div class="service-header">
                                    <p>Web & App Design</p>
                                    <h4>I will design modern websites in figma or adobe xd</h4>
                                </div>

                                <div class="rating-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ratings">
                                            <i class="fa fa-star rating-color"></i>
                                            <p>4.7 <span> 684 reviews </span></p>
                                        </div>
                                        <!-- <h5 class="review-count">12 Reviews</h5> -->
                                    </div>
                                </div>

                                <div class="service-footer">
                                    <div class="seller-info">
                                        <img src="<?= SITE; ?>ajaxfiles/profile/IMG_1466443467.png">
                                        <p>Wanda Runo</p>
                                    </div>
                                    <div class="service-price">
                                        <p>Starting at <span>$983</span></p>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3 service-item">
                        <div class="service-wrapper">
                            <img src="<?= SITE; ?>marketplace/images/service/02.jpg" alt="" class="service_image">
                            <div class="service-collect">
                                <p><i class="fa fa-heart-o" aria-hidden="true"></i></p>
                            </div>
                            <div class="service-content">
                                <div class="service-header">
                                    <p>Web & App Design</p>
                                    <h4>I will design modern websites in figma or adobe xd</h4>
                                </div>

                                <div class="rating-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ratings">
                                            <i class="fa fa-star rating-color"></i>
                                            <p>4.7 <span> 684 reviews </span></p>
                                        </div>
                                        <!-- <h5 class="review-count">12 Reviews</h5> -->
                                    </div>
                                </div>

                                <div class="service-footer">
                                    <div class="seller-info">
                                        <img src="<?= SITE; ?>ajaxfiles/profile/IMG_1466443467.png">
                                        <p>Wanda Runo</p>
                                    </div>
                                    <div class="service-price">
                                        <p>Starting at <span>$983</span></p>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3 service-item">
                        <div class="service-wrapper">
                            <img src="<?= SITE; ?>marketplace/images/service/01.jpg" alt="" class="service_image">
                            <div class="service-collect">
                                <p><i class="fa fa-heart-o" aria-hidden="true"></i></p>
                            </div>
                            <div class="service-content">
                                <div class="service-header">
                                    <p>Web & App Design</p>
                                    <h4>I will design modern websites in figma or adobe xd</h4>
                                </div>

                                <div class="rating-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ratings">
                                            <i class="fa fa-star rating-color"></i>
                                            <p>4.7 <span> 684 reviews </span></p>
                                        </div>
                                        <!-- <h5 class="review-count">12 Reviews</h5> -->
                                    </div>
                                </div>

                                <div class="service-footer">
                                    <div class="seller-info">
                                        <img src="<?= SITE; ?>ajaxfiles/profile/IMG_1466443467.png">
                                        <p>Wanda Runo</p>
                                    </div>
                                    <div class="service-price">
                                        <p>Starting at <span>$983</span></p>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3 service-item">
                        <div class="service-wrapper">
                            <img src="<?= SITE; ?>marketplace/images/service/02.jpg" alt="" class="service_image">
                            <div class="service-collect">
                                <p><i class="fa fa-heart-o" aria-hidden="true"></i></p>
                            </div>
                            <div class="service-content">
                                <div class="service-header">
                                    <p>Web & App Design</p>
                                    <h4>I will design modern websites in figma or adobe xd</h4>
                                </div>

                                <div class="rating-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ratings">
                                            <i class="fa fa-star rating-color"></i>
                                            <p>4.7 <span> 684 reviews </span></p>
                                        </div>
                                        <!-- <h5 class="review-count">12 Reviews</h5> -->
                                    </div>
                                </div>

                                <div class="service-footer">
                                    <div class="seller-info">
                                        <img src="<?= SITE; ?>ajaxfiles/profile/IMG_1466443467.png">
                                        <p>Wanda Runo</p>
                                    </div>
                                    <div class="service-price">
                                        <p>Starting at <span>$983</span></p>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3 service-item">
                        <div class="service-wrapper">
                            <img src="<?= SITE; ?>marketplace/images/service/01.jpg" alt="" class="service_image">
                            <div class="service-collect">
                                <p><i class="fa fa-heart-o" aria-hidden="true"></i></p>
                            </div>
                            <div class="service-content">
                                <div class="service-header">
                                    <p>Web & App Design</p>
                                    <h4>I will design modern websites in figma or adobe xd</h4>
                                </div>

                                <div class="rating-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ratings">
                                            <i class="fa fa-star rating-color"></i>
                                            <p>4.7 <span> 684 reviews </span></p>
                                        </div>
                                        <!-- <h5 class="review-count">12 Reviews</h5> -->
                                    </div>
                                </div>

                                <div class="service-footer">
                                    <div class="seller-info">
                                        <img src="<?= SITE; ?>ajaxfiles/profile/IMG_1466443467.png">
                                        <p>Wanda Runo</p>
                                    </div>
                                    <div class="service-price">
                                        <p>Starting at <span>$983</span></p>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3 service-item">
                        <div class="service-wrapper">
                            <img src="<?= SITE; ?>marketplace/images/service/02.jpg" alt="" class="service_image">
                            <div class="service-collect">
                                <p><i class="fa fa-heart-o" aria-hidden="true"></i></p>
                            </div>
                            <div class="service-content">
                                <div class="service-header">
                                    <p>Web & App Design</p>
                                    <h4>I will design modern websites in figma or adobe xd</h4>
                                </div>

                                <div class="rating-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="ratings">
                                            <i class="fa fa-star rating-color"></i>
                                            <p>4.7 <span> 684 reviews </span></p>
                                        </div>
                                        <!-- <h5 class="review-count">12 Reviews</h5> -->
                                    </div>
                                </div>

                                <div class="service-footer">
                                    <div class="seller-info">
                                        <img src="<?= SITE; ?>ajaxfiles/profile/IMG_1466443467.png">
                                        <p>Wanda Runo</p>
                                    </div>
                                    <div class="service-price">
                                        <p>Starting at <span>$983</span></p>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                </div>


            </div>

        </div>

    </section>


    <section class="category_browse">

        <div class="container">
            <div class="category_heading">
                <h3>Browse by category</h3>
                <p>Get some Inspirations from 1800+ Courses</p>
            </div>



            <div class="carousel-content">

                <div class="row owl-carousel owl-theme">

                    <div class="category-item item">
                        <div class="category-content">
                            <img src="<?= SITE; ?>marketplace/images/category/01.jpg" alt="" class="category_image">
                            <div class="category-body">
                                <p>1.853 Courses</p>
                                <h3>Courses & Learning</h3>
                            </div>
                        </div>
                    </div>

                    <div class="category-item item">
                        <div class="category-content">
                            <img src="<?= SITE; ?>marketplace/images/category/02.jpg" alt="" class="category_image">
                            <div class="category-body">
                                <p>1.853 Courses</p>
                                <h3>Courses & Learning</h3>
                            </div>
                        </div>
                    </div>

                    <div class="category-item item">
                        <div class="category-content">
                            <img src="<?= SITE; ?>marketplace/images/category/03.jpg" alt="" class="category_image">
                            <div class="category-body">
                                <p>1.853 Courses</p>
                                <h3>Courses & Learning</h3>
                            </div>
                        </div>
                    </div>

                    <div class="category-item item">
                        <div class="category-content">
                            <img src="<?= SITE; ?>marketplace/images/category/04.jpg" alt="" class="category_image">
                            <div class="category-body">
                                <p>1.853 Courses</p>
                                <h3>Courses & Learning</h3>
                            </div>
                        </div>
                    </div>
                    <div class="category-item item">
                        <div class="category-content">
                            <img src="<?= SITE; ?>marketplace/images/category/04.jpg" alt="" class="category_image">
                            <div class="category-body">
                                <p>1.853 Courses</p>
                                <h3>Courses & Learning</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owl-theme">
                    <div class="owl-controls">
                        <div class="custom-nav owl-nav"></div>
                    </div>
                </div>

            </div>


        </div>

    </section>

    <?php include_once("includes/include_script.php"); ?>
    <?php include_once("includes/include_footer.php"); ?>

</body>

</html>