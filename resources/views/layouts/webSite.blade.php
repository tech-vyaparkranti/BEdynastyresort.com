<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.head')
</head>
<body class="{{Request::is('/') ? 'common-home' : 'information' }}">
    <div class="page-wrapper">
        <!-- Preloader -->
        <div class="preloader"><div class="custom-loader"></div></div>
        @include('include.navigation')

        @yield('content')

        <!-- footer -->
        @include('include.footer')
        <!-- end footer -->

    </div>
    @include('include.script')
    @yield('script')
</body>
</html>
