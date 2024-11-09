<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    header("Location:" . SITE . "login");
}

if ($userdata['account_type'] != 'Admin') {
    header("Location:" . SITE . "welcome");
}

include('include_doctype.php');
?>
<!DOCTYPE html>
<html lang="en">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | Coupon</title>
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>images/favicon.png">

    <!--calendar css-->
    <link href="<?php echo SITE; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/app-style.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <script src="<?php echo SITE; ?>assets/js/modernizr.min.js"></script>

    <script src="<?php echo SITE; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/moment.min.js"></script>
    <script src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js'></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>

    <script>
        var SITE = '<?php echo SITE; ?>'
    </script>
    <link href="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
    <style>
        .dt-header {
            cursor: pointer;
        }

        .btn-mini {
            padding: 4px 10px;
            border: 2px solid #e8e8e8;
            font-size: 12px;
            font-weight: 500;
            color: #333;
        }

        .btn-mini i {
            font-size: 16px;
        }

        .display_table tbody tr td {
            vertical-align: super;
        }

        #update_coupon,
        #add_coupon {
            z-index: 9999;
        }

        .master_modal {
            overflow: scroll;
        }

        .modal-backdrop {
            background-color: #000;
            z-index: 1111;
        }

        .modal-blur {
            -webkit-backdrop-filter: blur(4px);
            backdrop-filter: blur(4px);
        }

        .action_button {
            font-size: 12px;
        }

        label.error {
            font-size: 12px;
            color: red !important;
            position: relative;
            bottom: 18px;
        }

        .modal .modal-dialog .modal-content .modal-body {
            padding: 5px 28px;
        }
    </style>
</head>

