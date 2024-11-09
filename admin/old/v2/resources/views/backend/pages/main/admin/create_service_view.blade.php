@extends('backend.layouts.admin_backend')

@section('title')
Add New Service
@endsection

@section('content')

@php
$site_url = config('services.site.url');
@endphp

<div class="container-fluid">

    <div class="row ">
        <div class="col-12">
            <!-- <h3 class="page-headr ml-2"> Single Users</h3> -->

            <!-- Top Status -->
            <form id="service_form_add">
                <div class="row main-content">

                    <div class="col-lg-8">


                        <div class="card-box section-content form-content">
                            <div class="section-header">
                                <h3 class="section-title">Add New Service</h3>
                            </div>



                            <div class="row">

                                <div class="col-lg-12">
                                    <legend class="form-legend">Service Information</legend>
                                </div>

                                <div class="col-lg-12">
                                    <label class="event-form-label">Service Title</label>
                                    <div class="form-group">
                                        <input name="service_title" type="text" class="account-form-control form-control input-lg inp1" placeholder="Service Title">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="event-form-label">Service Description</label>
                                    <div class="form-group">
                                        <textarea id="service_description" name="service_description" class="form-control summernote"></textarea>
                                    </div>
                                    <label id="service_description-error" class="error service_description_error custom-label" for="service_description"></label>
                                </div>

                                <div class="col-lg-12">

                                    <label class="event-form-label">Regular Price (Amount)</label>
                                    <div class="form-group">
                                        <input name="regular_price" id="regular_price" type="text" class="account-form-control form-control input-lg inp1" placeholder="0">
                                    </div>

                                </div>

                                <div class="col-lg-6">

                                    <label class="event-form-label">Sale Price (Amount)</label>
                                    <div class="form-group">
                                        <input name="sale_price" id="sale_price" type="text" class="account-form-control form-control input-lg inp1" placeholder="0">
                                    </div>


                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-sm-4">

                                            <label class="event-form-label">Member Price (%)</label>
                                            <div class="form-group">
                                                <input name="member_price_percentage" id="member_price_percentage" value="10" type="number" class="account-form-control form-control input-lg inp1" placeholder="%">
                                            </div>

                                        </div>

                                        <div class="col-sm-8">

                                            <label class="event-form-label">Member Price (Amount)</label>
                                            <div class="form-group">
                                                <input name="member_price" id="member_price" type="text" class="account-form-control form-control input-lg inp1" placeholder="0">
                                            </div>

                                        </div>
                                    </div>

                                </div>


                                <div class="col-lg-6">

                                    <label class="event-form-label">Service Type</label>
                                    <div class="form-group">
                                        <select class="form-control" name="service_type" id="service_type">
                                            <option value="">Select a option</option>
                                            <option value="virtual">Virtual</option>
                                            <option value="downloadable">Downloadable</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="col-lg-6">
                                    <div id="downloadable" style="display: none;">
                                        <label class="event-form-label">Downloadable file</label>
                                        <div class="form-group">
                                            <input type="file" name="downloadable_file" class="downloadable">
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <legend class="form-legend">Author Information</legend>
                                </div>

                                <div class="col-lg-12">
                                    <label class="event-form-label">Author Name</label>
                                    <div class="form-group">
                                        <input name="author_name" type="text" id="author_name" class="account-form-control form-control input-lg inp1" placeholder="Author Name">
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <label class="event-form-label">Author Description</label>
                                    <div class="form-group">
                                        <textarea id="author_description" name="author_description" class="form-control summernote"></textarea>
                                    </div>
                                    <label id="author_description-error" class="error author_description_error custom-label" for="author_description"></label>
                                </div>




                            </div>



                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-box">
                            <!-- <div class="comment-title">
                            <h3> Added Comments</h3>
                        </div> -->

                            <!-- <div class="comment-body">


                        </div> -->



                            <div class="row">

                                <div class="col-lg-12">
                                    <label class="event-form-label">Service Status</label>
                                    <div class="form-group">
                                        <select class="form-control" name="service_status">
                                            <option value="">Select a option</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <label class="event-form-label">Service Category</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="category_id" placeholder="Category Name" value="" list="category_id-datalist" autocomplete="off">
                                        <datalist id="category_id-datalist" class="category">

                                        </datalist>
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <label class="event-form-label">Image</label>
                                    <div class="form-group">
                                        <input type="file" name="image" class="dropify" data-height="180" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png">
                                    </div>
                                    <label id="image-error" class="error image_error custom-label" for="image"></label>
                                </div>

                                @csrf

                                <div class="col-lg-12">
                                    <div class="form-group d-flex justify-content-between">
                                        <button type="button" class="btn btn-info process_button submit_button" id="reset_buttton"> <i class="fa fa-history" aria-hidden="true"></i>
                                            Reset</button>
                                        <button type="submit" class="btn btn-primary process_button submit_button"> <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                            Process</button>
                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>
            </form>
        </div>
    </div>

