<?php
include_once("config.ini.php");
$slug = $_GET['people_id'];
$item = [];
if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'employees';
    header("Location:" . SITE . "login");
}


if (isset($slug) && !empty($slug)) {

    $stmt = $dbh->prepare("SELECT a.id_employee,a.employee_id,u.id FROM employees as a
    LEFT JOIN users AS u ON a.employee_id = u.customer_number
    WHERE a.id_employee=? and a.id_user= ?");
    $stmt->bindValue(1, $slug, PDO::PARAM_INT);
    $stmt->bindValue(2, $userdata['uid'], PDO::PARAM_INT);
    $tmp = $stmt->execute();
    $aux = '';
    if ($tmp && $stmt->rowCount() > 0) {
        $item = $stmt->fetch(PDO::FETCH_OBJ);
    }
}


if (empty($item)) {
    header('location: ../people');
}


// if ($userdata['account_type'] == 'Individual')
//     header("Location:" . SITE . "welcome");

//include('include_doctype.php');
?>

<?php
$page_title = "PLANIVERSITY - People Details";
include('templates/header.php'); ?>
<link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
<link href="<?php echo SITE; ?>js/upload/uploadfile.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<script src='https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>
<script src="<?php echo SITE; ?>js/upload/jquery.uploadfile.js"></script>
<script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
<script src="<?php echo SITE; ?>js/flexcroll.js"></script>
<link href="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo SITE; ?>js/node_modules/php-date-formatter/js/php-date-formatter.min.js"></script>
<script src="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.js?v=3"></script>

<script>
    var ID = '<?php echo $slug; ?>';
    var EID = '<?php echo $item->id; ?>';
</script>

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

    .migration-tools {
        margin-top: 50px;
    }

    #upload-demo {
        width: 300px;
        height: 300px;
        padding-bottom: 25px;
        margin: auto;
    }

    .menu-filter {
        background: #0d256c;
    }

    ul.navbar-nav.people-nav li a {
        color: #fff;
    }

    ul.navbar-nav.people-nav li {
        margin-left: 20px;
    }

    a.nav-link.active {
        color: #f4a134 !important;
    }

    .user-info-text {
        position: relative;
        top: 15px;
        left: 20px;
    }

    .user-info-text h3 {
        color: #0D256C;
        font-size: 26px;
        font-weight: bold;
    }

    .user-info-text h4 {
        color: #0D256C;
        font-size: 20px;
    }

    .employee-save-btn {
        width: 100%;
        font-weight: 500;
    }

    h3.section-title {
        font-size: 20px;
        color: #1F74B7;
        margin: 20px 0px;
    }

    .file-upload-container:hover {
        background: aliceblue;
    }

    .file-upload-container {
        border: 1px solid #eaecf0;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        border-radius: 10px;
        margin: 20px;
    }

    .file-upload-container label {
        font-size: 16px;
        color: #007bff;
        margin: 0;
    }

    .file-upload-container label i {
        border: 2px solid #eaecf0;
        padding: 10px;
        border-radius: 10px;
        margin: 0;
    }

    .instructions {
        font-size: 14px;
        color: #333;
        margin-top: 10px;
    }

    .instructions span {
        color: #0290fc;
        font-weight: 600;
    }

    .other-section {
        margin-top: 175px;
    }

    button.navbar-toggler {
        color: #fff;
    }

    .event_list {
        height: calc(370px - 70px);
        overflow-y: auto;
        min-height: auto;
    }

    .event_list::-webkit-scrollbar {
        width: 12px;
    }

    .event_list::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
    }

    .event_list::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
    }

    .loading_screen {
        position: relative;
        left: 48%;
        top: 25px;
        z-index: 999;
    }

    h3.no-found {
        position: relative;
        font-size: 16px;
        font-weight: 400;
        top: 40px;
        color: #0c246b;
        text-align: center;
    }

    @media (max-width: 992px) {
        .menu-filter {
            margin-top: 70px;
        }

        .other-section {
            margin-top: 20px;
        }

        ul.navbar-nav.people-nav li {
            display: block;
            margin: 0 12px;
            padding-top: 10px;
            line-height: 10px;
        }
    }
</style>

</head>

