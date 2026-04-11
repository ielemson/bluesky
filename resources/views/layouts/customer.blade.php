<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Blue SkyMart Customer Dashboard')">
    <meta name="author" content="Blue SkyMart">

    <title>@yield('title', 'Blue SkyMart - Dashboard')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/imgs/favicon.png') }}">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('customer/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('customer/vendor_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('customer/vendor_components/morris.js/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('customer/vendor_components/datatable/datatables.min.css') }}">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('customer/css/bootstrap-extend.css') }}">
    <link rel="stylesheet" href="{{ asset('customer/css/master_style.css') }}">
    <link rel="stylesheet" href="{{ asset('customer/css/skins/_all-skins.css') }}">

    @stack('styles')
</head>

<body class="hold-transition skin-info-light fixed sidebar-mini">
    <div class="wrapper">

        @include('customer.partials.header')
        @include('customer.partials.aside')

        <div class="content-wrapper">
            @hasSection('content_header')
                @yield('content_header')
            @endif

            <section class="content">
                @yield('content')
            </section>
        </div>

        <footer class="main-footer">
            <div class="pull-right d-none d-sm-inline-block">
                <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0)">FAQ</a>
                    </li>
                </ul>
            </div>

            &copy; {{ now()->year }} <strong>Blue SkyMart</strong>. All rights reserved.
        </footer>

        <div class="control-sidebar-bg"></div>

        @yield('modal_wrapper')
    </div>

    <!-- Vendor JS -->
    <script src="{{ asset('customer/vendor_components/jquery-3.3.1/jquery-3.3.1.js') }}"></script>
    <script src="{{ asset('customer/vendor_components/popper/dist/popper.min.js') }}"></script>
    <script src="{{ asset('customer/vendor_components/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="{{ asset('customer/vendor_components/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- Theme JS -->
    <script src="{{ asset('customer/js/template.js') }}"></script>

    @stack('scripts')
</body>

</html>