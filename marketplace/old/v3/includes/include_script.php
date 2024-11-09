<div class="modal fade modal-blur" tabindex="-1" role="dialog" id="master_modal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content custom-content">
            <!-- <div class="modal-header">
                    <h5 class="modal-title">Service Details</h5>
                </div> -->
            <div class="modal-body">

                <div class="row">

                    <div class="col-lg-12">

                        <div class="service_head">
                            <div class="service_heading">
                                <img src="https://via.placeholder.com/200/FFFFFF/000000/?text=loading" class="heading_img">
                                <h3>I will do mobile app development for ios and android</h3>
                                <h5>Courses & Learning</h5>
                            </div>

                            <div class="payment_info">

                                <p>Regular Price : <span>$350</span></p>
                                <p>Sale Price : <span>$250</span></p>
                                <p>Member Price : <span>$230</span></p>

                            </div>

                        </div>

                        <div class="service-footer no-footer">
                            <div class="seller-info">
                                <img src="https://www.planiversity.com/staging/ajaxfiles/profile/IMG_1466443467.png">
                                <p id="author_name">Wanda Runo</p>
                            </div>
                            <div class="rating-section">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="ratings">
                                        <i class="fa fa-star rating-color"></i>
                                        <p>4.7 <span> 684 reviews </span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=""></div>

                        <div class="seller_info">

                            <p>Loading...</p>

                        </div>

                        <div class="service_body">

                            <h3>About This Service</h3>

                            <div class="service_content"></div>

                        </div>

                    </div>



                </div>


            </div>
            <div class=" modal-footer">
                <button type="button" class="btn btn-secondary master_modal_click" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary buy-btn" value=""><i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy</button>

            </div>
        </div>
    </div>
</div>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>

