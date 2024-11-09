<head>
    <style>
    h1 {
        font-size: 24px;
        color: #1f74b7;
    }

    h2 {
        font-size: 20px;
        color: #1f74b7;
    }

    h4 {
        font-size: 12px;
        color: #67758d;
        text-transform: uppercase;
    }

    .w-35 {
        width: 35%;
    }


    .itinerary_tab {
        width: 100%;
        height: 50%;
        margin: 0px auto;
    }


    .regular-checkbox {
        display: none;
    }

    .regular-checkbox+label {
        background-color: #fafafa;
        border: 1px solid #cacece;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05);
        padding: 9px;
        border-radius: 3px;
        display: inline-block;
        position: relative;
    }

    .regular-checkbox+label:active,
    .regular-checkbox:checked+label:active {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px 1px 3px rgba(0, 0, 0, 0.1);
    }

    .regular-checkbox:checked+label {
        background-color: #F1F5FD;
        border: 1px solid #C8CCD5;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05), inset 0px -15px 10px -12px rgba(0, 0, 0, 0.05), inset 15px 10px -12px rgba(255, 255, 255, 0.1);
        color: #99a1a7;
    }

    .regular-checkbox:checked+label:after {
        content: '\2714';
        font-size: 14px;
        position: absolute;
        top: 0px;
        left: 3px;
        color: #058BEF;
    }


    .big-checkbox+label {
        padding: 18px;
    }

    .big-checkbox:checked+label:after {
        font-size: 28px;
        left: 6px;
    }

    .itinerary_content {
        margin-top: 30px;
        min-height: 30px;
    }

    .itinerary_section {
        text-align: center;
        color: #68768D;
    }

    .itinerary_section h3 {
        font-size: 26px;
    }

    .itinerary_section h3 span {
        display: block;
        line-height: 42px;
    }

    .itinerary_section p {
        margin: 0;
    }


.options {
    margin-top: 20px;
}

.option-padding-60 {
    padding: 0 60px;
}

.option-padding-160 {
    padding: 0 160px;
}

.option-padding-5 {
    padding: 0 5px;
}

.option-control {
    display: inline-flex;
    margin-right: 20px;
}

.option-control p {
    display: block;
    position: relative;
    left: 10px;
    top: 5px;
    font-size: 16px;
    font-weight: 400;
}
    #document-modal1 {
        z-index: 9999;
        position: fixed;
        top: 10px !important;
        background-color: transparent !important;
    }

    .city-image {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 561px;
        z-index: 0
    }

    .modal-content.white-content {
        background: white !important;
        border-radius: 8px;
        min-height: 400px
    }

    .back-button {
        color: black;
    }

    #second-redirect-tab{
        display: none;
    }
    </style>


</head>
<div data-backdrop="false" id="document-modal1" class="modal fade bs-example-modal-lg custom_prefix_modal" tabindex="-1"
    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-custom-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
        <div class="modal-content white-content">
            <div class="modal-body connect-bg-ground p-0 position-relative d-flex align-items-center" id="document-modal-body">
                <div class="container p-0 position-relative" style="z-index: 1;">
                    <div class="itinerary_tab" id="first-redirect-tab">

                        <div class="itinerary_section">
                            <h3>Do you want to add special instructions?</h3>
                            <p>Select a option</p>
                            <div class="itinerary_content">

                                <div class="row">
                                    <div class="col-lg-12 col-xl-12">

                                        <div class="options">
                                            <div class="option-control pr-5" onclick="redirect('<?= SITE; ?>trip/plan-notes/')">
                                                <input
                                                
                                                  type="checkbox" id="first_opt" name="hotel_option"
                                                    value="yes" class="regular-checkbox big-checkbox" /><label
                                                    for="hotel_option_one"></label>
                                                <p>Yes</p>
                                            </div>

                                            <div class="option-control" onclick="hideFirst()">
                                                <input type="checkbox" id="hotel_option_two" name="hotel_option"
                                                    value="no" class="regular-checkbox big-checkbox" /><label
                                                    for="hotel_option_two"></label>
                                                <p>No</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="itinerary_tab" id="second-redirect-tab">

                        <div class="itinerary_section">
                            <h3>Do you want to attach a document?</h3>
                            <p>Select a option</p>
                            <div class="itinerary_content">

                                <div class="row">
                                    <div class="col-lg-12 col-xl-12">

                                        <div class="options">
                                            <div class="option-control pr-5" onclick="redirect('<?= SITE; ?>trip/travel-documents/')">
                                                <input  type="checkbox" id="second_opt" name="hotel_option"
                                                
                                                    value="yes" class="regular-checkbox big-checkbox" /><label
                                                    for="hotel_option_one"></label>
                                                <p>Yes</p>
                                            </div>

                                            <div class="option-control" onclick="hideRedirectModal()">
                                                <input type="checkbox" id="hotel_option_two" name="hotel_option"
                                                    value="no" class="regular-checkbox big-checkbox" /><label
                                                    for="hotel_option_two"></label>
                                                <p>No</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>

function hideFirst(){
    document.getElementById('first-redirect-tab').style.display = 'none'
    document.getElementById('second-redirect-tab').style.display = 'initial'
}

function hideRedirectModal(){
$('#document-modal1').modal('hide');
}

function redirect(path){
    hideRedirectModal()
    location.href = path + '<?php echo $id_trip ?>';
}

$(window).on('load', function() {
    $('#document-modal1').modal('show');

});
$(".close").click(function() {
    if ($("#document-modal1").hasClass('modaltrans')) {
        $("#document-modal1").removeClass('modaltrans');
        $("#document-modal-body").removeClass('modaltrans-body');
        $("#myLargeModalLabel").css({
            fontSize: 21
        });
        $(this).html("-");
    } else {
        $("#document-modal1").addClass('modaltrans');
        $("#document-modal-body").addClass('modaltrans-body');
        $("#myLargeModalLabel").css({
            fontSize: 15
        });
        $(this).html("+");
    }
});
</script>

</html>