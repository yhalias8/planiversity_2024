@extends('backend.layouts.admin_backend')

@section('title')
User View
@endsection

@section('content')

<div class="container-fluid">

    @php
    $site_url = config('services.site.url');
    $status = $singleData->active ? ' Active' : ' Pending ';
    $type_label = $singleData->account_type=="Bussines" ? ' business-status' : ' individual-status ';
    @endphp

    <div class="row ">
        <div class="col-12">
            <!-- <h3 class="page-headr ml-2"> Single Users</h3> -->

            <div class="row">

                <div class="col-lg-12">
                    <div class="top-section d-flex justify-content-between">

                        <div class="top-left">
                            <a href="{{ url('users') }}" class="btn btn-nav"><i class="fa fa-chevron-circle-left"></i>Back To Users</a>
                        </div>
                        <div class="top-right">
                            <button class="btn btn-nav white action_button" id="active_action"> <i class="fa fa-check-circle-o"></i> Active</button>
                            <button class="btn btn-nav white action_button" id="hold_action"> <i class="fa fa-pause-circle-o"></i> Hold Account</button>
                            <button class="btn btn-nav white action_button" id="delete_action"> <i class="fa fa-trash"></i> Delete User</button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row main-content">
                <div class="col col-lg-6">
                    <div class="user-top-header">
                        <div class="user-info-stats">
                            <div class="user-info-img">


                                @if(empty($singleData->picture))
                                <img src="{{ $site_url }}assets/images/user_profile.png" width="50px" height="50px" alt="user" class="header-avatar rounded-circle">
                                @else
                                <img src="{{ $site_url }}ajaxfiles/profile/{{ $singleData->picture }}" width="50px" height="50px" alt="user" class="header-avatar rounded-circle">
                                @endif

                            </div>
                            <div class="user-info-text">
                                <h4>{{ $singleData->name }}</h4>
                                <h5>Customer#: {{ $singleData->customer_number }} </h5>
                            </div>
                        </div>

                        <div class="user-button-action">

                        </div>

                    </div>

                    <div class="card-box section-content">
                        <div class="section-header">
                            <h3 class="section-title">User Details</h3>
                            <span class="account_type{{ $type_label }} m-small">{{ $singleData->account_type }} User</span>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <label class="event-form-label">Customer Name</label>
                                <div class="form-group">
                                    <input name="customer_name" type="text" value="{{ $singleData->name }}" class="account-form-control form-control input-lg inp1" placeholder="Name" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="event-form-label">Customer Email</label>
                                <div class="form-group">
                                    <input name="customer_name" type="text" value="{{ $singleData->email }}" class="account-form-control form-control input-lg inp1" placeholder="Email" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-sm-5">

                                        <label class="event-form-label">Package Count</label>
                                        <div class="form-group">
                                            <input name="package" type="text" value="0" class="account-form-control form-control input-lg inp1" placeholder="0" readonly>
                                        </div>

                                    </div>

                                    <div class="col-sm-7">

                                        <label class="event-form-label">Current Sign in At</label>
                                        <div class="form-group">
                                            <input name="current_at" type="text" value="{{ $singleData->date_current_login }}" class="account-form-control form-control input-lg inp1" placeholder="Date" readonly>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-sm-6">

                                        <label class="event-form-label">Last Sign In</label>
                                        <div class="form-group">
                                            <input name="last_at" type="text" value="{{ $singleData->date_last_login }}" class="account-form-control form-control input-lg inp1" placeholder="Date" readonly>
                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <label class="event-form-label">Current Sign in IP</label>
                                        <div class="form-group">
                                            <input name="current_ip" type="text" value="{{ $singleData->ip_current_login }}" class="account-form-control form-control input-lg inp1" placeholder="IP" readonly>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-6">
                                <label class="event-form-label">Telephone Number</label>
                                <div class="form-group">
                                    <input name="mobile_number" type="text" value="{{ $singleData->mobile_no }}" class="account-form-control form-control input-lg inp1" placeholder="Number" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-sm-6">

                                        <label class="event-form-label">Account Status</label>
                                        <div class="form-group">
                                            <input name="status" type="text" id="status_field" value="{{ $status }}" class="account-form-control form-control input-lg inp1" placeholder="Status" readonly>
                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <label class="event-form-label">Account Type</label>
                                        <div class="form-group">
                                            <input name="account_type" type="text" value="{{ $singleData->account_type }}" class="account-form-control form-control input-lg inp1" placeholder="Type" readonly>
                                        </div>

                                    </div>
                                </div>

                            </div>


                            <div class="col-lg-6">
                                <label class="event-form-label">Signin Count</label>
                                <div class="form-group">
                                    <input name="signin_count" type="text" value="{{ $singleData->sign_count }}" class="account-form-control form-control input-lg inp1" placeholder="Signin Count" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label class="event-form-label">Failed Attempts</label>
                                <div class="form-group">
                                    <input name="failed_count" type="text" value="{{ $singleData->failed_attemps }}" class="account-form-control form-control input-lg inp1" placeholder="Failed Attempts" readonly>
                                </div>
                            </div>


                        </div>




                    </div>
                </div>
                <div class="col col-lg-6">
                    <!-- <div class="card-box user-comment"> -->


                    <div class="main-card mb-3 card user-comment">
                        <div class="card-header">

                            <div class="btn-actions-pane-right">
                                <div role="group" class="btn-group-sm nav btn-group">
                                    <a data-toggle="tab" href="#tab-eg3-0" class="btn-pill pl-3 active btn btn-focus">Payment</a>
                                    <a data-toggle="tab" href="#tab-eg3-1" class="btn btn-focus">Orders</a>
                                    <a data-toggle="tab" href="#tab-eg3-2" class="btn-pill pr-3  btn btn-focus">Comment</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-eg3-0" role="tabpanel">


                                    <div class="history-header">
                                        <h3 class="history-title">Payment History</h3>
                                    </div>

                                    <div class="history-body">

                                        <div class="table-wrap">
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

                                    </div>



                                </div>
                                <div class="tab-pane" id="tab-eg3-1" role="tabpanel">


                                    <div class="history-header">
                                        <h3 class="history-title">Order History</h3>
                                    </div>

                                    <div class="history-body">

                                        <div class="table-wrap">
                                            <div class="table-responsive">

                                                <table class="display_table table table-striped" id="order_list" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="3%">#</th>
                                                            <th width="12%">Order Date</th>
                                                            <th width="12%">Service Title</th>
                                                            <th width="8%">Price</th>
                                                            <th width="8%">Status</th>
                                                            <th width="8%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="tab-pane" id="tab-eg3-2" role="tabpanel">
                                    <p>Added Comments</p>
                                </div>
                            </div>
                        </div>
                    </div>





                    <!-- <div class="comment-title">
                            <h3> Added Comments</h3>
                        </div>

                        <div class="comment-body">


                        </div> -->



                    <!-- </div> -->


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
                                <p class="event-title">Payment Type</p>
                                <input type="text" class="form-control datepicker" id="payment_type" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Plan Type</p>
                                <input type="text" class="form-control datepicker" id="plan_type" readonly>
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


                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Paid Date</p>
                                <input type="text" class="form-control" id="date_paid" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">Expired Date</p>
                                <input type="text" class="form-control" id="date_expire" readonly>
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


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger action_button btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
        </div>
        </form>

    </div>
