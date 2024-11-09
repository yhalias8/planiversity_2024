@extends('backend.layouts.admin_backend')

@section('title')
Profile
@endsection

@section('content')

@php
$user = auth()->user();
@endphp

<div class="container-fluid">

    <div class="row ">
        <div class="col-12">
            <h3 class="page-headr">Profile</h3>


            <section class="upcoming_events_sec profile">
                <div class="row">
                    <div class="col-xl-6">
                        <div class="upcoming_sec profile_section">
                            <h3>Update Profile Image</h3>

                            <div class="profile_image_section">
                                <div class="uploaded_image profile_image">
                                    <img src="{{ asset('profile').'/'.$user->picture }}" height="100" class="profile_picture_place">
                                </div>

                                <div class="profile_action_section">
                                    <button class="update_profile"> Update Profile Image </button>
                                </div>
                            </div>

                            <hr class="hr" />

                            <h3>Update Personal Information</h3>
                            <form id="personal_form">
                                <div class="row">

                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group frm-grp">
                                            <label class="mr-b-10">Name</label>
                                            <input type="text" name="name" id="name" class="profile-control form-control" value="{{ $user->name }}">
                                        </div>
                                        <label id="name-error" class="error name_error custom-label" for="name"></label>

                                        <div class="form-group frm-grp">
                                            <label class="mr-b-10">Email Address</label>
                                            <input type="text" name="email" id="email" class="profile-control form-control" value="{{ $user->email }}">
                                        </div>
                                        <label id="email-error" class="error email_error custom-label" for="email"></label>

                                        <div class="form-group frm-grp">
                                            <label class="mr-b-10">Telephone Number (Used for OTP)</label>
                                            <input type="text" name="mobile_no" id="mobile_no" class="profile-control form-control" value="{{ $user->mobile_no }}">
                                        </div>
                                        <label id="mobile_no-error" class="error mobile_no_error custom-label" for="mobile_no"></label>
                                    </div>

                                    @csrf
                                    @method('PUT')

                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group frm-grp mt-4">
                                            <button type="submit" class="update_information"> Update Information </button>
                                        </div>
                                    </div>


                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="upcoming_sec profile_section">
                            <h3>Change Password</h3>
                            <form id="password_form">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">

                                        <div class="form-group frm-grp">
                                            <label class="mr-b-10">Update Password</label>
                                            <div class="mb-3">
                                                <input type="password" name="password" id="toggle-password" class="profile-control form-control">
                                                <span toggle="#password-field" class="fa fa-fw field-icon toggle-password fa-eye-slash"></span>
                                            </div>
                                        </div>

                                        <label id="password-error" class="error password_error custom-label" for="password"></label>

                                        <div class="form-group frm-grp">
                                            <label class="mr-b-10">Confirm Password</label>
                                            <div class="mb-3">
                                                <input type="password" name="confirm_password" id="toggle-confirm" class="profile-control form-control">
                                                <span toggle="#password-confirm-field" class="fa fa-fw field-icon toggle-confirm fa-eye-slash"></span>
                                            </div>
                                        </div>

                                        <label id="confirm_password-error" class="error confirm_password_error custom-label" for="confirm_password"></label>
                                    </div>

                                    @csrf
                                    @method('PUT')

                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group frm-grp mt-4">
                                            <button type="submit" class="update_information update_password"> Update Password </button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </section>

        </div>
    </div>

</div>

<form id="formId" name="formId" action="" method="post" enctype="multipart/form-data" class="d-none">
    <input type="file" id="upload" value="Choose a file" accept="image/*" style="display: none;">
</form>

<button id="btn-crop-image" data-toggle="modal" class="d-none" data-target="#cropImagePop">Open Modal</button>

