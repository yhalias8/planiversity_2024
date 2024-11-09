@extends('backend.layouts.admin_backend')

@section('title')
Blog Posts
@endsection


@section('new-action-process')

<a class="new-account-btn" href="{{ route('user.blog.create.post') }}">Create New Blog Post
    <i class="fa fa-plus-circle"></i>
</a>

@endsection

@section('content')

<div class="container-fluid">

    <div class="row ">
        <div class="col-12">
            <h3 class="page-headr ml-2">Posts</h3>

            <div class="col-lg-12">
                <div class="card-box">
                    <div class="table-wrap">
                        <div class="table-responsive">

                            <table class="display_table table table-striped" id="post_list" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="6%">Image</th>
                                        <th width="15%">Title</th>
                                        <th width="12%">Category</th>
                                        <th width="10%">Author</th>
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
    var post_list = $('#post_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.blog.post.list') }}",
            "dataType": "json",
            "type": "GET",
            cache: false
        },
        "columns": [{
                "data": "DT_RowIndex",
                "className": "center",
                orderable: false,
                searchable: false
            },
            {
                "data": "featured_image",
                "className": "left",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    return "<a class='image-popup-vertical-fit' href='{{ asset('storage/uploads/images/blog/post') }}/" + data + "'> <img class='img-responsive table-image' src='{{ asset('storage/uploads/images/blog/post') }}/" + data + "' /></a>"
                }

            },
            {
                "data": "post_title"
            },
            {
                "data": "categoryList",
                "name": "categoryList",
                "className": "left",
                searchable: false,
                orderable: false,
                defaultContent: "",
                "render": function(data, type, row) {
                    var items = "";
                    $.each(row.categoryList, function(index, item) {
                        items += "<span class='badge badge-info mr-1 custom-badge'>" + item + "</span>";

                    });
                    return items;
                }

            },
            {
                "data": "author_name"
            },
            {
                "data": "post_status",
                "className": "center",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    let status = data == "published" ? 'badge-success' : 'badge-primary';
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


    $('#post_list tbody').on('click', '#edit', function() {
        var data = post_list.row($(this).parents('tr')).data();
        var url = "{{route('user.blog.edit.post', '')}}" + "/" + data.id;
        window.location.href = url;
    });


    $('#post_list tbody').on('click', '#delete', function() {
        var data = post_list.row($(this).parents('tr')).data();

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
                url: "{{route('user.blog.destroy.post')}}",
                data: {
                    "id": data.id,
                    "_method": 'delete',
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    toastr.success('Successfully Blog Post Deleted');
                    post_list.ajax.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(jqXHR.responseJSON);
                }

            });

        });

    });
</script>

@stop