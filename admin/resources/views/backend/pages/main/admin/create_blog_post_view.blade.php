@extends('backend.layouts.admin_backend')

@section('title')
Add New Blog Post
@endsection

@section('content')

@php
$site_url = config('services.site.url');
@endphp

<div class="container-fluid">

    <div class="row ">
        <div class="col-12">
            <!-- <h3 class="page-headr ml-2"> Single Users</h3> -->

            <!-- Top Status -->
            <form id="blog_post_form_add">
                <div class="row main-content">

                    <div class="col-lg-8">


                        <div class="card-box section-content form-content">
                            <div class="section-header">
                                <h3 class="section-title">Add New Blog Post</h3>
                            </div>


                            <div class="row">

                                <div class="col-lg-12">
                                    <legend class="form-legend">Post Information</legend>
                                </div>

                                <div class="col-lg-12">
                                    <label class="event-form-label">Post Title
                                        <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="The main title of your post. It will be your primary page heading (h1) and the default SEO Page Title."></i>
                                    </label>

                                    <div class="form-group">
                                        <input name="post_title" type="text" class="account-form-control form-control input-lg inp1" placeholder="Post Title">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="event-form-label post-content-label">
                                        <div>
                                            Post Contet
                                            <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="This is the main content of your blog post. This is where all the magic happens."></i>
                                        </div>

                                        <div class="content_words">
                                            <p> <span id="wordCount">0</span> WORDS </p>
                                        </div>
                                    </label>

                                    <div class="form-group">
                                        <textarea id="post_content" name="post_content" class="form-control summernote"></textarea>
                                    </div>

                                    <label id="post_content-error" class="error post_content_error custom-label" for="post_content"></label>


                                </div>

                                <div class="col-lg-12">

                                    <label class="event-form-label">Slug / URL
                                        <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="The post slug is a version of your title without spaces. The main use is for the URL. If you change this, the URL of your post will change. Do so with caution. If the post has been live for a while consider creating a 301 redirect from the old page to the new page with your hosting account."></i>
                                    </label>
                                    <div class="form-group">
                                        <input name="post_slug" type="text" class="account-form-control form-control input-lg inp1" placeholder="Slug">
                                    </div>
                                    <label id="post_slug-error" class="error post_slug_error custom-label" for="post_slug"></label>


                                </div>

                                <div class="col-lg-6">
                                    <label class="event-form-label">Post Status
                                        <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="This determines if your post is live. Draft posts are not live and do not show your blog. Published posts are live on your blog. "></i>
                                    </label>
                                    <div class="form-group">
                                        <select class="form-control" name="post_status" id="post_status">
                                            <option value="">Select a option</option>
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="visibility" style="display: none;">
                                        <label class="event-form-label">Post Visibility
                                            <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="This determines if your post is public or private. Public posts will show in your blog list. Private posts will not show in your blog list but can be accessed with the direct url." aria-describedby="tooltip322599"></i>
                                        </label>
                                        <div class="form-group">
                                            <select class="form-control" name="post_type">
                                                <option value="">Select a option</option>
                                                <option value="public" selected>Public</option>
                                                <option value="private">Private</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="visibility" style="display: none;">
                                        <label class="event-form-label">Published At
                                            <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="This date determines your Posts published date. It also determines the sort order of all Published Posts. " aria-describedby="tooltip322599"></i>
                                        </label>

                                        <div class="form-group">
                                            <input type="text" name="published_at" id="published_at" class="account-form-control form-control input-lg inp1" />
                                        </div>
                                        <label id="published_at-error" class="error published_at_error custom-label" for="published_at"></label>

                                    </div>
                                </div>


                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <legend class="form-legend">SEO Information</legend>
                                </div>

                                <div class="col-lg-12">

                                    <label class="event-form-label">Keyword
                                        <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="This is usually a 2-5 word phrase."></i>
                                    </label>
                                    <div class="form-group">
                                        <input name="seo_keyword" type="text" class="account-form-control form-control input-lg inp1" placeholder="Primary keyword">
                                    </div>

                                    <label class="event-form-label">SEO Title
                                        <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="The page title of the post. Defaults to Post Title. This is important for SEO and shows as the first line of your listing in search engine results."></i>
                                    </label>
                                    <div class="form-group custom-group">
                                        <input type="text" class="form-control" name="seo_title">
                                    </div>
                                    <label id="seo_title-error" class="error seo_title_error custom-label" for="seo_title"></label>

                                    <label class="event-form-label">SEO Description
                                        <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="The meta description of the post. It defaults to the beginning of the post content. This is the text that shows at the bottom of your search engine listing. It should be a simple to read summary of your post to inform the readers what it's about."></i>
                                    </label>
                                    <div class="form-group custom-group">
                                        <textarea type="text" class="form-control" name="seo_description" rows="4"></textarea>
                                    </div>
                                    <label id="seo_description-error" class="error seo_description_error custom-label" for="seo_description"></label>


                                </div>

                            </div>



                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-box">

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="form-check mt-3 mb-3">
                                        <label class="form-check-label">
                                            <input class="form-check-input form-check-input-lg" type="checkbox" name="pin_post" id="pin_post" value="1">
                                            Pin This Blog Post
                                            <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="This determines if your post is pinned to the top above other posts."></i>
                                        </label>
                                    </div>

                                </div>

                                <div class="col-lg-12">
                                    <label class="event-form-label">Author
                                        <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="This is the Author of the post. This can show in your post summary on the blog home and category pages. It will also show toward the top of the blog post."></i>
                                    </label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="author_id" placeholder="Author Name" value="" list="author_id-datalist" autocomplete="off">
                                        <datalist id="author_id-datalist" class="author">
                                        </datalist>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="event-form-label">Blog Category
                                        <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="Select the categories in which you would like your post to show. If you do not choose any categories your post will still show on the blog home page. Visit the Categories page to manage your Categories."></i>
                                    </label>
                                    <div class="form-group">
                                        <div class="blog-category"></div>
                                    </div>

                                    <label id="categories[]-error" class="error custom-label" for="categories[]"></label>
                                </div>

                                <div class="col-lg-12">
                                    <label class="event-form-label">Featured Image
                                        <i class="fa fa-question-circle info-tooltip" data-html="false" data-toggle="tooltip" title="" data-original-title="Upload a Featured Image for your post. This can show in your post summary on the blog home and category pages. It will also show at the top of the blog post. Ideal size is 1400px wide x 788px high. (Note: Any height is okay, but if you want your grid to line up nicely make them all the same height)"></i>
                                    </label>
                                    <div class="form-group">
                                        <input type="file" name="image" class="dropify" data-height="180" data-max-file-size="2M" data-allowed-file-extensions="jpg jpeg png webp">
                                    </div>
                                    <label id="image-error" class="error image_error custom-label" for="image"></label>
                                </div>

                                @csrf

                                <div class="col-lg-12">

                                    <div class="form-group d-flex justify-content-between">
                                        <button type="button" class="btn btn-info process_button submit_button" id="reset_buttton"> <i class="fa fa-history" aria-hidden="true"></i>
                                            Reset</button>
                                        <button type="submit" class="btn btn-primary process_button submit_button"> <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                            Process</button>
                                    </div>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>
            </form>
        </div>
    </div>

