<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>


<script>
    let returnValues = [];
    let next_page = null;
    let category = 0;


    $(function() {
        categoryListProcess();
        initialServiceLoad();
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


    $("#search_form").validate({
        rules: {
            search: {
                required: true,
            },
            category_field: {
                required: true,
            },
        },
        messages: {
            search: {
                required: 'Please type your keyword'
            },
            category_field: {
                required: 'Please select a category'
            },
        },


        submitHandler: function(form) {

            var search = $('#search').val();
            var category_field = $('.category_field').val();

            var dataSet = 'category=' + category_field + '&search=' + search + '&page=' + 1;
            serviceListProcess(dataSet, true, "", true);
            $('html, body').animate({
                scrollTop: $("#marketplace-content").offset().top - 70
            }, 1000);


        }, // Do not change code below
        errorPlacement: function(error, element) {
            //error.insertAfter(element.parent());
        }


    });


    function categoryListProcess() {

        // $('#event_placeholder').addClass("loading");
        // $('#event_content').html('<div class="spinner-grow text-info custom-load"></div>');

        ///var dataSet = 'type=' + type + '&id=' + id;

        $.ajax({
            url: SITE + "root/category/list",
            type: "GET",
            //data: dataSet,
            dataType: 'json',
            success: function(response) {

                //console.log('response', response.data.list);
                $('#category_content').html(response.data.list);
                carouselLoad();

            },
            error: function(jqXHR, textStatus, errorThrown) {

                $('#event_content').html("<h2>A system error has been encountered. Please try again</h2>");
                $('#event_placeholder').removeClass("loading");

            }

        });


    }


    function initialServiceLoad() {
        var current_page = $('.view_more').val();
        var dataSet = 'category=' + category + '&page=' + 1;
        serviceListProcess(dataSet);
    }

    $(document).on("click", ".button-class", function(event) {

        var value_hold = $(this).attr("value");
        var current_page = $('.view_more').val();
        var dataSet = 'category=' + value_hold + '&page=' + 1;
        category = value_hold;
        serviceListProcess(dataSet, true);

        classToggle(this);
    });

    const classToggle = (e) => {
        $('.button-class').removeClass("active");
        $(e).addClass("active");
    }


    $(document).on("click", ".view_more", function(event) {
        var current_page = $(this).val();
        var dataSet = 'category=' + category + '&page=' + current_page;
        serviceListProcess(dataSet, true, true);
    });

    function serviceListProcess(dataSet, cat_mode = null, load_more = null, data_empty = null) {
        if (data_empty) {
            $("#service_content").html("");
        }

        $(".loading_section").show();
        // $(".load_more").hide();
        // $(".target_action").attr("disabled", false);
        // $(".background_search").attr("disabled", true);

        $('.e_button').css('cursor', 'wait');
        $('.e_button').attr('disabled', true);

        if (cat_mode) {
            $('.button-class').css('cursor', 'wait');
            $('.button-class').attr('disabled', true);
        }

        $.ajax({
            url: SITE + "root/service/list",
            type: "GET",
            data: dataSet,
            dataType: "json",
            success: function(response) {

                console.log('response', response);

                $(".loading_section").hide();
                if (load_more) {
                    $("#service_content").append(response.data.responseList);
                } else {
                    $("#service_content").html(response.data.responseList);
                }

                next_page = response.data.next_page;
                // request_query = response.data.request_query;
                // returnValues = [...response.data.return_values];

                $(".view_more").val(response.data.next_page);
                // $("#previous_button").val(response.data.previous_page);

                // if (response.data.responseList) {
                //     $("#api_proceced").val(1);
                //     $("#api_query").val(request_query);
                // }

                // if (next_page || previous_page) {
                //     $(".load_more").show();
                // }


                if (response.data.results.next_page_url == null) {
                    $(".view_more").hide();
                } else {
                    $(".view_more").show();
                }

                // if (previous_page == null) {
                //     $("#previous_button").attr("disabled", true);
                // }

                // $(".background_search").attr("disabled", false);

                if (cat_mode) {
                    $('.button-class').css('cursor', 'pointer');
                    $('.button-class').removeAttr('disabled');
                }

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