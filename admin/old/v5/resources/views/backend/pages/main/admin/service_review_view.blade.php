@extends('backend.layouts.admin_backend')

@section('title')
Service Reviews
@endsection

@section('new-action-process')

<a class="new-account-btn open" data-toggle="modal" data-target="#add_modal">Create New Review
    <i class="fa fa-plus-circle"></i>
</a>

@endsection

@section('content')

@php
$site_url = config('services.site.url');
@endphp

<div class="container-fluid">

    @include('backend.includes.segments.service-navbar',['segment' => 'review','service_id'=> $id])

    <div class="row ">
        <div class="col-12">
            <!-- <h3 class="page-headr ml-2"> Single Users</h3> -->
            <div class="card-box">
                <div class="table-wrap">
                    <div class="table-responsive">

                        <table class="display_table table table-striped" id="review_list" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%">#</th>
                                    <th width="12%">Review Date</th>
                                    <th width="12%">Review</th>
                                    <th width="12%">Rating</th>
                                    <th width="8%">Ip</th>
                                    <th width="8%">User</th>
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


<div class="modal fade modal-blur" data-backdrop="true" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Create Review</h4>
            </div>
            <form id="addForm">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">

                            <div class="form-group custom-group">
                                <p class="event-title">Review</p>
                                <textarea class="form-control" name="review" rows="3"></textarea>
                            </div>
                            <label id=" review-error" class="error review_error custom-label" for="review"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Rating</p>

                                <div class="rating-widget">
                                    <div data-rating="1">★</div>
                                    <div data-rating="2">★</div>
                                    <div data-rating="3">★</div>
                                    <div data-rating="4">★</div>
                                    <div data-rating="5">★</div>
                                </div>

                                <input type="hidden" class="form-control validy rating" name="rating">
                            </div>
                            <label id="rating-error" class="error rating_error custom-label" for="rating"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Status</p>
                                <select class="form-control" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">inactive</option>
                                </select>
                            </div>
                            <label id="status-error" class="error status_error custom-label" for="status"></label>

                        </div>

                    </div>

                    @csrf
                    <input type="hidden" id="service_id" name="service_id" value="{{$id}}" readonly>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary action_button add_submit_button">Process</button>
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
                <h4 class="modal-title" id="myModalLabel">Update Review</h4>
            </div>
            <form id="updateForm">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">

                            <div class="form-group custom-group">
                                <p class="event-title">Review</p>
                                <textarea class="form-control" name="review" id="review" rows="3"></textarea>
                            </div>
                            <label id=" review-error" class="error review_error custom-label" for="review"></label>

                            <div class="form-group custom-group">
                                <p class="event-title">Rating</p>

                                <div class="rating-widget">
                                    <div data-rating="1">★</div>
                                    <div data-rating="2">★</div>
                                    <div data-rating="3">★</div>
                                    <div data-rating="4">★</div>
                                    <div data-rating="5">★</div>
                                </div>

                                <input type="hidden" class="form-control validy rating" name="rating">
                            </div>
                            <label id="rating-error" class="error rating_error custom-label" for="rating"></label>

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
    function ratingCalculation(rating) {

        let start = 1;
        let end = 5;
        let items = "<div class='ratings' title='" + rating + " Star Rating'/>";
        // // $.each(row.permissonList, function(index, item) {
        // //     items += "<span class='badge badge-primary mr-1 custom-badge'>" + item + "</span>";
        // // });
        let star = "fa-star";
        for (var i = start; i <= end; i++) {
            if (i > rating) {
                items += "<i class='fa fa-star-o'></i>";
            } else {
                items += "<i class='fa fa-star'></i>";
            }

        }

        items += "</div'>";


        return items;

    }

    var review_list = $('#review_list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ route('user.review.list') }}",
            "dataType": "json",
            "type": "GET",
            "data": {
                "service_id": "{{ $id }} "
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
                "data": "review"
            },

            {
                "data": "rating",
                "name": "rating",
                "className": "left",
                defaultContent: "",
                "render": function(data, type, row) {
                    return ratingCalculation(data);
                }

            },

            {
                "data": "ip"
            },
            {
                "data": "user_name",
                "className": "left",
                // searchable: false,
                // orderable: false,
                "render": function(data, type, row) {
                    let status = data == "Guest" ? 'badge-danger' : 'badge-secondary';
                    return "<span class='badge " + status + " mr-1 custom-badge'>" + data + "</span>";
                }

            },

            {
                "data": "status",
                "className": "center",
                // searchable: false,
                // orderable: false,
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
            "defaultContent": " <td><div align='center'><a id='view_button' href='#update_modal' class='btn btn-mini btn-info view_action' role='button' data-toggle='modal' title='Edit Review'><i class='fa fa-eye'></i> </a>  <button id='delete' class='btn btn-mini btn-danger delete_action' title='Delete Order'><i class='fa fa-trash'></i> </button></div></td>"
        }],

    });

    function ratingValueSet(rating, flag = false) {
        if (flag) {
            $('.rating-widget div').removeClass('selected');
        }
        for (var i = 1; i <= rating; i++) {
            $('.rating-widget div[data-rating="' + i + '"]').addClass('selected');
        }
    }

    function initialReset() {
        $('.rating-widget div').removeClass('selected');
        $(".rating").val('');
    }

    $('.open').click(function() {
        initialReset();
    });

    $('#review_list tbody').on('click', '#view_button', function() {
        var data = review_list.row($(this).parents('tr')).data();

        $("#review").val(data.review);
        $(".rating").val(data.rating);
        $("#status").val(data.status);
        $("#id").val(data.id);
        ratingValueSet(data.rating, true);
    });

    $('.rating-widget div').click(function() {
        $('.rating-widget div').removeClass('selected');
        var rating = $(this).data('rating');
        $(".rating").val(rating);
        ratingValueSet(rating, "");

    });


    $("#addForm").validate({
        ignore: ':hidden:not(.validy)',
        rules: {

            review: {
                required: true,
                minlength: 3,
            },
            rating: {
                required: true,
            },
            status: {
                required: true,
            },

        },

        messages: {
            review: {
                required: "Please type review",
                minlength: "Minimum length need to be 3",
            },
            rating: {
                required: "Please select rating",
            },
            status: {
                required: "Please select a status",
            },

        },

        submitHandler: function(form) {

            $('.add_submit_button').css('cursor', 'wait');
            $('.add_submit_button').attr('disabled', true);

            $.ajax({
                url: "{{route('user.store.review')}}",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(res) {

                    $(form).trigger("reset");
                    initialReset
                    $('#add_modal').modal('hide');
                    toastr.success(res.message);
                    review_list.ajax.reload();

                    $('.add_submit_button').css('cursor', 'pointer');
                    $('.add_submit_button').removeAttr('disabled');

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

                    $('.add_submit_button').css('cursor', 'pointer');
                    $('.add_submit_button').removeAttr('disabled');

                }
            });
        }, // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }

    });

    $("#updateForm").validate({
        ignore: ':hidden:not(.validy)',
        rules: {

            review: {
                required: true,
                minlength: 3,
            },
            rating: {
                required: true,
            },
            status: {
                required: true,
            },

        },

        messages: {
            review: {
                required: "Please type review",
                minlength: "Minimum length need to be 3",
            },
            rating: {
                required: "Please select rating",
            },
            status: {
                required: "Please select a status",
            },

        },

        submitHandler: function(form) {

            $('.update_submit_button').css('cursor', 'wait');
            $('.update_submit_button').attr('disabled', true);

            $.ajax({
                url: "{{route('user.update.review')}}",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(res) {

                    $(form).trigger("reset");
                    $('#update_modal').modal('hide');
                    toastr.success(res.message);
                    review_list.ajax.reload();

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

    $('#review_list tbody').on('click', '#delete', function() {
        var data = review_list.row($(this).parents('tr')).data();


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