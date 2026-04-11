<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 97%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header fs-10 m-0 text-uppercase">Dashboard</li>
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i data-feather="headphones"></i>
                            <span>Vendor</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.vendors.pending') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Pending</a></li>
                            <li><a href="{{ route('admin.vendors.active') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Active</a></li>
                            <li><a href="{{ route('admin.vendors.suspended') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Suspended</a></li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i data-feather="grid"></i>
                            <span>User Managment</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.users.create') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Create</a></li>
                            <li><a href="{{ route('admin.users.index') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>View</a></li>

                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i data-feather="grid"></i>
                            <span>Category Managment</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.categories.index') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>View</a></li>
                            <li><a href="{{ route('admin.categories.create') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Create</a></li>

                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i data-feather="grid"></i>
                            <span>Product Managment</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.products.index') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>View</a></li>
                            <li><a href="{{ route('admin.products.create') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Create</a></li>

                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i data-feather="grid"></i>
                            <span>Wallet Management</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('admin.wallets.create') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Create Payment</a>
                            </li>
                            <li><a href="{{ route('admin.wallet.index') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Deposits</a></li>

                            <li><a href="{{ route('admin.wallet-options.index') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Payout Option</a>
                            </li>

                        </ul>
                    </li>

                     <li class="treeview">
                        <a href="#">
                            <i data-feather="grid"></i>
                            <span>Order Managment</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route("admin.orders.index") }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>View</a></li>
                            <li><a href="{{ route('admin.orders.create') }}"><i class="icon-Commit"><span
                                            class="path1"></span><span class="path2"></span></i>Create</a></li>

                        </ul>
                    </li>

                    <li class="header  fs-10 m-0">Site Management</li>
                    <li class="treeview">
                        <a href="#">
                            <i data-feather="lock"></i>
                            <span>Website</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="{{ route('admin.settings.index') }}" class="d-light"><i
                                        class="icon-Commit"><span class="path1"></span><span
                                            class="path2"></span></i>Settings</a>

                            </li>

                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </section>
</aside>
