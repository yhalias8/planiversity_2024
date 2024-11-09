<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend.includes.backend-head')
</head>

<body>
    @include('backend.includes.backend-script')

    <header id="topnav">
        @include('backend.includes.backend-topbar')
        @include('backend.includes.backend-navbar')
    </header>

    <div class="wrapper mb-5">
        @yield('content')
    </div>

    @include('backend.includes.backend-footer')

    @yield('scripts')

</body>

</html>