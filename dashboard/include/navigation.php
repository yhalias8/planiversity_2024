<?php
include_once(__DIR__ . "/../config.app.php");
include_once(__DIR__ . "/../config.ini.php");
include_once(__DIR__ . "/../config.ini.curl.php");
include_once('config.app.php');

$metric = '';
$imperial = '';
$googleChecked = $userdata['sync_googlecalendar'] == 1 ? true : false;
$outlookChecked = $userdata['sync_outlookcalendar'] == 1 ? true : false;

if (isset($_POST['scale'])) {
    $scale = $_POST['scale'];
    $query = "UPDATE `users` SET `scale` = '" . $scale . "' WHERE `users`.`id` = " . $userdata['id'] . ";";
    $stmtnew = $dbh->prepare($query);
    $stmtnew->execute();
    if ($_POST['scale'] == 'metric') $metric = 'checked="checked"';
    if ($_POST['scale'] == 'imperial') $imperial = 'checked="checked"';
} else {
    if ($userdata['scale'] == 'metric')
        $metric = 'checked="checked"';
    if ($userdata['scale'] == 'imperial')
        $imperial = 'checked="checked"';
}

// Sync google calendar
$gcalendar = '';
$calendar_check = 1;
// if (isset($_POST['ggcaltmp'])) {

//     $check = ($_POST['ggcal']) ? 1 : 0;
//     $timezone_offset_minutes = $_POST['ggcaltimezone'];
//     $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
//     $query = "UPDATE `users` SET `sync_googlecalendar` = '" . $check . "', `timezone` = '" . $timezone_name . "'  WHERE `users`.`id` = " . $userdata['id'] . ";";
//     $stmtnew = $dbh->prepare($query);
//     $stmtnew->execute();
//     // if ($check) {
//     //     $calendar_check = 2;
//     //     $gcalendar = 'checked="checked"';
//     // }
// } else {
//     if ($userdata['sync_googlecalendar']) {
//         $calendar_check = 2;
//         $gcalendar = 'checked="checked"';
//     }
// }

if (isset($_POST['sync_gcal'])) {
    $query = "UPDATE `users` SET `sync_googlecalendar` = '" . $_POST['sync_gcal'] . "'  WHERE `users`.`id` = " . $userdata['id'] . ";";
    $stmtnew = $dbh->prepare($query);
    $stmtnew->execute();
    $calendar_check = 2;
    $googleChecked = $_POST['sync_gcal'] == 1 ? true : false;
}

// $ocalendar = '';
$ocalendar_check = 1;
// if (isset($_POST['oocaltmp'])) {
//     $check = ($_POST['oocal']) ? 1 : 0;
//     $timezone_offset_minutes = $_POST['oocaltimezone'];
//     $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
//     $query = "UPDATE `users` SET `sync_outlookcalendar` = '" . $check . "', `timezone` = '" . $timezone_name . "'  WHERE `users`.`id` = " . $userdata['id'] . ";";
//     $stmtnew = $dbh->prepare($query);
//     $stmtnew->execute();
//     if ($check) {
//         $ocalendar_check = 2;
//         $ocalendar = 'checked="checked"';
//     }
// } else {
//     if ($userdata['sync_outlookcalendar']) {
//         $ocalendar_check = 2;
//         $ocalendar = 'checked="checked"';
//     }
// }



if (isset($_POST['sync_outlook'])) {
    $query = "UPDATE `users` SET `sync_outlookcalendar` = '" . $_POST['sync_outlook'] . "'  WHERE `users`.`id` = " . $userdata['id'] . ";";
    $stmtnew = $dbh->prepare($query);
    $stmtnew->execute();
    $ocalendar_check = 2;
    $outlookChecked = $_POST['sync_outlook'] == 1 ? true : false;
}


// if ($userdata['account_type'] == 'Individual') {
//     $user_payment_status = $plan->individual_check_plan($userdata['id']);
// } else {
//     $user_payment_status = $plan->check_plan($userdata['id']);
// }

$payment_status = 1;



?>

