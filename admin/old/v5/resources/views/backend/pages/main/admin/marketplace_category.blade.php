@extends('backend.layouts.admin_backend')

@section('title')
Marketplace Category
@endsection


@section('new-action-process')

<a class="new-account-btn" data-toggle="modal" data-target="#add_modal">Create New Category
    <i class="fa fa-plus-circle"></i>
</a>

@endsection

@section('content')

<div class="container-fluid">

    <div class="row ">
        <div class="col-12">
            <h3 class="page-headr ml-2">Categories</h3>

            <div class="col-lg-12">
                <div class="card-box">
                    <div class="table-wrap">
                        <div class="table-responsive">

                            <table class="display_table table table-striped" id="category_list" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="10%">Image</th>
                                        <th width="15%">Category Name</th>
                                        <th width="12%">Slug</th>
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

<div class="modal fade modal-blur" data-backdrop="true" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Create Category</h4>
            </div>
            <form id="addForm">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-8 col-lg-8 col-sm-12">

                            <div class="form-group custom-group">
                                <p class="event-title">Category Name</p>
                                <input type="text" class="form-control" name="category_name">
                            </div>
                            <label id="category_name-error" class="error category_name_error custom-label" for="category_name"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Slug</p>
                                <input type="text" class="form-control" name="slug">
                            </div>
                            <label id="slug-error" class="error slug_error custom-label" for="slug"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Status</p>
                                <select class="form-control" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">inactive</option>
                                </select>
                            </div>
                            <label id="status-error" class="error status_error custom-label" for="status"></label>

                        </div>

                        <div class="col-md-4 col-lg-4 col-sm-12">

                            <div class="form-group custom-group">
                                <label class="control-label">Image</label>
                                <input type="file" name="image" class="dropify" data-height="180" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png" />
                            </div>
                            <label id="image-error" class="error image_error custom-label" for="image"></label>

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
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Update Category</h4>
            </div>
            <form id="updateForm">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">

                            <div class="form-group custom-group">
                                <p class="event-title">Category Name</p>
                                <input type="text" class="form-control" name="category_name" id="category_name">
                            </div>
                            <label id="category_name-error" class="error category_name_error custom-label" for="category_name"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Slug</p>
                                <input type="text" class="form-control" name="slug" id="slug">
                            </div>
                            <label id="slug-error" class="error slug_error custom-label" for="slug"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Status</p>
                                <select class="form-control" name="status" id="status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">inactive</option>
                                </select>
                            </div>
                            <label id="status-error" class="error status_error custom-label" for="status"></label>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-8 col-12">

                            <div class="form-group custom-group">
                                <label class="control-label">Image</label>
                                <input type="file" name="image" class="dropify" data-height="180" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png" />
                            </div>
                            <label id="image-error" class="error image_error custom-label" for="image"></label>

                        </div>

                        <div class="col-md-4 col-12">

                            <div class="form-group custom-group">
                                <label class="control-label">Current Image</label>
                                <div id="image_place"></div>

                            </div>

                        </div>

                    </div>

                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id" name="id" readonly>
                    <input type="hidden" name="current_image" id="current_image" readonly>
                </div>
                <div class="modal-footer">
                    <button type="update" class="btn btn-primary action_button update_submit_button">Process</button>
                    <button type="button" class="btn btn-danger action_button btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $('.dropify').dropify();

    var category_list = $('#category_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.categeory.list') }}",
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
                "data": "category_image",
                "className": "left",
                searchable: false,
                orderable: false,
                "render": function(data, type, row) {
                    //return "<img class='img-responsive product-image' src='{{ asset('public/upload/category') }}/" + row.image + "' />"
                    return "<a class='image-popup-vertical-fit' href='{{ asset('storage/uploads/images/category') }}/" + data + "'> <img class='img-responsive table-image' src='{{ asset('storage/uploads/images/category') }}/" + data + "' /></a>"
                }

            },
            {
                "data": "category_name"
            },
            {
                "data": "slug"
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
            "defaultContent": " <td><div align='center'><a id='view_button' href='#update_modal' class='btn btn-mini btn-info view_action' role='button' data-toggle='modal' title='Edit Category'><i class='fa fa-eye'></i> </a>  <button id='delete' class='btn btn-mini btn-danger delete_action' title='Delete Category'><i class='fa fa-trash'></i> </a></div></td>"
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


    $('#category_list tbody').on('click', '#view_button', function() {
        var data = category_list.row($(this).parents('tr')).data();

        $("#category_name").val(data.category_name);
        $("#slug").val(data.slug);
        $("#status").val(data.status);
        $("#id").val(data.id);
        $("#current_image").val(data.category_image);

        $("#image_place").html("<img class='img-responsive product-image-mid' src='{{ asset('/storage/uploads/images/category') }}/" + data.category_image + "' />");

    });


    $("#addForm").validate({
        rules: {

            category_name: {
                required: true,
                minlength: 3,

            },
            slug: {
                required: true,
                minlength: 3,
            },
            status: {
                required: true,
            },
            image: {
                required: true,
            },
        },

        messages: {
            category_name: {
                required: "Please type category name",
                minlength: "Minimum length need to be 3",
            },
            slug: {
                required: "Please type slug name",
                minlength: "Minimum length need to be 3",
            },
            status: {
                required: "Please select a status",
            },
            image: {
                required: "Please upload image",
            },
        },

        submitHandler: function(form) {

            $('.submit_button').css('cursor', 'wait');
            $('.submit_button').attr('disabled', true);

            var formData = new FormData($('#addForm')[0]);

            $.ajax({
                url: "{{route('user.store.category')}}",
                type: "POST",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {

                    $(form).trigger("reset");
                    $('#add_modal').modal('hide');
                    $('.dropify-clear').trigger("click");
                    toastr.success(res.message);
                    category_list.ajax.reload();

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


    $("#updateForm").validate({
        rules: {

            category_name: {
                required: true,
                minlength: 3,

            },
            slug: {
                required: true,
                minlength: 3,
            },
            status: {
                required: true,
            },

        },

        messages: {
            category_name: {
                required: "Please type category name",
                minlength: "Minimum length need to be 3",
            },
            slug: {
                required: "Please type slug name",
                minlength: "Minimum length need to be 3",
            },
            status: {
                required: "Please select a status",
            },

        },

        submitHandler: function(form) {

            $('.update_submit_button').css('cursor', 'wait');
            $('.update_submit_button').attr('disabled', true);

            var formData = new FormData($('#updateForm')[0]);

            $.ajax({
                url: "{{route('user.update.category')}}",
                type: "POST",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {

                    $(form).trigger("reset");
                    $('#update_modal').modal('hide');
                    $('.dropify-clear').trigger("click");
                    toastr.success(res.message);
                    category_list.ajax.reload();

                    $('.update_submit_button').css('cursor', 'pointer');
                    $('.update_submit_button').removeAttr('disabled');

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

                    $('.update_submit_button').css('cursor', 'pointer');
                    $('.update_submit_button').removeAttr('disabled');

                }
            });
        }, // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }

    });


    $('#category_list tbody').on('click', '#delete', function() {
        var data = category_list.row($(this).parents('tr')).data();


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
                url: "{{route('user.destroy.category')}}",
                data: {
                    "id": data.id,
                    "_method": 'delete',
                    "_token": "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function(response) {
                    toastr.success('Successfully Category Deleted');
                    category_list.ajax.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(jqXHR.responseJSON);
                }

            });



        });



    });
</script>

@stop