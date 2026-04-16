@php
    if (!function_exists('nav_active')) {
        function nav_active($names)
        {
            return request()->routeIs($names) ? 'is-active' : '';
        }
    }
@endphp

<aside class="main-sidebar">
    <section class="sidebar">

        <ul class="sidebar-menu" data-widget="tree">

            {{-- User profile block --}}
            <li
                class="user-profile treeview {{ nav_active(['customer.dashboard', 'vendor.balance', 'customer.wallets.index', 'vendor.delivery.index']) }}">
                <a href="{{ route('customer.dashboard') }}">
                    <img src="{{ asset('assets/imgs/user.png') }}" alt="user">
                    <span>
                        <span class="d-block font-weight-600 font-size-16">{{ Auth()->user()->nickname }}</span>
                        <span class="email-id">{{ Auth()->user()->contact }}</span>
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
                        <a href="javascript:void(0)">
                            <i class="fa fa-envelope-open mr-5"></i>Inbox
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
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>My Account</span>
                </a>
            </li>
              {{-- Vendor section --}}
            @if (Auth()->user()->is_vendor)
                <li class="{{ nav_active('customer.products.*') }}">
                    <a href="{{ route('customer.products.index') }}">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>Wholesale Management</span>
                    </a>
                </li>

                <li class="{{ nav_active('vendor.shop.*') }}">
                    <a href="{{ route("vendor.shop.index") }}">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>Shop Details</span>
                    </a>
                </li>

                <li class="{{ nav_active('vendor.listings.*') }}">
                    <a href="{{ route('vendor.listings.my') }}">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>Product Management</span>
                    </a>
                </li>
            @else
                <li class="{{ nav_active('vendor.apply_form') }}">
                    <a href="{{ route('vendor.apply_form') }}">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>Apply for a store</span>
                    </a>
                </li>
            @endif

            {{-- Current Balance --}}
            <li class="{{ nav_active('vendor.balance') }}">
                <a href="{{ route('vendor.balance') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>Current Balance</span>
                </a>
            </li>

            {{-- My Orders (FIXED) --}}
            <li class="{{ nav_active('vendor.orders.*') }}">
                <a href="{{ route('vendor.orders.index') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>My Orders</span>
                </a>
            </li>

            {{-- Billing Records --}}
            <li class="{{ nav_active('customer.billing.*') }}">
                <a href="{{ route('customer.dashboard') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>Billing Records</span>
                </a>
            </li>

            {{-- Recharge Records --}}
            <li class="{{ nav_active('customer.recharge.*') }}">
                <a href="{{ route('customer.dashboard') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>Recharge Records</span>
                </a>
            </li>

            {{-- Withdrawal Records --}}
            <li class="{{ nav_active('customer.withdrawals.*') }}">
                <a href="{{ route('customer.dashboard') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>Withdrawal Records</span>
                </a>
            </li>

            {{-- Wallet Management --}}
            <li class="{{ nav_active('customer.wallets.*') }}">
                <a href="{{ route('customer.wallets.index') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>Wallet Management</span>
                </a>
            </li>

            {{-- Delivery Address Management --}}
            <li class="{{ nav_active('vendor.delivery.*') }}">
                <a href="{{ route('vendor.delivery.index') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>Delivery Address Management</span>
                </a>
            </li>

            {{-- Internal Message --}}
            <li class="{{ nav_active('customer.messages.*') }}">
                <a href="{{ route('customer.messages.index') }}">
                    <i class="mdi mdi-view-dashboard"></i>
                    <span>Internal Message</span>
                </a>
            </li>

        </ul>
    </section>
</aside>