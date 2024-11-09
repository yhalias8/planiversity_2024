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
$page_title = "PLANIVERSITY - People Groups";
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
<script src="<?php echo SITE; ?>js/responsive-nav.js"></script>
<script src="<?php echo SITE; ?>js/flexcroll.js"></script>

<style>
    .footer {
        position: absolute !important;
    }
</style>

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

            <h3 class="page-headr color-1F74B7">Group</h3>


            <div class="menu-filter">

                <nav class="navbar navbar-expand-lg ftco_navbar ftco-navbar-light" id="ftco-navbar">

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa fa-bars"></span> Filter
                    </button>
                    <div class="collapse navbar-collapse" id="ftco-nav">

                        <ul class="navbar-nav people-nav">
                            <li class="nav-item"><a href="<?= SITE ?>people" class="nav-link">Add New User</a></li>
                            <li class="nav-item"><a href="<?= SITE ?>people-groups" class="nav-link active">Groups</a></li>
                        </ul>

                    </div>

                </nav>


            </div>


        </div>

        <div class="container-fluid">
            <div class="row mt-2">
                <div class="col-lg-12">
                    <div class="card-box">

                        <a class="new-account-btn" data-toggle="modal" data-target="#add_modal">Create New Group
                            <i class="fa fa-plus-circle"></i>
                        </a>

                        <div class="table-wrap mt-4">
                            <div class="table-responsive table-striped">

                                <table id="group_list" class="display_table table " cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="10%">Created At</th>
                                            <th width="10%">Group Name</th>
                                            <th width="12%">Description</th>
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

    <div class="modal fade modal-blur show" data-backdrop="true" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Add Group</h4>
                </div>
                <form id="addForm" novalidate="novalidate">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">

                                <div class="form-group custom-group">
                                    <p class="event-title">Group Name</p>
                                    <input type="text" class="form-control" name="group_name">
                                </div>
                                <label id="group_name-error" class="error group_name_error custom-label" for="group_name"></label>


                                <div class="form-group custom-group">
                                    <p class="event-title">Description</p>
                                    <textarea type="text" class="form-control" name="description"></textarea>
                                </div>
                                <label id="description-error" class="error description_error custom-label" for="description"></label>

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

    <div class="modal fade modal-blur show" data-backdrop="true" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Update Group</h4>
                </div>
                <form id="updateForm" novalidate="novalidate">
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">

                                <div class="form-group custom-group">
                                    <p class="event-title">Group Name</p>
                                    <input type="text" class="form-control" name="group_name" id="group_name">
                                </div>
                                <label id="group_name-error" class="error group_name_error custom-label" for="group_name"></label>


                                <div class="form-group custom-group">
                                    <p class="event-title">Description</p>
                                    <textarea type="text" class="form-control" name="description" id="description"></textarea>
                                </div>
                                <label id="description-error" class="error description_error custom-label" for="description"></label>

                            </div>

                        </div>

                        <input type="hidden" class="form-control" name="id" id="eid" readonly>


                    </div>
                    <div class="modal-footer">
                        <button type="update" class="btn btn-primary action_button update_button">Update</button>
                        <button type="button" class="btn btn-danger action_button btn-close-modal" data-dismiss="modal">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="<?php echo SITE; ?>js/people_groups.js?v=<?= time(); ?>"></script>

    <?php include('new_backend_footer.php'); ?>

</body>

</html>