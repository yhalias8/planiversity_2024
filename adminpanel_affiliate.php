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
<html lang="en">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Planiversity | Affiliate Management</title>
    <meta name="description" content="Planiversity turns travelers into confident and well-prepared travel professionals, providing travel information beyond itinerary management">
    <meta name="keywords" content="Consolidated Travel Information Management">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE; ?>images/favicon.png">

    <!--calendar css-->
    <link href="<?php echo SITE; ?>assets/css/fullcalendar.min.css" rel="stylesheet" />

    <link href="<?php echo SITE; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo SITE; ?>assets/css/app-style.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo SITE; ?>assets/js/modernizr.min.js"></script>

    <script src="<?php echo SITE; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo SITE; ?>assets/js/moment.min.js"></script>
    <script src='<?php echo SITE; ?>assets/js/fullcalendar.min.js'></script>
    <script>
        var SITE = '<?php echo SITE; ?>'
    </script>
    <link href="<?php echo SITE; ?>js/node_modules/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
    <style>
        .dt-header {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header id="topnav">
        <div class="topbar-main">
            <div class="container-fluid">
                <div class="logo">
                    <a href="<?php echo SITE; ?>" class="logo">
                        <span class="logo-small"><img src="<?php echo SITE; ?>assets/images/logo-icon.png" alt="logo icon"></span>
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
                        <li class="list-inline-item dropdown more-nav-list new-backend-header-style">
                            <a class="nav-link dropdown-toggle arrow-none link-drop" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <span class="fa fa-chevron-down mr-2"></span>
                                <span><?php echo isset($userdata['name']) ? $userdata['name'] : "Test"; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-arrow dropdown-menu-lg" aria-labelledby="Preview">
                                <?php
                                if ($userdata['account_type'] == 'Admin') {
                                ?>
                                    <a class="dropdown-item drop-menu-item" href="<?= SITE; ?>apanel/affiliate" target="_blank">
                                        Admin
                                    </a>
                                <?php } ?>
                               
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
                        <li class="list-inline-item dropdown more-nav-list new-backend-header-style">
                            <?php
                            $img = 'images/img3.png';
                            if ($userdata['picture']) $img = 'ajaxfiles/profile/' . $userdata['picture'];
                            ?>
                            <img src="<?php echo SITE . $img; ?>" width="35px" height="35px" alt="user" class="header-avatar rounded-circle">
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <style>
            .navigation-menu.header-top-bar-style li a {
                color: #007bff;
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
                        <li class="active-link">
                            <a href="<?php echo SITE; ?>apanel/affiliate">Affiliate</a>
                        </li>
                        <li>
                            <a href="<?php echo SITE; ?>apanel/users">Users</a>
                        </li>
                        <li>
                        <a href="<?php echo SITE; ?>apanel/coupon">Coupon</a>
                        </li>                        
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <?php
    if (isset($_COOKIE['Planiversity_affiliate']) && !empty($_COOKIE['Planiversity_affiliate']))
        $orderby = $_COOKIE['Planiversity_affiliate'];
    else
        $orderby = ' ORDER BY `affiliate`.`created_at` DESC';

    if (isset($_GET['ord_e']) && !empty($_GET['ord_e'])) {
        if ($_GET['ord_e'] == 'ASC') $ord_e = 'DESC';
        else $ord_e = 'ASC';
        $orderby = ' ORDER BY `affiliate`.`email` ' . $ord_e;
    } else  $ord_e = 'ASC';

    if (isset($_GET['ord_c']) && !empty($_GET['ord_c'])) {
        if ($_GET['ord_c'] == 'ASC') $ord_c = 'DESC';
        else $ord_c = 'ASC';
        $orderby = ' ORDER BY `affiliate`.`created_at` ' . $ord_c;
    } else  $ord_c = 'ASC';

    if (isset($_GET['ord_n']) && !empty($_GET['ord_n'])) {
        if ($_GET['ord_n'] == 'ASC') $ord_n = 'DESC';
        else $ord_n = 'ASC';
        $orderby = ' ORDER BY `affiliate`.`first_name` ' . $ord_n;
    } else  $ord_n = 'ASC';

    if (isset($_GET['ord_l']) && !empty($_GET['ord_l'])) {
        if ($_GET['ord_l'] == 'ASC') $ord_l = 'DESC';
        else $ord_l = 'ASC';
        $orderby = ' ORDER BY `affiliate`.`last_name` ' . $ord_l;
    } else  $ord_l = 'ASC';

    //store a cookie for 1 day user friendly behavior
    if (isset($_GET['ord_e']) || isset($_GET['ord_c']) || isset($_GET['ord_l']) || isset($_GET['ord_n']))
        setcookie('Planiversity_affiliate', $orderby, time() + (86400 * 30), "/"); // 86400 = 1 day
    ?>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="table-wrap">
                            <div class="table-responsive table-striped">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <form method="get" id="dt-header-form">
                                                <th scope="col" id="ord_u" data-id="<?php
                                                                                    if (isset($_GET['ord_n']) && !empty($_GET['ord_n'])) {
                                                                                        if ($_GET['ord_n'] == 'ASC') echo 'DESC';
                                                                                        else echo 'ASC';
                                                                                    } else  echo 'ASC';
                                                                                    ?>" class="dt-header">First Name
                                                    <span class="<?php
                                                                    if (isset($_GET['ord_n']) && !empty($_GET['ord_n'])) {
                                                                        if ($_GET['ord_n'] == 'ASC') echo 'fa fa-arrow-up';
                                                                        else echo 'fa fa-arrow-down';
                                                                    } else  echo '';
                                                                    ?>"></span>
                                                </th>
                                                <th scope="col" id="ord_l" data-id="<?php
                                                                                    if (isset($_GET['ord_l']) && !empty($_GET['ord_l'])) {
                                                                                        if ($_GET['ord_l'] == 'ASC') echo 'DESC';
                                                                                        else echo 'ASC';
                                                                                    } else  echo 'ASC';
                                                                                    ?>" class="dt-header">Last Name
                                                    <span class="<?php
                                                                    if (isset($_GET['ord_l']) && !empty($_GET['ord_l'])) {
                                                                        if ($_GET['ord_l'] == 'ASC') echo 'fa fa-arrow-up';
                                                                        else echo 'fa fa-arrow-down';
                                                                    } else  echo '';
                                                                    ?>"></span>
                                                </th>
                                                <th scope="col" id="ord_e" data-id="<?php
                                                                                    if (isset($_GET['ord_e']) && !empty($_GET['ord_e'])) {
                                                                                        if ($_GET['ord_e'] == 'ASC') echo 'DESC';
                                                                                        else echo 'ASC';
                                                                                    } else  echo 'ASC';
                                                                                    ?>" class="dt-header">Email
                                                    <span class="<?php
                                                                    $new_load = !isset($_GET['ord_e']) &&
                                                                        !isset($_GET['ord_c']) &&
                                                                        !isset($_GET['ord_l']) &&
                                                                        !isset($_GET['ord_n']);

                                                                    if (!$new_load) {
                                                                        if (isset($_GET['ord_e']) && !empty($_GET['ord_e'])) {
                                                                            if ($_GET['ord_e'] == 'ASC') echo 'fa fa-arrow-up';
                                                                            else echo 'fa fa-arrow-down';
                                                                        } else {
                                                                            echo '';
                                                                        }
                                                                    } else {
                                                                        echo "fa fa-arrow-down";
                                                                    }
                                                                    ?>
                                            "></span>
                                                </th>
                                                <th scope="col" id="ord_c" data-id="<?php
                                                                                    if (isset($_GET['ord_c']) && !empty($_GET['ord_c'])) {
                                                                                        if ($_GET['ord_c'] == 'ASC') echo 'DESC';
                                                                                        else echo 'ASC';
                                                                                    } else  echo 'ASC';
                                                                                    ?>" class="dt-header">Created at
                                                    <span class="<?php
                                                                    if (isset($_GET['ord_c']) && !empty($_GET['ord_c'])) {
                                                                        if ($_GET['ord_c'] == 'ASC') echo 'fa fa-arrow-up';
                                                                        else echo 'fa fa-arrow-down';
                                                                    } else  echo '';
                                                                    ?>"></span>
                                                </th>
                                                <input type="hidden" id="dt-header-form-data">
                                            </form>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?PHP
                                        //delete user
                                        if (isset($_GET['user']) && $_GET['user'] == 'del' && !empty($_GET['id'])) {
                                            $query = 'DELETE FROM `affiliate` WHERE `affiliate`.`id` = ?';
                                            $stmt = $dbh->prepare($query);
                                            $stmt->bindValue(1, $_GET['id'], PDO::PARAM_INT);
                                            $tmp = $stmt->execute();
                                        }
                                        ?>

                                        <?php
                                        $stmt2 = $dbh->prepare("SELECT * FROM `affiliate`");
                                        $tmp2 = $stmt2->execute();
                                        $total_rows = $stmt2->rowCount();

                                        $numrows = $total_rows;

                                        $rowsperpage = $config->__get('rows_per_page');

                                        $totalpages = ceil($numrows / $rowsperpage);

                                        if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
                                            $currentpage = (int)$_GET['currentpage'];
                                        } else {
                                            $currentpage = 1;
                                        }

                                        if ($currentpage > $totalpages) {
                                            $currentpage = $totalpages;
                                        }

                                        if ($currentpage < 1) {
                                            $currentpage = 1;
                                        }

                                        $offset = ($currentpage - 1) * $rowsperpage;


                                        $stmt = $dbh->prepare("SELECT * FROM `affiliate`" . $orderby . " LIMIT " . $offset . ',' . $rowsperpage);
                                        $tmp = $stmt->execute();
                                        if ($tmp && $stmt->rowCount() > 0) {
                                            $user_ = $stmt->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($user_ as $user_row) {
                                        ?>
                                                <tr>
                                                    <td><?= $user_row->first_name ?></td>
                                                    <td><?= $user_row->last_name ?></td>
                                                    <td><?= $user_row->email ?></td>
                                                    <td><?= $user_row->created_at ?></td>
                                                    <td class="text-right">
                                                        <a onclick="return confirm('Are you sure you want to delete user: <?= $user_row->email ?>?, this action can not be undone!!')" href="<?= SITE . 'apanel/affiliate?user=del&id=' . $user_row->id ?>" class="admin-action-btn"><i class="fa fa-trash"></i>Delete</a>
                                                    </td>
                                                </tr>
                                        <?php }
                                        } ?>
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
                                                } // end else
                                            } // end if
                                        } // end for
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
                                        }
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

    <script>
        $(".dt-header").on("click", function() {
            var index = $(this).attr("id");
            var order_dir = $(this).data("id");
            document.getElementById("dt-header-form-data").setAttribute("name", index)
            document.getElementById("dt-header-form-data").setAttribute("value", order_dir)
            $("#dt-header-form").submit()
        });
    </script>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="footer-text">&copy; Copyright. 2015 -
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
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