<div class="header-top header-top-ptb-1 d-md-block d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-3 col-lg-4 d-none">
                <div class="header-info">
                    <ul>
                       
                        <li>
                            <i class="fi-rs-marker"></i>
                            <a href="{{ route('home') }}">{{ gtrans('Shop online without stress') }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-xl-6 col-lg-4 col-md-4">
                <div class="text-center">
                    <div id="news-flash" class="d-inline-block">
                        <ul>
                            <li>{{ gtrans('Shop online without stress') }}</li>
                            <li>{{ gtrans('Discover quality products at great prices') }}</li>
                            <li>{{ gtrans('Fast shopping experience with secure checkout') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4">
                <div class="header-info header-info-right">
                    <ul>
                        <li>
                        <li class="position-relative language-area">
                            <a href="#" class="language-trigger">
                                <i class="fi-rs-world"></i>
                                {{ app()->getLocale() == 'en'
                                    ? 'English'
                                    : (app()->getLocale() == 'fr'
                                        ? 'Français'
                                        : (app()->getLocale() == 'es'
                                            ? 'Español'
                                            : 'English')) }}
                                <i class="fi-rs-angle-small-down"></i>
                            </a>

                            <ul class="language-dropdown">
                                <li>
                                    <form action="{{ route('lang.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="en">
                                        <button type="submit">English</button>
                                    </form>
                                </li>

                                <li>
                                    <form action="{{ route('lang.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="fr">
                                        <button type="submit">Français</button>
                                    </form>
                                </li>

                                <li>
                                    <form action="{{ route('lang.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="locale" value="es">
                                        <button type="submit">Español</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        </li>

                        @auth
                            <li>
                                @if (auth()->user()->hasRole('admin'))
                                    <i class="fi-rs-user"></i>
                                    <a href="{{ route('admin.dashboard') }}">{{ gtrans('Dashboard') }}</a>
                                @else
                                    <i class="fi-rs-user"></i>
                                    <a href="{{ route('customer.dashboard') }}">{{ gtrans('Dashboard') }}</a>
                                @endif
                            </li>
                        @else
                            <li>
                                <i class="fi-rs-user"></i>
                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    {{ gtrans('Log In / Sign Up') }}
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
