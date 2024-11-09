<div class="app-sidebar__inner">
    <span class="only-mob hamburger hamburger--elastic mobile-toggle-nav up-onslide show-mob arrow-rotate"><img src="<?= SITE; ?>/dashboard/images/arrow-planhd.svg"></span>
    <ul class="vertical-nav-menu">

        <li>
            <a href="<?= SITE ?>welcome" class="mm-active">
                <i class="metismenu-icon pe-7s-rocket">
                    <i class="bi bi-grid"></i>
                </i>
                Home
            </a>
        </li>
        <?php
            $modal = NULL; 
            if ($userdata['account_type'] == 'Individual') {
                if ($plan->individual_check_plan($userdata['id'])) {
                    $url = SITE . 'events'; 
                } else {
                    $url    = 'javascript:void(0)';
                    $modal  = 'data-toggle="modal" data-target="#upgrade"';
                } 
            } else {
                $url = SITE . 'events';
            }
        ?>
        <li>
            <a href="<?= $url ?>" <?= $modal ?>>
                <i class="metismenu-icon pe-7s-rocket">
                    <i class="bi bi-calendar2-check"></i>
                </i>
                Events
            </a>
        </li>
        <?php if ($userdata['account_type'] != 'Individual') { ?>
            <li>
                <a href="<?= SITE ?>meetings">
                    <i class="metismenu-icon pe-7s-display2">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                    </i>
                    Meetings
                </a>
            </li>
            <li>
                <a href="<?= SITE ?>employees">
                    <i class="metismenu-icon pe-7s-mouse">
                        <i class="fa fa-address-card-o" aria-hidden="true"></i>
                    </i>Employess
                </a>
            </li>
            <li>
                <a href="<?= SITE ?>jobs">
                    <i class="metismenu-icon pe-7s-eyedropper">
                        <i class="bi bi-file-earmark-diff"></i>
                    </i>Jobs
                </a>
            </li>
        <?php } ?>
        <li>
            <a href="<?= SITE ?>billing">
                <i class="metismenu-icon pe-7s-pendrive">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                </i>Billing
            </a>
        </li>
        <li>
            <a href="<?= SITE ?>">
                <i class="metismenu-icon pe-7s-graph2">
                    <i class="bi bi-map"></i>
                </i>Trips
            </a>
        </li>

        <li class="menu_user_profile">
            <div class="menu_user_profile_img uploaded_image">
                <img src="<?= $img ?>" height="30">
            </div>
            <div class="menu_user_profile_text">
                <h6><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?></h6>
            </div>
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
                        <input type="checkbox" onClick="google_sync(<?= $calendar_check ?>)" <?= $gcalendar; ?> />
                    </div>
                </li>
                <li class="Measurements_items">
                    <h6>Measurements</h6>
                    <div class="menu_checkbox">
                        <span>Imperial</span>
                        <input type="checkbox" onClick="change_scale('metric')" id="metric" <?= $metric; ?> value="metric" />
                    </div>
                    <div class="menu_checkbox">
                        <span>Metric</span>
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