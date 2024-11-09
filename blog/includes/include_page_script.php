<?php include_once("include_script.php"); ?>

<script>
    $(function() {
        getCategoryList(category_slug);
        initialProcess();
    });

    function initialProcess() {
        var dataSet = 'category=' + category + '&author=' + author + '&page=' + 1;
        blogListProcess(dataSet, null, false);
    }
</script>