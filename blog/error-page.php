<?php
include_once("../config.ini.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | 404 </title>
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
        include_once("includes/include_error_header.php");
        ?>

        <div class="blog-filter">

            <div class="container">
                <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
                    <div class="container">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fa fa-bars"></span> Filter
                        </button>
                        <div class="collapse navbar-collapse" id="ftco-nav">

                            <ul class="navbar-nav" id="blog-category">

                            </ul>

                            <ul class="navbar-nav ml-auto">


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

                        <div class="blog-place error">

                            <h2>Error Page</h2>

                            <img src="<?php echo SITE; ?>blog/images/404-error.png" alt="" class="error_image" />
                            <a href="<?= SITE . "blog" ?>" class="go_back">Go Back to Blog</a>


                        </div>




                    </div>

                </div>
            </div>
        </div>

    </div>

    <?php
    include_once("includes/include_footer.php");
    ?>


</body>

</html>