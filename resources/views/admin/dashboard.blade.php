@extends("layouts.admin")
@section('title', 'Admin Dashboard')
@section("content")
    <div class="box box-body">
        <div class="row">
            <!-- Total Revenue Card -->
            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-12">
                <div class="box-body rounded-0 p-0 pb-lg-0 pb-sm-15 pb-xs-15 be-1 fill-icon">
                    <div class="d-flex align-items-center">
                        <div class="w-70 h-70 me-15 bg-primary-light rounded-circle text-center p-10">
                            <div class="w-50 h-50 bg-primary rounded-circle">
                                <i class="fa fa-dollar fs-24 l-h-50"></i>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-fade fs-12">Total Revenue</span>
                            <h2 class="text-dark hover-primary m-0 fw-600">${{ number_format($totalRevenue) }}</h2>
                            <small class="text-success">${{ number_format($todayRevenue) }} today</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Orders Card -->
            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-12">
                <div class="box-body rounded-0 p-0 pb-lg-0 pb-sm-15 pb-xs-15 be-1 fill-icon">
                    <div class="d-flex align-items-center">
                        <div class="w-70 h-70 me-15 bg-info-light rounded-circle text-center p-10">
                            <div class="w-50 h-50 bg-info rounded-circle">
                                <i class="fa fa-shopping-cart fs-20 l-h-50"></i>
                            </div>		
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-fade fs-12">Total Orders</span>
                            <h2 class="text-dark hover-primary m-0 fw-600">{{ number_format($totalOrders) }}</h2>
                            <small class="text-info">{{ $completedOrders }} completed</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Users Card -->
            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-12">
                <div class="box-body rounded-0 p-0 pb-lg-0 pb-xs-15 be-1 fill-icon">
                    <div class="d-flex align-items-center">
                        <div class="w-70 h-70 me-15 bg-danger-light rounded-circle text-center p-10">
                            <div class="w-50 h-50 bg-danger rounded-circle">
                                <i class="fa fa-users fs-20 l-h-50"></i>	
                            </div>		
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-fade fs-12">Total Users</span>
                            <h2 class="text-dark hover-primary m-0 fw-600">{{ number_format($totalUsers) }}</h2>
                            <small class="text-danger">{{ $totalVendors }} vendors</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Total Products Card -->
            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-12">
                <div class="box-body rounded-0 p-0 fill-icon">
                    <div class="d-flex align-items-center">
                        <div class="w-70 h-70 me-15 bg-warning-light rounded-circle text-center p-10">
                            <div class="w-50 h-50 bg-warning rounded-circle">
                                <i class="fa fa-cubes fs-24 l-h-50"></i>
                            </div>	
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-fade fs-12">Total Products</span>
                            <h2 class="text-dark hover-primary m-0 fw-600">{{ number_format($totalProducts) }}</h2>
                            <small class="text-warning">{{ $totalVendorListings }} listings</small>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>

    <div class="row">
        <div class="col-xxxl-8 col-xxl-8 col-xl-8 col-lg-7">
            <div class="card rounded-4">
                <div class="box-header d-flex b-0 justify-content-between align-items-center pb-0">
                    <h4 class="box-title">Revenue Overview</h4>
                    <ul class="m-0" style="list-style: none;">
                        <li class="dropdown">
                            <button class="waves-effect waves-light btn btn-outline btn-rounded btn-primary mb-5 dropdown-toggle btn-sm" data-bs-toggle="dropdown" href="#" aria-expanded="false">Monthly</button>
                            <div class="dropdown-menu dropdown-menu-end" style="will-change: transform;">
                                <a class="dropdown-item" href="#">Daily</a>
                                <a class="dropdown-item" href="#">Weekly</a>
                                <a class="dropdown-item" href="#">Yearly</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-body ps-5 pt-5">
                    <div class="chart">
                        <!-- Revenue chart placeholder - you can integrate with Chart.js or other libraries -->
                        <div class="text-center p-5 bg-light rounded">
                            <h5 class="text-muted">Revenue Chart</h5>
                            <p class="text-muted mb-0">Monthly Revenue: ${{ number_format($currentMonthRevenue) }}</p>
                            <p class="text-muted">Average Order Value: ${{ number_format($averageOrderValue, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-5">
            <div class="row">
                <!-- Pending Orders -->
                <div class="col-12">
                    <div class="box">
                        <div class="box-body fill-img">
                            <div class="d-flex align-items-center">
                                <div class="w-80 h-80 me-15 bg-danger-light rounded-3 text-center p-5">
                                    <div class="w-70 h-70 bg-light rounded-3 d-flex align-items-center justify-content-center">
                                        <i class="fa fa-clock-o fs-24 text-danger"></i>
                                    </div>	
                                </div>
                                <div class="d-flex flex-column">
                                    <h4 class="text-fade mb-1">Pending Orders</h4>
                                    <h2 class="text-danger hover-primary m-0 fw-600">{{ number_format($pendingOrders) }}</h2>
                                </div>
                            </div>
                            <div class="progress mb-2 progress-sm mt-20">
                                @php
                                    $pendingPercentage = $totalOrders > 0 ? ($pendingOrders / $totalOrders) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $pendingPercentage }}%" aria-valuenow="{{ $pendingPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Vendor Applications -->
                <div class="col-12">
                    <div class="box">
                        <div class="box-body fill-img">
                            <div class="d-flex align-items-center">
                                <div class="w-80 h-80 me-15 bg-success-light rounded-3 text-center p-5">
                                    <div class="w-70 h-70 bg-light rounded-3 d-flex align-items-center justify-content-center">
                                        <i class="fa fa-user-tie fs-24 text-success"></i>
                                    </div>	
                                </div>
                                <div class="d-flex flex-column">
                                    <h4 class="text-fade mb-1">Vendor Applications</h4>
                                    <h2 class="text-success hover-primary m-0 fw-600">{{ number_format($totalPendingVendors) }}</h2>
                                </div>
                            </div>
                            <div class="progress mb-2 progress-sm mt-20">
                                @php
                                    $vendorPercentage = $totalVendors > 0 ? ($totalPendingVendors / $totalVendors) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $vendorPercentage }}%" aria-valuenow="{{ $vendorPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Carousel -->
        <div class="col-12">
            <div class="box">
                <div class="box-header d-flex justify-content-between align-items-center">
                    <h4 class="box-title">Recent Products</h4>
                    <ul class="m-0" style="list-style: none;">
                        <li class="dropdown">
                            <a href="{{ route('admin.products.index') }}" class="waves-effect waves-light btn btn-outline btn-rounded btn-primary btn-sm">View All</a>
                        </li>
                    </ul>
                </div>
                <div class="box-body">				  
                    <div class="owl-carousel owl-theme" id="owl-carousel-13">
                        @if($recentProducts > 0)
                            @foreach(\App\Models\Product::latest()->take(10)->get() as $product)
                                <div class="box mb-0">
                                    @if($product->images->count() > 0)
                                        <img class="card-img-top img-responsive" src="{{ asset($product->images->first()->image_path) }}" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <img class="card-img-top img-responsive" src="https://via.placeholder.com/300x200?text=No+Image" alt="No image" style="height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="p-3">
                                        <h6 class="mb-1">{{ Str::limit($product->name, 30) }}</h6>
                                        <p class="text-muted mb-0">${{ number_format($product->price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center p-4">
                                <p class="text-muted">No recent products</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>  
        </div> 
    </div>

    <div class="row">
        <!-- Stock Status -->
        <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-12">
            <div class="card rounded-4">
                <div class="box-header">
                    <h4 class="box-title">Inventory Status</h4>
                </div>
                <div class="card-body p-5">
                    <div class="chart">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-6">
                                    <div class="bg-danger-light p-3 rounded mb-3">
                                        <h3 class="text-danger">{{ number_format($lowStockProducts) }}</h3>
                                        <small class="text-muted">Low Stock</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-warning-light p-3 rounded mb-3">
                                        <h3 class="text-warning">{{ number_format($outOfStockProducts) }}</h3>
                                        <small class="text-muted">Out of Stock</small>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-success-light p-3 rounded">
                                <h3 class="text-success">{{ number_format($totalVendorListingsWithStock) }}</h3>
                                <small class="text-muted">In Stock Listings</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Today's Sales Summary -->
        <div class="col-xxxl-8 col-xxl-8 col-xl-8 col-lg-12">
            <div class="box rounded-4">
                <div class="box-header d-flex b-0 justify-content-between align-items-center pb-0">
                    <h4 class="box-title">Today's Performance</h4>
                    <ul class="m-0" style="list-style: none;">
                        <li class="dropdown">
                            <button class="waves-effect waves-light btn btn-outline btn-rounded btn-primary btn-sm"><i class="fa fa-fw fa-refresh"></i> Refresh</button>
                        </li>
                    </ul>
                </div>
                <div class="box-body pt-0 summery-box">
                    <p class="mb-20 text-fade">Daily Performance Summary</p>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="box pull-up mb-sm-0 bg-primary-light">
                                <div class="box-body">
                                    <div class="w-50 h-50 bg-primary rounded-circle text-center"> 
                                        <i class="fa fa-dollar fs-18 l-h-50"></i>
                                    </div>
                                    <h2 class="fw-600 mt-3">${{ number_format($todayRevenue) }}</h2>
                                    <p class="text-fade fw-500 mb-2">Today's Revenue</p>
                                    <p class="mb-0 text-primary">From {{ $recentOrders }} orders</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="box pull-up mb-sm-0 bg-success-light">
                                <div class="box-body">
                                    <div class="w-50 h-50 bg-success rounded-circle text-center"> 
                                        <i class="fa fa-shopping-cart fs-18 l-h-50"></i>
                                    </div>
                                    <h2 class="fw-600 mt-3">{{ number_format($recentOrders) }}</h2>
                                    <p class="text-fade fw-500 mb-2">Recent Orders</p>
                                    <p class="mb-0 text-success">Last 7 days</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="box pull-up mb-0 bg-info-light">
                                <div class="box-body">
                                    <div class="w-50 h-50 bg-info rounded-circle text-center"> 
                                        <i class="fa fa-cube fs-18 l-h-50"></i>
                                    </div>
                                    <h2 class="fw-600 mt-3">{{ number_format($recentProducts) }}</h2>
                                    <p class="text-fade fw-500 mb-2">New Products</p>
                                    <p class="mb-0 text-info">Last 7 days</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="box pull-up mb-0 bg-warning-light">
                                <div class="box-body">
                                    <div class="w-50 h-50 bg-warning rounded-circle text-center"> 
                                        <i class="fa fa-users fs-20 l-h-50"></i>
                                    </div>
                                    <h2 class="fw-600 mt-3">{{ number_format($totalPendingVendors) }}</h2>
                                    <p class="text-fade fw-500 mb-2">Pending Vendors</p>
                                    <p class="mb-0 text-warning">Awaiting approval</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row">
        <!-- Order Status Breakdown -->
        {{-- <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-6">
            <div class="card rounded-4">
                <div class="box-header b-0">
                    <h4 class="box-title">Order Status</h4>
                </div>
                <div class="card-body p-5">
                    <div class="chart">
                        <div class="text-center">
                            <div class="mb-3">
                                <h4 class="text-primary">{{ $completedOrders }}</h4>
                                <small class="text-muted">Completed</small>
                            </div>
                            <div class="mb-3">
                                <h4 class="text-warning">{{ $processingOrders }}</h4>
                                <small class="text-muted">Processing</small>
                            </div>
                            <div>
                                <h4 class="text-danger">{{ $pendingOrders }}</h4>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         --}}
          <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-6">
            <div class="card rounded-4">
                <div class="box-header b-0">
                    <h4 class="box-title"> <h4 class="box-title">Order Status</h4></h4>
                </div>
                <div class="card-body p-0">
                    <div class="chart">
                        <div class="row text-center p-4">
                            <div class="col-4 border-end">
                                <h3 class="text-success">{{ $completedOrders }}</h3>
                                <small class="text-muted">Completed</small>
                            </div>
                            <div class="col-4 border-end">
                                <h3 class="text-warning">{{ $processingOrders }}</h3>
                                <small class="text-muted">Processing</small>
                            </div>
                            <div class="col-4">
                                <h3 class="text-warning">{{ $pendingOrders }}</h3>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendor Statistics -->
        <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-6">
            <div class="card rounded-4">
                <div class="box-header b-0">
                    <h4 class="box-title">Vendor Overview</h4>
                </div>
                <div class="card-body p-0">
                    <div class="chart">
                        <div class="row text-center p-4">
                            <div class="col-6 border-end">
                                <h3 class="text-success">{{ $totalApprovedVendors }}</h3>
                                <small class="text-muted">Approved</small>
                            </div>
                            <div class="col-6">
                                <h3 class="text-warning">{{ $totalPendingVendors }}</h3>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="row border-top">
                            <div class="col-12 p-0 text-center">
                                <p class="mb-0 p-3">
                                    <i class="fa fa-store text-primary"></i> 
                                    Total Vendors: {{ $totalVendors }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="col-xxxl-4 col-xxl-4 col-xl-4 col-lg-12">
            <div class="card rounded-4">
                <div class="box-header b-0">
                    <h4 class="box-title">Quick Stats</h4>
                </div>
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-12 be-1">
                            <div class="box-body rounded-0 px-15 pb-5 pt-0 fill-icon">
                                <div class="d-flex align-items-center">
                                    <div class="w-70 h-70 me-15 bg-success-light rounded-circle text-center p-10">
                                        <div class="w-50 h-50 bg-success rounded-circle">
                                            <i class="fa fa-check fs-24 l-h-50"></i>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-fade fs-12">Active Products</span>
                                        <h2 class="text-dark hover-primary m-0 fw-600">{{ number_format($totalActiveProducts) }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-12">
                            <div class="box-body rounded-0 px-15 pb-5 pt-0 fill-icon">
                                <div class="d-flex align-items-center">
                                    <div class="w-70 h-70 me-15 bg-info-light rounded-circle text-center p-10">
                                        <div class="w-50 h-50 bg-info rounded-circle">
                                            <i class="fa fa-bar-chart fs-20 l-h-50"></i>
                                        </div>		
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-fade fs-12">Listings with Stock</span>
                                        <h2 class="text-dark hover-primary m-0 fw-600">{{ number_format($totalVendorListingsWithStock) }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Row -->
    <div class="row">
        <!-- Recent Orders -->
        <div class="col-xxl-6 col-xl-6 col-lg-6">
            <div class="card rounded-4">
                <div class="box-header d-flex b-0 justify-content-between align-items-center">
                    <h4 class="box-title">Recent Orders</h4>
                    <p class="text-primary m-0">+{{ $recentOrders }} new</p>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                @foreach(\App\Models\Order::latest()->take(5)->get() as $order)
                                <tr>
                                    <td class="pt-0 px-0 b-0 w-50">
                                        <div class="w-40 h-40 bg-light rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa fa-user text-primary"></i>
                                        </div>
                                    </td>
                                    <td class="pt-0 px-0 b-0">
                                        <a class="d-block fw-500 fs-14" href="#">{{ $order->customer_name }}</a>
                                        <span class="text-fade">Order #{{ $order->order_number }}</span>
                                    </td>
                                    <td class="text-end b-0 pt-0 px-0">
                                        @php
                                            $statusClass = [
                                                'pending' => 'btn-warning',
                                                'processing' => 'btn-info', 
                                                'completed' => 'btn-success',
                                                'delivered' => 'btn-primary',
                                                'cancelled' => 'btn-danger'
                                            ][$order->order_status] ?? 'btn-secondary';
                                        @endphp
                                        <button class="waves-effect waves-light btn btn-outline btn-rounded {{ $statusClass }} mb-0 btn-sm">
                                            {{ ucfirst($order->order_status) }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>	
            </div>
        </div>
        
        <!-- Recent Products -->
        <div class="col-xxl-6 col-xl-6 col-lg-6">
            <div class="card rounded-4">
                <div class="box-header d-flex b-0 justify-content-between align-items-center">
                    <h4 class="box-title">Recent Products</h4>
                    <ul class="box-controls pull-right">
                        <li class="dropdown">
                            <a data-bs-toggle="dropdown" href="#" class="px-10 pt-1" aria-expanded="false"><i class="ti-more-alt"></i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('admin.products.create') }}"><i class="ti-plus"></i> Add New</a>
                                <a class="dropdown-item" href="{{ route('admin.products.index') }}"><i class="ti-list"></i> View All</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                @foreach(\App\Models\Product::latest()->take(5)->get() as $product)
                                <tr>
                                    <td class="pt-0 px-0 b-0 w-50">
                                        @if($product->images->count() > 0)
                                            <img class="img-fluid img-40 rounded bg-light p-1" src="{{ asset($product->images->first()->image_path) }}" alt="{{ $product->name }}">
                                        @else
                                            <div class="w-40 h-40 bg-light rounded d-flex align-items-center justify-content-center">
                                                <i class="fa fa-cube text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="pt-0 px-0 b-0">
                                        <a class="d-block fw-500 fs-14" href="#">{{ Str::limit($product->name, 25) }}</a>
                                        <span class="text-fade">${{ number_format($product->price, 2) }}</span>
                                    </td>
                                    <td class="text-end b-0 pt-0 px-0">
                                        <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>	
            </div>
        </div>		
    </div>

    @push('scripts')
    <script>
    $(document).ready(function() {
        // Initialize Owl Carousel for products
        $('#owl-carousel-13').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            responsive: {
                0: { items: 1 },
                600: { items: 3 },
                1000: { items: 5 }
            }
        });

        // Refresh stats functionality
        $('.btn-refresh').on('click', function() {
            const button = $(this);
            const originalHtml = button.html();
            
            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            
            setTimeout(function() {
                location.reload();
            }, 1000);
        });
    });
    </script>
    @endpush
@endsection