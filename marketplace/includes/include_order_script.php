<?php include_once("include_script.php"); ?>

<script>

    let order_props;
    let popup_activation = 0;
    
    $(function() {
        localStorageValueInitialized();
        localStorageWishValueInitialized();
        initialServiceLoad();
    });

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $(document).on("click", ".guest", function(event) {
        if (popup_activation) {
            $('#advanced_popup').modal('show');
        } else {
            window.location.href = SITE + "marketplace/guest-checkout";
        }        
    });
    
    function toogleInfoMesage(value) {
    
            if (value == 'auth') {
                $('.info_message').hide();
            } else {
                $('.info_message').show();
            }
        }    
    
    $(".dropdown-menu li button").click(function(evt) {
        // Setup VARs
        var inputGroup = $('.input-group');
        var inputGroupBtn = inputGroup.find('.input-group-btn .btn');
        var inputGroupAddon = inputGroup.find('.input-group-addon');
        var inputGroupInput = inputGroup.find('.form-control');

        // Get info for the selected country
        var selectedCountry = $(evt.target).closest('li');
        var selectedEmoji = selectedCountry.find('.flag-emoji').html();
        var selectedExampleNumber = selectedCountry.find('.example-number').html();
        var selectedDialCode = selectedCountry.find('.dial-code').html();

        // Dynamically update the picker
        inputGroupBtn.find('.emoji').html(selectedEmoji);
        inputGroupAddon.html(selectedDialCode);
        $('#country_code').val(selectedDialCode);
        inputGroupInput.attr("placeholder", selectedExampleNumber);
    });    
    


$(document).on("click", ".member", function(event) {

        order_props = $(this).data("props");

        toogleInfoMesage(order_props);

        $('.e_button').css('cursor', 'wait');
        $('.e_button').attr('disabled', true);

        $.ajax({
            url: SITE + "root/auth/check",
            type: "GET",
            dataType: "json",
            cache: false,
            success: function(response) {

                if (response.data.responseList) {

                    if (order_props == 'auth') {
                        if (popup_activation) {
                            $('#advanced_popup').modal('show');
                        } else {
                            window.location.href = SITE + "marketplace/member-checkout";
                        }
                    } else {
                        $('#contactModal').modal('show');
                    }

                } else {
                    if (popup_activation) {
                        $('#advanced_popup').modal('show');
                    } else {
                        $('#loginModal').modal('show');
                    }
                }

                $('.e_button').css('cursor', 'pointer');
                $('.e_button').removeAttr('disabled');

            },
            error: function(jqXHR, textStatus, errorThrown) {

                $('.e_button').css('cursor', 'pointer');
                $('.e_button').removeAttr('disabled');

            }

        });

    });
    
    $(document).on("click", ".contact-seller", function(event) {

        $('#advanced_popup').modal('hide');
        $('#contactModal').modal('show');

    });      

    $("#contactform").validate({

        rules: {
            name: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            mobile: {
                maxlength: 20,
            },
            message: {
                required: true,
                minlength: 5,
                maxlength: 150,
            },
        },

        messages: {
            name: {
                required: "Please type your name",
            },
            email: {
                required: "Please type your email",
                email: "Please type valid email",
            },
            message: {
                required: "Please type your message",
                minlength: "Minimum length 5 characters",
                maxlength: "Maximum length 150 characters",
            },
        },

        submitHandler: function(form) {

            $('.process_button').css('cursor', 'wait');
            $('.process_button').attr('disabled', true);

            $.ajax({
                url: SITE + "root/inquiry/process",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(res) {

                    $('#contactModal').modal('hide');
                    $(form).trigger("reset");

                    Swal.fire({
                        title: 'Inquery Submitted',
                        text: "The seller will contact you shortly.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        showCancelButton: false,
                        timer: 4000
                    });

                    $('.process_button').css('cursor', 'pointer');
                    $('.process_button').removeAttr('disabled');

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.responseJSON.data.error) {
                        toastr.error(jqXHR.responseJSON.data.error);
                    }

                    $('.process_button').css('cursor', 'pointer');
                    $('.process_button').removeAttr('disabled');

                }
            });
        },
        //errorElement: "span",                                               // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }

    });


    $("#loginform").validate({

        rules: {
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 3,
            },
        },

        messages: {
            email: {
                required: "Please type your email",
                email: "Please type valid email",
            },
            password: {
                required: "Please type your password",
                minlength: "Minimum password length need to be 5",
            },
        },

        submitHandler: function(form) {

            $('.submit_button').css('cursor', 'wait');
            $('.submit_button').attr('disabled', true);

            $.ajax({
                url: SITE + "root/auth",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(res) {

                    $('#loginModal').modal('hide');

                    if (res.data.success) {
                        if (order_props == 'auth') {
                            toastr.success('Please wait ...');
                            window.location.href = SITE + "marketplace/member-checkout";
                        } else {
                            $('#contactModal').modal('show');
                        }
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.responseJSON.data.error) {
                        toastr.error(jqXHR.responseJSON.data.error);
                    }

                    $('#password').val('');
                    $('.submit_button').css('cursor', 'pointer');
                    $('.submit_button').removeAttr('disabled');

                }
            });
        },
        //errorElement: "span",                                               // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }

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
                
                popup_activation = response.data.popup_activation;

                $(".items").html(response.data.responseList);
                $("#subtotal").html(response.data.service_price);
                $("#total").html(response.data.service_price);
                
                $("#foot_note").show();

                $('.e_button').css('cursor', 'pointer');
                $('.e_button').removeAttr('disabled');

            },
            error: function(jqXHR, textStatus, errorThrown) {

                $('.e_button').css('cursor', 'pointer');
                $('.e_button').removeAttr('disabled');


            }

        });

    }
</script>