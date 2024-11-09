<?php
session_start();
include_once("config.ini.php");

if (!$auth->isLogged()) {
    $_SESSION['redirect'] = 'trip/add-employee-profile/' . $_GET['idtrip'];
    header("Location:" . SITE . "login");
}
// if ($userdata['account_type'] == 'Individual')
//     header("Location:" . SITE . "trip/name/" . $_GET['idtrip']);

$output = '';
include("class/class.TripPlan.php");
$trip = new TripPlan();
$id_trip = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
if (empty($id_trip))
    header("Location:" . SITE . "trip/how-are-you-traveling");
$trip->get_data($id_trip);

if ($trip->error) {
    if ($trip->error == 'error_access') { // popup and 
        header("Location:" . SITE . "trip/how-are-you-traveling");
        //$output = 'You do not have access to this trip';
    } else
        $output = 'A system error has been encountered. Please try again.';
}


$transport = (isset($trip->trip_transport) && !empty($trip->trip_transport)) ? $trip->trip_transport : '';
$tmp = str_replace('(', '', $trip->trip_location_from_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp = str_replace(')', '', $tmp);
$tmp = explode(',', $tmp);
$lat_from = $tmp[0];
$lng_from = $tmp[1];
$tmp = str_replace('(', '', $trip->trip_location_to_latlng); // Ex: (25.7616798, -80.19179020000001)
$tmp = str_replace(')', '', $tmp);
$tmp = explode(',', $tmp);
$lat_to = $tmp[0];
$lng_to = $tmp[1];
$markerAlpaArr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB');

$travelmode = 'DRIVING';
switch ($transport) {
    case 'vehicle':
        $travelmode = 'DRIVING';
        break;
    case 'train':
        $travelmode = 'TRANSIT';
        break;
}

if (isset($_POST['profile_submit'])) { //$filter = $_POST['filter_option'];
    //$embassis = $_POST['embassy_list'];
    // edit data trip in DB
    //$trip->edit_data_filter($id_trip,$filter,$embassis);
    if (!empty($_POST['profile_employee'])) { // save employee data in DB 
        $trip->edit_data_employee($id_trip, $_POST['profile_employee']);
        if (!$trip->error)
            header("Location:" . SITE . "trip/name/" . $id_trip);
        else
            $output = 'A system error has been encountered. Please try again.';
    } else
        header("Location:" . SITE . "trip/name/" . $id_trip);
}

include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <title>PLANIVERSITY - ADD AN PEOPLE PROFILE</title>

    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">

    <link href="<?= SITE; ?>style/style.css?v=20230926" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="<?php echo SITE; ?>style/sweetalert.css" rel="stylesheet">
    <script src="<?php echo SITE; ?>js/sweetalert.min.js"></script>

    <script src="<?= SITE; ?>js/jquery-1.11.3.js"></script>
    <script>
        var SITE = '<?php echo SITE; ?>';
        var itinerary_type_mode = "<?= $trip->itinerary_type; ?>";
        var idtrip = '<?php echo $_GET['idtrip']; ?>';
    </script>
    <script src="<?= SITE; ?>js/js_map.js"></script>
    <script src="<?= SITE; ?>js/global.js?v=203040"></script>
    <?php include('new_head_files.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        .modaltrans {
            width: 292px;
            overflow: hidden !important;
            padding-left: 0px !important;
            max-height: 300px;
            margin-left: 14px;
        }

        label.error {
            color: #dd0f0f;
            font-size: 12px;
        }

        .modaltrans-body {
            transform: scale(0.4) translate(-77%, -77%);
            width: 260%;
        }


        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .menu_checkbox {
            position: relative;
            display: flex;
            align-items: center;
            margin: 15px 0;
        }

        .menu_checkbox span {
            margin-right: 10px;
        }

        .menu_checkbox input[type="checkbox"] {
            position: relative;
            width: 80px;
            height: 38px;
            -webkit-appearance: none;
            -webkit-appearance: none;
            background: #4c7fb8;
            outline: none;
            cursor: pointer;
            border-radius: 20px;
            /* box-shadow: inset 0 0 5px rgb(0 0 0 / 20%); */
            transition: background 300ms linear;
            margin-right: 10px;
        }

        .menu_checkbox input[type="checkbox"]::before {
            position: absolute;
            content: "";
            width: 40px;
            height: 40px;
            top: -2px;
            left: 0px;
            border-radius: 40px;
            /* background-color: #fff; */
            transform: scale(1.1);
            box-shadow: 0 2px 5px rgb(0 0 0 / 20%);
            transition: left 300ms linear;
            background-image: linear-gradient(to right, #fec84d, #f3a230);
        }

        .menu_checkbox input[type="checkbox"]:checked {
            background: #4c7fb8;
        }

        .menu_checkbox input[type="checkbox"]:checked::before {
            left: 40px;
            background-color: #fff;
        }
    </style>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-146873572-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-146873572-1');
    </script>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PBF3Z2D');
    </script>
    <!-- End Google Tag Manager -->
</head>


<?php
$emp = $dbh->prepare("SELECT CASE  WHEN a.role = 'view_only' THEN ''  ELSE 'Collaborator' END AS role, a.id_employee as option_id ,CONCAT(a.f_name,' ',a.l_name) as option_name  FROM employees as a, users as b WHERE a.employee_id = b.customer_number AND a.id_user = ? ORDER BY f_name");
$emp->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$emp_tmp = $emp->execute();
$employees = array(); // Initialize an array to hold the results

if ($emp_tmp && $emp->rowCount() > 0) {
    $employees = $emp->fetchAll(PDO::FETCH_OBJ);
}

// Convert the PHP array to JSON
$employeeJson = json_encode($employees);


$grp = $dbh->prepare("SELECT DISTINCT a.id as option_id , CASE  WHEN b.role = 'view_only' THEN ''  ELSE 'Collaborator' END AS role, a.group_name as option_name FROM travel_groups as a, employees as b WHERE a.id = b.travel_group AND a.user_id = ? ORDER BY id");
$grp->bindValue(1, $userdata['id'], PDO::PARAM_INT);
$grp_tmp = $grp->execute();
$groups = array(); // Initialize an array to hold the results

if ($grp_tmp && $grp->rowCount() > 0) {
    $groups = $grp->fetchAll(PDO::FETCH_OBJ);
}

// Convert the PHP array to JSON
$groupsJson = json_encode($groups);


?>

<body class="custom_profile">
<div class="fullscreen-background"></div>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBF3Z2D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include('new_backend_header.php'); ?>

    <div class="navbar-custom old-site-colors">
        <div class="container-fluid">
            <?php
            $step_index = "connect";
            include('dashboard/include/itinerary-step.php');
            ?>
        </div>
    </div>
    </header>

    <div id="export-modal" data-backdrop="false" class="modal fade bs-example-modal-lg custom_prefix_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display:none">
        <div class="modal-dialog modal-custom-dialog modal-lg mt-xs-0 pt-xs-0 mt-sm-0 mt-md-0 mt-lg-3">
            <div class="modal-content connect-bg rounded-div">
                <div class="modal-header pl-0 px-4">
                    <div>
                        <p class="small-logo-title pt-4">PLANIVERSITY</p>
                        <h4 class="modal-title pl-0 pt-0" id="myLargeModalLabel">Connect <span class="event-title pb-0">(Who will this plan be going to?)</span></h4>
                    </div>
                </div>
                <div class="modal-body connect-bg-ground" id="export-modal-body">
                    <form id="profile_form" class="routemap">
                        <?php //include('include_icondetails.php')  
                        ?>
                        <div class="error_style"><?= $output; ?></div>
                        <input name="location_from" id="location_from" class="inp1" value="<?= $trip->trip_location_from; ?>" type="hidden">
                        <input name="idtrip" id="idtrip" class="inp1" value="<?php echo $_GET['idtrip']; ?>" type="hidden">
                        <input name="location_to" id="location_to" class="inp1" value="<?= $trip->trip_location_to; ?>" type="hidden">
                        <input name="location_from_latlng_flightportion" id="location_from_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_latlng_flightportion; ?>">
                        <input name="location_to_latlng_flightportion" id="location_to_latlng_flightportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_latlng_flightportion; ?>">
                        <input name="trip_location_from_drivingportion" id="trip_location_from_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_drivingportion; ?>">
                        <input name="trip_location_to_drivingportion" id="trip_location_to_drivingportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_drivingportion; ?>">
                        <input name="trip_location_from_trainportion" id="trip_location_from_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_from_trainportion; ?>">
                        <input name="trip_location_to_trainportion" id="trip_location_to_trainportion" class="inp1" type="hidden" value="<?php if ($trip) echo $trip->trip_location_to_trainportion; ?>">

                        <p class="event-title pb-0"></p>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6">

                                    <?php if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>

                                    <div class="menu_checkbox">
                                        <span>Group</span>
                                        <input type="checkbox" name="switch" id="switch" checked value="1">
                                        <span>People</span>
                                    </div>

                                    <p class="event-title pb-0">Add profile </p>

                                    <div class="people_place">

                                        <div class="form-group people-field">
                                            <select autofocus name="profile_employee" id="profile_employee" class="dashboard-form-control input-lg">
                                            </select>
                                        </div>

                                        <div class="form-group people-action">
                                            <button type="submit" class="btn btn-primary people-button">
                                                Add
                                            </button>
                                        </div>

                                    </div>

                                    <div class="border-none">

                                        <div class="skip_item_section no-background">
                                            <ul class="list-unstyled justify-content-between">

                                                <li>
                                                    <a href="<?= SITE; ?>trip/travel-documents/<?= $_GET['idtrip']; ?>" class="skipt_value">Back</a>
                                                </li>
                                                <ul class="list-unstyled">
                                                    <li>
                                                        <a href="<?php echo SITE; ?>trip/create-timeline/<?php echo $_GET['idtrip']; ?>" class="skipt_value">Skip Section</a>
                                                    </li>
                                                    <li>
                                                        <!-- <a href="javascript:void(0)" id="btn-plan-submit" class="save_next_value">Save and Next</a> -->
                                                        <a href="<?php echo SITE; ?>trip/create-timeline/<?php echo $_GET['idtrip']; ?>" id="notes_submit" class="refresh-btn mt-3 mt-xs-0 mt-sm-0">Finished, Next Step</a>
                                                    </li>
                                                </ul>
                                            </ul>
                                        </div>
                                    </div>
                                    <br><br><br>
                                    <a href="<?php echo SITE; ?>people" style="font-size:0.65rem">Need to add a profile for someone?</a>
                                    <?php } ?>

                                </div>

                                <div class="col-md-6">

                                    <div class="people_section">



                                    </div>
                                </div>

                        </fieldset>
                        <br clear="all" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <br clear="all" />


    <?PHP
    $scale = 'METRIC';
    if ($userdata['scale'] == 'imperial') {
        $scale = 'IMPERIAL';
    }
    if (!empty($trip->trip_location_to_latlng_flightportion)) {
        $tmp = str_replace('(', '', $trip->trip_location_from_latlng_flightportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_from_flightportion = $tmp[0];
        $lng_from_flightportion = $tmp[1];
        $tmp = str_replace('(', '', $trip->trip_location_to_latlng_flightportion); // Ex: (25.7616798, -80.19179020000001)
        $tmp = str_replace(')', '', $tmp);
        $tmp = explode(',', $tmp);
        $lat_to_flightportion = $tmp[0];
        $lng_to_flightportion = $tmp[1];
    }
    ?>
    <script>
        var employees = <?php echo $employeeJson; ?>;
        var groups = <?php echo $groupsJson; ?>;


        $(function() {
            getPeopleList();
            populateSelectBox(employees, "People");
        });

        function getPeopleList() {


            var items = "";
            $.getJSON(SITE + "ajaxfiles/connect/list_processing.php", {
                id_trip: idtrip
            }, function(data) {
                $.each(data, function(index, item) {
                    let photo = SITE + "assets/images/user_profile.png";
                    let path_folder = "people";

                    let connection_type = "Individual";

                    if (item.is_group == "1") {
                        connection_type = `${item.group_name}`;
                    }

                    let name = item.first_name + " " + item.last_name;

                    if (item.photo_connect == "1") {
                        path_folder = "profile";
                    }

                    if (item.photo) {
                        photo = SITE + `ajaxfiles/${path_folder}/${item.photo}`;
                    }


                    <?php
                    if ($trip->getRole($id_trip) == TripPlan::ROLE_COLLABORATOR) { ?>
                    items += `
                    <div class="people_row" id="people_${item.id}">
                    <div class="people_left_side"><div class="people_img">
                    <img src="${photo}"></div><div class="people_info">
                    <h4>${name}</h4><p>${item.email} - ( ${connection_type} ) </p></div></div>
                    <div class="people_right_side">
                    <button id="delete" class="btn btn-mini btn-danger delete_action" title="Delete User" value="${item.id}"><i class='fa fa-trash'></i> </button>
                    </div></div>
                    `;
                    <?php } else { ?>
                    items += `
                    <div class="people_row" id="people_${item.id}">
                    <div class="people_left_side"><div class="people_img">
                    <img src="${photo}"></div><div class="people_info">
                    <h4>${name}</h4><p>${item.email} - ( ${connection_type} ) </p></div></div>
                    </div>
                    `;
                    <?php } ?>


                });

                $(".people_section").html(items);

            });

        }

        const populateSelectBox = (data, type) => {

            var items = `<option value="">Select ${type === 'Group' ? 'Group' : 'Profile'} (You do not need to add yourself)</option>`;

            $.each(data, function(index, item) {

                if (item.role!='') {
                    items += "<option id='" + item.option_id + "' value='" + item.option_id + "' >" + item.option_name + " (" + item.role + ")</option>";
                } else {
                    items += "<option id='" + item.option_id + "' value='" + item.option_id + "' >" + item.option_name + "</option>";
                }

            });

            $("#profile_employee").html(items);

        }

        $('#switch').change(function() {
            if (this.checked) {
                populateSelectBox(employees, "People")
            } else {
                populateSelectBox(groups, "Group")
            }
        });


        $("#profile_form").validate({
            rules: {
                profile_employee: {
                    required: true,
                },
            },
            messages: {
                profile_employee: {
                    required: "Please select people profile",
                },
            },

            submitHandler: function(form) {
                $(".people-button").css("cursor", "wait");
                $(".people-button").attr("disabled", true);

                $.ajax({
                    url: SITE + "ajaxfiles/connect/data_processing.php",
                    type: "POST",
                    data: $(form).serialize(),
                    dataType: "json",
                    success: function(response) {
                        $("#profile_employee").val("");

                        toastr.success('Successfully People Connected');

                        getPeopleList();

                        $(".people-button").css("cursor", "pointer");
                        $(".people-button").removeAttr("disabled");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                        toastr.error(jqXHR.responseJSON.message);
                        $(".people-button").css("cursor", "pointer");
                        $(".people-button").removeAttr("disabled");
                    },
                });
            },
        });

        $(document).on("click", "button#delete", function(event) {

            $(".delete_action").css("cursor", "wait");
            $(".delete_action").attr("disabled", true);

            var id = $(this).val();

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
                    url: SITE + "ajaxfiles/connect/delete_connect.php",
                    data: {
                        "id": id,
                    },
                    dataType: 'json',
                    success: function(response) {

                        toastr.success(response.message);
                        $("#people_" + id).slideUp(150, function() {
                            $("#people_" + id).remove();
                        });
                        //$("#people_" + id).remove();

                        $(".delete_action").css("cursor", "pointer");
                        $(".delete_action").removeAttr("disabled");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error(jqXHR.responseJSON);
                        $(".delete_action").css("cursor", "pointer");
                        $(".delete_action").removeAttr("disabled");
                    }

                });



            });



        });


    </script>
    <script>
        $(window).on('load', function() {
            $('#export-modal').modal('show');
        });

    </script>

    <?php include('new_backend_footer.php'); ?>

</body>

</html>