</div>
</div>



<script>
    var payment_list = $('#payment_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.payment.list') }}",
            "dataType": "json",
            "type": "GET",
            "data": {
                "uid": "{{ $singleData->id }} ",
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
                "data": "date_paid"
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
        $('#plan_type').val(data.plan_type);
        $('#created_at').val(data.created_at);
        $('#date_paid').val(data.date_paid);
        $('#date_expire').val(data.date_expire);
        $('#amount').val(data.amount);
        $('#ip_address').val(data.ip_address);
        $('#status').val(data.status);

    });



    var order_list = $('#order_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.order.list') }}",
            "dataType": "json",
            "type": "GET",
            "data": {
                "user_id": "{{ $singleData->id }} "
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
                "data": "service_title"
            },
            {
                "data": "service_price"
            },
            {
                "data": "status",
                "className": "center",
                // searchable: false,
                // orderable: false,
                "render": function(data, type, row) {
                    let status = data == "completed" ? 'badge-success' : 'badge-primary';
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
            "defaultContent": " <td><div align='center'><a id='edit' class='btn btn-mini btn-info view_action' role='button' title='Edit Order'><i class='fa fa-eye'></i> </a> </div></td>"
        }],

    });

    $('#order_list tbody').on('click', '#edit', function() {
        var data = order_list.row($(this).parents('tr')).data();
        var url = "{{route('user.edit.order', '')}}" + "/" + data.id;
        window.location.href = url;
    });

    $('#active_action').click(function() {
        var status = 1;
        var dataSet = 'status=' + status + '&uid=' + "{{ $singleData->id }}" + "&_token={{ csrf_token() }} ";
        StatusAjaxProcess(dataSet);

    });

    $('#hold_action').click(function() {
        var status = 0;
        var dataSet = 'status=' + status + '&uid=' + "{{ $singleData->id }}" + "&_token={{ csrf_token() }} ";
        StatusAjaxProcess(dataSet);

    });


    function StatusAjaxProcess(dataSet) {

        $('.action_button').css('cursor', 'wait');
        $('.action_button').attr('disabled', true);

        $.ajax({
            "url": "{{ route('user.status.update') }}",
            type: "POST",
            data: dataSet,
            dataType: "json",
            success: function(response) {
                toastr.success(response.message);
                $('#status_field').val(response.status);
                $('.action_button').css('cursor', 'pointer');
                $('.action_button').removeAttr('disabled');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error('A system error has been encountered. Please try again');
                $('.action_button').css('cursor', 'pointer');
                $('.action_button').removeAttr('disabled');
            }

        });
    }

    function DeleteRedirection() {
        window.location.href = "{{ url('users') }}";
    }


    $('#delete_action').click(function() {

        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        }, function() {

            $('.action_button').css('cursor', 'wait');
            $('.action_button').attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{route('user.destroy.userList')}}",
                data: {
                    "id": "{{ $singleData->id }}",
                    "_method": 'delete',
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastr.success(response);
                    const myTimeout = setTimeout(DeleteRedirection, 3000);

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    toastr.error(jqXHR.responseJSON);

                }

            });



        });



    });
</script>

@stop