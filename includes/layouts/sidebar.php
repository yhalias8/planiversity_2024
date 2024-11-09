<?php
$googleCalenderChecked = isset($userdata['sync_googlecalendar']) && $userdata['sync_googlecalendar'] == '1' ? "checked" : "";
$outlookCalenderChecked = isset($userdata['sync_outlookcalendar']) && $userdata['sync_outlookcalendar'] == '1' ? "checked" : "";
$imperialChecked = isset($userdata['scale']) && $userdata['scale'] == 'imperial' ? "checked" : "";
$metricChecked = isset($userdata['scale']) && $userdata['scale'] == 'metric' ? "checked" : "";
$payment_status = 1;
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-left" href="javascript:void(0)">
        <div class="sidebar-brand-text">Planiversity</div>
    </a>

    <!-- Topbar Search -->
    <form class="nav-item d-none d-sm-inline-block form-inline navbar-search mb-4">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-1 small bg-transparent" placeholder="Search "
                aria-label="Search" aria-describedby="basic-addon2">
        </div>
    </form>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= SITE ?>welcome">
            <i class="bi bi-house-fill" style="font-size:1.3em; color: #B4DDFB;"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Nav Item - People -->
    <li class="nav-item">
        <a class="nav-link" href="<?= SITE ?>people">
            <i class="bi bi-person-square" style="font-size:1.3em; color: #B4DDFB;"></i>
            <span>People</span></a>
    </li>

    <!-- Nav Item - Migrations -->
    <li class="nav-item">
        <a class="nav-link" href="<?= SITE ?>migrations">
            <i class="bi bi-stopwatch" style="font-size:1.3em; color: #B4DDFB;"></i>
            <span>Migrations</span></a>
    </li>

    <!-- Nav Item - Archive -->
    <li class="nav-item">
        <a class="nav-link" href="<?= SITE ?>trip-dashboard">
            <i class="bi bi-calendar-plus-fill" style="font-size:1.3em; color: #B4DDFB;"></i>
            <span>Archive</span></a>
    </li>

    <!-- Nav Item - Travel Resource -->
    <li class="nav-item">
        <a class="nav-link" href="<?= SITE ?>travel-booking">
            <i class="bi bi-list-check" style="font-size:1.3em; color: #B4DDFB;"></i>
            <span>Travel Resource</span></a>
    </li>

    <!-- Nav Item - Market Place -->
    <li class="nav-item">
        <a class="nav-link" href="<?= SITE ?>marketplace">
            <i class="bi bi-card-checklist" style="font-size:1.3em; color: #B4DDFB;"></i>
            <span>Market Place</span></a>
    </li>

    <div class="sidebar-bottom">
        <li class="nav-item">
            <a class="nav-link collapsed" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseAccount"
                aria-expanded="false" aria-controls="collapseAccount">
                <i class="bi bi-person-circle" style="font-size:1.3em; color: #B4DDFB;"></i>
                <span>Account</span>
                <i class="bi bi-chevron-down arrow-icon" style="font-size:1em; color: #B4DDFB;"></i>
            </a>
            <div id="collapseAccount" class="collapse" aria-labelledby="headingAccount" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="profile">Profile</a>
                    <a class="collapse-item" href="billing">Billing</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="javascript:void(0)" data-toggle="collapse" data-target="#collapseSetting"
                aria-expanded="false" aria-controls="collapseSetting">
                <i class="bi bi-gear" style="font-size:1.3em; color: #B4DDFB;"></i>
                <span>Setting</span>
                <i class="bi bi-chevron-down arrow-icon" style="font-size:1em; color: #B4DDFB;"></i>
            </a>
            <div id="collapseSetting" class="collapse" aria-labelledby="headingSetting" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Connection</h6>
                    <a class="collapse-item" href="javascript:void(0)">Google Calendar
                        <input type="checkbox" class="toggle-button toggle-sidebar" id="toggleGoogleCalendar" onClick="google_sync(<?= $payment_status ?>)" <?= $googleCalenderChecked ?>>
                    </a>
                    <a class="collapse-item" href="javascript:void(0)">Outlook Calendar
                        <input type="checkbox" class="toggle-button toggle-sidebar" id="toggleOutlookCalendar" onClick="outlook_sync(<?= $payment_status ?>)" <?= $outlookCalenderChecked ?>>
                    </a>
                    <h6 class="collapse-header">Measurements</h6>
                    <a class="collapse-item" href="javascript:void(0)">Metric
                        <input type="checkbox" class="toggle-button toggle-sidebar" id="toggleMetric" onClick="change_scale('metric')" <?= $metricChecked ?>>
                    </a>
                    <a class="collapse-item" href="javascript:void(0)">Imperial
                        <input type="checkbox" class="toggle-button toggle-sidebar" id="toggleImperial" onClick="change_scale('imperial')" <?= $imperialChecked ?>>
                    </a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Tables -->

        <li class="nav-item">
            <a class="nav-link" href="logout">
                <img class="img-profile rounded-circle" src="<?= $img ?>">
                <span class="ml-2 d-none d-lg-inline text-white-600 small sidebar-text-user"><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?>
                    <br>
                    <div class="sidebar-text-email" style="font-family: 'Circular Std book'"><?= isset($userdata['email']) ? $userdata['email'] : ""; ?></div>
                </span>
                <i class="bi bi-box-arrow-in-right" style="font-size:1em; color: #B4DDFB;margin-bottom:20px"></i>
            </a>
        </li>
    </div>
</ul>