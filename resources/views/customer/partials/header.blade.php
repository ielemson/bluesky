<header class="main-header">
    <!-- Logo -->
    <a href="index.html" class="logo">
        <!-- mini logo -->
        <div class="logo-mini">
            <span class="light-logo"><img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo"></span>
            <span class="dark-logo"><img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo"></span>
        </div>
        <!-- logo-->
        <div class="logo-lg">
            <span class="light-logo"><img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo"></span>
            <span class="dark-logo"><img src="{{ asset('assets/imgs/logo.jpg') }}" alt="logo"></span>
        </div>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </div>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="search-box">
                    <a class="nav-link hidden-sm-down" href="javascript:void(0)"><i class="mdi mdi-magnify"></i></a>
                    <form class="app-search" style="display: none;">
                        <input type="text" class="form-control" placeholder="Search &amp; enter"> <a
                            class="srh-btn"><i class="ti-close"></i></a>
                    </form>
                </li>
                <!-- User Account-->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('assets/imgs/user.png') }}" class="user-image rounded-circle"
                            alt="User Image">
                    </a>
                    <ul class="dropdown-menu animated flipInY">
                        <!-- User image -->
                        <li class="user-header bg-img"
                            style="background-image: url({{ asset('assets/imgs/user.png') }})" data-overlay="3">
                            <div class="flexbox align-self-center">
                                <img src="{{ asset('assets/imgs/user.png') }}" class="float-left rounded-circle"
                                    alt="User Image">
                                <h4 class="user-name align-self-center">
                                    <span>{{ Auth()->user()->nickname }}</span>
                                    <small>{{ Auth()->user()->email }}</small>
                                </h4>
                            </div>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <a class="dropdown-item" href="{{ route("customer.profile") }}"><i class="ion ion-person"></i> My
                                Profile</a>
                            <a class="dropdown-item" href="{{ route('vendor.balance') }}"><i class="ion ion-bag"></i> My
                                Balance</a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:void(0)"><i class="ion ion-settings"></i> Account
                                Setting</a>
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ion-log-out"></i> Logout
                            </a>


                        </li>
                    </ul>
                </li>

                <!-- Messages -->

            </ul>
        </div>
    </nav>
</header>
