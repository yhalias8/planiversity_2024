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

            <div class="row main-content">

                <div class="col-lg-8">

                    <div class="card-box section-content form-content">
                        <div class="section-header">
                            <h3 class="section-title">Edit Order</h3>
                        </div>

                        <div class="row ">

                            <div class="col-lg-12">
                                <legend class="form-legend">Order Information</legend>
                            </div>

                            <div class="col-lg-4 text">

                                <label class="event-form-label">Order ID</label>
                                <div class="form-group">
                                    <p> {{ $singleData->id }}</p>
                                </div>

                            </div>

                            <div class="col-lg-4 text">

                                <label class="event-form-label">Order Number</label>
                                <div class="form-group">
                                    <p> #{{ $singleData->order_number }}</p>
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

                            <div class="col-lg-4 text">

                                <label class="event-form-label">Service UUID</label>
                                <div class="form-group">
                                    <p>{{ $singleData->services->service_uuid }}</p>
                                </div>

                            </div>

                            <div class="col-lg-4 text">

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

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <legend class="form-legend">Order Payment</legend>
                            </div>



                            <div class="table-responsive">

                                <table class="display_table table table-striped" id="payment_list" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="3%">#</th>
                                            <th width="18%">Date</th>
                                            <th width="10%" class="hidden">Payment</th>
                                            <th width="10%">Amount</th>
                                            <th width="8%">Status</th>
                                            <th width="8%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                        <p>Rating</p>




                    </div>
                </div>



                <div class="col-lg-4">
                    <form id="service_form_add">
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

                    </form>
                </div>

            </div>

        </div>
    </div>

</div>


<div class="modal fade modal-blur" data-backdrop="true" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">View Payment</h4>
            </div>
            <form class="view_class">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Transaction ID</p>
                                <input type="text" class="form-control" id="transaction_id" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Order Number</p>
                                <input type="text" class="form-control datepicker" id="order_number" readonly>
                            </div>
                        </div>


                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Payment Type</p>
                                <input type="text" class="form-control datepicker" id="payment_type" readonly>
                            </div>
                        </div>



                    </div>

                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Created Date</p>
                                <input type="text" class="form-control" id="created_at" readonly>
                            </div>
                        </div>


                        <div class="col-md-6 col-lg-8 col-sm-8">
                            <div class="form-group custom-group">
                                <p class="event-title">Service Name</p>
                                <input type="text" class="form-control" id="service_name" readonly>
                            </div>
                        </div>



                    </div>

                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Amount</p>
                                <input type="text" class="form-control" id="amount" readonly>
                            </div>
                        </div>


                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Ip Address</p>
                                <input type="text" class="form-control" id="ip_address" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Status</p>
                                <input type="text" class="form-control" id="status" readonly>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">User</p>
                                <input type="text" class="form-control" id="user_name" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">User First Name</p>
                                <input type="text" class="form-control" id="fname" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">User Last Name</p>
                                <input type="text" class="form-control" id="lname" readonly>
                            </div>
                        </div>



                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger action_button btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
        </div>
        </form>

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


    var payment_list = $('#payment_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.order.payment') }}",
            "dataType": "json",
            "type": "GET",
            "data": {
                "order_id": "{{ $singleData->id }} ",
                //"_token": "{{ csrf_token() }} "
            }
        },
        "columns": [{
                "data": "DT_RowIndex",
                "className": "center",
                orderable: false,
                searchable: false
            },
            {
                "data": "created_at"
            },
            {
                "data": "payment_type",
                "className": "hidden",
            },
            {
                "data": "amount"
            },
            {
                "data": "status",
                "className": "center",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    //let hold = data == "Succeeded" ? 'Active' : 'Pending';
                    let status = data == "succeeded" ? 'badge-success' : 'badge-primary';
                    return "<span class='badge " + status + " mr-1 custom-badge'>" + data + "</span>";
                }

            },

            {
                data: 'action',
                name: 'action',
                "className": "center",
                orderable: false,
                searchable: false
            }

        ],

        "columnDefs": [{
            "targets": -1,
            "data": null,
            "defaultContent": " <td><div align='center'><a id='view_button' href='#update_modal' data-toggle='modal' class='btn btn-mini btn-info view_action' role='button'  title='View Payment'><i class='fa fa-eye'></i> </a> </div></td>"
        }],



    });


    $('#payment_list tbody').on('click', '#view_button', function() {
        var data = payment_list.row($(this).parents('tr')).data();

        $('#transaction_id').val(data.transaction_id);
        $('#payment_type').val(data.payment_type);
        $('#order_number').val(data.order_number);
        $('#created_at').val(data.created_at);
        $('#service_name').val(data.service_title);
        $('#amount').val(data.amount);
        $('#ip_address').val(data.ip_address);
        $('#status').val(data.status);
        $('#user_name').val(data.user);
        $('#fname').val(data.fname);
        $('#lname').val(data.lname);

    });
</script>

@stop