<?php
include_once("config.ini.php");

if (!$auth->isLogged()) {
    header("Location:" . SITE . "login");
}
if ($userdata['account_type'] != 'Admin') {
    header("Location:" . SITE . "welcome");
}

include('include_doctype.php');
?>
<html lang="en">
<!--<![endif]-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | Consolidated Travel Information Management</title>
    <meta name="description"
          content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>images/favicon.png">

    <!--calendar css-->
    <link href="<?php echo SITE; ?>assets/css/fullcalendar.min.css" rel="stylesheet"/>

    <link href="<?php echo SITE; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo SITE; ?>assets/css/icons.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo SITE; ?>assets/css/app-style.css" rel="stylesheet" type="text/css"/>

    <script src="<?php echo SITE; ?>assets/js/modernizr.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/moment.min.js"></script>
    <script src='<?php echo SITE; ?>assets/js/fullcalendar.min.js'></script>
    <script>var SITE = '<?php echo SITE; ?>' </script>
    <link href="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.css" rel="stylesheet" type="text/css"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">
            <div class="logo">
                <a href="<?php echo SITE; ?>" class="logo">
                    <span class="logo-small"><img src = "<?php echo SITE; ?>assets/images/logo-icon.png" alt = "logo icon"></span>
                    <span class="logo-large"><span>Planiversity</span></span>
                    <li class="menu-item list-inline-item">
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
                    <!-- <li class="menu-item list-inline-item">
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                    </li> -->
                    <li class="list-inline-item dropdown more-nav-list new-backend-header-style">
                        <a class="nav-link dropdown-toggle arrow-none link-drop" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                          <span class="fa fa-chevron-down mr-2"></span>
                            <span><?php  echo isset($userdata['name'])?$userdata['name']:"Test"; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg" aria-labelledby="Preview">
                            <?php 
                                if ($userdata['account_type'] == 'Admin') {
                            ?>
                                <a class="dropdown-item drop-menu-item" href="<?php echo SITE; ?>apanel/users" target="_blank">
                                    Admin
                                </a>
                            <?php } ?>

                            
                            <a href="<?php echo SITE; ?>contact-us" class="dropdown-item drop-menu-item" target="_blank">
                                Contact Us
                            </a>
                            <a target="_blank" href="http://erichrichardblog.wordpress.com"  class="dropdown-item drop-menu-item" target="_blank">
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
                    <li class="list-inline-item dropdown more-nav-list new-backend-header-style">
                     <?php
                        $img = 'images/img3.png';
                        if ($userdata['picture']) $img = 'ajaxfiles/profile/' . $userdata['picture'];
                        ?>
                        <img src="<?php echo SITE . $img; ?>" width="35px" height="35px" alt="user" class="header-avatar rounded-circle">
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
    <style>
        .navigation-menu.header-top-bar-style li a{
            color:#007bff;
        }
    </style>
    <div class="navbar-custom header-nav-bar-style">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu header-top-bar-style">
                    <li>
                        <a href="<?php echo SITE; ?>welcome">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>apanel/routes">Routes</a>
                    </li>
                    <li class="active-link">
                        <a href="<?php echo SITE; ?>apanel/settings">Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>apanel/transactions">Transactions</a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>apanel/affiliate">Affiliate</a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>apanel/users">Users</a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>apanel/coupon">Coupon</a>
                    </li>                    
                    <li class="pull-right">
                        <a class="new-account-btn" data-toggle="modal" data-backdrop="false"
                           data-target="#new-user-modal">Create New Account
                            <i class="fa fa-plus-circle"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<div class="wrapper">
    <div class="container-fluid">
        <h3 class="page-headr">Settings</h3>
        <form name="admin_users" method="POST" class="form-horizontal">
            <div class="card-box">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-wrap">
                            <?PHP
                            $output = '';
                            $name = '';
                            $email = '';
                            $Individual;
                            $Business;
                            $Admin = '';

                            $allow_settings = array('site_url', 'site_name', 'site_key', 'cookie_name', 'site_email', 'rows_per_page');
                            $allow_settings2 = array('Why_Create_a_Trip_Timeline', 'Why_Create_Trip_Notes', 'Why_Add_Your_Documents', 'Why_Add_Filters', 'How_to_use_the_map');

                            if (isset($_POST['save_settings'])) {
                                foreach ($allow_settings as $key => $setting) {
                                    $_POST[$setting] = filter_var($_POST[$setting], FILTER_SANITIZE_STRING);
                                    $stmt = $dbh->prepare("UPDATE `config` SET `value` = '" . $_POST[$setting] . "' WHERE `config`.`setting` = '" . $setting . "';");
                                    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                    $tmp = $stmt->execute();
                                }
                                foreach ($allow_settings2 as $key => $setting) {
                                    $_POST[$setting] = filter_var($_POST[$setting], FILTER_SANITIZE_STRING);
                                    $_POST[$setting] = htmlentities($_POST[$setting]);
                                    $stmt = $dbh->prepare("UPDATE `config` SET `value` = '" . $_POST[$setting] . "' WHERE `config`.`setting` = '" . $setting . "';");
                                    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                    $tmp = $stmt->execute();
                                }

                            }

                            $stmt = $dbh->prepare("SELECT * FROM `config`" . $s_email . $orderby);
                            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                            $tmp = $stmt->execute();
                            $aux1 = '';
                            $aux2 = '';

                            if ($tmp && $stmt->rowCount() > 0) {
                                $user_ = $stmt->fetchAll(PDO::FETCH_OBJ);
                                $bg1 = '';
                                $c = 0;
                                foreach ($user_ as $user_row) {
                                    $c++;
                                    if (in_array($user_row->setting, $allow_settings)) {
                                        $aux1 .= ' <label class="admin-settings-label">' . $user_row->setting . '</label>
												<div class="col-sm-12">
													<div class="form-group">
														<input class="admin-form-control form-control input-lg inp1" name="' . $user_row->setting . '" ' . (in_array($user_row->setting, $allow_settings) ? 'class="can_edit"' : 'class="can_not" readonly="readonly"') . ' type="text" 
														value="' . $user_row->value . '" class="admin-form-control form-control input-lg inp1" placeholder="' . $user_row->setting . '" required="">
													</div>
												</div>';
                                    }

                                    if ($c == 2) $c = 0;
                                }
                                foreach ($user_ as $user_row) {
                                    $c++;
                                    if (in_array($user_row->setting, $allow_settings2)) {
                                        $aux2 .= ' <label class="emp-form-label">' . $user_row->setting . '</label>
									  <div class="col-sm-12">
                                            <div class="form-group">
                                                <textarea class="textarea-control form-control input-lg" rows="5" cols="40" name="' . htmlentities($user_row->setting) . '">' . $user_row->value . '</textarea>
                                            </div>
                                        </div>';
                                    }

                                    if ($c == 2) $c = 0;
                                }
                                //echo $aux;
                            }
                            ?>
                            <fieldset>
                                <div class="row">
                                    <?php echo $aux1; ?>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-wrap">
                            <fieldset>
                                <div class="row">
                                    <?php echo $aux2; ?>
                                    <div class="col-sm-12">
                                        <div class="save-btn-wrapper">
                                            <input name="save_settings" value="SAVE SETTINGS" type="submit"
                                                   class="save-changes-btn"/>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal -->
<?php include('userModal.php'); ?>
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <p class="footer-text">&copy; Copyright. 2015 -
                    <script>document.write(new Date().getFullYear())</script>
                    Planiversity, LLC. All Rights Reserved.
                </p>
            </div>
        </div>
    </div>
</footer>
<script src="<?php echo SITE; ?>assets/js/popper.min.js"></script>
<script src="<?php echo SITE; ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo SITE; ?>assets/js/jquery.slimscroll.js"></script>
<script src="<?php echo SITE; ?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo SITE; ?>assets/js/jquery.app.js"></script>

</body>
</html>