</div>

<script>
    $('.dropify').dropify();

    var today = moment().format('YYYY-MM-DD HH:mm A');

    flatpickr("#published_at", {
        dateFormat: "Y-m-d h:i K",
        enableTime: true,
        time_24hr: false,
        // onClose: function(selectedDates, dateStr, instance) {
        //     instance.close(); // Close the calendar when the "Done" button is clicked
        // }
    });

    $('#published_at').val(today);


    function getAuthorList() {

        var items = "";
        $.getJSON("{{ route('user.authorlist.author') }}", function(data) {
            $.each(data, function(index, item) {
                items += "<option id='" + item.id + "' value='" + item.author_name + "'>" + item.author_name + "</option>";
            });

            $(".author").html(items);

        });

    }

    function getCategoryList() {
        var items = "";

        $.getJSON("{{ route('user.blog.categorylist.category') }}", function(data) {
            $.each(data, function(index, item) {
                items += '<div class="form-check">';
                items += '<input class="form-check-input form-check-input-lg" type="checkbox" name="categories[]" id="item_' + item.id + '" value="' + item.id + '">';
                items += '<label class="form-check-label category-label" for="item_' + item.id + '">' + item.category_name + '</label>';
                items += '</div>';
            });

            $(".blog-category").html(items);
        });
    }


    $(document).ready(function() {
        getAuthorList();
        getCategoryList();
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#post_status').on('change', function() {
        var status = $(this).val();
        if (status == 'published') {
            $('.visibility').show();
        } else {
            $('.visibility').hide();
        }
    });


    $.validator.addMethod('author_required', function(value, element, param) {
        var id = $(param).find('option').filter(
            function() {
                return $.trim($(this).text()) === value;
            }).attr('id');

        if (id) {
            return true;
        } else {
            return false;
        }
    }, "");

    var form_validate = $("#blog_post_form_add").validate({
        ignore: ":hidden:not(.summernote),.note-editable.panel-body",
        rules: {
            post_title: {
                required: true,
            },
            post_content: {
                required: true,
            },
            post_slug: {
                required: true,
            },
            post_status: {
                required: true,
            },
            post_type: {
                required: true,
            },
            published_at: {
                required: true,
            },
            author_id: {
                required: true,
                author_required: '#author_id-datalist',
            },
            'categories[]': {
                required: true,
            },
            image: {
                required: true,
            },

        },
        messages: {

            post_title: {
                required: 'Please type post title'
            },
            post_content: {
                required: 'Please type post content'
            },
            post_slug: {
                required: 'Please type post slug'
            },
            post_status: {
                required: 'Please select post status'
            },
            post_type: {
                required: 'Please select post type'
            },
            published_at: {
                required: 'Please select post date'
            },
            author_id: {
                required: "Please select a author name",
                category_required: "Please select valid author name"
            },
            'categories[]': {
                required: 'Please select a post category'
            },
            image: {
                required: "Please upload featured image",
            },
        },

        submitHandler: function(form) {

            $('.submit_button').css('cursor', 'wait');
            $('.submit_button').attr('disabled', true);

            var author_id_key = $('input[name="author_id"]').val();
            var author_id = $('#author_id-datalist').find('option').filter(function() {
                return $.trim($(this).text()) === author_id_key;
            }).attr('id');

            var formData = new FormData($('#blog_post_form_add')[0]);
            formData.append('author', author_id);

            $.ajax({
                url: "{{ route('user.blog.store.post') }}",
                type: "POST",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {

                    console.log('response', response);

                    $(form).trigger("reset");
                    $('.note-editable').html('');
                    $('.dropify-clear').trigger("click");
                    $('#wordCount').text('0');
                    $('#post_status').val('');
                    $('.visibility').hide();
                    //$('#published_at').val(today);

                    toastr.success('Successfully Blog Post Created');

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


    $(document).on('click', '#reset_buttton', function() {

        swal({
            title: "Are you sure,to reset?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, reset it!",
            closeOnConfirm: true
        }, function() {

            $('#blog_post_form_add').trigger("reset");
            $('.note-editable').html('');
            $('.dropify-clear').trigger("click");
            $('#wordCount').text('0');
            $('#post_status').val('');
            $('.visibility').hide();
            $('#published_at').val(today);

            toastr.success('Form Reseted');

        });



    });


    var myElement = $('#post_content');

    myElement.summernote({
        // See: http://summernote.org/deep-dive/
        height: 250,
        callbacks: {
            // Register the `onChnage` callback in order to listen to the changes of the
            // Summernote editor. You can also use the event `summernote.change` to handle
            // the change event as follow:
            //   myElement.summernote()
            //     .on("summernote.change", function(event, contents, $editable) {
            //       // ...
            //     });
            onChange: function(contents, $editable) {
                // Note that at this point, the value of the `textarea` is not the same as the one
                // you entered into the summernote editor, so you have to set it yourself to make
                // the validation consistent and in sync with the value.
                myElement.val(myElement.summernote('isEmpty') ? "" : contents);

                // You should re-validate your element after change, because the plugin will have
                // no way to know that the value of your `textarea` has been changed if the change
                // was done programmatically.
                form_validate.element(myElement);
            },
            onKeyup: function(e) {
                //var content = myElement.summernote('getText');
                var content = e.currentTarget.innerText;
                console.log('Counting', content);
                if (content) {
                    var wordCount = content.trim().split(/\s+/).length;
                    $('#wordCount').text(wordCount);
                }
            }
        }
    });
</script>

@stop