<body>

    <?php $page_name = 'people';
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
            <h3 class="page-headr color-1F74B7">People Details</h3>

            <div class="menu-filter">

                <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa fa-bars"></span> Filter
                    </button>
                    <div class="collapse navbar-collapse" id="ftco-nav">

                        <ul class="navbar-nav people-nav">
                            <li class="nav-item"><a href="<?= SITE ?>people" class="nav-link">Add New User</a></li>
                            <li class="nav-item"><a href="<?= SITE ?>people-groups" class="nav-link">Groups</a></li>
                            <li class="nav-item"><a href="<?= SITE ?>people" class="nav-link active">User Profile</a></li>
                        </ul>

                    </div>

                </nav>


            </div>

            <div class="row">

                <div class="col-lg-7 user-section">
                    <form class="form-horizontal" id="people_update_form">
                        <div class="row mt-4 mb-4">

                            <div class="col-sm-12 ml-4">

                                <div class="form-group profile_section mt-3">

                                    <div class="profile_image_section">
                                        <div class="uploaded_image profile_image">
                                            <img src="<?= SITE . "assets/images/user_profile.png"; ?>" width="90" height="90" class="profile_picture_place">
                                        </div>

                                        <div class="user-info-text">
                                            <h3 id="customer_name">User Name</h3>
                                            <h4>Customer#: <span id="customer_ref_number"></span></h4>
                                        </div>

                                        <div class="profile_action_section">

                                        </div>
                                    </div>

                                    <!-- <input type="hidden" name="base64_image" id="base64_image" class="form-control image_field" readonly>
                                    <input type="hidden" name="photo_connect" id="photo_connect" class="form-control image_field" value="0" readonly>
                                    <input type="hidden" name="photo" id="photo" class="form-control image_field" readonly> -->

                                </div>

                            </div>

                        </div>

                        <div class="card-box">
                            <div class="form-wrap mt-4">

                                <fieldset>
                                    <!-- <div class="row">

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

                                            </div>
                                        </div>

                                    </div> -->



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
                                                    <input name="employee_veteran" type="radio" id="radio-yes" value="1">
                                                    <label for="radio-yes">Yes</label>
                                                </div>
                                                <div class="radio form-check-inline">
                                                    <input name="employee_veteran" type="radio" id="radio-no" value="0">
                                                    <label for="radio-no">No</label>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="eid" class="rid" readonly>



                                    </div>
                                </fieldset>

                            </div>
                        </div>

                        <div class="card-box">

                            <h3 class="section-title">Travel Group</h3>

                            <fieldset>

                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="emp-form-label"></label>

                                            <select class="form-control travel_group" name="travel_group" id="travel_group">
                                                <option value="">Select a option</option>

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

                            <h3 class="section-title">Role</h3>

                            <fieldset>

                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="emp-form-label"></label>

                                            <select class="form-control travel_group" name="role" id="role">
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
                            <button type="submit" class="save-changes-btn submit_action_button employee-save-btn" id="employee_add"> Save Updates</button>
                        </div>

                    </form>
                </div>

                <div class="col-lg-5 other-section">

                    <div class="card-box">

                        <h3 class="section-title">Update Profile Photo</h3>

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


                                    </div>


                                </div>

                            </div>

                        </fieldset>

                    </div>

                    <div class="card-box">
                        <form class="form-horizontal" id="migration_process">
                            <h3 class="section-title">Migration Tools</h3>

                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="event-form-label">Customer Number</label>
                                    <div class="form-group">
                                        <input name="migration_customer_number" type="text" class="account-form-control form-control input-lg inp1" placeholder="Customer Number">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <label class="event-form-label">Packet Number</label>
                                    <div class="form-group">
                                        <input name="migration_packet_number" type="text" class="account-form-control form-control input-lg inp1" placeholder="Packet Number">
                                    </div>
                                </div>

                            </div>

                            <input type="hidden" name="pid" class="rid" readonly>

                            <div class="col-sm-12 save-btn-wrapper">
                                <button type="submit" class="save-changes-btn process_action_button employee-save-btn" id="employee_add"> Process</button>
                            </div>

                        </form>

                    </div>

                    <div class="card-box">
                        <h3 class="section-title">User Previous Plans</h3>

                        <div class="loading_screen" id="previous_plan_loading">
                            <div class="spinner-border text-primary"></div>
                        </div>

                        <div class="event_list" id="event_list">

                        </div>
                    </div>


                </div>

            </div>
        </div>

        <div class="container-fluid">
            <div class="row mt-4">
                <div class="col-lg-12">

                    <div class="card-box mt-3">

                        <h3 class="section-title">Migration List</h3>

                        <div class="table-wrap">
                            <div class="table-responsive table-striped">

                                <table id="migration_list" class="display_table table " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="6%">Created Date</th>
                                            <th width="6%">Trip ID</th>
                                            <th width="8%">Status</th>
                                            <th width="8%">Last Update</th>
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
    <script src="<?php echo SITE; ?>js/employee_details.js?v=<?= time() ?>"></script>

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