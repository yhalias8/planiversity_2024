<?php include_once("include_script.php"); ?>

<script>
    $(function() {
        initialProcess();
    });


    function initialProcess() {
        let category = 0;
        var dataSet = 'category=' + category + '&page=' + 1;
        blogListProcess(dataSet, null, false);
    }
</script>