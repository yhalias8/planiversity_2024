@extends('backend.layouts.admin_backend')

@section('title')
Blog Categories
@endsection


@section('new-action-process')

<a class="new-account-btn" data-toggle="modal" data-target="#add_modal">Add New Category
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
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Add Category</h4>
            </div>
            <form id="addForm">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">

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

                            <div class="form-group custom-group">
                                <p class="event-title">SEO Title</p>
                                <input type="text" class="form-control" name="seo_title">
                            </div>
                            <label id="seo_title-error" class="error seo_title_error custom-label" for="seo_title"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">SEO Description</p>
                                <textarea type="text" class="form-control" name="seo_description"></textarea>
                            </div>
                            <label id="seo_description-error" class="error seo_description_error custom-label" for="seo_description"></label>

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

                            <div class="form-group custom-group">
                                <p class="event-title">SEO Title</p>
                                <input type="text" class="form-control" name="seo_title" id="seo_title">
                            </div>
                            <label id="seo_title-error" class="error seo_title_error custom-label" for="seo_title"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">SEO Description</p>
                                <textarea type="text" class="form-control" name="seo_description" id="seo_description"></textarea>
                            </div>
                            <label id="seo_description-error" class="error seo_description_error custom-label" for="seo_description"></label>

                        </div>

                    </div>

                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id" name="id" readonly>
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
    var category_list = $('#category_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.blog.category.list') }}",
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

    });


    $('#category_list tbody').on('click', '#view_button', function() {
        var data = category_list.row($(this).parents('tr')).data();

        $("#category_name").val(data.category_name);
        $("#slug").val(data.slug);
        $("#status").val(data.status);
        $("#seo_title").val(data.seo_title);
        $("#seo_description").val(data.seo_description);
        $("#id").val(data.id);

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

            $('.submit_button').css('cursor', 'wait');
            $('.submit_button').attr('disabled', true);

            $.ajax({
                url: "{{route('user.blog.store.category')}}",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',

                success: function(res) {

                    $(form).trigger("reset");
                    $('#add_modal').modal('hide');

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

            $.ajax({
                url: "{{route('user.blog.update.category')}}",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(res) {

                    $(form).trigger("reset");
                    $('#update_modal').modal('hide');
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
                url: "{{route('user.blog.destroy.category')}}",
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