<div class="app-sidebar__inner">
    <span class="only-mob hamburger hamburger--elastic mobile-toggle-nav up-onslide show-mob arrow-rotate"><img src="<?= SITE; ?>/dashboard/images/arrow-planhd.svg"></span>
    <ul class="vertical-nav-menu">

        <li>
            <a href="<?= SITE ?>welcome" class="<?php if ($page_index == "home") {
                                                    echo 'mm-active';
                                                } ?>">
                <i class="metismenu-icon pe-7s-rocket">
                    <i class="bi bi-grid"></i>
                </i>
                Home
            </a>
        </li>
        <?php
        $modal = NULL;
        ?>
        <?php if ($userdata['account_type'] != 'Individual') { ?>
            <li>
                <a href="<?= SITE ?>people">
                    <i class="metismenu-icon pe-7s-mouse">
                        <i class="fa fa-address-card-o" aria-hidden="true"></i>
                    </i>People
                </a>
            </li>
            <li>
                <a href="<?= SITE ?>migration" class="<?php if ($page_index == "migration") {
                                                            echo 'mm-active';
                                                        } ?>">
                    <i class="metismenu-icon pe-7s-graph2">
                        <i class="bi bi-magic"></i>
                    </i>Migration
                </a>
            </li>
        <?php } else { ?>
            <li>
                <a href="<?= SITE ?>people">
                    <i class="metismenu-icon pe-7s-mouse">
                        <i class="fa fa-address-card-o" aria-hidden="true"></i>
                    </i>People
                </a>
            </li>
        <?php } ?>
        <li>
            <a href="<?= SITE ?>trip-dashboard" class="<?php if ($page_index == "trip-dashboard") {
                                                            echo 'mm-active';
                                                        } ?>">
                <i class="metismenu-icon pe-7s-graph2">
                    <i class="bi bi-map"></i>
                </i>Archive
                <span class="soon-feature">Going soon</span>
            </a>
        </li>

        <li>
            <a href="<?= SITE ?>travel-booking" class="<?php if ($page_index == "trip-booking") {
                                                            echo 'mm-active';
                                                        } ?>">
                <i class="metismenu-icon pe-7s-graph2">
                    <i class="bi bi-binoculars"></i>
                </i>Travel Booking
            </a>
        </li>

        <li>
            <a href="<?= SITE ?>marketplace">
                <i class="metismenu-icon pe-7s-graph2">
                    <i class="bi bi-shop"></i>
                </i>Marketplace
            </a>
        </li>

        <li class="menu_user_profile menu_setting_itema mm-active">
            <a href="javascript:void(0)" aria-expanded="true">
                <i class="metismenu-icon">
                    <img src="<?= $img ?>" height="30" class="profile_picture_place">
                </i>
                Account
                <i class="metismenu-state-icon fa fa-angle-down caret-left"></i>
            </a>
            <ul class="menu_setting_item_list mm-show">
                <li class="Connections_items">
                    <!-- <h6>Connections</h6> -->
                    <a href="<?= SITE ?>profile" class="p-0<?php if ($page_index == "profile") {
                                                                echo 'mm-active';
                                                            } ?>">
                        <div class="menu_checkbox">

                            <div class="menu_user_profile_img uploaded_image">
                                <i class="bi bi-person-square"></i>
                            </div>
                            <div class="menu_user_profile_text">
                                <span>Profile</span>
                            </div>

                        </div>
                    </a>
                    <a href="<?= SITE ?>billing" class="expendable_link">
                        <div class="menu_checkbox">

                            <div class="menu_user_profile_img uploaded_image">
                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                            </div>
                            <div class="menu_user_profile_text">
                                <span>Billing</span>
                            </div>
                        </div>
                    </a>

                </li>

            </ul>
        </li>

        <li class="menu_setting_item mm-active">
            <a href="javascript:void(0)" aria-expanded="true"><i class="metismenu-icon bi bi-gear"></i>
                Settings
                <i class="metismenu-state-icon fa fa-angle-down caret-left"></i>
            </a>
            <ul class="menu_setting_item_list mm-show">
                <li class="Connections_items">
                    <h6>Connections</h6>
                    <div class="menu_checkbox">
                        <span>Google Calendar</span>
                        <input type="checkbox" id="google_calender" onClick="google_sync(<?= $calendar_check ?>,<?= $payment_status ?>, this)" <?= $googleChecked == '1' ? 'checked' : ''; ?> />
                        <div id="buttonDiv"></div>
                    </div>
                    <div class="menu_checkbox">
                        <span>Outlook Calendar</span>
                        <input type="checkbox" id="outlook_calender" onClick="outlook_sync(<?= $ocalendar_check ?>,<?= $payment_status ?>, this)" <?= $outlookChecked == '1' ? 'checked' : ''; ?> />
                    </div>
                </li>
                <li class="Measurements_items">
                    <h6>Measurements</h6>
                    <div class="menu_checkbox">
                        <span>Metric</span>
                        <input type="checkbox" onClick="change_scale('metric')" id="metric" <?= $metric; ?> value="metric" />
                    </div>
                    <div class="menu_checkbox">
                        <span>Imperial</span>
                        <input type="checkbox" onClick="change_scale('imperial')" id="imperial" <?= $imperial; ?> value="imperial" />
                    </div>
                </li>
            </ul>
        </li>

    </ul>
