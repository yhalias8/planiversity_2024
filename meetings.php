<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'jobs';
    header("Location:" . SITE . "login");
}
if ($userdata['account_type'] == 'Individual')
    header("Location:" . SITE . "welcome");
include('include_doctype.php');
//print_r($_POST);
//die();
$msg = '';

?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>PLANIVERSITY - Meetings</title>

    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.ico" type="image/x-icon">
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
            background: #55bbe4 !important;
        }

        label.input-file:hover {
            background: white;
            width: 100%;
        }

        .ajax-file-upload {
            padding: 6px 16px;
            background: #55bbe4;
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
            background: #55bbe4;
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

    <?php $page_name = 'meetings';
    include('templates/dashboard_header.php'); ?>
    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu">
                    <?php
                    if ($userdata['account_type'] == 'Business' || $userdata['account_type'] == 'Admin') { ?>
                        <li>
                            <a href="<?php echo SITE ?>employees" class="left-nav-button">
                                <i class="mdi mdi-account-multiple"></i>Employee
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SITE ?>jobs" class="left-nav-button">
                                <i class="mdi mdi-briefcase"></i>Jobs
                            </a>
                        </li>
                        <li class="a-menu-element">
                            <a href="<?php echo SITE ?>events" class="left-nav-button">
                                <i class="mdi mdi-calendar"></i>Events
                            </a>
                        </li>
                        <li class="a-menu-element">
                            <a href="<?php echo SITE ?>meetings" style="background: #075b9e !important" class="left-nav-button">
                                <i class="mdi mdi-clock"></i>Meetings
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
            <h3 class="page-headr color-1F74B7">Meetings</h3>
            <div class="row">
                <div class="col-lg-7 employee-left-side">
                    <div><?php echo $msg; ?></div>
                    <div class="card-box">
                        <form id="meeting_form" method="post">
                            <div class="form-wrap">
                                <fieldset>
                                    <div class="row">

                                        <div class="col-sm-12">
                                            <label class="event-form-label">Meeting Title</label>
                                            <div class="form-group">
                                                <input name="title" type="text" class="account-form-control form-control input-lg inp1" placeholder="Meeting Title" required>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label class="event-form-label">Customer Name</label>
                                            <div class="form-group">
                                                <input name="customer_name" type="text" class="account-form-control form-control input-lg inp1" placeholder="Customer Name">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label class="event-form-label">Meeting Location</label>
                                            <div class="form-group">
                                                <input name="location" type="text" id="location" class="account-form-control form-control input-lg inp1" placeholder="Meeting Location">
                                            </div>
                                        </div>
                                        <!-- <div class="col-sm-6">
                                            <label class="event-form-label">Country</label>
                                            <div class="form-group">
                                                <input name="country" type="text" class="account-form-control form-control input-lg inp1" placeholder="Country">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="event-form-label">City</label>
                                            <div class="form-group">
                                                <input name="city" type="text" class="account-form-control form-control input-lg inp1" placeholder="City">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="event-form-label">state</label>
                                            <div class="form-group">
                                                <input name="state" type="text" class="account-form-control form-control input-lg inp1" placeholder="State">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="event-form-label">Zip</label>
                                            <div class="form-group">
                                                <input name="zip" type="text" class="account-form-control form-control input-lg inp1" placeholder="Zip Code">
                                            </div>
                                        </div> -->
                                        <div class="col-sm-4">
                                            <label class="event-form-label">Meeting Time From</label>
                                            <div class="form-group picker-group">
                                                <input id="event_time_from" name="event_time_from" type="time" class="account-form-control form-control input-lg inp1" placeholder="Meeting Time From" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="event-form-label">Meeting Time To</label>
                                            <div class="form-group picker-group">
                                                <input id="event_time_to" name="event_time_to" type="time" class="account-form-control form-control input-lg inp1" placeholder="Meeting Time To" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="event-form-label">Meeting Date</label>
                                            <div class="form-group picker-group">
                                                <input id="event_date" name="event_date" type="date" class="account-form-control form-control input-lg inp1" placeholder="Meeting Date" required>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label class="event-form-label">Meeting Overview</label>
                                            <div class="form-group">
                                                <textarea name="overview" class="textarea-control form-control input-lg" cols="" rows="3" placeholder="Meeting Overview"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <label class="event-form-label">Special Instructions</label>
                                            <div class="form-group">
                                                <textarea name="instructions" class="textarea-control form-control input-lg" cols="" rows="3" placeholder="Special Instructions and requests"></textarea>
                                            </div>
                                        </div>
                                        <label class="emp-form-label">Add Invitees</label>

                                        <div class="col-sm-12 number-group">
                                            <div class="form-group process-btn">

                                                <input type="text" class="form-control account-form-control input-lg" id="invitee_type" placeholder="Add Invitees" list="invitee_id_datalist" autocomplete="off" autofocus="">
                                                <datalist id="invitee_id_datalist" class="invitee_list">
                                                </datalist>
                                                <Button type="button" class="btn btn-info" id="add_invitee"><span class="fa fa-plus-circle"></span></Button>

                                            </div>
                                            <div id="added-number-group">
                                            </div>
                                        </div>

                                        <div class="col-sm-12 mt-3">
                                            <label>Send notification to invitee?</label>
                                            <div class="form-group radio-form-group">
                                                <div class="checkbox form-check-inline">
                                                    <input type="checkbox" id="agree" value="1" name="notification">
                                                    <label for="agree">Send notification</label>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-sm-12 save-btn-wrapper">
                                            <!-- <input name="create_event" value="Create Meeting" type="submit" class="event-action-btn employee-save-btn" /> -->
                                            <button type="submit" class="save-changes-btn submit_action_button employee-save-btn"> Save Meeting</button>
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
                        <div class="upload-wrap2" id="upload-form">
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
                                    <input type="file" class="display-none" name="job_doc1" id="upload-file">
                                    <input type="hidden" name="job_docname1" id="job_docname1" value="">
                                    <small>Drag & drop or <label class="text-blue point bold" for="upload-file">browse</label> your file here</small>
                                </div>
                            </div>
                        </div>
                        <h5 class="itineraries-text size-22 mt-3">Documents</h5>
                        <h6 class="itineraries-text">Your documents</h6>
                        <div class="upload-wrapper1">
                            <div id="uploaded-group1">
                            </div>
                        </div>
                        <div id="eventsmessage"></div>

                        <div class="col-sm-12">
                            <div class="save-btn-wrapper">
                                <!--<button type="submit" class="save-changes-btn">Save</button>-->
                                <!--<input name="job_add" id="job_add" type="button" class="save-changes-btn-new button button2" value="Save">-->
                            </div>
                            <br clear="all" />
                            <div class="error_cont">
                                <div id="error_list" class="show_error"></div>
                                <div id="loading_list" style="display:none;"><img src="<?php echo SITE; ?>images/loading.gif" /></div>
                            </div>
                            <div id="commBox">
                                <!--<h3 class="comment-hd">Added Comments <a href = "" data-toggle="modal" data-backdrop data-target="#add-comment-modal"><i class = "fa fa-comment"></i>Add Comment</a></h3>-->
                                <h3 class="itineraries-text size-22 mt-3 add-comment-header">
                                    Added Comments
                                    <a href="" data-toggle="modal" data-backdrop data-target="#add-comment-modal">
                                        <span class="fa fa-plus-circle add-btn point"></span>
                                        Add new Comment
                                    </a>
                                </h3>
                                <h6 class="itineraries-text">Upload your Equipment Documents</h6>

                                <?php
                                $stmt = $dbh->prepare("SELECT * FROM `events_comments` WHERE user_id=" . $userdata['uid']);
                                $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                $tmp = $stmt->execute();
                                $aux = '';
                                if ($tmp && $stmt->rowCount() > 0) {
                                    $user_ = $stmt->fetchAll(PDO::FETCH_OBJ);

                                    foreach ($user_ as $user_row) {
                                        echo '<div id="dv-' . $user_row->id . '" class = "comment-item picker-group w-100">
                                                ' . $user_row->comment . '.
                                                    <a href = "#" onClick="delComm(' . $user_row->id . ')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"></a>
                                                <button type="button" class="remove-uploaded-item">&times;</button>
                                            </div>';
                                    }
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>


    <div class="container-fluid">
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="card-box">


                    <div class="table-wrap">
                        <div class="table-responsive table-striped">

                            <table id="meeting_list" class="display_table table " cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="10%">Meeting Title</th>
                                        <th width="8%">Meeting From</th>
                                        <th width="6%">Meeting To</th>
                                        <th width="8%">Meeting Location</th>
                                        <th width="6%" align="center">Customer Name</th>
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
    <!-- Modal -->



    <div class="modal fade modal-blur" data-backdrop="true" id="update_meeting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Update Meeting</h4>
                </div>
                <form id="meeting_form_update">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">

                                <div class="form-group custom-group">
                                    <p class="event-title">Meeting Title</p>
                                    <input type="text" class="form-control" name="title" id="e_title" placeholder="Meeting Title">
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group custom-group">
                                    <p class="event-title">Customer Name</p>
                                    <input type="text" class="form-control " name="customer_name" id="e_customer_name" placeholder="Customer Name">
                                </div>

                            </div>

                            <div class="col-md-6 col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <p class="event-title">Meeting Location</p>
                                    <input name="location" id="e_location" type="text" class="form-control" placeholder="Meeting Location">
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-4 col-lg-4 col-sm-4">

                                <div class="form-group custom-group">
                                    <p class="event-title">Meeting Time From</p>
                                    <input type="time" class="form-control" name="event_time_from" id="e_event_time_from">
                                </div>

                            </div>


                            <div class="col-md-4 col-lg-4 col-sm-4">

                                <div class="form-group custom-group">
                                    <p class="event-title">Meeting Time To</p>
                                    <input type="time" class="form-control" name="event_time_to" id="e_event_time_to">
                                </div>

                            </div>



                            <div class="col-md-4 col-lg-4 col-sm-4">

                                <div class="form-group custom-group">
                                    <p class="event-title">Meeting Date</p>
                                    <input type="date" class="form-control" name="event_date" id="e_event_date">
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <p class="event-title">Meeting Overview</p>
                                    <textarea name="overview" id="e_overview" class="textarea-control form-control input-lg" cols="" rows="1" placeholder="Meeting overview details"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <p class="event-title">Special Instructions</p>
                                    <textarea name="instructions" id="e_instructions" class="textarea-control form-control input-lg" cols="" rows="1" placeholder="Special Instructions and requests"></textarea>
                                </div>
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <p class="event-title">Invitees</p>

                                    <div id="meeting_invitee_list"></div>


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

    <div class="modal fade" id="add-comment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content wht-bg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Comment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <textarea class="textarea-control form-control input-lg" id="user_com" name="user_com" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <button type="button" id="comButton" class="add-user-btn">Add Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>
    <script src="<?php echo SITE; ?>js/meetings.js?v=202211"></script>

    <script>
        function initMap() {
            var originInput = document.getElementById('location');
            var e_originInput = document.getElementById('e_location');
            var originPlaceAutocomplete = new google.maps.places.Autocomplete(originInput);
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

            if (emp_id === undefined || emp_id === null || emp_id === "") {

                swal({
                    title: "Select a valid invitee",
                    type: "error",
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: 'swal-height'
                });

            } else if ($.inArray(emp_id, emp_map) > -1) {

                swal({
                    title: "Invitee is already in this list",
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

        $("#comButton").click(function() {
            $.ajax({
                    method: "POST",
                    url: "/ajaxfiles/add_documents.php",
                    data: {
                        purpose: "addEventComment",
                        uid: <?php echo $userdata['uid'] ?>,
                        comment: $("#user_com").val()
                    }
                })
                .done(function(msg) {
                    alert("Comment saved successfully!");
                    data = JSON.parse(msg);
                    $("#commBox").append('<div id="dv-' + data.ins_ud + '" class = "comment-item picker-group w-100">' + $("#user_com").val() + '<a onClick="delComm(' + data.ins_ud + ')" href = "#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"></a><button type="button" class="remove-uploaded-item">&times;</button></div>');
                    $("#add-comment-modal").hide();
                    $("#add-comment-modal").html('');
                });
        });

        function delComm(id) {
            $.ajax({
                    method: "POST",
                    url: "/ajaxfiles/add_documents.php",
                    data: {
                        purpose: "DelEventComment",
                        commid: id
                    }
                })
                .done(function(msg) {
                    alert("Comment deleted successfully!");
                    $('#dv-' + id).remove();
                });

        }


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

        // Drag enter
        $('#upload-form').on('dragenter', function(e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).css({
                "border-color": "#666",
                "border-style": "dashed"
            })
        });
        $('#upload-form').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            $("#upload-file").click();
        });
        $("#upload-file").on("click", function(e) {
            e.stopPropagation();
        })
        $('#upload-form').on('dragleave', function(e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).css({
                "border": "none"
            })
        });

        // Drag over
        $('#upload-form').on('dragover', function(e) {
            e.stopPropagation();
            e.preventDefault();
            $(this).css({
                "border-color": "#666",
                "border-style": "dashed"
            })
        });
        $('#upload-form').on('drop', function(e) {
            e.stopPropagation();
            e.preventDefault();

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();
            fd.append('myfile', file[0]);
            fd.append('name', file[0].name.split('.').slice(0, -1).join('.'));
            $(".loading-wrapper").css("display", "flex")
            uploadData(fd);
        });

        $("#upload-file").change(function() {
            var fd = new FormData();

            var files = $('#upload-file')[0].files[0];
            console.log(files)
            fd.append('name', files.name.split('.').slice(0, -1).join('.'));
            fd.append('myfile', files);
            $(".loading-wrapper").css("display", "flex")
            uploadData(fd, 1);
        });

        function uploadData(formdata) {
            $("#upload-form").css({
                "border": "none"
            })
            var uploadUrl = SITE + "ajaxfiles/upd_empdocs.php"
            $.ajax({
                url: uploadUrl,
                type: 'post',
                data: formdata,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    $(".loading-wrapper").css("display", "none")
                    uploadedItem(response, 1);
                }
            });
        }

        function empItem(item_id, item_name, item_email) {
            var dom = `<div class="uploaded-item">
                                <label>` + item_name + `</label>
                                <input type="hidden" name="invitee_id[]" value="` + item_id + `" class="emp_map">
                                <input type="hidden" name="invitee_email[]" value="` + item_email + `">
                                <button type="button" class="remove-uploaded-item">&times;</button>
                                </div>`;

            $("#added-number-group").append(dom);

        }

        function uploadedItem(uploadedItem, index) {

            var dom = `<div class="uploaded-item">
                                <label>` + uploadedItem + `</label>
                                <input type="hidden" name="employee_docname[]" value="` + uploadedItem + `" class="upload_item">
                                <button type="button" class="remove-uploaded-item">&times;</button>
                                </div>`;

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

        $("#add-job-invitee").on("click", function(e) {
            e.stopPropagation();
            var value = $(this).parent().children('input')[0].value
            uploadedItem(value, 3)
            $(this).parent().children('input')[0].value = ''
        })


        $(document).ready(function() {
            $("#fileuploader_doc1").uploadFile({
                url: SITE + "ajaxfiles/upd_eventdocs.php",
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
                        $.post(SITE + "ajaxfiles/del_eventdoc.php", {
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
        });
    </script>

    </script>
    <style>
        .footer {
            position: inherit !important;
        }
    </style>

    <?php include('new_backend_footer.php'); ?>
    <script type="text/javascript">
        function see_detail(id) {
            $('#details' + id).toggle('fade');
        }
    </script>

</body>

</html>