@extends('backend.layouts.admin_backend')

@section('title')
Users List
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

<script>
    var user_list = $('#user_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.list') }}",
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