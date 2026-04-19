<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Basic --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Bluesky Mart">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $defaultTitle = 'Bluesky Mart';
        $defaultDescription = 'Shop quality products at Bluesky Mart – electronics, fashion, home essentials and more.';
        $defaultKeywords = 'ecommerce, online store, shopping, fashion, electronics, nigeria';
        $defaultImage = asset('assets/imgs/favicon.png');
        $currentUrl = url()->current();
    @endphp

    {{-- Title --}}
    <title>@yield('title', $defaultTitle)</title>

    {{-- SEO --}}
    <meta name="description" content="@yield('meta_description', $defaultDescription)">
    <meta name="keywords" content="@yield('meta_keywords', $defaultKeywords)">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $currentUrl }}">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="Bluesky Mart">
    <meta property="og:title" content="@yield('og_title', View::yieldContent('title', $defaultTitle))">
    <meta property="og:description" content="@yield('og_description', View::yieldContent('meta_description', $defaultDescription))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:image" content="@yield('og_image', $defaultImage)">
    <meta property="og:image:secure_url" content="@yield('og_image', $defaultImage)">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="512">
    <meta property="og:image:height" content="512">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', View::yieldContent('og_title', View::yieldContent('title', $defaultTitle)))">
    <meta name="twitter:description" content="@yield('twitter_description', View::yieldContent('og_description', View::yieldContent('meta_description', $defaultDescription)))">
    <meta name="twitter:image" content="@yield('twitter_image', View::yieldContent('og_image', $defaultImage))">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/imgs/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/imgs/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/imgs/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/imgs/favicon.png') }}">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
        @include('partials.floating-css')
</head>
    <!-- Optional Page Styles -->
    @stack('styles')
</head>
<body>

    @yield('_header')

    <main class="main">
        @yield('home_')
        @include('partials.footer')
        <!-- Preloader Start -->
        <div id="preloader-active">
            <div class="preloader d-flex align-items-center justify-content-center">
                <div class="preloader-inner position-relative">
                    <div class="text-center">
                        <h5 class="mb-5">Now Loading</h5>
                        <div class="loader">
                            <div class="bar bar1"></div>
                            <div class="bar bar2"></div>
                            <div class="bar bar3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         @include('components.login-form')
         @include('components.register-form')
         @include('partials.floating-assistant')
     
    </main>

    <!-- Vendor JS-->
    <script src="{{ asset('assets/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/js/plugins/slick.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.syotimer.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/wow.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/magnific-popup.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/waypoints.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/counterup.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/images-loaded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/isotope.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/scrollup.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.vticker-min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.theia.sticky.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.elevatezoom.js') }}"></script>
    <!-- Template JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/shop.js') }}"></script>

    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v8c78df7c7c0f484497ecbca7046644da1771523124516"
        integrity="sha512-8DS7rgIrAmghBFwoOTujcf6D9rXvH8xm8JQ1Ja01h9QX8EzXldiszufYa4IFfKdLUKTTrnSFXLDkUEOTrZQ8Qg=="
        data-cf-beacon='{"version":"2024.11.0","token":"3aa9a3481f734e94bceb8bb1bd648ba1","r":1,"server_timing":{"name":{"cfCacheStatus":true,"cfEdge":true,"cfExtPri":true,"cfL4":true,"cfOrigin":true,"cfSpeedBrain":true},"location_startswith":null}}'
        crossorigin="anonymous"></script>

         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>

    <!-- Linearicons CDN -->
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

    <!-- Toastify (if already included elsewhere, skip this) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @stack('scripts')

    @include('partials.floating-js')

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const setCartCount = (count) => {
        document.querySelectorAll('.cart_count').forEach(el => {
            el.textContent = count;
        });
    };

    const setCartDropdown = (html) => {
        document.querySelectorAll('.cart_box').forEach(el => {
            el.innerHTML = html;
        });
    };

    const showToast = (message, success = true) => {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            style: {
                background: success
                    ? "linear-gradient(to right, #00b09b, #96c93d)"
                    : "linear-gradient(to right, #ff5f6d, #ffc371)"
            }
        }).showToast();
    };

    document.body.addEventListener("click", async function (e) {
        const link = e.target.closest(".add-to-cart-link");
        if (!link || link.classList.contains('disabled')) return;

        e.preventDefault();

        try {
            const response = await fetch("{{ route('cart.add.ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    product_id: link.dataset.productId,
                    quantity: 1
                })
            });

            const data = await response.json();

            if (!response.ok || !data.status) {
                showToast(data.message || "Unable to add to cart.", false);
                console.error(data);
                return;
            }

            showToast(data.message || "Product added to cart.");
            setCartCount(data.cart_count);
            setCartDropdown(data.cart_dropdown);

        } catch (error) {
            console.error(error);
            showToast("Something went wrong.", false);
        }
    });

    document.body.addEventListener("click", async function (e) {
        const removeBtn = e.target.closest(".item_remove");
        if (!removeBtn) return;

        e.preventDefault();

        try {
            const response = await fetch("{{ route('cart.remove.ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    cart_id: removeBtn.dataset.cartId
                })
            });

            const data = await response.json();

            if (!response.ok || !data.status) {
                showToast(data.message || "Unable to remove item.", false);
                return;
            }

            showToast(data.message || "Item removed.");
            setCartCount(data.cart_count);
            setCartDropdown(data.cart_dropdown);

        } catch (error) {
            console.error(error);
            showToast("Something went wrong.", false);
        }
    });

    (async function loadCartState() {
        try {
            const response = await fetch("{{ route('cart.dropdown') }}");
            const data = await response.json();

            if (data.status) {
                setCartCount(data.cart_count);
                setCartDropdown(data.cart_dropdown);
            }
        } catch (e) {
            console.warn("Cart load failed", e);
        }
    })();
});
</script>

</body>

</html>
