<?php
include_once("../config.ini.php");
$slug = $_GET['slug'];

if ($_GET['slug']) {

    $stmt = $dbh->prepare("SELECT id,category_name FROM marketplace_category WHERE slug=?");
    $stmt->bindValue(1, $slug, PDO::PARAM_STR);
    $tmp = $stmt->execute();
    $aux = '';
    $items = [];
    if ($tmp && $stmt->rowCount() > 0) {
        $items = $stmt->fetch(PDO::FETCH_OBJ);
    }
}

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

    <?php include_once("includes/include_page_header.php"); ?>

    <section class="marketplace-content" id="marketplace-content">

        <div class="container">

            <div class="marketplace_item">

                <div class="marketplace_heading">

                    <h3>Browse <?= $items->category_name; ?></h3>

                    <div class="marketplace_subheading">
                        <div class="search_result">
                            <p>245 Services Found</p>
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

                    <button class="view_more e_button" value="1" style="display: none;">View More</button>

                </div>


            </div>

        </div>

    </section>


    <?php include_once("includes/include_category_script.php"); ?>
    <?php include_once("includes/include_footer.php"); ?>

</body>

</html>