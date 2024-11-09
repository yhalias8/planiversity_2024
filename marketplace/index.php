<?php
include_once("../config.ini.php");
$flag = "wish_call";
$wish_clause = 0;
$search_parms = null;
$category_parms = null;
if (isset($_GET['wish'])) {
    $wish_clause = 1;
}

if (isset($_GET['category'])) {
    $category_parms = $_GET['category'];
}

if (isset($_GET['search'])) {
    $search_parms = $_GET['search'];
}

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
    <?php include_once("includes/include_home_header.php"); ?>

    <section class="marketplace-content" id="marketplace-content">

        <div class="container">

            <div class="marketplace_item">

                <div class="marketplace_heading">

                    <h3>Browse Service</h3>

                    <div class="marketplace_subheading">
                        <div class="search_result">
                            <p><span class="service_count">0</span> Services Found</p>
                        </div>

                        <div class="marketplace-category-nav">
                            <ul class="category_nav">
                                <li class="category-link"><button class="button-class active" value="0">All</button></li>
                                <li class="category-link"><button class="button-class" value="3"> Travel Services </button></li>
                                <li class="category-link"><button class="button-class" value="4">Event Services </button></li>
                                <li class="category-link"><button class="button-class" value="10">Adventure </button></li>
                                <li class="category-link"><button class="button-class" value="11">Concierge Services </button></li>                                
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
                    <div id="page-content" class="page-content">Page 1</div>
                    <ul id="pagination-step" class="pagination-sm"></ul>
                </div>


            </div>

        </div>

    </section>

    <section class="category_browse">

        <div class="container">
            <div class="category_heading">
                <h3>Browse by category</h3>
                <p>Get some Inspirations from <span class="service_count">0</span>+ Services</p>
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



    <?php include_once("includes/include_home_script.php"); ?>
    <?php include_once("includes/include_footer.php"); ?>

</body>

</html>