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

                            <a href="<?php echo SITE; ?>welcome" class="dropdown-item drop-menu-item">
                                Home
                            </a>   
                            
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
                    <li class="active-link">
                        <a href="<?php echo SITE; ?>apanel/transactions">Transactions</a>
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
        <div class="row">
            <div class="col-sm-12">
                <div class="admin-filter-wrap-box">
                    <form name="admin_users" method="get" class="form-horizontal">
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <h3>Search Filter&nbsp;<a class="right <?php echo $showclear; ?>"
                                                                  href="<?php echo SITE; ?>apanel/users">Clear</a></h3>
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
                                            <select onChange="this.form.submit()" name="s_type" class="input-lg inp1">
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
                                        <label>PAID AT</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="">
                                        <input class="admin-form-control form-control input-lg"
                                               onChange="this.form.submit()" name="date_paid" id="date_paid" type="text"
                                               value="<?php echo $_GET['date_paid']; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input class="admin-form-control form-control input-lg"
                                           onChange="this.form.submit()" name="date_paid2" id="date_paid2" type="text"
                                           value="<?php echo $_GET['date_paid2']; ?>"/>
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
                                        <label>EXPIRE AT</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="">
                                        <input class="admin-form-control form-control input-lg"
                                               onChange="this.form.submit()" name="expire_at" id="expire_at"
                                               value="<?php echo $_GET['expire_at']; ?>" type="text"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input class="admin-form-control form-control input-lg"
                                           onChange="this.form.submit()" name="expire_at2" id="expire_at2"
                                           value="<?php echo $_GET['expire_at2']; ?>"/>
                                </div>
                            </div>
                        </div>
                        </fieldset>
                        <script src="<?php echo SITE; ?>js/node_modules/php-date-formatter/js/php-date-formatter.min.js"></script>
                        <script src="<?php echo SITE; ?>js/node_modules/jquery-mousewheel/jquery.mousewheel.js"></script>
                        <script src="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.js"></script>
                        <script>
                            $.datetimepicker.setLocale('en');
                            $('#date_paid').datetimepicker({
                                inline: false,
                                format: 'Y-m-d',
                                onChangeDateTime: function () {
                                    var tmp = $('#date_paid').val();
                                    $("#date_paid").val(tmp);
                                },
                            });
                            $('#date_paid2').datetimepicker({
                                inline: false,
                                format: 'Y-m-d',
                                onChangeDateTime: function () {
                                    var tmp = $('#date_paid2').val();
                                    $("#date_paid2").val(tmp);
                                },
                            });

                            $('#expire_at').datetimepicker({
                                inline: false,
                                format: 'Y-m-d',
                                onChangeDateTime: function () {
                                    var tmp = $('#expire_at').val();
                                    $("#expire_at").val(tmp);
                                },
                            });
                            $('#expire_at2').datetimepicker({
                                inline: false,
                                format: 'Y-m-d',
                                onChangeDateTime: function () {
                                    var tmp = $('#expire_at2').val();
                                    $("#expire_at2").val(tmp);
                                },
                            });
                        </script>
                        </br>
                        <input name="filter" type="submit" style="display:none;" value="filter">
                    </form>
                </div>
            </div>
        </div>
        <?php
        $user_view = 'hide';
        if (isset($_GET['payment']) && $_GET['payment'] == 'view' && !empty($_GET['pid']))
            $user_view = 'show';

        if (isset($_COOKIE['Planiversity_pay_user']) && !empty($_COOKIE['Planiversity_pay_user']))
            $orderby = $_COOKIE['Planiversity_pay_user'];
        else
            $orderby = ' ORDER BY `payments`.`date_paid` ASC';

        if (isset($_GET['ord_e']) && !empty($_GET['ord_e'])) {
            if ($_GET['ord_e'] == 'ASC') $ord_e = 'DESC'; else $ord_e = 'ASC';
            $orderby = ' ORDER BY `users`.`email` ' . $ord_e;
        } else  $ord_e = 'ASC';

        if (isset($_GET['ord_p']) && !empty($_GET['ord_p'])) {
            if ($_GET['ord_p'] == 'ASC') $ord_p = 'DESC'; else $ord_p = 'ASC';
            $orderby = ' ORDER BY `payments`.`plan_type` ' . $ord_p;
        } else  $ord_p = 'ASC';

        if (isset($_GET['ord_dp']) && !empty($_GET['ord_dp'])) {
            if ($_GET['ord_dp'] == 'ASC') $ord_dp = 'DESC'; else $ord_dp = 'ASC';
            $orderby = ' ORDER BY `payments`.`date_paid` ' . $ord_dp;
        } else  $ord_dp = 'ASC';

        if (isset($_GET['ord_de']) && !empty($_GET['ord_de'])) {
            if ($_GET['ord_de'] == 'ASC') $ord_de = 'DESC'; else $ord_de = 'ASC';
            $orderby = ' ORDER BY `payments`.`date_expire` ' . $ord_de;
        } else  $ord_de = 'ASC';

        if (isset($_GET['ord_a']) && !empty($_GET['ord_a'])) {
            if ($_GET['ord_a'] == 'ASC') $ord_a = 'DESC'; else $ord_a = 'ASC';
            $orderby = ' ORDER BY `payments`.`amount` ' . $ord_a;
        } else  $ord_a = 'ASC';

        if (isset($_GET['ord_s']) && !empty($_GET['ord_s'])) {
            if ($_GET['ord_s'] == 'ASC') $ord_s = 'DESC'; else $ord_s = 'ASC';
            $orderby = ' ORDER BY `payments`.`status` ' . $ord_s;
        } else  $ord_s = 'ASC';

        //store a cookie for 1 day user friendly behavior
        if (isset($_GET['ord_e']) || isset($_GET['ord_p']) || isset($_GET['ord_dp']) || isset($_GET['ord_de']) || isset($_GET['ord_a']) || isset($_GET['ord_s']))
            setcookie('Planiversity_pay_user', $orderby, time() + (86400 * 30), "/"); // 86400 = 1 day

        if (isset($_GET['payment']) && $_GET['payment'] == 'view' && !empty($_GET['pid'])) {
            $query = 'SELECT users.id,users.name,users.email,payments.* FROM `users` INNER JOIN payments ON users.id=payments.id_user WHERE `payments`.`id_payment` =' . $_GET['pid'];
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if ($tmp && $stmt->rowCount() > 0)
                $user_view_data = $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        if ($user_view == 'show') { ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h3 class="user-dt-hd">Payment Details</h3>
                        <ul class="admin-user-dt">
                            <li><p>Email: <span><?PHP echo $user_view_data[0]->email; ?></span></p></li>
                            <li><p>First Name: <span><?PHP echo $user_view_data[0]->fname; ?></span></p></li>
                            <li><p>Last Name: <span><?PHP echo $user_view_data[0]->lname; ?></span></p></li>
                            <li><p>Country: <span><?PHP echo $user_view_data[0]->country; ?></span></p></li>
                            <li><p>Address: <span><?PHP echo $user_view_data[0]->address; ?></span></p></li>
                            <li><p>City: <span><?PHP echo $user_view_data[0]->city; ?></span></p></li>
                            <li><p>State: <span><?PHP echo $user_view_data[0]->state; ?></span></p></li>
                            <li><p>Zip Code: <span><?PHP echo $user_view_data[0]->zipcode; ?></span></p></li>
                            <li><p>Plan Type: <span><?PHP echo $user_view_data[0]->plan_type; ?></span></p></li>
                            <li><p>Date Paid:
                                    <span><?PHP echo date('M d,Y h:i a', strtotime($user_view_data[0]->date_paid)); ?></span>
                                </p></li>
                            <li><p>Date Expire:
                                    <span><?PHP echo date('M d,Y h:i a', strtotime($user_view_data[0]->date_expire)); ?></span>
                                </p></li>
                            <li><p>Amount: <span><?PHP echo $user_view_data[0]->amount; ?></span></p></li>
                            <li><p>Status: <span><?PHP echo $user_view_data[0]->status; ?></span></p></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <div class="export-wrap">
                        <h3>Transactions</h3>
                        <div class="btn-group exp-dropdown">
                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                Export Data
                            </button>
                            <div class="dropdown-menu">
                                <a target="_blank" class="dropdown-item" href="/exportTransactions.php?mode=xls">To
                                    Excel</a>
                                <a target="_blank" class="dropdown-item" href="/exportTransactions.php?mode=csv">To
                                    CSV</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-wrap">
                        <div class="table-responsive table-striped">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Email</th>
                                    <th scope="col">Plan Type</th>
                                    <th scope="col">Date Paid</th>
                                    <th scope="col">Date Expire</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?PHP
                                $showuserform = 'hide';
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
                                    if (!empty($_GET['date_paid']) || !empty($_GET['expire_at']))
                                        $s_email .= " WHERE users.`email` " . $s_type . " '" . $simbol . $_GET['search_string'] . $simbol . "' AND ";
                                    else
                                        $s_email .= " WHERE users.`email` " . $s_type . " '" . $simbol . $_GET['search_string'] . $simbol . "' ";
                                }

                                //covering payment date //
                                if (isset($_GET['date_paid']) && !empty($_GET['date_paid'])) {
                                    if (!empty($_GET['search_string'])) {

                                        if (!empty($_GET['date_paid']) && !empty($_GET['date_paid2']))
                                            $s_email .= "payments.`date_paid` BETWEEN '" . $_GET['date_paid'] . " 00:00:00.000000' AND '" . $_GET['date_paid2'] . " 23:59:59.000000'";
                                        else
                                            $s_email .= "payments.`date_paid` LIKE '%" . $_GET['date_paid'] . "%'";

                                    } else {
                                        if (!empty($_GET['date_paid']) && !empty($_GET['date_paid2']))
                                            $s_email .= " WHERE payments.`date_paid` BETWEEN '" . $_GET['date_paid'] . " 00:00:00.000000' AND '" . $_GET['date_paid2'] . " 23:59:59.000000'";
                                        else
                                            $s_email .= " WHERE payments.`date_paid` LIKE '%" . $_GET['date_paid'] . "%'";
                                    }

                                }

                                //covering expire date
                                if (isset($_GET['expire_at']) && !empty($_GET['expire_at'])) {
                                    if (!empty($_GET['search_string']) || !empty($_GET['date_paid'])) {
                                        if (!empty($_GET['expire_at']) && !empty($_GET['expire_at2']))
                                            $s_email .= " AND payments.`date_expire` BETWEEN '" . $_GET['expire_at'] . " 00:00:00.000000' AND '" . $_GET['expire_at2'] . " 23:59:59.000000'";
                                        else
                                            $s_email .= " AND payments.`date_expire` LIKE '%" . $_GET['expire_at'] . "%'";
                                    } else {
                                        if (!empty($_GET['expire_at']) && !empty($_GET['expire_at2']))
                                            $s_email .= " WHERE payments.`date_expire` BETWEEN '" . $_GET['expire_at'] . " 00:00:00.000000' AND '" . $_GET['expire_at2'] . " 23:59:59.000000'";
                                        else
                                            $s_email .= " WHERE payments.`date_expire` LIKE '%" . $_GET['expire_at'] . "%'";
                                    }
                                }


                                //echo $s_email.$orderby;
                                $stmt2 = $dbh->prepare("SELECT users.id,users.name,users.email,payments.* FROM `users` INNER JOIN payments ON users.id=payments.id_user" . $s_email . $orderby);
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


                                $_SESSION['exportQuery'] = "SELECT users.id,users.name,users.email,payments.* FROM `users` INNER JOIN payments ON users.id=payments.id_user" . $s_email . $orderby;
                                $_SESSION['expuid'] = $userdata['id'];
                                $stmt = $dbh->prepare("SELECT users.id,users.name,users.email,payments.* FROM `users` INNER JOIN payments ON users.id=payments.id_user" . $s_email . $orderby . " LIMIT " . $offset . ',' . $rowsperpage);
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
																			   <td>' . $user_row->plan_type . '</td>
																			   <td>' . date('M d,Y h:i a', strtotime($user_row->date_paid)) . '</td>
																			   <td>' . date('M d,Y h:i a', strtotime($user_row->date_expire)) . '</td>
																			   <td>' . $user_row->amount . '</td>
																			   <td>' . $user_row->status . '</td>
																				<td class="text-right">
			<a href="' . SITE . 'apanel/transactions?payment=view&pid=' . $user_row->id_payment . '" class="admin-action-btn"><i class = "fa fa-eye"></i>View</a></a>
			<!--<a href="' . SITE . 'apanel/users?user=del&uid=' . $user_row->id . '" onclick="return confirm(\'Are you sure you want to delete user:' . $user_row->email . '?, this action can not be undone!!\');"><img src="' . SITE . 'images/icon_del.png" alt="" />Delete</a>-->
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
                                        echo '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?currentpage=1"><<<</a></li>';
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
                                                echo " <li class='page-item'><b class='page-link'>" . $x . "</b></li>";
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
                                            <span aria-hidden="true">&raquo;</span>
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

<script>
    $('#datetimepicker').datetimepicker({
        inline: true
    });
</script>
</body>
</html>