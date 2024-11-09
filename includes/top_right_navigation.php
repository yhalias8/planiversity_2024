<?php
/**
 * @author: Fabian Rolof <fabian@rolof.pl>
 */

$img = SITE . 'images/my_profile_icon.png';
if ($userdata['picture']) $img = SITE . 'ajaxfiles/profile/' . $userdata['picture'];

?>
<div class="app-header__content">
    <div class="app-header-right">
        <ul class="header-menu nav notification-nav">
            <li class="nav-item">
                <a href="<?= SITE ?>activities.php" class="nav-link notification-link">
                    <span><i class="fa fa-bell"> </i></span>
                    <span class="badge" id="message_count_notification"><?=ActivityLogger::getNumberOfUnreadNotifications($userdata['id']);?></span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= SITE ?>message" class="nav-link notification-link">
                    <span><i class="fa fa-envelope"> </i></span>
                    <span class="badge" id="message_count"></span>
                </a>
            </li>
        </ul>
        <div class="header-btn-lg pr-0">
            <div class="widget-content p-0">
                <div class="widget-content-wrapper">
                    <div class="widget-content-left user_account_infor">
                        <div class="btn-group">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                <i class="bi bi-chevron-down"></i>
                            </a>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                <ul class="list-unstyled">
                                    <?php
                                    if ($userdata['account_type'] == 'Admin') {
                                        ?>
                                        <li>
                                            <a class="dropdown-item drop-menu-item" href="<?= SITE; ?>apanel/users" target="_blank">
                                                Admin
                                            </a>
                                        </li>
                                    <?php } ?>

                                    <li>
                                        <a href="<?= SITE; ?>contact-us" class="dropdown-item drop-menu-item" target="_blank">
                                            Contact Us
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= SITE ?>blog" class="dropdown-item drop-menu-item" target="_blank">
                                            Blog
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= SITE; ?>leave" class="dropdown-item drop-menu-item" target="_blank">
                                            Delete Account
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= SITE; ?>logout" class="dropdown-item drop-menu-item">
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content-left  ml-3 header-user-info">
                        <div class="widget-heading">
                            <h6><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?></h6>
                        </div>
                    </div>
                    <div class="header_users widget-content-right header-user-info ml-3">
                        <div class="heade_user_img uploaded_image">
                            <img src="<?= $img ?>" class="rounded-circle profile_picture_place">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
