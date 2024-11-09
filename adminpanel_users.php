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
<!DOCTYPE html>
<!--[if lt IE 7 ]>
<html lang="en" class="ie6"> <![endif]-->
<!--[if IE 7 ]>
<html lang="en" class="ie7"> <![endif]-->
<!--[if IE 8 ]>
<html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9 ]>
<html lang="en" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--->
<html lang="en">
<!--<![endif]-->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

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
    <style>
        .dt-header{
            cursor: pointer;
        }
    </style>
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
                    <li>
                        <a href="<?php echo SITE; ?>apanel/settings">Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>apanel/transactions">Transactions</a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>apanel/affiliate">Affiliate</a>
                    </li>
                    <li class="active-link">
                        <a href="<?php echo SITE; ?>apanel/users">Users</a>
                    </li>
                    <li>
                        <a href="<?php echo SITE; ?>apanel/coupon">Coupon</a>
                    </li>
                    
                    <li class="pull-right">
                        <!--href="<?php // echo SITE.'apanel/users?user=new'; ?>"-->
                        <a class="new-account-btn" data-toggle="modal" data-target="#new-user-modal"
                           data-backdrop="false">Create New Account
                            <i class="fa fa-plus-circle"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<?php

$user_view = 'hide';
if (isset($_GET['user']) && $_GET['user'] == 'view' && !empty($_GET['uid']))
    $user_view = 'show';


if (isset($_COOKIE['Planiversity_ord_user']) && !empty($_COOKIE['Planiversity_ord_user']))
    $orderby = $_COOKIE['Planiversity_ord_user'];
else
    $orderby = ' ORDER BY `users`.`email` ASC';

if (isset($_GET['ord_e']) && !empty($_GET['ord_e'])) {
    if ($_GET['ord_e'] == 'ASC') $ord_e = 'DESC'; else $ord_e = 'ASC';
    $orderby = ' ORDER BY `users`.`email` ' . $ord_e;
} else  $ord_e = 'ASC';

if (isset($_GET['ord_c']) && !empty($_GET['ord_c'])) {
    if ($_GET['ord_c'] == 'ASC') $ord_c = 'DESC'; else $ord_c = 'ASC';
    $orderby = ' ORDER BY `users`.`date_created` ' . $ord_c;
} else  $ord_c = 'ASC';

if (isset($_GET['ord_l']) && !empty($_GET['ord_l'])) {
    if ($_GET['ord_l'] == 'ASC') $ord_l = 'DESC'; else $ord_l = 'ASC';
    $orderby = ' ORDER BY `users`.`date_last_login` ' . $ord_l;
} else  $ord_l = 'ASC';

if (isset($_GET['ord_u']) && !empty($_GET['ord_u'])) {
    if ($_GET['ord_u'] == 'ASC') $ord_u = 'DESC'; else $ord_u = 'ASC';
    $orderby = ' ORDER BY `users`.`active` ' . $ord_u;
} else  $ord_u = 'ASC';

//store a cookie for 1 day user friendly behavior
if (isset($_GET['ord_e']) || isset($_GET['ord_c']) || isset($_GET['ord_l']) || isset($_GET['ord_u']))
    setcookie('Planiversity_ord_user', $orderby, time() + (86400 * 30), "/"); // 86400 = 1 day


?>
<!--<p><a href="#" class="button batch_actions">Batch Actions</a></p>-->

<?php
/*function get_last_ip($uid){
      global $dbh;
      $query='SELECT * FROM `sessions` WHERE `sessions`.`uid` ='.$uid;
      $stmt = $dbh->prepare($query);
      $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
      $tmp = $stmt->execute();
      if ($tmp && $stmt->rowCount()>0){
          $data = $stmt->fetchAll(PDO::FETCH_OBJ);
         return $data[0]->ip;
      }else
         return 'unknown';

}*/

