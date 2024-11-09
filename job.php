<?php
include_once("config.ini.php");
include_once("config.ini.curl.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'jobs';
    header("Location:" . SITE . "login");
}
if ($userdata['account_type'] == 'Individual')
    header("Location:" . SITE . "welcome");

include('include_doctype.php');
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <title>PLANIVERSITY - Jobs</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.png" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.png" type="image/x-icon">

    <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
    <script src="<?php echo SITE; ?>js/flexcroll.js"></script>

    <script src="<?php echo SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?php echo SITE; ?>'
    </script>

    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

    <?php include('templates/header.php'); ?>

    <script src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
    <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
    <script src="<?php echo SITE; ?>js/flexcroll.js"></script>

    <style>
        .form-group.process-btn {
            display: flex;
        }

        .pac-container {
            z-index: 10000 !important;
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

        label.error {
            color: #f31c1c;
            position: relative;
            bottom: 15px;
            font-size: 13px;
        }

        button#add_invitee {
            border: 1px solid #d1d1d1;
            position: relative;
            right: 10px;
            bottom: 1px;
        }

        .upload-input-file input[type="text"] {
            font-size: 14px;
            border: 1px solid #e4e4e4;
            border-radius: 0px;
            box-shadow: none;
            display: block;
            color: #333;
            height: 46px;
            padding: 12px;
            outline: none;
            width: 100%;
            font-weight: 400;
            -webkit-box-shadow: 0 1px 1px 0 rgba(45, 44, 44, 0.05) !important;
            box-shadow: 0 1px 1px 0 rgba(45, 44, 44, 0.05) !important;
        }

        input[type="file"] {
            display: block;
        }

        label.input-file {
            background: white;
            width: 100%;
        }

        .a-menu-element a.active {
            background: #075b9e !important;
        }

        label.input-file:hover {
            background: white;
            width: 100%;
        }

        .footer {
            position: inherit !important;
        }

        .ajax-file-upload {
            padding: 6px 16px;
            background: #fb7f16;
            display: table;
            color: #fff;
            float: left;
            font-size: 14px;
            margin-bottom: 0px;
            margin-top: 0px;
            border-radius: 50px;
        }

        .ajax-file-upload:hover {
            padding: 6px 16px;
            background: #fb7f16;
            display: table;
            color: #fff;
            float: left;
            font-size: 14px;
            margin-bottom: 0px;
            margin-top: 0px;
            border-radius: 50px;
        }

        .btn-mini {
            padding: 4px 10px;
            border: 2px solid #e8e8e8;
            font-size: 12px;
            font-weight: 500;
            color: #333;
        }
    </style>

</head>