</div>

<script>
    $('.dropify').dropify();

    $(document).ready(function() {
        getCategoryList();
        // $('.summernote').summernote({
        //     height: 150,
        // });
    });

    $('#service_type').on('change', function() {
        var status = $(this).val();
        if (status == 'downloadable') {
            $('#downloadable').show();
        } else {
            $('#downloadable').hide();
        }
    });


    function getCategoryList() {

        var items = "";
        $.getJSON("{{ route('user.categorylist.service') }}", function(data) {
            $.each(data, function(index, item) {
                items += "<option id='" + item.id + "' value='" + item.category_name + "' >" + item.category_name + "</option>";
            });

            $(".category").html(items);
        });

    }

    function discount_member_price() {

        var member_price = $('#member_price');

        member_price.val(function() {

            var regular_price = $('#regular_price').val();
            var member_price_percentage = $('#member_price_percentage').val();

            if (regular_price == '') {
                regular_price = '0';
            }
            if (member_price_percentage == '') {
                member_price_percentage = '0';
                $('#member_price_percentage').val(0);
            }
            return ((parseFloat(regular_price) - (parseFloat(regular_price) * parseFloat(member_price_percentage) / 100))).toFixed(2);
        });

    };

    $(document).on('keyup blur', '#regular_price', function() {
        discount_member_price();
    });

    $(document).on('keyup blur', '#member_price_percentage', function() {
        var regular_price = $('#regular_price').val();
        var member_price_percentage = $(this).val();

        if (regular_price == '') {
            regular_price = '0';
        }
        if (member_price_percentage == '') {
            member_price_percentage = '0';
        }

        var output = ((parseFloat(regular_price) - (parseFloat(regular_price) * parseFloat(member_price_percentage) / 100))).toFixed(2);

        $('#member_price').val(output);
    });

    $(document).on('keyup blur', '#member_price', function() {
        var regular_price = $('#regular_price').val();
        var member_price = $(this).val();

        if (regular_price == '') {
            regular_price = '0';
        }
        if (member_price == '') {
            member_price = '0';
        }

        var output = (((parseFloat(regular_price) - parseFloat(member_price)) / parseFloat(regular_price)) * 100).toFixed(2);

        $('#member_price_percentage').val(output);
    });



    // $.validator.setDefaults({
    //     ignore: ":hidden:not(.summernote),.note-editable.panel-body"
    // });


    $.validator.addMethod('category_required', function(value, element, param) {
        var id = $(param).find('option').filter(
            function() {
                return $.trim($(this).text()) === value;
            }).attr('id');

        if (id) {
            return true;
        } else {
            return false;
        }
    }, "");

    $.validator.addMethod("greaterThan", function(value, element, param) {

        var $otherElement = $(param);
        if (value == "") {
            return true;
        }
        if (parseInt(value, 10) < parseInt($otherElement.val(), 10)) {
            return true;
        } else {
            return false;
        }
    });

    var form_validate = $("#service_form_add").validate({
        ignore: ":hidden:not(.summernote),.note-editable.panel-body",
        rules: {
            service_title: {
                required: true,
            },
            service_description: {
                required: true,
            },
            regular_price: {
                required: true,
            },
            sale_price: {
                required: false,
                greaterThan: "#regular_price"
            },
            member_price: {
                required: true,
                greaterThan: "#regular_price"
            },
            service_type: {
                required: true,
            },
            downloadable_file: {
                required: true,
            },
            author_name: {
                required: true,
            },
            author_description: {
                required: true,
            },
            service_status: {
                required: true,
            },
            category_id: {
                required: true,
                category_required: '#category_id-datalist',
            },
            image: {
                required: true,
            },

        },
        messages: {

            service_title: {
                required: 'Please type service title'
            },
            service_description: {
                required: 'Please type service description'
            },
            regular_price: {
                required: 'Please type regular price'
            },
            sale_price: {
                greaterThan: 'Please type a price is less then regular price'
            },
            member_price: {
                required: 'Please type member price',
                greaterThan: 'Please type a price is less then regular price'
            },
            service_type: {
                required: 'Please select service type'
            },
            downloadable_file: {
                required: 'Please upload file'
            },
            author_name: {
                required: 'Please type author name'
            },
            author_description: {
                required: 'Please type author description'
            },
            service_status: {
                required: 'Please select a option'
            },
            category_id: {
                required: "Please select a category name",
                category_required: "Please select valid category name"
            },
            image: {
                required: "Please upload image",
            },
        },


        submitHandler: function(form) {

            $('.submit_button').css('cursor', 'wait');
            $('.submit_button').attr('disabled', true);

            var category_id_key = $('input[name="category_id"]').val();
            var category_id = $('#category_id-datalist').find('option').filter(function() {
                return $.trim($(this).text()) === category_id_key;
            }).attr('id');

            var formData = new FormData($('#service_form_add')[0]);
            formData.append('category_id', category_id);

            $.ajax({
                url: "{{ route('user.store.service') }}",
                type: "POST",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {

                    $(form).trigger("reset");
                    $('.note-editable').html('');
                    $('.dropify-clear').trigger("click");

                    toastr.success('Successfully Service Added');

                    $('.submit_button').css('cursor', 'pointer');
                    $('.submit_button').removeAttr('disabled');


                },
                error: function(jqXHR, textStatus, errorThrown) {

                    toastr.error('A system error has been encountered. Please try again');

                    $('.submit_button').css('cursor', 'pointer');
                    $('.submit_button').removeAttr('disabled');

                }


            });




        }, // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }


    });


    $(document).on('click', '#reset_buttton', function() {

        swal({
            title: "Are you sure,to reset?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, reset it!",
            closeOnConfirm: true
        }, function() {

            $('#service_form_add').trigger("reset");
            $('.note-editable').html('');
            $('.dropify-clear').trigger("click");

            toastr.success('Form Reseted');

        });



    });


    var myElement = $('#service_description');
    var myElement2 = $('#author_description');

    myElement.summernote({
        // See: http://summernote.org/deep-dive/
        height: 150,
        callbacks: {
            // Register the `onChnage` callback in order to listen to the changes of the
            // Summernote editor. You can also use the event `summernote.change` to handle
            // the change event as follow:
            //   myElement.summernote()
            //     .on("summernote.change", function(event, contents, $editable) {
            //       // ...
            //     });
            onChange: function(contents, $editable) {
                // Note that at this point, the value of the `textarea` is not the same as the one
                // you entered into the summernote editor, so you have to set it yourself to make
                // the validation consistent and in sync with the value.
                myElement.val(myElement.summernote('isEmpty') ? "" : contents);

                // You should re-validate your element after change, because the plugin will have
                // no way to know that the value of your `textarea` has been changed if the change
                // was done programmatically.
                form_validate.element(myElement);
            }
        }
    });

    myElement2.summernote({
        // See: http://summernote.org/deep-dive/
        height: 150,
        callbacks: {
            // Register the `onChnage` callback in order to listen to the changes of the
            // Summernote editor. You can also use the event `summernote.change` to handle
            // the change event as follow:
            //   myElement.summernote()
            //     .on("summernote.change", function(event, contents, $editable) {
            //       // ...
            //     });
            onChange: function(contents, $editable) {
                // Note that at this point, the value of the `textarea` is not the same as the one
                // you entered into the summernote editor, so you have to set it yourself to make
                // the validation consistent and in sync with the value.
                myElement2.val(myElement2.summernote('isEmpty') ? "" : contents);

                // You should re-validate your element after change, because the plugin will have
                // no way to know that the value of your `textarea` has been changed if the change
                // was done programmatically.
                form_validate.element(myElement2);
            }
        }
    });
</script>

@stop