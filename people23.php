<?php
include_once("config.ini.php");
if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'employees';
    header("Location:" . SITE . "login");
}
// if ($userdata['account_type'] == 'Individual')
//     header("Location:" . SITE . "welcome");
?>
<?php
$page_title = "PLANIVERSITY - People";
include('templates/header.php');
?>
<link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<script src='https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
<script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
<script src="<?php echo SITE; ?>js/upload/jquery.uploadfile.js"></script>
<script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
<script src="<?php echo SITE; ?>js/flexcroll.js"></script>
<link href="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITE; ?>js/node_modules/php-date-formatter/js/php-date-formatter.min.js"></script>
<script src="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.js?v=3"></script>
</head>
<body>
    <?php $page_name = 'people';
    include('templates/dashboard_header.php');
    ?>
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
    <div class="loading-wrapper">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div class="wrapper">
        <div class="container-fluid container-top-offset ">
            <h3 class="page-headr color-1F74B7">People</h3>
            <div class="menu-filter">
                <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa fa-bars"></span> Filter
                    </button>
                    <div class="collapse navbar-collapse" id="ftco-nav">
                        <ul class="navbar-nav people-nav">
                            <li class="nav-item"><a href="<?= SITE ?>people" class="nav-link active">Add New User</a></li>
                            <li class="nav-item"><a href="<?= SITE ?>people-groups" class="nav-link">Groups</a></li>
                        </ul>
                    </div>
                </nav>

            </div>
            <form class="form-horizontal" id="employee_form">
                <div class="row">
                    <div class="col-lg-7 user-section">
                        <div class="row mt-4 mb-4">
                            <div class="col-sm-12 ml-4">
                                <div class="form-group profile_section mt-3">
                                    <div class="profile_image_section">
                                        <div class="uploaded_image profile_image">
                                            <img src="<?= SITE . "assets/images/user_profile.png"; ?>" width="90" height="90" class="profile_picture_place">
                                        </div>
                                        <div class="user-info-text">
                                            <h3 id="customer_name">New User</h3>
                                            <h4>Customer#: <span id="customer_ref_number"></span></h4>
                                        </div>
                                        <div class="profile_action_section">
                                        </div>
                                    </div>
                                    <input type="hidden" name="base64_image" id="base64_image" class="form-control image_field" readonly>
                                    <input type="hidden" name="photo_connect" id="photo_connect" class="form-control image_field" value="0" readonly>
                                    <input type="hidden" name="photo" id="photo" class="form-control image_field" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="card-box">
                            <div class="form-wrap">
                                <h3 class="section-title">Add New User</h3>
                                <fieldset>
                                    <div class="row">
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
                                                <div class="input-group">
                                                    <!-- Input field -->
                                                    <input type="text" class="account-form-control form-control employee-email input-lg custom-field" name="employee_email" id="employee_email" placeholder="Email">
                                                    <!-- Button -->
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary go-action email_process" type="button">Go</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <label id="employee_email-error" class="error" for="employee_email"></label>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="emp-form-label">Employee Reference</label>
                                                <div class="input-group">
                                                    <!-- Input field -->
                                                    <input type="text" class="account-form-control form-control input-lg custom-field" name="employee_id" id="employee_id" placeholder="Customer number">
                                                    <!-- Button -->
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary go-action employee_process" type="button">Go</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <label id="employee_id-error" class="error" for="employee_id"></label>
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

                                        <!-- <div class="col-sm-12 save-btn-wrapper">
                                            <button type="submit" class="save-changes-btn submit_action_button employee-save-btn" id="employee_add"> Save People</button>
                                        </div> -->
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 other-section">
                        <div class="card-box">
                            <h3 class="section-title">Upload Profile Photo</h3>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="document_action">
                                            <div class="file-upload-container" onclick="document.getElementById('upload').click();" id="drop-area">
                                                <label for="fileInput"><i class="fa fa-cloud-upload" aria-hidden="true"></i></label>
                                                <div class="instructions">
                                                    <span>Click to upload</span> or drag and drop<br>
                                                    JPG, PNG, GIF (max 800*400px)
                                                </div>
                                            </div>
                                            <!-- <input type="file" id="fileInput" accept=".png, .jpg, .pdf, .doc, .docx" style="display: none;"> -->
                                            <label id="base64_image-error" class="error" for="base64_image"></label>
                                        </div>


                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <div class="card-box">
                            <h3 class="section-title">Add to Group</h3>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="emp-form-label"></label>
                                            <select class="form-control travel_group" name="travel_group">
                                                <option value="">Select a group (optional)</option>
                                                <?php
                                                $stmt = $dbh->prepare("SELECT id,group_name FROM travel_groups WHERE user_id=? ORDER BY id");
                                                $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                                $tmp = $stmt->execute();
                                                $aux = '';
                                                if ($tmp && $stmt->rowCount() > 0) {
                                                    $employees = $stmt->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($employees as $employee) {
                                                        $aux .= '<option value="' . $employee->id . '">'
                                                            . $employee->group_name . '                                 
                                                                </option>';
                                                    }
                                                    echo $aux;
                                                } ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="card-box">

                            <h3 class="section-title">Add a Role</h3>
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="emp-form-label"></label>
                                            <select class="form-control travel_group" name="role">
                                                <option value="">Select a role (optional)</option>
                                                <option value="collaborator">Collaborator</option>
                                                <option value="view_only">View only</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                        <div class="col-sm-12 save-btn-wrapper">
                            <button type="submit" class="save-changes-btn submit_action_button employee-save-btn" id="employee_add"> Save User</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h3 class="section-title">Users</h3>
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
    <script src="<?php echo SITE; ?>js/employee.js?v=<?= time() ?>"></script>
    <script>
        function initMap() {
            var originInput = document.getElementById('employee_address');
            var e_originInput = document.getElementById('e_employee_address');
            var originPlaceAutocomplete = new google.maps.places.Autocomplete(originInput);
            var e_originPlaceAutocomplete = new google.maps.places.Autocomplete(e_originInput);
        }
    </script>
    <?php include('new_backend_footer.php'); ?>
</body>
</html>