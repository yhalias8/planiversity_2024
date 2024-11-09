<?php
include_once("../config.ini.php");
$category_slug = 0;
$category = 0;
$author = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | Blog </title>
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management.">
    <meta name="keywords" content="Planiversity Consolidated Travel Itinerary Management Blog">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>favicon.png">
    <?php include_once("includes/include_head.php"); ?>

</head>

<body>

    <div id="main-wrapper">

        <?php
        include_once("includes/include_navbar.php");
        include_once("includes/include_home_banner.php");
        include_once("includes/include_top_filter.php");
        ?>


        <div class="blog-section spacer pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">

                        <div class="blog-place">

                            <div class="loading_section" style="display: none;">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>

                            <div class="row blog-row"></div>

                            <div class="action_section">
                                <div id="page-content" class="page-content">Page 1</div>
                                <ul id="pagination-step" class="pagination-sm"></ul>
                            </div>

                        </div>


                    </div>

                </div>
            </div>
        </div>

    </div>

    <?php
    include_once("includes/include_page_script.php");
    include_once("includes/include_footer.php");
    ?>


</body>

</html>