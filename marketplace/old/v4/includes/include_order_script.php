<?php include_once("include_script.php"); ?>

<script>
    $(function() {
        localStorageValueInitialized();
        localStorageWishValueInitialized();
        initialServiceLoad();
    });


    $(document).on("click", ".guest", function(event) {
        window.location.href = SITE + "marketplace/guest-checkout";
    });


    function initialServiceLoad() {
        uuid = "<?= $uuid ?>";
        var dataSet = 'service_uuid=' + uuid;
        orderItemProcess(dataSet);
    }

    function orderItemProcess(dataSet) {

        $(".loading_section").show();

        $('.e_button').css('cursor', 'wait');
        $('.e_button').attr('disabled', true);


        $.ajax({
            url: SITE + "root/service/single",
            type: "GET",
            data: dataSet,
            dataType: "json",
            cache: false,
            success: function(response) {

                $(".summary").show();
                $(".loading_section").hide();

                $(".items").html(response.data.responseList);
                $("#subtotal").html(response.data.service_price);
                $("#total").html(response.data.service_price);

                $('.e_button').css('cursor', 'pointer');
                $('.e_button').removeAttr('disabled');

            },
            error: function(jqXHR, textStatus, errorThrown) {

                // $(".loading_screen").hide();
                // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");


            }

        });

    }
</script>