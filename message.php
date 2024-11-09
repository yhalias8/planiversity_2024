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
    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="<?= SITE ?>dashboard/js/jquery-3.6.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
    <script src="<?php echo SITE; ?>js/jquery.validate.min.js"></script>
    <script src="<?php echo SITE; ?>js/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
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

        #user_modal {
            z-index: 9999;
        }

        .modal {
            margin-bottom: 0px !important;
            top: 2%;
            z-index: 9999;
        }

        .modal-backdrop {
            background-color: #000;
            z-index: 1111;
        }

        .modal-blur {
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
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

        textarea.error {
            border: 1px solid #f19393;
        }

        label.error {
            font-size: 14px;
            color: #db3737;
            position: relative;
            float: left;
            font-weight: 400;
        }

        .app-inner-layout .app-inner-layout__wrapper {
            min-height: 70vh;
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

                    <?php include_once("includes/top_right_navigation.php"); ?>


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

                                                <div class="app-inner-layout chat-layout">

                                                    <div class="app-inner-layout__wrapper">
                                                        <div class="app-inner-layout__content card">
                                                            <div class="table-responsive">
                                                                <div class="app-inner-layout__top-pane">
                                                                    <div class="receipt_head message_board" style="display:none;">
                                                                        <div class="pane-left">
                                                                            <div class="avatar-icon-wrapper mr-2">
                                                                                <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg">
                                                                                </div>
                                                                                <div class="avatar-icon avatar-icon-xl rounded">
                                                                                    <img width="82" id="receipt_photo" src="">
                                                                                </div>
                                                                            </div>
                                                                            <h4 class="mb-0 text-nowrap" id="receipt_name">...</h4>
                                                                        </div>
                                                                        <div class="pane-right">
                                                                            <div class="btn-group">

                                                                                <button type="button" class="ml-2 btn message-refresh receipt-button">
                                                                                    <span class="mr-1">
                                                                                        <i class="fa fa-refresh"></i>
                                                                                    </span>
                                                                                </button>

                                                                                <button type="button" class="ml-2 btn message-expand receipt-button">
                                                                                    <span class="mr-1">
                                                                                        <i class="fa fa-expand"></i>
                                                                                    </span>
                                                                                </button>

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="message_loading" style="display: none;">
                                                                    <div class="spinner-border text-primary"></div>
                                                                </div>
                                                                <div class="load-more" style="display:none">
                                                                    <button type="button" class="ml-2 btn message-load-more extra-button">
                                                                        load more
                                                                    </button>
                                                                </div>
                                                                <div class="chat-wrapper" id="chat-wrapper">

                                                                </div>

                                                                <form id="message_process_form">
                                                                    <div class="app-inner-layout__bottom-pane text-center message_board" style="display:none;">
                                                                        <div class="mb-0 position-relative row form-group">
                                                                            <div class="col-sm-12">
                                                                                <!-- <input placeholder="Write here and hit enter to send..." type="text" class="form-control-lg form-control"> -->
                                                                                <textarea placeholder="Write here and hit enter to send..." name="message" id="message" class="form-control-lg form-control" rows="2"></textarea>
                                                                                <input type="hidden" name="conversation_id" id="conversation_id" reaonly>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-12">
                                                                            <div class="message_action">

                                                                                <div class="message_extended_section">

                                                                                </div>
                                                                                <div class="message_process_section">
                                                                                    <button type="submit" class="btn btn-success button_send receipt-button">
                                                                                        Send
                                                                                    </button>
                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                        <div class="app-inner-layout__sidebar card">
                                                            <div class="app-inner-layout__sidebar-header">
                                                                <ul class="nav flex-column">
                                                                    <li class="pt-1 pl-3 pr-3 nav-item">
                                                                        <button class="btn btn-info new_conversation" id="new_conversation" data-toggle="modal" data-target="#user_modal"> <i class="fa fa-plus-circle" aria-hidden="true"></i> New Conversation</button>
                                                                    </li>
                                                                    <li class="pt-1 pl-3 pr-3 pb-3 nav-item">
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text">
                                                                                    <i class="fa fa-search"></i>
                                                                                </div>
                                                                            </div>
                                                                            <input placeholder="Search..." type="text" class="form-control">
                                                                        </div>
                                                                    </li>
                                                                    <li class="nav-item-header nav-item">All Conversations</li>
                                                                </ul>
                                                            </div>

                                                            <div class="message_head_loading" style="display: none;">
                                                                <div class="spinner-grow text-info"></div>
                                                                <div class="spinner-grow text-info"></div>
                                                                <div class="spinner-grow text-info"></div>
                                                            </div>

                                                            <ul id="message_head">
                                                            </ul>

                                                            <div class="head-load-more conversation-action" style="display:none">
                                                                <button type="button" class="ml-2 btn button-load-more extra-button">
                                                                    load more
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

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

    <div class="modal fade modal-blur" id="user_modal" role="dialog" data-backdrop="static" data-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">User List</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12 main_receipt_area">

                            <div class="search_receipt_place">
                                <input type="text" id="receipt_keyword" class="form-control" placeholder="Search by Name And Customer Number">
                                <div class="search_receipt_btn">
                                    <button type="button" id="keyword-button" class="btn btn-primary finish-next-btn background_search"><i class="fa fa-search"></i></button>
                                </div>
                            </div>


                            <div class="receipt_loading" style="display: none;">
                                <div class="spinner-border text-primary"></div>
                            </div>

                            <div class="row new_receipt_list">

                                <!-- <div class="col-lg-3 col-md-4 col-6 col-sm-6">

                                    <button class="receipt_button">
                                        <div class="receipt_box">
                                            <div class="receipt_image">
                                                <img src="https://localhost/master/stag/images/user.png" alt="">
                                            </div>

                                            <div class="receipt_info">
                                                <h4>Name</h4>
                                                <p>12345678</p>
                                            </div>
                                        </div>
                                    </button>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6 col-sm-6">

                                    <button class="receipt_button">
                                        <div class="receipt_box">
                                            <div class="receipt_image">
                                                <img src="https://localhost/master/stag/images/user.png" alt="">
                                            </div>

                                            <div class="receipt_info">
                                                <h4>Name</h4>
                                                <p>12345678</p>
                                            </div>
                                        </div>
                                    </button>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6 col-sm-6">

                                    <button class="receipt_button">
                                        <div class="receipt_box">
                                            <div class="receipt_image">
                                                <img src="https://localhost/master/stag/images/user.png" alt="">
                                            </div>

                                            <div class="receipt_info">
                                                <h4>Name</h4>
                                                <p>12345678</p>
                                            </div>
                                        </div>
                                    </button>

                                </div>

                                <div class="col-lg-3 col-md-4 col-6 col-sm-6">

                                    <button class="receipt_button">
                                        <div class="receipt_box">
                                            <div class="receipt_image">
                                                <img src="https://localhost/master/stag/images/user.png" alt="">
                                            </div>

                                            <div class="receipt_info">
                                                <h4>Name</h4>
                                                <p>12345678</p>
                                            </div>
                                        </div>
                                    </button>

                                </div> -->

                            </div>

                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger action_button btn-close-modal" data-dismiss="modal">Cancel</button>
                </div>

            </div>
        </div>
    </div>


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

        <audio id="chatAudio">
        <source src="<?= SITE ?>assets/sound/notification.mp3" type="audio/mpeg">
        </audio>

    <?php
    include('dashboard/include/mobile-message.php');
    ?>

    <script src="<?php echo SITE; ?>assets/js/popper.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= SITE ?>dashboard/js/jquery-ui.js"></script>

    <script>
        let receiptList = [];
        let receiptActive = null;
        let messageList = [];
        let actionList = [];
        let head_offset = 1;
        let offset = 1;
        let loadMore = false;
        let head_loadMore = false;
        let newReceiptList = [];
        let receipt_load = 0;
        let initial_message_count = 0;

        $(function() {
            initialProcess();
        });

        function initialProcess() {
            var dataSet = 'request_id=' + "planiversity";
            messageNotificationProcess(dataSet);
            var dataSet = 'request_id=' + "planiversity" + '&page=' + 1;
            messageHeadProcess(dataSet);

        }
        
        setInterval(function() {
            var dataSet = 'request_id=' + "planiversity";
            messageNotificationProcess(dataSet);
        }, 8000);    
        
        function notificationSoundProcess(recent_count) {
            if (initial_message_count < recent_count) {

                $('#chatAudio')[0].play().catch(function(error) {
                    console.log("Chrome cannot play sound without user interaction first")
                });

            }
            initial_message_count = recent_count;
            console.log('initial_message_count-2', initial_message_count);
        }


        $('.message-expand').click(function() {

            $('.left_menu_colom3').toggleClass('left-side-nav');
            $('.right_content_colom9').toggleClass('right-side-nav');

        });

        $("#chat-wrapper").scroll(function() {
            console.log('scrollTop', this);
            if (($(this).scrollTop() > 0)) {
                $('.load-more').hide();
                console.log('load more hide');
            } else {
                if (loadMore) {
                    console.log('load more show');
                    $('.load-more').show();
                }
            }
        });


        $("#message_head").scroll(function() {
            console.log('scrollTop', this);
            if (($(this).scrollTop() > 0)) {
                if (head_loadMore) {
                    console.log('load more show');
                    $('.head-load-more').show();
                }
            } else {
                $('.head-load-more').hide();
                console.log('load more hide');
            }
        });



        function messageNotificationProcess(dataSet) {

            $.ajax({
                url: SITE + "root/message/notification",
                type: "GET",
                data: dataSet,
                dataType: "json",
                cache: false,
                success: function(response) {

                    if (response.data.results > 0) {
                        $('#message_count').html(response.data.results);
                        notificationSoundProcess(response.data.results);
                    } else {
                        $('#message_count').html('');
                    }

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    // $(".loading_screen").hide();
                    // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");

                }

            });


        }



        function messageHeadProcess(dataSet, appending = false) {

            $(".message_head_loading").show();
            $(".head-load-more").hide();
            $('.conversation-action').css('cursor', 'wait');
            $('.conversation-action').attr('disabled', true);

            $.ajax({
                url: SITE + "root/message/head",
                type: "GET",
                data: dataSet,
                dataType: "json",
                cache: false,
                success: function(response) {

                    if (appending) {
                        $("#message_head").append(response.data.responseList);
                        receiptList = [...receiptList, ...response.data.results.data];
                    } else {
                        $("#message_head").html(response.data.responseList);
                        receiptList = response.data.results.data;
                    }

                    console.log('receiptList', receiptList);

                    head_offset = response.data.next_page;

                    $(".message_head_loading").hide();

                    if (response.data.results.next_page_url == null) {
                        //$(".load-more").hide();
                        head_loadMore = false;
                    } else {
                        //$(".load-more").show();
                        head_loadMore = true;
                    }

                    $('.conversation-action').css('cursor', 'pointer');
                    $('.conversation-action').removeAttr('disabled');

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    // $(".loading_screen").hide();
                    // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");

                }

            });


        }


        $(document).on("click", "li.nav-item.head-item button", function(event) {

            $('li.nav-item button').removeClass("active");
            var conversation_ID = $(this).val();
            const matchValue = receiptList.findIndex((item) => item.id == conversation_ID);
            receiptData = receiptList[matchValue];
            receiptActive = conversation_ID;
            $('#conversation_id').val(conversation_ID);

            var dataSet = 'conversation_id=' + conversation_ID + '&page=' + 1;
            messageListProcess(dataSet, false, false, receiptData.recipient, this, true);
        });

        $(document).on("click", ".message-refresh", function(event) {

            var dataSet = 'conversation_id=' + receiptActive + '&page=' + 1;
            messageListProcess(dataSet, false);
        });

        $(document).on("click", ".load-more", function(event) {

            var scrollPosition = $("#chat-wrapper").scrollTop();
            console.log('scrollPosition top', scrollPosition);
            var dataSet = 'conversation_id=' + receiptActive + '&page=' + offset;
            messageListProcess(dataSet, true, true);
        });

        $(document).on("click", ".head-load-more", function(event) {

            //var scrollPosition = $("#chat-wrapper").scrollTop();
            //console.log('scrollPosition top', scrollPosition);
            //var dataSet = 'conversation_id=' + receiptActive + '&page=' + offset;
            var dataSet = 'request_id=' + "planiversity" + '&page=' + head_offset;
            messageHeadProcess(dataSet, true);
        });


        function receiptHeaderLoad(receiptData) {

            console.log('inside-function', receiptData);

            let photo = SITE + "assets/images/user_profile.png";
            if (receiptData.picture) {
                photo = SITE + "ajaxfiles/profile/" + receiptData.picture;
            }

            $('#receipt_name').html(receiptData.name);
            $("#receipt_photo").attr("src", photo);
        }


        function messageActionProcess(dataSet) {

            //$(".message_loading").show();

            // $('.receipt-button').css('cursor', 'wait');
            // $('.receipt-button').attr('disabled', true);

            $.ajax({
                url: SITE + "root/message/seen",
                type: "POST",
                data: dataSet,
                dataType: "json",
                cache: false,
                success: function(response) {

                    var dataSet = 'request_id=' + "planiversity";
                    messageNotificationProcess(dataSet);
                    // $(".receipt_head").show();
                    // $("#chat-wrapper").html(response.data.responseList);
                    // $(".message_loading").hide();
                    // $('.receipt-button').css('cursor', 'pointer');
                    // $('.receipt-button').removeAttr('disabled');

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    // $(".loading_screen").hide();
                    // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");

                    $('.receipt-button').css('cursor', 'pointer');
                    $('.receipt-button').removeAttr('disabled');


                }

            });

        }


        function messageListProcess(dataSet, prepending = false, scrollPosition = false, receiptData = null, ref = null, initial = false) {

            $(".message_loading").show();
            $(".load-more").hide();
            $('.receipt-button').css('cursor', 'wait');
            $('.receipt-button').attr('disabled', true);

            $.ajax({
                url: SITE + "root/message/load",
                type: "GET",
                data: dataSet,
                dataType: "json",
                cache: false,
                success: function(response) {

                    if (receiptData) {
                        receiptHeaderLoad(receiptData);
                        $(".message_board").show();
                    }
                    if (prepending) {
                        $("#chat-wrapper").prepend(response.data.responseList);
                    } else {
                        $("#chat-wrapper").html(response.data.responseList);
                    }

                    offset = response.data.next_page;
                    actionList = response.data.actions;

                    console.log('actionList', actionList);

                    if (actionList.length != 0) {
                        var actionData = 'action_id=' + actionList;
                        messageActionProcess(actionData);
                    }

                    if (scrollPosition) {
                        console.log('scrollPosition', scrollPosition);
                        $("#chat-wrapper").scrollTop(300);
                    }

                    if (initial) {
                        console.log('Initial scroll load');
                        $("#chat-wrapper").scrollTop($('#chat-wrapper')[0].scrollHeight);
                    }

                    if (ref) {
                        $(ref).addClass("active");
                    }

                    if (response.data.results.next_page_url == null) {
                        //$(".load-more").hide();
                        loadMore = false;
                    } else {
                        //$(".load-more").show();
                        loadMore = true;
                    }

                    console.error('loadMore', loadMore);

                    var firstMsg = $('#chat-wrapper:first');
                    //$('body').prepend(firstMsg.clone());
                    $(document).scrollTop(firstMsg.offset().top);

                    $(".message_loading").hide();

                    $('.receipt-button').css('cursor', 'pointer');
                    $('.receipt-button').removeAttr('disabled');

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    // $(".loading_screen").hide();
                    // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");

                    $('.receipt-button').css('cursor', 'pointer');
                    $('.receipt-button').removeAttr('disabled');


                }

            });

        }

        function messageRefreshProcess(dataSet) {

            $(".message_loading").show();

            $('.receipt-button').css('cursor', 'wait');
            $('.receipt-button').attr('disabled', true);

            $.ajax({
                url: SITE + "root/message/load",
                type: "GET",
                data: dataSet,
                dataType: "json",
                cache: false,
                success: function(response) {

                    $(".receipt_head").show();
                    $("#chat-wrapper").html(response.data.responseList);
                    $(".message_loading").hide();
                    $('.receipt-button').css('cursor', 'pointer');
                    $('.receipt-button').removeAttr('disabled');

                },
                error: function(jqXHR, textStatus, errorThrown) {

                    // $(".loading_screen").hide();
                    // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");

                    $('.receipt-button').css('cursor', 'pointer');
                    $('.receipt-button').removeAttr('disabled');


                }

            });

        }


        $("#message_process_form").validate({

            rules: {

                message: {
                    required: true,
                },


            },
            messages: {

                message: {
                    required: 'Please type your message',
                },

            },


            submitHandler: function(form) {


                $('.button_send').css('cursor', 'wait');
                $('.button_send').attr('disabled', true);

                $.ajax({
                    url: SITE + "root/message/process",
                    type: "POST",
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function(response) {

                        toastr.success(response.data.message);
                        $("#message").val("");
                        var dataSet = 'conversation_id=' + receiptActive + '&page=' + 1;
                        messageListProcess(dataSet, false);

                        $('.button_send').css('cursor', 'pointer');
                        $('.button_send').removeAttr('disabled');


                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                        var res = jqXHR.responseJSON;
                        toastr.error(res.message);

                        $('.button_send').css('cursor', 'pointer');
                        $('.button_send').removeAttr('disabled');

                    }


                });


            }, // Do not change code below
            // errorPlacement: function(error, element) {
            //     error.insertAfter(element.parent());
            // }


        });


        function receiptInitialProcess() {
            var dataSet = 'request_id=' + "planiversity" + '&page=' + "1";
            receiptAjaxProcess(dataSet);
        }


        $('#new_conversation').click(function() {

            //var api_proceced = $('#api_proceced').val();

            if (receipt_load == 0) {
                receiptInitialProcess();
            }

        });


        $(document).on("click", "#keyword-button", function(event) {
            var queryString = $('#receipt_keyword').val();
            var dataSet = 'request_id=' + "planiversity" + '&search=' + queryString + '&page=' + "1";
            if (queryString != "") {
                receiptAjaxProcess(dataSet);
            }
        });

        $("#receipt_keyword").keyup(function(event) {
            if (event.keyCode == 13) {
                $("#keyword-button").click();
            }
        });


        function receiptAjaxProcess(dataSet) {
            $(".new_receipt_list").html("");
            $(".receipt_loading").show();
            // $(".load_more").hide();
            // $(".target_action").attr("disabled", false);
            // $(".background_search").attr("disabled", true);

            $.ajax({
                url: SITE + "root/receipt/list",
                type: "GET",
                data: dataSet,
                dataType: "json",
                success: function(response) {

                    $(".receipt_loading").hide();
                    $(".new_receipt_list").html(response.data.responseList);
                    // next_page = response.data.next_page;
                    // previous_page = response.data.previous_page;
                    // request_query = response.data.request_query;
                    newReceiptList = response.data.results.data;
                    receipt_load = 1

                    // $("#next_button").val(response.data.next_page);
                    // $("#previous_button").val(response.data.previous_page);

                    // if (response.data.responseList) {
                    //     $("#api_proceced").val(1);
                    //     $("#api_query").val(request_query);
                    // }

                    // if (next_page || previous_page) {
                    //     $(".load_more").show();
                    // }

                    // if (next_page == null) {
                    //     $("#next_button").attr("disabled", true);
                    // }

                    // if (previous_page == null) {
                    //     $("#previous_button").attr("disabled", true);
                    // }

                    // $(".background_search").attr("disabled", false);


                },
                error: function(jqXHR, textStatus, errorThrown) {

                    $(".loading_screen").hide();
                    $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");


                }

            });
        }

        $(document).on("click", ".receipt_button", function(event) {
            var action_value = $(this).val();
            var dataSet = 'action_value=' + action_value;
            const matchUser = newReceiptList.findIndex((item) => item.id == action_value);
            newReceiptData = newReceiptList[matchUser];
            conversationProcess(dataSet, newReceiptData, this);
        });


        function conversationProcess(dataSet, newReceiptData, ref) {

            $('.receipt_action').css('cursor', 'wait');
            $('.receipt_action').attr('disabled', true);

            $.ajax({
                url: SITE + "root/conversation/process",
                type: "POST",
                data: dataSet,
                dataType: "json",
                success: function(response) {
                    var dataSetHead = 'request_id=' + "planiversity" + '&page=' + 1;
                    messageHeadProcess(dataSetHead);
                    //$('li.nav-item button').removeClass("active");

                    $(ref).parent().remove();
                    $('#user_modal').modal('hide');
                    $('.receipt_action').css('cursor', 'pointer');
                    $('.receipt_action').removeAttr('disabled');
                    $('#conversation_id').val(response.data.conversation);
                    receiptActive = response.data.conversation;
                    var conversationDataSet = 'conversation_id=' + response.data.conversation + '&page=' + 1;
                    messageListProcess(conversationDataSet, false, false, newReceiptData, false, true);

                    setTimeout(() => {

                        var ref = '#head_' + response.data.conversation;
                        console.log('ref_value', ref);
                        $(ref).addClass("active");

                    }, "2000");



                },
                error: function(jqXHR, textStatus, errorThrown) {

                    // $(".loading_screen").hide();
                    // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");


                }

            });
        }

        $(document).on("click", ".migration_action", function(event) {
            let data_flag = $(this).data("flag");
            let did = $(this).parent().data('did');

            var dataSet = 'did=' + did + '&flag=' + data_flag;

            swal({
                    title: "Are you sure?",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#268763",
                    confirmButtonText: "Yes, proceed!",
                    closeOnConfirm: true,
                },
                function() {
                    $.ajax({
                        type: "POST",
                        url: SITE + "root/migration/status",
                        data: dataSet,
                        dataType: "json",
                        success: function(response) {

                            $("#section_block_" + did).remove();
                            toastr.success(response.data.message);
                            $("#status_action_" + did).html(data_flag);

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR.responseJSON);
                        },
                    });
                }
            );

        });
    </script>


</body>

</html>