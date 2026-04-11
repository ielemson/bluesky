<header class="header_area">
    <div class="container">
        <div class="header_wrap d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="{{ url('/') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="BlueSkyMart"></a>
            </div>

            @include('components.language-switcher')

            <nav class="navbar">
                <ul class="nav_list d-flex gap-4">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li><a href="{{ route('user.profile') }}">Account</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
