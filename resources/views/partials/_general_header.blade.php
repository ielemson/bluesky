<header class="header-area header-style-5">
    <!-- Top bar -->
    <div class="header-top header-top-ptb-1 d-md-block d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="header-info">
                        {{-- <ul>
                            <li><i class="fi-rs-smartphone"></i> <a href="javascript:;">(+234) 000 000 0000</a></li>
                            <li><i class="fi-rs-marker"></i> <a href="javascript:;">Our location</a></li>
                        </ul> --}}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="header-info header-info-right">
                        <ul>
                            <li><i class="fi-rs-user"></i> <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#loginModal"> {{ gtrans('Log In / Sign Up') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main header -->
    <div class="header-bottom sticky-bar sticky-white-bg">
        <div class="container">
            <div class="header-wrap header-space-between position-relative">
                
                <!-- Logo -->
                <div class="logo logo-width-1">
                    <a href="{{ route("home") }}">
                        <img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo">
                    </a>
                </div>

                <!-- Navigation -->
                <div class="main-menu main-menu-padding-1 main-menu-lh-1 d-none d-lg-block">
                    <nav>
                        <ul>
                            <li><a class="active" href="{{ route("home") }}">{{ gtrans('Home') }}</a></li>
                            <li><a href="{{ route('page.products.shop') }}">{{ gtrans('Shop') }}</a></li>
                        </ul>
                    </nav>
                </div>

                  <div class="header-action-right">
                    <div class="header-action-2">
                        <div class="header-action-icon-2">
                            <a class="mini-cart-icon" href="{{ route('cart.view') }}">
                                <img alt="cart" src="{{ asset('assets/imgs/theme/icons/icon-cart.svg') }}">
                                <span
                                    class="pro-count blue cart_count">{{ \Cart::getContent()->sum('quantity') }}</span>
                            </a>
                            <div class="cart-dropdown-wrap cart-dropdown-hm2 cart_box">
                                @include('components.cart-dropdown', [
                                    'cartItems' => \Cart::getContent(),
                                    'cartTotal' => \Cart::getTotal(),
                                ])
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</header>