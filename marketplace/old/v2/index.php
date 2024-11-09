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

    <section class="marketplace-content" id="marketplace-content">

        <div class="container">

            <div class="marketplace_item">

                <div class="marketplace_heading">

                    <h3>Browse Courses & Learning</h3>

                    <div class="marketplace_subheading">
                        <div class="search_result">
                            <p>245 Services Found</p>
                        </div>

                        <div class="marketplace-category-nav">
                            <ul class="category_nav">
                                <li class="category-link"><button class="button-class active" value="0">All</button></li>
                                <li class="category-link"> <button class="button-class" value="1">Courses & Learning</button></li>
                                <li class="category-link"><button class="button-class" value="2"> E-books </button></li>
                                <li class="category-link"><button class="button-class" value="3"> Travel Services </button></li>
                                <li class="category-link"><button class="button-class" value="4">Event Services </button></li>
                            </ul>

                        </div>

                    </div>

                </div>


                <div class="service_load">
                    <div class="loading_section" style="display: none;">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                    <div class="row" id="service_content"></div>

                </div>

                <div class="action_section">

                    <button class="view_more e_button" value="1">View More</button>

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

                <div class="row owl-carousel owl-theme" id="category_content">




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