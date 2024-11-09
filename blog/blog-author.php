<?php
include_once("../config.ini.php");
$slug = $_GET['author_slug'];
$category_slug = 0;
$category = 0;

if (isset($slug) && !empty($slug)) {

    $stmt = $dbh->prepare("SELECT id,author_name,photo,description,seo_title,seo_description,slug FROM blog_authors WHERE slug=? and status='active'");
    $stmt->bindValue(1, $slug, PDO::PARAM_STR);
    $tmp = $stmt->execute();
    $aux = '';
    $item = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $item = $stmt->fetch(PDO::FETCH_OBJ);
    }
}

if (empty($item)) {
    header('location: ../404');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | <?= $item->seo_title; ?> Category</title>
    <meta name="description" content="<?= $item->seo_description; ?>">
    <meta name="keywords" content="<?= $item->seo_description; ?>">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>favicon.png">
    <?php include_once("includes/include_head.php"); ?>

</head>

<body>

    <div id="main-wrapper">

        <?php
        $heading_title = $item->author_name;
        $heading_description = $item->description;
        $author = $item->id;
        include_once("includes/include_navbar.php");
        include_once("includes/include_author_header.php");
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

                            <div class="row blog-row">

                            </div>

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