<body>

    <?php $page_name = 'jobs';
    include('templates/dashboard_header.php'); ?>
    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu">
                    <?php
                    $s_email = '';
                    $s_showcancel = 'style="display:none;"';
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $s_email .= " AND `title` LIKE '%" . $_GET['search'] . "%' OR `transport` LIKE '%" . $_GET['search'] . "%' OR `location_from` LIKE '%" . $_GET['search'] . "%' OR `location_to` LIKE '%" . $_GET['search'] . "%'";
                        $s_showcancel = '';
                    }
                    if ($userdata['account_type'] == 'Business' || $userdata['account_type'] == 'Admin') { ?>
                        <li>
                            <a href="<?php echo SITE ?>people" class="left-nav-button">
                                <i class="mdi mdi-account-multiple"></i>People
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SITE ?>jobs" style="background: #075b9e !important" class="left-nav-button">
                                <i class="mdi mdi-briefcase"></i>Jobs
                            </a>
                        </li>
                        <li class="a-menu-element">
                            <a href="<?php echo SITE ?>events" class="left-nav-button">
                                <i class="mdi mdi-calendar"></i>Events
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    </header>

    <div class="loading-wrapper">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="wrapper">
        <div class="container-fluid">
            <h3 class="page-headr color-1F74B7">Jobs</h3>
            <div class="row">
                <div class="col-lg-7 employee-left-side">
                    <div class="card-box">
                        <form id="job_form">
                            <div class="form-wrap">
                                <fieldset>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="emp-form-label">Name</label>
                                                <input name="job_name" type="text" class="account-form-control form-control input-lg inp1" placeholder="Job Name">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="emp-form-label">Category</label>
                                                <select name="job_category" class="form-control input-lg inp1">
                                                    <option value="">Select a option</option>
                                                    <option value="Residential">Residential</option>
                                                    <option value="Commerical">Commerical</option>
                                                    <option value="Land Lot">Land Lot</option>
                                                    <option value="Government Project">Government Project</option>
                                                    <option value="State Project">State Project</option>
                                                    <option value="Bridge">Bridge</option>
                                                    <option value="Departmental">Departmental</option>
                                                    <option value="Area">Area</option>
                                                    <option value="Regional">Regional</option>
                                                    <option value="Private">Private</option>
                                                    <option value="Public">Public</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="emp-form-label">Job Details</label>
                                                <textarea name="job_details" class="textarea-control form-control input-lg" placeholder="Job Details" cols="" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 number-group">
                                            <label class="emp-form-label">Contact Numbers</label>
                                            <div class="form-group">
                                                <input name="job_cnumbers" class="form-control account-form-control input-lg" placeholder="Contact Numbers" name="">
                                                <span class="fa fa-plus-circle add-btn point" id="add-contact-number"></span>
                                            </div>
                                            <div id="added-number-group">
                                            </div>
                                        </div>

                                        <div class="col-sm-12 number-group">
                                            <label class="emp-form-label">People</label>
                                            <div class="form-group process-btn">

                                                <input type="text" class="form-control account-form-control input-lg" id="invitee_type" placeholder="Search Employee" list="invitee_id_datalist" autocomplete="off" autofocus="">
                                                <datalist id="invitee_id_datalist" class="invitee_list">
                                                </datalist>
                                                <Button type="button" class="btn btn-info" id="add_invitee"><span class="fa fa-plus-circle"></span></Button>

                                            </div>
                                            <div id="added-emp-group">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Address</label>
                                                <input name="job_address" id="address" placeholder="Address" type="text" class="account-form-control form-control input-lg">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">City</label>
                                                <input name="job_city"  type="text" class="account-form-control form-control input-lg" placeholder="Address">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">State</label>
                                                <input name="job_state"   type="text" placeholder="State" class="account-form-control form-control input-lg">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Zip Code</label>
                                                <input name="job_zcode" placeholder="Zip Code"  type="text" class="account-form-control form-control input-lg">
                                            </div>
                                        </div>

                                        <div class="col-sm-12 save-btn-wrapper">
                                            <button type="submit" class="save-changes-btn submit_action_button employee-save-btn"> Save Job</button>
                                        </div>

                                    </div>
                                </fieldset>
                            </div>
                    </div>
                </div>
                <div class="col-lg-5 employee-right-side">
                    <div class="card-box">
                        <h5 class="itineraries-text size-22 mt-5">Documents</h5>
                        <h6 class="itineraries-text">Upload your documents</h6>
                        <div class="upload-wrap2" id="upload-form1">
                            <div class="upload-wrap-top">
                                <div>
                                    <img src="assets/images/file-plus.png" />
                                    <span class="bold">Upload a documen</span>
                                </div>
                                <span class="point bold">&times;</span>
                            </div>
                            <div class="space-arround">
                                <img src="assets/images/upload_document.png">
                                <div>
                                    <div class="bold">Upload your document</div>
                                    <input type="file" class="display-none" name="employee_doc" id="upload-file1">
                                    <small>Drag & drop or <label class="text-blue point bold" for="upload-file1">browse</label> your file here</small>
                                </div>
                            </div>
                        </div>
                        <h6 class="itineraries-text">Your documents</h6>
                        <div class="upload-wrapper1">
                            <div id="uploaded-group1">
                            </div>
                        </div>

                        <!-- <h5 class="itineraries-text size-22 mt-5">Equipment</h5>
                            <h6 class="itineraries-text">Upload your Equipment Documents</h6>
                            <div class="upload-wrap2" id="upload-form2">
                                <div class="upload-wrap-top">
                                    <div>
                                        <img src="assets/images/file-plus.png" />
                                        <span class="bold">Upload a document</span>
                                    </div>
                                    <span class="point bold">&times;</span>
                                </div>
                                <div class="space-arround">
                                    <img src="assets/images/upload_document.png">
                                    <div>
                                        <div class="bold">Upload your document</div>
                                        <input type="file" class="display-none" name="employee_doc" id="upload-file2">
                                        <small>Drag & drop or <label class="text-blue point bold" for="upload-file2">browse</label> your file here</small>
                                    </div>
                                </div>
                            </div>
                            <h6 class="itineraries-text">Your Equipment Documents</h6>
                            <div class="upload-wrapper1">
                                <div id="uploaded-group2">
                                </div>
                                <div id="eventsmessage"></div>
                                <!-- <div class="col-sm-12">
                                    <div class="save-btn-wrapper">
                                        <button type="submit" class="save-changes-btn">Save</button>
                                        <input name="job_add" id="job_add" type="button" class="save-changes-btn button button2 employee-save-btn" value="Save">
                                    </div>
                                    <br clear="all" />
                                    <div class="error_cont">
                                        <div id="error_list" class="show_error"></div>
                                        <div id="loading_list" style="display:none;"><img src="<?php echo SITE; ?>images/loading.gif" /></div>
                                    </div>
                                </div>
                            </div> -->



                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <div class="container-fluid">
        <div class="row mt-2 mb-2">
            <div class="col-lg-12">
                <div class="card-box">


                    <div class="table-wrap">
                        <div class="table-responsive table-striped">

                            <table id="job_list" class="display_table table " cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="10%">Job Title</th>
                                        <th width="8%">Category</th>
                                        <th width="8%">Address</th>
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


    <div class="modal fade modal-blur" data-backdrop="true" id="update_job" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Update Job</h4>
                </div>
                <form id="job_form_update">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">

                                <div class="form-group custom-group">
                                    <p class="event-title">Job Name</p>
                                    <input type="text" class="form-control" name="job_name" id="e_job_name" placeholder="Job Name">
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Category</p>
                                    <select name="job_category" id="e_job_category" class="form-control input-lg inp1">
                                        <option value="">Select a option</option>
                                        <option value="Residential">Residential</option>
                                        <option value="Commerical">Commerical</option>
                                        <option value="Land Lot">Land Lot</option>
                                        <option value="Government Project">Government Project</option>
                                        <option value="State Project">State Project</option>
                                        <option value="Bridge">Bridge</option>
                                        <option value="Departmental">Departmental</option>
                                        <option value="Area">Area</option>
                                        <option value="Regional">Regional</option>
                                        <option value="Private">Private</option>
                                        <option value="Public">Public</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="form-group custom-group">
                                    <p class="event-title">Contact Numbers</p>
                                    <input type="text" class="form-control" name="job_cnumbers" id="e_job_cnumbers">
                                </div>                                

                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <p class="event-title">Job Details</p>
                                    <textarea name="job_details" id="e_job_details" class="textarea-control form-control input-lg" placeholder="Job Details" rows="5"></textarea>
                                </div>

                            </div>

                        </div>

                        <div class="row">



                            <div class="col-md-6 col-lg-6 col-sm-6">

                                <div class="form-group custom-group">
                                    <p class="event-title">Address</p>
                                    <input type="text" class="form-control" name="job_address" id="e_job_address">
                                </div>

                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-6">

                                <div class="form-group custom-group">
                                    <p class="event-title">City</p>
                                    <input type="text" class="form-control" name="job_city" id="e_job_city">
                                </div>

                            </div>


                            <div class="col-md-6 col-lg-6 col-sm-6">

                                <div class="form-group custom-group">
                                    <p class="event-title">State</p>
                                    <input type="text" class="form-control" name="job_state" id="e_job_state">
                                </div>

                            </div>    
                            
                            <div class="col-md-6 col-lg-6 col-sm-6">

                                <div class="form-group custom-group">
                                    <p class="event-title">Zip Code</p>
                                    <input type="text" class="form-control" name="job_zcode" id="e_job_zcode">
                                </div>

                            </div>                                

                        </div>
            

                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <p class="event-title">People</p>

                                    <div id="employee_list"></div>

                                </div>

                            </div>

                        </div>


                        <input type="hidden" id="eid" name="eid" readonly>

                    </div>
                    <div class="modal-footer">
                        <button type="update" class="btn btn-primary action_button update_submit_button">Update</button>
                        <button type="button" class="btn btn-danger action_button btn-close-modal" data-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>
    <script src="<?php echo SITE; ?>js/jobs.js?v=202211"></script>

    <script>
        function initMap() {
            var originInput = document.getElementById('address');
            var originPlaceAutocomplete = new google.maps.places.Autocomplete(originInput);
            var e_originInput = document.getElementById('e_job_address');
            var e_originPlaceAutocomplete = new google.maps.places.Autocomplete(e_originInput);            

            
        }

        $(function() {
            getEmployee();
        });

        function getEmployee() {

            var items = "";
            $.getJSON(SITE + "ajaxfiles/employee/get_employee.php", function(data) {
                items += "<option value='' >-Select a invitees-</option>";
                $.each(data, function(index, item) {
                    items += "<option id='" + item.emp_id + "' value='" + item.full_name + "' data-emp_email='" + item.email + "'>" + item.full_name + "</option>";
                });

                $(".invitee_list").html(items);
            });

        }

        $("#invitee_type").keyup(function(event) {
            if (event.keyCode == 13) {
                if ($('#add_invitee').attr('disabled')) {} else {
                    $("#add_invitee").click();
                }
            }
        });

        $(document).on('click', '#add_invitee', function() {

            var emp_key = $('#invitee_type').val();
            var emp_map = $('.emp_map').map(function() {
                return this.value;
            }).get();

            var emp_email = $('#invitee_id_datalist [value="' + emp_key + '"]').data('emp_email');

            var emp_id = $('#invitee_id_datalist').find('option').filter(function() {
                return $.trim($(this).text()) === emp_key;
            }).attr('id');

            console.log('emp_map', emp_map);

            if (emp_id === undefined || emp_id === null || emp_id === "") {

                swal({
                    title: "Select a valid employee",
                    type: "error",
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: 'swal-height'
                });

            } else if ($.inArray(emp_id, emp_map) > -1) {

                swal({
                    title: "Employee is already in this list",
                    type: "warning",
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: 'swal-height'
                });

            } else {

                empItem(emp_id, emp_key, emp_email);
                $('#invitee_type').val('');
            }

        });

        function empItem(item_id, item_name, item_email) {
            var dom = `<div class="uploaded-item">
                        <label>` + item_name + `</label>
                	    <input type="hidden" name="employee_id[]" value="` + item_id + `" class="emp_map">                	    
                        <button type="button" class="remove-uploaded-item">&times;</button>
                        </div>`;

            $("#added-emp-group").append(dom);

        }


        $(document).ready(function() {

            // preventing page from redirecting
            $("html").on("dragover", function(e) {
                e.preventDefault();
                e.stopPropagation();
                $("#upload-form").css({
                    "border-color": "#666",
                    "border-style": "dashed"
                })
            });
            $("html").on("dragleave", function(e) {
                e.preventDefault();
                e.stopPropagation();
                $("#upload-form").css({
                    "border": "none"
                })
            });

            $("html").on("drop", function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
            //first upload
            // Drag enter
            $('#upload-form1').on('dragenter', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).css({
                    "border-color": "#666",
                    "border-style": "dashed"
                })
            });
            $('#upload-form1').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $("#upload-file1").click();
            });
            $("#upload-file1").on("click", function(e) {
                e.stopPropagation();
            })
            $('#upload-form1').on('dragleave', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).css({
                    "border": "none"
                })
            });

            // Drag over
            $('#upload-form1').on('dragover', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).css({
                    "border-color": "#666",
                    "border-style": "dashed"
                })
            });
            $('#upload-form1').on('drop', function(e) {
                e.stopPropagation();
                e.preventDefault();

                var file = e.originalEvent.dataTransfer.files;
                var fd = new FormData();
                fd.append('myfile', file[0]);
                fd.append('name', file[0].name.split('.').slice(0, -1).join('.'));
                $(".loading-wrapper").css("display", "flex")
                uploadData(fd, 1);
            });

            $("#upload-file1").change(function() {
                var fd = new FormData();

                var files = $('#upload-file1')[0].files[0];
                console.log(files)
                fd.append('name', files.name.split('.').slice(0, -1).join('.'));
                fd.append('myfile', files);
                $(".loading-wrapper").css("display", "flex")
                uploadData(fd, 1);
            });

            //second upload

            $('#upload-form2').on('dragenter', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).css({
                    "border-color": "#666",
                    "border-style": "dashed"
                })
            });
            $('#upload-form2').on('click', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $("#upload-file2").click();
            });
            $("#upload-file2").on("click", function(e) {
                e.stopPropagation();
            })
            $('#upload-form2').on('dragleave', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).css({
                    "border": "none"
                })
            });

            // Drag over
            $('#upload-form2').on('dragover', function(e) {
                e.stopPropagation();
                e.preventDefault();
                $(this).css({
                    "border-color": "#666",
                    "border-style": "dashed"
                })
            });
            $('#upload-form2').on('drop', function(e) {
                e.stopPropagation();
                e.preventDefault();

                var file = e.originalEvent.dataTransfer.files;
                var fd = new FormData();
                fd.append('myfile', file[0]);
                fd.append('name', file[0].name.split('.').slice(0, -1).join('.'));
                $(".loading-wrapper").css("display", "flex")
                uploadData(fd, 2);
            });

            $("#upload-file2").change(function() {
                var fd = new FormData();

                var files = $('#upload-file2')[0].files[0];
                console.log(files)
                fd.append('name', files.name.split('.').slice(0, -1).join('.'));
                fd.append('myfile', files);
                $(".loading-wrapper").css("display", "flex")
                uploadData(fd, 2);
            });


            function uploadData(formdata, index) {
                if (index == 1) {
                    $("#upload-form1").css({
                        "border": "none"
                    })
                } else {
                    $("#upload-form2").css({
                        "border": "none"
                    })
                }
                var uploadUrl = SITE + "ajaxfiles/upd_empdocs.php"
                $.ajax({
                    url: uploadUrl,
                    type: 'post',
                    data: formdata,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        uploadedItem(response, index);
                        $(".loading-wrapper").css("display", "none")
                    }
                });
            }

            function uploadedItem(uploadedItem, index) {
                var dom = `<div class="uploaded-item">
                        <label>` + uploadedItem + `</label>
                	    <input type="hidden" name="employee_docname[]" value="` + uploadedItem + `" class="upload_item">
                        <button type="button" class="remove-uploaded-item">&times;</button>
                    </div>`
                if (index == 1) {
                    $("#uploaded-group1").append(dom);
                } else if (index == 2) {
                    $("#uploaded-group2").append(dom);
                } else {
                    $("#added-number-group").append(dom);
                }
            }
            $(document).on("click", ".remove-uploaded-item", function() {
                $(this).parent().remove()
            })

            $("#add-contact-number").on("click", function(e) {
                e.stopPropagation();
                var value = $(this).parent().children('input')[0].value
                uploadedCotactItem(value, 3)
                $(this).parent().children('input')[0].value = ''
            })

            function uploadedCotactItem(valuel) {
                var dom = `<div class="uploaded-item">
                        <label>` + valuel + `</label>
                	    <input type="hidden" name="contact_number[]" value="` + valuel + `" class="contact_number">                	    
                        <button type="button" class="remove-uploaded-item">&times;</button>
                        </div>`;

                $("#added-number-group").append(dom);

            }

            $("#fileuploader_doc1").uploadFile({
                url: SITE + "ajaxfiles/upd_jobdocs.php",
                fileName: "myfile",
                returnType: "json",
                showDelete: true,
                showDownload: false,
                statusBarWidth: 600,
                dragdropWidth: 600,
                maxFileCount: 1,
                acceptFiles: "image/*",
                dynamicFormData: function() {
                    var data = {
                        name: document.getElementById('job_doc1').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('job_docname1').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_jobdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('job_docname1').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });
            $("#fileuploader_doc2").uploadFile({
                url: SITE + "ajaxfiles/upd_jobdocs.php",
                fileName: "myfile",
                returnType: "json",
                showDelete: true,
                showDownload: false,
                statusBarWidth: 600,
                dragdropWidth: 600,
                maxFileCount: 1,
                acceptFiles: "image/*",
                dynamicFormData: function() {
                    var data = {
                        name: document.getElementById('job_doc2').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('job_docname2').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_jobdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('job_docname2').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });
            $("#fileuploader_doc3").uploadFile({
                url: SITE + "ajaxfiles/upd_jobdocs.php",
                fileName: "myfile",
                returnType: "json",
                showDelete: true,
                showDownload: false,
                statusBarWidth: 600,
                dragdropWidth: 600,
                maxFileCount: 1,
                acceptFiles: "image/*",
                dynamicFormData: function() {
                    var data = {
                        name: document.getElementById('job_doc3').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('job_docname3').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_jobdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('job_docname3').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });
            $("#fileuploader_doc4").uploadFile({
                url: SITE + "ajaxfiles/upd_jobdocs.php",
                fileName: "myfile",
                returnType: "json",
                showDelete: true,
                showDownload: false,
                statusBarWidth: 600,
                dragdropWidth: 600,
                maxFileCount: 1,
                acceptFiles: "image/*",
                dynamicFormData: function() {
                    var data = {
                        name: document.getElementById('job_doc4').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('job_docname4').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_jobdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('job_docname4').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });
            $("#fileuploader_doc5").uploadFile({
                url: SITE + "ajaxfiles/upd_jobdocs.php",
                fileName: "myfile",
                returnType: "json",
                showDelete: true,
                showDownload: false,
                statusBarWidth: 600,
                dragdropWidth: 600,
                maxFileCount: 1,
                acceptFiles: "image/*",
                dynamicFormData: function() {
                    var data = {
                        name: document.getElementById('job_doc5').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('job_docname5').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_jobdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('job_docname5').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });
            $("#fileuploader_doc6").uploadFile({
                url: SITE + "ajaxfiles/upd_jobdocs.php",
                fileName: "myfile",
                returnType: "json",
                showDelete: true,
                showDownload: false,
                statusBarWidth: 600,
                dragdropWidth: 600,
                maxFileCount: 1,
                acceptFiles: "image/*",
                dynamicFormData: function() {
                    var data = {
                        name: document.getElementById('job_doc6').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('job_docname6').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_jobdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('job_docname6').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });
            $("#fileuploader_doc7").uploadFile({
                url: SITE + "ajaxfiles/upd_jobdocs.php",
                fileName: "myfile",
                returnType: "json",
                showDelete: true,
                showDownload: false,
                statusBarWidth: 600,
                dragdropWidth: 600,
                maxFileCount: 1,
                acceptFiles: "image/*",
                dynamicFormData: function() {
                    var data = {
                        name: document.getElementById('job_doc7').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('job_docname7').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_jobdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('job_docname7').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });
            $("#fileuploader_doc8").uploadFile({
                url: SITE + "ajaxfiles/upd_jobdocs.php",
                fileName: "myfile",
                returnType: "json",
                showDelete: true,
                showDownload: false,
                statusBarWidth: 600,
                dragdropWidth: 600,
                maxFileCount: 1,
                acceptFiles: "image/*",
                dynamicFormData: function() {
                    var data = {
                        name: document.getElementById('job_doc8').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('job_docname8').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_jobdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('job_docname8').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });
        });
    </script>

    <?php include('new_backend_footer.php'); ?>

</body>

</html>