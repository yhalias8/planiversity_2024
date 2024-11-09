let offersRequest = null;
let offersList = [];
let offerData = null;
let next_page = null;
let previous_page = null;
let off_id = null;
let payment_intent_id = null;

var datePickerOptions = {
    format: "yyyy-mm-dd",
    autoclose: true,
};

$(".datepicker_one").datepicker(datePickerOptions);

$("input[type=radio][name=action_option_value]").change(function() {
    if (this.value == "book") {
        $("#build_place").hide();
        $("#booking_place").delay(500).fadeIn();
    } else {
        $("#booking_place").hide();
        $("#build_place").delay(500).fadeIn();
    }
});

$("input[type=radio][name=trip_style]").change(function() {
    if (this.value == "one") {
        $("#return_flight_section").hide();
    } else {
        $("#return_flight_section").delay(500).fadeIn();
    }
});

$("#step_sort").on("change", function(e) {
    var optionSelected = $(this).val();
    $("#steps").val(optionSelected);
    $("#flight_initial_form").submit();

    // var trip_mode = $("input[name='trip_style']:checked").val();
    // var sorts = $("#sorts").val();
    // var dataSet = 'request_id=' + offersRequest + '&trip_mode=' + trip_mode + '&steps=' + optionSelected + '&sorts=' + sorts;
    // ajaxLoad(dataSet);
});

$("#list_sort").on("change", function(e) {
    var optionSelected = $(this).val();
    $("#sorts").val(optionSelected);

    var trip_mode = $("input[name='trip_style']:checked").val();
    var steps = $("#steps").val();
    var dataSet =
        "request_id=" +
        offersRequest +
        "&trip_mode=" +
        trip_mode +
        "&steps=" +
        steps +
        "&sorts=" +
        optionSelected;

    ajaxLoad(dataSet);
});

function timeFormat(dt) {
    return moment(dt).format("hh:mm a");
}

function timeDuration(dt) {
    var hour = moment(dt, "HH:mm").format("h");
    var minute = moment(dt, "HH:mm").format("mm");

    return hour + "h " + minute + "m";
}

function layover(value) {
    var message;
    switch (value) {
        case 1:
            message = "Direct";
            break;
        case 2:
            message = "1 Stop";
            break;
        case 3:
            message = "2 Stop";
            break;
        case 4:
            message = "3 Stop";
            break;
        default:
            message = "Direct";
    }

    return message;
}

function oneWayCalculation(slices) {
    console.log("slices", slices);

    var segment_count = slices[0].segments.length;
    var segment_index = segment_count - 1;

    var items = "";
    items +=
        `
    <div class="flight_heading">
                    <div class="flight_destination">
                        <h4>
                            <i class="fa fa-plane"></i> Flight to ` +
        slices[0].destination.name +
        `
                        </h4>

                        <div class="flight_fact">
                        ` +
        timeFormat(slices[0].segments[0].departing_at) +
        ` - ` +
        timeFormat(slices[0].segments[segment_index].arriving_at) +
        `
                        </div>

                        <div class="flight_fact">
                        ` +
        timeDuration(slices[0].duration) +
        ` (` +
        layover(segment_count) +
        `)
                        </div>
                    </div>
                </div>
    `;

    items += `<ul class="flight_spot">`;
    $.each(slices[0].segments, function(index, item) {
        items +=
            `
            <li>` +
            item.destination.name +
            `</li>
                    <p>` +
            timeDuration(item.duration) +
            ` flight</p>
                    <p>` +
            item.operating_carrier.name +
            ` - ` +
            item.operating_carrier_flight_number +
            `</p>
                    <p>` +
            item.passengers[0].cabin_class_marketing_name +
            `</p>
        `;
    });

    items += `</ul>`;

    $(".flight_timeline").html(items);
}

function roundTripCalculation(slices) {
    console.log("slices", slices);

    var segment_count = slices[0].segments.length;
    var segment_index = segment_count - 1;

    var items = "";
    items +=
        `
    <div class="flight_heading">
                    <div class="flight_destination">
                        <h4>
                            <i class="fa fa-plane"></i> Flight to ` +
        slices[0].destination.name +
        `
                        </h4>

                        <div class="flight_fact">
                        ` +
        timeFormat(slices[0].segments[0].departing_at) +
        ` - ` +
        timeFormat(slices[0].segments[segment_index].arriving_at) +
        `
                        </div>

                        <div class="flight_fact">
                        ` +
        timeDuration(slices[0].duration) +
        ` (` +
        layover(segment_count) +
        `)
                        </div>
                    </div>
                </div>
    `;

    items += `<ul class="flight_spot">`;
    $.each(slices[0].segments, function(index, item) {
        items +=
            `
            <li>` +
            item.destination.name +
            `</li>
                    <p>` +
            timeDuration(item.duration) +
            ` flight</p>
                    <p>` +
            item.operating_carrier.name +
            ` - ` +
            item.operating_carrier_flight_number +
            `</p>
                    <p>` +
            item.passengers[0].cabin_class_marketing_name +
            `</p>
        `;
    });

    items += `</ul>`;

    items +=
        `
    <div class="flight_heading">
                    <div class="flight_destination">
                        <h4>
                            <i class="fa fa-plane"></i> Flight to ` +
        slices[1].destination.name +
        `
                        </h4>

                        <div class="flight_fact">
                        ` +
        timeFormat(slices[1].segments[0].departing_at) +
        ` - ` +
        timeFormat(slices[1].segments[segment_index].arriving_at) +
        `
                        </div>

                        <div class="flight_fact">
                        ` +
        timeDuration(slices[1].duration) +
        ` (` +
        layover(segment_count) +
        `)
                        </div>
                    </div>
                </div>
    `;

    items += `<ul class="flight_spot">`;
    $.each(slices[1].segments, function(index, item) {
        items +=
            `
            <li>` +
            item.destination.name +
            `</li>
                    <p>` +
            timeDuration(item.duration) +
            ` flight</p>
                    <p>` +
            item.operating_carrier.name +
            ` - ` +
            item.operating_carrier_flight_number +
            `</p>
                    <p>` +
            item.passengers[0].cabin_class_marketing_name +
            `</p>
        `;
    });

    items += `</ul>`;

    $(".flight_timeline").html(items);
}

