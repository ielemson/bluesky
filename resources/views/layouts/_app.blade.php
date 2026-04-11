<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="Anil z" name="author">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Shopwise is Powerful features and You Can Use The Perfect Build this Template For Any eCommerce Website. The template is built for sell Fashion Products, Shoes, Bags, Cosmetics, Clothes, Sunglasses, Furniture, Kids Products, Electronics, Stationery Products and Sporting Goods.">
    <meta name="keywords"
        content="ecommerce, electronics store, Fashion store, furniture store,  bootstrap 4, clean, minimal, modern, online store, responsive, retail, shopping, ecommerce store">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- SITE TITLE -->
    <title>Bluesky Mart :: @yield('title', config('app.name'))</title>
    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Animation CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">

    <!-- Latest Bootstrap min CSS -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">
    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/simple-line-icons.css') }}">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('assets/owlcarousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/owlcarousel/css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/owlcarousel/css/owl.theme.default.min.css') }}">

    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">

    <!-- Slick CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slick-theme.css') }}">

    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @stack('styles')
    <style>
        .logo_light,
        .logo_dark {
            height: 60px;
            /* Match your navbar height */
            width: auto;
            max-width: 180px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    {{-- {{ __('messages.welcome') }} --}}
    <!-- LOADER -->
    {{-- <div class="preloader">
        <div class="lds-ellipsis">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div> --}}
    <!-- END LOADER -->
    <!-- End Screen Load Popup Section -->

    @yield('home_')
    <!-- END MAIN CONTENT -->
    <div class="main_content">

        @yield('content')

    </div>
    <!-- END MAIN CONTENT -->

    <!-- START FOOTER -->
    @include('partials.footer')
    <!-- END FOOTER -->

    <a href="#" class="scrollup" style="display: none;"><i class="ion-ios-arrow-up"></i></a>

    <!-- Latest jQuery -->
    {{-- <script src="{{ asset('assets/js/jquery-3.7.0.min.js') }}"></script>  --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- popper min js -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>

    <!-- Latest compiled and minified Bootstrap -->
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- owl-carousel min js  -->
    <script src="{{ asset('assets/owlcarousel/js/owl.carousel.min.js') }}"></script>

    <!-- magnific-popup min js  -->
    <script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script>

    <!-- waypoints min js  -->
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>

    <!-- parallax js  -->
    <script src="{{ asset('assets/js/parallax.js') }}"></script>

    <!-- countdown js  -->
    <script src="{{ asset('assets/js/jquery.countdown.min.js') }}"></script>

    <!-- imagesloaded js -->
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>

    <!-- isotope min js -->
    <script src="{{ asset('assets/js/isotope.min.js') }}"></script>

    <!-- jquery.dd.min js -->
    <script src="{{ asset('assets/js/jquery.dd.min.js') }}"></script>

    <!-- slick js -->
    <script src="{{ asset('assets/js/slick.min.js') }}"></script>

    <!-- elevatezoom js -->
    <script src="{{ asset('assets/js/jquery.elevatezoom.js') }}"></script>

    <!-- scripts js -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>

    <!-- Linearicons CDN -->
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

    <!-- Toastify (if already included elsewhere, skip this) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @stack('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.body.addEventListener("click", async function(e) {
                const link = e.target.closest(".add-to-cart-link");
                if (!link) return;

                e.preventDefault();

                const payload = {
                    _token: "{{ csrf_token() }}",
                    vendor_product_id: link.dataset.vendorProductId,
                    product_id: link.dataset.productId,
                    vendor_id: link.dataset.vendorId,
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
                    // Update cart count
                    document.querySelector('.cart_count').innerText = data.cart_count;

                    // Update cart total
                    document.querySelector('.amount').innerHTML =
                        `<span class="currency_symbol">$</span>${data.cart_total}`;

                    // Replace dropdown cart HTML
                    document.querySelector('.cart_box').innerHTML = data.cart_dropdown;

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

        });


        document.addEventListener("DOMContentLoaded", async function() {
            try {
                const res = await fetch("/cart/summary");
                const data = await res.json();

                // Set cart count
                document.querySelector('.cart_count').textContent = data.cart_count;

                // Set cart total
                document.querySelector('.amount').innerHTML =
                    `<span class="currency_symbol">$</span>${data.cart_total}`;

            } catch (e) {
                console.warn("Cart load failed", e);
            }
        });

        document.addEventListener("DOMContentLoaded", async function() {
            const res = await fetch("{{ route('cart.dropdown') }}");
            const data = await res.json();

            // Apply cart values
            document.querySelector('.cart_count').innerText = data.cart_count;

            document.querySelector('.amount').innerHTML =
                `<span class="currency_symbol">$</span>${data.cart_total}`;

            document.querySelector('.cart_box').innerHTML = data.cart_dropdown;
        });

        document.body.addEventListener('click', async function(e) {
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

                // Update cart UI
                document.querySelector('.cart_count').textContent = data.cart_count;
                document.querySelector('.cart_trigger .amount').innerHTML =
                    `<span class="currency_symbol">$</span>${data.cart_total}`;
                const dropdown = document.querySelector('.cart_box');
                if (dropdown) dropdown.innerHTML = data.cart_dropdown;

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
    </script>

</body>

</html>
