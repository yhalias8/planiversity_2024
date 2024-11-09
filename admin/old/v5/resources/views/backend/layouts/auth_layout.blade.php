<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend.includes.auth_head')
</head>

@include('backend.includes.auth_script')

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <section id="wrapper">
        @yield('content')
    </section>

</body>

</html>