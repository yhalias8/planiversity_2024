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
                    <li class="active-link">
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
<?php $user_view = 'hide';
if (isset($_GET['trip']) && $_GET['trip'] == 'view' && !empty($_GET['tid']))
    $user_view = 'show';
?>

<section class="admin_subheader">
    <div class="admin_in">
        <p><a href="<?php echo SITE; ?>apanel/routes">Admin</a> / <strong>Routes</strong></p>

    </div>
</section>


<?php
if (isset($_COOKIE['Planiversity_ord_routes']) && !empty($_COOKIE['Planiversity_ord_routes']))
    $orderby = $_COOKIE['Planiversity_ord_routes'];
else
    $orderby = ' ORDER BY `trips`.`date_created` ASC';

if (isset($_GET['ord_e']) && !empty($_GET['ord_e'])) {
    if ($_GET['ord_e'] == 'ASC') $ord_e = 'DESC'; else $ord_e = 'ASC';
    $orderby = ' ORDER BY `users`.`email` ' . $ord_e;
} else  $ord_e = 'ASC';

if (isset($_GET['ord_t']) && !empty($_GET['ord_t'])) {
    if ($_GET['ord_t'] == 'ASC') $ord_t = 'DESC'; else $ord_t = 'ASC';
    $orderby = ' ORDER BY `trips`.`title` ' . $ord_t;
} else  $ord_t = 'ASC';

if (isset($_GET['ord_c']) && !empty($_GET['ord_c'])) {
    if ($_GET['ord_c'] == 'ASC') $ord_c = 'DESC'; else $ord_c = 'ASC';
    $orderby = ' ORDER BY `trips`.`transport` ' . $ord_c;
} else  $ord_c = 'ASC';

if (isset($_GET['ord_l']) && !empty($_GET['ord_l'])) {
    if ($_GET['ord_l'] == 'ASC') $ord_l = 'DESC'; else $ord_l = 'ASC';
    $orderby = ' ORDER BY `trips`.`location_from` ' . $ord_l;
} else  $ord_l = 'ASC';

if (isset($_GET['ord_u']) && !empty($_GET['ord_u'])) {
    if ($_GET['ord_u'] == 'ASC') $ord_u = 'DESC'; else $ord_u = 'ASC';
    $orderby = ' ORDER BY `trips`.`location_to` ' . $ord_u;
} else  $ord_u = 'ASC';

//store a cookie for 1 day user friendly behavior
if (isset($_GET['ord_e']) || isset($_GET['ord_c']) || isset($_GET['ord_l']) || isset($_GET['ord_u']))
    setcookie('Planiversity_ord_routes', $orderby, time() + (86400 * 30), "/"); // 86400 = 1 day


?>

<?PHP
if (isset($_GET['trip']) && $_GET['trip'] == 'view' && !empty($_GET['tid'])) {
    $query = 'SELECT users.id,users.name,users.email,trips.* FROM `users` INNER JOIN trips ON users.id=trips.id_user WHERE `trips`.`id_trip` =' . $_GET['tid'];
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
    $tmp = $stmt->execute();
    if ($tmp && $stmt->rowCount() > 0)
        $user_view_data = $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>
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
                                            <label>Title</label>
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
                                        <label>CREATED AT</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div>
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
                        </script>
                        </br>
                        <input name="filter" type="submit" style="display:none;" value="filter">
                    </form>
                </div>
            </div>
        </div>
        <?PHP
        if (isset($_GET['trip']) && $_GET['trip'] == 'view' && !empty($_GET['tid'])) {
            $query = 'SELECT users.id,users.name,users.email,trips.* FROM `users` INNER JOIN trips ON users.id=trips.id_user WHERE `trips`.`id_trip` =' . $_GET['tid'];
            $stmt = $dbh->prepare($query);
            $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
            $tmp = $stmt->execute();
            if ($tmp && $stmt->rowCount() > 0)
                $user_view_data = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        ?>
        <?php if ($user_view == "show") { ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h3 class="user-dt-hd">Trip Details</h3>
                        <ul class="admin-user-dt">
                            <li><p>Email: <span><?PHP echo $user_view_data[0]->email; ?></span></p></li>
                            <li><p>Title: <span><?PHP echo $user_view_data[0]->title; ?></span></p></li>
                            <li><p>Transport: <span><?PHP echo $user_view_data[0]->transport; ?></span></p></li>
                            <li><p>Location From: <span><?PHP echo $user_view_data[0]->location_from; ?></span></p></li>
                            <li><p>Location To: <span><?PHP echo $user_view_data[0]->location_to; ?></span></p></li>
                            <li><p>Date Created:
                                    <span><?PHP echo date('M d,Y h:i a', strtotime($user_view_data[0]->date_created)); ?></span>
                                </p></li>
                        </ul>
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
                                    <th scope="col">Email</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Transport</th>
                                    <th scope="col">From</th>
                                    <th scope="col">To</th>
                                    <th scope="col">&nbsp;</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
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
                                        $s_email .= " WHERE `title` " . $s_type . " '" . $simbol . $_GET['search_string'] . $simbol . "' AND ";
                                    else
                                        $s_email .= " WHERE `title` " . $s_type . " '" . $simbol . $_GET['search_string'] . $simbol . "' ";
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

                                //delete trip
                                if (isset($_GET['trip']) && $_GET['trip'] == 'del' && !empty($_GET['tid'])) {
                                    $query = 'DELETE FROM `trips` WHERE `trips`.`id_trip` =' . $_GET['tid'];
                                    $stmt = $dbh->prepare($query);
                                    $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                    $tmp = $stmt->execute();
                                }

                                //echo $s_email.$orderby;
                                $stmt2 = $dbh->prepare("SELECT users.id,users.name,users.email,trips.* FROM `users` INNER JOIN trips ON users.id=trips.id_user" . $s_email . $orderby);
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

                                $stmt = $dbh->prepare("SELECT users.id,users.name,users.email,trips.* FROM `users` INNER JOIN trips ON users.id=trips.id_user" . $s_email . $orderby . " LIMIT " . $offset . ',' . $rowsperpage);
                                $stmt->bindValue(1, $userdata['id'], PDO::PARAM_INT);
                                $tmp = $stmt->execute();
                                $aux = '';
                                if ($tmp && $stmt->rowCount() > 0) {
                                    $user_ = $stmt->fetchAll(PDO::FETCH_OBJ);
                                    $bg1 = '';
                                    $c = 0;
                                    foreach ($user_ as $user_row) {
                                        $c++;
                                        $aux .= ' <tr>
												   <td>' . $user_row->email . '</td>
												   <td>' . $user_row->title . '</td>
												   <td>' . $user_row->transport . '</td>
												   <td>' . $user_row->location_from . '</td>
												   <td>' . $user_row->location_to . '</td>
												   <td class = "text-right">
														<a href = "' . SITE . 'apanel/routes?trip=view&tid=' . $user_row->id_trip . '" class = "admin-action-btn"><i class = "fa fa-eye"></i>View</a>
														<a href = "' . SITE . 'apanel/routes?trip=del&tid=' . $user_row->id_trip . '" onclick="return confirm(\'Delete trip: ' . $user_row->title . ' ?, this action can not be undone!!\');" class = "admin-action-btn"><i class = "fa fa-trash"></i>Delete</a>
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
<script src="<?php echo SITE;?>assets/js/popper.min.js"></script>
<script src="<?php echo SITE;?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo SITE; ?>assets/js/jquery.slimscroll.js"></script>
<script src="<?php echo SITE; ?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo SITE; ?>assets/js/jquery.app.js"></script>

</body>
</html>