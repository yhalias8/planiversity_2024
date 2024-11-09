<?php
include_once("config.ini.php");

include("class/class.Plan.php");
$plan = new Plan();

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'welcome';
    header("Location:" . SITE . "login");
}

include('include_doctype.php');

$output = '';
if (isset($_POST['myprofile_submit'])) {
    $curr_password = $_POST['myprofile_cpass'];
    $new_password = $_POST['myprofile_npass'];
    $confirm_password = $_POST['myprofile_nrpass'];
    $result = $auth->changePassword($userdata['id'], $curr_password, $new_password, $confirm_password, null);

    $output = $result['message'];
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <title>Planiversity | Consolidated Travel Information Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= SITE; ?>images/favicon.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">

    <?php include('dashboard/include/css.php'); ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="<?= SITE ?>dashboard/js/jquery-3.6.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
    <script src="<?php echo SITE; ?>js/jquery.validate.min.js"></script>
    <script src="<?php echo SITE; ?>js/additional-methods.js"></script>
    <script>
        var SITE = '<?= SITE; ?>';
    </script>

    <!--Facebook Pixel Code-->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };

            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';

            n.queue = [];
            t = b.createElement(e);
            t.async = !0;

            t.src = v;
            s = b.getElementsByTagName(e)[0];

            s.parentNode.insertBefore(t, s)
        }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

        fbq('init', '871547440200746');

        fbq('track', 'PageView');
    </script>

    <noscript>
        <img height="1" width="1" src="https://www.facebook.com/tr?id=871547440200746&ev=PageView&noscript=1" />
    </noscript>
    <!--End Facebook Pixel Code-->
    <?php //include('include_google_analitics.php') 
    ?>

    <style>
        .btn-primary.focus,
        .btn-primary:focus {
            color: #000;
            background-color: transparent;
            border-color: #0062cc;
            box-shadow: none;
        }

        .modal-backdrop {
            z-index: 9;
        }

        #upload-demo {
            margin: auto;
        }

        .trip_section {
            display: flex;
            justify-content: space-between;
        }

        .trip_text:nth-child(2) {
            margin-left: 10px;
        }

        label.error {
            font-size: 14px;
            color: #db3737;
            position: relative;
            bottom: 10px;
        }
    </style>
</head>

<?php

$img = SITE . 'images/my_profile_icon.png';
if ($userdata['picture']) $img = SITE . 'ajaxfiles/profile/' . $userdata['picture'];
?>

