@extends('backend.layouts.admin_backend')

@section('title')
Coupon
@endsection

@section('new-action-process')

<a class="new-account-btn" data-toggle="modal" data-target="#add_modal">Create New Coupon
    <i class="fa fa-plus-circle"></i>
</a>

@endsection

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <h3 class="page-headr ml-2">Coupon</h3>

            <div class="col-lg-12">
                <div class="card-box">
                    <div class="table-wrap">
                        <div class="table-responsive">

                            <table class="display_table table table-striped" id="coupon_list" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="10%">Title</th>
                                        <th width="8%">Coupon Code</th>
                                        <th width="6%">Discount(%)</th>
                                        <th width="5%">Start Date</th>
                                        <th width="5%">End Date</th>
                                        <th width="5%" align="center">Status</th>
                                        <th width="6%" align="center">Lifetime Access</th>
                                        <th width="6%" align="center">Bulk Coupon</th>
                                        <th width="6%" align="center">Account Type</th>
                                        <th width="10%" align="center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>


<div class="modal fade modal-blur" data-backdrop="true" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Create Coupon</h4>
            </div>
            <form id="coupon_form_add">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">

                            <div class="form-group custom-group">
                                <p class="event-title">Title</p>
                                <input type="text" class="form-control" name="title">
                            </div>

                            <div class="form-group custom-group">
                                <p class="event-title">Start Date</p>
                                <input type="text" class="form-control datepicker " name="start_date">
                            </div>

                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">

                            <div class="form-group custom-group">
                                <p class="event-title">Single Coupon Code</p>
                                <input type="text" class="form-control" name="coupon_code">
                            </div>

                            <div class="form-group custom-group">
                                <p class="event-title">End Date</p>
                                <input type="text" class="form-control datepicker " name="end_date">
                            </div>

                        </div>


                        <div class="col-md-4 col-lg-4 col-sm-4">

                            <div class="form-group custom-group">
                                <p class="event-title">Discount Percent</p>
                                <input type="number" class="form-control" name="percent">
                            </div>

                            <div class="form-group custom-group">
                                <p class="event-title">Status</p>
                                <select class="form-control" name="status">
                                    <option value="">Select a option</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6 col-lg-6 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Lifetime Access?</p>
                                <select class="form-control" name="access">
                                    <option value="">Select a option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Target Account Type?</p>
                                <select class="form-control" name="auth_level">
                                    <option value="">Select a option</option>
                                    <option value="Business">Business</option>
                                    <option value="Individual">Individual</option>
                                    <option value="Either">Either</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Target billing plan?</p>
                                <select class="form-control" name="plan_level">
                                    <option value="">Select a option</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="annual">Annual</option>
                                    <option value="either">Either</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Bulk Coupon?</p>
                                <select class="form-control" name="bulk" id="bulk">
                                    <option value="">Select a option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row" id="prefix_area" style="display: none;">


                        <div class="col-md-6 col-lg-6 col-sm-6">

                            <div class="form-group custom-group">
                                <p class="event-title">Coupon Prefix</p>
                                <input type="text" class="form-control" name="prefix">
                            </div>

                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-6">

                            <div class="form-group custom-group">
                                <p class="event-title">Coupon Postfix</p>
                                <input type="text" class="form-control" name="postfix">
                            </div>

                        </div>

                    </div>

                    @csrf
                </div>
                <div class="modal-footer">
                    <button type="update" class="btn btn-primary action_button submit_button">Process</button>
                    <button type="button" class="btn btn-danger action_button btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>


