<?php
$img = SITE . 'images/my_profile_icon.png';

if ($userdata['picture']) $img = SITE. 'ajaxfiles/profile/' . $userdata['picture'];

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
                    <li class="menu-item list-inline-item  new-backend-header-style mobile_button">
                        <a class="navbar-toggle nav-link">
                            <i class="fa fa-chevron-down" style="color:white;display:none;"></i>
                        </a>
                    </li>
                    <li class="list-inline-item dropdown more-nav-list new-backend-header-style mobile_hide">
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

                            <a href="<?php echo SITE; ?>welcome" class="dropdown-item drop-menu-item">
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
                    <li class="list-inline-item dropdown more-nav-list new-backend-header-style mobile_hide">
                        <img src="<?= $img; ?>" width="35px" height="35px" alt="user" class="header-avatar rounded-circle">
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