<body>
    <section class="dashboard_mail_sec">
        <div class="container-fluid">
            <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
                <div class="app-header header-shadow">
                    <div class="app-header__logo">
                        <div class="logo-src">
                            <h3><span class="only-mob hamburger hamburger--elastic mobile-toggle-nav"><img src="<?= SITE; ?>/dashboard/images/arrow-planhd.svg"></span> Planiversity</h3>
                        </div>
                        <div class="header__pane ml-auto">
                            <div class="show-mob">
                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                    <span class="hamburger-box">
                                        <span class="hamburger-inner"></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="app-header__mobile-menu">
                        <div class=" btn-group">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                <span class="hamburger-inner"></span>
                            </a>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right onmobile-drop">
                                <ul class="list-unstyled">
                                    <?php
                                    if ($userdata['account_type'] == 'Admin') {
                                    ?>
                                        <li>
                                            <a class="dropdown-item drop-menu-item" href="<?= SITE; ?>apanel/users" target="_blank">
                                                Admin
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <li>
                                        <a href="<?= SITE; ?>contact-us" class="dropdown-item drop-menu-item" target="_blank">
                                            Contact Us
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= SITE ?>blog" class="dropdown-item drop-menu-item" target="_blank">
                                            Blog
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= SITE; ?>leave" class="dropdown-item drop-menu-item" target="_blank">
                                            Delete Account
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= SITE; ?>logout" class="dropdown-item drop-menu-item">
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="app-header__content">

                        <div class="app-header-right">
                            <div class="header-btn-lg pr-0">
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left user_account_infor">
                                            <div class="btn-group">
                                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                                    <i class="bi bi-chevron-down"></i>
                                                </a>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                                    <ul class="list-unstyled">
                                                        <?php
                                                        if ($userdata['account_type'] == 'Admin') {
                                                        ?>
                                                            <li>
                                                                <a class="dropdown-item drop-menu-item" href="<?= SITE; ?>apanel/users" target="_blank">
                                                                    Admin
                                                                </a>
                                                            </li>
                                                        <?php } ?>

                                                        <li>
                                                            <a href="<?= SITE; ?>contact-us" class="dropdown-item drop-menu-item" target="_blank">
                                                                Contact Us
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE ?>blog" class="dropdown-item drop-menu-item" target="_blank">
                                                                Blog
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE; ?>leave" class="dropdown-item drop-menu-item" target="_blank">
                                                                Delete Account
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="<?= SITE; ?>logout" class="dropdown-item drop-menu-item">
                                                                Logout
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-content-left  ml-3 header-user-info">
                                            <div class="widget-heading">
                                                <h6><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?></h6>
                                            </div>
                                        </div>
                                        <div class="header_users widget-content-right header-user-info ml-3">
                                            <div class="heade_user_img uploaded_image">
                                                <img src="<?= $img ?>" class="rounded-circle profile_picture_place">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="app-main">
                    <div class="row dashboard_main_row">
                        <div class="col-xl-2 left_menu_colom3">
                            <div class="navbar_left_sec">
                                <div class="app-sidebar sidebar-shadow left_menubar_sec">
                                    <div class="app-header__logo">
                                        <div class="logo-src"></div>
                                        <div class="header__pane ml-auto">
                                            <div>
                                                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                                    <span class="hamburger-box1">
                                                        <span class="hamburger-inner"></span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="scrollbar-sidebar">
                                        <?php
                                        $page_index = "";
                                        include('dashboard/include/navigation.php');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-10 right_content_colom9">
                            <div class="app-main__outer">
                                <div class="app-main__inner">
                                    <div class="">
                                        <div class="launch_commision_item_sec">
                                            <div class="dashboard_user_sec">
                                                <div class="user_header">
                                                    <div class="row align-self-center">
                                                        <div class="col-xl-6 col-9">
                                                            <div class="user_header_left_item">
                                                                <div class="user_header_list">
                                                                    <div class="user_header_img only-mob uploaded_image">
                                                                        <img src="<?= $img ?>">
                                                                    </div>
                                                                    <div class="user_header_text">
                                                                        <h4><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?> <span class="business_user"><?= $userdata['account_type']; ?> USER</span></h4>
                                                                        <h6>Customer#: <?= strtoupper($userdata['customer_number']) ?></h6>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-3">
                                                            <div class="start_a_plan_btn">
                                                                <a href="<?= SITE; ?>trip/itinerary-option"><span>Start a new plan</span>
                                                                    <div class="plus-icon"><img src="<?= SITE; ?>/dashboard/images/plus-circle.svg"></div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <section class="heading_page">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div>
                                                                <p class="small-logo-title pt-4">PLANIVERSITY</p>
                                                                <h4 class="page-title pl-0 pt-0">Security</h4>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </section>

                                                <section class="upcoming_events_sec profile">
                                                    <div class="row">
                                                        <div class="col-xl-6">
                                                            <div class="upcoming_sec profile_section">
                                                                <h3>Update Profile Image</h3>

                                                                <div class="profile_image_section">
                                                                    <div class="uploaded_image profile_image">
                                                                        <img src="<?= $img ?>" height="100" class="profile_picture_place">
                                                                    </div>

                                                                    <div class="profile_action_section">
                                                                        <button class="update_profile"> Update Profile Image </button>
                                                                    </div>
                                                                </div>

                                                                <hr />

                                                                <h3>Update Personal Information</h3>
                                                                <form id="personal_form">
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group frm-grp">
                                                                                <label class="mr-b-10">Email Address</label>
                                                                                <input type="text" name="email_address" id="email_address" class="profile-control form-control">
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group frm-grp">
                                                                                <label class="mr-b-10">Telephone Number (Used for OTP)</label>
                                                                                <input type="text" name="mobile_number" id="mobile_number" class="profile-control form-control">
                                                                            </div>
                                                                        </div>


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
                                                                        </div>

                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group frm-grp">
                                                                                <label class="mr-b-10">Confirm Password</label>
                                                                                <div class="mb-3">
                                                                                    <input type="password" name="confirm_password" id="toggle-confirm" class="profile-control form-control">
                                                                                    <span toggle="#password-confirm-field" class="fa fa-fw field-icon toggle-confirm fa-eye-slash"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

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
                                </div>
                            </div>
                            <div class="dashboard_footer">
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="privacy_and_policy_list">
                                            <a href="">Privacy</a>
                                            <a href="">Terms of Service</a>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="copy_write">
                                            <p>&copy;Copyright. 2015 - <?= date('Y'); ?> Planiversity, LLC. All Rights Reserved.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($userdata['account_type'] != 'Admin') { ?>
                                <!-- <div class="currnet-planboxt show-mob">
                                    <div class="current-plan">
                                        <div class="form-row justify-content-center pad-20">
                                            <div class="col-lg-8 col-7">
                                                <h4> Current Plan </h4>
                                                <h6>Expires 25/08/2022</h6>
                                            </div>
                                            <div class="col-lg-4 col-5 align-self-center">
                                                <div class="personal-userbtn"> <?= strtoupper($userdata['account_type']); ?> USER </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-lg-12">
                                                <div class="add-new-event-btn"> Add New Event <div class="add-evnt-icon"><img src="https://www.planiversity.com//dashboard/images/plus-circle.svg"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <?php if ($userdata['account_type'] != 'Admin') { ?>
        <!-- <div class="mobile-fixed-footer show-mob">
            <div class="form-row">
                <div class="col-lg-3 col-3">
                    <div class="footer-box active">
                        <a href="<?= SITE ?>welcome">
                            <div class="footer-icon"><img src="<?= SITE; ?>/dashboard/images/dashboard.svg"></div>
                            <p>Dashboard</p>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-3">
                    <div class="footer-box">
                        <a href="#">
                            <div class="footer-icon"><img src="<?= SITE; ?>/dashboard/images/calendar.svg"></div>
                            <p>My Plans </p>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-3">
                    <div class="footer-box">
                        <a href="#">
                            <div class="footer-icon"><img src="<?= SITE; ?>/dashboard/images/profile.svg"></div>
                            <p>My Profile</p>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-3">
                    <div class="footer-box">
                        <a href="#">
                            <div class="footer-icon"><img src="<?= SITE; ?>/dashboard/images/settings.svg"></div>
                            <p>Settings</p>
                        </a>
                    </div>
                </div>
            </div>
        </div> -->
    <?php } ?>

    <form id="formId" name="formId" action="" method="post" enctype="multipart/form-data" class="d-none">
        <input type="file" id="upload" value="Choose a file" accept="image/*" style="display: none;">
    </form>

    <button id="btn-crop-image" data-toggle="modal" class="d-none" data-target="#cropImagePop">Open Modal</button>

    <div class="modal fade" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        Edit Photo</h4>
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

    <?php
    include('dashboard/include/mobile-message.php');
    ?>

    <script src="<?php echo SITE; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/popper.min.js"></script>
    <script src="<?= SITE ?>dashboard/js/main.js"></script>
    <script src="<?= SITE ?>dashboard/js/jquery-ui.js"></script>

    <script>
        $(function() {
            getProfileData();
        });

        function getProfileData() {

            $.ajax({
                url: SITE + "ajaxfiles/profile_info/load_data.php",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $('#email_address').val(response.data.email);
                    $('#mobile_number').val(response.data.mobile_no);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error('No data found');
                }

            });

        }

        $("#personal_form").validate({

            rules: {

                email_address: {
                    required: true,
                    email: true
                },
                mobile_number: {
                    required: true,
                    minlength: 5,
                },


            },
            messages: {

                email_address: {
                    required: 'Please type email address',
                    email: 'Please type a valid email address'
                },
                mobile_number: {
                    required: 'Please type your number',
                    minlength: "Minimum number length should be 5",
                },
            },


            submitHandler: function(form) {


                $('.update_information').css('cursor', 'wait');
                $('.update_information').attr('disabled', true);


                $.ajax({
                    url: SITE + "ajaxfiles/profile_info/update_profile.php",
                    type: "POST",
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function(response) {

                        toastr.success(response.message);

                        $('.update_information').css('cursor', 'pointer');
                        $('.update_information').removeAttr('disabled');


                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                        var res = jqXHR.responseJSON;
                        toastr.error(res.message);

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
                    url: SITE + "ajaxfiles/profile_info/update_password.php",
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

                        var res = jqXHR.responseJSON;
                        toastr.error(res.message);

                        $('.update_information').css('cursor', 'pointer');
                        $('.update_information').removeAttr('disabled');

                    }


                });




            }, // Do not change code below
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            }


        });

        $(document).ready(function() {


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


            $(document).on("click", "a.fc-day-grid-event", function() {
                event.stopImmediatePropagation();
                event.stopPropagation();
                var url = $(this).attr("href");
                window.open(url, "_blank");
                return false;
            });

            $('.update_profile').click(function() {
                $('#upload').click();
            })

            $('.uploaded_image').click(function() {
                $('#upload').click();
            })

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
                        url: 'ajaxfiles/upload_profile.php',
                        method: 'POST',
                        data: {
                            image: resp,
                            useId: <?= $userdata['id'] ?>
                        },
                        success: function(data) {

                            $(".profile_picture_place").attr("src", data);
                            //$('#upload-demo').croppie('destroy');
                            $('#formId').trigger("reset");
                            $('#cropImagePop').modal('hide');
                            toastr.success('Profile Picture Successfully Updated');
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>