<div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">
                    Edit Profile Photo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="upload-demo" class="center-block"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="cropImageBtn" class="btn btn-primary">Save Photo</button>
                <button type="button" class="btn btn-danger btn-close-modal" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".toggle-password").click(function() {

        console.log('Password');

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $('#toggle-password');
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $(".toggle-confirm").click(function() {

        console.log('Password');

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $('#toggle-confirm');
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });


    const classToggle = (e) => {
        $('.event_trip_meeting_all a').removeClass("active");
        $(e).addClass("active");

        console.log('thisValue', e);
    }


    $('.update_profile').click(function() {
        $('#upload').click();
    });

    $('.uploaded_image').click(function() {
        $('#upload').click();
    });

    $("#personal_form").validate({

        rules: {
            name: {
                required: true,
                minlength: 3,
            },
            email: {
                required: true,
                email: true
            },
            mobile_no: {
                required: true,
                minlength: 5,
            },

        },
        messages: {
            name: {
                required: 'Please type your name',
                minlength: "Minimum length should be 3",
            },
            email: {
                required: 'Please type email address',
                email: 'Please type a valid email address'
            },
            mobile_no: {
                required: 'Please type your number',
                minlength: "Minimum number length should be 5",
            },
        },


        submitHandler: function(form) {

            $('.update_information').css('cursor', 'wait');
            $('.update_information').attr('disabled', true);

            $.ajax({
                url: "{{route('user.profile.update')}}",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {

                    toastr.success(response.message);

                    $('.update_information').css('cursor', 'pointer');
                    $('.update_information').removeAttr('disabled');


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


                    $('.update_information').css('cursor', 'pointer');
                    $('.update_information').removeAttr('disabled');

                }


            });


        }, // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }


    });

    $.validator.addMethod("strong_password", function(value, element) {
        let password = value;
        if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(password))) {
            return false;
        }
        return true;
    }, function(value, element) {
        let password = $(element).val();
        if (!(/^(.{8,20}$)/.test(password))) {
            return 'Password must be between 8 to 20 characters long.';
        } else if (!(/^(?=.*[A-Z])/.test(password))) {
            return 'Password must contain at least one uppercase.';
        } else if (!(/^(?=.*[a-z])/.test(password))) {
            return 'Password must contain at least one lowercase.';
        } else if (!(/^(?=.*[0-9])/.test(password))) {
            return 'Password must contain at least one digit.';
        } else if (!(/^(?=.*[@#$%&])/.test(password))) {
            return "Password must contain special characters from @#$%&.";
        }
        return false;
    });

    $("#password_form").validate({

        rules: {

            password: {
                strong_password: true,
                minlength: 8,
            },
            confirm_password: {
                required: true,
                minlength: 8,
                equalTo: "#toggle-password"
            },


        },
        messages: {

            password: {
                required: 'Please type your password',
                minlength: "Minimum password length should be 8 characters",
            },
            confirm_password: {
                required: "Please type confirm password",
                minlength: "Minimum password length should be 8 characters",
                equalTo: "Password mismatch"
            },
        },


        submitHandler: function(form) {

            $('.update_password').css('cursor', 'wait');
            $('.update_password').attr('disabled', true);

            $.ajax({
                url: "{{route('user.password.update')}}",
                type: "POST",
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {

                    $(form).trigger("reset");
                    toastr.success(response.message);

                    $('.update_password').css('cursor', 'pointer');
                    $('.update_password').removeAttr('disabled');


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


                    $('.update_password').css('cursor', 'pointer');
                    $('.update_password').removeAttr('disabled');

                }


            });




        }, // Do not change code below
        errorPlacement: function(error, element) {
            error.insertAfter(element.parent());
        }


    });




    var $uploadCrop,
        tempFilename,
        rawImg,
        imageId;

    function readFile(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.upload-demo').addClass('ready');
                //$('#btn-crop-image').click();
                $('#cropImagePop').modal('show');
                rawImg = e.target.result;

                // $uploadCrop.croppie('bind', {
                //     url: rawImg,
                // }).then(function() {
                //     console.log('jQuery bind complete');
                //     //$('.cr-slider').attr({'min':0.1, 'max':0.1});
                // });
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            alert("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    $uploadCrop = $('#upload-demo').croppie({
        viewport: {
            width: 200,
            height: 200,
            type: 'circle'
        },
        enableExif: true,
        showZoomer: true,
        enableResize: true,
        enableOrientation: true,
        mouseWheelZoom: 'ctrl',
    });


    $('#cropImagePop').on('shown.bs.modal', function() {
        $uploadCrop.croppie('bind', {
            url: rawImg
        }).then(function() {
            console.log('jQuery bind complete');
        });
    });

    $('#upload').on('change', function() {
        readFile(this);
    });

    $('#cropImageBtn').on('click', function(ev) {
        $uploadCrop.croppie('result', {
            type: 'base64',
            format: 'png'

        }).then(function(resp) {
            $.ajax({
                url: "{{route('user.profile.image.update')}}",
                method: 'POST',
                data: {
                    image: resp,
                    "_method": 'put',
                    "_token": "{{ csrf_token() }} "
                },
                success: function(data) {

                    $(".profile_picture_place").attr("src", "{{ asset('profile') }}/" + data.image);
                    $('#formId').trigger("reset");
                    $('#cropImagePop').modal('hide');
                    toastr.success(data.message);
                },
                error: function(jqXHR, textStatus, errorThrown) {

                    $('#formId').trigger("reset");
                    $('#cropImagePop').modal('hide');

                    toastr.error(jqXHR.responseJSON.message);

                }
            });
        });
    });
</script>

@stop