<body>
    <header id="topnav">
        <div class="topbar-main">
            <div class="container-fluid">
                <div class="logo">
                    <a href="<?php echo SITE; ?>" class="logo">
                        <span class="logo-small"><img src="<?php echo SITE; ?>assets/images/logo-icon.png" alt="logo icon"></span>
                        <span class="logo-large"><span>Planiversity</span></span>
                        <li class="menu-item list-inline-item">
                            <a class="navbar-toggle nav-link">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                        </li>
                    </a>
                </div>
                <div class="menu-extras topbar-custom">
                    <ul class="list-inline float-right mb-0">
                        <li class="list-inline-item dropdown more-nav-list new-backend-header-style">
                            <a class="nav-link dropdown-toggle arrow-none link-drop" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <span class="fa fa-chevron-down mr-2"></span>
                                <span><?php echo isset($userdata['name']) ? $userdata['name'] : "Test"; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg" aria-labelledby="Preview">
                                <?php
                                if ($userdata['account_type'] == 'Admin') {
                                ?>
                                    <a class="dropdown-item drop-menu-item" href="<?= SITE; ?>apanel/affiliate" target="_blank">
                                        Admin
                                    </a>
                                <?php } ?>
                                <a href="<?php echo SITE; ?>about-us" class="dropdown-item drop-menu-item" target="_blank">
                                    About Us
                                </a>
                                <a href="<?php echo SITE; ?>select-your-payment" class="dropdown-item drop-menu-item" target="_blank">
                                    What It Costs
                                </a>
                                <a href="<?php echo SITE; ?>faq" class="dropdown-item drop-menu-item" target="_blank">
                                    FAQs
                                </a>
                                <a href="<?php echo SITE; ?>data-security" class="dropdown-item drop-menu-item" target="_blank">
                                    Data Security
                                </a>
                                <a href="<?php echo SITE; ?>contact-us" class="dropdown-item drop-menu-item" target="_blank">
                                    Contact Us
                                </a>
                                <a target="_blank" href="http://erichrichardblog.wordpress.com" class="dropdown-item drop-menu-item" target="_blank">
                                    Blog
                                </a>
                                <a href="<?php echo SITE; ?>leave" class="dropdown-item drop-menu-item" target="_blank">
                                    Delete Account
                                </a>
                                <a href="<?php echo SITE; ?>logout" class="dropdown-item drop-menu-item">
                                    Logout
                                </a>
                            </div>
                        </li>
                        <li class="list-inline-item dropdown more-nav-list new-backend-header-style">
                            <?php
                            $img = 'images/img3.png';
                            if ($userdata['picture']) $img = 'ajaxfiles/profile/' . $userdata['picture'];
                            ?>
                            <img src="<?php echo SITE . $img; ?>" width="35px" height="35px" alt="user" class="header-avatar rounded-circle">
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <style>
            .navigation-menu.header-top-bar-style li a {
                color: #007bff;
            }
        </style>
        <div class="navbar-custom header-nav-bar-style">
            <div class="container-fluid">
                <div id="navigation">
                    <ul class="navigation-menu header-top-bar-style">
                        <li>
                            <a href="<?php echo SITE; ?>welcome">Home</a>
                        </li>
                        <li>
                            <a href="<?php echo SITE; ?>apanel/routes">Routes</a>
                        </li>
                        <li>
                            <a href="<?php echo SITE; ?>apanel/settings">Settings</a>
                        </li>
                        <li>
                            <a href="<?php echo SITE; ?>apanel/transactions">Transactions</a>
                        </li>
                        <li>
                            <a href="<?php echo SITE; ?>apanel/affiliate">Affiliate</a>
                        </li>
                        <li>
                            <a href="<?php echo SITE; ?>apanel/users">Users</a>
                        </li>

                        <li class="active-link">
                            <a href="<?php echo SITE; ?>apanel/coupon">Coupon</a>
                        </li>

                        <li class="pull-right">
                            <a class="new-account-btn" data-toggle="modal" data-target="#add_coupon">Create New Coupon
                                <i class="fa fa-plus-circle"></i>
                            </a>
                        </li>


                    </ul>
                </div>
            </div>
        </div>
    </header>


    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-headr">Coupon</h3>

                    <div class="card-box">
                        <div class="table-wrap">
                            <div class="table-responsive table-striped">

                                <table id="coupon_list" class="display_table table " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="10%">Title</th>
                                            <th width="8%">Coupon Code</th>
                                            <th width="8%">Discount Percent</th>
                                            <th width="8%">Start Date</th>
                                            <th width="8%">End Date</th>
                                            <th width="6%" align="center">Status</th>
                                            <th width="10%" align="center">Action</th>
                                        </tr>
                                    </thead>

                                </table>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade modal-blur" data-backdrop="true" id="add_coupon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Create Coupon</h4>
                </div>
                <form id="coupon_form_add">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-4">

                                <div class="form-group custom-group">
                                    <p class="event-title">Title</p>
                                    <input type="text" class="form-control" name="title">
                                </div>

                                <div class="form-group custom-group">
                                    <p class="event-title">Start Date</p>
                                    <input type="text" class="form-control datepicker " name="start_date">
                                </div>

                            </div>

                            <div class="col-md-4 col-lg-4 col-sm-4">

                                <div class="form-group custom-group">
                                    <p class="event-title">Coupon Code</p>
                                    <input type="text" class="form-control" name="coupon_code">
                                </div>

                                <div class="form-group custom-group">
                                    <p class="event-title">End Date</p>
                                    <input type="text" class="form-control datepicker " name="end_date">
                                </div>

                            </div>


                            <div class="col-md-4 col-lg-4 col-sm-4">

                                <div class="form-group custom-group">
                                    <p class="event-title">Discount Percent</p>
                                    <input type="number" class="form-control" name="percent">
                                </div>

                                <div class="form-group custom-group">
                                    <p class="event-title">Status</p>
                                    <select class="form-control" name="status">
                                        <option value="">Select a option</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Stripe Individual Plan ID</p>
                                    <input type="text" class="form-control" name="stripe_individual_plan_id">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Stripe Business Plan ID</p>
                                    <input type="text" class="form-control" name="stripe_business_plan_id">
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Paypal Individual Plan ID</p>
                                    <input type="text" class="form-control" name="paypal_individual_plan_id">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Paypal Business Plan ID</p>
                                    <input type="text" class="form-control" name="paypal_business_plan_id">
                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="update" class="btn btn-primary action_button submit_button">Process</button>
                        <button type="button" class="btn btn-danger action_button btn-close-modal" data-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <div class="modal fade modal-blur" data-backdrop="true" id="update_coupon" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Update Coupon</h4>
                </div>
                <form id="coupon_form_update">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-4 col-lg-4 col-sm-4">

                                <div class="form-group custom-group">
                                    <p class="event-title">Title</p>
                                    <input type="text" class="form-control" name="title" id="e_title">
                                </div>

                                <div class="form-group custom-group">
                                    <p class="event-title">Start Date</p>
                                    <input type="text" class="form-control datepicker " name="start_date" id="e_start_date">
                                </div>

                            </div>

                            <div class="col-md-4 col-lg-4 col-sm-4">

                                <div class="form-group custom-group">
                                    <p class="event-title">Coupon Code</p>
                                    <input type="text" class="form-control" name="coupon_code" id="e_coupon_code">
                                </div>

                                <div class="form-group custom-group">
                                    <p class="event-title">End Date</p>
                                    <input type="text" class="form-control datepicker " name="end_date" id="e_end_date">
                                </div>

                            </div>


                            <div class="col-md-4 col-lg-4 col-sm-4">

                                <div class="form-group custom-group">
                                    <p class="event-title">Discount Percent</p>
                                    <input type="number" class="form-control" name="percent" id="e_percent">
                                </div>

                                <div class="form-group custom-group">
                                    <p class="event-title">Status</p>
                                    <select class="form-control" name="status" id="e_status">
                                        <option value="">Select a option</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>

                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Stripe Individual Plan ID</p>
                                    <input type="text" class="form-control" name="stripe_individual_plan_id" id="e_stripe_individual_plan_id">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Stripe Business Plan ID</p>
                                    <input type="text" class="form-control" name="stripe_business_plan_id" id="e_stripe_business_plan_id">
                                </div>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Paypal Individual Plan ID</p>
                                    <input type="text" class="form-control" name="paypal_individual_plan_id" id="e_paypal_individual_plan_id">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Paypal Business Plan ID</p>
                                    <input type="text" class="form-control" name="paypal_business_plan_id" id="e_paypal_business_plan_id">
                                </div>
                            </div>

                        </div>




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
        $(".dt-header").on("click", function() {
            var index = $(this).attr("id");
            var order_dir = $(this).data("id");
            document.getElementById("dt-header-form-data").setAttribute("name", index)
            document.getElementById("dt-header-form-data").setAttribute("value", order_dir)
            $("#dt-header-form").submit()
        });



        $(document).ready(function() {

            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            })



            var coupon_list = $('#coupon_list').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": SITE + "ajaxfiles/coupon_list/coupon_list_processing.php",
                type: "POST",
                "columnDefs": [{
                    "targets": -1,
                    "data": null,
                    "defaultContent": " <td><div align='center'><a id='edit' href='#update_coupon' class='btn btn-mini btn-info' role='button' data-toggle='modal' title='Edit Coupon'><i class='fa fa-edit'></i> Edit</a>  <button id='delete' class='btn btn-mini btn-danger' title='Delete Coupon'><i class='fa fa-trash'></i> Delete</a></div></td>"
                }]
            });



            $('#coupon_list tbody').on('click', '#edit', function() {
                var data = coupon_list.row($(this).parents('tr')).data();

                $('#e_title').val(data[0]);
                $('#e_start_date').val(data[3]);
                $('#e_coupon_code').val(data[1]);
                $('#e_end_date').val(data[4]);
                $('#e_percent').val(data[2]);
                $('#e_status').val(data[5]);
                $('#e_stripe_individual_plan_id').val(data[6]);
                $('#e_stripe_business_plan_id').val(data[7]);
                $('#e_paypal_individual_plan_id').val(data[8]);
                $('#e_paypal_business_plan_id').val(data[9]);
                $('#id').val(data[10]);

            });


            $("#coupon_form_add").validate({
                rules: {
                    title: {
                        required: true,
                    },
                    start_date: {
                        required: true,
                    },
                    coupon_code: {
                        required: true,
                    },
                    end_date: {
                        required: true,
                    },
                    percent: {
                        required: true,
                    },
                    status: {
                        required: true,
                    },
                    stripe_individual_plan_id: {
                        required: true,
                    },
                    stripe_business_plan_id: {
                        required: true,
                    },
                    paypal_individual_plan_id: {
                        required: true,
                    },
                    paypal_business_plan_id: {
                        required: true,
                    }
                },
                messages: {

                    title: {
                        required: 'Please type title'
                    },
                    start_date: {
                        required: 'Please select start date'
                    },
                    coupon_code: {
                        required: 'Please type coupon code'
                    },
                    end_date: {
                        required: 'Please select end date'
                    },
                    percent: {
                        required: 'Please discount percent'
                    },
                    status: {
                        required: 'Please select status'
                    },
                    stripe_individual_plan_id: {
                        required: 'Please type plan ID'
                    },
                    stripe_business_plan_id: {
                        required: 'Please type plan ID'
                    },
                    paypal_individual_plan_id: {
                        required: 'Please type plan ID'
                    },
                    paypal_business_plan_id: {
                        required: 'Please type plan ID'
                    },
                },


                submitHandler: function(form) {

                    $('.submit_button').css('cursor', 'wait');
                    $('.submit_button').attr('disabled', true);

                    $.ajax({
                        url: SITE + "ajaxfiles/coupon_list/add_coupon.php",
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(response) {

                            $("#coupon_form_add").trigger("reset");
                            $('#add_coupon').modal('hide');

                            toastr.success('Successfully Coupon Added');

                            coupon_list.ajax.reload();

                            $('.submit_button').css('cursor', 'pointer');
                            $('.submit_button').removeAttr('disabled');


                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            toastr.error('A system error has been encountered. Please try again');

                            $('.submit_button').css('cursor', 'pointer');
                            $('.submit_button').removeAttr('disabled');

                        }


                    });




                }, // Do not change code below
                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                }


            });



            $("#coupon_form_update").validate({
                rules: {
                    title: {
                        required: true,
                    },
                    start_date: {
                        required: true,
                    },
                    coupon_code: {
                        required: true,
                    },
                    end_date: {
                        required: true,
                    },
                    percent: {
                        required: true,
                    },
                    status: {
                        required: true,
                    },
                    stripe_individual_plan_id: {
                        required: true,
                    },
                    stripe_business_plan_id: {
                        required: true,
                    },
                    paypal_individual_plan_id: {
                        required: true,
                    },
                    paypal_business_plan_id: {
                        required: true,
                    }
                },
                messages: {

                    title: {
                        required: 'Please type title'
                    },
                    start_date: {
                        required: 'Please select start date'
                    },
                    coupon_code: {
                        required: 'Please type coupon code'
                    },
                    end_date: {
                        required: 'Please select end date'
                    },
                    percent: {
                        required: 'Please discount percent'
                    },
                    status: {
                        required: 'Please select status'
                    },
                    stripe_individual_plan_id: {
                        required: 'Please type plan ID'
                    },
                    stripe_business_plan_id: {
                        required: 'Please type plan ID'
                    },
                    paypal_individual_plan_id: {
                        required: 'Please type plan ID'
                    },
                    paypal_business_plan_id: {
                        required: 'Please type plan ID'
                    },
                },


                submitHandler: function(form) {

                    $('.update_submit_button').css('cursor', 'wait');
                    $('.update_submit_button').attr('disabled', true);

                    $.ajax({
                        url: SITE + "ajaxfiles/coupon_list/update_coupon.php",
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(response) {

                            $("#coupon_form_update").trigger("reset");
                            $('#update_coupon').modal('hide');

                            toastr.success('Successfully Coupon Updated');

                            coupon_list.ajax.reload();

                            $('.update_submit_button').css('cursor', 'pointer');
                            $('.update_submit_button').removeAttr('disabled');


                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                            toastr.error('A system error has been encountered. Please try again');

                            $('.update_submit_button').css('cursor', 'pointer');
                            $('.update_submit_button').removeAttr('disabled');

                        }


                    });




                }, // Do not change code below
                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                }


            });



            $('#coupon_list tbody').on('click', '#delete', function() {
                var data = coupon_list.row($(this).parents('tr')).data();


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
                        url: SITE + "ajaxfiles/coupon_list/delete_coupon.php",
                        data: {
                            "id": data[10],
                        },
                        dataType: 'json',
                        success: function(response) {
                            toastr.success('Successfully Coupon Deleted');
                            coupon_list.ajax.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(jqXHR.responseJSON);
                        }

                    });



                });



            });



        });
    </script>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="footer-text">&copy; Copyright. 2015 -
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        Planiversity, LLC. All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?php echo SITE; ?>assets/js/popper.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/bootstrap-datepicker.js"></script>
    <script src="<?php echo SITE; ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/jquery.slimscroll.js"></script>
    <script src="<?php echo SITE; ?>assets/js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/jquery.app.js"></script>

</body>

</html>