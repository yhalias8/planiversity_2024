<?php include_once("include_script.php"); ?>

<script>
    $(function() {
        localStorageValueInitialized();
        localStorageWishValueInitialized();
        //initialServiceLoad();
    });



    // Create a Stripe client.
    var stripe = Stripe('pk_test_huytZ9hW6O47goPy7CYAuXmE00D0oxtS3Y');

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

    // Handle form submission.
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();


        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {

                stripeTokenHandler(result.token);
            }
        });
    });

    // Submit the form with the token ID.
    function stripeTokenHandler(token) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        // var hiddenInput = document.createElement('input');
        // hiddenInput.setAttribute('type', 'hidden');
        // hiddenInput.setAttribute('name', 'stripeToken');
        // hiddenInput.setAttribute('value', token.id);
        // form.appendChild(hiddenInput);

        // Submit the form
        //form.submit();

        $('.stripe_process').css('cursor', 'wait');
        $('.stripe_process').attr('disabled', true);

        $.ajax({
            url: SITE + 'PaymentCheck',
            type: "POST",
            data: $(form).serialize() + '&stripeToken=' + token.id,
            success: function(response) {

                var getValue = response.substring(12);

                var response_brought = response.indexOf('successfully');
                var response = response.indexOf('failed');
                if (response_brought != -1) {

                    alert(getValue);

                } else {


                }


                $('.stripe_process').css('cursor', 'pointer');
                $('.stripe_process').removeAttr('disabled');

            }

        });



    };


    paypal.Button.render({
        //env: 'sandbox',
        locale: 'en_US',
        style: {
            shape: 'rect',
            color: 'black',
            size: "responsive",
            // layout: 'vertical',
            label: 'pay',
            // fundingicons: 'true'
        },
        ui: {
            hasError: !1
        },

        // Or 'production'
        // Set up the payment:
        // 1. Add a payment callback
        payment: function(data, actions) {
            // 2. Make a request to your server
            return actions.request.post(baseURL + 'api/create-payment/', {
                    csrf_secure_name: token_key,
                    hash_key: hash_key,
                })

                .then(function(res) {
                    // 3. Return res.id from the response
                    return res.id;
                });

        },
        // Execute the payment:
        // 1. Add an onAuthorize callback
        onAuthorize: function(data, actions) {
            // 2. Make a request to your server
            return actions.request.post(baseURL + 'api/execute-payment/', {
                    csrf_secure_name: token_key,
                    hash_key: hash_key,
                    paymentID: data.paymentID,
                    payerID: data.payerID
                })
                .then(function(res) {
                    // 3. Show the buyer a confirmation message.

                    // if(res.message=='payment Successfull'){

                    //     window.location.replace(baseURL+'success');

                    // }else{

                    //     window.location.replace(baseURL+'error');
                    // }

                });
        }
    }, '#paypal-button-container');




    $(document).on("click", ".guest", function(event) {
        window.location.href = SITE + "marketplace/guest-checkout";
    });


    function initialServiceLoad() {

        //var dataSet = 'service_uuid=' + uuid;
        //orderItemProcess(dataSet);
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