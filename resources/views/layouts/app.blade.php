<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Bluesky Mart">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>@yield('title', 'Bluesky Mart')</title>
    <!-- SEO Meta -->
    <meta name="description" content="@yield('meta_description', 'Shop quality products at Bluesky Mart – electronics, fashion, home essentials and more.')">
    <meta name="keywords" content="@yield('meta_keywords', 'ecommerce, online store, shopping, fashion, electronics, nigeria')">
    <!-- Open Graph (Social Sharing) -->
    <meta property="og:title" content="@yield('og_title', 'Bluesky Mart')">
    <meta property="og:description" content="@yield('og_description', 'Shop quality products at Bluesky Mart.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('assets/imgs/favicon.png'))">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/imgs/favicon.png') }}">

          <!-- Latest Bootstrap min CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
  
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const setCartCount = (count) => {
        document.querySelectorAll('.cart_count').forEach(el => {
            el.textContent = count;
        });
    };

    const setCartTotal = (total) => {
        document.querySelectorAll('.amount').forEach(el => {
            el.innerHTML = `<span class="currency_symbol">$</span>${total}`;
        });
    };

    const setCartDropdown = (html) => {
        document.querySelectorAll('.cart_box').forEach(el => {
            el.innerHTML = html;
        });
    };

    document.body.addEventListener("click", async function (e) {
        const link = e.target.closest(".add-to-cart-link");
        if (!link) return;

        e.preventDefault();

        const payload = {
            _token: "{{ csrf_token() }}",
            product_id: link.dataset.productId,
            name: link.dataset.name,
            price: link.dataset.price,
            slug: link.dataset.slug,
            image: link.dataset.image,
            quantity: 1
        };

        try {
            const response = await fetch("{{ route('cart.add.ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            Toastify({
                text: data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                }
            }).showToast();

            setCartCount(data.cart_count);
            setCartTotal(data.cart_total);
            setCartDropdown(data.cart_dropdown);

        } catch (error) {
            Toastify({
                text: "Something went wrong.",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #ff5f6d, #ffc371)"
                }
            }).showToast();
        }
    });

    document.body.addEventListener('click', async function (e) {
        const removeBtn = e.target.closest('.item_remove');
        if (!removeBtn) return;

        e.preventDefault();
        const cartId = removeBtn.dataset.cartId;

        try {
            const response = await fetch("{{ route('cart.remove.ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    cart_id: cartId
                })
            });

            const data = await response.json();

            Toastify({
                text: data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #ff5f6d, #ffc371)"
                }
            }).showToast();

            setCartCount(data.cart_count);
            setCartTotal(data.cart_total);
            setCartDropdown(data.cart_dropdown);

        } catch (error) {
            console.error(error);
            Toastify({
                text: "Something went wrong.",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                style: {
                    background: "linear-gradient(to right, #ff5f6d, #ffc371)"
                }
            }).showToast();
        }
    });

    (async function loadCartState() {
        try {
            const res = await fetch("{{ route('cart.dropdown') }}");
            const data = await res.json();

            setCartCount(data.cart_count);
            setCartTotal(data.cart_total);
            setCartDropdown(data.cart_dropdown);
        } catch (e) {
            console.warn("Cart load failed", e);
        }
    })();
});
</script>


</body>

</html>
