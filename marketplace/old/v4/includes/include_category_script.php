<?php include_once("include_script.php"); ?>


<script>
    $(function() {
        localStorageValueInitialized();
        localStorageWishValueInitialized();
        initialServiceLoad();
    });

    function initialServiceLoad() {
        category = "<?= $items->id ?>";
        let uuid = localStorageValueGet();
        var current_page = $('.view_more').val();
        var dataSet = 'category=' + category + '&page=' + 1 + '&uuid=' + uuid;
        serviceListProcess(dataSet);
    }
</script>