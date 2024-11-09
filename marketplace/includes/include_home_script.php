<?php include_once("include_script.php"); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>
<script>
    let wish_clause = "<?= $wish_clause ?>";
    let category_parms = "<?= $category_parms ?>";
    let search_parms = "<?= $search_parms ?>";    
    
    let container = $(".advanced_search_section");
    let searchForm = $("#search-form");    
    
    function initMap() {
        var locationInput = document.getElementById('location');
        var spinner = document.getElementById('spinner_section');

        var originLocationAutocomplete = new google.maps.places.Autocomplete(locationInput);

        originLocationAutocomplete.addListener('place_changed', function() {

            spinner.style.display = 'flex';
            locationInput.disabled = true;

            // Get the selected place from the autocomplete object
            var place = originLocationAutocomplete.getPlace();

            //if the place has a valid geometry
            if (place.geometry && place.geometry.location) {
                // Get the latitude and longitude of the location
                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();

                $('#latitude').val(lat);
                $('#longitude').val(lng);
            }

            setTimeout(() => {
                locationInput.disabled = false;
                spinner.style.display = 'none';
                console.log('None');
            }, 100);


        });

        locationInput.addEventListener('input', function() {
            // If the input field is empty, clear the latitude and longitude fields
            if (!locationInput.value) {
                $('#latitude').val('');
                $('#longitude').val('');
            }
        });


    }    

    $(function() {
        localStorageValueInitialized();
        localStorageWishValueInitialized();
        categoryListProcess();
        initialProcess();
    });
    
    $(document).mouseup(function(e) {
        console.log('mouseup');

        if (!searchForm.is(e.target) && searchForm.has(e.target).length === 0) {
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide();
                console.log('hide');
            }
        }
    });    

    $(document).on("click", ".advanced_link", function(event) {
        container.show();
    });

    function initialServiceLoad() {
        let uuid = localStorageValueGet();
        var current_page = $('.view_more').val();
        var dataSet = 'category=' + category + '&page=' + 1 + '&uuid=' + uuid;
        serviceListProcess(dataSet);
    }
    
    function initialSearchServiceLoad() {

        $('#search').val(search_parms);
        $('#category_field').val(category_parms);

        let uuid = localStorageValueGet();
        var current_page = $('.view_more').val();
        var dataSet = 'category=' + category_parms + '&search=' + search_parms + '&page=' + 1 + '&uuid=' + uuid;
        serviceListProcess(dataSet, true, "", true);

    }    


    function initialProcess() {

        if (wish_clause == "0") {

            if (category_parms && search_parms) {
                console.log('Load Params');
                //initialServiceLoad();
                initialSearchServiceLoad();
            } else {
                initialServiceLoad();
            }

        } else {
            console.log('wishing');
            wishCall();
        }
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
            success: function(response) {

                //console.log('response', response.data.list);
                $('#category_content').html(response.data.list);
                //$('.service_count').html(response.data.total_count);
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