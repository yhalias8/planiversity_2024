<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'employees';
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
    <!-- <title>PLANIVERSITY - Employees</title> -->
    <link rel="shortcut icon" href="<?php echo SITE; ?>favicon.png" type="image/x-icon">
    <link rel="icon" href="<?php echo SITE; ?>favicon.png" type="image/x-icon">
    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <link href="<?php echo SITE; ?>js/upload/uploadfile.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />

    <script>
        var SITE = '<?php echo SITE; ?>';
    </script>

    <?php
    $page_title = "PLANIVERSITY - People";
    include('templates/header.php'); ?>
    <script src='https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
    <script src="<?php echo SITE; ?>js/upload/jquery.uploadfile.js"></script>
    <script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
    <script src="<?php echo SITE; ?>js/flexcroll.js"></script>
    <link href="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo SITE; ?>js/node_modules/php-date-formatter/js/php-date-formatter.min.js"></script>
    <script src="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.js?v=3"></script>

    <style>
        .form-group.process-btn {
            display: flex;
        }

        .pac-container {
            z-index: 10000 !important;
        }

        .action_button {
            font-size: 12px;
        }

        #update_event,
        #add-comment-modal {
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

        label.error {
            color: #f31c1c;
            position: relative;
            bottom: 15px;
            font-size: 13px;
        }

        select#e_employee_gender {
            padding: 5px;
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

        .footer {
            position: inherit !important;
        }

        .btn-mini {
            padding: 4px 10px;
            border: 2px solid #e8e8e8;
            font-size: 12px;
            font-weight: 500;
            color: #333;
        }

        #upload-demo {
            width: 300px;
            height: 300px;
            padding-bottom: 25px;
            margin: auto;
        }
    </style>

</head>

