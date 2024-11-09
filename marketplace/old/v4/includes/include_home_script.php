<?php include_once("include_script.php"); ?>


<script>
    $(function() {
        localStorageValueInitialized();
        localStorageWishValueInitialized();
        categoryListProcess();
        initialServiceLoad();
    });


    function initialServiceLoad() {
        let uuid = localStorageValueGet();
        var current_page = $('.view_more').val();
        var dataSet = 'category=' + category + '&page=' + 1 + '&uuid=' + uuid;
        serviceListProcess(dataSet);
    }


    $(document).on("click", ".category-item.item", function(event) {
        //var service_id = $(".service-wrapper").data("service-id");
        var categorySlug = $(this).data("category-slug");

        window.location.href = SITE + "marketplace/category/" + categorySlug;

    });

    function carouselLoad() {
        var owl = $('.owl-carousel');
        owl.owlCarousel({
            stagePadding: 10,
            loop: true,
            margin: 10,
            nav: true,
            autoplay: true,
            lazyLoad: true,
            autoplayTimeout: 5000,
            navText: [
                '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
            navContainer: '.main-content .custom-nav',
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 4
                }
            }
        });
    }

    function categoryListProcess() {

        // $('#event_placeholder').addClass("loading");
        // $('#event_content').html('<div class="spinner-grow text-info custom-load"></div>');

        ///var dataSet = 'type=' + type + '&id=' + id;

        $.ajax({
            url: SITE + "root/category/list",
            type: "GET",
            //data: dataSet,
            dataType: 'json',
            cache: false,
            success: function(response) {

                //console.log('response', response.data.list);
                $('#category_content').html(response.data.list);
                $('.service_count').html(response.data.total_count);
                carouselLoad();

            },
            error: function(jqXHR, textStatus, errorThrown) {

                $('#event_content').html("<h2>A system error has been encountered. Please try again</h2>");
                $('#event_placeholder').removeClass("loading");

            }

        });


    }


    $(document).on("click", ".button-class", function(event) {
        let uuid = localStorageValueGet();
        var value_hold = $(this).attr("value");
        var current_page = $('.view_more').val();
        var dataSet = 'category=' + value_hold + '&page=' + 1 + '&uuid=' + uuid;
        category = value_hold;
        serviceListProcess(dataSet, true);

        classToggle(this);
    });

    const classToggle = (e) => {
        $('.button-class').removeClass("active");
        $(e).addClass("active");
    }
</script>