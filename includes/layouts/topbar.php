<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                            placeholder="Search for..." aria-label="Search"
                            aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1 d-flex align-items-center">
            <a class="nav-link" href="activities.php" id="alertsDropdown" role="button">
                <i class="bi bi-bell" style="font-size:1.5em; color: #0C246B;"></i>
                <span class="badge badge-danger badge-counter"><?=ActivityLogger::getNumberOfUnreadNotifications($userdata['id'])?></span>
            </a>
        </li>

        <!-- <div class="topbar-divider d-none d-sm-block"></div> -->

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" style="border:1px solid #b4ddfb " src="<?= $img ?>">
                <div class="ml-2">
                    <div class="d-flex">
                        <span class="d-lg-inline text-primary small topbar-text"><?= isset($userdata['name']) ? $userdata['name'] : "Guest Test"; ?></span>
                        <span class="badge-warning badge-user ml-1"><?= isset($userdata['account_type']) ? $userdata['account_type'] : "####"; ?> User</span>
                    </div>
                    <div>
                        <span class="customer-text">Customer#: <?= isset($userdata['customer_number']) ? $userdata['customer_number'] : "####"; ?></span>
                    </div>
                </div>
                <i class="bi bi-chevron-down ml-2" style="font-size:1em; color: #0C246B;"></i>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="contact-us">
                    Contact Us
                </a>
                <a class="dropdown-item" href="blog">
                    Blog
                </a>
                <a class="dropdown-item" href="leave">
                    Delete Account
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout">
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>