if (isset($_GET['user']) && $_GET['user'] == 'view' && !empty($_GET['uid'])) {

    if (isset($_GET['enablecyc']) && $_GET['enablecyc'] == 'true') {
        $query = 'update `users` SET date_freecycle="' . date('Y-m-d H:i:s') . '" WHERE `users`.`id` =' . $_GET['uid'];
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, date('Y-m-d'), PDO::PARAM_STR);
        $stmt->bindValue(2, $userdata['id'], PDO::PARAM_INT);
        $tmp = $stmt->execute();
    } else if (isset($_GET['enablecyc']) && $_GET['enablecyc'] == 'false') {
        $query = 'update `users` SET date_freecycle="" WHERE `users`.`id` =' . $_GET['uid'];
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, "", PDO::PARAM_STR);
        $stmt->bindValue(2, $userdata['id'], PDO::PARAM_INT);
        $tmp = $stmt->execute();
    }


    $query = 'SELECT * FROM `users` WHERE `users`.`id` =' . $_GET['uid'];
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
    $tmp = $stmt->execute();
    if ($tmp && $stmt->rowCount() > 0)
        $user_view_data = $stmt->fetchAll(PDO::FETCH_OBJ);

    //$last_ip = get_last_ip($user_view_data[0]->id);
}
?>
<div class="wrapper">
    <div class="container-fluid">
        <?php if ($user_view == 'show') { ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="admin-filter-wrap-box">
                        <div class="row">
                            <div class="col-sm-2">
                                <a href="https://www.planiversity.com/apanel/users" class="user-back-btn"><i
                                            class="fa fa-chevron-left"></i>Back To Users</a>
                            </div>
                            <div class="col-sm-2">
                                <a class="user-action-btn" data-toggle="modal" data-target="#add-comment-modal"
                                   data-backdrop="false"><i class="fa fa-comment"></i>Add Comment</a>
                            </div>
                            <div class="col-sm-2">
                                <a class="user-action-btn"><i class="fa fa-send"></i>Send Invoice</a>
                            </div>
                            <div class="col-sm-2">
                                <a class="user-action-btn"
                                   href="<?php echo SITE . 'apanel/users?user=activate&uid=' . $_GET['uid']; ?>"><i
                                            class="fa fa-check"></i>Activate</a>
                            </div>
                            <div class="col-sm-2">
                                <a class="user-action-btn"><i class="fa fa-pause"
                                                              href="<?php echo SITE . 'apanel/users?user=hold&uid=' . $_GET['uid']; ?>"></i>Hold
                                    Account</a>
                            </div>
                            <div class="col-sm-2">
                                <a href="<?php echo SITE . 'apanel/users?user=del&uid=' . $_GET['uid']; ?>"
                                   onClick="return confirm('Are you sure you want to delete this user ?, this action can not be undone!!');"
                                   class="user-action-btn"><i class="fa fa-trash"></i>Delete User</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card-box">
                        <h3 class="user-dt-hd">User Details</h3>
                        <ul class="admin-user-dt">
                            <li><p>Name: <span><?PHP echo $user_view_data[0]->name; ?></span></p></li>
                            <li><p>Email: <span><?PHP echo $user_view_data[0]->email; ?></span></p></li>
                            <li><p>Sign In Count: <span><?PHP echo $user_view_data[0]->sign_count; ?></span></p></li>
                            <li><p>Current sign In at:
                                    <span><?PHP echo date('M d, Y h:i a', strtotime($user_view_data[0]->date_current_login)); ?></span>
                                </p></li>
                            <li><p>Last Sign In:
                                    <span><?PHP echo date('M d, Y h:i a', strtotime($user_view_data[0]->date_last_login)); ?></span>
                                </p></li>
                            <li><p>Current Sign In IP: <span><?PHP echo $user_view_data[0]->ip_current_login; ?></span>
                                </p></li>
                            <li><p>Last Sign In IP: <span><?PHP echo $user_view_data[0]->ip_last_login; ?></span></p>
                            </li>
                            <li><p>Unconfirmed Emial:
                                    <span><?PHP echo ($user_view_data[0]->active) ? 'Active' : 'Unconfirmed' ?></span>
                                </p></li>
                            <li><p>Failed Attemp: <span><?PHP echo $user_view_data[0]->failed_attemps; ?></span></p>
                            </li>
                            <li>
                                <p>Enable Free Cycle:<span>
                                        <?PHP
                                        if ($user_view_data[0]->date_freecycle != '') {
                                            echo date('M d,Y h:i a', strtotime($user_view_data[0]->date_freecycle));
                                        } else {
                                            echo "No Cycle Found";
                                        }
                                        echo ' - <a href="' . SITE . 'apanel/users?user=view&uid=' . $_GET['uid'] . '&enablecyc=true">Reset cycle</a>'; ?>
                                            /
                                            <?php
                                            echo '<a href="' . SITE . 'apanel/users?user=view&uid=' . $_GET['uid'] . '&enablecyc=false">Disable</a>';
                                            ?>
                                       </span>
                                </p>
                            </li>
                        </ul>
                        <?php

                        $queryp = 'SELECT * FROM `payments` WHERE id_user =' . $_GET['uid'];
                        $stmtp = $dbh->prepare($queryp);
                        $stmtp->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                        $tmpp = $stmtp->execute();
                        $user_payment_data = '';
                        if ($tmpp && $stmtp->rowCount() > 0) {
                            $user_payment_data = $stmtp->fetchAll(PDO::FETCH_OBJ);

                            //$last_ip = get_last_ip($user_view_data[0]->id);
                        }
                        ?>
                        <h3 class="user-dt-hd">Payment History</h3>
                        <ul class="admin-user-dt">
                            <?php
                            if ($user_payment_data != '') {
                                foreach ($user_payment_data as $payment) {
                                    ?>
                                    <li><p>$<?php echo $payment->amount; ?>
                                            <span><?php echo date('M d,Y h:i a', strtotime($payment->date_paid)); ?></span>
                                        </p></li>
                                <?php }
                            } ?>
                            <!--<li><p>$20 <span>Jun 19, 2018 - 7:20am</span></p></li>
                            <li><p>$20 <span>Jun 19, 2018 - 7:20am</span></p></li>-->
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div id="add_comments_div" class="card-box">
                        <h3 class="user-dt-hd">Added Comments</h3>
                        <?php
                        $stmt = $dbh->prepare("SELECT * FROM `user_comments` WHERE user_id=" . $_GET['uid']);
                        $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                        $tmp = $stmt->execute();
                        $aux = '';
                        if ($tmp && $stmt->rowCount() > 0) {
                            $user_ = $stmt->fetchAll(PDO::FETCH_OBJ);

                            foreach ($user_ as $user_row) {
                                echo '<div id="dv-' . $user_row->id . '" class = "note-result-wrap">
                                                <p>' . date('M d, Y', strtotime($user_row->comment_time)) . ' --- ' . $user_row->comment . '.
                                                    <a href = "#" onClick="delComm(' . $user_row->id . ')" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class = "fa fa-close edit-icon"></i></a>
                                                </p>
                                            </div>';
                            }

                        }

                        ?>
                    </div>
                </div>
            </div>
            <script>
                function delComm(id) {
                    $.ajax({
                        method: "POST",
                        url: "/ajaxfiles/add_documents.php",
                        data: {purpose: "DelComment", commid: id}
                    })
                        .done(function (msg) {
                            alert("Comment deleted successfully!");
                            $('#dv-' + id).remove();
                        });

                }

            </script>


        <?php }else{ ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="admin-filter-wrap-box">
                        <form name="admin_users" method="get" class="form-horizontal">
                            <fieldset>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <h3>Search Filter&nbsp;<a class="right <?php echo $showclear; ?>"
                                                                      href="<?php echo SITE; ?>apanel/users">Clear</a>
                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="">
                                                <label>Email</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <div class="admin-select-style">
                                                <select onChange="this.form.submit()" name="s_type"
                                                        class="input-lg inp1">
                                                    <option <?php echo $contains; ?> value="contains">Contains</option>
                                                    <option <?php echo $equal; ?>>Equal to</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text" class="admin-form-control form-control input-lg"
                                                   name="search_string" value="<?php echo $_GET['search_string']; ?>"
                                                   title="write and press enter" required="">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        &nbsp;
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="">
                                            <label>Create At</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="">
                                            <input class="admin-form-control form-control input-lg"
                                                   onChange="this.form.submit()" name="created" id="created" type="text"
                                                   value="<?php echo $_GET['created']; ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="admin-form-control form-control input-lg"
                                               onChange="this.form.submit()" name="created2" id="created2" type="text"
                                               value="<?php echo $_GET['created2']; ?>"/>
                                    </div>
                                </div>
                            </div>
                            </fieldset>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        &nbsp;
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="">
                                            <label>Last Sign In At</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="">
                                            <input class="admin-form-control form-control input-lg"
                                                   onChange="this.form.submit()" name="last_singinat" id="last_singinat"
                                                   value="<?php echo $_GET['last_singinat']; ?>" type="text"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="admin-form-control form-control input-lg"
                                               onChange="this.form.submit()" name="last_singinat2" id="last_singinat2"
                                               value="<?php echo $_GET['last_singinat2']; ?>"/>
                                    </div>
                                </div>
                            </div>
                            </fieldset>
                            <script src="<?php echo SITE; ?>js/node_modules/php-date-formatter/js/php-date-formatter.min.js"></script>
                            <script src="<?php echo SITE; ?>js/node_modules/jquery-mousewheel/jquery.mousewheel.js"></script>
                            <script src="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.js"></script>
                            <script>
                                $.datetimepicker.setLocale('en');
                                $('#created').datetimepicker({
                                    inline: false,
                                    format: 'Y-m-d',
                                    onChangeDateTime: function () {
                                        var tmp = $('#created').val();
                                        $("#created").val(tmp);
                                    },
                                });
                                $('#created2').datetimepicker({
                                    inline: false,
                                    format: 'Y-m-d',
                                    onChangeDateTime: function () {
                                        var tmp = $('#created2').val();
                                        $("#created2").val(tmp);
                                    },
                                });

                                $('#last_singinat').datetimepicker({
                                    inline: false,
                                    format: 'Y-m-d',
                                    onChangeDateTime: function () {
                                        var tmp = $('#last_singinat').val();
                                        $("#last_singinat").val(tmp);
                                    },
                                });
                                $('#last_singinat2').datetimepicker({
                                    inline: false,
                                    format: 'Y-m-d',
                                    onChangeDateTime: function () {
                                        var tmp = $('#last_singinat2').val();
                                        $("#last_singinat2").val(tmp);
                                    },
                                });
                            </script>
                            </br>
                            <input name="filter" type="submit" style="display:none;" value="filter">
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <div class="table-wrap">
                        <div class="table-responsive table-striped">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <form method="get" id="dt-header-form">
                                            <th scope="col" id="ord_e" data-id="<?php
                                            if (isset($_GET['ord_e']) && !empty($_GET['ord_e'])) {
                                                if ($_GET['ord_e'] == 'ASC') echo 'DESC'; else echo 'ASC';
                                            } else  echo 'ASC';
                                            ?>" class="dt-header">Email
                                            <span class="<?php
                                            $new_load = !isset($_GET['ord_e']) &&
                                                        !isset($_GET['ord_c']) &&
                                                        !isset($_GET['ord_l']) &&
                                                        !isset($_GET['ord_u']);

                                            if (!$new_load) {
                                                if (isset($_GET['ord_e']) && !empty($_GET['ord_e'])) {
                                                    if ($_GET['ord_e'] == 'ASC') echo 'fa fa-arrow-up'; else echo 'fa fa-arrow-down';
                                                } else {
                                                    echo '';
                                                } 
                                            } else {
                                                echo "fa fa-arrow-down";
                                            }
                                            ?>
                                            "></span></th>
                                            <th scope="col" id="ord_c" data-id="<?php
                                            if (isset($_GET['ord_c']) && !empty($_GET['ord_c'])) {
                                                if ($_GET['ord_c'] == 'ASC') echo 'DESC'; else echo 'ASC';
                                            } else  echo 'ASC';
                                            ?>" class="dt-header">Created at
                                            <span class="<?php
                                            if (isset($_GET['ord_c']) && !empty($_GET['ord_c'])) {
                                                if ($_GET['ord_c'] == 'ASC') echo 'fa fa-arrow-up'; else echo 'fa fa-arrow-down';
                                            } else  echo '';
                                            ?>"></span></th>

                                            <th scope="col" id="ord_l" data-id="<?php
                                            if (isset($_GET['ord_l']) && !empty($_GET['ord_l'])) {
                                                if ($_GET['ord_l'] == 'ASC') echo 'DESC'; else echo 'ASC';
                                            } else  echo 'ASC';
                                            ?>" class="dt-header">Last Sign in at
                                            <span class="<?php
                                            if (isset($_GET['ord_l']) && !empty($_GET['ord_l'])) {
                                                if ($_GET['ord_l'] == 'ASC') echo 'fa fa-arrow-up'; else echo 'fa fa-arrow-down';
                                            } else  echo '';
                                            ?>"></span></th>

                                            <th scope="col" id="ord_u" data-id="<?php
                                            if (isset($_GET['ord_u']) && !empty($_GET['ord_u'])) {
                                                if ($_GET['ord_u'] == 'ASC') echo 'DESC'; else echo 'ASC';
                                            } else  echo 'ASC';
                                            ?>" class="dt-header">Account Status
                                            <span class="<?php
                                            if (isset($_GET['ord_u']) && !empty($_GET['ord_u'])) {
                                                if ($_GET['ord_u'] == 'ASC') echo 'fa fa-arrow-up'; else echo 'fa fa-arrow-down';
                                            } else  echo '';
                                            ?>"></span>
                                            </th>
                                            <th scope="col">&nbsp;</th>
                                            <th scope="col">&nbsp;</th>
                                            <input type="hidden" id="dt-header-form-data">
                                        </form>
                                    </tr>
                                </thead>
                                <tbody>
                                <?PHP $showuserform = 'hide';
                                if (isset($_GET['user']) && $_GET['user'] == 'new') {
                                    $showuserform = '';
                                }
                                //delete user
                                if (isset($_GET['user']) && $_GET['user'] == 'del' && !empty($_GET['uid'])) {
                                    $query = 'DELETE FROM `users` WHERE `users`.`id` =' . $_GET['uid'];
                                    $stmt = $dbh->prepare($query);
                                    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                    $tmp = $stmt->execute();
                                }
                                //activate user
                                if (isset($_GET['user']) && $_GET['user'] == 'activate' && !empty($_GET['uid'])) {
                                    $query = 'UPDATE `users` SET `active` = 1 WHERE `users`.`id` = ' . $_GET['uid'] . ';';
                                    $stmt = $dbh->prepare($query);
                                    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                    $tmp = $stmt->execute();
                                }
                                //hold user
                                if (isset($_GET['user']) && $_GET['user'] == 'hold' && !empty($_GET['uid'])) {
                                    $query = 'UPDATE `users` SET `active` = 0 WHERE `users`.`id` = ' . $_GET['uid'] . ';';
                                    $stmt = $dbh->prepare($query);
                                    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                    $tmp = $stmt->execute();
                                }
                                $output = '';
                                $name = '';
                                $email = '';
                                $Individual = '';
                                $Business = '';
                                $Admin = '';


                                if (isset($_POST['create_user'])) {
                                    function generate_customerid()
                                    {
                                        for ($i = 0; $i < 10; $i++) {
                                            $decstr = substr(md5(uniqid(rand(), true)), 10, 10);
                                            return $decstr;
                                        }
                                    }

                                    switch ($_POST['c_type']) {
                                        case "Individual":
                                            $Individual = 'selected="selected"';
                                            break;
                                        case "Business":
                                            $Business = 'selected="selected"';
                                            break;
                                        case "Admin":
                                            $Admin = 'selected="selected"';
                                            break;
                                        default :
                                            $s_type = 'LIKE';

                                    }

                                    $name = filter_var($_POST["c_name"], FILTER_SANITIZE_STRING);
                                    $email = filter_var($_POST["c_email"], FILTER_SANITIZE_STRING);
                                    $password = filter_var($_POST["c_pass"], FILTER_SANITIZE_STRING);
                                    $passwordconform = filter_var($_POST["c_pass2"], FILTER_SANITIZE_STRING);
                                    $clientID = generate_customerid();

                                    $params = array("name" => $name, "account_type" => $_POST["c_type"], "customer_number" => $clientID);
                                    // $result = $auth->register($email, $password, $passwordconform, $params, null, $sendmail = false);
                                    $result = $auth->register($email, $password, null, '', $passwordconform, $params, null, true);
                                    //$result = $auth->register($email, $password, $passwordconform, $params, $_POST['g-recaptcha-response'], $sendmail = true);
                                    if ($result['error']) {
                                        $output = $result['message'];
                                    } else {
                                        // send clientID number by email
                                        /*$mail = new PHPMailer;
                                         $mail->CharSet = 'UTF-8';
                                         $mail->From = 'info@planiversity.com';
                                         $mail->FromName = 'Planiversity.com';
                                         $mail->addAddress($email);
                                         $mail->isHTML(true);
                                         $mail->Subject = 'Planiversity.com - Account created';
                                         $mail->Body = 'Hello,<br/><br/> Your clientID is <b>'.$clientID.'</b>, you can use this number to login here <a href="'.SITE.'">Planiversity.com</a>';
                                         $mail->send();    */
                                        // send clientID number by email
                                        $output = $result['message'];
                                        $output .= ' User Created.';
                                        $name = '';
                                        $email = '';
                                        $showuserform = 'hide';
                                    }
                                }
                                ?>

                                <?php
                                $s_where = '';
                                $showclear = 'hide';
                                if (isset($_GET['s_type'])) {
                                    switch ($_GET['s_type']) {
                                        case "contains":
                                            $s_type = 'LIKE';
                                            $simbol = '%';
                                            $contains = 'selected="selected"';
                                            break;
                                        case "equal":
                                            $s_type = '=';
                                            $simbol = '';
                                            $equal = 'selected="selected"';
                                            break;
                                        default :
                                            $s_type = 'LIKE';

                                    }
                                    $showclear = '';
                                }
                                if (isset($_GET['search_string']) && !empty($_GET['search_string'])) {
                                    if (!empty($_GET['created']) || !empty($_GET['last_singinat']))
                                        $s_email .= " WHERE `email` " . $s_type . " '" . $simbol . $_GET['search_string'] . $simbol . "' AND ";
                                    else
                                        $s_email .= " WHERE `email` " . $s_type . " '" . $simbol . $_GET['search_string'] . $simbol . "' ";
                                }

                                //covering CREATED AT CASE //
                                if (isset($_GET['created']) && !empty($_GET['created'])) {
                                    if (!empty($_GET['search_string'])) {

                                        if (!empty($_GET['created']) && !empty($_GET['created2']))
                                            $s_email .= "`date_created` BETWEEN '" . $_GET['created'] . " 00:00:00.000000' AND '" . $_GET['created2'] . " 23:59:59.000000'";
                                        else
                                            $s_email .= "`date_created` LIKE '%" . $_GET['created'] . "%'";

                                    } else {
                                        if (!empty($_GET['created']) && !empty($_GET['created2']))
                                            $s_email .= " WHERE `date_created` BETWEEN '" . $_GET['created'] . " 00:00:00.000000' AND '" . $_GET['created2'] . " 23:59:59.000000'";
                                        else
                                            $s_email .= " WHERE `date_created` LIKE '%" . $_GET['created'] . "%'";
                                    }

                                }

                                //covering LAST SIGN IN AT
                                if (isset($_GET['last_singinat']) && !empty($_GET['last_singinat'])) {
                                    if (!empty($_GET['search_string']) || !empty($_GET['created'])) {
                                        if (!empty($_GET['last_singinat']) && !empty($_GET['last_singinat2']))
                                            $s_email .= " AND `date_last_login` BETWEEN '" . $_GET['last_singinat'] . " 00:00:00.000000' AND '" . $_GET['last_singinat2'] . " 23:59:59.000000'";
                                        else
                                            $s_email .= " AND `date_last_login` LIKE '%" . $_GET['last_singinat'] . "%'";
                                    } else {
                                        if (!empty($_GET['last_singinat']) && !empty($_GET['last_singinat2']))
                                            $s_email .= " WHERE `date_last_login` BETWEEN '" . $_GET['last_singinat'] . " 00:00:00.000000' AND '" . $_GET['last_singinat2'] . " 23:59:59.000000'";
                                        else
                                            $s_email .= " WHERE `date_last_login` LIKE '%" . $_GET['last_singinat'] . "%'";
                                    }
                                }

                                //echo $s_email.$orderby;
                                $stmt2 = $dbh->prepare("SELECT * FROM `users`" . $s_email . $orderby);
                                $stmt2->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                $tmp2 = $stmt2->execute();
                                $total_rows = $stmt2->rowCount();
                                // find out how many rows are in the table 
                                $numrows = $total_rows;
                                // number of rows to show per page
                                $rowsperpage = $config->__get('rows_per_page');
                                // find out total pages
                                $totalpages = ceil($numrows / $rowsperpage);
                                // get the current page or set a default
                                if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
                                    // cast var as int
                                    $currentpage = (int)$_GET['currentpage'];
                                } else {
                                    // default page num
                                    $currentpage = 1;
                                } // end if
                                // if current page is greater than total pages...
                                if ($currentpage > $totalpages) {
                                    // set current page to last page
                                    $currentpage = $totalpages;
                                } // end if
                                // if current page is less than first page...
                                if ($currentpage < 1) {
                                    // set current page to first page
                                    $currentpage = 1;
                                } // end if
                                // the offset of the list, based on current page 
                                $offset = ($currentpage - 1) * $rowsperpage;


                                $stmt = $dbh->prepare("SELECT * FROM `users`" . $s_email . $orderby . " LIMIT " . $offset . ',' . $rowsperpage);
                                $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                $tmp = $stmt->execute();
                                $aux = '';
                                if ($tmp && $stmt->rowCount() > 0) {
                                    $user_ = $stmt->fetchAll(PDO::FETCH_OBJ);
                                    $bg1 = '';
                                    $c = 0;

                                    foreach ($user_ as $user_row) {
                                        $c++;
                                        $aux .= ' <tr ' . ($c == 1 ? 'class="bg1"' : '') . '>
                                                   <td>' . $user_row->email . '</td>
                                                   <td>' . date('M d, Y h:i a', strtotime($user_row->date_created)) . '</td>
                                                   <td>' . date('M d, Y h:i a', strtotime($user_row->date_last_login)) . '</td>
                                                   <td>' . ($user_row->active ? 'Active' : 'Unconfirmed') . '</td>
                                                   <td class="view_del">
                                                   <td class = "text-right"><a href="' . SITE . 'apanel/users?user=view&uid=' . $user_row->id . '" class = "admin-action-btn"><i class = "fa fa-eye"></i>View</a>
                                                        <a onclick="return confirm(\'Are you sure you want to delete user:' . $user_row->email . '?, this action can not be undone!!\');" href = "' . SITE . 'apanel/users?user=del&uid=' . $user_row->id . '" class = "admin-action-btn"><i class = "fa fa-trash"></i>Delete</a></td>
                                                   </td>
                                                  </tr>';

                                        if ($c == 2) $c = 0;
                                    }
                                    echo $aux;
                                }
                                ?>
                                </tbody>
                            </table>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?PHP /******  build the pagination links ******/
                                    // range of num links to show
                                    $range = 3;
                                    // if not on page 1, don't show back links
                                    if ($currentpage > 1) {
                                        // show << link to go back to page 1
                                        echo '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?currentpage=1">First</a></li>';
                                        //echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
                                        // get previous page num
                                        $prevpage = $currentpage - 1;
                                        // show < link to go back to 1 page
                                        echo '<li class="page-item">
                                          <a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?currentpage=' . $prevpage . '" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                            <span class="sr-only">Previous</span>
                                          </a>
                                        </li>';
                                        //echo " <a href=''><</a> ";
                                    } // end if
                                    // loop to show links to range of pages around current page
                                    for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
                                        // if it's a valid page number...
                                        if (($x > 0) && ($x <= $totalpages)) {
                                            // if we're on current page...
                                            if ($x == $currentpage) {
                                                // 'highlight' it but don't make a link
                                                echo " <li class='page-item'><span class='page-link'><b>" . $x . "</b></span></li>";
                                                // if not current page...
                                            } else {
                                                // make it a link
                                                echo '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?currentpage=' . $x . '">' . $x . '</a></li>';
                                                //echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
                                            } // end else
                                        } // end if
                                    } // end for
                                    // if not on last page, show forward and last page links
                                    if ($currentpage != $totalpages) {
                                        // get next page
                                        $nextpage = $currentpage + 1;
                                        // echo forward link for next page
                                        echo '<li class="page-item">
                                          <a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?currentpage=' . $nextpage . '" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                            <span class="sr-only">Next</span>
                                          </a>
                                        </li>';
                                        echo '<li class="page-item">
                                          <a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?currentpage=' . $totalpages . '" aria-label="Next">
                                            <span aria-hidden="true">Last</span>
                                            <span class="sr-only">Last</span>
                                          </a>
                                        </li>';
                                        // echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
                                        // echo forward link for lastpage
                                        //echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
                                    } // end if
                                    /****** end build pagination links ******/
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="add-comment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content wht-bg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <textarea class="textarea-control form-control input-lg" id="user_com" name="user_com"
                                      rows="5"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button type="button" id="comButton" class="add-user-btn">Add Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('userModal.php'); ?>
<script>
    $("#comButton").click(function () {
        let uid = "<?php echo $_GET['uid'] ?>";
        $.ajax({
            method: "POST",
            url: "/ajaxfiles/add_documents.php",
            data: {purpose: "addComment", uid: parseInt(uid), comment: $("#user_com").val()}
        })
            .done(function (msg) {
                alert("Comment saved successfully!");
                data = JSON.parse(msg);
                $("#add_comments_div").append('<div id="dv-' + data.ins_ud + '"class = "note-result-wrap"><p>' + data.ins_dt + ' --- ' + $("#user_com").val() + '.<a onClick="delComm(' + data.ins_ud + ')" href = "#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class = "fa fa-close edit-icon"></i></a></p></div>');
                $("#add-comment-modal").hide();
                $("#add-comment-modal").html('');
            });
    });
    $(".dt-header").on("click",function(){
        var index = $(this).attr("id");
        var order_dir = $(this).data("id");
        document.getElementById("dt-header-form-data").setAttribute("name",index)
        document.getElementById("dt-header-form-data").setAttribute("value",order_dir)
        $("#dt-header-form").submit()
    });
</script>
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