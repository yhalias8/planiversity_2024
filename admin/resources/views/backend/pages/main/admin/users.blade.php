@extends('backend.layouts.admin_backend')

@section('title')
Users List
@endsection

@section('new-action-process')

<a class="new-account-btn" data-toggle="modal" data-target="#add_modal">Create New User
    <i class="fa fa-plus-circle"></i>
</a>

@endsection

@section('content')

<div class="container-fluid">

    <div class="row ">
        <div class="col-12">
            <h3 class="page-headr ml-2">Users</h3>

            <div class="col-lg-12">
                <div class="card-box">
                    <div class="table-wrap">
                        <div class="table-responsive">

                            <table class="display_table table table-striped" id="user_list" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="12%">Name</th>
                                        <th width="15%">Email</th>
                                        <th width="12%">Created at</th>
                                        <th width="12%">Last Signin at</th>
                                        <th width="10%">Account status</th>
                                        <th width="12%">Account type</th>
                                        <th width="10%"></th>
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
                <h4 class="modal-title" id="myModalLabel">Create New User</h4>
            </div>
            <form id="addForm">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-8 col-lg-8 col-sm-12">

                            <div class="form-group custom-group">
                                <p class="event-title">User Name</p>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <label id="name-error" class="error name_error custom-label" for="name"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Email</p>
                                <input type="text" class="form-control" name="email">
                            </div>
                            <label id="email-error" class="error email_error custom-label" for="email"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Password</p>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <label id="password-error" class="error password_error custom-label" for="password"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Account Type</p>
                                <select class="form-control" name="account_type">
                                    <option value="Individual" selected>Individual</option>
                                    <option value="Business">Business</option>
                                </select>
                            </div>
                            <label id="account_type-error" class="error account_type_error custom-label" for="account_type"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Status</p>
                                <select class="form-control" name="status">
                                    <option value="1" selected>Active</option>
                                    <option value="0">inactive</option>
                                </select>
                            </div>
                            <label id="status-error" class="error status_error custom-label" for="status"></label>

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

<script>
    var user_list = $('#user_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.list') }}",
            "dataType": "json",
            "type": "GET",
            "data":{"_token":"{{ csrf_token() }} "}
        },
        "columns": [{
                "data": "DT_RowIndex",
                "className": "center",
                orderable: false,
                searchable: false
            },
            {
                "data": "name"
            },
            {
                "data": "email"
            },
            {
                "data": "date_created"
            },
            {
                "data": "date_current_login"
            },
            {
                "data": "active",
                "className": "center",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    let hold = data == 1 ? 'Active' : 'Pending';
                    let status = data == 1 ? 'badge-success' : 'badge-primary';
                    return "<span class='badge " + status + " mr-1 custom-badge'>" + hold + "</span>";
                }

            },
            {
                "data": "account_type",
                "className": "center",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    let status = data == 'Individual' ? 'individual-status' : 'business-status';
                    return "<span class='account_type " + status + "'>" + data + "</span>";
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
            "defaultContent": " <td><div align='center'><a id='view_button' class='btn btn-mini btn-info view_action' role='button'  title='View User'><i class='fa fa-eye'></i> </a>  <button id='delete' class='btn btn-mini btn-danger delete_action' title='Delete User'><i class='fa fa-trash'></i> </button></div></td>"
        }],



    });



    $('#user_list tbody').on('click', '#view_button', function() {
        var data = user_list.row($(this).parents('tr')).data();
        var url = "{{route('user.single.user', '')}}" + "/" + data.id;
        window.location.href = url;
    });


    $("#addForm").validate({
        rules: {

            name: {
                required: true,
                minlength: 3,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20
            },
            account_type: {
                required: true,
            },
            status: {
                required: true,
            },

        },

        messages: {
            name: {
                required: "Please type user name",
                minlength: "Minimum length need to be 3",
            },
            email: {
                required: "Please type user email",
                email: "Please type valid email",
            },
            password: {
                required: "Please type password",
            },
            account_type: {
                required: "Please select a account type",
            },
            status: {
                required: "Please select a status",
            },

        },

        submitHandler: function(form) {

            $('.submit_button').css('cursor', 'wait');
            $('.submit_button').attr('disabled', true);

            $.ajax({
                url: "{{ route('user.store.user') }}",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {

                    $(form).trigger("reset");
                    $('#add_modal').modal('hide');
                    toastr.success(response.message);
                    user_list.ajax.reload();

                    $('.submit_button').css('cursor', 'pointer');
                    $('.submit_button').removeAttr('disabled');

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    if (jqXHR.responseJSON.errors) {
                        $.each(jqXHR.responseJSON.errors, function(prefix, val) {
                            $(form).find('label.' + prefix + '_error').html(val[0]).show();
                        });
                        toastr.error(jqXHR.responseJSON.message);
                    }


                    if (!jqXHR.responseJSON.errors) {
                        toastr.error(jqXHR.responseJSON);
                    }

                    $('.submit_button').css('cursor', 'pointer');
                    $('.submit_button').removeAttr('disabled');

                }
            });
        }, // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }

    });


    $('#user_list tbody').on('click', '#delete', function() {
        var data = user_list.row($(this).parents('tr')).data();

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
                url: "{{route('user.destroy.userList')}}",
                data: {
                    "id": data.id,
                    "_method": 'delete',
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastr.success(response);
                    user_list.ajax.reload();

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    toastr.error(jqXHR.responseJSON);
                    user_list.ajax.reload();

                }

            });



        });



    });
</script>

@stop