<script>
    let serviceList = [];
    let next_page = null;
    let category = 0;

    function uuidv4() {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }

    const localStorageValueInitialized = () => {
        if (localStorage.getItem("planiversity_uuid") === null) {
            localStorage.setItem('planiversity_uuid', JSON.stringify(uuidv4()));
        }
    }

    const localStorageWishValueInitialized = () => {
        if (localStorage.getItem("planiversity_wishlist") === null) {
            localStorage.setItem('planiversity_wishlist', JSON.stringify(0));
        }
        notificationValueProcess();
    }

    const localStorageValueGet = () => {
        if (localStorage.getItem('planiversity_uuid') !== null) {
            var savedValue = JSON.parse(localStorage.getItem('planiversity_uuid'));
            return savedValue;
        }
    }

    const localStorageWishlistValueGet = () => {
        if (localStorage.getItem('planiversity_wishlist') !== null) {
            var savedValue = JSON.parse(localStorage.getItem('planiversity_wishlist'));
            return savedValue;
        }
    }

    const localStorageWishlistValueSet = (value) => {
        localStorage.setItem('planiversity_wishlist', JSON.stringify(value));
    }

    $(document).on("click", ".wish-class", function(event) {
        let uuid = localStorageValueGet();
        //var wishID = $(this).data("wish-id");
        var wishID = $(this).val();
        var serviceID = $(this).data("service-id");
        var dataSet = 'wish_id=' + wishID + '&service_id=' + serviceID + '&uuid=' + uuid;
        wishlistProcess(dataSet, this);

    });


    $(document).on("click", ".buy-btn", function(event) {
        var serviceUUID = $(this).val();

        window.location.href = SITE + "marketplace/order/" + serviceUUID;

    });


    $(document).on("click", "img.service_image,.service-header h4", function(event) {
        //var service_id = $(".service-wrapper").data("service-id");
        var serviceID = $(this).parent().closest('.service-wrapper').data("service-id");
        const matchValue = serviceList.findIndex((item) => item.id == serviceID);
        serviceData = serviceList[matchValue];

        console.log('serviceData', serviceData);

        serviceValueProcess(serviceData);

        $("#master_modal").modal("show");
    });

    function serviceValueProcess(serviceData) {

        $('.service_heading h3').html(serviceData.service_title);
        $('.service_heading h5').html(serviceData.category.category_name);
        $('img.heading_img').attr('src', FILE_PATH + 'images/service/' + serviceData.service_image);
        $('.seller_info').html(serviceData.author_description);
        $('#author_name').html(serviceData.author_name);
        $('.service_content').html(serviceData.service_description);
        $('.buy-btn').val(serviceData.service_uuid);

        servicePriceProcess(serviceData.regular_price, serviceData.sale_price, serviceData.member_price);

    }

    function servicePriceProcess(regular_price, sale_price, member_price) {
        var items = "";

        if (sale_price == 0) {
            items += "<p>Regular Price : <span>$" + regular_price + "</span></p>";
        } else {
            items += "<p class='old-price'>Regular Price : <span>$" + regular_price + "</span></p>";
            items += "<p>Sale Price : <span>$" + sale_price + "</span></p>";
        }

        items += "<p>Member Price : <span>$" + member_price + "</span></p>";

        $(".payment_info").html(items);

    }



    const localStorageWishValueProcess = (type) => {
        let currentValue = localStorageWishlistValueGet();

        if (type == "Add") {
            localStorageWishlistValueSet(currentValue + 1);
        } else {
            localStorageWishlistValueSet(currentValue - 1);
        }
        notificationValueProcess();

    }

    const notificationValueProcess = () => {
        let currentValue = localStorageWishlistValueGet();
        $('.wishlist_section span.badge').html(currentValue);

    }

    function wishlistProcess(dataSet, wishItem) {

        $('.wish-class').css('cursor', 'wait');
        $('.wish-class').attr('disabled', true);

        $.ajax({
            url: SITE + "root/wishlist/process",
            type: "POST",
            data: dataSet,
            dataType: "json",
            success: function(response) {

                console.log('Data Type', response.results.data);

                localStorageWishValueProcess(response.results.data);

                if (response.results.data == "Add") {
                    console.log('Inside Add');
                    $(wishItem).val(response.results.id);
                    $(wishItem).addClass("added");
                } else {
                    $(wishItem).removeClass("added");
                    console.log('Inside Remove');
                    $(wishItem).val('');
                }

                $('.wish-class').css('cursor', 'pointer');
                $('.wish-class').removeAttr('disabled');

            },
            error: function(jqXHR, textStatus, errorThrown) {

                // $(".loading_screen").hide();
                // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");

                $('.wish-class').css('cursor', 'pointer');
                $('.wish-class').removeAttr('disabled');


            }

        });

    }



    $(document).on("click", ".wishlist_section", function(event) {
        let uuid = localStorageValueGet();
        var dataSet = 'uuid=' + uuid;
        wishListData(dataSet, true, true);
        $('html, body').animate({
            scrollTop: $("#marketplace-content").offset().top - 70
        }, 1000);

    });


    function wishListData(dataSet, cat_mode = null, data_empty = null) {
        if (data_empty) {
            $("#service_content").html("");
        }

        $(".loading_section").show();

        $('.wishlist_section').css('cursor', 'wait');
        $('.wishlist_section').attr('disabled', true);

        if (cat_mode) {
            $('.button-class').css('cursor', 'wait');
            $('.button-class').attr('disabled', true);
        }

        $.ajax({
            url: SITE + "root/wish/list",
            type: "GET",
            data: dataSet,
            dataType: "json",
            success: function(response) {

                $(".loading_section").hide();
                $("#service_content").html(response.data.responseList);
                serviceList = [...response.data.results.data];

                console.log('response', response);
                console.log('serviceList', serviceList);

                next_page = response.data.next_page;

                $(".view_more").val(response.data.next_page);

                if (response.data.results.next_page_url == null) {
                    $(".view_more").hide();
                } else {
                    $(".view_more").show();
                }

                console.log('serviceList', serviceList);

                if (cat_mode) {
                    $('.button-class').css('cursor', 'pointer');
                    $('.button-class').removeAttr('disabled');
                }

                $('.wishlist_section').css('cursor', 'pointer');
                $('.wishlist_section').removeAttr('disabled');


            },
            error: function(jqXHR, textStatus, errorThrown) {

                // $(".loading_screen").hide();
                // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");


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
            let uuid = localStorageValueGet();
            var search = $('#search').val();
            var category_field = $('.category_field').val();

            var dataSet = 'category=' + category_field + '&search=' + search + '&page=' + 1 + '&uuid=' + uuid;
            serviceListProcess(dataSet, true, "", true);
            $('html, body').animate({
                scrollTop: $("#marketplace-content").offset().top - 70
            }, 1000);


        }, // Do not change code below
        errorPlacement: function(error, element) {
            //error.insertAfter(element.parent());
        }


    });



    $(document).on("click", ".view_more", function(event) {
        let uuid = localStorageValueGet();
        var current_page = $(this).val();
        var dataSet = 'category=' + category + '&page=' + current_page + '&uuid=' + uuid;
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
            cache: false,
            success: function(response) {

                console.log('response', response);

                $(".loading_section").hide();
                if (load_more) {
                    $("#service_content").append(response.data.responseList);
                    serviceList = [...serviceList, ...response.data.results.data];
                } else {
                    $("#service_content").html(response.data.responseList);
                    serviceList = [...response.data.results.data];
                }

                next_page = response.data.next_page;
                // request_query = response.data.request_query;

                console.log('serviceList', serviceList);

                $(".view_more").val(response.data.next_page);
                // $("#previous_button").val(response.data.previous_page);

                // if (response.data.responseList) {
                //     $("#api_proceced").val(1);
                //     $("#api_query").val(request_query);
                // }

                // if (next_page || previous_page) {
                //     $(".load_more").show();
                // }

                console.log('response.data.results.next_page_url', response.data.results.next_page_url);

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