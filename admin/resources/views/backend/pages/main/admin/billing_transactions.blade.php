@extends('backend.layouts.admin_backend')

@section('title')
Billing Transactions
@endsection

@section('content')

<div class="container-fluid">

    @include('backend.includes.segments.transactions-navbar',['segment' => 'billing'])

    <div class="row ">
        <div class="col-12">
            <h3 class="page-headr ml-2">Billing Transactions</h3>

            <div class="col-lg-12">
                <div class="card-box">
                    <div class="table-wrap">
                        <div class="table-responsive">

                            <table class="display_table table table-striped" id="payment_list" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="12%">Date</th>
                                        <th width="10%">User</th>
                                        <th width="8%">Plan Type</th>
                                        <th width="10%">Payment Type</th>
                                        <th width="10%">Amount</th>
                                        <th width="10%">IP</th>
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

                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">User Name</p>
                                <input type="text" class="form-control" id="user_name" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">User Email</p>
                                <input type="text" class="form-control" id="user_email" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-4">
                            <div class="form-group custom-group">
                                <p class="event-title">User Type</p>
                                <input type="text" class="form-control" id="user_type" readonly>
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
    var payment_list = $('#payment_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.billing.transactions') }}",
            "dataType": "json",
            "type": "GET",
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
                "data": "user_name",
            },
            {
                "data": "plan_type",
            },
            {
                "data": "payment_type",
            },
            {
                "data": "amount"
            },
            {
                "data": "ip_address"
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
        $('#user_name').val(data.user_name);
        $('#user_email').val(data.user_email);
        $('#user_type').val(data.user_type);

    });
</script>

@stop