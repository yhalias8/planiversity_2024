<?php
$img = SITE . 'images/my_profile_icon.png';
if ($userdata['picture']) {
	$img = SITE . 'ajaxfiles/profile/' . $userdata['picture'];
}
?>
<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">
            <div class="logo">
                <a href="<?php echo SITE; ?>" class="logo">
                    <span class="logo-small"><img src="<?php echo SITE; ?>assets/images/logo-icon1.png" width="40" alt="logo icon"></span>
                    <span class="logo-large"><span>Planiversity</span></span>
                    <li class="menu-item list-inline-item display_button">
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                    </li>
                </a>
            </div>
            <div class="menu-extras topbar-custom">
                <ul class="list-inline float-right mb-0">
                    <li class="menu-item list-inline-item mobile_button">
                        <a class="navbar-toggle nav-link">
                            <i class="fa fa-chevron-down" style="color:white;display:none;"></i>
                        </a>
                    </li>
                    <li class="list-inline-item dropdown more-nav-list mobile_hide">
                        <a class="nav-link dropdown-toggle arrow-none link-drop" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="fa fa-chevron-down mr-2"></span>
                            <span><?php echo isset($userdata['name']) ? $userdata['name'] : "Test"; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg" aria-labelledby="Preview">
                            <?php
                            if ($userdata['account_type'] == 'Admin') {
                            ?>
                                <a class="dropdown-item drop-menu-item" href="<?php echo SITE; ?>apanel/users" target="_blank">
                                    Admin
                                </a>
                            <?php } ?>

                         
                             <a href="<?= SITE; ?>welcome" class="dropdown-item drop-menu-item" target="_blank">
                                           Home
                            </a>
         

                            <a href="<?php echo SITE; ?>contact-us" class="dropdown-item drop-menu-item" target="_blank">
                                Contact Us
                            </a>
                            <a target="_blank" href="http://erichrichardblog.wordpress.com" class="dropdown-item drop-menu-item" target="_blank">
                                Blog
                            </a>
                            <a href="<?php echo SITE; ?>leave" class="dropdown-item drop-menu-item" target="_blank">
                                Delete Account
                            </a>
                            <a href="<?php echo SITE; ?>logout" class="dropdown-item drop-menu-item">
                                Logout
                            </a>
                        </div>
                    </li>
                    <li class="list-inline-item dropdown more-nav-list mobile_hide">
                        <img src="<?= $img; ?>" width="35px" height="35px" alt="user" class="header-avatar rounded-circle">
                    </li>
                    <li class="list-inline-item dropdown more-nav-list">
                        <div class="new-plan">
                            <a href="<?php echo SITE; ?>trip/itinerary-option">
                                <p class="start-plan-style"><span class="d-none d-md-inline">Start a new plan </span>&nbsp;<span class="fa fa-plus-circle"></span></p>
                            </a>
                        </div>
                    </li>
                    <!-- <?php if ($auth->isLogged()) { ?>
                    <li class="list-inline-item dropdown more-nav-list">
                        <a href="<?php echo SITE; ?>logout" class="dropdown-item drop-menu-item" style="font-size: 15.2px;">
                            <span>Logout</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown " aria-labelledby="Preview">
                            <div class="dropdown-item noti-title">
                                <h5 class="text-overflow">
                                    <small class="text-white"><?php echo $userdata['name']; ?></small>
                                </h5>
                            </div>
                            <a href="<?php echo SITE; ?>logout" class="dropdown-item drop-menu-item">
                                <span>Logout</span>
                            </a>
                        </div>
                    </li>
                    <?php } ?> -->
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php
    if ($userdata['account_type'] == 'Business' || $userdata['account_type'] == 'Admin') { ?>
        <div class="navbar-custom">
            <div class="container-fluid">
                <div id="navigation">
                    <ul class="navigation-menu" id="menu">
                        <li>
                            <div class="header-titles">
                                <a href="<?php echo SITE ?>welcome" class="left-nav-button <?php echo $page_name == 'welcome' ? 'active-color' : ''; ?>">
                                    Home
                                </a>
                                <div class="header-bottom-style <?php echo $page_name == 'welcome' ? 'menu-active' : ''; ?>"></div>
                            </div>
                        </li>
                        <li>
                            <div class="header-titles">
                                <a href="<?php echo SITE ?>people" class="left-nav-button  <?php echo $page_name == 'people' ? 'active-color' : ''; ?>">
                                    People
                                </a>
                                <div class="header-bottom-style <?php echo $page_name == 'people' ? 'menu-active' : ''; ?>"></div>
                            </div>
                        </li>
                        <li>
                            <div class="header-titles">
                                <a href="<?php echo SITE ?>jobs" class="left-nav-button  <?php echo $page_name == 'jobs' ? 'active-color' : ''; ?>">
                                    Jobs
                                </a>
                                <div class="header-bottom-style <?php echo $page_name == 'jobs' ? 'menu-active' : ''; ?>"></div>
                            </div>
                        </li>
                        <li class="a-menu-element">
                            <div class="header-titles">
                                <a href="<?php echo SITE ?>events" class="left-nav-button  <?php echo $page_name == 'events' ? 'active-color' : ''; ?>">
                                    Events
                                </a>
                                <div class="header-bottom-style <?php echo $page_name == 'events' ? 'menu-active' : ''; ?>"></div>
                            </div>
                        </li>
                        <li class="a-menu-element">
                            <div class="header-titles">
                                <a href="<?php echo SITE ?>meetings" class="left-nav-button  <?php echo $page_name == 'meetings' ? 'active-color' : ''; ?>">
                                    Meetings
                                </a>
                                <div class="header-bottom-style <?php echo $page_name == 'meetings' ? 'menu-active' : ''; ?>"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>
</header>
