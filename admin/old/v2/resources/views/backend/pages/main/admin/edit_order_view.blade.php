@extends('backend.layouts.admin_backend')

@section('title')
Edit Order
@endsection

@section('content')

@php
$site_url = config('services.site.url');
$user_status = $order_user == "Guest" ? 'badge-danger' : 'badge-secondary';
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
                                <h3 class="section-title">Edit Order</h3>
                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <legend class="form-legend">Order Information</legend>
                                </div>

                                <div class="col-lg-4 text">

                                    <label class="event-form-label">Order ID</label>
                                    <div class="form-group">
                                        <p> #{{ $singleData->id }}</p>
                                    </div>

                                </div>

                                <div class="col-lg-4 text">

                                    <label class="event-form-label">Order Date</label>
                                    <div class="form-group">
                                        <p> {{ $singleData->created_at }}</p>
                                    </div>

                                </div>

                                <div class="col-lg-4 text">

                                    <label class="event-form-label">Order Price</label>
                                    <div class="form-group">
                                        <p>${{ $singleData->service_price }}</p>
                                    </div>

                                </div>

                                <div class="col-lg-6 text">

                                    <label class="event-form-label">Service UUID</label>
                                    <div class="form-group">
                                        <p>{{ $singleData->services->service_uuid }}</p>
                                    </div>

                                </div>

                                <div class="col-lg-6 text">

                                    <label class="event-form-label">Order User</label>
                                    <div class="form-group">
                                        <p> <span class="badge {{ $user_status }} mr-1 custom-badge">{{ $order_user }}</span></p>
                                    </div>

                                </div>

                                <div class="col-lg-12 text">
                                    <label class="event-form-label">Service Title</label>
                                    <div class="form-group">
                                        <p>{{ $singleData->services->service_title }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-12 text">
                                    <label class="event-form-label">Service Description</label>
                                    <div class="form-group">
                                        <!-- <p><button type="button">Show</button></p> -->
                                        <p>{!! $singleData->services->author_description !!}</p>
                                    </div>
                                </div>



                                <div class="col-lg-4 text">

                                    <label class="event-form-label">Service Category</label>
                                    <div class="form-group">
                                        <p> {{ $order_category }} </p>
                                    </div>

                                </div>

                                <div class="col-lg-4 text">

                                    <label class="event-form-label">Author Name</label>
                                    <div class="form-group">
                                        <p>{{ $singleData->services->author_name }} </p>
                                    </div>

                                </div>

                                <div class="col-lg-4 text">

                                    <label class="event-form-label">Service Type</label>
                                    <div class="form-group">
                                        <p>{{ $singleData->services->service_type }} </p>
                                    </div>

                                </div>


                            </div>





                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-box">


                            <div class="row">

                                <div class="col-lg-12">
                                    <label class="event-form-label">Order Status</label>
                                    <div class="form-group">
                                        <select class="form-control" name="order_status" id="order_status">
                                            <option value="">Select a option</option>
                                            <option value="pending">Pending payment</option>
                                            <option value="processing">Processing</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                            <option value="refunded">Refunded</option>
                                            <option value="failed">Failed</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                </div>



                                @csrf

                                <div class="col-lg-12">
                                    <div class="form-group d-flex justify-content-between">
                                        <button type="button" class="btn btn-info process_button submit_button" id="back_buttton"> <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>
                                            Back</button>
                                        <button type="submit" class="btn btn-primary process_button submit_button"> <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                            Update</button>
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
    $(function() {
        loadEditData();
    });


    function loadEditData() {

        let order_status = "{{ $singleData->status }}";

        $('#order_status').val(order_status);


    }

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


    $(document).on('click', '#back_buttton', function() {

        swal({
            title: "Are you sure,to go back?",
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#025390",
            confirmButtonText: "Yes!",
            closeOnConfirm: true
        }, function() {

            window.location.href = "{{route('user.order.list')}}";

        });

    });
</script>

@stop