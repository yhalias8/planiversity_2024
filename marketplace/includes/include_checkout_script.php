<?php include_once("include_script.php"); ?>

<script>
    $(function() {
        localStorageValueInitialized();
        localStorageWishValueInitialized();
        initialServiceLoad();
    });

    let service_uuid = null;
    let member = null;
    let u_number = "<?= $u_number ?>";
    let uuid = localStorageValueGet();

    function initialServiceLoad() {
        service_uuid = "<?= $service_uuid ?>";
        member = "<?= $member ?>";
        var dataSet = 'service_uuid=' + service_uuid + '&member=' + member;
        orderItemProcess(dataSet);
    }


    function orderItemProcess(dataSet) {

        $(".loading_section").show();

        $('.stripe_process').css('cursor', 'wait');
        $('.stripe_process').attr('disabled', true);

        $.ajax({
            url: SITE + "root/service/single",
            type: "GET",
            data: dataSet,
            dataType: "json",
            cache: false,
            success: function(response) {

                $(".summary").show();
                $(".loading_section").hide();

                $(".order-summary").html(response.data.responseList);
                $("#subtotal").html(response.data.service_price);
                $("#total").html(response.data.service_price);

                $('.stripe_process').css('cursor', 'pointer');
                $('.stripe_process').removeAttr('disabled');

            },
            error: function(jqXHR, textStatus, errorThrown) {

                // $(".loading_screen").hide();
                // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");


            }

        });

    }

    // Create a Stripe client.
    var stripe = Stripe('pk_live_wbt9OmxTfkNhl2a4eSMsYTBU');

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', {
        style: style
    });

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });



    $("#payment-form").validate({
        rules: {

            fname: {
                required: true,
            },
            lname: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            address: {
                required: true,
            },
            city: {
                required: true,
            },
            state: {
                required: true,
            },
            zip: {
                required: true,
            },

        },
        messages: {

            fname: {
                required: 'Please type your first name'
            },
            lname: {
                required: 'Please type your last name'
            },
            email: {
                required: 'Please type your email address',
                email: 'Please type vaild email address'
            },
            address: {
                required: 'Please type your address'
            },
            city: {
                required: 'Please type your city',
            },
            state: {
                required: 'Please type your state',
            },
            zip: {
                required: 'Please type your zip',
            },

        },


        submitHandler: function(form) {

            $(".loading_section").show();

            $('.stripe_process').css('cursor', 'wait');
            $('.stripe_process').attr('disabled', true);

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    $(".loading_section").hide();
                    $('.stripe_process').css('cursor', 'pointer');
                    $('.stripe_process').removeAttr('disabled');

                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    stripeTokenHandler(result.token, form);
                }
            });

            // $('#payment_auth_submit').css('cursor', 'wait');
            // $('#payment_auth_submit').attr('disabled', true);

            // $("#payment_auth_confirm").modal('hide');
            // $('#payment_loading_screen').modal('show');

            // Stripe.createToken({
            //     number: $('#payment_cardnumber').val(),
            //     cvc: $('#payment_cvc').val(),
            //     exp_month: $('#payment_expmonth').val(),
            //     exp_year: $('#payment_expyear').val()
            // }, stripeResponseHandler);
            // return false;


        }, // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }


    });


    // // Handle form submission.
    // var form = document.getElementById('payment-form');
    // form.addEventListener('submit', function(event) {
    //     event.preventDefault();

    //     stripe.createToken(card).then(function(result) {
    //         if (result.error) {
    //             // Inform the user if there was an error.
    //             var errorElement = document.getElementById('card-errors');
    //             errorElement.textContent = result.error.message;
    //         } else {

    //             stripeTokenHandler(result.token);
    //         }
    //     });
    // });

    // Submit the form with the token ID.
    function stripeTokenHandler(token, form) {
        // Insert the token ID into the form so it gets submitted to the server
        //var form = document.getElementById('payment-form');
        // var hiddenInput = document.createElement('input');
        // hiddenInput.setAttribute('type', 'hidden');
        // hiddenInput.setAttribute('name', 'stripeToken');
        // hiddenInput.setAttribute('value', token.id);
        // form.appendChild(hiddenInput);

        // Submit the form
        //form.submit();

        $(".loading_section").show();

        $('.stripe_process').css('cursor', 'wait');
        $('.stripe_process').attr('disabled', true);


        $.ajax({
            url: SITE + "root/payment/stripe",
            type: "POST",
            data: $(form).serialize() + '&stripeToken=' + token.id + '&service_uuid=' + service_uuid + '&uuid=' + uuid + '&u_number=' + u_number,
            dataType: "json",
            cache: false,
            success: function(response) {

                var res = response.results;

                $(".loading_section").hide();

                Swal.fire({
                    title: 'Payment success',
                    text: "Payment has been made successfully",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    showCancelButton: false,
                    timer: 4000
                }).then((result) => {
                    window.location.href = SITE + "marketplace/order-confirmation/" + res.order_number;
                });


                $('.stripe_process').css('cursor', 'pointer');
                $('.stripe_process').removeAttr('disabled');

            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {

                $(".loading_section").hide();

                Swal.fire({
                    icon: 'error',
                    title: 'Error Occured',
                    text: XMLHttpRequest.responseJSON.results.message,
                })

                $('.stripe_process').css('cursor', 'pointer');
                $('.stripe_process').removeAttr('disabled');
            }

        });



    };


    paypal.Button.render({
        env: 'production',
        locale: 'en_US',
        style: {
            shape: 'rect',
            color: 'black',
            size: "responsive",
            // layout: 'vertical',
            label: 'pay',
            //fundingicons: 'true'
        },
        ui: {
            hasError: !1
        },

        // Or 'production'
        // Set up the payment:
        // 1. Add a payment callback
        payment: function(data, actions) {
            // 2. Make a request to your server


            return actions.request.post(SITE + 'root/payment/paypal-instance', {
                    token: generate_token(32),
                    service_uuid: service_uuid,
                    uuid: uuid,
                    u_number: u_number,
                })

                .then(function(res) {
                    // 3. Return res.id from the response

                    console.log(res);
                    return res.results.paymentID;
                }).catch(function(err) {
                    reject("erroring", err);
                    $(".loading_section").hide();
                });

        },
        // Execute the payment:
        // 1. Add an onAuthorize callback
        onAuthorize: function(data, actions) {
            // 2. Make a request to your server
            return actions.request.post(SITE + 'root/payment/paypal-execute', {
                    token: generate_token(32),
                    service_uuid: service_uuid,
                    uuid: uuid,
                    u_number: u_number,
                    paymentID: data.paymentID,
                    payerID: data.payerID
                })
                .then(function(response) {

                    console.log('res', res);

                    var res = response.results;

                    if (res.message == 'Payment has been made successfully') {

                        $(".loading_section").hide();

                        Swal.fire({
                            title: 'Payment success',
                            text: "Payment has been made successfully",
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                            showCancelButton: false,
                            timer: 4000
                        }).then((result) => {
                            window.location.href = SITE + "marketplace/order-confirmation/" + res.order_number;
                        });


                        $('.stripe_process').css('cursor', 'pointer');
                        $('.stripe_process').removeAttr('disabled');

                    } else {

                        $(".loading_section").hide();

                        Swal.fire({
                            icon: 'error',
                            title: 'Error Occured',
                            text: XMLHttpRequest.responseJSON.results.message,
                        })

                        $('.stripe_process').css('cursor', 'pointer');
                        $('.stripe_process').removeAttr('disabled');

                    }

                    // 3. Show the buyer a confirmation message.

                    // if(res.message=='payment Successfull'){

                    //     window.location.replace(baseURL+'success');

                    // }else{

                    //     window.location.replace(baseURL+'error');
                    // }

                }).catch(function(err) {
                    reject("erroring", err);
                    $(".loading_section").hide();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error Occured',
                        text: XMLHttpRequest.responseJSON.results.message,
                    })

                    $('.stripe_process').css('cursor', 'pointer');
                    $('.stripe_process').removeAttr('disabled');
                });;
        }
    }, '#paypal-button-container');


    function generate_token(length) {
        //edit the token allowed characters
        var a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
        var b = [];
        for (var i = 0; i < length; i++) {
            var j = (Math.random() * (a.length - 1)).toFixed(0);
            b[i] = a[j];
        }
        return b.join("");
    }



    $(document).on("click", ".guest", function(event) {
        window.location.href = SITE + "marketplace/guest-checkout";
    });
</script>