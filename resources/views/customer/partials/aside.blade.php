@php
    if (!function_exists('nav_active')) {
        function nav_active($names)
        {
            return request()->routeIs($names) ? 'is-active' : '';
        }
    }

    $authUser = auth()->user();
    $isVendor = (bool) ($authUser->is_vendor ?? false);

    $orderRoute = $isVendor ? 'vendor.orders.index' : 'customer.orders.index';
    $orderActive = $isVendor ? 'vendor.orders.*' : 'customer.orders.*';
@endphp

<aside class="main-sidebar">
    <section class="sidebar">

        <ul class="sidebar-menu" data-widget="tree">

            {{-- User profile block --}}
            <li class="user-profile treeview {{ nav_active(['customer.dashboard', 'vendor.balance', 'customer.wallets.*', 'vendor.delivery.*', 'vendor.orders.*', 'customer.orders.*', 'customer.messages.*']) }}">
                <a href="{{ route('customer.dashboard') }}">
                    <img src="{{ asset('assets/imgs/user.png') }}" alt="user">
                    <span>
                        <span class="d-block font-weight-600 font-size-16">
                            {{ $authUser->nickname ?: $authUser->name ?: 'User' }}
                        </span>
                        <span class="email-id">{{ $authUser->contact }}</span>
                    </span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <i class="fa fa-user mr-5"></i>My Profile
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('vendor.balance') }}" class="{{ nav_active('vendor.balance') }}">
                            <i class="fa fa-money mr-5"></i>My Balance
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('customer.messages.index') }}" class="{{ nav_active('customer.messages.*') }}">
                            <i class="fa fa-bell mr-5"></i>Notifications
                        </a>
                    </li>

                    <li>
                        <a href="javascript:void(0)">
                            <i class="fa fa-cog mr-5"></i>Account Setting
                        </a>
                    </li>

                    <li>
                        <a href="javascript:void(0)">
                            <i class="fa fa-power-off mr-5"></i>Logout
                        </a>
                    </li>
                </ul>
            </li>

            <li class="header nav-small-cap">
                <i class="mdi mdi-drag-horizontal mr-5"></i>PERSONAL
            </li>

            {{-- Dashboard --}}
            <li class="{{ nav_active('customer.dashboard') }}">
                <a href="{{ route('customer.dashboard') }}">
                    <i class="mdi mdi-home-outline"></i>
                    <span>My Account</span>
                </a>
            </li>

            {{-- Vendor section --}}
            @if ($isVendor)
                <li class="{{ nav_active('customer.products.*') }}">
                    <a href="{{ route('customer.products.index') }}">
                        <i class="mdi mdi-storefront-outline"></i>
                        <span>Wholesale Management</span>
                    </a>
                </li>

                <li class="{{ nav_active('vendor.shop.*') }}">
                    <a href="{{ route('vendor.shop.index') }}">
                        <i class="mdi mdi-store-settings-outline"></i>
                        <span>Shop Details</span>
                    </a>
                </li>

                <li class="{{ nav_active('vendor.listings.*') }}">
                    <a href="{{ route('vendor.listings.my') }}">
                        <i class="mdi mdi-package-variant-closed"></i>
                        <span>Product Management</span>
                    </a>
                </li>
            @else
                <li class="{{ nav_active('vendor.apply_form') }}">
                    <a href="{{ route('vendor.apply_form') }}">
                        <i class="mdi mdi-store-plus-outline"></i>
                        <span>Apply for a Store</span>
                    </a>
                </li>
            @endif

            {{-- Current Balance --}}
            <li class="{{ nav_active('vendor.balance') }}">
                <a href="{{ route('vendor.balance') }}">
                    <i class="mdi mdi-wallet-outline"></i>
                    <span>Current Balance</span>
                </a>
            </li>

            {{-- Orders --}}
            <li class="{{ nav_active($orderActive) }}">
                <a href="{{ route($orderRoute) }}">
                    <i class="mdi mdi-cart-outline"></i>
                    <span>{{ $isVendor ? 'Store Orders' : 'My Orders' }}</span>
                </a>
            </li>

            {{-- Billing Records --}}
            <li class="{{ nav_active('customer.billing.*') }}">
                <a href="{{ route('customer.billing.index') }}">
                    <i class="mdi mdi-receipt-text-outline"></i>
                    <span>Billing Records</span>
                </a>
            </li>

            {{-- Recharge Records --}}
            <li class="{{ nav_active('customer.recharge.*') }}">
                <a href="{{ route('customer.recharges.index') }}">
                    <i class="mdi mdi-cash-plus"></i>
                    <span>Recharge Records</span>
                </a>
            </li>

            {{-- Withdrawal Records --}}
            <li class="{{ nav_active('customer.withdrawals.*') }}">
                <a href="{{ route('customer.withdrawals.index') }}">
                    <i class="mdi mdi-cash-minus"></i>
                    <span>Withdrawal Records</span>
                </a>
            </li>

            {{-- Wallet Management --}}
            <li class="{{ nav_active('customer.wallets.*') }}">
                <a href="{{ route('customer.wallets.index') }}">
                    <i class="mdi mdi-wallet-membership"></i>
                    <span>Wallet Management</span>
                </a>
            </li>

            {{-- Delivery Address Management --}}
            <li class="{{ nav_active('vendor.delivery.*') }}">
                <a href="{{ route('vendor.delivery.index') }}">
                    <i class="mdi mdi-map-marker-outline"></i>
                    <span>Delivery Address Management</span>
                </a>
            </li>

            {{-- Internal Message --}}
            <li class="{{ nav_active('customer.messages.*') }}">
                <a href="{{ route('customer.messages.index') }}">
                    <i class="mdi mdi-bell-outline"></i>
                    <span>Notifications</span>
                </a>
            </li>

        </ul>
    </section>
</aside>