<body>

    <?php //include('include_header.php'); 
    ?>

    <?php $page_name = 'employees';
    include('templates/dashboard_header.php'); ?>

    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu">
                    <?php
                    if ($userdata['account_type'] == 'Business' || $userdata['account_type'] == 'Admin') { ?>
                        <li>
                            <a href="<?php echo SITE ?>employees" style="background: #075b9e !important" class="left-nav-button">
                                <i class="mdi mdi-account-multiple #075b9e !important"></i>Employee
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo SITE ?>jobs" class="left-nav-button">
                                <i class="mdi mdi-briefcase"></i>Jobs
                            </a>
                        </li>
                        <li class="a-menu-element">
                            <a href="<?php echo SITE ?>events" href="" class="left-nav-button">
                                <i class="mdi mdi-calendar"></i>Events
                            </a>
                        </li>
                        <li class="a-menu-element">
                            <a href="<?php echo SITE ?>meetings" class="left-nav-button">
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
            <h3 class="page-headr color-1F74B7">People</h3>
            <form class="form-horizontal" id="employee_form">
                <div class="row">
                    <div class="col-lg-7  employee-left-side">
                        <div class="card-box">
                            <div class="form-wrap">
                                <fieldset>
                                    <div class="row">
                                        <!--<form name="employee_form" id="employee_form" method="post">-->

                                        <div class="col-sm-6">
                                            <div class="form-group profile_section">
                                                <label class="emp-form-label">Photo</label>

                                                <div class="profile_image_section">
                                                    <div class="uploaded_image profile_image">
                                                        <img src="<?= SITE . "assets/images/user_profile.png"; ?>" height="100" class="profile_picture_place">
                                                    </div>

                                                    <div class="profile_action_section">
                                                        <button type="button" class="update_profile"> Upload Profile Image </button>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="base64_image" id="base64_image" class="form-control image_field">

                                            </div>
                                        </div>

                                    </div>

                                    <div class=" row">

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">First Name</label>
                                                <input name="employee_fname" id="employee_fname" type="text" class="account-form-control form-control input-lg inp1" maxlength="50" placeholder="First Name">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Last Name</label>
                                                <input name="employee_lname" id="employee_lname" type="text" class="account-form-control form-control input-lg" placeholder="Last Name">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Email</label>
                                                <input name="employee_email" id="employee_email" type="text" class="account-form-control form-control input-lg" placeholder="Email">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Planiversity customer number</label>
                                                <input name="employee_id" id="employee_id" type="text" class="account-form-control form-control input-lg" placeholder="Customer number">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Address</label>
                                                <input name="employee_address" id="employee_address" type="text" class="account-form-control form-control input-lg" placeholder="Address">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">City</label>
                                                <input name="employee_city" id="employee_city" type="text" class="account-form-control form-control input-lg" placeholder="City">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">State</label>
                                                <input name="employee_state" id="employee_state" type="text" class="account-form-control form-control input-lg" placeholder="State">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Zip Code</label>
                                                <input name="employee_zcode" id="employee_zcode" type="text" class="account-form-control form-control input-lg" placeholder="Zip Code">
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Phone Number</label>
                                                <input name="employee_phone" id="employee_phone" type="text" class="account-form-control form-control input-lg" placeholder="Phone Number">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Social Security Number</label>
                                                <input name="employee_ssn" id="employee_ssn" type="text" class="account-form-control form-control input-lg" placeholder="Social Security Number">
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label class="emp-form-label2">Driver License Number</label>
                                                <input name="employee_dlnumber" id="employee_dlnumber" type="text" class="account-form-control form-control input-lg inp1" placeholder="Driver License Number">
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label class="emp-form-label2">License State</label>
                                                <input name="employee_dlstate" id="employee_dlstate" type="text" class="account-form-control form-control input-lg" placeholder="License State">
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <div class="form-group picker-group">
                                                <label class="emp-form-label2">License Expiry</label>
                                                <input name="employee_dldate" id="employee_dldate" type="date" class="account-form-control form-control input-lg" placeholder="License Expiry">
                                                <!--<span class="fa fa-calendar"></span>-->
                                            </div>
                                        </div>


                                        <div class="col-sm-6">

                                            <div class="form-group picker-group">
                                                <label class="emp-form-label">Birthday</label>
                                                <input name="employee_b" id="employee_b" type="date" class="account-form-control form-control input-lg" placeholder="Birthday">
                                                <!--<span class="fa fa-calendar"></span>-->
                                            </div>
                                        </div>
                                        <div class="col-sm-6">

                                            <div class="form-group">
                                                <label class="emp-form-label">Gender</label>
                                                <div class="select-style">
                                                    <select name="employee_gender" id="employee_gender" class="input-lg select-form-control">
                                                        <option value="m">Male</option>
                                                        <option value="f">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Race</label>
                                                <input name="employee_race" id="employee_race" type="text" class="account-form-control form-control input-lg" placeholder="Race">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Veteran</label>
                                            <div class="form-group radio-form-group">
                                                <div class="radio form-check-inline">
                                                    <input name="employee_veteran" type="radio" id="radio-yes" value="1" name="vetran">
                                                    <label for="radio-yes">Yes</label>
                                                </div>
                                                <div class="radio form-check-inline">
                                                    <input name="employee_veteran" type="radio" id="radio-no" value="0" name="vetran" checked>
                                                    <label for="radio-no">No</label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-sm-12 save-btn-wrapper">
                                            <button type="submit" class="save-changes-btn submit_action_button employee-save-btn" id="employee_add"> Save People</button>
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
                                        <input type="file" class="display-none" name="employee_doc" id="upload-file">
                                        <small>Drag & drop or <label class="text-blue point bold" for="upload-file">browse</label> your file here</small>
                                    </div>
                                </div>
                            </div>
                            <h5 class="itineraries-text size-22 mt-3">Documents</h5>
                            <h6 class="itineraries-text">Your documents</h6>
                            <div class="upload-wrapper1">
                                <div id="uploaded-group">
                                </div>
                                <div id="eventsmessage"></div>
                                <div class="col-sm-12">
                                    <!-- <div class="save-btn-wrapper"> -->
                                    <!--<button type="submit" class="save-changes-btn">Save</button>-->
                                    <!-- <input name="employee_add" id="employee_add" type="submit" class="save-changes-btn employee-save-btn" value="Save Employee"> -->

                                    <!-- </div> -->
                                    <br clear="all" />
                                    <div class="error_cont">
                                        <div id="error_list" class="show_error"></div>
                                        <div id="loading_list" style="display:none;"><img src="<?php echo SITE; ?>images/loading.gif" /></div>
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

                            <table id="employee_list" class="display_table table " cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th width="6%">Photo</th>
                                        <th width="6%">First Name</th>
                                        <th width="6%">Last Name</th>
                                        <th width="8%">Email</th>
                                        <th width="8%" align="center">ID</th>
                                        <th width="8%">Phone</th>
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

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAcMVuiPorZzfXIMmKu2Y2BVBgTFfdhJ2Y&libraries=places&callback=initMap" async defer></script>
    <script src="<?php echo SITE; ?>js/employee.js?v=20230223"></script>

    <script>
        function initMap() {
            var originInput = document.getElementById('employee_address');
            var e_originInput = document.getElementById('e_employee_address');
            var originPlaceAutocomplete = new google.maps.places.Autocomplete(originInput);
            var e_originPlaceAutocomplete = new google.maps.places.Autocomplete(e_originInput);
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
                fd.append('name', files.name.split('.').slice(0, -1).join('.'));
                fd.append('myfile', files);
                $(".loading-wrapper").css("display", "flex")
                uploadData(fd);
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
                        uploadedItem(response);
                    }
                });
            }

            function uploadedItem(uploadedItem) {
                var dom = `<div class="uploaded-item">
                        <label>` + uploadedItem + `</label>
                	    <input type="hidden" name="employee_docname[]" value="` + uploadedItem + `">
                        <button type="button" class="remove-uploaded-item">&times;</button>
                    </div>`
                $("#uploaded-group").append(dom);
            }
            $(document).on("click", ".remove-uploaded-item", function() {
                $(this).parent().remove()
            })

            $("#fileuploader_doc1").uploadFile({
                url: SITE + "ajaxfiles/upd_empdocs.php",
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
                        name: document.getElementById('employee_doc1').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('employee_docname1').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_empdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('employee_docname1').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });

            $("#fileuploader_doc2").uploadFile({
                url: SITE + "ajaxfiles/upd_empdocs.php",
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
                        name: document.getElementById('employee_doc2').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('employee_docname2').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_empdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('employee_docname2').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });

            $("#fileuploader_doc3").uploadFile({
                url: SITE + "ajaxfiles/upd_empdocs.php",
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
                        name: document.getElementById('employee_doc3').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('employee_docname3').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_empdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('employee_docname3').value = '';
                            });
                    }
                    pd.statusbar.hide(); //You choice.
                },
            });

            $("#fileuploader_doc4").uploadFile({
                url: SITE + "ajaxfiles/upd_empdocs.php",
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
                        name: document.getElementById('employee_doc4').value
                    }
                    return data;
                },
                onSuccess: function(files, data, xhr, pd) {
                    document.getElementById('employee_docname4').value = JSON.stringify(data);
                },
                deleteCallback: function(data, pd) {
                    for (var i = 0; i < data.length; i++) {
                        $.post(SITE + "ajaxfiles/del_empdocs.php", {
                                op: "delete",
                                name: data[i]
                            },
                            function(resp, textStatus, jqXHR) {
                                document.getElementById('employee_docname4').value = '';
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