</div>

<div class="modal" id="upgrade" style="margin-top: 100px;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">UPGRADE PLAN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Upgrade your plan to access all benefits</p>
                <a href="<?= SITE ?>billing">
                    <button class="btn btn-info">UPGRADE NOW</button>
                </a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>

<script src="https://apis.google.com/js/api.js"></script>
<script src="https://accounts.google.com/gsi/client"></script>

<script>
    var timezone_offset_minutes = new Date().getTimezoneOffset();
    timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;


    function change_scale(scale) {
        $('<form action="" method="POST"></form>').append('<input name="scale" value="' + scale + '" />').appendTo('body').submit();
    }

    function google_sync(e, status, res) {
        // Konfigurasi OAuth2
        const clientId = `<?= GOOGLE_CLIENT_ID ?>`;
        const redirectUri = `<?= GOOGLE_CLIENT_REDIRECT_URL ?>`;
        const scope = 'https://www.googleapis.com/auth/calendar';

        //   return;
        if (status == 0) {
            document.getElementById("google_calender").checked = false;
            $('#upgrade').modal('show');
            return
        }

        if (res.checked) {
            // Jika belum login, tampilkan tombol loginoauth2
            const authUrl = `https://accounts.google.com/o/oauth2/v2/auth?client_id=${clientId}&scope=${scope}&redirect_uri=${redirectUri}&response_type=code&access_type=offline`;

            // Buka jendela autentikasi
            window.open(authUrl, '_blank', 'width=500,height=600'); 
        }else{
            $('<form action="" method="POST"></form>')
                .append('<input name="sync_gcal" value="0" />')
                .appendTo('body').submit();
        }


    }

    function outlook_sync(e, status, res) {
        const tenant = `<?= OUTLOOK_CLIENT_TENANT ?>`;
        const clientId = `<?= OUTLOOK_CLIENT_ID ?>`;
        const redirectUri = `<?= OUTLOOK_CLIENT_REDIRECT_URL ?>`;

        
        //   return;
        if (status == 0) {
            document.getElementById("outlook_calender").checked = false;
            $('#upgrade').modal('show');
            return
        }

        if (res.checked) {
            // Jika belum login, tampilkan tombol loginoauth2
            // const authUrl = `https://login.microsoftonline.com/outlook_tenant_id/oauth2/v2.0/authorize?client_id=outlook_client_id&response_type=code&redirect_uri=outlook_redirect_uri&scope=Calendars.ReadWrite%20offline_access&response_mode=query&state=12345`;

            // const authUrl = `https://login.microsoftonline.com/${tenant}/oauth2/v2.0/authorize?&client_id=${clientId}&response_type=code&redirect_uri=${redirectUri}&response_mode=query&scope=https://graph.microsoft.com/.default&state=12345`
            const authUrl = `https://login.microsoftonline.com/common/oauth2/v2.0/authorize?&client_id=${clientId}&response_type=code&redirect_uri=${redirectUri}&response_mode=query&scope=https://graph.microsoft.com/.default&state=12345`

            // Buka jendela autentikasi
            window.open(authUrl, '_blank', 'width=500,height=600'); 
        }else{
            $('<form action="" method="POST"></form>')
                .append('<input name="sync_outlook" value="0" />')
                .appendTo('body').submit();
        }


    return;

        var oval = '';
        if (e == 1) {
            oval = 1;
        } else {
            oval = 0;
        }

        if (status == 0) {
            document.getElementById("google_calender").checked = false;
            $('#upgrade').modal('show');
        } else {

            $('<form action="" method="POST"></form>')
                .append('<input name="oocal" value="' + oval + '" />')
                .append('<input name="oocaltmp" value="checked" />')
                .append('<input name="oocaltimezone" value="' + timezone_offset_minutes + '" />')
                .appendTo('body').submit();
        }


    }
</script>