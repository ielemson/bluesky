<header class="header_wrap fixed-top header_with_topbar">
    <div class="top-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <div class="lng_dropdown me-2">
                            <form id="lang-switcher" action="{{ route('lang.switch') }}" method="POST" class="d-inline">
                                @csrf

                                <select name="locale" class="custome_select" onchange="this.form.submit()">
                                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>
                                        🇬🇧 {{ gtrans('English') }}
                                    </option>

                                    <option value="zh" {{ app()->getLocale() == 'zh' ? 'selected' : '' }}>
                                        🇨🇳 {{ gtrans('中文 (Chinese)') }}
                                    </option>

                                    <option value="fr" {{ app()->getLocale() == 'fr' ? 'selected' : '' }}>
                                        🇫🇷 {{ gtrans('Français') }}
                                    </option>

                                    <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>
                                        🇪🇸 {{ gtrans('Español') }}
                                    </option>
                                </select>
                            </form>

                        </div>

                        <div class="me-3">
                            <select name="countries" class="custome_select">
                                <option value="USD" data-title="USD">USD</option>
                                <option value="EUR" data-title="EUR">EUR</option>
                                <option value="GBR" data-title="GBR">GBR</option>
                            </select>
                        </div>

                        <ul class="contact_detail text-center text-lg-start">
                            <li>
                                <i class="ti-mobile"></i>
                                <span>{{ gtrans('123-456-7890') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-center text-md-end">
                        <ul class="header_list">
                            <li>
                                <a href="javascript:;">
                                    <i class="ti-control-shuffle"></i>
                                    <span>{{ gtrans('Compare') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;">
                                    <i class="ti-heart"></i>
                                    <span>{{ gtrans('Wishlist') }}</span>
                                </a>
                            </li>

                            @auth
                                @if (auth()->user()->hasRole('admin'))
                                    <li>
                                        <a href="{{ route('admin.dashboard') }}">
                                            <i class="ti-user"></i>
                                            <span>&nbsp; {{ gtrans('Dashboard') }}</span>
                                        </a>
                                    </li>
                                @elseif(auth()->user()->hasAnyRole(['customer', 'vendor']))
                                    <li>
                                        <a href="{{ route('customer.dashboard') }}">
                                            <i class="ti-user"></i>
                                            <span>&nbsp; {{ gtrans('Dashboard') }}</span>
                                        </a>
                                    </li>
                                @endif
                            @else
                                <li>
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                        <i class="ti-user"></i>
                                        <span>{{ gtrans('Login') }}</span>
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom_header dark_skin main_menu_uppercase">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img class="logo_light" src="{{ asset('assets/images/logo.jpg') }}" alt="logo" />
                    <img class="logo_dark" src="{{ asset('assets/images/logo.jpg') }}" alt="logo" />
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-expanded="false">
                    <span class="ion-android-menu"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="dropdown">
                            <a class="nav-link" href="{{ route('home') }}">{{ gtrans('Home') }}</a>
                        </li>
                        <li>
                            <a class="nav-link nav_item" href="javascript:;">{{ gtrans('Products') }}</a>
                        </li>
                        <li>
                            <a class="nav-link nav_item" href="javascript:;">{{ gtrans('About Us') }}</a>
                        </li>
                        <li>
                            <a class="nav-link nav_item" href="javascript:;">{{ gtrans('Contact Us') }}</a>
                        </li>
                    </ul>
                </div>

                <ul class="navbar-nav attr-nav align-items-center">
                    <li>
                        <a href="javascript:;" class="nav-link search_trigger">
                            <i class="linearicons-magnifier"></i>
                        </a>
                        <div class="search_wrap">
                            <span class="close-search">
                                <i class="ion-ios-close-empty"></i>
                            </span>
                            <form>
                                <input type="text" placeholder="{{ gtrans('Search') }}" class="form-control"
                                    id="search_input">
                                <button type="submit" class="search_icon">
                                    <i class="ion-ios-search-strong"></i>
                                </button>
                            </form>
                        </div>
                        <div class="search_overlay"></div>
                    </li>

                    <li class="dropdown cart_dropdown">
                        <a class="nav-link cart_trigger" href="#" data-bs-toggle="dropdown">
                            <i class="linearicons-bag2"></i>
                            <span class="cart_count">0</span>
                            <span class="amount">
                                <span class="currency_symbol">$</span>0
                            </span>
                        </a>

                        <div class="cart_box cart_right dropdown-menu dropdown-menu-right">
                            @include('components.cart-dropdown', [
                                'cartItems' => \Cart::getContent(),
                                'cartTotal' => \Cart::getTotal(),
                            ])
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
