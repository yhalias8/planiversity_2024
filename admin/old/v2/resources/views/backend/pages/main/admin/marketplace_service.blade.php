@extends('backend.layouts.admin_backend')

@section('title')
Marketplace Service
@endsection


@section('new-action-process')

<a class="new-account-btn" href="{{ url('marketplace-service/create') }}">Create New Service
    <i class="fa fa-plus-circle"></i>
</a>

@endsection

@section('content')

<div class="container-fluid">

    <div class="row ">
        <div class="col-12">
            <h3 class="page-headr ml-2">Services</h3>

            <div class="col-lg-12">
                <div class="card-box">
                    <div class="table-wrap">
                        <div class="table-responsive">

                            <table class="display_table table table-striped" id="service_list" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="6%">Image</th>
                                        <th width="15%">Title</th>
                                        <th width="12%">Category</th>
                                        <th width="10%">Author</th>
                                        <th width="8%">Price</th>
                                        <th width="8%">Status</th>
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
    var service_list = $('#service_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.service.list') }}",
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
                "data": "service_image",
                "className": "left",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    //return "<img class='img-responsive product-image' src='{{ asset('public/upload/category') }}/" + row.image + "' />"
                    return "<a class='image-popup-vertical-fit' href='{{ asset('storage/uploads/images/service') }}/" + data + "'> <img class='img-responsive table-image' src='{{ asset('storage/uploads/images/service') }}/" + data + "' /></a>"
                }

            },
            {
                "data": "service_title"
            },
            {
                "data": "category_name"
            },
            {
                "data": "author_name"
            },
            {
                "data": "regular_price"
            },
            {
                "data": "status",
                "className": "center",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    let status = data == "active" ? 'badge-success' : 'badge-primary';
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
            "defaultContent": " <td><div align='center'><a id='edit' class='btn btn-mini btn-info view_action' role='button' title='Edit Service'><i class='fa fa-eye'></i> </a>  <button id='delete' class='btn btn-mini btn-danger delete_action' title='Delete Service'><i class='fa fa-trash'></i> </a></div></td>"
        }],
        "fnDrawCallback": function() {
            $('.image-popup-vertical-fit').magnificPopup({
                type: 'image',
                closeOnContentClick: true,
                mainClass: 'mfp-img-mobile',
                image: {
                    verticalFit: true
                },

                zoom: {
                    enabled: true,
                    duration: 300 // don't foget to change the duration also in CSS
                },

            });
        }
    });


    $('#service_list tbody').on('click', '#edit', function() {
        var data = service_list.row($(this).parents('tr')).data();
        var url = "{{route('user.edit.service', '')}}" + "/" + data.id;
        window.location.href = url;
    });



    $('#service_list tbody').on('click', '#delete', function() {
        var data = service_list.row($(this).parents('tr')).data();


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
                url: "{{route('user.destroy.service')}}",
                data: {
                    "id": data.id,
                    "_method": 'delete',
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    toastr.success('Successfully Service Deleted');
                    service_list.ajax.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(jqXHR.responseJSON);
                }

            });



        });



    });
</script>

@stop