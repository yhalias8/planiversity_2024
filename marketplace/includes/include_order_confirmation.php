<?php include_once("include_script.php"); ?>

<script>
    $(function() {
        localStorageValueInitialized();
        localStorageWishValueInitialized();
        initialServiceLoad();
    });

    let service_uuid = null;

    function initialServiceLoad() {
        order_number = "<?= $order_number ?>";
        var dataSet = 'order_number=' + order_number;
        confirmationProcess(dataSet);
    }


    function confirmationProcess(dataSet) {

        $(".loading_section").show();


        $.ajax({
            url: SITE + "root/order/confirmation",
            type: "GET",
            data: dataSet,
            dataType: "json",
            cache: false,
            success: function(response) {

                $(".order-place").show();
                $(".loading_section").hide();
                $(".order-info").html(response.data.responseList);


            },
            error: function(jqXHR, textStatus, errorThrown) {

                // $(".loading_screen").hide();
                // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");


            }

        });

    }



    $(document).on("click", ".guest", function(event) {
        window.location.href = SITE + "marketplace/guest-checkout";
    });
</script>