<div class="modal fade modal-blur" data-backdrop="true" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Coupon</h4>
            </div>
            <form id="coupon_form_update">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">

                            <div class="form-group custom-group">
                                <p class="event-title">Title</p>
                                <input type="text" class="form-control" name="title" id="e_title">
                            </div>

                            <div class="form-group custom-group">
                                <p class="event-title">Start Date</p>
                                <input type="text" class="form-control datepicker " name="start_date" id="e_start_date">
                            </div>

                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">

                            <div class="form-group custom-group">
                                <p class="event-title">Single Coupon Code</p>
                                <input type="text" class="form-control" name="coupon_code" id="e_coupon_code">
                            </div>

                            <div class="form-group custom-group">
                                <p class="event-title">End Date</p>
                                <input type="text" class="form-control datepicker " name="end_date" id="e_end_date">
                            </div>

                        </div>


                        <div class="col-md-4 col-lg-4 col-sm-4">

                            <div class="form-group custom-group">
                                <p class="event-title">Discount Percent</p>
                                <input type="number" class="form-control" name="percent" id="e_percent">
                            </div>

                            <div class="form-group custom-group">
                                <p class="event-title">Status</p>
                                <select class="form-control" name="status" id="e_status">
                                    <option value="">Select a option</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Lifetime Access?</p>
                                <select class="form-control" name="access" id="e_access">
                                    <option value="">Select a option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Target Account Type?</p>
                                <select class="form-control" name="auth_level" id="e_auth_level">
                                    <option value="">Select a option</option>
                                    <option value="Business">Business</option>
                                    <option value="Individual">Individual</option>
                                    <option value="Either">Either</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6 col-lg-6 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Target billing plan?</p>
                                <select class="form-control" name="plan_level" id="e_plan_level">
                                    <option value="">Select a option</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="annual">Annual</option>
                                    <option value="either">Either</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Bulk Coupon?</p>
                                <select class="form-control" name="bulk" id="e_bulk">
                                    <option value="">Select a option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row" id="e_prefix_area" style="display: none;">


                        <div class="col-md-6 col-lg-6 col-sm-6">

                            <div class="form-group custom-group">
                                <p class="event-title">Coupon Prefix</p>
                                <input type="text" class="form-control" name="prefix" id="e_prefix">
                            </div>

                        </div>


                        <div class=" col-md-6 col-lg-6 col-sm-6">

                            <div class="form-group custom-group">
                                <p class="event-title">Coupon Postfix</p>
                                <input type="text" class="form-control" name="postfix" id="e_postfix">
                            </div>

                        </div>


                    </div>
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id" name="id" readonly>

                </div>
                <div class="modal-footer">
                    <button type="update" class="btn btn-primary action_button update_submit_button">Update</button>
                    <button type="button" class="btn btn-danger action_button btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $('#bulk').on('change', function() {
        var status = $(this).val();
        if (status == '1') {
            $('#prefix_area').slideDown();
        } else {
            $('#prefix_area').slideUp();
        }
    });


    $('#e_bulk').on('change', function() {
        var status = $(this).val();
        if (status == '1') {
            $('#e_prefix_area').slideDown();
        } else {
            $('#e_prefix_area').slideUp();
        }
    });


    var coupon_list = $('#coupon_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.coupon.list') }}",
            "dataType": "json",
            "type": "GET",
            // "data":{"_token":"{{ csrf_token() }} "}
        },
        "columns": [{
                "data": "DT_RowIndex",
                "className": "center",
                orderable: false,
                searchable: false
            },
            {
                "data": "title"
            },
            {
                "data": "coupon_code"
            },
            {
                "data": "percent"
            },
            {
                "data": "start_date"
            },
            {
                "data": "end_date"
            },
            {
                "data": "status",
                "className": "center",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    //let hold = data == 1 ? 'Active' : 'Pending';
                    let status = data == 'active' ? 'badge-success' : 'badge-primary';
                    return "<span class='badge " + status + " mr-1 custom-badge'>" + data + "</span>";
                }

            },
            {
                "data": "lifetime",
                "className": "center",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    let hold = data == 1 ? 'Yes' : 'No';
                    let status = data == 1 ? 'badge-success' : 'badge-primary';
                    return "<span class='badge " + status + " mr-1 custom-badge'>" + hold + "</span>";
                }

            },
            {
                "data": "bulk_coupon",
                "className": "center",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    let hold = data == 1 ? 'Yes' : 'No';
                    let status = data == 1 ? 'badge-success' : 'badge-primary';
                    return "<span class='badge " + status + " mr-1 custom-badge'>" + hold + "</span>";
                }

            },
            {
                "data": "target_auth_level"
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
            "defaultContent": " <td><div align='center'><a id='view_button' href='#update_modal' data-toggle='modal' class='btn btn-mini btn-info view_action' role='button'  title='View Coupon'><i class='fa fa-eye'></i> </a>  <button id='delete' class='btn btn-mini btn-danger delete_action' title='Delete Coupon'><i class='fa fa-trash'></i> </button></div></td>"
        }],



    });

    $('#coupon_list tbody').on('click', '#view_button', function() {
        var data = coupon_list.row($(this).parents('tr')).data();

        $('#e_title').val(data.title);
        $('#e_start_date').val(data.start_date);
        $('#e_coupon_code').val(data.coupon_code);
        $('#e_end_date').val(data.end_date);
        $('#e_percent').val(data.percent);
        $('#e_status').val(data.status);
        $('#e_access').val(data.lifetime);
        $('#e_auth_level').val(data.target_auth_level);
        $('#e_bulk').val(data.bulk_coupon);
        $('#e_prefix').val(data.coupon_prefix);
        $('#e_postfix').val(data.coupon_postfix);
        $('#id').val(data.id);
        $('#e_plan_level').val(data.target_plan_level);

        if (data.bulk_coupon == "1") {
            $('#e_prefix_area').show();
        } else {
            $('#e_prefix_area').hide();
        }

    });


    $("#coupon_form_add").validate({
        rules: {
            title: {
                required: true,
            },
            start_date: {
                required: true,
            },
            coupon_code: {
                //required: true,
                required: function() {
                    return $("#bulk").val() != "1";
                },
                minlength: 3,
            },
            end_date: {
                required: true,
            },
            percent: {
                required: true,
            },
            status: {
                required: true,
            },
            access: {
                required: true,
            },
            auth_level: {
                required: true,
            },
            plan_level: {
                required: true,
            },
            bulk: {
                required: true,
            },
            prefix: {
                required: true,
                minlength: 3,
                maxlength: 3,
            },
            postfix: {
                required: true,
                minlength: 4,
                maxlength: 4,
            },
        },
        messages: {

            title: {
                required: 'Please type title'
            },
            start_date: {
                required: 'Please select start date'
            },
            coupon_code: {
                required: 'Please type coupon code'
            },
            end_date: {
                required: 'Please select end date'
            },
            percent: {
                required: 'Please discount percent'
            },
            status: {
                required: 'Please select a status'
            },
            access: {
                required: 'Please select a option'
            },
            auth_level: {
                required: 'Please select a option'
            },
            plan_level: {
                required: 'Please select a option'
            },
            bulk: {
                required: 'Please select a option'
            },
            prefix: {
                required: 'Please type coupon prefix'
            },
            postfix: {
                required: 'Please type coupon postfix'
            },
        },


        submitHandler: function(form) {

            $('.submit_button').css('cursor', 'wait');
            $('.submit_button').attr('disabled', true);

            $.ajax({
                url: "{{ route('user.store.coupon') }}",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {

                    $("#coupon_form_add").trigger("reset");
                    $('#prefix_area').hide();
                    $('#add_modal').modal('hide');

                    toastr.success('Successfully Coupon Added');

                    coupon_list.ajax.reload();

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

    $("#coupon_form_update").validate({
        rules: {
            title: {
                required: true,
            },
            start_date: {
                required: true,
            },
            coupon_code: {
                //required: true,
                required: function() {
                    return $("#e_bulk").val() != "1";
                },
                minlength: 3,
            },
            end_date: {
                required: true,
            },
            percent: {
                required: true,
            },
            status: {
                required: true,
            },
            access: {
                required: true,
            },
            auth_level: {
                required: true,
            },
            plan_level: {
                required: true,
            },
            bulk: {
                required: true,
            },
            prefix: {
                required: true,
                minlength: 3,
                maxlength: 3,
            },
            postfix: {
                required: true,
                minlength: 4,
                maxlength: 4,
            },

        },
        messages: {

            title: {
                required: 'Please type title'
            },
            start_date: {
                required: 'Please select start date'
            },
            coupon_code: {
                required: 'Please type coupon code'
            },
            end_date: {
                required: 'Please select end date'
            },
            percent: {
                required: 'Please discount percent'
            },
            status: {
                required: 'Please select status'
            },
            access: {
                required: 'Please select a option'
            },
            auth_level: {
                required: 'Please select a option'
            },
            plan_level: {
                required: 'Please select a option'
            },
            bulk: {
                required: 'Please select a option'
            },
            prefix: {
                required: 'Please type coupon prefix'
            },
            postfix: {
                required: 'Please type coupon postfix'
            },
        },


        submitHandler: function(form) {

            $('.update_submit_button').css('cursor', 'wait');
            $('.update_submit_button').attr('disabled', true);

            $.ajax({
                url: "{{ route('user.update.coupon') }}",
                type: "PUT",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {

                    $("#coupon_form_update").trigger("reset");
                    $('#e_prefix_area').hide();
                    $('#update_modal').modal('hide');

                    toastr.success('Successfully Coupon Updated');

                    coupon_list.ajax.reload();

                    $('.update_submit_button').css('cursor', 'pointer');
                    $('.update_submit_button').removeAttr('disabled');


                },
                error: function(jqXHR, textStatus, errorThrown) {

                    toastr.error('A system error has been encountered. Please try again');

                    $('.update_submit_button').css('cursor', 'pointer');
                    $('.update_submit_button').removeAttr('disabled');

                }


            });

        }, // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }


    });



    $('#coupon_list tbody').on('click', '#delete', function() {
        var data = coupon_list.row($(this).parents('tr')).data();


        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        }, function() {


            $.ajax({
                type: "POST",
                url: "{{route('user.destroy.coupon')}}",
                data: {
                    "id": data.id,
                    "_method": 'delete',
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    toastr.success('Successfully Coupon Deleted');
                    coupon_list.ajax.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(jqXHR.responseJSON);
                }

            });



        });



    });
</script>

@stop