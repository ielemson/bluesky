<header class="header-area header-style-5">
    <!-- Top bar -->
    <div class="header-top header-top-ptb-1 d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="header-info">
                        <ul>
                            <li><i class="fi-rs-smartphone"></i> <a href="javascript:;">(+234) 000 000 0000</a></li>
                            <li><i class="fi-rs-marker"></i> <a href="javascript:;">Our location</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="header-info header-info-right">
                        <ul>
                            <li><i class="fi-rs-user"></i> <a href="javascript:;">Log In / Sign Up</a></li>
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
                            <li><a class="active" href="{{ route("home") }}">Home</a></li>
                            <li><a href="javascript:;">Shop</a></li>
                            <li><a href="javascript:;">About</a></li>
                            <li><a href="javascript:;">Contact</a></li>
                        </ul>
                    </nav>
                </div>

                <!-- Right actions -->
                <div class="header-action-right">
                        <div class="header-action-2">
                            <div class="header-action-icon-2">
                                <a href="#">
                                    <img class="svgInject" alt="wishlist" src="{{ asset('assets/imgs/theme/icons/icon-heart.svg') }}">
                                    <span class="pro-count blue">0</span>
                                </a>
                            </div>

                            <div class="header-action-icon-2">
                                <a class="mini-cart-icon" href="">
                                    <img alt="cart" src="{{ asset('assets/imgs/theme/icons/icon-cart.svg') }}">
                                    <span class="pro-count blue">{{ \Cart::getContent()->count() }}</span>
                                </a>

                                <div class="cart-dropdown-wrap cart-dropdown-hm2">
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
    </div>
</header>