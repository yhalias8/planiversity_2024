<?php
include_once("../config.ini.php");
include_once("process/blogSingle.php");
$slug = $_GET['post_slug'];

if (isset($slug) && !empty($slug)) {

    $item = [];

    $URL = ADMIN_URL;
    $FILE_PATH = ADMIN_URL_UPLOADS;
    $PATH = "blog/single";
    $SITE = SITE . "post/";
    $parms = "";
    if ($slug) {
        $parms .= "?slug=" . $slug;
    }
    $API_GET_PATH = $URL . $PATH . $parms;

    $mData = curlRequestGet($API_GET_PATH);
    $rData = json_decode($mData);

    if ($rData && !empty($rData)) {
        $item = $rData;
    }
}

if (empty($item)) {
    header('location: ../blog/404');
}

$parms = "images/blog/post/";
$featured_image = file_path_process($FILE_PATH, $parms, $item->featured_image);
$author_parms = "images/blog/author/";
$author_image = file_path_process($FILE_PATH, $author_parms, $item->author->photo);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | <?= $item->post_title; ?> Category</title>
    <meta name="description" content="<?= $item->seo_description; ?>">
    <meta name="keywords" content="<?= $item->seo_keyword; ?>">
    <meta name="author" content="<?= $item->author->author_name; ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>favicon.png">

    <meta property="og:url" content="<?= $SITE . $item->post_slug; ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $item->seo_title; ?>">
    <meta property="og:description" content="<?= $item->seo_description; ?>">
    <meta property="og:image" content="<?= $featured_image; ?>">


    <?php include_once("includes/include_head.php"); ?>

</head>

<body>

    <div id="main-wrapper">

        <?php
        $heading_title = $item->post_title;
        include_once("includes/include_navbar.php");
        include_once("includes/include_blog_header.php");
        ?>

        <div class="blog-filter">

            <div class="container">
                <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar"></nav>
            </div>

        </div>


        <div class="container my-5">
            <img class="w-100 my-3" src="<?= $featured_image ?>" />
            <div style="max-width: 700px; top: -80px;" class="mx-auto text-secondary position-relative">
                <div class="text-center mb-4">
                    <img src="<?= $author_image; ?>" style="width: 120px; height:120px; border: 5px solid #eee; background-color: white;" class="rounded-circle mx-auto text-center" />
                    <div class="mt-2 mb-2">
                        <small class="blog-category">
                            <?php
                            $categories = blog_category_process($item->category_type, SITE);
                            ?>
                        </small>
                    </div>
                    <h2 class="text-center font-weight-bold text-dark"><?= $item->post_title ?>
                    </h2>
                    <div>
                        <small class="text-dark">
                            Written by <a href="<?= SITE . "blog/author/" . $item->author->slug; ?>" class="text-primary"><?= $item->author->author_name; ?></a>
                        </small>
                        <small class="text-dark ml-3">
                            <?= $item->published_at; ?>
                        </small>
                    </div>
                </div>


                <div class="blog-content-section">
                    <?= blog_post_process($item->post_content); ?>
                </div>



            </div>
        </div>

    </div>


    <?php
    include_once("includes/include_footer.php");
    ?>


</body>

</html>