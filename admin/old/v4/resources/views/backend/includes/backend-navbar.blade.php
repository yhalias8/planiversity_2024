<div class="navbar-custom header-nav-bar-style active">
    <div class="container-fluid">
        <div id="navigation" class="active">
            <ul class="navigation-menu header-top-bar-style">
                <li class="{{ (request()->segment(1) == 'home') ? 'active-link active' : '' }}">
                    <a href="{{ url('home') }}">Home</a>
                </li>
                <li class="{{ (request()->segment(1) == 'users') ? 'active-link active' : '' }}">
                    <a href="{{ url('users') }}">Users</a>
                </li>
                <li class="{{ (request()->segment(1) == 'settings') ? 'active-link active' : '' }}">
                    <a href="{{ url('settings') }}">Settings</a>
                </li>
                <li class="{{ (request()->segment(1) == 'transactions') ? 'active-link active' : '' }}">
                    <a href="{{ url('transactions') }}">Transactions</a>
                </li>
                <li class="{{ (request()->segment(1) == 'coupon') ? 'active-link active' : '' }}">
                    <a href="{{ url('coupon') }}">Coupon</a>
                </li>

                <li class="{{ (request()->segment(1) == 'marketplace-category') ? 'active-link active' : '' }}">
                    <a href="{{ url('marketplace-category') }}">Category</a>
                </li>

                <li class="{{ (request()->segment(1) == 'marketplace-service') ? 'active-link active' : '' }}">
                    <a href="{{ url('marketplace-service') }}">Service</a>
                </li>


                <li class="{{ (request()->segment(1) == 'marketplace-order') ? 'active-link active' : '' }}">
                    <a href="{{ url('marketplace-order') }}">Order</a>
                </li>

                <!-- <li class="dropdown {{ (request()->segment(1) == 'marketplace') ? 'active-link active' : '' }}" id="navbarDropdownMenuLink">
                    <a>Marketplace</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>

                    </ul>
                </li> -->

                <li class="pull-right">
                    @yield('new-action-process')
                </li>
            </ul>
        </div>
    </div>
</div>