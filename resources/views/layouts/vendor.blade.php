<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Vendor Dashboard | BlueSkyMart')</title>
    <link rel="stylesheet" href="{{ asset('vendor/css/style.css') }}">
    @stack('styles')
</head>
<body>
    @include('vendor.partials.sidebar')

    <main class="p-4">
        @yield('content')
    </main>

    <script src="{{ asset('vendor/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