$(document).on("click", ".flight-item", function(event) {
    var offerID = $(this).data("offer-id");
    var trip_mode = $("input[name='trip_style']:checked").val();

    console.log("trip_mode", trip_mode);

    const matchValue = offersList.findIndex((item) => item.id == offerID);

    offerData = offersList[matchValue];

    console.log("offerData", offerData);

    $(".traveller_container").hide();
    $(".message_container").hide();
    $(".action_container").hide();
    $("#traveler_inputs").html("");

    $("#master_modal").modal("show");

    if (trip_mode == "one") {
        oneWayCalculation(offerData.slices);
    } else {
        roundTripCalculation(offerData.slices);
    }

    $(".payment_loading_screen").show();
    $(".payment_container").hide();

    var dataSet = "offer_id=" + offerData.id;

    $.ajax({
        url: SITE + "process/api/paymentSingleOfferProcessing.php",
        type: "GET",
        data: dataSet,
        dataType: "json",
        success: function(response) {
            console.log("response", response);

            $(".payment_loading_screen").hide();
            $(".payment_container").show();

            $("#amount_target").html("$" + response.data.total_amount);

            off_id = response.data.offer_id;
            payment_intent_id = response.data.payment_id;

            DuffelComponents.renderCardPaymentComponent("payment_target", {
                duffelPaymentIntentClientToken: response.data.client_token,
                successfulPaymentHandler: successfulPaymentHandler, // Show 'successful payment' page and confirm Duffel PaymentIntent
                errorPaymentHandler: errorPaymentHandlerFn, // Show error page
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var res = jqXHR.responseJSON;

            $(".payment_loading_screen").hide();
            $(".message_container").show();
            $(".response-message").html('<p class="warn">' + res.message + "</p>");
        },
    });
});

$("#order-form-complete").validate({
    rules: {
        "travels_title[]": {
            required: true,
        },
        "travels_gender[]": {
            required: true,
        },
        "travels_dob[]": {
            required: true,
        },
        "travels_name[]": {
            required: true,
            minlength: 2,
        },
        "travels_family_name[]": {
            required: true,
            minlength: 2,
        },
        "travels_email[]": {
            required: true,
            email: true,
        },
        "travels_number[]": {
            required: true,
            phoneUS: true,
        },
        "countryCode[]": {
            required: true,
        },
    },

    submitHandler: function(form) {
        $(".flight_booking").css("cursor", "wait");
        $(".flight_booking").attr("disabled", true);

        $.ajax({
            url: SITE + "process/api/booking_complete_process.php",
            type: "POST",
            data: $(form).serialize() +
                "&offersRequest=" +
                offersRequest +
                "&off_id=" +
                off_id +
                "&payment_intent_id=" +
                payment_intent_id +
                "&check=" +
                SITE +
                "&date=" +
                new Date(),
            dataType: "json",
            success: function(response) {
                console.log("order-response", response);
                console.log("order-response-id", response.id);

                $(".traveller_container").hide();
                $(".message_container").show();
                $(".response-message").html(
                    "<p>Your Booking reference is : " + response.id + "</p>"
                );

                $(".flight_booking").css("cursor", "pointer");
                $(".flight_booking").removeAttr("disabled");

                $("#results_count").html("0");
                $("#search_result").html("");
                $("#search_result_section").hide();
                $(".loading_screen").show();
                $(".result-actions").hide();
                $(".flight_load_more").hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var res = jqXHR.responseJSON;

                console.log("jqXHR", jqXHR);
                console.log("res", res);
                var error_items = "<p><ul>";

                if (jqXHR.responseJSON.errors) {
                    $.each(jqXHR.responseJSON.errors, function(prefix, val) {
                        console.log("prefix", prefix);
                        console.log("val", val);
                        //$(form).find('label.' + prefix + '_error').html(val[0]).show();
                        error_items += "<li>" + val.message + "</li>";
                    });

                    error_items += "</ul></p>";

                    $(".response-message").html(error_items);
                }

                $(".traveller_container").hide();
                $(".message_container").show();
                $(".action_container").show();

                $(".flight_booking").css("cursor", "pointer");
                $(".flight_booking").removeAttr("disabled");
            },
        });
    }, // Do not change code below
    errorPlacement: function(error, element) {
        //error.insertAfter(element.parent());
    },
});

$(".try_again").click(function() {
    $(".traveller_container").show();
    //$('.message_container').show();
    $(".action_container").hide();
});

function successfulPaymentHandler() {
    var dataSet =
        "intent_id=" + payment_intent_id + "&check=" + SITE + "&date=" + new Date();

    $.ajax({
        url: SITE + "process/api/payment_confirm_process.php",
        type: "POST",
        data: dataSet,
        dataType: "json",
        success: function(response) {
            console.log("response_confirm", response);

            swal({
                title: "Payment process successfully done",
                type: "success",
                timer: 2500,
                showConfirmButton: false,
                customClass: "swal-height",
            });

            $(".payment_container").hide();
            $(".traveller_container").show();
            travalers_input_populate(offerData.passengers);

            $(".datepicker_two").datepicker(datePickerOptions);

            //$('.payment_field').val('');

            //card.clear();

            //$("#payment-form").trigger("reset");

            // var getValue = response.substring(12);

            // var response_brought = response.indexOf('successfully');
            // var response = response.indexOf('failed');
            // if (response_brought != -1) {

            //     alert(getValue);

            // } else {

            // }

            // $('.stripe_process').css('cursor', 'pointer');
            // $('.stripe_process').removeAttr('disabled');
        },
    });
}

function errorPaymentHandlerFn() {
    swal({
        title: "Payment process failed",
        type: "warning",
        timer: 2500,
        showConfirmButton: false,
        customClass: "swal-height",
    });
}

function travalers_input_populate(TData) {
    var item_input = "";

    $.each(TData, function(index, item) {
        item_input +=
            `            <p>Traveler  ` +
            (index + 1) +
            ` : ` +
            item.type +
            `</p>
                                        <div class="row mb-1 pay-row">
                                            <div class="col-xl-4 col-12 padding_left">
                                                <div class="form_group ">
                                                    <label>Title</label>
                                                    <select class="form-control extra-option-complete" name="travels_title[]">
                                                        <option value="mr">Mr.</option>
                                                        <option value="ms">Ms.</option>
                                                        <option value="mrs">Mrs.</option>
                                                        <option value="miss">Miss</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-12">
                                                <div class="form_group">
                                                    <label>Gender</label>
                                                    <select class="form-control extra-option-complete" name="travels_gender[]">
                                                        <option value="m">Male</option>
                                                        <option value="f">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-12">
                                                <div class="form_group">
                                                    <label>DOB</label>
                                                    <input type="text" name="travels_dob[]" id="travels_dob_` +
            index +
            `" class="form-control datepicker_two extra-option-complete payment_field" placeholder="Date" require>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-1 pay-row">
                                            <div class="col-xl-6 col-12 padding_left">
                                                <div class="form_group">
                                                    <label>Given name</label>
                                                    <input type="text" name="travels_name[]" id="travels_name_` +
            index +
            `" class="form-control extra-option-complete payment_field" placeholder="Enter Name" require>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-12 padding_left">
                                                <div class="form_group">
                                                    <label>Family name</label>
                                                    <input type="text" name="travels_family_name[]" id="travels_family_name_` +
            index +
            `" class="form-control extra-option-complete payment_field" placeholder="Enter Family Name" require>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row mb-1 pay-row">
                                            <div class="col-xl-12 col-12 padding_left">
                                                <div class="form_group">
                                                    <label>Email</label>
                                                    <input type="email" name="travels_email[]" id="travels_email_` +
            index +
            `" class="form-control extra-option-complete payment_field" placeholder="Enter Email" require>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-12 padding_left">
                                                <div class="form_group">
                                                    <label>Country Code</label>
                                                                                                                                                                    
                                                <select name="countryCode[]" class="form-control extra-option-complete">                                                        
                                                <option data-countryCode="US" value="+1" Selected>USA (+1)</option>
                                                <option data-countryCode="GB" value="+44" >UK (+44)</option>
                                                <optgroup label="Other countries">
                                                    <option data-countryCode="DZ" value="+213">Algeria (+213)</option>
                                                    <option data-countryCode="AD" value="+376">Andorra (+376)</option>
                                                    <option data-countryCode="AO" value="+244">Angola (+244)</option>
                                                    <option data-countryCode="AI" value="+1264">Anguilla (+1264)</option>
                                                    <option data-countryCode="AG" value="+1268">Antigua &amp; Barbuda (+1268)</option>
                                                    <option data-countryCode="AR" value="+54">Argentina (+54)</option>
                                                    <option data-countryCode="AM" value="+374">Armenia (+374)</option>
                                                    <option data-countryCode="AW" value="+297">Aruba (+297)</option>
                                                    <option data-countryCode="AU" value="+61">Australia (+61)</option>
                                                    <option data-countryCode="AT" value="+43">Austria (+43)</option>
                                                    <option data-countryCode="AZ" value="+994">Azerbaijan (+994)</option>
                                                    <option data-countryCode="BS" value="+1242">Bahamas (+1242)</option>
                                                    <option data-countryCode="BH" value="+973">Bahrain (+973)</option>
                                                    <option data-countryCode="BD" value="+880">Bangladesh (+880)</option>
                                                    <option data-countryCode="BB" value="+1246">Barbados (+1246)</option>
                                                    <option data-countryCode="BY" value="+375">Belarus (+375)</option>
                                                    <option data-countryCode="BE" value="+32">Belgium (+32)</option>
                                                    <option data-countryCode="BZ" value="+501">Belize (+501)</option>
                                                    <option data-countryCode="BJ" value="+229">Benin (+229)</option>
                                                    <option data-countryCode="BM" value="+1441">Bermuda (+1441)</option>
                                                    <option data-countryCode="BT" value="+975">Bhutan (+975)</option>
                                                    <option data-countryCode="BO" value="+591">Bolivia (+591)</option>
                                                    <option data-countryCode="BA" value="+387">Bosnia Herzegovina (+387)</option>
                                                    <option data-countryCode="BW" value="+267">Botswana (+267)</option>
                                                    <option data-countryCode="BR" value="+55">Brazil (+55)</option>
                                                    <option data-countryCode="BN" value="+673">Brunei (+673)</option>
                                                    <option data-countryCode="BG" value="+359">Bulgaria (+359)</option>
                                                    <option data-countryCode="BF" value="+226">Burkina Faso (+226)</option>
                                                    <option data-countryCode="BI" value="+257">Burundi (+257)</option>
                                                    <option data-countryCode="KH" value="+855">Cambodia (+855)</option>
                                                    <option data-countryCode="CM" value="+237">Cameroon (+237)</option>
                                                    <option data-countryCode="CA" value="+1">Canada (+1)</option>
                                                    <option data-countryCode="CV" value="+238">Cape Verde Islands (+238)</option>
                                                    <option data-countryCode="KY" value="+1345">Cayman Islands (+1345)</option>
                                                    <option data-countryCode="CF" value="+236">Central African Republic (+236)</option>
                                                    <option data-countryCode="CL" value="+56">Chile (+56)</option>
                                                    <option data-countryCode="CN" value="+86">China (+86)</option>
                                                    <option data-countryCode="CO" value="+57">Colombia (+57)</option>
                                                    <option data-countryCode="KM" value="+269">Comoros (+269)</option>
                                                    <option data-countryCode="CG" value="+242">Congo (+242)</option>
                                                    <option data-countryCode="CK" value="+682">Cook Islands (+682)</option>
                                                    <option data-countryCode="CR" value="+506">Costa Rica (+506)</option>
                                                    <option data-countryCode="HR" value="+385">Croatia (+385)</option>
                                                    <option data-countryCode="CU" value="+53">Cuba (+53)</option>
                                                    <option data-countryCode="CY" value="+90392">Cyprus North (+90392)</option>
                                                    <option data-countryCode="CY" value="+357">Cyprus South (+357)</option>
                                                    <option data-countryCode="CZ" value="+42">Czech Republic (+42)</option>
                                                    <option data-countryCode="DK" value="+45">Denmark (+45)</option>
                                                    <option data-countryCode="DJ" value="+253">Djibouti (+253)</option>
                                                    <option data-countryCode="DM" value="+1809">Dominica (+1809)</option>
                                                    <option data-countryCode="DO" value="+1809">Dominican Republic (+1809)</option>
                                                    <option data-countryCode="EC" value="+593">Ecuador (+593)</option>
                                                    <option data-countryCode="EG" value="+20">Egypt (+20)</option>
                                                    <option data-countryCode="SV" value="+503">El Salvador (+503)</option>
                                                    <option data-countryCode="GQ" value="+240">Equatorial Guinea (+240)</option>
                                                    <option data-countryCode="ER" value="+291">Eritrea (+291)</option>
                                                    <option data-countryCode="EE" value="+372">Estonia (+372)</option>
                                                    <option data-countryCode="ET" value="+251">Ethiopia (+251)</option>
                                                    <option data-countryCode="FK" value="+500">Falkland Islands (+500)</option>
                                                    <option data-countryCode="FO" value="+298">Faroe Islands (+298)</option>
                                                    <option data-countryCode="FJ" value="+679">Fiji (+679)</option>
                                                    <option data-countryCode="FI" value="+358">Finland (+358)</option>
                                                    <option data-countryCode="FR" value="+33">France (+33)</option>
                                                    <option data-countryCode="GF" value="+594">French Guiana (+594)</option>
                                                    <option data-countryCode="PF" value="+689">French Polynesia (+689)</option>
                                                    <option data-countryCode="GA" value="+241">Gabon (+241)</option>
                                                    <option data-countryCode="GM" value="+220">Gambia (+220)</option>
                                                    <option data-countryCode="GE" value="+7880">Georgia (+7880)</option>
                                                    <option data-countryCode="DE" value="+49">Germany (+49)</option>
                                                    <option data-countryCode="GH" value="+233">Ghana (+233)</option>
                                                    <option data-countryCode="GI" value="+350">Gibraltar (+350)</option>
                                                    <option data-countryCode="GR" value="+30">Greece (+30)</option>
                                                    <option data-countryCode="GL" value="+299">Greenland (+299)</option>
                                                    <option data-countryCode="GD" value="+1473">Grenada (+1473)</option>
                                                    <option data-countryCode="GP" value="+590">Guadeloupe (+590)</option>
                                                    <option data-countryCode="GU" value="+671">Guam (+671)</option>
                                                    <option data-countryCode="GT" value="+502">Guatemala (+502)</option>
                                                    <option data-countryCode="GN" value="+224">Guinea (+224)</option>
                                                    <option data-countryCode="GW" value="+245">Guinea - Bissau (+245)</option>
                                                    <option data-countryCode="GY" value="+592">Guyana (+592)</option>
                                                    <option data-countryCode="HT" value="+509">Haiti (+509)</option>
                                                    <option data-countryCode="HN" value="+504">Honduras (+504)</option>
                                                    <option data-countryCode="HK" value="+852">Hong Kong (+852)</option>
                                                    <option data-countryCode="HU" value="+36">Hungary (+36)</option>
                                                    <option data-countryCode="IS" value="+354">Iceland (+354)</option>
                                                    <option data-countryCode="IN" value="+91">India (+91)</option>
                                                    <option data-countryCode="ID" value="+62">Indonesia (+62)</option>
                                                    <option data-countryCode="IR" value="+98">Iran (+98)</option>
                                                    <option data-countryCode="IQ" value="+964">Iraq (+964)</option>
                                                    <option data-countryCode="IE" value="+353">Ireland (+353)</option>
                                                    <option data-countryCode="IL" value="+972">Israel (+972)</option>
                                                    <option data-countryCode="IT" value="+39">Italy (+39)</option>
                                                    <option data-countryCode="JM" value="+1876">Jamaica (+1876)</option>
                                                    <option data-countryCode="JP" value="+81">Japan (+81)</option>
                                                    <option data-countryCode="JO" value="+962">Jordan (+962)</option>
                                                    <option data-countryCode="KZ" value="+7">Kazakhstan (+7)</option>
                                                    <option data-countryCode="KE" value="+254">Kenya (+254)</option>
                                                    <option data-countryCode="KI" value="+686">Kiribati (+686)</option>
                                                    <option data-countryCode="KP" value="+850">Korea North (+850)</option>
                                                    <option data-countryCode="KR" value="+82">Korea South (+82)</option>
                                                    <option data-countryCode="KW" value="+965">Kuwait (+965)</option>
                                                    <option data-countryCode="KG" value="+996">Kyrgyzstan (+996)</option>
                                                    <option data-countryCode="LA" value="+856">Laos (+856)</option>
                                                    <option data-countryCode="LV" value="+371">Latvia (+371)</option>
                                                    <option data-countryCode="LB" value="+961">Lebanon (+961)</option>
                                                    <option data-countryCode="LS" value="+266">Lesotho (+266)</option>
                                                    <option data-countryCode="LR" value="+231">Liberia (+231)</option>
                                                    <option data-countryCode="LY" value="+218">Libya (+218)</option>
                                                    <option data-countryCode="LI" value="+417">Liechtenstein (+417)</option>
                                                    <option data-countryCode="LT" value="+370">Lithuania (+370)</option>
                                                    <option data-countryCode="LU" value="+352">Luxembourg (+352)</option>
                                                    <option data-countryCode="MO" value="+853">Macao (+853)</option>
                                                    <option data-countryCode="MK" value="+389">Macedonia (+389)</option>
                                                    <option data-countryCode="MG" value="+261">Madagascar (+261)</option>
                                                    <option data-countryCode="MW" value="+265">Malawi (+265)</option>
                                                    <option data-countryCode="MY" value="+60">Malaysia (+60)</option>
                                                    <option data-countryCode="MV" value="+960">Maldives (+960)</option>
                                                    <option data-countryCode="ML" value="+223">Mali (+223)</option>
                                                    <option data-countryCode="MT" value="+356">Malta (+356)</option>
                                                    <option data-countryCode="MH" value="+692">Marshall Islands (+692)</option>
                                                    <option data-countryCode="MQ" value="+596">Martinique (+596)</option>
                                                    <option data-countryCode="MR" value="+222">Mauritania (+222)</option>
                                                    <option data-countryCode="YT" value="+269">Mayotte (+269)</option>
                                                    <option data-countryCode="MX" value="+52">Mexico (+52)</option>
                                                    <option data-countryCode="FM" value="+691">Micronesia (+691)</option>
                                                    <option data-countryCode="MD" value="+373">Moldova (+373)</option>
                                                    <option data-countryCode="MC" value="+377">Monaco (+377)</option>
                                                    <option data-countryCode="MN" value="+976">Mongolia (+976)</option>
                                                    <option data-countryCode="MS" value="+1664">Montserrat (+1664)</option>
                                                    <option data-countryCode="MA" value="+212">Morocco (+212)</option>
                                                    <option data-countryCode="MZ" value="+258">Mozambique (+258)</option>
                                                    <option data-countryCode="MN" value="+95">Myanmar (+95)</option>
                                                    <option data-countryCode="NA" value="+264">Namibia (+264)</option>
                                                    <option data-countryCode="NR" value="+674">Nauru (+674)</option>
                                                    <option data-countryCode="NP" value="+977">Nepal (+977)</option>
                                                    <option data-countryCode="NL" value="+31">Netherlands (+31)</option>
                                                    <option data-countryCode="NC" value="+687">New Caledonia (+687)</option>
                                                    <option data-countryCode="NZ" value="+64">New Zealand (+64)</option>
                                                    <option data-countryCode="NI" value="+505">Nicaragua (+505)</option>
                                                    <option data-countryCode="NE" value="+227">Niger (+227)</option>
                                                    <option data-countryCode="NG" value="+234">Nigeria (+234)</option>
                                                    <option data-countryCode="NU" value="+683">Niue (+683)</option>
                                                    <option data-countryCode="NF" value="+672">Norfolk Islands (+672)</option>
                                                    <option data-countryCode="NP" value="+670">Northern Marianas (+670)</option>
                                                    <option data-countryCode="NO" value="+47">Norway (+47)</option>
                                                    <option data-countryCode="OM" value="+968">Oman (+968)</option>
                                                    <option data-countryCode="PW" value="+680">Palau (+680)</option>
                                                    <option data-countryCode="PA" value="+507">Panama (+507)</option>
                                                    <option data-countryCode="PG" value="+675">Papua New Guinea (+675)</option>
                                                    <option data-countryCode="PY" value="+595">Paraguay (+595)</option>
                                                    <option data-countryCode="PE" value="+51">Peru (+51)</option>
                                                    <option data-countryCode="PH" value="+63">Philippines (+63)</option>
                                                    <option data-countryCode="PL" value="+48">Poland (+48)</option>
                                                    <option data-countryCode="PT" value="+351">Portugal (+351)</option>
                                                    <option data-countryCode="PR" value="+1787">Puerto Rico (+1787)</option>
                                                    <option data-countryCode="QA" value="+974">Qatar (+974)</option>
                                                    <option data-countryCode="RE" value="+262">Reunion (+262)</option>
                                                    <option data-countryCode="RO" value="+40">Romania (+40)</option>
                                                    <option data-countryCode="RU" value="+7">Russia (+7)</option>
                                                    <option data-countryCode="RW" value="+250">Rwanda (+250)</option>
                                                    <option data-countryCode="SM" value="+378">San Marino (+378)</option>
                                                    <option data-countryCode="ST" value="+239">Sao Tome &amp; Principe (+239)</option>
                                                    <option data-countryCode="SA" value="+966">Saudi Arabia (+966)</option>
                                                    <option data-countryCode="SN" value="+221">Senegal (+221)</option>
                                                    <option data-countryCode="CS" value="+381">Serbia (+381)</option>
                                                    <option data-countryCode="SC" value="+248">Seychelles (+248)</option>
                                                    <option data-countryCode="SL" value="+232">Sierra Leone (+232)</option>
                                                    <option data-countryCode="SG" value="+65">Singapore (+65)</option>
                                                    <option data-countryCode="SK" value="+421">Slovak Republic (+421)</option>
                                                    <option data-countryCode="SI" value="+386">Slovenia (+386)</option>
                                                    <option data-countryCode="SB" value="+677">Solomon Islands (+677)</option>
                                                    <option data-countryCode="SO" value="+252">Somalia (+252)</option>
                                                    <option data-countryCode="ZA" value="+27">South Africa (+27)</option>
                                                    <option data-countryCode="ES" value="+34">Spain (+34)</option>
                                                    <option data-countryCode="LK" value="+94">Sri Lanka (+94)</option>
                                                    <option data-countryCode="SH" value="+290">St. Helena (+290)</option>
                                                    <option data-countryCode="KN" value="+1869">St. Kitts (+1869)</option>
                                                    <option data-countryCode="SC" value="+1758">St. Lucia (+1758)</option>
                                                    <option data-countryCode="SD" value="+249">Sudan (+249)</option>
                                                    <option data-countryCode="SR" value="+597">Suriname (+597)</option>
                                                    <option data-countryCode="SZ" value="+268">Swaziland (+268)</option>
                                                    <option data-countryCode="SE" value="+46">Sweden (+46)</option>
                                                    <option data-countryCode="CH" value="+41">Switzerland (+41)</option>
                                                    <option data-countryCode="SI" value="+963">Syria (+963)</option>
                                                    <option data-countryCode="TW" value="+886">Taiwan (+886)</option>
                                                    <option data-countryCode="TJ" value="+7">Tajikstan (+7)</option>
                                                    <option data-countryCode="TH" value="+66">Thailand (+66)</option>
                                                    <option data-countryCode="TG" value="+228">Togo (+228)</option>
                                                    <option data-countryCode="TO" value="+676">Tonga (+676)</option>
                                                    <option data-countryCode="TT" value="+1868">Trinidad &amp; Tobago (+1868)</option>
                                                    <option data-countryCode="TN" value="+216">Tunisia (+216)</option>
                                                    <option data-countryCode="TR" value="+90">Turkey (+90)</option>
                                                    <option data-countryCode="TM" value="+7">Turkmenistan (+7)</option>
                                                    <option data-countryCode="TM" value="+993">Turkmenistan (+993)</option>
                                                    <option data-countryCode="TC" value="+1649">Turks &amp; Caicos Islands (+1649)</option>
                                                    <option data-countryCode="TV" value="+688">Tuvalu (+688)</option>
                                                    <option data-countryCode="UG" value="+256">Uganda (+256)</option>        
                                                    <option data-countryCode="UA" value="+380">Ukraine (+380)</option>
                                                    <option data-countryCode="AE" value="+971">United Arab Emirates (+971)</option>
                                                    <option data-countryCode="UY" value="+598">Uruguay (+598)</option>        
                                                    <option data-countryCode="UZ" value="+7">Uzbekistan (+7)</option>
                                                    <option data-countryCode="VU" value="+678">Vanuatu (+678)</option>
                                                    <option data-countryCode="VA" value="+379">Vatican City (+379)</option>
                                                    <option data-countryCode="VE" value="+58">Venezuela (+58)</option>
                                                    <option data-countryCode="VN" value="+84">Vietnam (+84)</option>
                                                    <option data-countryCode="VG" value="+84">Virgin Islands - British (+1284)</option>
                                                    <option data-countryCode="VI" value="+84">Virgin Islands - US (+1340)</option>
                                                    <option data-countryCode="WF" value="+681">Wallis &amp; Futuna (+681)</option>
                                                    <option data-countryCode="YE" value="+969">Yemen (North)(+969)</option>
                                                    <option data-countryCode="YE" value="+967">Yemen (South)(+967)</option>
                                                    <option data-countryCode="ZM" value="+260">Zambia (+260)</option>
                                                    <option data-countryCode="ZW" value="+263">Zimbabwe (+263)</option>
                                                </optgroup>
                                            </select>
                                             </div>
                                            </div>                                                    

                                            <div class="col-xl-6 col-12 padding_left">
                                                <div class="form_group">
                                                    <label>Phone number</label>
                                                    <input type="text" name="travels_number[]" id="travels_number_` +
            index +
            `" class="form-control extra-option-complete payment_field" placeholder="Enter Number" require>
                                                </div>
                                            </div>
                                            <input type="hidden" name="travels_type[]" value="` +
            item.type +
            `" readonly>
                                            <input type="hidden" name="travels_id[]" value="` +
            item.id +
            `" readonly>
                                        </div>
    
    `;
    });

    $("#traveler_inputs").html(item_input);
}

let pas = [{
        type: "adult",
        loyalty_programme_accounts: [],
        id: "pas_0000ALDZniJTjcpjOqFzMo",
        given_name: null,
        family_name: null,
        age: null,
    },
    {
        type: "child",
        loyalty_programme_accounts: [],
        id: "pas_0000ALDZniJTjcpjOqFzMp",
        given_name: null,
        family_name: null,
        age: null,
    },
    {
        type: "infant_without_seat",
        loyalty_programme_accounts: [],
        id: "pas_0000ALDZniJTjcpjOqFzMq",
        given_name: null,
        family_name: null,
        age: null,
    },
];

$("#master_modal_click").on("click", function(e) {
    $("#master_modal").modal("hide");
});

//$('.traveller_container').show();
//travalers_input_populate(pas);

const SITE_API = "https://www.planiversity.com/staging/flow/";
const token = "duffel_test_ZgNwOQ6vA1oMHPy7r1UY5j5VI4xItZuwRKxOcpcsjMj";

function airportDataFetch() {
    //$('#update_note').modal('show');

    var dataSet = "id=" + "id";

    $.ajax({
        url: SITE + "process/api/airportData.php",
        type: "GET",
        //data: dataSet,
        dataType: "json",
        success: function(response) {
            dataRendaring(response.data);
        },
    });
}

function dataRendaring(data) {
    var items = "";
    $.each(data, function(index, item) {
        items +=
            "<div class='col-sm-4'> <div class='airport-result-wrap' id='airport_" +
            item.id +
            "'> <label class='control control--radio'> " +
            item.name +
            "  <input type='radio' name='airport_value'/><div class='control__indicator'></div></label> </div></div>";
    });

    $("#airport_list_place").html(items);
}

$(".input-button").click(function() {
    $("#airportsListModal").modal("show");
    airportDataFetch();
});

function formatRepo(repo) {
    if (repo.loading) return repo.name;
    var check_icon = null;
    if (repo.type == "city") {
        check_icon = "fa-map-marker";
    } else {
        check_icon = "fa-plane";
    }

    var markup =
        '<div class="markup-location"><div class="m-icon"><i class="fa ' +
        check_icon +
        '"></i></div><div class="m-content"><p>' +
        repo.name +
        "<span>" +
        repo.country_name +
        "</span></p></div></div>";

    return markup;
}

function formatRepoSelectionFrom(repo) {
    console.log("selected value", repo);
    $("#from_location_code").val(repo.iata_code);
    return repo.name;
}

function formatRepoSelectionTo(repo) {
    console.log("selected value", repo);
    $("#to_location_code").val(repo.iata_code);
    return repo.name;
}

$("#from_location").select2({
    ajax: {
        url: SITE + "process/api/airportDataProcessing.php",
        type: "POST",
        dataType: "json",
        delay: 250,
        data: function(params) {
            var query = {
                query: params.term,
                //type: 'public'
            };
            // Query parameters will be ?search=[term]&type=public
            return query;
        },
        processResults: function(data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;

            return {
                results: data.data,
                pagination: {
                    more: params.page * 30 < data.data.total_count,
                },
            };
        },
        cache: true,
    },
    escapeMarkup: function(markup) {
        return markup;
    },
    placeholder: "Search for a repository",
    minimumInputLength: 2,
    templateResult: formatRepo,
    templateSelection: formatRepoSelectionFrom,
    allowClear: true,
    width: "100%",
    //dropdownParent: $("#schedule-modal"),
    dropdownPosition: "below",
});

$("#to_location").select2({
    ajax: {
        url: SITE + "process/api/airportDataProcessing.php",
        type: "POST",
        dataType: "json",
        delay: 250,
        data: function(params) {
            var query = {
                query: params.term,
                //type: 'public'
            };
            // Query parameters will be ?search=[term]&type=public
            return query;
        },
        processResults: function(data, params) {
            // parse the results into the format expected by Select2
            // since we are using custom formatting functions we do not need to
            // alter the remote JSON data, except to indicate that infinite
            // scrolling can be used
            params.page = params.page || 1;

            return {
                results: data.data,
                pagination: {
                    more: params.page * 30 < data.data.total_count,
                },
            };
        },
        cache: true,
    },
    escapeMarkup: function(markup) {
        return markup;
    },
    placeholder: "Search for a location",
    minimumInputLength: 2,
    templateResult: formatRepo,
    templateSelection: formatRepoSelectionTo,
    allowClear: true,
    width: "100%",
    //dropdownParent: $("#schedule-modal"),
    dropdownPosition: "below",
});

// $('#flight_search').click(function() {

//     $.ajax({
//         url: SITE + "process/api/offerRequestProcessing.php",
//         type: "POST",
//         //data: dataSet,
//         dataType: 'json',
//         success: function(response) {

//             console.log(response);
//             //dataRendaring(response.data);
//         }

//     });

// });

$.validator.addMethod(
    "maxDate",
    function(value, element) {
        var curDate = new Date();
        var inputDate = new Date(value);
        if (inputDate < curDate) return true;
        return false;
    },
    "Invalid Date!"
); // error message

$("#flight_initial_form").validate({
    ignore: ":hidden:not(.validy)",
    rules: {
        from_location_code: {
            required: true,
        },
        to_location_code: {
            required: true,
        },
        departure_flight_date: {
            required: true,
            date: true,
            //maxDate: true
        },
        return_flight_date: {
            required: true,
            date: true,
            //maxDate: true
        },
    },
    messages: {
        from_location_code: {
            required: "Please select origin airport",
        },
        to_location_code: {
            required: "Please select destination airport",
        },
        departure_flight_date: {
            required: "Please select departure date",
        },
        return_flight_date: {
            required: "Please select return date",
        },
    },

    submitHandler: function(form) {
        // $('.flight_search').css('cursor', 'wait');
        // $('.flight_search').attr('disabled', true);

        $("#results_count").html("0");
        $("#search_result").html("");
        $("#search_result_section").delay(200).fadeIn();
        $(".loading_screen").show();
        $(".result-actions").hide();
        $(".flight_load_more").hide();
        $(".target_action").attr("disabled", false);

        $.ajax({
            url: SITE + "process/api/offerRequestProcessing.php",
            type: "POST",
            data: $(form).serialize(),
            dataType: "json",
            success: function(response) {
                console.log(response);
                $(".loading_screen").hide();
                $("#search_result").html(response.data.offerList);
                $("#results_count").html(response.data.results);
                offersList = [...response.data.offers];
                offersRequest = response.data.offer_request_id;
                next_page = response.data.next_page;
                previous_page = response.data.previous_page;
                $("#next_button").val(response.data.next_page);
                $("#previous_button").val(response.data.previous_page);

                if (response.data.results > 0) {
                    $(".result-actions").show();
                }

                if (next_page || previous_page) {
                    $(".flight_load_more").show();
                }

                if (next_page == null) {
                    $("#next_button").attr("disabled", true);
                }

                if (previous_page == null) {
                    $("#previous_button").attr("disabled", true);
                }

                console.log("offersList", offersList);
                console.log("next_page", next_page);
                console.log("previous_page", previous_page);

                // $('.flight_search').css('cursor', 'pointer');
                // $('.flight_search').removeAttr('disabled');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // toastr.error('A system error has been encountered. Please try again');
                // $('.update_submit_button').css('cursor', 'pointer');
                // $('.update_submit_button').removeAttr('disabled');
                // $('#update_note').modal('hide');
            },
        });
    }, // Do not change code below
    errorPlacement: function(error, element) {
        error.insertAfter(element.parent());
    },
});

$(document).on("click", ".target_action", function(event) {
    var type = $(this).data("type");
    var targetValue = $(this).val();
    var steps = $("#steps").val();
    var sorts = $("#sorts").val();
    var trip_mode = $("input[name='trip_style']:checked").val();
    var dataSet =
        "request_id=" +
        offersRequest +
        "&trip_mode=" +
        trip_mode +
        "&type=" +
        type +
        "&typeValue=" +
        targetValue +
        "&steps=" +
        steps +
        "&sorts=" +
        sorts;
    ajaxLoad(dataSet);
});

function ajaxLoad(dataSet) {
    $("#results_count").html("0");
    $("#search_result").html("");
    $("#search_result_section").delay(200).fadeIn();
    $(".loading_screen").show();
    $(".result-actions").hide();
    $(".flight_load_more").hide();
    $(".target_action").attr("disabled", false);

    $.ajax({
        url: SITE + "process/api/offerListProcessing.php",
        type: "GET",
        data: dataSet,
        dataType: "json",
        success: function(response) {
            $(".loading_screen").hide();
            $("#search_result").html(response.data.offerList);
            $("#results_count").html(response.data.results);
            offersList = [...response.data.offers];
            offersRequest = response.data.offer_request_id;
            next_page = response.data.next_page;
            previous_page = response.data.previous_page;
            $("#next_button").val(response.data.next_page);
            $("#previous_button").val(response.data.previous_page);

            if (response.data.results > 0) {
                $(".result-actions").show();
            }

            if (next_page || previous_page) {
                $(".flight_load_more").show();
            }

            if (next_page == null) {
                $("#next_button").attr("disabled", true);
            }

            if (previous_page == null) {
                $("#previous_button").attr("disabled", true);
            }
        },
    });
}

var body = $("body,html");
var selectSpecial = $("#js-select-special");
var info = selectSpecial.find("#info");
var dropdownSelect = selectSpecial.parent().find(".dropdown-select");
var listRoom = dropdownSelect.find(".list-room");
var listChild = dropdownSelect.find(".child-list");
var listInfant = dropdownSelect.find(".infant-list");
var btnAddRoom = $("#btn-add-room");
var totalRoom = 0;
var totalInfant = 0;

selectSpecial.on("click", function(e) {
    e.stopPropagation();
    $(this).toggleClass("open");
    dropdownSelect.toggleClass("show");
});

dropdownSelect.on("click", function(e) {
    e.stopPropagation();
});

body.on("click", function() {
    selectSpecial.removeClass("open");
    dropdownSelect.removeClass("show");
});

listRoom.on("click", ".a__plus", function() {
    var that = $(this);
    var qtyContainer = that.parent();
    var qtyInput = qtyContainer.find("input[type=number]");
    var oldValue = parseInt(qtyInput.val());
    var newVal = oldValue + 1;
    qtyInput.val(newVal);

    updateRoom();
});

listRoom.on("click", ".a__minus", function() {
    var that = $(this);
    var qtyContainer = that.parent();
    var qtyInput = qtyContainer.find("input[type=number]");
    var min = qtyInput.attr("min");

    var oldValue = parseInt(qtyInput.val());
    if (oldValue <= min) {
        var newVal = oldValue;
    } else {
        var newVal = oldValue - 1;
    }
    qtyInput.val(newVal);

    updateRoom();
});

listRoom.on("click", ".c__plus", function() {
    var that = $(this);
    var qtyContainer = that.parent();
    var qtyInput = qtyContainer.find("input[type=number]");
    var oldValue = parseInt(qtyInput.val());
    var newVal = oldValue + 1;
    qtyInput.val(newVal);
    add_children();
    updateRoom();
});

listRoom.on("click", ".c__minus", function() {
    var that = $(this);
    var qtyContainer = that.parent();
    var qtyInput = qtyContainer.find("input[type=number]");
    var min = qtyInput.attr("min");

    var oldValue = parseInt(qtyInput.val());
    if (oldValue <= min) {
        var newVal = oldValue;
    } else {
        var newVal = oldValue - 1;
    }
    qtyInput.val(newVal);
    $(".list-room .children-list:last").remove();
    totalRoom--;
    updateRoom();
});

function add_children() {
    totalRoom++;

    listChild.append(
        '<li class="list-room__item children-list">' +
        '                                        <ul class="list-person children-person">' +
        '                                            <li class="list-person__item children-item">' +
        '                                                <span class="name">' +
        "                                                    Age of child " +
        totalRoom +
        "" +
        "                                                </span>" +
        '                                                <div class="child-age-div">' +
        '                                                 <select class="child-age" name="children[]"><option value="2" selected>2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option></select>' +
        "                                                </div>" +
        "                                            </li>" +
        "                                        </ul>"
    );
}

listRoom.on("click", ".i__plus", function() {
    var that = $(this);
    var qtyContainer = that.parent();
    var qtyInput = qtyContainer.find("input[type=number]");
    var oldValue = parseInt(qtyInput.val());
    var newVal = oldValue + 1;
    qtyInput.val(newVal);
    add_infant();

    updateRoom();
});

listRoom.on("click", ".i__minus", function() {
    var that = $(this);
    var qtyContainer = that.parent();
    var qtyInput = qtyContainer.find("input[type=number]");
    var min = qtyInput.attr("min");

    var oldValue = parseInt(qtyInput.val());
    if (oldValue <= min) {
        var newVal = oldValue;
    } else {
        var newVal = oldValue - 1;
    }
    qtyInput.val(newVal);
    $(".list-room .infant-list:last").remove();
    totalInfant--;
    updateRoom();
});

function add_infant() {
    totalInfant++;

    listInfant.append(
        '<li class="list-room__item infant-list">' +
        '                                        <ul class="list-person children-person">' +
        '                                            <li class="list-person__item children-item">' +
        '                                                <span class="name">' +
        "                                                    Age of infant " +
        totalInfant +
        "" +
        "                                                </span>" +
        '                                                <div class="child-age-div">' +
        '                                                 <select class="child-age" name="infant[]"><option value="1" selected>1</option></select>' +
        "                                                </div>" +
        "                                            </li>" +
        "                                        </ul>"
    );
}

function countAdult() {
    var listRoomItem = listRoom.find(".list-room__item");
    var totalAdults = 0;

    var numberAdults = parseInt(listRoomItem.find(".quantity1 > input").val());
    totalAdults = totalAdults + numberAdults;

    return totalAdults;
}

function countChildren() {
    var listRoomItem = listRoom.find(".list-room__item");
    var totalChildren = 0;

    var numberChildren = parseInt(listRoomItem.find(".quantity2 > input").val());

    totalChildren = totalChildren + numberChildren;

    return totalChildren;
}

function countInfant() {
    var listRoomItem = listRoom.find(".list-room__item");
    var totalNoInfant = 0;

    var numberInfant = parseInt(listRoomItem.find(".quantity3 > input").val());

    totalNoInfant = totalNoInfant + numberInfant;

    return totalNoInfant;
}

function updateRoom() {
    var totalAd = parseInt(countAdult());
    var totalChi = parseInt(countChildren());
    var totalInf = parseInt(countInfant());

    console.log("totalAd", totalAd);
    console.log("totalChi", totalChi);

    var adults = "Adult, ";
    var rooms = "Room";

    if (totalAd > 1) {
        adults = "Adults, ";
    }

    if (totalRoom > 1) {
        rooms = "Rooms";
    }

    var infoText = Number(totalAd + totalChi + totalInf);

    var infoText = infoText + " Person ";

    info.val(infoText);
}